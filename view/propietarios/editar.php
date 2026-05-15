<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../login.php"); exit(); }
include '../../services/conexion.php';

// Obtenemos el id del propietario que queremos editar desde la URL
$id = $_GET['id'];

// Consulta para obtener los datos actuales del propietario (usando prepared statements)
$sql_propietario = "SELECT * FROM propietarios WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql_propietario);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// Comprobamos si la consulta fue exitosa
if ($resultado === false) {
    echo "<p>Error al obtener los datos del propietario. Intenta de nuevo más tarde.</p>";
    exit;
}

// Traemos los datos del propietario
$p = mysqli_fetch_assoc($resultado);

// Si el propietario no existe, mostramos un error
if ($p === null) {
    echo "<p>El propietario no fue encontrado.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Propietario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/estilos.css">
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
            <a href="index.php" class="activo">Propietarios</a>
            <a href="../../processes/logout.proc.php" class="btn-cerrar">Cerrar Sesion</a>
        </nav>
    </header>

    <a href="javascript:history.back()" class="btn-volver">
        <i class="fa-solid fa-circle-arrow-left"></i>
    </a>

    <main class="contenedor-crud">
        <div class="cabecera-crud"><h2>Editar Dueño: <?php echo htmlspecialchars($p['nombre']); ?></h2></div>
        <div class="contenedor-form form-centrado">
            <form action="../../processes/propietarios/editar.proc.php" method="POST">

            <!-- onsubmit="return validarCrearPropietario()" -->

                <input type="hidden" name="id_prop" value="<?php echo $id; ?>">
                <div class="grupo-input">
                    <label>Nombre</label>
                    <input type="text" name="nombre_prop" id="nombre_prop" value="<?php echo htmlspecialchars($p['nombre']); ?>" onblur="validaNombreProp()">
                    <p id="errorNombreProp" class="texto-error"></p>
                    <!-- <div id="errorNombreProp" class="texto-error"></div> -->
                </div>
                <div class="grupo-input">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos_prop" id="apellidos_prop" value="<?php echo htmlspecialchars($p['apellidos']); ?>" onblur="validaApellidosProp()">
                    <p id="errorApellidosProp" class="texto-error"></p>
                    <!-- <div id="errorApellidosProp" class="texto-error"></div> -->
                </div>
                <div class="grupo-input">
                    <label>DNI</label>
                    <input type="text" name="dni_prop" id="dni_prop" value="<?php echo htmlspecialchars($p['dni']); ?>" onblur="validaDNI()">
                    <p id="errorDNI" class="texto-error"></p>
                    <!-- <div id="errorDNI" class="texto-error"></div> -->
                </div>
                <div class="grupo-input">
                    <label>Email</label>
                    <input type="email" name="email_prop" id="email_prop" value="<?php echo htmlspecialchars($p['email']); ?>" onblur="validaEmailProp()">
                    <p id="errorEmailProp" class="texto-error"></p>
                    <!-- <div id="errorEmailProp" class="texto-error"></div> -->
                </div>
                <div class="grupo-input">
                    <label>Teléfono</label>
                    <input type="tel" name="telefono_prop" id="telefono_prop" value="<?php echo htmlspecialchars($p['telefono']); ?>" placeholder="Ej: 600123456" onblur="ValidaTelfProp()">
                    <p id="errorTelefonoProp" class="texto-error"></p>
                </div>
                <div class="grupo-botones">
                    <button type="submit" name="btn_editar_prop" class="btn-principal">Actualizar Propietario</button>
                    <a href="index.php" class="btn-cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>