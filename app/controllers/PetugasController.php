<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Tool.php';
require_once __DIR__ . '/../models/Rental.php';
require_once __DIR__ . '/../models/RentalDetail.php';

class PetugasController
{
    public static function tools()
    {
        AuthController::checkRole('petugas');
        $tools = Tool::getAll();
        require __DIR__ . '/../views/petugas/tools.php';
    }

    public static function transactions()
    {
        AuthController::checkRole('petugas');
        $rentals = Rental::getAllWithUser();
        require __DIR__ . '/../views/petugas/transactions/index.php';
    }

    //public static function returns()
    //{
    //    AuthController::checkRole('petugas');
    //    $returns = ReturnModel::getAll();
    //    require __DIR__ . '/../views/petugas/returns.php';
    //}
     public static function createSewa()
    {
        AuthController::checkRole('petugas');

        $users = User::getAll();     // hanya untuk dipilih
        $tools = Tool::getAll();     // stok ditampilkan

        require __DIR__ . '/../views/petugas/sewa/create.php';
    }

    public static function storeSewa()
    {
        AuthController::checkRole('petugas');

        $db = Database::connect();
        $db->beginTransaction();

        try {
            // 1. insert rentals
            $rentalId = Rental::create([
                'user_id'    => $_POST['user_id'],
                'start_date' => $_POST['start_date'],
                'end_date'   => $_POST['end_date'],
                'status'     => 'dipinjam'
            ]);

            // 2. insert rental_details
            //RentalDetail::create([
            //    'rental_id'    => $rentalId,
            //    'tool_id'      => $_POST['tool_id'],
            //    'quantity'     => $_POST['quantity'],
            //    'price_per_day'=> $_POST['price_per_day']
            //]);

            // 3. update stok
            Tool::reduceStock($_POST['tool_id'], $_POST['quantity']);

            $db->commit();

            header('Location: index.php?page=petugas-dashboard');
            exit;

        } catch (Exception $e) {
            $db->rollBack();
            echo "Gagal menyimpan transaksi";
        }
    }

