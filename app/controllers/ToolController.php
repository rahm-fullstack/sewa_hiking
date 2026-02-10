<?php
require_once __DIR__ . '/../models/Tool.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/ActivityLog.php';

class ToolController
{
    public static function index()
    {
        $tools = Tool::getAll();
        require __DIR__.'/../views/admin/tools/index.php';
    }

    public static function create()
    {
        $categories = Category::getAll();
        require __DIR__.'/../views/admin/tools/create.php';
    }

    public static function store()
    {
        if ($_POST['stock'] < 0 || $_POST['price_per_day'] < 0) {
            $_SESSION['error'] = 'Stok dan harga tidak boleh negatif';
            header('Location: add-tool');
            exit;
        }

        Tool::create($_POST);
        ActivityLog::create($_SESSION['user']['id'], 'Menambah alat');

        header('Location: tools');
    }

    public static function edit($id)
    {
        $tool = Tool::find($id);
        $categories = Category::getAll();
        require __DIR__.'/../views/admin/tools/edit.php';
    }

    public static function update($id)
    {
        Tool::update($id, $_POST);
        ActivityLog::create($_SESSION['user']['id'], 'Update alat');

        header('Location: tools');
    }

    public static function delete($id)
    {
        if (Tool::isUsedInRental($id)) {
            $_SESSION['error'] = 'Alat sudah pernah disewa';
            header('Location: tools');
            exit;
        }

        Tool::delete($id);
        ActivityLog::create($_SESSION['user']['id'], 'Hapus alat');

        header('Location: tools');
    }
}
