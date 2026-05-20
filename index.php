<?php
// index.php
require_once 'config.php';
require_once 'controllers/EmployeeController.php';

// Capturar la acción de la URL (si no hay, por defecto es 'index')
$action = $_GET['action'] ?? 'index';

$controller = new EmployeeController();

// Router básico
switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'index':
    default:
        $controller->index();
        break;
}