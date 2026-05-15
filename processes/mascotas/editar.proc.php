<?php
// Iniciamos la sesión
session_start();

// Incluimos la conexión a la base de datos
include '../../services/conexion.php';

// Comprobamos si el botón de editar fue presionado
if (isset($_POST['btn_editar_masc'])) {

    // Recibimos los datos del formulario
    $chip = $_POST['chip_mascota'];
    $nombre = $_POST['nombre_masc'];
    $sexo = $_POST['sexo'];
    $id_raza = $_POST['id_raza'];
    $id_prop = $_POST['id_prop'];
    $id_vet = $_POST['id_vet'];

    // ¡AQUÍ ESTABA EL FALLO! 
    // Hemos cambiado id_raza por raza_id, id_propietario por propietario_id, etc.
    $sql = "UPDATE mascotas SET nombre=?, sexo=?, raza_id=?, propietario_id=?, veterinario_id=? WHERE chip=?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Comprobamos si hubo error al preparar la consulta
    if ($stmt === false) {
        $_SESSION['error'] = "Error al preparar la consulta.";
        header("Location: ../../view/mascotas/editar.php?chip=" . $chip);
        exit;
    }
    
    // Tipos: s=string, i=integer
    // Orden: nombre(s), sexo(s), raza_id(i), propietario_id(i), veterinario_id(i), chip(s)
    mysqli_stmt_bind_param($stmt, "ssiiis", $nombre, $sexo, $id_raza, $id_prop, $id_vet, $chip);

    // Ejecutamos la consulta
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "Mascota actualizada con éxito.";
        header("Location: ../../view/mascotas/index.php");
    } else {
        $_SESSION['error'] = "Error al actualizar los datos.";
        header("Location: ../../view/mascotas/editar.php?chip=" . $chip);
    }

    // Cerramos el statement y la conexión
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>