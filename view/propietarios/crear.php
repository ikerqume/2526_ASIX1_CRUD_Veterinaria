<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Propietario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/estilos.css">
    <link rel="icon" type="image/svg+xml" href="https://api.iconify.design/fa6-solid/paw.svg?color=%2328a745">
    <script defer src="../../js/script.js"></script>
</head>
<body>
    <header class="navbar">
        <div class="navbar-logo">
            <img src="../../img/logo.png" alt="Logo Clinica Perriatra">
            <h2>Clinica Perriatra</h2>
        </div>
        <nav class="navbar-enlaces">
            <a href="../index.php">Inicio</a>
            <a href="../mascotas/index.php">Mascotas</a>
            <a href="index.php" class="activo">Propietarios</a>
            <a href="../veterinarios/index.php">Veterinarios</a>
            <a href="../razas/index.php">Razas</a>
            <a href="../../processes/logout.proc.php" class="btn-cerrar">Cerrar Sesion</a>
        </nav>
    </header>

    <a href="javascript:history.back()" class="btn-volver">
        <i class="fa-solid fa-circle-arrow-left"></i>
    </a>

    <main class="contenedor-crud">
        <div class="cabecera-crud"><h2>Registrar Nuevo Propietario</h2></div>

        <?php if (isset($_SESSION['error'])) { ?>
            <div class="mensaje-php error-php">
                <?php
                    echo $_SESSION['error']; // Mostrar el mensaje de error

                    unset($_SESSION['error']); // Limpiar el mensaje para que no se repita en recargas
                ?>
            </div>
        <?php } ?>

        <?php if (isset($_SESSION['mensaje'])) { ?>
            <div class="alerta-exito">
                <?php
                    $mensajeExito = $_SESSION['mensaje'];

                    echo "<i class=\"fa-solid fa-circle-check\"></i> " . $mensajeExito; // Mostrar mensaje de éxito

                    unset($_SESSION['mensaje']); // Limpiar el mensaje para que no se repita en recargas
                ?>
            </div>
        <?php } ?>

        <div class="contenedor-form form-centrado">
            <form action="../../processes/propietarios/crear.proc.php" method="POST">

            <!-- id="formCrearProp" onsubmit="return validarCrearPropietario()" -->

                <div class="grupo-input">
                    <label for="nombre_prop">Nombre</label>
                    <input type="text" name="nombre_prop" id="nombre_prop" placeholder="Ej: Juan" onblur="validaNombreProp()">
                    <p id="errorNombreProp" class="texto-error"></p>
                    <!-- <div id="errorNombreProp" class="texto-error"></div> -->
                </div>
                <div class="grupo-input">
                    <label for="apellidos_prop">Apellidos</label>
                    <input type="text" name="apellidos_prop" id="apellidos_prop" placeholder="Ej: Pérez García" onblur="validaApellidosProp()">
                    <p id="errorApellidosProp" class="texto-error"></p>
                    <!-- <div id="errorApellidosProp" class="texto-error"></div> -->
                </div>
                <div class="grupo-input">
                    <label for="dni_prop">DNI</label>
                    <input type="text" name="dni_prop" id="dni_prop" placeholder="12345678Z" onblur="validaDNI()">
                    <p id="errorDNI" class="texto-error"></p>
                    <!-- <div id="errorDNI" class="texto-error"></div> -->
                </div>
                <div class="grupo-input">
                    <label for="email_prop">Correo Electrónico</label>
                    <input type="email" name="email_prop" id="email_prop" placeholder="juan@ejemplo.com" onblur="validaEmailProp()">
                    <p id="errorEmailProp" class="texto-error"></p>
                    <!-- <div id="errorEmailProp" class="texto-error"></div> -->
                </div>
                <div class="grupo-input">
                    <label for="telefono_prop">Teléfono</label>
                    <input type="tel" name="telefono_prop" id="telefono_prop" placeholder="Ej: 600123456" onblur="ValidaTelfProp()">
                    <p id="errorTelefonoProp" class="texto-error"></p>
                </div>
                <div class="grupo-botones">
                    <button type="submit" name="btn_crear_prop" class="btn-principal">Guardar Propietario</button>
                    <a href="index.php" class="btn-cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>