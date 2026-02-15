<?php

require_once '../app/models/User.php';
require_once '../app/models/Category.php';

class AdminController
{
    public static function users()
    {
        AuthController::checkRole('admin');
        $users = User::getAll();
        require '../app/views/admin/user.php';
    }

    public static function addUser()
    {
        AuthController::checkRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            User::create($_POST);
            header('Location: index.php?page=users');
            exit;
        }

        require '../app/views/admin/add_user.php';
    }

    public static function deleteUser()
    {
        AuthController::checkRole('admin');
        User::delete($_GET['id']);
        header('Location: index.php?page=users');
    }
        public static function categories()
    {
        AuthController::checkRole('admin');
        $categories = Category::getAll();
        require '../app/views/admin/categories/index.php';
    }
    
    //category

    public static function addCategory()
    {
        AuthController::checkRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Category::existsByName($_POST['category_name'])) {
                $_SESSION['error'] = 'Kategori sudah ada';
                header('Location: index.php?page=add-category');
                exit;
            }

            Category::create($_POST);

            header('Location: index.php?page=categories');
            exit;
        }

        require __DIR__ . '/../views/admin/categories/create.php';
    }

    public static function editCategory()
    {
        AuthController::checkRole('admin');

        $category = Category::find($_GET['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Category::update($_GET['id'], $_POST);
            header('Location: index.php?page=categories');
            exit;
        }

        require '../app/views/admin/categories/edit.php';
    }

    public static function deleteCategory()
    {
        AuthController::checkRole('admin');

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=categories');
            exit;
        }

        if (Category::isUsedByTools($id)) {
            $_SESSION['error'] = 'Kategori masih digunakan oleh alat';
            header('Location: index.php?page=categories');
            exit;
        }

        Category::delete($id);

        header('Location: index.php?page=categories');
        exit;
    }

    //tools
    public static function tools()
    {
        AuthController::checkRole('admin');

        $tools = Tool::getAll();
        require __DIR__ . '/../views/admin/tools/index.php';
    }

    public static function addTool()
    {
        AuthController::checkRole('admin');

        $categories = Category::getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Tool::create($_POST);
            header('Location: index.php?page=tools');
            exit;
        }

        require __DIR__ . '/../views/admin/tools/create.php';
    }

    public static function editTool()
    {
        AuthController::checkRole('admin');

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?page=tools');
            exit;
        }

        $tool = Tool::find($id);
        $categories = Category::getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Tool::update($id, $_POST);
            header('Location: index.php?page=tools');
            exit;
        }

        require __DIR__ . '/../views/admin/tools/edit.php';
    }

    public static function deleteTool($id)
    {
        AuthController::checkRole('admin');


            Tool::delete($id);


        header('Location: index.php?page=tools');
        exit;
    }
    
}
