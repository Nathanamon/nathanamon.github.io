<?php
// index.php
require_once 'controllers/EntrepriseController.php';

$controller = new EntrepriseController();
$page = $_GET['page'] ?? 'list';

if ($page === 'form') {
    $controller->showForm();
} else {
    $controller->listEntreprises();
}
