<?php
// Iniciamos la sesión para poder guardar mensajes de error o éxito
session_start();

// Incluimos el archivo que tiene la conexión a la base de datos
include '../../services/conexion.php';

// Comprobamos si se ha pulsado el botón de editar del formulario
// isset() devuelve true si la variable existe y no es null
if (isset($_POST['btn_editar_prop'])) {

// Recogemos los datos que ha enviado el formulario
    $id = $_POST['id_prop'];
    $nombre = trim($_POST['nombre_prop'] ?? '');
    $apellidos = trim($_POST['apellidos_prop'] ?? '');
    $dni = trim($_POST['dni_prop'] ?? '');
    $email = trim($_POST['email_prop'] ?? '');
    $telefono = trim($_POST['telefono_prop'] ?? '');


    // Comprobamos que los campos obligatorios no estén vacíos
    // empty() devuelve true si la variable está vacía
    if (empty($nombre) || empty($dni) || empty($email)) {
        $_SESSION['error'] = "El nombre, DNI y Email son obligatorios.";
        header("Location: ../../view/propietarios/editar.php?id=" . $id);
        exit();
    }

    // Preparamos la consulta UPDATE para modificar los datos en la BD
    // Usamos ? en vez de poner los datos directamente, esto se llama consulta preparada
    // y sirve para evitar inyección SQL (que alguien meta código malicioso por el formulario)
    $sql = "UPDATE propietarios SET nombre = ?, apellidos = ?, dni = ?, telefono = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Enlazamos los datos con los ? de la consulta
    // "sssssi" indica el tipo de cada dato: s = string (texto), i = integer (número)
    // El orden tiene que coincidir con los ? de arriba
    mysqli_stmt_bind_param($stmt, "sssssi", $nombre, $apellidos, $dni, $telefono, $email, $id);

    // Ejecutamos la consulta y miramos si ha funcionado
    if (mysqli_stmt_execute($stmt)) {
        // Si ha ido bien guardamos un mensaje de éxito en la sesión
        $_SESSION['mensaje'] = "Datos del propietario actualizados.";
        // Redirigimos al listado de propietarios
        header("Location: ../../view/propietarios/index.php");
    } else {
        // Si ha fallado guardamos un mensaje de error en la sesión
        $_SESSION['error'] = "Hubo un error al guardar los cambios.";
        header("Location: ../../view/propietarios/editar.php?id=" . $id);
    }

    // Cerramos la consulta y la conexión para liberar memoria
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {
    // Si alguien accede a esta página sin enviar el formulario
    // lo mandamos directamente al listado
    header("Location: ../../view/propietarios/index.php");
}
?>