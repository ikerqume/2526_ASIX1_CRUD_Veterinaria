<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../../services/conexion.php';

// Comprobar que nos llega un ID válido por la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "No se ha especificado la raza a editar.";
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Consultar los datos actuales de la raza (usando prepared statements para seguridad)
$sql = "SELECT * FROM razas WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// Verificar si la raza existe
if ($fila = mysqli_fetch_assoc($resultado)) {
    // Guardar los valores actuales con htmlspecialchars para seguridad
    $nombre_actual = htmlspecialchars($fila['nombre']);

    $fisicas_actual = htmlspecialchars($fila['caracteristicas_fisicas']);

    $comportamiento_actual = htmlspecialchars($fila['comportamiento']);
} else {
    // Si no existe, mostrar error y redirigir
    $_SESSION['error'] = "La raza no existe.";
    header("Location: index.php");
    exit();
}

// Cerrar el statement
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Raza</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/estilos.css">
    <link rel="icon" type="image/svg+xml" href="https://api.iconify.design/fa6-solid/paw.svg?color=%2328a745">
    <script defer src="../../js/script.js"></script>
</head>
<body>

    <header class="navbar">
        <div class="navbar-logo">
            <img src="../../img/logo.png" alt="Logo Perriatra" class="logo-imagen">
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
            <h2>Editar Raza: <?php echo $nombre_actual; ?></h2>
        </div>

        <div class="contenedor-form form-centrado">
            <form action="../../processes/razas/editar.proc.php" method="POST">

            <!-- id="formEditarRaza" onsubmit="return validarCrearRaza()" -->
                
                <input type="hidden" name="id_raza" value="<?php echo $id; ?>">

                <div class="grupo-input">
                    <label for="nombre_raza">Nombre de la Raza</label>
                    <input type="text" name="nombre_raza" id="nombre_raza" value="<?php echo $nombre_actual; ?>" onblur="validaNombreRaza()">
                    <p id="errorNombreRaza" class="texto-error"></p>
                    <!-- <div id="errorNombreRaza" class="texto-error"></div> -->
                </div>

                <div class="grupo-input">
                    <label for="caracteristicas_fisicas">Características Físicas</label>
                    <textarea name="caracteristicas_fisicas" id="caracteristicas_fisicas" rows="4" onblur="validaFisicas()"><?php echo $fisicas_actual; ?></textarea>
                    <p id="errorFisicas" class="texto-error"></p>
                    <!-- <div id="errorFisicas" class="texto-error"></div> -->
                </div>

                <div class="grupo-input">
                    <label for="comportamiento">Comportamiento</label>
                    <textarea name="comportamiento" id="comportamiento" rows="4" onblur="validaComportamiento()"><?php echo $comportamiento_actual; ?></textarea>
                    <p id="errorComportamiento" class="texto-error"></p>
                    <!-- <div id="errorComportamiento" class="texto-error"></div> -->
                </div>

                <!-- Botones controlados por CSS externo -->
                <div class="grupo-botones">
                    <!-- Le he quitado el color azul en línea para que use el verde por defecto de btn-principal, manteniendo la coherencia visual del diseño -->
                    <button type="submit" name="btn_editar_raza" class="btn-principal">Actualizar Raza</button>
                    <a href="index.php" class="btn-cancelar">Cancelar y Volver</a>
                </div>
            </form>
        </div>
    </main>

</body>
</html>