<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de ID de Usuario</title>
    <!-- Enlace a hojas de estilo -->
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/styles-adduser.css">
    <link rel="stylesheet" href="../../css/styles-check_id.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="../js/script-adduser.js"></script>
</head>
<body>
    <!-- Barra de navegación principal -->
    <div>
        <nav id="main-nav">
            <div class="logo-container">
                <img src="../../resources/images/logo-udlap.png" alt="" class="img-fluid" id="logo-udlap">
            </div>
            <div class="header-container" id="header-container">
                <header id="nav-departamento">
                    Departamento de Electrónica
                </header>
                <header id="nav-titulo">
                    -Registro y modificación de usuarios-
                </header>
            </div>
        </nav>
    </div>
    <br>
    <!-- Contenedor principal para el formulario -->
    <div class="container-general">
        <div class="button-return">
            <a href="../../index.html">
                <img src="../../resources/images/icon-return.png" alt="">
            </a>
        </div>
        <div class="box-white">
            <form action="http://localhost/sistema_almacen/php/modules/bkend-check_id.php" method="POST", id="idForm">
                <div class="form-group">
                    <label for="user-id">Ingrese su ID</label>
                    <input type="text" class="form-control" id="user-id" name="user-id" required placeholder="Ingrese su ID" pattern="[0-9]+">
                </div>
                <button type="submit" class="btn btn-primary btn-lg">Verificar</button>
                <div id="loading" style="display:none;">
                    <p>Verificando ID, porfavor espere</p>
                </div>
                <br><br>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('idForm').addEventListener('submit', function() {
            document.getElementById('loading').style.display = 'block';
        });

        console.log("HOLA")
    </script>
</body>
</html>