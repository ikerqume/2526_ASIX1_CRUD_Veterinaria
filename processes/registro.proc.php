<?php
session_start();
include '../services/conexion.php';

if (isset($_POST['btn_registro'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validacion basica backend
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error_registro'] = "Todos los campos son obligatorios.";
        header("Location: ../view/registro.php");
        exit();
    }

    // Encriptacion de contrasena obligatoria (password_hash)
    $pass_hash = password_hash($password, PASSWORD_BCRYPT);

    // Preparar insercion
    $sql = "INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $pass_hash);
        
        try {
            mysqli_stmt_execute($stmt);
            $_SESSION['exito_registro'] = "Registro completado. Inicia sesion.";
            header("Location: ../view/login.php");
        } catch (mysqli_sql_exception $e) {
            // Captura errores como email o usuario duplicado
            $_SESSION['error_registro'] = "El usuario o email ya existe en el sistema.";
            header("Location: ../view/registro.php");
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error_registro'] = "Error interno del servidor.";
        header("Location: ../view/registro.php");
    }
    mysqli_close($conn);
} else {
    header("Location: ../view/registro.php");
    exit();
}