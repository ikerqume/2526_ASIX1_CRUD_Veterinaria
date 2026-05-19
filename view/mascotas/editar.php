<?php
// Empezamos abriendo la sesión para mantener al usuario logueado y gestionar mensajes
session_start();

// Nos traemos el archivo de conexión a la base de datos
include '../../services/conexion.php';

// Recogemos el chip de la mascota que queremos editar, el cual nos llega por la URL al hacer clic en 'Editar'
$chip = $_GET['chip'];

// Preparamos una consulta segura para obtener todos los datos de esa mascota en concreto
$sql_mascota = "SELECT * FROM mascotas WHERE chip = ?";
$stmt = mysqli_prepare($conn, $sql_mascota);

// Enlazamos el chip a la consulta indicando que es texto (string) con la 's'
mysqli_stmt_bind_param($stmt, "s", $chip);
mysqli_stmt_execute($stmt);

// Recogemos el resultado de la búsqueda
$resultado_mascota = mysqli_stmt_get_result($stmt);

// Si ha habido algún problema técnico con la consulta, mostramos un error rápido y paramos
if ($resultado_mascota === false) {
    echo "<p>Error al obtener los datos de la mascota. Intenta de nuevo más tarde.</p>";
    exit;
}

// Transformamos el resultado en un array asociativo para poder usar los datos fácilmente (ej: $mascota['nombre'])
$mascota = mysqli_fetch_assoc($resultado_mascota);

// Si la búsqueda no ha devuelto nada, significa que alguien ha escrito un chip falso en la URL
if ($mascota === null) {
    echo "<p>La mascota no fue encontrada.</p>";
    exit;
}


// Buscamos todas las razas ordenadas alfabéticamente
$sql_razas = "SELECT id, nombre FROM razas ORDER BY nombre";
$resultado_razas = mysqli_query($conn, $sql_razas);

if ($resultado_razas === false) {
    echo "<p>No se pudieron cargar las razas. Intenta de nuevo más tarde.</p>";
    exit;
}

// Hacemos lo mismo con los dueños
$sql_props = "SELECT id, nombre FROM propietarios ORDER BY nombre";
$resultado_props = mysqli_query($conn, $sql_props);

if ($resultado_props === false) {
    echo "<p>No se pudieron cargar los propietarios. Intenta de nuevo más tarde.</p>";
    exit;
}

// Y lo mismo con los veterinarios
$sql_vets = "SELECT id, nombre FROM veterinarios ORDER BY nombre";
$resultado_vets = mysqli_query($conn, $sql_vets);

if ($resultado_vets === false) {
    echo "<p>No se pudieron cargar los veterinarios. Intenta de nuevo más tarde.</p>";
    exit;
}

// Convertimos los resultados de las tres consultas en arrays (arreglos)
// Así podremos recorrerlos fácilmente luego con un bucle foreach en el HTML
$razas = mysqli_fetch_all($resultado_razas, MYSQLI_ASSOC);
if ($razas === null) { $razas = []; }

$propietarios = mysqli_fetch_all($resultado_props, MYSQLI_ASSOC);
if ($propietarios === null) { $propietarios = []; }

$veterinarios = mysqli_fetch_all($resultado_vets, MYSQLI_ASSOC);
if ($veterinarios === null) { $veterinarios = []; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Mascota</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <form action="../../processes/mascotas/editar.proc.php" method="POST" onsubmit="return validarFormMascota()">
                
                <h2>Editar Ficha de <?php echo htmlspecialchars($mascota['nombre']); ?></h2>

                <input type="hidden" name="chip_original" value="<?php echo $chip; ?>">

                <div class="grupo-input">
                    <label for="nuevo_chip">CHIP de la Mascota:</label>
                    <input id="chip" type="text" name="nuevo_chip" value="<?php echo $chip; ?>" required onblur="validaChip()">
                    <p id="errorChip" class="texto-error"></p>
                </div>
                
                <div class="grupo-input">
                    <label for="nomMasc">Nombre:</label>
                    <input id="nomMasc" type="text" name="nombre_masc" value="<?php echo htmlspecialchars($mascota['nombre']); ?>" required onblur="ValidaNomMasc()">
                    <p id="errorNomMasc" class="texto-error"></p>
                </div>

                <div class="grupo-input">
                    <label for="sexo">Sexo:</label>
                    <select id="sexo" name="sexo" required onblur="validaSex()">
                        <option value="Macho" <?php echo ($mascota['sexo'] == 'Macho') ? 'selected' : ''; ?>>Macho</option>
                        <option value="Hembra" <?php echo ($mascota['sexo'] == 'Hembra') ? 'selected' : ''; ?>>Hembra</option>
                        <option value="Desconocido" <?php echo ($mascota['sexo'] == 'Desconocido') ? 'selected' : ''; ?>>Desconocido</option>
                    </select>
                    <p id="errorSex" class="texto-error"></p>
                </div>

                <div class="grupo-input">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input id="fecha_nacimiento" type="date" name="fecha_nacimiento" value="<?php echo htmlspecialchars($mascota['fecha_nacimiento']); ?>" required max="<?php echo date('Y-m-d'); ?>" onblur="validarFechaNacimiento()">
                    <p id="errorFecha" class="texto-error"></p>
                </div>

                <div class="grupo-input">
                    <label for="raza">Raza:</label>
                    <select id="raza" name="id_raza" required onblur="ValidarRaza()">
                        <?php
                            // Aquí usamos el array de razas que creamos arriba y lo recorremos con un foreach
                            foreach ($razas as $raza) {
                                // Limpiamos los datos por seguridad antes de imprimirlos en el HTML
                                $razaId = htmlspecialchars($raza['id']);
                                $razaNombre = htmlspecialchars($raza['nombre']);

                                // Comprobamos si el ID de esta raza coincide con el ID de raza que tiene la mascota guardada
                                // Si coincide, marcamos esta opción como la seleccionada por defecto
                                $selected = ($raza['id'] == $mascota['raza_id']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $razaId; ?>" <?php echo $selected; ?>>
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
                    <select id="dueno" name="id_prop" required onblur="ValidarDueno()">
                        <?php
                            // Hacemos exactamente el mismo proceso para el desplegable de los propietarios
                            foreach ($propietarios as $prop) {
                                $propId = htmlspecialchars($prop['id']);
                                $propNombre = htmlspecialchars($prop['nombre']);
                                $selected = ($prop['id'] == $mascota['propietario_id']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $propId; ?>" <?php echo $selected; ?>>
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
                    <select id="id_vet" name="id_vet">
                        <?php
                            // Y repetimos la misma lógica para rellenar la lista de los veterinarios disponibles
                            foreach ($veterinarios as $vet) {
                                $vetId = htmlspecialchars($vet['id']);
                                $vetNombre = htmlspecialchars($vet['nombre']);
                                $selected = ($vet['id'] == $mascota['veterinario_id']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $vetId; ?>" <?php echo $selected; ?>>
                                <?php echo $vetNombre; ?>
                            </option>
                        <?php
                            }
                        ?>
                    </select>
                </div>

                <div class="grupo-botones">
                    <button type="submit" name="btn_editar_masc" class="btn-principal">Actualizar Mascota</button>
                    <a href="index.php" class="btn-cancelar">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>