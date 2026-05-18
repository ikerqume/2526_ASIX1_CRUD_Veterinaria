<?php 
// Iniciamos o recuperamos la sesión nada más arrancar la página
// Esto es super necesario porque si el backend (registro.proc.php) detecta que el usuario 
// o el email ya existen, nos mandará de vuelta aquí con un mensaje de error guardado en la sesión
session_start(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perriatra - Registro</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="icon" type="image/svg+xml" href="https://api.iconify.design/fa6-solid/paw.svg?color=%2328a745">
    <script defer src="../js/script.js"></script>
</head>
<body>

<div class="contenedor-form">
    <div class="cabecera-form">
        <img src="../img/logo_2.png" alt="Logo Perriatra" class="logo-login">
        <h2>Registro de Personal</h2>
    </div>

    <?php
    // Aquí gestionamos la respuesta del servidor en caso de que el registro falle
    // Comprobamos si la variable de sesión 'error_registro' tiene algún mensaje guardado
    if (isset($_SESSION['error_registro'])) {
        // Si hay un error (por ejemplo, email duplicado), lo pintamos en un recuadro rojo bien visible
        echo "<div class='mensaje-php error-php'>" . $_SESSION['error_registro'] . "</div>";
        // Al instante limpiamos la variable con unset para que el cartelito no se quede ahí clavado
        // si el usuario decide refrescar la pantalla manualmente
        unset($_SESSION['error_registro']);
    }
    ?>

    <form action="../processes/registro.proc.php" method="POST">
        
        <div class="grupo-input">
            <label for="username">Nombre de Usuario</label>
            <input type="text" name="username" id="username" placeholder="Ej: DrLopez" onblur="validaUsername()">
            <div id="errorUsername" class="texto-error"></div>
        </div>

        <div class="grupo-input">
            <label for="email">Correo Electronico</label>
            <input type="email" name="email" id="email" placeholder="ejemplo@perriatra.com" onblur="validaEmail()">
            <div id="errorEmail" class="texto-error"></div>
        </div>

        <div class="grupo-input">
            <label for="password">Contrasena</label>
            <input type="password" name="password" id="password" placeholder="Minimo 8 caracteres, 1 mayus, 1 num" onblur="validaPassword()">
            <div id="errorPassword" class="texto-error"></div>
        </div>

        <button type="submit" name="btn_registro" class="btn-principal">Registrar</button>

        <a href="login.php" class="enlace-form">¿Ya tienes cuenta? Inicia sesion</a>
    </form>
</div>

</body>
</html>