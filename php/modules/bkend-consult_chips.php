<?php 
// Incluir el archivo de conexión a la base de datos
include_once 'conn.php';

// Obtener los datos de la tabla 'chips'
function getChips($connection) {
    $sql = "SELECT * FROM chips";
    $stmt = $connection->prepare($sql);
    
    // Verificar errores en la ejecución de la consulta
    if (!$stmt->execute()) {
        echo "Error executing query: " . $stmt->errorInfo()[2];
        return false; // Retornar false en caso de error
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener un chip específico por su número
function getChipByNumber($connection, $numero) {
    $sql = "SELECT * FROM chips WHERE numero = :numero";
    $stmt = $connection->prepare($sql);
    
    // Verificar errores en la ejecución de la consulta
    if (!$stmt->execute([':numero' => $numero])) {
        echo "Error executing query: " . $stmt->errorInfo()[2];
        return false;
    }

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Actualizar un chip
function updateChip($connection, $numero, $coordenada, $posicion, $parte, $descripcion1, $descripcion2, $manual, $pagina, $minimo, $existencia, $parte_2, $pedidos, $pedido, $precio, $fecha_adq, $proveedor, $no_proveed, $nuevapos, $realizoinv, $fecha_inv) {
    $sql = "UPDATE chips SET coordenada = :coordenada, posicion = :posicion, parte = :parte, descripcion1 = :descripcion1, 
            descripcion2 = :descripcion2, manual = :manual, pagina = :pagina, minimo = :minimo, existencia = :existencia,
            parte_2 = :parte_2, pedidos = :pedidos, pedido = :pedido, precio = :precio, fecha_adq = :fecha_adq,
            proveedor = :proveedor, no_proveed = :no_proveed, nuevapos = :nuevapos, realizoinv = :realizoinv, 
            fecha_inv = :fecha_inv WHERE numero = :numero";
    $stmt = $connection->prepare($sql);
    
    // Verificar errores en la ejecución de la consulta
    if (!$stmt->execute([  
        ':numero' => $numero,
        ':coordenada' => $coordenada,
        ':posicion' => $posicion,
        ':parte' => $parte,
        ':descripcion1' => $descripcion1,
        ':descripcion2' => $descripcion2,
        ':manual' => $manual,
        ':pagina' => $pagina,
        ':minimo' => $minimo,
        ':existencia' => $existencia,
        ':parte_2' => $parte_2,
        ':pedidos' => $pedidos,
        ':pedido' => $pedido,
        ':precio' => $precio,
        ':fecha_adq' => $fecha_adq,
        ':proveedor' => $proveedor,
        ':no_proveed' => $no_proveed,
        ':nuevapos' => $nuevapos,
        ':realizoinv' => $realizoinv,
        ':fecha_inv' => $fecha_inv
    ])) {
        echo "Error updating chip: " . $stmt->errorInfo()[2];
    }
}

// Eliminar un chip
function deleteChip($connection, $numero) {
    $sql = "DELETE FROM chips WHERE numero = :numero";
    $stmt = $connection->prepare($sql);
    
    // Verificar errores en la ejecución de la consulta
    if (!$stmt->execute([':numero' => $numero])) {
        echo "Error deleting chip: " . $stmt->errorInfo()[2];
    }
}

// Procesar la edición o eliminación dependiendo de la acción
function processRequest($connection) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Procesar la edición
        if (isset($_POST['edit'])) {
            $numero = $_POST['numero'];
            $coordenada = $_POST['coordenada'];
            $posicion = $_POST['posicion'];
            $parte = $_POST['parte'];
            //181763
            $descripcion1 = $_POST['descripcion1'];
            $descripcion2 = $_POST['descripcion2'];
            $manual = $_POST['manual'];
            $pagina = $_POST['pagina'];
            $minimo = $_POST['minimo'];
            $existencia = $_POST['existencia'];
            $parte_2 = $_POST['parte_2'];
            $pedidos = $_POST['pedidos'];
            $pedido = $_POST['pedido'];
            $precio = $_POST['precio'];
            $fecha_adq = $_POST['fecha_adq'];
            $proveedor = $_POST['proveedor'];
            $no_proveed = $_POST['no_proveed'];
            $nuevapos = $_POST['nuevapos'];
            $realizoinv = $_POST['realizoinv'];
            $fecha_inv = $_POST['fecha_inv'];
            updateChip($connection, $numero, $coordenada, $posicion, $parte, $descripcion1, $descripcion2, $manual, $pagina, $minimo, $existencia, $parte_2, $pedidos, $pedido, $precio, $fecha_adq, $proveedor, $no_proveed, $nuevapos, $realizoinv, $fecha_inv);
            header("Location: search_chips.php?success=edit");
            exit;
        }

        // Procesar la eliminación
        if (isset($_POST['delete'])) {
            $numero = $_POST['numero'];
            deleteChip($connection, $numero);
            header("Location: search_chips.php?success=delete");
            exit;
        }
    }
}
?>

