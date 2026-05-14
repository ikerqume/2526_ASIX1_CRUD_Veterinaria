<?php
session_start();
if (!isset($_SESSION['user_id'])) { exit(); }
include '../../services/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM veterinarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "Veterinario eliminado.";
    } else {
        $_SESSION['error'] = "No se pudo eliminar el registro.";
    }
    mysqli_stmt_close($stmt);
}
header("Location: ../../view/veterinarios/index.php");
exit();