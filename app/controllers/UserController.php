<?php
class UserController
{
    public static function tools()
    {
        AuthController::checkRole('user');

        $tools = Tool::getAllAvailable();
        require __DIR__.'/../views/user/tools/index.php';
    }

    public static function rentForm()
    {
        AuthController::checkRole('user');

        $tool = Tool::find($_GET['id']);
        require __DIR__.'/../views/user/rent/create.php';
    }

    public static function submitRent()
    {
        AuthController::checkRole('user');

        $db = Database::connect();
        $db->beginTransaction();

        try {

            $user_id   = $_SESSION['user']['id'];
            $tool_id   = $_POST['tool_id'];
            $quantity  = $_POST['quantity'];
            $start_date = $_POST['start_date'];
            $end_date   = $_POST['end_date'];

            // 🔵 VALIDASI TANGGAL
        $today = new DateTime('today');

        $start = DateTime::createFromFormat('Y-m-d', $start_date);
        $end   = DateTime::createFromFormat('Y-m-d', $end_date);

        if (!$start || !$end) {
            throw new Exception("Format tanggal tidak valid");
        }


            if ($start < $today) {
                throw new Exception("Tanggal sewa tidak boleh sebelum hari ini");
            }

            if ($end < $start) {
                throw new Exception("Tanggal kembali tidak boleh sebelum tanggal sewa");
            }

            // 🔵 Ambil data tool
            $tool = Tool::find($tool_id);

            if (!$tool) {
                throw new Exception("Alat tidak ditemukan");
            }

            if ($quantity > $tool['stock']) {
                throw new Exception("Stok tidak mencukupi");
            }

            // 🔵 Cek apakah sudah ada rental pending dengan tanggal sama
            $stmt = $db->prepare("
                SELECT id FROM rentals
                WHERE user_id = ?
                AND start_date = ?
                AND end_date = ?
                AND status = 'pending'
            ");
            $stmt->execute([$user_id, $start_date, $end_date]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $rental_id = $existing['id'];
            } else {
                // Buat rental baru
                $stmt = $db->prepare("
                    INSERT INTO rentals (user_id, start_date, end_date, status)
                    VALUES (?, ?, ?, 'pending')
                ");
                $stmt->execute([$user_id, $start_date, $end_date]);
                $rental_id = $db->lastInsertId();
            }

            // 🔵 Insert ke rental_details
            $stmtDetail = $db->prepare("
                INSERT INTO rental_details (rental_id, tool_id, quantity, price_per_day)
                VALUES (?, ?, ?, ?)
            ");

            $stmtDetail->execute([
                $rental_id,
                $tool_id,
                $quantity,
                $tool['price_per_day']
            ]);

            $db->commit();

            header('Location: index.php?page=user-rentals');
            exit;

        } catch (Exception $e) {
            $db->rollBack();
            echo "Error: " . $e->getMessage();
            die;
        }
    }

    public static function rentals()
    {
        AuthController::checkRole('user');

        $rentals = Rental::getByUser($_SESSION['user']['id']);
        require __DIR__.'/../views/user/rent/index.php';
    }

    public static function index()
    {
        AuthController::checkRole('user');
        $rentals = Rental::getByUser($_SESSION['user']['id']);
        require '../app/views/user/rent/index.php';
    }


    public static function requestReturn()
    {
        AuthController::checkRole('user');

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=user-rentals');
            exit;
        }
        Rental::updateStatus($id, 'returned_request');

        header('Location: index.php?page=user-rentals');
        exit;
    }

    public static function cancel()
    {
        AuthController::checkRole('user');

        $id = $_GET['id'] ?? null;

        $db = Database::connect();
        $stmt = $db->prepare("
            UPDATE rentals
            SET status = 'cancelled'
            WHERE id = ? AND user_id = ? AND status = 'pending'
        ");

        $stmt->execute([$id, $_SESSION['user']['id']]);

        header("Location: index.php?page=user-rentals");
        exit;
    }

    public static function cancelItem()
    {
        AuthController::checkRole('user');

        $detail_id = $_GET['id'] ?? null;
        if (!$detail_id) die("Detail ID tidak ditemukan");

        $db = Database::connect();

        $stmt = $db->prepare("
            UPDATE rental_details
            SET status = 'cancelled'
            WHERE id = ?
            AND status = 'pending'
        ");

        $stmt->execute([$detail_id]);

        header("Location: index.php?page=user-rentals");
        exit;
    }

}
