<?php

require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class CategoryController
{
    public static function index()
    {
        $category = Category::getAll();
        require __DIR__.'/../views/admin/categories/index.php';
    }

    public static function create()
    {
        require __DIR__.'/../views/admin/categories/create.php';
    }

    public static function store()
    {
        if (Category::existsByName($_POST['category_name'])) {
            $_SESSION['error'] = 'Kategori sudah ada';
            header('Location: add-category');
            exit;
        }

        Category::create($_POST);
        ActivityLog::create($_SESSION['user']['id'], 'Menambah kategori');

        header('Location: categories');
    }

    public static function edit($id)
    {
        $category = Category::find($id);
        require __DIR__. '/../views/admin/categories/edit.php';
    }

    public static function update($id)
    {
        Category::update($id, $_POST);
        ActivityLog::create($_SESSION['user']['id'], 'Update kategori');

        header('Location: categories');
    }

    public static function delete($id)
    {
        if (Category::isUsedByTools($id)) {
            $_SESSION['error'] = 'Kategori masih digunakan';
            header('Location: categories');
            exit;
        }

        Category::delete($id);
        ActivityLog::create($_SESSION['user']['id'], 'Hapus kategori');

        header('Location: categories');
    }
}
