<?php
// Añadir __DIR__ asegura que busque el config.php exactamente en la misma carpeta
include __DIR__ . '/config.php';

$conn = mysqli_connect($nombreservidor, $username, $password, $dbname);

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4"); 
?>