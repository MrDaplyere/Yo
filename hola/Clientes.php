<?php
include_once 'SalesReportFacade.php';
include_once 'Database.php';

$db = new Database();
$conn = $db->getConnection();
$salesReportFacade = new SalesReportFacade($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['addClient'])) {
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $revendedor = $_POST['revendedor'];

        $salesReportFacade->addClient($nombre, $telefono, $revendedor);
    }

    if (isset($_POST['removeClient'])) {
        $id_cliente = $_POST['id_cliente'];
        $salesReportFacade->removeClient($id_cliente);
    }

    if (isset($_POST['updateClient'])) {
        $id_cliente = $_POST['id_cliente'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];
        $revendedor = $_POST['revendedor'];

        $salesReportFacade->updateClient($id_cliente, $nombre, $telefono, $revendedor);
    }
}

// Obtener el reporte de clientes
$clientes = $salesReportFacade->getClientsReport();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <!-- Vincula Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Clientes</h1>

        <!-- Botón para abrir el modal de agregar cliente -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addClientModal">Agregar Cliente</button>

        <!-- Modal para agregar cliente -->
        <div class="modal fade" id="addClientModal" tabindex="-1" role="dialog" aria-labelledby="addClientModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addClientModalLabel">Nuevo Cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" required>
                            </div>
                            <div class="form-group">
                                <label for="revendedor">Revendedor</label>
                                <input type="text" class="form-control" id="revendedor" name="revendedor" placeholder="Revendedor" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" name="addClient" class="btn btn-primary">Guardar Cliente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de clientes -->
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID Cliente</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Revendedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= $cliente['id_Cliente']; ?></td>
                        <td><?= $cliente['Nombre']; ?></td>
                        <td><?= $cliente['Telefono']; ?></td>
                        <td><?= $cliente['revendedor']; ?></td>
                        <td>
                            <!-- Botón para abrir el modal de eliminar cliente -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id_cliente" value="<?= $cliente['id_Cliente']; ?>">
                                <button type="submit" name="removeClient" class="btn btn-danger">Eliminar</button>
                            </form>
                            <!-- Botón para abrir el modal de actualizar cliente -->
                            <button class="btn btn-warning" data-toggle="modal" data-target="#updateClientModal<?= $cliente['id_Cliente']; ?>">Actualizar</button>

                            <!-- Modal para actualizar cliente -->
                            <div class="modal fade" id="updateClientModal<?= $cliente['id_Cliente']; ?>" tabindex="-1" role="dialog" aria-labelledby="updateClientModalLabel<?= $cliente['id_Cliente']; ?>" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateClientModalLabel<?= $cliente['id_Cliente']; ?>">Actualizar Cliente</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <input type="hidden" name="id_cliente" value="<?= $cliente['id_Cliente']; ?>">
                                                    <label for="nombre">Nombre</label>
                                                    <input type="text" class="form-control" name="nombre" value="<?= $cliente['Nombre']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="telefono">Teléfono</label>
                                                    <input type="text" class="form-control" name="telefono" value="<?= $cliente['Telefono']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="revendedor">Revendedor</label>
                                                    <input type="text" class="form-control" name="revendedor" value="<?= $cliente['revendedor']; ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" name="updateClient" class="btn btn-primary">Actualizar Cliente</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        // Activar modal con jQuery
        $('#addClientModal').modal('show'); // Esto lo abrirá al cargar la página (si lo deseas así)

        // O si quieres abrir el modal solo cuando el usuario haga clic en el botón
        $('[data-toggle="modal"]').click(function() {
            var target = $(this).data('target');
            $(target).modal('show');
        });
    });
</script>

</body>
</html>
