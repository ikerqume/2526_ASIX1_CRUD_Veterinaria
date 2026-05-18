<?php 
// Aquí arriba lo primero que hacemos es abrir o recuperar la sesión activa del servidor
// Es obligatorio ponerlo al principio del todo porque si el usuario viene rebotado de un login fallido 
// o de un registro completado con éxito, necesitaremos leer los mensajes temporales de la sesión
session_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perriatra - Iniciar Sesion</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="icon" type="image/svg+xml" href="https://api.iconify.design/fa6-solid/paw.svg?color=%2328a745">
    <script defer src="../js/script.js"></script>
</head>
<body class="login-page">

<div class="contenedor-form">
    <div class="cabecera-form">
        <img src="../img/logo_2.png" alt="Logo Perriatra" class="logo-login">
        <h2>Acceso Empleados</h2>
    </div>

    <?php
    // Este bloque de PHP es fundamental de cara al guion del proyecto para gestionar las alertas del servidor
    // Primero miramos si el procesador del login detectó algún fallo y nos guardó un mensaje de error
    if (isset($_SESSION['error_login'])) {
        // Si existe, pintamos un recuadro rojo amigable en pantalla usando las clases que definimos en el CSS
        echo "<div class='mensaje-php error-php'>" . $_SESSION['error_login'] . "</div>";
        // Justo después de mostrarlo usamos unset para borrarlo de la memoria de la sesión
        // Así nos aseguramos de que el mensaje desaparezca si el usuario decide refrescar la página manualmente
        unset($_SESSION['error_login']);
    }
    
    // Hacemos exactamente lo mismo por si el usuario viene de la pantalla de registro tras crear su cuenta con éxito
    // Si la variable exito_registro existe, pintamos el recuadro de color verde para avisarle de que ya puede entrar
    if (isset($_SESSION['exito_registro'])) {
        echo "<div class='mensaje-php exito-php'>" . $_SESSION['exito_registro'] . "</div>";
        unset($_SESSION['exito_registro']); // Y lo limpiamos de la memoria por la misma razón
    }
    ?>

    <form action="../processes/login.proc.php" method="POST">
        
        <div class="grupo-input">
            <label for="email_login">Correo Electronico</label>
            <input type="email" name="email_login" id="email_login" placeholder="ejemplo@perriatra.com" onblur="validaEmailLogin()">
            <div id="errorEmailLogin" class="texto-error"></div>
        </div>

        <div class="grupo-input">
            <label for="pass_login">Contrasena</label>
            <input type="password" name="pass_login" id="pass_login" placeholder="********" onblur="validaPassLogin()">
            <div id="errorPassLogin" class="texto-error"></div>
        </div>

        <button type="submit" name="btn_login" class="btn-principal">Iniciar Sesion</button>

        <a href="registro.php" class="enlace-form">¿Nuevo trabajador? Registrate aqui</a>
    </form>
</div>

</body>
</html>