<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../view/login.php");
    exit();
}

include '../../services/conexion.php';

if (isset($_POST['btn_crear_raza'])) {
    // Recoger datos y limpiar espacios en blanco
    $nombre = trim($_POST['nombre_raza']);
    $fisicas = trim($_POST['caracteristicas_fisicas']);
    $comportamiento = trim($_POST['comportamiento']);

    // 1. Validación PHP: Campos vacíos y mínimo 3 caracteres
    if (empty($nombre) || empty($fisicas) || empty($comportamiento)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../../view/razas/crear.php");
        exit();
    }

    if (strlen($nombre) < 3 || strlen($fisicas) < 3 || strlen($comportamiento) < 3) {
        $_SESSION['error'] = "Todos los campos deben tener al menos 3 caracteres.";
        header("Location: ../../view/razas/crear.php");
        exit();
    }

    // 2. Comprobar si la raza ya existe (El "pasito más" de calidad)
    $sql_check = "SELECT id FROM razas WHERE nombre = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $nombre);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $_SESSION['error'] = "La raza '" . htmlspecialchars($nombre) . "' ya está registrada en el sistema.";
        mysqli_stmt_close($stmt_check);
        mysqli_close($conn);
        header("Location: ../../view/razas/crear.php");
        exit();
    }
    mysqli_stmt_close($stmt_check);

    // 3. Inserción segura con sentencias preparadas
    $sql = "INSERT INTO razas (nombre, caracteristicas_fisicas, comportamiento) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $fisicas, $comportamiento);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['mensaje'] = "Raza creada con éxito.";
            header("Location: ../../view/razas/index.php");
        } else {
            $_SESSION['error'] = "Error al guardar en la base de datos.";
            header("Location: ../../view/razas/crear.php");
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Error de preparación de consulta.";
        header("Location: ../../view/razas/crear.php");
    }
    mysqli_close($conn);
} else {
    header("Location: ../../view/razas/index.php");
    exit();
}