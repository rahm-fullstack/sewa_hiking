<?php
class Rental
{
    public static function create($data)
    {
        $db = Database::connect();

        $status = $data['status'] ?? 'pending';
        $petugasId = $data['petugas_id'] ?? null;

        $stmt = $db->prepare(
            "INSERT INTO rentals 
            (user_id, tool_id, quantity, start_date, end_date, status, petugas_id, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"
        );

        return $stmt->execute([
            $data['user_id'],
            $data['tool_id'],
            $data['quantity'],
            $data['start_date'],
            $data['end_date'],
            $status,
            $petugasId
        ]);
    }

    public static function getAllWithUser()
    {
        $db = Database::connect();

        $sql = "
            SELECT 
                r.id,
                u.name AS user_name,
                r.start_date,
                r.end_date,
                r.status
            FROM rentals r
            JOIN users u ON r.user_id = u.id
            ORDER BY r.created_at DESC
        ";

        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getForReturn()
    {
        $db = Database::connect();

        $sql = "
            SELECT r.id, r.start_date, r.end_date,
                   u.name AS user_name
            FROM rentals r
            JOIN users u ON r.user_id = u.id
            WHERE r.status = 'approved'
            ORDER BY r.id DESC
        ";

        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function markReturned($rentalId)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "UPDATE rentals SET status = 'returned' WHERE id = ?"
        );
        return $stmt->execute([$rentalId]);
    }


    public static function getByUser($user_id)
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            SELECT 
                r.id,
                r.start_date,
                r.end_date,
                r.status,
                rd.quantity,
                t.tool_name,
                rd.price_per_day
            FROM rentals r
            JOIN rental_details rd ON r.id = rd.rental_id
            JOIN tools t ON rd.tool_id = t.id
            WHERE r.user_id = ?
            ORDER BY r.id DESC
        ");

        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id)
    {   
    $db = Database::connect();

    $stmt = $db->prepare("
        SELECT r.*, u.name AS user_name
        FROM rentals r
        JOIN users u ON r.user_id = u.id
        WHERE r.id = ?
        LIMIT 1
    ");

    $stmt->execute([$id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updateStatus($id, $status)
    {
    $db = Database::connect();

    $stmt = $db->prepare("
        UPDATE rentals
        SET status = ?
        WHERE id = ?
    ");

    return $stmt->execute([$status, $id]);
    }

    public static function getDetails($rental_id)
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            SELECT 
                rd.id,
                rd.quantity,
                rd.price_per_day,
                t.tool_name,
                t.id as tool_id
            FROM rental_details rd
            JOIN tools t ON rd.tool_id = t.id
            WHERE rd.rental_id = ?
        ");

        $stmt->execute([$rental_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function transactions()
    {
        $db = Database::connect();

        $sql = "
            SELECT
                r.id AS rental_id,
                u.name AS user_name,
                GROUP_CONCAT(CONCAT(t.tool_name, ' (', rd.quantity, ')') SEPARATOR ', ') AS tools,
                SUM(rd.quantity * rd.price_per_day) AS total_price,
                r.start_date,
                r.end_date,
                r.status
            FROM rentals r
            JOIN users u ON r.user_id = u.id
            JOIN rental_details rd ON rd.rental_id = r.id
            JOIN tools t ON rd.tool_id = t.id
            GROUP BY r.id
            ORDER BY r.created_at DESC
        ";

        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

}
