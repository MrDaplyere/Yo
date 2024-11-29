<?php
include_once 'ReportBuilder.php';

class SalesReportFacade {
    private $db;
    private $reportBuilder;
    private $reportStrategy;

    public function __construct($db) {
        $this->db = $db;
        $this->reportBuilder = new ReportBuilder($this->db);
    }

    // -------------------------------VENTAS--------------------------------------
    public function addSale($cliente, $producto, $fecha) {
        return $this->reportBuilder->insertSale($cliente, $producto, $fecha);
    }

    public function removeSale($id_venta) { 
        return $this->reportBuilder->deleteSale($id_venta);
    }

    public function updateSale($id_venta, $cliente, $producto, $fecha) {
        return $this->reportBuilder->updateSale($id_venta, $cliente, $producto, $fecha);
    }

    public function getSalesReport() {
        return $this->reportBuilder->buildSalesReport();  
    }

    // -------------------------------CLIENTES--------------------------------------
    public function addClient($nombre, $telefono, $revendedor) {
        return $this->reportBuilder->insertClient($nombre, $telefono, $revendedor);
    }

    public function removeClient($id_cliente) {
        return $this->reportBuilder->deleteClient($id_cliente);
    }

    public function updateClient($id_cliente, $nombre, $telefono, $revendedor) {
        return $this->reportBuilder->updateClient($id_cliente, $nombre, $telefono, $revendedor);
    }

    public function getClientsReport() {
        return $this->reportBuilder->buildClientsReport();  
    }

    // -------------------------------PRODUCTOS--------------------------------------
    public function addProduct($nombreProducto, $precio, $revendedor) {
        return $this->reportBuilder->insertProduct($nombreProducto, $precio, $revendedor);
    }

    public function removeProduct($id_producto) {
        return $this->reportBuilder->deleteProduct($id_producto);
    }

    public function updateProduct($id_producto, $nombreProducto, $precio, $revendedor) {
        return $this->reportBuilder->updateProduct($id_producto, $nombreProducto, $precio, $revendedor);
    }

    public function getProductsReport() {
        return $this->reportBuilder->buildProductsReport();  
    }

    // -------------------------------REPORTES--------------------------------------
    public function setReportStrategy(ReportStrategy $strategy) {
        $this->reportStrategy = $strategy;
    }

    public function generateSalesReport() {
        $data = $this->reportBuilder->buildSalesReport();
        if ($this->reportStrategy) {
            return $this->reportStrategy->generateReport($data);
        }
        throw new Exception("No hay reporte");
    }

    // ------------------------------ AUTOCOMPLETE ------------------------------
    public function searchProducts($term) {
        return $this->reportBuilder->searchProducts($term);
    }

    public function searchClients($term) {
        return $this->reportBuilder->searchClients($term);
    }

    // ------------------------------ INSERTAR VENTA ------------------------------
    // -------------------------------VENTAS--------------------------------------
    public function insertSale($cliente, $producto) {
        $fecha = date('Y-m-d H:i:s'); // Fecha actual
        return $this->addSale($cliente, $producto, $fecha);
    }
}
?>
