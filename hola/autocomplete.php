<?php
include_once 'SalesReportFacade.php';
include_once 'Database.php';

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
$salesReportFacade = new SalesReportFacade($db);

if (isset($_GET['term'])) {
    $term = $_GET['term'];
    $products = $salesReportFacade->getProductsReport();

    // Filtrar productos según el término buscado
    $filteredProducts = array_filter($products, function($product) use ($term) {
        return stripos($product['NombreProducto'], $term) !== false;
    });

    echo json_encode(array_values($filteredProducts));
} elseif (isset($_GET['action']) && $_GET['action'] === 'get_price' && isset($_GET['id'])) {
    $productId = $_GET['id'];
    $products = $salesReportFacade->getProductsReport();

    foreach ($products as $product) {
        if ($product['id_producto'] == $productId) {
            echo json_encode(['precio' => $product['Precio']]);
            exit;
        }
    }

    // Si no se encuentra el producto
    echo json_encode(['precio' => '']);
}
?>
