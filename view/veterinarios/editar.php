<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../login.php"); exit(); }
include '../../services/conexion.php';

// Obtenemos el id del veterinario que queremos editar desde la URL
$id = $_GET['id'];

// Consulta para obtener los datos actuales del veterinario (usando prepared statements)
$sql = "SELECT * FROM veterinarios WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

// Comprobamos si la consulta fue exitosa
if ($resultado === false) {
    echo "<p>Error al obtener los datos del veterinario. Intenta de nuevo más tarde.</p>";
    exit;
}

// Traemos los datos del veterinario
$vet = mysqli_fetch_assoc($resultado);

// Si el veterinario no existe, mostramos un error
if ($vet === null) {
    echo "<p>El veterinario no fue encontrado.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Veterinario</title>
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
            <a href="index.php" class="activo">Veterinarios</a>
            <a href="../../processes/logout.proc.php" class="btn-cerrar">Cerrar Sesion</a>
        </nav>
    </header>

    <a href="javascript:history.back()" class="btn-volver">
        <i class="fa-solid fa-circle-arrow-left"></i>
    </a>

    <main class="contenedor-crud">
        <div class="cabecera-crud"><h2>Editar Veterinario: <?php echo htmlspecialchars($vet['nombre']); ?></h2></div>
        <div class="contenedor-form form-centrado">
            <form action="../../processes/veterinarios/editar.proc.php" method="POST">

            <!-- onsubmit="return validarCrearVeterinario()" -->

                <input type="hidden" name="id_vet" value="<?php echo $id; ?>">
                <div class="grupo-input">
                    <label>Nombre</label>
                    <input type="text" name="nombre_vet" id="nombre_vet" value="<?php echo htmlspecialchars($vet['nombre']); ?>" onblur="validaNombreVet()">
                    <p id="errorNombreVet" class="texto-error"></p>
                    <!-- <div id="errorNombreVet" class="texto-error"></div> -->
                </div>
                <div class="grupo-input">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos_vet" id="apellidos_vet" value="<?php echo htmlspecialchars($vet['apellidos']); ?>" onblur="validaApellidosVet()">
                    <p id="errorApellidosVet" class="texto-error"></p>
                    <!-- <div id="errorApellidosVet" class="texto-error"></div> -->
                </div>
                <div class="grupo-input">
                    <label>Especialidad</label>
                    <input type="text" name="especialidad" id="especialidad" value="<?php echo htmlspecialchars($vet['especialidad']); ?>" onblur="validaEspecialidad()">
                    <p id="errorEspecialidad" class="texto-error"></p>
                    <!-- <div id="errorEspecialidad" class="texto-error"></div> -->
                </div>
                <div class="grupo-input">
                    <label>Teléfono</label>
                    <input type="tel" name="telefono_vet" id="telefono_vet" value="<?php echo htmlspecialchars($vet['telefono']); ?>" onblur="validaTelefonoVet()">
                    <p id="errorTelefonoVet" class="texto-error"></p>
                    <!-- <div id="errorTelefonoVet" class="texto-error"></div> -->
                </div>
                <div class="grupo-input">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email_vet" id="email_vet" value="<?php echo htmlspecialchars($vet['email']); ?>" onblur="validaEmailVet()">
                    <p id="errorEmailVet" class="texto-error"></p>
                </div>

                <div class="grupo-input">
                    <label>Salario (opcional)</label>
                    <input type="number" name="salario" id="salario" step="0.01" value="<?php echo htmlspecialchars($vet['salario'] ?? ''); ?>" onblur="validaSalario()">
                    <p id="errorSalario" class="texto-error"></p>
                </div>
                <div class="grupo-botones">
                    <button type="submit" name="btn_editar_vet" class="btn-principal">Actualizar Datos</button>
                    <a href="index.php" class="btn-cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>