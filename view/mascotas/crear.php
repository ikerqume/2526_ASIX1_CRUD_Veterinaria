<?php
session_start();

// Conexión a la base de datos
include '../../services/conexion.php';

// Pedimos los datos de razas, veterinarios y propietarios
$sql_razas = "SELECT * FROM razas ORDER BY nombre";
$resultado_razas = mysqli_query($conn, $sql_razas);

if ($resultado_razas === false) {
    echo "<p>No se pudieron cargar las razas. Intenta de nuevo más tarde.</p>";
    exit;
}

$sql_vets = "SELECT * FROM veterinarios ORDER BY nombre";
$resultado_vets = mysqli_query($conn, $sql_vets);

if ($resultado_vets === false) {
    echo "<p>No se pudieron cargar los veterinarios. Intenta de nuevo más tarde.</p>";
    exit;
}

$sql_props = "SELECT * FROM propietarios ORDER BY nombre";
$resultado_props = mysqli_query($conn, $sql_props);

if ($resultado_props === false) {
    echo "<p>No se pudieron cargar los propietarios. Intenta de nuevo más tarde.</p>";
    exit;
}

// Guardamos los resultados en arreglos para poder iterar con foreach
$razas = mysqli_fetch_all($resultado_razas, MYSQLI_ASSOC);

if ($razas === null) {
    $razas = [];
}

$vets = mysqli_fetch_all($resultado_vets, MYSQLI_ASSOC);

if ($vets === null) {
    $vets = [];
}

$props = mysqli_fetch_all($resultado_props, MYSQLI_ASSOC);

if ($props === null) {
    $props = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Mascota</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/estilos.css">
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
            <a href="index.php" class="activo">Mascotas</a>
            <a href="../propietarios/index.php">Propietarios</a>
            <a href="../veterinarios/index.php">Veterinarios</a>
            <a href="../razas/index.php">Razas</a>
            <a href="../../processes/logout.proc.php" class="btn-cerrar">Cerrar Sesion</a>
        </nav>
    </header>

    <a href="javascript:history.back()" class="btn-volver">
        <i class="fa-solid fa-circle-arrow-left"></i>
    </a>

    <main class="contenedor-crud">
        <div class="contenedor-form form-centrado">
            <?php if (isset($_SESSION['error'])) { ?>
                <div class="mensaje-php error-php">
                    <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="mensaje-php exito-php">
                    <?php
                        echo $_SESSION['mensaje'];
                        unset($_SESSION['mensaje']);
                    ?>
                </div>
            <?php } ?>
            <form action="../../processes/mascotas/crear.proc.php" method="POST" onsubmit="return validarFormMascota()">
                <h2>Añadir Mascota</h2>
                
                <div class="grupo-input">
                    <label for="chip">Chip:</label>
                    <input id="chip" type="text" name="chip" required maxlength="50" onblur="validaChip()">
                    <p id="errorChip" class="texto-error"></p>
                </div>

                <div class="grupo-input">
                    <label for="nombre_masc">Nombre:</label>
                    <input id="nombre_masc" type="text" name="nombre_masc" required onblur="ValidaNomMasc()">
                    <p id="errorNomMasc" class="texto-error"></p>
                </div>

                <div class="grupo-input">
                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo" required onblur="validaSex()">
                        <option value="">-- Selecciona el sexo --</option>
                        <option value="Macho">Macho</option>
                        <option value="Hembra">Hembra</option>
                        <option value="Desconocido">Desconocido</option>
                    </select>
                    <p id="errorSex" class="texto-error"></p>
                </div>

                <div class="grupo-input">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input id="fecha_nacimiento" type="date" name="fecha_nacimiento" required max="<?php echo date('Y-m-d'); ?>" onblur="validarFechaNacimiento()">
                    <p id="errorFecha" class="texto-error"></p>
                </div>

                <div class="grupo-input">
                    <label for="id_raza">Raza:</label>
                    <select id="id_raza" name="id_raza" required onblur="ValidarRaza()">
                        <option value="">-- Selecciona una raza --</option> 
                        <?php
                            foreach ($razas as $raza) {
                                $razaId = htmlspecialchars($raza['id']);

                                $razaNombre = htmlspecialchars($raza['nombre']);
                        ?>
                            <option value="<?php echo $razaId; ?>">
                                <?php echo $razaNombre; ?>
                            </option>
                        <?php
                            }
                        ?>
                    </select>
                    <p id="errorRaza" class="texto-error"></p>
                </div>

                <div class="grupo-input">
                    <label for="id_prop">Dueño:</label>
                    <select id="id_prop" name="id_prop" required onblur="ValidarDueno()">
                        <option value="">-- Selecciona un dueño --</option>
                        <?php
                            foreach ($props as $prop) {
                                $propId = htmlspecialchars($prop['id']);

                                $propNombre = htmlspecialchars($prop['nombre']);
                        ?>
                            <option value="<?php echo $propId; ?>">
                                <?php echo $propNombre; ?>
                            </option>
                        <?php
                            }
                        ?>
                    </select>
                    <p id="errorDuenMasc" class="texto-error"></p>
                </div>

                <div class="grupo-input">
                    <label for="id_vet">Veterinario:</label>
                    <select id="id_vet" name="id_vet" required onblur="ValidaVet()">
                        <option value="">-- Selecciona un veter --</option>

                        <?php
                            foreach ($vets as $vet) {
                                $vetId = htmlspecialchars($vet['id']);

                                $vetNombre = htmlspecialchars($vet['nombre']);
                        ?>
                            <option value="<?php echo $vetId; ?>">
                                <?php echo $vetNombre; ?>
                            </option>
                        <?php
                            }
                        ?>
                    </select>
                    <p id="errorVetMasc" class="texto-error"></p>
                </div>

                <div class="grupo-botones">
                    <button type="submit" name="btn_crear_masc" class="btn-principal">Guardar</button>
                    <a href="index.php" class="btn-cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>