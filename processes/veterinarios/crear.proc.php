<?php
// Arrancamos la sesion para poder gestionar los mensajes de aviso que le mostraremos al usuario
session_start();

// Hacemos una comprobacion de seguridad fundamental para que nadie que no este logueado pueda colar un registro
if (!isset($_SESSION['user_id'])) { 
    header("Location: ../../view/login.php"); 
    exit(); 
}

// Nos traemos nuestro archivo de configuracion para conectar con la base de datos
include '../../services/conexion.php';

// Nos aseguramos de que el proceso solo se ejecuta si el usuario ha pulsado el boton de crear en el formulario
if (isset($_POST['btn_crear_vet'])) {
    
    // Recogemos todos los datos que ha escrito el usuario y les aplicamos un trim para limpiar espacios en blanco sobrantes
    $nombre = trim($_POST['nombre_vet']);
    $apellidos = trim($_POST['apellidos_vet']);
    $especialidad = trim($_POST['especialidad']);
    $telefono = trim($_POST['telefono_vet']);
    $email = trim($_POST['email_vet']);
    $salario = trim($_POST['salario']);

    // Hacemos una pequeña verificacion extra para asegurar que el nombre y los apellidos no llegan vacios
    if (empty($nombre) || empty($apellidos)) {
        $_SESSION['error'] = "Los campos nombre y apellidos son obligatorios.";
        header("Location: ../../view/veterinarios/crear.php"); 
        exit();
    }

    // Si el usuario ha decidido poner un email comprobamos que realmente tenga un formato de correo valido
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "El correo electrónico no es válido.";
        header("Location: ../../view/veterinarios/crear.php"); 
        exit();
    }

    // Y lo mismo con el sueldo verificamos que si lo han rellenado sea realmente un numero y que no sea negativo
    if (!empty($salario) && (!is_numeric($salario) || $salario < 0)) {
        $_SESSION['error'] = "El salario debe ser un número positivo.";
        header("Location: ../../view/veterinarios/crear.php"); 
        exit();
    }
    
    // Como las consultas preparadas necesitan variables reales convertimos los campos vacios en nulos de verdad
    $emailParam = !empty($email) ? $email : null; 
    $salarioParam = !empty($salario) ? floatval($salario) : null; 

    // Este bloque es super importante para el guion porque nos evita tener veterinarios duplicados
    // Solo hacemos la comprobacion si realmente nos han pasado un correo electronico
    if ($emailParam !== null) {
        
        // Hacemos una busqueda rapida en la base de datos para ver si ese correo ya lo esta usando otro veterinario
        $sql_check = "SELECT id FROM veterinarios WHERE email = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "s", $emailParam);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);
        
        // Si el recuento nos da mas de 0 resultados significa que el correo ya existe
        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            
            // Asi que preparamos un mensaje de error cerramos la comprobacion y cortamos el proceso para que no se guarde nada
            $_SESSION['error'] = "No se puede registrar: Ya existe un veterinario con este email.";
            mysqli_stmt_close($stmt_check);
            mysqli_close($conn);
            header("Location: ../../view/veterinarios/crear.php"); 
            exit(); 
        }
        
        // Si el correo esta libre cerramos esta comprobacion y seguimos adelante
        mysqli_stmt_close($stmt_check);
    }

    // Como todos los datos son seguros y no hay duplicados preparamos la orden final para guardar al veterinario
    $sql = "INSERT INTO veterinarios (nombre, apellidos, especialidad, telefono, email, salario) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Vinculamos los datos indicando que tenemos cinco textos y un numero decimal para el salario
    mysqli_stmt_bind_param($stmt, "sssssd", $nombre, $apellidos, $especialidad, $telefono, $emailParam, $salarioParam);

    // Mandamos ejecutar el guardado y si la base de datos nos da luz verde devolvemos al usuario al listado con un aviso de exito
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "Veterinario registrado con éxito.";
        header("Location: ../../view/veterinarios/index.php");
    } else {
        // Si por algun casual la insercion fallase le avisamos para que vuelva a intentarlo
        $_SESSION['error'] = "Error al registrar en la base de datos.";
        header("Location: ../../view/veterinarios/crear.php");
    }
    
    // Limpiamos los recursos cerrando la consulta y la conexion
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
} else {
    // Y por ultimo si alguien ha llegado aqui forzando la url sin enviar el formulario lo bloqueamos por seguridad
    $_SESSION['error'] = "Acceso no autorizado.";
    header("Location: ../../view/veterinarios/crear.php");
    exit();
}
?>