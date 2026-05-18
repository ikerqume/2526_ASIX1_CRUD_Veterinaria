<?php
// Empezamos abriendo la sesion para poder usar nuestros mensajes flotantes de aviso
session_start();

// Hacemos nuestra comprobacion de seguridad habitual para expulsar a cualquiera que intente acceder sin hacer login
if (!isset($_SESSION['user_id'])) { 
    header("Location: ../../view/login.php"); 
    exit(); 
}

// Nos traemos la conexion a la base de datos
include '../../services/conexion.php';

// Comprobamos que este codigo solo se ejecute si realmente hemos recibido el identificador del veterinario desde el formulario
if (isset($_POST['id_vet'])) {
    
    // Recogemos el identificador para saber a que ficha exacta le vamos a hacer el UPDATE
    $id = $_POST['id_vet'];
    
    // Recogemos el resto de datos nuevos que ha escrito el usuario limpiando los espacios sobrantes con trim
    $nombre = trim($_POST['nombre_vet']);
    $apellidos = trim($_POST['apellidos_vet']);
    $especialidad = trim($_POST['especialidad']);
    $telefono = trim($_POST['telefono_vet']);
    $email = trim($_POST['email_vet']);
    $salario = trim($_POST['salario']);

    // Aqui aplicamos unas validaciones de calidad directamente en el servidor con PHP
    // Primero comprobamos que el nombre y los apellidos tengan sentido y midan al menos 3 caracteres
    if (strlen($nombre) < 3 || strlen($apellidos) < 3) {
        // Si no cumplen la condicion guardamos el error y devolvemos al usuario a la ficha de este veterinario en concreto
        $_SESSION['error'] = "Nombre y apellidos deben tener al menos 3 caracteres.";
        header("Location: ../../view/veterinarios/editar.php?id=" . $id);
        exit();
    }

    // Hacemos lo mismo con el email verificando que si han escrito algo sea realmente un formato de correo valido
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "El correo electrónico no es válido.";
        header("Location: ../../view/veterinarios/editar.php?id=" . $id);
        exit();
    }

    // Y por supuesto si han puesto un salario verificamos que sea un numero positivo y no una letra o un numero bajo cero
    if (!empty($salario) && (!is_numeric($salario) || $salario < 0)) {
        $_SESSION['error'] = "El salario debe ser un número positivo.";
        header("Location: ../../view/veterinarios/editar.php?id=" . $id);
        exit();
    }

    // Como la base de datos permite que el correo y el salario esten vacios los convertimos a nulos reales si no nos los envian
    $emailParam = !empty($email) ? $email : null;
    $salarioParam = !empty($salario) ? floatval($salario) : null;

    // Preparamos nuestra orden de actualizacion usando las interrogaciones para protegernos de inyecciones de codigo
    $sql = "UPDATE veterinarios SET nombre = ?, apellidos = ?, especialidad = ?, telefono = ?, email = ?, salario = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Vinculamos los datos respetando sus tipos exactos
    // Tenemos cinco strings para los textos una letra d para el decimal del salario y una i para el identificador
    mysqli_stmt_bind_param($stmt, "sssssdi", $nombre, $apellidos, $especialidad, $telefono, $emailParam, $salarioParam, $id);

    // Mandamos la orden a la base de datos
    if (mysqli_stmt_execute($stmt)) {
        // Si la base de datos confirma que ha actualizado la fila preparamos el mensaje verde y vamos al listado principal
        $_SESSION['mensaje'] = "Veterinario actualizado correctamente.";
        header("Location: ../../view/veterinarios/index.php");
    } else {
        // Si hay algun problema avisamos del error y le mantenemos en el formulario de edicion
        $_SESSION['error'] = "Error al actualizar.";
        header("Location: ../../view/veterinarios/editar.php?id=" . $id);
    }
    
    // Por ultimo cerramos tanto la peticion como la conexion a la base de datos para no consumir memoria del servidor
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>