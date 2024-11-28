<?php
include_once 'Database.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $_POST['cliente'] ?? '';
    $producto = $_POST['producto'] ?? '';
    $precio = $_POST['precio'] ?? '';

    if (!$cliente || !$producto) {
        $response['message'] = 'Los campos cliente y producto son obligatorios.';
        echo json_encode($response);
        exit;
    }

    try {
        $database = new Database();
        $db = $database->getConnection();

        // Preparamos el query de inserción
        $query = "INSERT INTO ventas (cliente, producto, fecha) VALUES (:cliente, :producto, NOW())";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':cliente', $cliente);
        $stmt->bindParam(':producto', $producto);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Venta insertada correctamente.';
        } else {
            $response['message'] = 'Error al ejecutar la inserción.';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error de servidor: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
?>
