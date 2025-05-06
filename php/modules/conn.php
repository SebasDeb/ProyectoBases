<?php

$server = "localhost";
$user = "root";
$password = "nanmope";
$database = "almacen";
//root:2C603

try {
    // Creación de la conexión mediante PDO
    $connection = new PDO("mysql:host=$server;dbname=$database;charset=utf8", $user, $password);

    // Configuración de excepciones PDO
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "¡Conexión exitosa!"; <-- Esta línea debe estar eliminada o comentada
} catch (PDOException $e) {
    // Manejo de errores de conexión
    die("Conexión fallida: " . $e->getMessage());
}

?>
