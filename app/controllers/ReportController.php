<?php

require_once __DIR__ . '/../models/Report.php';
class ReportController
{
    public static function index()
    {
        AuthController::checkRole('admin');
        require __DIR__ . '/../views/admin/reports/index.php';
    }

    public static function transactions()
    {
        AuthController::checkRole('admin');

        $reports = Report::transactions(null, null);

        require __DIR__ . '/../views/admin/reports/transactions.php';
    }
    public static function tools()
    {
        AuthController::checkRole('admin');
    
        $reports = Report::toolReport();
    
        require __DIR__ . '/../views/admin/reports/tools.php';
    }

}
