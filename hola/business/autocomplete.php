<?php
// Configurar las cabeceras para asegurar que el contenido sea JSON
header('Content-Type: application/json');

// Corregir las rutas para que apunten a los archivos en la capa de negocio
if (file_exists(__DIR__ . '/../business/SalesReportFacade.php')) {
    include_once __DIR__ . '/../business/SalesReportFacade.php';
} else {
    die('Archivo SalesReportFacade.php no encontrado.');
}

if (file_exists(__DIR__ . '/../data/Database.php')) {
    include_once __DIR__ . '/../data/Database.php';
} else {
    die('Archivo Database.php no encontrado.');
}

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();
if ($db === null) {
    die('Error de conexión a la base de datos.');
}

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