    //pengembalian
    public static function approveReturn()
    {
        AuthController::checkRole('petugas');

        $rentalId = $_POST['rental_id'] ?? null;
        $condition = $_POST['condition_return'] ?? 'baik';
        $fine = $_POST['fine'] ?? 0;

        if (!$rentalId) {
            echo "Rental tidak valid";
            exit;
        }

        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            // 1️⃣ Validasi rental
            $stmt = $db->prepare("SELECT * FROM rentals WHERE id = ?");
            $stmt->execute([$rentalId]);
            $rental = $stmt->fetch();

            if (!$rental || !in_array($rental['status'], ['approved','return_requested'])) {
                throw new Exception("Rental tidak bisa dikembalikan");
            }

            // 2️⃣ Cek sudah pernah return
            $stmt = $db->prepare("SELECT id FROM returns WHERE rental_id = ?");
            $stmt->execute([$rentalId]);
            if ($stmt->fetch()) {
                throw new Exception("Sudah pernah dikembalikan");
            }

            // 3️⃣ Insert ke returns
            $stmt = $db->prepare("
                INSERT INTO returns (rental_id, return_date, condition_return, fine)
                VALUES (?, CURDATE(), ?, ?)
            ");
            $stmt->execute([$rentalId, $condition, $fine]);

            // 4️⃣ Update rental status
            $stmt = $db->prepare("
                UPDATE rentals SET status = 'returned' WHERE id = ?
            ");
            $stmt->execute([$rentalId]);

            // 5️⃣ Update semua tools jadi available
            $stmt = $db->prepare("
                UPDATE tools t
                JOIN rental_details rd ON rd.tool_id = t.id
                SET t.availability_status = 'available'
                WHERE rd.rental_id = ?
            ");
            $stmt->execute([$rentalId]);

            $db->commit();

            header("Location: index.php?controller=petugas&action=returns&success=1");

        } catch (Exception $e) {
            $db->rollBack();
            echo $e->getMessage();
        }
    }

    public static function returns()
    {
        AuthController::checkRole('petugas');
    
        $rentals = Rental::getForReturn();
    
        require __DIR__.'/../views/petugas/returns/index.php';
    }

    public static function approveReturnForm()
    {
        AuthController::checkRole('petugas');
    
        $rentalId = $_GET['id'] ?? null;
        if (!$rentalId) {
            echo "Rental tidak ditemukan";
            exit;
        }
    
        require __DIR__.'/../views/petugas/returns/approve.php';
    }

    public static function approve()
    {
       AuthController::checkRole('petugas');

       $detail_id = $_GET['id'] ?? null;
       if (!$detail_id) die("Detail ID tidak ditemukan");

       $db = Database::connect();
       $db->beginTransaction();

       try {

           // Ambil detail
           $stmt = $db->prepare("
               SELECT * FROM rental_details WHERE id = ?
           ");
           $stmt->execute([$detail_id]);
           $detail = $stmt->fetch(PDO::FETCH_ASSOC);

           if (!$detail) throw new Exception("Detail tidak ditemukan");
           if ($detail['status'] !== 'pending')
               throw new Exception("Item bukan status pending");

           // Cek stok
           $tool = Tool::find($detail['tool_id']);
           if ($detail['quantity'] > $tool['stock'])
               throw new Exception("Stok tidak cukup");

           // Kurangi stok
           $stmtUpdateStock = $db->prepare("
               UPDATE tools SET stock = stock - ?
               WHERE id = ?
           ");
           $stmtUpdateStock->execute([
               $detail['quantity'],
               $detail['tool_id']
           ]);

           // Update status detail
           $stmtUpdate = $db->prepare("
               UPDATE rental_details
               SET status = 'approved'
               WHERE id = ?
           ");
           $stmtUpdate->execute([$detail_id]);

           $db->commit();

           header("Location: index.php?page=petugas-transactions");
           exit;

       } catch (Exception $e) {
           $db->rollBack();
           die("Error: " . $e->getMessage());
       }
    }

    public static function detail()
    {
        AuthController::checkRole('petugas');

        if (!isset($_GET['id'])) {
            die('ID tidak ditemukan');
        }

        $rental_id = $_GET['id'];

        $rental = Rental::find($rental_id);
        $details = RentalDetail::getByRental($rental_id);

        require __DIR__.'/../views/petugas/transactions/detail.php';
    }

    public static function approveRental()
    {
        AuthController::checkRole('petugas');
    
        $rental_id = $_POST['id'] ?? null;
    
        if (!$rental_id) {
            die("Rental ID tidak ditemukan");
        }
    
        $db = Database::connect();
        $db->beginTransaction();
    
        try {
    
            // Cek status transaksi
            $stmt = $db->prepare("SELECT status FROM rentals WHERE id = ?");
            $stmt->execute([$rental_id]);
            $rental = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$rental || $rental['status'] !== 'pending') {
                throw new Exception("Transaksi bukan status pending");
            }
    
            // Ambil semua item dalam transaksi
            $stmt = $db->prepare("
                SELECT tool_id, quantity 
                FROM rental_details 
                WHERE rental_id = ?
            ");
            $stmt->execute([$rental_id]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // 🔥 DI SINI FOREACH-NYA
            foreach ($items as $item) {
    
                // cek stock
                $stmt = $db->prepare("SELECT stock FROM tools WHERE id = ?");
                $stmt->execute([$item['tool_id']]);
                $current = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($current['stock'] < $item['quantity']) {
                    throw new Exception("Stock tidak mencukupi");
                }
    
                // kurangi stock
                $stmt = $db->prepare("
                    UPDATE tools 
                    SET stock = stock - ? 
                    WHERE id = ?
                ");
                $stmt->execute([$item['quantity'], $item['tool_id']]);
            }
    
            // Update status rental
            $stmt = $db->prepare("
                UPDATE rentals 
                SET status = 'approved' 
                WHERE id = ?
            ");
            $stmt->execute([$rental_id]);
    
            $db->commit();
    
        } catch (Exception $e) {
            $db->rollBack();
            die($e->getMessage());
        }
    
        header("Location: index.php?page=petugas-transactions");
        exit;
    }

    public static function reject()
    {
        AuthController::checkRole('petugas');

        $id = $_GET['id'] ?? null;
        if (!$id) die('ID tidak ditemukan');

        $db = Database::connect();
        $stmt = $db->prepare("
            UPDATE rentals 
            SET status = 'rejected'
            WHERE id = ? AND status = 'pending'
        ");

        $stmt->execute([$id]);

        header("Location: index.php?page=petugas-transactions");
        exit;
    }
    
}