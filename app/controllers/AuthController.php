<?php

class AuthController
{
    public static function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::connect();

            $stmt = $db->prepare(
                "SELECT users.*, roles.role_name 
                 FROM users 
                 JOIN roles ON users.role_id = roles.id 
                 WHERE username = ?"
            );

            $stmt->execute([$_POST['username']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'role' => $user['role_name'],
                    'name' => $user['name']
                ];

                header('Location: index.php?page=admin-dashboard');
                exit;
            }
        }

        require '../app/views/auth/login.php';
    }

    public static function checkRole($role)
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $role) {
            header('Location: index.php?page=login');
            exit;
        }
    }
}
