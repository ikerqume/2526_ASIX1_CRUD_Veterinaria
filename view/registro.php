<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perriatra - Registro</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <script defer src="../js/script.js"></script>
</head>
<body>

<div class="contenedor-form">
    <div class="cabecera-form">
        <img src="../img/logo_2.png" alt="Logo Perriatra" class="logo-login">
        <h2>Registro de Personal</h2>
    </div>

    <?php
    if (isset($_SESSION['error_registro'])) {
        echo "<div class='mensaje-php error-php'>" . $_SESSION['error_registro'] . "</div>";
        unset($_SESSION['error_registro']);
    }
    ?>

    <form action="../processes/registro.proc.php" method="POST">

    <!-- id="formRegistro" onsubmit="return validarRegistro()" -->
        
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