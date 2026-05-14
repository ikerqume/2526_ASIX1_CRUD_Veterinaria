<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../view/login.php");
    exit();
}

include '../../services/conexion.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    // Borrado seguro (El 20% de la rúbrica)
    $sql = "DELETE FROM razas WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['mensaje'] = "La raza ha sido eliminada del sistema.";
        } else {
            $_SESSION['error'] = "No se pudo eliminar la raza.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Error en la consulta de borrado.";
    }
    mysqli_close($conn);
} else {
    $_SESSION['error'] = "ID no proporcionado.";
}

// Redirigir siempre de vuelta al listado
header("Location: ../../view/razas/index.php");
exit();