<?php
// Lo primero de todo es arrancar la sesion porque la vamos a usar para pasarle los mensajes de error
// o el mensaje verde de exito para que inicie sesion cuando termine de registrarse
session_start();

// Nos traemos las credenciales para poder comunicarnos con la base de datos
include '../services/conexion.php';

// Verificamos que el usuario ha llegado hasta aqui pulsando el boton del formulario de verdad
if (isset($_POST['btn_registro'])) {
    
    // Recogemos el nombre y el correo limpiando los posibles espacios que haya escrito sin querer al principio o al final
    // La contraseña la recogemos tal cual porque los espacios en las contraseñas si que son validos
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Hacemos una validacion rapida en el lado del servidor por si alguien se ha saltado el HTML
    // Si falta cualquier dato paramos la maquina y lo devolvemos a la pantalla de registro
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error_registro'] = "Todos los campos son obligatorios.";
        header("Location: ../view/registro.php");
        exit();
    }

    // AQUI ESTA LA MAGIA DEL GUION: La encriptacion segura de la contraseña
    // Usamos la funcion nativa password_hash de PHP para generar un Hash seguro y no guardar el texto plano
    // Cumpliendo asi con la normativa del proyecto de no usar MD5
    $pass_hash = password_hash($password, PASSWORD_BCRYPT);

    // Preparamos nuestra orden de insercion de forma segura con las interrogaciones
    $sql = "INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    // Si la base de datos prepara bien la consulta seguimos adelante
    if ($stmt) {
        
        // Enlazamos las tres variables como cadenas de texto usando las letras s
        // Ojo aqui: le pasamos la contraseña ya encriptada y no la original
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $pass_hash);
        
        // Ejecutamos la consulta y verificamos si ha ido bien
        if (mysqli_stmt_execute($stmt)) {
            // Si funciona le preparamos el mensaje de bienvenida y lo mandamos directamente al login
            $_SESSION['exito_registro'] = "Registro completado. Inicia sesion.";
            header("Location: ../view/login.php");
        } else {
            // Si la base de datos devuelve error (por ejemplo email duplicado) mostramos un mensaje amigable
            $_SESSION['error_registro'] = "El usuario o email ya existe en el sistema.";
            header("Location: ../view/registro.php");
        }
        
        // Recogemos nuestra consulta de la memoria
        mysqli_stmt_close($stmt);
        
    } else {
        // Por si acaso el propio servidor fallara al intentar montar el SQL inicial
        $_SESSION['error_registro'] = "Error interno del servidor.";
        header("Location: ../view/registro.php");
    }
    
    // Y apagamos la conexion general
    mysqli_close($conn);
    
} else {
    // Si un listillo intenta entrar escribiendo la ruta en la barra de direcciones lo echamos fuera
    header("Location: ../view/registro.php");
    exit();
}
?>