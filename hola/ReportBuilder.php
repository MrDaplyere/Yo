<?php
class ReportBuilder {
    private $conn;
    private $salesTable = "ventas";      // En minúsculas
    private $clientsTable = "clientes"; // En minúsculas
    private $productsTable = "productos"; // En minúsculas

    public function __construct($db) {
        $this->conn = $db;
    }

    // Métodos para manejar ventas
    public function insertSale($cliente, $producto, $fecha) {
        $query = "INSERT INTO " . $this->salesTable . " (cliente, producto, fecha) VALUES (:cliente, :producto, :fecha)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente', $cliente);
        $stmt->bindParam(':producto', $producto);
        $stmt->bindParam(':fecha', $fecha);

        return $stmt->execute();
    }

    public function deleteSale($id_venta) {
        $query = "DELETE FROM " . $this->salesTable . " WHERE id_venta = :id_venta";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_venta', $id_venta);

        return $stmt->execute();
    }

    public function updateSale($id_venta, $cliente, $producto, $fecha) {
        $query = "UPDATE " . $this->salesTable . " SET cliente = :cliente, producto = :producto, fecha = :fecha WHERE id_venta = :id_venta";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_venta', $id_venta);
        $stmt->bindParam(':cliente', $cliente);
        $stmt->bindParam(':producto', $producto);
        $stmt->bindParam(':fecha', $fecha);

        return $stmt->execute();
    }

    public function buildSalesReport() {
        $query = "SELECT id_venta, cliente, producto, fecha FROM " . $this->salesTable;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Métodos para manejar clientes
    public function insertClient($nombre, $telefono, $revendedor) {
        $query = "INSERT INTO " . $this->clientsTable . " (Nombre, Telefono, revendedor) VALUES (:nombre, :telefono, :revendedor)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':revendedor', $revendedor);

        return $stmt->execute();
    }

    public function deleteClient($id_cliente) {
        $query = "DELETE FROM " . $this->clientsTable . " WHERE id_Cliente = :id_cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);

        return $stmt->execute();
    }

    public function updateClient($id_cliente, $nombre, $telefono, $revendedor) {
        $query = "UPDATE " . $this->clientsTable . " SET Nombre = :nombre, Telefono = :telefono, revendedor = :revendedor WHERE id_Cliente = :id_cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':revendedor', $revendedor);

        return $stmt->execute();
    }

    public function buildClientsReport() {
        $query = "SELECT id_Cliente, Nombre, Telefono, revendedor FROM " . $this->clientsTable;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Métodos para manejar productos
    public function insertProduct($nombreProducto, $precio, $revendedor) {
        $query = "INSERT INTO " . $this->productsTable . " (NombreProducto, Precio, Revendedor) VALUES (:nombreProducto, :precio, :revendedor)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombreProducto', $nombreProducto);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':revendedor', $revendedor);

        return $stmt->execute();
    }

    public function deleteProduct($id_producto) {
        $query = "DELETE FROM " . $this->productsTable . " WHERE id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto);

        return $stmt->execute();
    }

    public function updateProduct($id_producto, $nombreProducto, $precio, $revendedor) {
        $query = "UPDATE " . $this->productsTable . " SET NombreProducto = :nombreProducto, Precio = :precio, Revendedor = :revendedor WHERE id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':nombreProducto', $nombreProducto);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':revendedor', $revendedor);

        return $stmt->execute();
    }

    public function buildProductsReport() {
        $query = "SELECT id_producto, NombreProducto, Precio, Revendedor FROM " . $this->productsTable;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
