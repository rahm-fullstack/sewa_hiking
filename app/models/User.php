<?php

class User
{
    public static function getAll()
    {
        $db = Database::connect();
        return $db->query(
            "SELECT users.*, roles.role_name
             FROM users
             JOIN roles ON users.role_id = roles.id"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO users (role_id, name, username, password)
             VALUES (?, ?, ?, ?)"
        );

        return $stmt->execute([
            $data['role_id'],
            $data['name'],
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
    }

    public static function delete($id)
    {
        $db = Database::connect();

        $stmt = $db->prepare(
            "SELECT COUNT(*) FROM rentals WHERE user_id = ?"
        );
        $stmt->execute([$id]);

        if ($stmt->fetchColumn() > 0) {
            return false; // user pernah transaksi
        }

        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
}
