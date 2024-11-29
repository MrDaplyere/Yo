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
$precio = $data->precio;

if (empty($producto) || empty($cliente) || empty($precio)) {
    echo json_encode(['success' => false, 'message' => 'Los campos cliente y producto son obligatorios.']);
    exit;
}

// Aquí realizamos la conexión y la inserción
try {
    include_once 'Database.php';
    $db = new Database(); // Tu clase de base de datos
    $conn = $db->getConnection();

    // Inserción de datos
    $query = "INSERT INTO ventas (producto, cliente, precio) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $producto);
    $stmt->bindParam(2, $cliente);
    $stmt->bindParam(3, $precio);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Venta insertada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al insertar en la base de datos.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
}
?>
