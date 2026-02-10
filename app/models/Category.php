<?php

class Category
{
    public static function getAll()
    {
        $db = Database::connect();
        return $db->query(
            "SELECT * FROM categories ORDER BY id DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO categories (category_name, description)
             VALUES (?, ?)"
        );
        return $stmt->execute([
            $data['category_name'],
            $data['description']
        ]);
    }

    public static function find($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "SELECT * FROM categories WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $data)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "UPDATE categories
             SET category_name = ?, description = ?
             WHERE id = ?"
        );
        return $stmt->execute([
            $data['category_name'],
            $data['description'],
            $id
        ]);
    }
    public static function isUsedByTools($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "SELECT COUNT(*) FROM tools WHERE category_id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
    
    public static function existsByName($name)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "SELECT COUNT(*) FROM categories WHERE category_name = ?"
        );
        $stmt->execute([$name]);
        return $stmt->fetchColumn() > 0;
    }

    public static function delete($id)
    {
        $db = Database::connect();
        $stmt = $db->prepare(
            "DELETE FROM categories WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }
}
