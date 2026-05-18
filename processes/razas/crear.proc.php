<?php
// Iniciamos la sesión para poder guardar mensajes y usarlos en otras páginas  
session_start();

// Comprobamos si el usuario ha iniciado sesión
// Si no existe $_SESSION['user_id'] significa que no está logueado
// y lo mandamos al login para que no pueda entrar sin permiso
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../view/login.php");
    exit();
}

// Incluimos el archivo que conecta con la base de datos
include '../../services/conexion.php';

// Comprobamos si se ha enviado el formulario de crear raza
if (isset($_POST['btn_crear_raza'])) {

    // Recogemos los datos del formulario y quitamos espacios con trim()
    $nombre = trim($_POST['nombre_raza']);
    $fisicas = trim($_POST['caracteristicas_fisicas']);
    $comportamiento = trim($_POST['comportamiento']);

    // Comprobamos que ningún campo esté vacío
    // empty() devuelve true si la variable está vacía
    if (empty($nombre) || empty($fisicas) || empty($comportamiento)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../../view/razas/crear.php");
        exit();
    }

    // Comprobamos que cada campo tenga al menos 3 caracteres
    // strlen() devuelve el número de caracteres de un string
    if (strlen($nombre) < 3 || strlen($fisicas) < 3 || strlen($comportamiento) < 3) {
        $_SESSION['error'] = "Todos los campos deben tener al menos 3 caracteres.";
        header("Location: ../../view/razas/crear.php");
        exit();
    }

    // Comprobamos si la raza ya existe en la base de datos
    // para no guardar duplicados
    $sql_check = "SELECT id FROM razas WHERE nombre = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $nombre);
    mysqli_stmt_execute($stmt_check);

    // Necesitamos store_result para poder usar num_rows después
    mysqli_stmt_store_result($stmt_check);

    // Si num_rows es mayor que 0 significa que ya existe una raza con ese nombre
    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $_SESSION['error'] = "La raza '" . htmlspecialchars($nombre) . "' ya está registrada en el sistema.";
        mysqli_stmt_close($stmt_check);
        mysqli_close($conn);
        header("Location: ../../view/razas/crear.php");
        exit();
    }

    // Cerramos la consulta de comprobación antes de hacer la de inserción
    mysqli_stmt_close($stmt_check);

    // Preparamos la consulta de inserción con ? para evitar inyección SQL
    $sql = "INSERT INTO razas (nombre, caracteristicas_fisicas, comportamiento) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    // Comprobamos que la consulta se ha preparado bien antes de continuar
    if ($stmt) {
        // Enlazamos los datos con los ? de la consulta
        // "sss" significa que los 3 parámetros son strings (texto)
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $fisicas, $comportamiento);
        
        // Ejecutamos la inserción y comprobamos si ha ido bien
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['mensaje'] = "Raza creada con éxito.";
            header("Location: ../../view/razas/index.php");
        } else {
            // Si falla la ejecución guardamos el error en sesión
            $_SESSION['error'] = "Error al guardar en la base de datos.";
            header("Location: ../../view/razas/crear.php");
        }
        mysqli_stmt_close($stmt);
    } else {
        // Si falla la preparación de la consulta también avisamos
        $_SESSION['error'] = "Error de preparación de consulta.";
        header("Location: ../../view/razas/crear.php");
    }
    // Cerramos la conexión con la base de datos para liberar recursos
    mysqli_close($conn);
} else {
    // Si alguien entra a esta página sin enviar el formulario
    // lo mandamos al listado de razas
    header("Location: ../../view/razas/index.php");
    exit();
}
?>