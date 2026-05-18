<?php 
// Iniciamos la sesión para poder usar las variables de sesión
session_start();

// Comprobamos si el usuario está logueado
// Si no tiene user_id en sesión lo mandamos al login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../view/login.php");
    exit();
}

// Incluimos la conexión a la base de datos
include '../../services/conexion.php';

// Comprobamos si se ha enviado el formulario de editar raza
if (isset($_POST['btn_editar_raza'])) {

// Recogemos los datos del formulario
// trim() quita los espacios en blanco al inicio y al final
    $id = $_POST['id_raza'];
    $nombre = trim($_POST['nombre_raza']);
    $fisicas = trim($_POST['caracteristicas_fisicas']);
    $comportamiento = trim($_POST['comportamiento']);

    // Comprobamos que ningún campo esté vacío
    // empty() devuelve true si la variable está vacía
    if (empty($nombre) || empty($fisicas) || empty($comportamiento)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../../view/razas/editar.php?id=" . $id);
        exit();
    }

    // Comprobamos que cada campo tenga al menos 3 caracteres
    // strlen() devuelve el número de caracteres de un string
    if (strlen($nombre) < 3 || strlen($fisicas) < 3 || strlen($comportamiento) < 3) {
        $_SESSION['error'] = "Todos los campos deben tener al menos 3 caracteres.";
        header("Location: ../../view/razas/editar.php?id=" . $id);
        exit();
    }

    // Preparamos la consulta UPDATE con ? para evitar inyección SQL
    // "sssi" = 3 strings y 1 integer (el id del final)
    $sql = "UPDATE razas SET nombre = ?, caracteristicas_fisicas = ?, comportamiento = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Comprobamos que la consulta se ha preparado correctamente
    if ($stmt) {

    // Enlazamos los datos con los ? de la consulta
    // el orden tiene que coincidir exactamente con los ? de arriba
        mysqli_stmt_bind_param($stmt, "sssi", $nombre, $fisicas, $comportamiento, $id);
    
        // Ejecutamos la consulta y comprobamos si ha ido bien
        if (mysqli_stmt_execute($stmt)) {
            // Si falla guardamos el error en sesión y volvemos al formulario
            $_SESSION['mensaje'] = "Raza actualizada correctamente.";
            header("Location: ../../view/razas/index.php");
        } else {
            $_SESSION['error'] = "Error al actualizar la base de datos.";
            header("Location: ../../view/razas/editar.php?id=" . $id);
        }

        // Cerramos la consulta preparada para liberar memoria
        mysqli_stmt_close($stmt);
    }

    // Cerramos la conexión con la base de datos
    mysqli_close($conn);
} else {
    // Si alguien entra sin enviar el formulario lo mandamos al listado
    header("Location: ../../view/razas/index.php");
    exit();
}
// Nota: la ç del final del archivo original es un error tipográfico, hay que borrarla

?>