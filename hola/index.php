<?php
require_once 'Presentador.php';

// Crear el Presentador
$presentador = new Presentador();

// Manejo de solicitudes de formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['report_format'])) {
        $format = $_POST['report_format'];
        $salesReport = $presentador->generateReport($format);

        if ($format === 'pdf') {
            // Asegúrate de que no haya salida antes de los encabezados
            ob_clean(); // Limpia cualquier salida previa (si es que estás usando el buffer)
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="reporte_ventas.pdf"');
            echo $salesReport;
            exit;  // Asegúrate de salir después de enviar el PDF
        }

        // Para otros formatos, se maneja aquí
        echo $salesReport;
        exit;
    }
}

// Obtener el reporte de ventas para mostrar
$reportData = $presentador->getSalesReport();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Historial de Ventas</title>
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
        th, td {
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
        .btn-pay:hover, .btn-remove:hover {
            opacity: 0.8;
        }
        .container {
            max-width: 100%;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
        }
/* Estilo para el contenedor de la lista */
 #product-list, #client-list {
        position: absolute;
        border: 1px solid #ccc;
        background-color: #fff;
        max-height: 200px;
        overflow-y: auto;
        width: calc(100% - 2px);
        z-index: 1000;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    /* Estilo para cada opción de la lista */
    #product-list div, #client-list div {
        padding: 10px;
        cursor: pointer;
        font-size: 14px;
        color: #333;
    }

    /* Cambio de color al pasar el ratón por encima */
    #product-list div:hover, #client-list div:hover {
        background-color: #f0f0f0;
        color: #000;
    }
</style>
</head>
<body>
<div class="container table-container">
<h1>Historial de Ventas</h1>

<!-- Formulario para generar reportes -->
<div class="mb-3">
    <form method="POST" class="form-inline">
        <label for="report_format" class="mr-2">Generar reporte como:</label>
        <select name="report_format" id="report_format" class="form-control mr-2">
            <option value="json">JSON</option>
            <option value="pdf">PDF</option> 
        </select>
        <button type="submit" class="btn btn-success">Generar Reporte</button>
    </form>
</div>
<div>
<form id="sales-form" class="p-4">
    <div class="mb-3">
        <label for="cliente" class="form-label">Cliente:</label>
        <input type="text" id="cliente" class="form-control" placeholder="Escribe un cliente">
        <div id="client-list" class="list-group mt-2"></div>
    </div>

    <div class="mb-3">
        <label for="producto" class="form-label">Producto:</label>
        <input type="text" id="producto" class="form-control" placeholder="Escribe un producto">
        <div id="product-list" class="list-group mt-2"></div>
    </div>

    <div class="mb-3">
        <label for="precio" class="form-label">Precio:</label>
        <input type="text" id="price" class="form-control" readonly>
    </div>

    <button type="button" id="insert-button" class="btn btn-primary">Insertar</button>
</form>
<div id="insert-status"></div>

<!-- Botón para insertar venta -->
<button class="btn btn-primary mb-3" data-toggle="modal" data-target="#insertModal">Insertar Venta</button>

<!-- Tabla del historial de ventas -->
<table class="table">
    <thead>
        <tr>
            <th>Cliente</th>
            <th>Producto</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($reportData)) {
            foreach ($reportData as $row) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['cliente']) . "</td>
                        <td>" . htmlspecialchars($row['producto']) . "</td>
                        <td>" . htmlspecialchars($row['fecha']) . "</td>
                        <td class='table-actions'>
                            <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteModal' data-id='" . htmlspecialchars($row['id_venta']) . "'>Eliminar</button>
                            <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editModal' data-id='" . htmlspecialchars($row['id_venta']) . "' data-cliente='" . htmlspecialchars($row['cliente']) . "' data-producto='" . htmlspecialchars($row['producto']) . "' data-fecha='" . htmlspecialchars($row['fecha']) . "'>Modificar</button>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4' class='text-center'>No hay datos disponibles.</td></tr>";
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
                <h5 class="modal-title" id="insertModalLabel">Insertar Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cliente">Cliente</label>
                        <input type="text" class="form-control" id="cliente" name="cliente" required>
                    </div>
                    <div class="form-group">
                        <label for="producto">Producto</label>
                        <input type="text" class="form-control" id="producto" name="producto" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
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

<!-- Modal para Eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Eliminar Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este registro?</p>  
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

