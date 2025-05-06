<?php
// Incluir el módulo de conexión
include_once '../modules/conn.php';

// Obtener todas las conexiones
function getConexiones($connection) {
    $sql = "SELECT * FROM conexion"; // Ajusta al nombre exacto de la tabla
    $stmt = $connection->prepare($sql);
    if (!$stmt->execute()) {
        return []; // Si hay un error, devolver un array vacío
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Procesar las conexiones
$conexiones = getConexiones($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Conexiones</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/styles-consults.css">
</head>

<body>
    <div>
        <nav id="main-nav">
            <div class="logo-container">
                <img src="../../resources/images/logo-udlap.png" alt="" class="img-fluid" id="logo-udlap">
            </div>
            <div class="header-container">
                <header id="nav-departamento">Departamento de Electrónica</header>
                <header id="nav-titulo">-Consulta y búsqueda de conexiones-</header>
            </div>
        </nav>
    </div>

    <div class="container mt-4">
        <div class="search-container">
            <div class="button-return mr-3">
                <a href="form-select_type.php">
                    <img src="../../resources/images/icon-return.png" alt="Volver">
                </a>
            </div>
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar conexión por cualquier campo..." onkeyup="filterTable()">
        </div>

        <div id="message" style="display: none;" class="alert alert-success"></div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="conexionTable">
                <thead class="thead-dark">
                    <tr>
                        <th>ART_NO</th>
                        <th>POSICIONX</th>
                        <th>ETIQUETA</th>
                        <th>CONECTOR</th>
                        <th>DESCRIP1</th>
                        <th>DESCRIP2</th>
                        <th>EXISTENCIA</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($conexiones)): ?>
                        <?php foreach ($conexiones as $conexion): ?>
                            <tr id="row-<?= htmlspecialchars($conexion['ART_NO'] ?? '') ?>">
                                <td><?= htmlspecialchars($conexion['ART_NO'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($conexion['POSICIONX'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($conexion['ETIQUETA'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($conexion['CONECTOR'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($conexion['DESCRIP1'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($conexion['DESCRIP2'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($conexion['EXISTENCIA'] ?? 'N/A') ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit" data-id="<?= htmlspecialchars($conexion['ART_NO'] ?? '') ?>">Editar</button>
                                    <button class="btn btn-danger btn-sm delete" data-id="<?= htmlspecialchars($conexion['ART_NO'] ?? '') ?>">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay conexiones disponibles.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination-buttons">
        <button id="prevBtn" class="btn btn-primary" onclick="prevPage()">Anterior</button>
        <span id="pageIndicator" class="page-indicator"></span>
        <button id="nextBtn" class="btn btn-primary" onclick="nextPage()">Siguiente</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
    // Habilitar la edición de los campos al presionar el botón "Editar"
    $(document).on('click', '.edit', function() {
        var row = $(this).closest('tr');
        row.find('.editable').attr('contenteditable', 'true').focus();
        $(this).removeClass('edit btn-primary').addClass('save btn-success').text('Guardar');
    });

    // Guardar los cambios al presionar "Guardar"
    $(document).on('click', '.save', function() {
        var row = $(this).closest('tr');
        var id = $(this).data('id'); // ID de la fila (ART_NO)
        var posicionx = row.find('td:eq(1)').text().trim();
        var etiqueta = row.find('td:eq(2)').text().trim();
        var conector = row.find('td:eq(3)').text().trim();
        var descripcion1 = row.find('td:eq(4)').text().trim();
        var descripcion2 = row.find('td:eq(5)').text().trim();
        var existencia = row.find('td:eq(6)').text().trim();

        // AJAX para guardar cambios
        $.ajax({
            url: '../modules/mod-edit_conexiones.php', // Archivo PHP para procesar la edición
            method: 'POST',
            data: { 
                art_no: id, 
                posicionx: posicionx, 
                etiqueta: etiqueta, 
                conector: conector, 
                descripcion1: descripcion1, 
                descripcion2: descripcion2, 
                existencia: existencia 
            },
            success: function(response) {
                if (response.trim() === 'success') {
                    // Mostrar mensaje de éxito
                    $('#message').html('<div class="alert alert-success">Cambios guardados correctamente</div>')
                        .fadeIn().delay(3000).fadeOut();

                    // Desactivar los campos editables
                    row.find('.editable').attr('contenteditable', 'false');

                    // Cambiar el botón "Guardar" a "Editar"
                    row.find('.save').removeClass('save btn-success').addClass('edit btn-primary').text('Editar');
                } else {
                    // Mostrar mensaje de error
                    $('#message').html('<div class="alert alert-danger">Error: ' + response + '</div>')
                        .fadeIn().delay(3000).fadeOut();
                }
            },
            error: function() {
                // Manejar error de AJAX
                $('#message').html('<div class="alert alert-danger">Error al guardar los cambios</div>')
                    .fadeIn().delay(3000).fadeOut();
            }
        });
    });

    // Eliminar la conexión al presionar "Eliminar"
    $(document).on('click', '.delete', function() {
        var id = $(this).data('id');
        var row = $(this).closest('tr');

        if (confirm('¿Estás seguro de eliminar esta conexión?')) {
            $.ajax({
                url: '../modules/mod-delete_conexiones.php',
                method: 'POST',
                data: { art_no: id },
                success: function(response) {
                    if (response.trim() === 'success') {
                        row.remove();
                        $('#message').html('<div class="alert alert-success">Conexión eliminada correctamente</div>')
                            .fadeIn().delay(3000).fadeOut();
                    } else {
                        $('#message').html('<div class="alert alert-danger">Error: ' + response + '</div>')
                            .fadeIn().delay(3000).fadeOut();
                    }
                },
                error: function() {
                    $('#message').html('<div class="alert alert-danger">Error al eliminar la conexión</div>')
                        .fadeIn().delay(3000).fadeOut();
                }
            });
        }
    });

    // Filtro y paginación
    function filterTable() {
        var input = document.getElementById('searchInput').value.toLowerCase();
        var rows = document.querySelectorAll('#conexionTable tbody tr');
        rows.forEach(row => {
            row.style.display = Array.from(row.cells).some(cell => cell.textContent.toLowerCase().includes(input)) ? '' : 'none';
        });
        paginateTable();
    }

    function paginateTable() {
        var rows = Array.from(document.querySelectorAll('#conexionTable tbody tr')).filter(row => row.style.display === '');
        var totalPages = Math.ceil(rows.length / 10);
        rows.forEach((row, i) => row.style.display = (i >= (currentPage - 1) * 10 && i < currentPage * 10) ? '' : 'none');
        document.getElementById('pageIndicator').textContent = `Página ${currentPage} de ${totalPages}`;
        document.getElementById('prevBtn').disabled = currentPage === 1;
        document.getElementById('nextBtn').disabled = currentPage === totalPages;
    }

    var currentPage = 1;
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('prevBtn').addEventListener('click', () => { currentPage--; paginateTable(); });
    document.getElementById('nextBtn').addEventListener('click', () => { currentPage++; paginateTable(); });

    filterTable();
});

    </script>
</body>
</html>
