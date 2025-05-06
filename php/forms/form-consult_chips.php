<?php
// Incluir el módulo de conexión y consultas de equipos
include_once '../modules/conn.php';
include_once '../modules/bkend-consult_chips.php'; // Cambié el nombre del módulo a 'consult_chips' (si es necesario)


// Procesar solicitudes de edición o eliminación
processRequest($connection);

// Obtener todos los chips para mostrarlos
$chips = getChips($connection);

// Si no se obtuvieron chips, asegurarse de que $chips sea un array vacío
if ($chips === false) {
    $chips = [];
}

// Verificar si se seleccionó un chip para editar
$chipEditar = null; // Inicializar la variable para evitar advertencias
if (isset($_GET['numero'])) {
    $chipEditar = getChipByNumero($connection, $_GET['numero']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadatos de la página -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Chips</title>
    
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/styles-consults.css">
</head>

<body>
    <!-- Inicio del contenido visible de la página -->
    <div>
        <!-- Sección de navegación principal -->
        <nav id="main-nav">
            <!-- Contenedor para el logotipo -->
            <div class="logo-container">
                <img src="../../resources/images/logo-udlap.png" alt="" class="img-fluid" id="logo-udlap">
            </div>
            <div class="header-container" id="header-container">
                <header id="nav-departamento">
                    Departamento de Electrónica
                </header>
                <header id="nav-titulo">
                    -Consulta y búsqueda de chips-
                </header>
            </div>
        </nav>
    </div>

    <div class="container mt-4">
        <!-- Contenedor para el botón de retorno y el campo de búsqueda -->
        <div class="search-container">
            <div class="button-return mr-3">
                <a href="../forms/form-select_type.php">
                    <img src="../../resources/images/icon-return.png" alt="Volver">
                </a>
            </div>
            <input type="text" id="searchInput" class="form-control" placeholder="Buscar chip por cualquier campo..." onkeyup="filterTable()">
        </div>

        <div id="message" style="display: none;" class="alert alert-success"></div> <!-- Barra verde de notificación -->

        <!-- Tabla de chips -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered" id="chipsTable">
                <thead class="thead-dark">
                    <tr>
                        <th>NUMERO</th>
                        <th>COORDENADA</th>
                        <th>POSICION</th>
                        <th>PARTE</th>
                        <th>DESCRIP1</th>
                        <th>DESCRIP2</th>
                        <th>MANUAL</th>
                        <th>PAGINA</th>
                        <th>MINIMO</th>
                        <th>EXISTENCIA</th>
                        <th>PARTE_2</th>
                        <th>PEDIDOS</th>
                        <th>PEDIDO</th>
                        <th>PRECIO</th>
                        <th>FECHA_ADQ</th>
                        <th>PROVEEDOR</th>
                        <th>NO_PROVEED</th>
                        <th>NUEVAPOSI</th>
                        <th>REALIZOINV</th>
                        <th>FECHA_INV</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($chips)): ?>
                        <?php foreach ($chips as $chip): ?>
                            <tr id="row-<?= htmlspecialchars($chip['ART_NO'] ?? '') ?>">
                                <td class="editable"><?= htmlspecialchars($chip['NUMERO'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['COORDENADA'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['POSICION'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['PARTE'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['DESCRIP1'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['DESCRIP2'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['MANUAL'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['PAGINA'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['MINIMO'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['EXISTENCIA'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['PARTE_2'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['PEDIDOS'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['PEDIDO'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['PRECIO'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['FECHA_ADQ'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['PROVEEDOR'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['NO_PROVEED'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['NUEVAPOS'] ?? 'N/A') ?></td>
                                <td class="editable"><?= htmlspecialchars($chip['REALIZOINV'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($chip['fecha_inv'] ?? 'N/A') ?></td>
                                <td>
                                    <button class="btn btn-primary btn-sm edit" data-id="<?= htmlspecialchars($chip['numero'] ?? '') ?>">Editar</button>
                                    <button class="btn btn-danger btn-sm delete" data-id="<?= htmlspecialchars($chip['numero'] ?? '') ?>">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="20" class="text-center">No hay chips disponibles.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Controles de paginación -->
    <div class="pagination-buttons">
        <button id="prevBtn" class="btn btn-primary" onclick="prevPage()">Anterior</button>
        <span id="pageIndicator" class="page-indicator"></span>
        <button id="nextBtn" class="btn btn-primary" onclick="nextPage()">Siguiente</button>
    </div>

    <!-- Script de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Habilitar la edición de los campos al presionar el botón "Editar"
            $(document).on('click', '.edit', function() {
                var row = $(this).closest('tr');
                row.find('.editable').attr('contenteditable', 'true').focus(); // Hacer los campos editables

                $(this).removeClass('edit btn-primary').addClass('save btn-success').text('Guardar');
            });

            // Guardar los cambios al presionar "Guardar"
            $(document).on('click', '.save', function() {
                var row = $(this).closest('tr');
                var id = $(this).data('id');
                var numero = row.find('td:eq(0)').text();
                var coordenada = row.find('td:eq(1)').text();
                var posicion = row.find('td:eq(2)').text();
                var parte = row.find('td:eq(3)').text();
                var descripcion1 = row.find('td:eq(4)').text();
                var descripcion2 = row.find('td:eq(5)').text();
                var manual = row.find('td:eq(6)').text();
                var pagina = row.find('td:eq(7)').text();
                var minimo = row.find('td:eq(8)').text();
                var existencia = row.find('td:eq(9)').text();
                var parte2 = row.find('td:eq(10)').text();
                var pedidos = row.find('td:eq(11)').text();
                var pedido = row.find('td:eq(12)').text();
                var precio = row.find('td:eq(13)').text();
                var fecha_adq = row.find('td:eq(14)').text();
                var proveedor = row.find('td:eq(15)').text();
                var no_proveed = row.find('td:eq(16)').text();
                var nuevapos = row.find('td:eq(17)').text();
                var realizoinv = row.find('td:eq(18)').text();

                // Enviar los datos mediante AJAX
                $.ajax({
                    type: 'POST',
                    url: 'backend-update_chip.php',
                    data: {
                        numero: numero,
                        coordenada: coordenada,
                        posicion: posicion,
                        parte: parte,
                        descripcion1: descripcion1,
                        descripcion2: descripcion2,
                        manual: manual,
                        pagina: pagina,
                        minimo: minimo,
                        existencia: existencia,
                        parte2: parte2,
                        pedidos: pedidos,
                        pedido: pedido,
                        precio: precio,
                        fecha_adq: fecha_adq,
                        proveedor: proveedor,
                        no_proveed: no_proveed,
                        nuevapos: nuevapos,
                        realizoinv: realizoinv
                    },
                    success: function(response) {
                        alert('Datos actualizados con éxito');
                    }
                });
                $(this).removeClass('save btn-success').addClass('edit btn-primary').text('Editar');
                row.find('.editable').attr('contenteditable', 'false'); // Deshabilitar la edición
            });

            // Eliminar el chip
            $(document).on('click', '.delete', function() {
                if (confirm('¿Estás seguro de que deseas eliminar este chip?')) {
                    var id = $(this).data('id');
                    var row = $(this).closest('tr');

                    $.ajax({
                        type: 'POST',
                        url: 'backend-delete_chip.php',
                        data: { numero: id },
                        success: function(response) {
                            row.remove();
                        }
                    });
                }
            });
        });

        // Función para filtrar la tabla según la entrada del usuario
        function filterTable() {
            var input = document.getElementById("searchInput").value.toUpperCase();
            var table = document.getElementById("chipsTable");
            var tr = table.getElementsByTagName("tr");

            for (var i = 1; i < tr.length; i++) {
                var td = tr[i].getElementsByTagName("td");
                var isMatch = false;

                for (var j = 0; j < td.length - 1; j++) {
                    if (td[j] && td[j].innerText.toUpperCase().indexOf(input) > -1) {
                        isMatch = true;
                        break;
                    }
                }

                tr[i].style.display = isMatch ? "" : "none";
            }
        }
    </script>
</body>
</html>
