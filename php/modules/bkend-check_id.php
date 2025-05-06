<?php
//root:2C603
// Incluir el archivo de conexión a la base de datos
include_once 'conn.php';

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Recoger y sanitizar el ID del formulario
    $user_id = filter_input(INPUT_POST, 'user-id', FILTER_SANITIZE_STRING);

    if (empty($user_id)) {
        die('El ID no puede estar vacio');
    }

    try {
        $sql_check = "SELECT * FROM usuarios WHERE NUMERO = :user_id";
        $stmt = $connection->prepare($sql_check);
        $stmt->execute([':user_id' => $user_id]);
    
        // Verificar si el ID existe en la base de datos
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $puesto = strtolower($user['PUESTO']);

            if ($puesto === 'becario' || 'asistente' || 'jefe de laboratorio' || 'profesor')
                header("Location: ../forms/form-select_type.php");

            // Si el ID existe, redirigir al formulario de edición con los datos del usuario
           // header("Location: ../forms/form-edit_user.php?user_id=" . urlencode($user_id));
            exit;
        } else {
            //181763
            // Si el ID no existe, redirigir al formulario para añadir un nuevo usuario
            header("Location: ../forms/form-add_user.php?user_id=" . urlencode($user_id));
            exit;
        }
    }
    catch (PDOException $e) {
        die("Error de base de datos: " . $e->getMessage());
    }
   

} else {
    // Si el formulario no fue enviado correctamente
    die("Solicitud no válida.");
}

?>
