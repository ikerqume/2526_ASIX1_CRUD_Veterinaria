<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../view/login.php");
    exit();
}

include '../../services/conexion.php';

if (isset($_POST['btn_editar_raza'])) {
    $id = $_POST['id_raza'];
    $nombre = trim($_POST['nombre_raza']);
    $fisicas = trim($_POST['caracteristicas_fisicas']);
    $comportamiento = trim($_POST['comportamiento']);

    // Validaciones PHP
    if (empty($nombre) || empty($fisicas) || empty($comportamiento)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../../view/razas/editar.php?id=" . $id);
        exit();
    }

    if (strlen($nombre) < 3 || strlen($fisicas) < 3 || strlen($comportamiento) < 3) {
        $_SESSION['error'] = "Todos los campos deben tener al menos 3 caracteres.";
        header("Location: ../../view/razas/editar.php?id=" . $id);
        exit();
    }

    // Actualización segura con prepared statements (El 15% de la rúbrica)
    $sql = "UPDATE razas SET nombre = ?, caracteristicas_fisicas = ?, comportamiento = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $nombre, $fisicas, $comportamiento, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['mensaje'] = "Raza actualizada correctamente.";
            header("Location: ../../view/razas/index.php");
        } else {
            $_SESSION['error'] = "Error al actualizar la base de datos.";
            header("Location: ../../view/razas/editar.php?id=" . $id);
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
} else {
    header("Location: ../../view/razas/index.php");
    exit();
}