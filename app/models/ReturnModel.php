<?php

class ReturnModel
{
    public static function create($data)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO returns 
            (rental_id, return_date, condition_return, fine, petugas_id)
            VALUES (?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $data['rental_id'],
            $data['return_date'],
            $data['condition_return'],
            $data['fine'],
            $data['petugas_id']
        ]);
    }
}
