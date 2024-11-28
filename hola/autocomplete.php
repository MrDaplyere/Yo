<?php
include_once 'SalesReportFacade.php';
include_once 'Database.php';

$db = new Database();
$connection = $db->getConnection();
$facade = new SalesReportFacade($connection);

// Obtener el término y el tipo de búsqueda
$term = isset($_GET['term']) ? $_GET['term'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';

if ($type === 'product') {
    // Buscar productos
    $products = $facade->searchProducts($term);
    echo json_encode($products);
} elseif ($type === 'client') {
    // Buscar clientes
    $clients = $facade->searchClients($term);
    echo json_encode($clients);
} else {
    echo json_encode([]); // Retorna vacío si no se especifica un tipo válido
}
?>
