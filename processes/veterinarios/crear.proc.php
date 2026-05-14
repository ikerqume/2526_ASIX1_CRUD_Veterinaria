<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../../view/login.php"); exit(); }
include '../../services/conexion.php';

if (isset($_POST['btn_crear_vet'])) {
    $nombre = trim($_POST['nombre_vet']);
    $apellidos = trim($_POST['apellidos_vet']);
    $especialidad = trim($_POST['especialidad']);
    $telefono = trim($_POST['telefono_vet']);
    $email = trim($_POST['email_vet']);
    $salario = trim($_POST['salario']);

    if (empty($nombre) || empty($apellidos)) {
        $_SESSION['error'] = "Los campos nombre y apellidos son obligatorios.";
        header("Location: ../../view/veterinarios/crear.php"); exit();
    }

    // Validar email si se proporciona
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "El correo electrónico no es válido.";
        header("Location: ../../view/veterinarios/crear.php"); exit();
    }

    // Validar salario si se proporciona
    if (!empty($salario) && (!is_numeric($salario) || $salario < 0)) {
        $_SESSION['error'] = "El salario debe ser un número positivo.";
        header("Location: ../../view/veterinarios/crear.php"); exit();
    }
    
    // mysqli_stmt_bind_param necesita variables normales, no expresiones directas.
    $emailParam = !empty($email) ? $email : null; // si email está vacío, guardamos null
    $salarioParam = !empty($salario) ? floatval($salario) : null; // si salario está vacío, guardamos null

    $sql = "INSERT INTO veterinarios (nombre, apellidos, especialidad, telefono, email, salario) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssd", $nombre, $apellidos, $especialidad, $telefono, $emailParam, $salarioParam);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "Veterinario registrado con éxito.";
        header("Location: ../../view/veterinarios/index.php");
    } else {
        $_SESSION['error'] = "Error al registrar en la base de datos.";
        header("Location: ../../view/veterinarios/crear.php");
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}