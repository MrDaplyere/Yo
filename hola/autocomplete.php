<?php
include_once 'SalesReportFacade.php';
include_once 'Database.php';

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
$salesReportFacade = new SalesReportFacade($db);

header('Content-Type: application/json');  // Asegura que la respuesta sea en formato JSON

try {
    if (isset($_GET['term']) && isset($_GET['type'])) {
        $term = $_GET['term'];
        $type = $_GET['type'];

        // Búsqueda de productos
        if ($type === 'product') {
            $products = $salesReportFacade->getProductsReport();

            // Filtrar productos según el término buscado
            $filteredProducts = array_filter($products, function($product) use ($term) {
                return stripos($product['NombreProducto'], $term) !== false;
            });

            echo json_encode(array_values($filteredProducts));  // Respuesta en JSON
        }
        // Búsqueda de clientes
        elseif ($type === 'client') {
            $clients = $salesReportFacade->getClientsReport();

            // Filtrar clientes según el término buscado
            $filteredClients = array_filter($clients, function($client) use ($term) {
                return stripos($client['Nombre'], $term) !== false;
            });

            echo json_encode(array_values($filteredClients));  // Respuesta en JSON
        }
    }
    // Obtener el precio de un producto por su ID
    elseif (isset($_GET['action']) && $_GET['action'] === 'get_price' && isset($_GET['id'])) {
        $productId = $_GET['id'];
        $products = $salesReportFacade->getProductsReport();

        $productFound = false;
        foreach ($products as $product) {
            if ($product['id_producto'] == $productId) {
                $productFound = true;
                echo json_encode(['precio' => $product['Precio']]);  // Respuesta JSON con precio
                break;
            }
        }

        if (!$productFound) {
            echo json_encode(['precio' => '']);  // Si no se encuentra el producto
        }
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Hubo un problema en el servidor: ' . $e->getMessage()]);
}
?>
