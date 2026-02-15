<?php

class RentalDetail
{
    public static function create($data)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "INSERT INTO rental_details (rental_id, tool_id, quantity, price_per_day)
             VALUES (?, ?, ?, ?)"
        );

        return $stmt->execute([
            $data['rental_id'],
            $data['tool_id'],
            $data['quantity'],
            $data['price_per_day']
        ]);
    }

    public static function getByRental($rental_id)
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            SELECT rd.*, t.tool_name
            FROM rental_details rd
            JOIN tools t ON rd.tool_id = t.id
            WHERE rd.rental_id = ?
        ");

        $stmt->execute([$rental_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
