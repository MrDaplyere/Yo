<?php

// Incluir las clases necesarias con las rutas corregidas
require_once __DIR__ . '/../business/SalesReportFacade.php';
require_once __DIR__ . '/../data/Database.php';
require_once __DIR__ . '/../business/JSONReportStrategy.php';
require_once __DIR__ . '/../business/PDFReportStrategy.php';

class Presentador {
    private $reportFacade;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection(); // Conectar a la base de datos
        $this->reportFacade = new SalesReportFacade($this->db); // Crear una instancia de la fachada
    }

    // Obtener los reportes de ventas
    public function getSalesReport() {
        return $this->reportFacade->getSalesReport();
    }

    // Generar el reporte según el formato
    public function generateReport($format) {
        // Establecer el tipo de reporte
        if ($format === 'json') {
            $this->reportFacade->setReportStrategy(new JsonReportStrategy());
        } elseif ($format === 'pdf') {
            $this->reportFacade->setReportStrategy(new PdfReportStrategy());
        }

        return $this->reportFacade->generateSalesReport();
    }

    // Insertar una nueva venta
    public function insertSale($cliente, $producto) {
        return $this->reportFacade->addSale($cliente, $producto, date('Y-m-d'));
    }

    // Eliminar una venta
    public function deleteSale($saleId) {
        return $this->reportFacade->removeSale($saleId);
    }

    // Modificar una venta
    public function updateSale($saleId, $cliente, $producto, $fecha) {
        return $this->reportFacade->updateSale($saleId, $cliente, $producto, $fecha);
    }

    // Obtener productos para autocompletar
    public function getProducts($term) {
        return $this->reportFacade->getProductsReport($term);
    }

    // Obtener clientes para autocompletar
    public function getClients($term) {
        return $this->reportFacade->getClientsReport($term);
    }

    // Obtener el precio de un producto
    public function getProductPrice($productId) {
        $products = $this->reportFacade->getProductsReport();
        foreach ($products as $product) {
            if ($product['id_producto'] == $productId) {
                return $product['Precio'];
            }
        }
        return '';
    }
}
?>
