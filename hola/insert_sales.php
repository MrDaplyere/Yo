<?php
// Habilitar error reporting para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Recibir los datos JSON
$data = json_decode(file_get_contents("php://input"));

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No se recibieron datos.']);
    exit;
}

$producto = $data->producto ?? null;
$cliente = $data->cliente ?? null;

// Validar datos requeridos
if (empty($producto) || empty($cliente)) {
    echo json_encode(['success' => false, 'message' => 'Los campos cliente y producto son obligatorios.']);
    exit;
}

// Incluir e inicializar la fachada
try {
    include_once 'Database.php';
    include_once 'SalesReportFacade.php';

    $db = new Database(); // Instancia de tu clase de conexión
    $conn = $db->getConnection();
    $reportFacade = new SalesReportFacade($conn); // Crear instancia de la fachada

    // Llamar al método para insertar la venta
    $resultado = $reportFacade->insertSale($cliente, $producto);

    if ($resultado) {
        echo json_encode(['success' => true, 'message' => 'Venta insertada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al insertar en la base de datos.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
