<?php

// --------------------------- DAO para Ventas ---------------------------
class SalesDao {
    private $conn;
    private $table = 'ventas';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertSale($cliente, $producto, $fecha) {
        $query = "INSERT INTO " . $this->table . " (cliente, producto, fecha) VALUES (:cliente, :producto, :fecha)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente', $cliente);
        $stmt->bindParam(':producto', $producto);
        $stmt->bindParam(':fecha', $fecha);

        return $stmt->execute();
    }

    public function deleteSale($id_venta) {
        $query = "DELETE FROM " . $this->table . " WHERE id_venta = :id_venta";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_venta', $id_venta);

        return $stmt->execute();
    }

    public function updateSale($id_venta, $cliente, $producto, $fecha) {
        $query = "UPDATE " . $this->table . " SET cliente = :cliente, producto = :producto, fecha = :fecha WHERE id_venta = :id_venta";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_venta', $id_venta);
        $stmt->bindParam(':cliente', $cliente);
        $stmt->bindParam(':producto', $producto);
        $stmt->bindParam(':fecha', $fecha);

        return $stmt->execute();
    }

    public function getSales() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// --------------------------- DAO para Clientes ---------------------------
class ClientsDao {
    private $conn;
    private $table = 'clientes';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertClient($nombre, $telefono, $revendedor) {
        $query = "INSERT INTO " . $this->table . " (Nombre, Telefono, revendedor) VALUES (:nombre, :telefono, :revendedor)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':revendedor', $revendedor);

        return $stmt->execute();
    }

    public function deleteClient($id_cliente) {
        $query = "DELETE FROM " . $this->table . " WHERE id_Cliente = :id_cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);

        return $stmt->execute();
    }

    public function updateClient($id_cliente, $nombre, $telefono, $revendedor) {
        $query = "UPDATE " . $this->table . " SET Nombre = :nombre, Telefono = :telefono, revendedor = :revendedor WHERE id_Cliente = :id_cliente";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':revendedor', $revendedor);

        return $stmt->execute();
    }

    public function getClients() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchClients($term) {
        $query = "SELECT id_Cliente, Nombre FROM " . $this->table . " WHERE Nombre LIKE :term LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%" . $term . "%";
        $stmt->bindParam(':term', $searchTerm);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// --------------------------- DAO para Productos ---------------------------
class ProductsDao {
    private $conn;
    private $table = 'productos';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertProduct($nombreProducto, $precio, $revendedor) {
        $query = "INSERT INTO " . $this->table . " (NombreProducto, Precio, Revendedor) VALUES (:nombreProducto, :precio, :revendedor)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombreProducto', $nombreProducto);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':revendedor', $revendedor);

        return $stmt->execute();
    }

    public function deleteProduct($id_producto) {
        $query = "DELETE FROM " . $this->table . " WHERE id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto);

        return $stmt->execute();
    }

    public function updateProduct($id_producto, $nombreProducto, $precio, $revendedor) {
        $query = "UPDATE " . $this->table . " SET NombreProducto = :nombreProducto, Precio = :precio, Revendedor = :revendedor WHERE id_producto = :id_producto";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':nombreProducto', $nombreProducto);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':revendedor', $revendedor);

        return $stmt->execute();
    }

    public function getProducts() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchProducts($term) {
        $query = "SELECT id_producto, NombreProducto FROM " . $this->table . " WHERE NombreProducto LIKE :term LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%" . $term . "%";
        $stmt->bindParam(':term', $searchTerm);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
