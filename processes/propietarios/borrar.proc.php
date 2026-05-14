<?php
// Iniciamos la sesión para poder usar variables SESSION
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../view/login.php");
    exit();
}

// Incluimos el archivo de conexión a la base de datos
include '../../services/conexion.php';

// Verificamos que nos llegue el ID por la URL
if (isset($_GET['id'])) {

// Guardamos el ID recibido en una variable
    $id = $_GET['id'];

    // Consulta SQL para eliminar un propietario según su ID
    // Se usa una consulta preparada por seguridad
    $sql = "DELETE FROM propietarios WHERE id = ?";

    // Preparamos la consulta
    $stmt = mysqli_prepare($conn, $sql);

    // Vinculamos el parámetro a la consulta
    // "i" indica que el valor es un entero
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Ejecutamos la consulta
    if (mysqli_stmt_execute($stmt)) {

        // Guardamos un mensaje de éxito en la sesión
        $_SESSION['mensaje'] = "Propietario eliminado correctamente.";
    } else {
        // Guardamos un mensaje de error en la sesió
        $_SESSION['error'] = "No se ha podido eliminar al propietario (puede que tenga mascotas asignadas).";
    }

    // Cerramos la consulta preparada
    mysqli_stmt_close($stmt);
}

// Volvemos al listado de dueños
header("Location: ../../view/propietarios/index.php");

// Finalizamos la ejecución del script
exit();
?>