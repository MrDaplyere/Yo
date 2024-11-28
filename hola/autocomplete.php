<?php
include_once 'SalesReportFacade.php';
include_once 'Database.php';

$db = new Database();
$connection = $db->getConnection();
$facade = new SalesReportFacade($connection);

$term = isset($_GET['term']) ? $_GET['term'] : '';
$products = $facade->searchProducts($term);

echo json_encode($products);
?>

