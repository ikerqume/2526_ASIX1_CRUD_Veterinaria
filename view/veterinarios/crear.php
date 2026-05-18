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
    <title>Añadir Veterinario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/estilos.css">
    <link rel="icon" type="image/svg+xml" href="https://api.iconify.design/fa6-solid/paw.svg?color=%230056b3">
    <script defer src="../../js/script.js"></script>
</head>
<body>

    <header class="navbar">
        <div class="navbar-logo">
            <img src="../../img/logo.png" alt="Logo Perriatra" class="logo-imagen">
            <h2>Clinica Perriatra</h2>
        </div>
        <nav class="navbar-enlaces">
            <a href="../index.php">Inicio</a>
            <a href="../mascotas/index.php">Mascotas</a>
            <a href="../propietarios/index.php">Propietarios</a>
            <a href="index.php" class="activo">Veterinarios</a>
            <a href="../razas/index.php">Razas</a>
            <a href="../../processes/logout.proc.php" class="btn-cerrar">Cerrar Sesion</a>
        </nav>
    </header>

    <a href="javascript:history.back()" class="btn-volver">
        <i class="fa-solid fa-circle-arrow-left"></i>
    </a>

    <main class="contenedor-crud">
        <div class="cabecera-crud">
            <h2>Registrar Nuevo Veterinario</h2>
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
            <form action="../../processes/veterinarios/crear.proc.php" method="POST" >

            <!-- id="formCrearVeterinario" onsubmit="return validarCrearVeterinario()" -->
                
                <div class="grupo-input">
                    <label for="nombre_vet">Nombre</label>
                    <input type="text" name="nombre_vet" id="nombre_vet" placeholder="Ej: Laura" onblur="validaNombreVet()">
                    <p id="errorNombreVet" class="texto-error"></p>
                    <!-- <div id="errorNombreVet" class="texto-error"></div> -->
                </div>

                <div class="grupo-input">
                    <label for="apellidos_vet">Apellidos</label>
                    <input type="text" name="apellidos_vet" id="apellidos_vet" placeholder="Ej: Martínez Gómez" onblur="validaApellidosVet()">
                    <p id="errorApellidosVet" class="texto-error"></p>
                    <!-- <div id="errorApellidosVet" class="texto-error"></div> -->
                </div>

                <div class="grupo-input">
                    <label for="especialidad">Especialidad</label>
                    <input type="text" name="especialidad" id="especialidad" placeholder="Ej: Cirugía, Dermatología..." onblur="validaEspecialidad()">
                    <p id="errorEspecialidad" class="texto-error"></p>
                    <!-- <div id="errorEspecialidad" class="texto-error"></div> -->
                </div>

                <div class="grupo-input">
                    <label for="telefono_vet">Teléfono de Contacto</label>
                    <input type="tel" name="telefono_vet" id="telefono_vet" placeholder="Ej: 600123456" onblur="validaTelefonoVet()">
                    <p id="errorTelefonoVet" class="texto-error"></p>
                    <!-- <div id="errorTelefonoVet" class="texto-error"></div> -->
                </div>

                <div class="grupo-input">
                    <label for="email_vet">Correo Electrónico</label>
                    <input type="email" name="email_vet" id="email_vet" placeholder="Ej: laura.martinez@clinica.com" onblur="validaEmailVet()">
                    <p id="errorEmailVet" class="texto-error"></p>
                </div>

                <div class="grupo-input">
                    <label for="salario">Salario (opcional)</label>
                    <input type="number" name="salario" id="salario" step="0.01" placeholder="Ej: 2500.00" onblur="validaSalario()">
                    <p id="errorSalario" class="texto-error"></p>
                </div>

                <div class="grupo-botones">
                    <button type="submit" name="btn_crear_vet" class="btn-principal">Guardar Veterinario</button>
                    <a href="index.php" class="btn-cancelar">Cancelar y Volver</a>
                </div>
            </form>
        </div>
    </main>

</body>
</html>