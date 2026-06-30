<?php
session_start();

// Incluir controlador principal
require_once __DIR__ . '/../app/controllers/ParticipanteController.php';

// Determinar acción
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$controller = new ParticipanteController();

switch ($action) {
    case 'guardar':
        $controller->guardar();
        break;
    case 'reporte':
        $controller->reporte();
        break;
    case 'exportar':
        $controller->exportar();
        break;
    case 'buscar':
        $controller->buscar();
        break;
    case 'estadisticas':
        $controller->estadisticas();
        break;
    default:
        $controller->index();
        break;
}
