<?php
include_once 'SalesReportFacade.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente = $_POST['cliente'] ?? '';
    $producto = $_POST['producto'] ?? '';

    if (!$cliente || !$producto) {
        $response['message'] = 'Los campos cliente y producto son obligatorios.';
        echo json_encode($response);
        exit;
    }

    try {
        $database = new Database();
        $db = $database->getConnection();
        $salesFacade = new SalesReportFacade($db);

        if ($salesFacade->insertSale($cliente, $producto)) {
            $response['success'] = true;
            $response['message'] = 'Venta insertada correctamente.';
        } else {
            $response['message'] = 'Error al insertar la venta.';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error de servidor: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);
?>
