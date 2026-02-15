<?php

class Tool
{
    public static function getAll()
    {
        $db = Database::connect();

        $stmt = $db->query("
            SELECT tools.*, categories.category_name
            FROM tools
            LEFT JOIN categories 
                ON tools.category_id = categories.id
            WHERE tools.is_active = 1
            ORDER BY tools.id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO tools 
            (category_id, tool_name, stock, price_per_day)
            VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['category_id'],
            $data['tool_name'],
            $data['stock'],
            $data['price_per_day']
        ]);
    }

    public static function find($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "SELECT * FROM tools WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $data)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "UPDATE tools
             SET category_id = ?, tool_name = ?, stock = ?, price_per_day = ?
             WHERE id = ?"
        );
        return $stmt->execute([
            $data['category_id'],
            $data['tool_name'],
            $data['stock'],
            $data['price_per_day'],
            $id
        ]);
    }

    public static function isUsedInRental($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "SELECT COUNT(*) FROM rental_details WHERE tool_id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare("
            UPDATE tools
            SET is_active = 0
            WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }
        public static function reduceStock($toolId, $qty)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "UPDATE tools SET stock = stock - ? WHERE id = ? AND stock >= ?"
        );

        return $stmt->execute([$qty, $toolId, $qty]);
    }
    
    public static function getAllAvailable()
        {
            $db = Database::connect();
        
            $stmt = $db->query("
                SELECT tools.*, categories.category_name
                FROM tools
                LEFT JOIN categories 
                    ON tools.category_id = categories.id
                WHERE tools.stock > 0
                AND tools.is_active = 1
                ORDER BY tools.id DESC
            ");
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

}
