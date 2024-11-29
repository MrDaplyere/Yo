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

$producto = $data->producto;
$cliente = $data->cliente;
$fecha = $data->fecha; // Asegúrate de que se recibe una fecha en formato 'YYYY-MM-DD'

if (empty($producto) || empty($cliente) || empty($fecha)) {
    echo json_encode(['success' => false, 'message' => 'Los campos cliente, producto y fecha son obligatorios.']);
    exit;
}

// Verificar que la fecha esté en el formato correcto
if (!DateTime::createFromFormat('Y-m-d', $fecha)) {
    echo json_encode(['success' => false, 'message' => 'La fecha no tiene el formato correcto.']);
    exit;
}

// Aquí realizamos la conexión y la inserción
try {
    include_once 'Database.php';
    $db = new Database(); // Tu clase de base de datos
    $conn = $db->getConnection();

    // Inserción de datos
    $query = "INSERT INTO ventas (producto, cliente, fecha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $producto);
    $stmt->bindParam(2, $cliente);
    $stmt->bindParam(3, $fecha);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Venta insertada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al insertar en la base de datos.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
}
?>
