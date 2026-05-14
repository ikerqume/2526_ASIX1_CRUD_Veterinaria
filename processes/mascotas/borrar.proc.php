<?php
session_start();
include '../../services/conexion.php';

// Si nos llega el chip por la URL
if (isset($_GET['chip'])) {
    $chip = $_GET['chip'];

    // Preparamos el borrado usando chip como identificador
    $sql = "DELETE FROM mascotas WHERE chip = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $chip);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "Mascota eliminada del sistema.";
    } else {
        $_SESSION['error'] = "No se pudo eliminar.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// Volvemos al listado
header("Location: ../../view/mascotas/index.php");
exit();
?>
