<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perriatra - Iniciar Sesion</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <script defer src="../js/script.js"></script>
</head>
<body class="login-page">

<div class="contenedor-form">
    <div class="cabecera-form">
        <img src="../img/logo_2.png" alt="Logo Perriatra" class="logo-login">
        <h2>Acceso Empleados</h2>
    </div>

    <?php
    if (isset($_SESSION['error_login'])) {
        echo "<div class='mensaje-php error-php'>" . $_SESSION['error_login'] . "</div>";
        unset($_SESSION['error_login']);
    }
    if (isset($_SESSION['exito_registro'])) {
        echo "<div class='mensaje-php exito-php'>" . $_SESSION['exito_registro'] . "</div>";
        unset($_SESSION['exito_registro']);
    }
    ?>

    <form action="../processes/login.proc.php" method="POST">

    <!-- id="formLogin" onsubmit="return validarLogin()" -->
        
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