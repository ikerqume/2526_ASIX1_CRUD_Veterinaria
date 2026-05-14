<?php
session_start();
include '../../services/conexion.php';

// Comprobamos que se ha pulsado el botón de actualizar
if (isset($_POST['btn_editar_prop'])) {

    // Recogemos los datos del formulario
    $id = $_POST['id_prop'];
    $nombre = $_POST['nombre_prop'];
    $apellidos = $_POST['apellidos_prop'];
    $dni = $_POST['dni_prop'];
    $email = $_POST['email_prop'];
    $telefono = trim($_POST['telefono_prop']);

    // Validación sencilla: que no estén vacíos y el DNI tenga cara y ojos
    if (empty($nombre) || empty($dni) || empty($email)) {
        $_SESSION['error'] = "El nombre, DNI y Email son obligatorios.";
        header("Location: ../../view/propietarios/editar.php?id=" . $id);
        exit();
    }

    // Consulta de actualización segura
    $sql = "UPDATE propietarios SET nombre = ?, apellidos = ?, dni = ?, telefono = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // "sssssi" -> 5 strings y 1 integer (el ID del final)
    mysqli_stmt_bind_param($stmt, "sssssi", $nombre, $apellidos, $dni, $telefono, $email, $id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "Datos del propietario actualizados.";
        header("Location: ../../view/propietarios/index.php");
    } else {
        $_SESSION['error'] = "Hubo un error al guardar los cambios.";
        header("Location: ../../view/propietarios/editar.php?id=" . $id);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {
    header("Location: ../../view/propietarios/index.php");
}
?>