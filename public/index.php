<?php
session_start();

// tampilkan error untuk development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// load core
require_once '../app/models/Database.php';

// load controllers
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/AdminController.php';
require_once '../app/controllers/CategoryController.php'; // tambahkan CategoryController
require_once '../app/controllers/ToolController.php';
require_once '../app/controllers/ReportController.php';

// ROUTING
$page = $_GET['page'] ?? 'login';

switch ($page) {

    // AUTH
    case 'login':
        AuthController::login();
        break;

    // ADMIN DASHBOARD
    case 'admin-dashboard':
        // cek session role admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        require '../app/views/admin/dashboard.php';
        break;

    // USERS
    case 'users':
        AdminController::users();
        break;

    case 'add-user':
        AdminController::addUser();
        break;

    case 'delete-user':
        AdminController::deleteUser();
        break;

    // CATEGORIES
    case 'categories':
        CategoryController::index();
        break;

    case 'add-category':
        AdminController::addCategory();
        break;

    case 'edit-category':
        $id = $_GET['id'] ?? null;
        if (!$id) { echo "ID kategori tidak ditemukan"; exit; }
        AdminController::editCategory($id);
        break;

    case 'store-category':
        CategoryController::store();
        break;

    case 'update-category':
        $id = $_GET['id'] ?? null;
        if (!$id) { echo "ID kategori tidak ditemukan"; exit; }
        CategoryController::update($id);
        break;

    case 'delete-category':
        $id = $_GET['id'] ?? null;
        if (!$id) { echo "ID kategori tidak ditemukan"; exit; }
        AdminController::deleteCategory($id);
        break;

    // TOOLS
    case 'tools':
        AdminController::tools();
        break;

    case 'add-tool':
        AdminController::addTool();
        break;

    case 'store-tool':
        ToolController::store();
        break;

    case 'edit-tool':
        $id = $_GET['id'] ?? null;
        if (!$id) { echo "ID alat tidak ditemukan"; exit; }
        AdminController::editTool($id);
        break;

    case 'update-tool':
        $id = $_GET['id'] ?? null;
        if (!$id) { echo "ID alat tidak ditemukan"; exit; }
        ToolController::update($id);
        break;

    case 'delete-tool':
        $id = $_GET['id'] ?? null;
        if (!$id) { echo "ID alat tidak ditemukan"; exit; }
        AdminController::deleteTool($id);
        break;

    //REPORT
    case 'reports':
        ReportController::index();
        break;

    case 'report-transactions':
        ReportController::transactions();
        break;
        
    case 'report-tools':
        ReportController::tools();
        break;
        
    // DEFAULT
    default:
        echo "404 - Page not found";
        break;
}

