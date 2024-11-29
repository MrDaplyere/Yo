<?php
include_once 'SalesReportFacade.php';
include_once 'Database.php';

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
$salesReportFacade = new SalesReportFacade($db);

if (isset($_GET['term']) && isset($_GET['type'])) {
    $term = $_GET['term'];
    $type = $_GET['type'];

    // Búsqueda de productos
    if ($type === 'product') {
        // Obtener todos los productos
        $products = $salesReportFacade->getProductsReport();

        // Filtrar productos según el término buscado
        $filteredProducts = array_filter($products, function($product) use ($term) {
            return stripos($product['NombreProducto'], $term) !== false;
        });

        // Devolver los productos filtrados como JSON
        echo json_encode(array_values($filteredProducts));
    }
    // Búsqueda de clientes
    elseif ($type === 'client') {
        // Obtener todos los clientes
        $clients = $salesReportFacade->getClientsReport();

        // Filtrar clientes según el término buscado
        $filteredClients = array_filter($clients, function($client) use ($term) {
            return stripos($client['Nombre'], $term) !== false;
        });

        // Devolver los clientes filtrados como JSON
        echo json_encode(array_values($filteredClients));
    }
}
// Obtener el precio de un producto por su ID
elseif (isset($_GET['action']) && $_GET['action'] === 'get_price' && isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Obtener todos los productos
    $products = $salesReportFacade->getProductsReport();

    // Buscar el producto con el id proporcionado
    foreach ($products as $product) {
        if ($product['id_producto'] == $productId) {
            // Si el producto se encuentra, devolver su precio
            echo json_encode(['precio' => $product['Precio']]);
            exit;
        }
    }

    // Si no se encuentra el producto, devolver un precio vacío
    echo json_encode(['precio' => '']);
}
?>
