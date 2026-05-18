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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nueva Raza</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/estilos.css">
    <link rel="icon" type="image/svg+xml" href="https://api.iconify.design/fa6-solid/paw.svg?color=%2328a745">
    <script defer src="../../js/script.js"></script>
</head>
<body>

    <header class="navbar">
    <div class="navbar-logo">
        <img src="../../img/logo.png" alt="Logo Perriatra">
        <h2>Clinica Perriatra</h2>
    </div>
    <nav class="navbar-enlaces">
        <a href="../vista.php">Inicio</a>
        <a href="../mascotas/index.php">Mascotas</a>
        <a href="../propietarios/index.php">Propietarios</a>
        <a href="../veterinarios/index.php">Veterinarios</a>
        <a href="index.php" class="activo">Razas</a>
        <a href="../../processes/logout.proc.php" class="btn-cerrar">Cerrar Sesion</a>
    </nav>
</header>

    <a href="javascript:history.back()" class="btn-volver">
        <i class="fa-solid fa-circle-arrow-left"></i>
    </a>

    <main class="contenedor-crud">
        <div class="cabecera-crud">
            <h2>Registrar Nueva Raza</h2>
        </div>

        <?php if (isset($_SESSION['error'])) { ?>
            <div class='mensaje-php error-php'>
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
            <form action="../../processes/razas/crear.proc.php" method="POST">

            <!-- id="formCrearRaza" onsubmit="return validarCrearRaza()" -->
                
                <div class="grupo-input">
                    <label for="nombre_raza">Nombre de la Raza</label>
                    <input type="text" name="nombre_raza" id="nombre_raza" placeholder="Ej: Pastor Alemán" onblur="validaNombreRaza()">
                    <p id="errorNombreRaza" class="texto-error"></p>
                    <!-- <div id="errorNombreRaza" class="texto-error"></div> -->
                </div>

                <div class="grupo-input">
                    <label for="caracteristicas_fisicas">Características Físicas</label>
                    <textarea name="caracteristicas_fisicas" id="caracteristicas_fisicas" rows="4" placeholder="Tamaño, pelaje, peso promedio..." onblur="validaFisicas()"></textarea>
                    <p id="errorFisicas" class="texto-error"></p>
                    <!-- <div id="errorFisicas" class="texto-error"></div> -->
                </div>

                <div class="grupo-input">
                    <label for="comportamiento">Comportamiento</label>
                    <textarea name="comportamiento" id="comportamiento" rows="4" placeholder="Temperamento, nivel de energía, socialización..." onblur="validaComportamiento()"></textarea>
                    <p id="errorComportamiento" class="texto-error"></p>
                    <!-- <div id="errorComportamiento" class="texto-error"></div> -->
                </div>

                <!-- Botones controlados por CSS externo -->
                <div class="grupo-botones">
                    <button type="submit" name="btn_crear_raza" class="btn-principal">Guardar Raza</button>
                    <a href="index.php" class="btn-cancelar">Cancelar y Volver</a>
                </div>
            </form>
        </div>
    </main>

</body>
</html>