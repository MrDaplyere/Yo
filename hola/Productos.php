<?php
require_once 'Database.php';
require_once 'ReportBuilder.php';
require_once 'SalesReportFacade.php'; // Incluir la fachada
$database = new Database();
$conn = $database->getConnection();

try {
    $query = "SHOW TABLES";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Tablas en la base de datos:<br>";
    foreach ($tables as $table) {
        echo $table[array_keys($table)[0]] . "<br>";
    }
} catch (PDOException $e) {
    echo "Error al listar las tablas: " . $e->getMessage();
}

// Crear la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Usar la fachada para gestionar las operaciones de productos
$reportFacade = new SalesReportFacade($db);

// Procesar las solicitudes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Eliminar un producto
    if (isset($_POST['delete_id'])) {
        $reportFacade->removeProduct($_POST['delete_id']);
    }
    // Insertar un nuevo producto
    elseif (isset($_POST['nombre'], $_POST['precio'], $_POST['revendedor'])) {
        $reportFacade->addProduct($_POST['nombre'], $_POST['precio'], $_POST['revendedor']);
    }
    // Modificar un producto
    elseif (isset($_POST['update_id'], $_POST['update_nombre'], $_POST['update_precio'], $_POST['update_revendedor'])) {
        $reportFacade->updateProduct($_POST['update_id'], $_POST['update_nombre'], $_POST['update_precio'], $_POST['update_revendedor']);
    }
}

// Obtener el reporte de productos
$productData = $reportFacade->getProductsReport();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Gestión de Productos</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            color: #333;
        }

        .table-container {
            overflow-x: auto;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
            background-color: #ffffff;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: #ffffff;
            text-transform: uppercase;
            font-weight: 600;
        }

        tr {
            border-bottom: 1px solid #dddddd;
        }

        tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        tr:last-of-type {
            border-bottom: 2px solid #2c3e50;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .table-actions .btn {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.875em;
            color: #ffffff;
        }

        .btn-pay {
            background-color: #28a745;
        }

        .btn-remove {
            background-color: #dc3545;
        }

        .btn-pay:hover,
        .btn-remove:hover {
            opacity: 0.8;
        }

        .container {
            max-width: 100%;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container table-container">
        <h1>Gestión de Productos</h1>
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#insertModal">Insertar Producto</button>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre Producto</th>
                    <th>Precio</th>
                    <th>Revendedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
    <?php
    if (!empty($productData)) {
        foreach ($productData as $row) {
            // Verificar si las claves existen antes de usarlas
            $nombreProducto = isset($row['NombreProducto']) ? htmlspecialchars($row['NombreProducto']) : 'No disponible';
            $precio = isset($row['Precio']) ? htmlspecialchars($row['Precio']) : 'No disponible';
            $revendedor = isset($row['Revendedor']) ? htmlspecialchars($row['Revendedor']) : 'No disponible';

            echo "<tr>
                <td>$nombreProducto</td>
                <td>$precio</td>
                <td>$revendedor</td>
                <td class='table-actions'>
                    <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteModal' data-id='" . htmlspecialchars($row['id_producto']) . "'>Eliminar</button>
                    <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editModal' data-id='" . htmlspecialchars($row['id_producto']) . "' data-nombre='" . $nombreProducto . "' data-precio='" . $precio . "' data-revendedor='" . $revendedor . "'>Modificar</button>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='4' class='text-center'>No hay productos disponibles.</td></tr>";
    }
    ?>
</tbody>
        </table>
    </div>

<!-- Modal para Insertar -->
<div class="modal fade" id="insertModal" tabindex="-1" aria-labelledby="insertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="insertModalLabel">Insertar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio</label>
                        <input type="text" class="form-control" id="precio" name="precio" required>
                    </div>
                    <div class="form-group">
                        <label for="revendedor">Revendedor</label>
                        <input type="text" class="form-control" id="revendedor" name="revendedor" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Insertar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal para Editar -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modificar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="update_id" id="update_id">
                    <div class="form-group">
                        <label for="update_nombre">Nombre del Producto</label>
                        <input type="text" class="form-control" id="update_nombre" name="update_nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="update_precio">Precio</label>
                        <input type="text" class="form-control" id="update_precio" name="update_precio" required>
                    </div>
                    <div class="form-group">
                        <label for="update_revendedor">Revendedor</label>
                        <input type="text" class="form-control" id="update_revendedor" name="update_revendedor" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Eliminar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este producto?</p>
                    <input type="hidden" name="delete_id" id="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nombre = button.data('nombre');
        var precio = button.data('precio');
        var revendedor = button.data('revendedor');
        
        var modal = $(this);
        modal.find('#update_id').val(id);
        modal.find('#update_nombre').val(nombre);
        modal.find('#update_precio').val(precio);
        modal.find('#update_revendedor').val(revendedor);
    });

    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        
        var modal = $(this);
        modal.find('#delete_id').val(id);
    });
</script>

</body>
</html>
