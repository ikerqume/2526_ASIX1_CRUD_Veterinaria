<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../../view/login.php"); exit(); }
include '../../services/conexion.php';

if (isset($_POST['id_vet'])) {
    $id = $_POST['id_vet'];
    $nombre = trim($_POST['nombre_vet']);
    $apellidos = trim($_POST['apellidos_vet']);
    $especialidad = trim($_POST['especialidad']);
    $telefono = trim($_POST['telefono_vet']);
    $email = trim($_POST['email_vet']);
    $salario = trim($_POST['salario']);

    // Validación PHP (El detalle de calidad)
    if (strlen($nombre) < 3 || strlen($apellidos) < 3) {
        $_SESSION['error'] = "Nombre y apellidos deben tener al menos 3 caracteres.";
        header("Location: ../../view/veterinarios/editar.php?id=" . $id);
        exit();
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "El correo electrónico no es válido.";
        header("Location: ../../view/veterinarios/editar.php?id=" . $id);
        exit();
    }

    if (!empty($salario) && (!is_numeric($salario) || $salario < 0)) {
        $_SESSION['error'] = "El salario debe ser un número positivo.";
        header("Location: ../../view/veterinarios/editar.php?id=" . $id);
        exit();
    }

    $emailParam = !empty($email) ? $email : null;
    $salarioParam = !empty($salario) ? floatval($salario) : null;

    $sql = "UPDATE veterinarios SET nombre = ?, apellidos = ?, especialidad = ?, telefono = ?, email = ?, salario = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssdi", $nombre, $apellidos, $especialidad, $telefono, $emailParam, $salarioParam, $id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "Veterinario actualizado correctamente.";
        header("Location: ../../view/veterinarios/index.php");
    } else {
        $_SESSION['error'] = "Error al actualizar.";
        header("Location: ../../view/veterinarios/editar.php?id=" . $id);
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}