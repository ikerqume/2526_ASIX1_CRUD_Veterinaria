<?php
// Iniciamos la sesión para poder usar variables SESSION
session_start();

// Comprobamos si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) 

    // Si no ha iniciado sesión, lo redirigimos al login
    { header("Location: ../../view/login.php"); 

    // Finalizamos la ejecución del script
    exit(); 

}

// Incluimos el archivo de conexión a la base de datos
include '../../services/conexion.php';

// Verificamos si se ha enviado el formulario
if (isset($_POST['btn_crear_prop'])) {

// Guardamos y limpiamos los datos recibidos por POST
    $nombre = trim($_POST['nombre_prop']);
    $apellidos = trim($_POST['apellidos_prop']);
    $dni = trim($_POST['dni_prop']);
    $email = trim($_POST['email_prop']);
    $telefono = trim($_POST['telefono_prop']);

    // Validación básica PHP
    if (strlen($nombre) < 3 || strlen($dni) < 9 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {

        // Guardamos un mensaje de error en la sesión
        $_SESSION['error'] = "Datos inválidos. Por favor, revisa todos los campos.";

        // Redirigimos al formulario de creación    
        header("Location: ../../view/propietarios/crear.php"); exit();
    }

    // Comprobamos si el DNI o el Email ya existen en la base de datos
    $check = mysqli_query($conn, "SELECT id FROM propietarios WHERE dni = '$dni' OR email = '$email'");

    // Si existe algún registro repetido
    if (mysqli_num_rows($check) > 0) {

    // Guardamos mensaje de error
        $_SESSION['error'] = "El DNI o el Email ya están registrados.";

        // Redirigimos al formulario
        header("Location: ../../view/propietarios/crear.php"); exit();
    }

    // Consulta SQL para insertar un nuevo propietario
    $sql = "INSERT INTO propietarios (nombre, apellidos, dni, telefono, email) VALUES (?, ?, ?, ?, ?)";

    // Preparamos la consulta
    $stmt = mysqli_prepare($conn, $sql);

    // Vinculamos los parámetros
    // "sssss" indica que todos los valores son string
    mysqli_stmt_bind_param($stmt, "sssss", $nombre, $apellidos, $dni, $telefono, $email);

    // Ejecutamos la consulta
    if (mysqli_stmt_execute($stmt)) {

    // Guardamos mensaje de éxito
        $_SESSION['mensaje'] = "Propietario registrado con éxito.";

        // Redirigimos al listado de propietarios
        header("Location: ../../view/propietarios/index.php");
    } else {

    // Guardamos mensaje de error
        $_SESSION['error'] = "Error en el registro.";

        // Redirigimos al formulario
        header("Location: ../../view/propietarios/crear.php");
    }

    // Cerramos la consulta preparada
    mysqli_stmt_close($stmt);
    
    // Cerramos la conexión con la base de datos
    mysqli_close($conn);
}