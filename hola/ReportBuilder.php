<?php
require_once 'DataAccessObjects.php';

class ReportBuilder {
    private $conn;
    private $salesDao;
    private $clientsDao;
    private $productsDao;

    public function __construct($db) {
        $this->conn = $db;
        $this->salesDao = new SalesDao($this->conn);
        $this->clientsDao = new ClientsDao($this->conn);
        $this->productsDao = new ProductsDao($this->conn);
    }

    // --------------------------- Métodos para manejar ventas ---------------------------
    public function insertSale($cliente, $producto, $fecha) {
        return $this->salesDao->insertSale($cliente, $producto, $fecha);
    }

    public function deleteSale($id_venta) {
        return $this->salesDao->deleteSale($id_venta);
    }

    public function updateSale($id_venta, $cliente, $producto, $fecha) {
        return $this->salesDao->updateSale($id_venta, $cliente, $producto, $fecha);
    }

    public function buildSalesReport() {
        return $this->salesDao->getSales();
    }

    // --------------------------- Métodos para manejar clientes ---------------------------
    public function insertClient($nombre, $telefono, $revendedor) {
        return $this->clientsDao->insertClient($nombre, $telefono, $revendedor);
    }

    public function deleteClient($id_cliente) {
        return $this->clientsDao->deleteClient($id_cliente);
    }

    public function updateClient($id_cliente, $nombre, $telefono, $revendedor) {
        return $this->clientsDao->updateClient($id_cliente, $nombre, $telefono, $revendedor);
    }

    public function buildClientsReport() {
        return $this->clientsDao->getClients();
    }

    // --------------------------- Métodos para manejar productos ---------------------------
    public function insertProduct($nombreProducto, $precio, $revendedor) {
        return $this->productsDao->insertProduct($nombreProducto, $precio, $revendedor);
    }

    public function deleteProduct($id_producto) {
        return $this->productsDao->deleteProduct($id_producto);
    }

    public function updateProduct($id_producto, $nombreProducto, $precio, $revendedor) {
        return $this->productsDao->updateProduct($id_producto, $nombreProducto, $precio, $revendedor);
    }

    public function buildProductsReport() {
        return $this->productsDao->getProducts();
    }

    // Autocomplete para productos y clientes
    public function searchProducts($term) {
        return $this->productsDao->searchProducts($term);
    }

    public function searchClients($term) {
        return $this->clientsDao->searchClients($term);
    }
}
?>
