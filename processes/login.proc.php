<?php
session_start();
include '../services/conexion.php';

if (isset($_POST['btn_login'])) {
    $email = trim($_POST['email_login']);
    $password = $_POST['pass_login'];

    if (empty($email) || empty($password)) {
        $_SESSION['error_login'] = "Por favor, introduce correo y contrasena.";
        header("Location: ../view/login.php");
        exit();
    }

    $sql = "SELECT id, username, password FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($resultado)) {
            // Verificar el hash de la contrasena
            if (password_verify($password, $row['password'])) {
                // Credenciales correctas: creamos variables de sesion
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                
                // Redirigir al panel principal
                header("Location: ../view/vista.php");
                exit();
            } else {
                $_SESSION['error_login'] = "Contrasena incorrecta.";
                header("Location: ../view/login.php");
                exit();
            }
        } else {
            $_SESSION['error_login'] = "No existe ninguna cuenta con ese correo.";
            header("Location: ../view/login.php");
            exit();
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
} else {
    header("Location: ../view/login.php");
    exit();
}