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
$revendedor = $data->revendedor; // El precio se pasa con el nombre de 'revendedor'

if (empty($producto) || empty($cliente) || empty($revendedor)) {
    echo json_encode(['success' => false, 'message' => 'Los campos cliente, producto y revendedor son obligatorios.']);
    exit;
}

// Aquí realizamos la conexión y la inserción
try {
    include_once 'Database.php';
    $db = new Database(); // Tu clase de base de datos
    $conn = $db->getConnection();

    // Preparar la consulta SQL para insertar los datos en la tabla 'ventas'
    $query = "INSERT INTO ventas (producto, cliente, revendedor) VALUES (?, ?, ?)"; // Usamos 'revendedor' para el precio
    $stmt = $conn->prepare($query);

    // Vincular los parámetros
    $stmt->bindParam(1, $producto);
    $stmt->bindParam(2, $cliente);
    $stmt->bindParam(3, $revendedor); // El precio se inserta en el campo 'revendedor'

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Venta insertada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al insertar en la base de datos.']);
    }
} catch (PDOException $e) {
    // Capturar cualquier error de base de datos y mostrarlo
    echo json_encode(['success' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()]);
}
?>