<!-- Modal para Modificar -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modificar Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="update_id" id="update_id">
                    <div class="form-group">
                        <label for="update_cliente">Cliente</label>
                        <input type="text" class="form-control" id="update_cliente" name="update_cliente" required>
                    </div>
                    <div class="form-group">
                        <label for="update_producto">Producto</label>
                        <input type="text" class="form-control" id="update_producto" name="update_producto" required>
                    </div>
                    <div class="form-group">
                        <label for="update_fecha">Fecha</label>
                        <input type="date" class="form-control" id="update_fecha" name="update_fecha" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Configuración de los modales para eliminar y editar
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        modal.find('#delete_id').val(id);
    });

    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var cliente = button.data('cliente');
        var producto = button.data('producto');
        var fecha = button.data('fecha');
        var modal = $(this);

        modal.find('#update_id').val(id);
        modal.find('#update_cliente').val(cliente);
        modal.find('#update_producto').val(producto);
        modal.find('#update_fecha').val(fecha);
    });

// Configuración de autocompletado
function setupAutocomplete(inputId, listId, type) {
    const input = document.getElementById(inputId);
    const list = document.getElementById(listId);

    input.addEventListener('input', function () {
        const term = this.value;

        if (term.length > 2) { // Buscar si tiene más de 2 caracteres
            fetch(`autocomplete.php?term=${encodeURIComponent(term)}&type=${type}`)
                .then(response => response.json())
                .then(data => {
                    list.innerHTML = ''; // Limpiar la lista de sugerencias
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.innerText = item.Nombre || item.NombreProducto; // Adaptar según el tipo
                        div.setAttribute('data-id', item.id_Cliente || item.id_producto);
                        div.addEventListener('click', function () {
                            input.value = item.Nombre || item.NombreProducto; // Rellenar input con el nombre seleccionado
                            list.innerHTML = ''; // Limpiar la lista después de seleccionar

                            // Si es de tipo producto, busca y actualiza el precio
                            if (type === 'product') {
                                fetch(`autocomplete.php?action=get_price&id=${item.id_producto}`)
                                    .then(response => response.json())
                                    .then(priceData => {
                                        const priceInput = document.getElementById('price');
                                        if (priceInput) {
                                            priceInput.value = priceData.precio; // Rellenar campo de precio
                                        }
                                    })
                                    .catch(err => console.error('Error fetching price:', err));
                            }
                        });
                        list.appendChild(div);
                    });
                });
        } else {
            list.innerHTML = ''; // Limpiar la lista si no hay suficientes caracteres
        }
    });

    // Limpiar lista si el input pierde el foco
    input.addEventListener('blur', function () {
        setTimeout(() => { list.innerHTML = ''; }, 200);
    });
}

// Configurar autocompletado para productos y clientes
setupAutocomplete('producto', 'product-list', 'product');
setupAutocomplete('cliente', 'client-list', 'client');

// Función para insertar venta
document.getElementById('insert-button').addEventListener('click', function () {
    const productoInput = document.getElementById('producto');
    const clienteInput = document.getElementById('cliente');

    if (!productoInput || !clienteInput) {
        console.error('Uno o más elementos no existen en el DOM.');
        return;
    }

    const producto = productoInput.value;
    const cliente = clienteInput.value;

    // Verifica que los valores no estén vacíos antes de continuar
    if (!producto || !cliente) {
        alert('Por favor, complete todos los campos antes de insertar.');
        return;
    }

    // Enviar solo cliente y producto; la fecha se genera automáticamente en el servidor
    fetch('insert_sales.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ producto, cliente })
    })
    .then(response => response.text())
    .then(data => {
        console.log('Respuesta del servidor:', data);

        // Intentar parsear la respuesta como JSON
        try {
            const jsonData = JSON.parse(data);
            if (jsonData.success) {
                // Mostrar SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: '¡Pago realizado exitosamente!',
                    text: 'La venta se ha registrado correctamente.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    // Recargar la página
                    location.reload();
                });

                // Opcional: limpiar los inputs
                productoInput.value = '';
                clienteInput.value = '';
            } else {
                alert('Error al insertar la venta: ' + jsonData.message);
            }
        } catch (err) {
            console.error('Error al parsear JSON:', err);
            alert('Hubo un problema al procesar la respuesta del servidor.');
        }
    })
    .catch(err => console.error('Error al insertar la venta:', err));
});
</script>
</body>
</html>
