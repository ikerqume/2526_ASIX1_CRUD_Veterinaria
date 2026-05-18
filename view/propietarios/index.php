<?php
// Iniciamos la sesión para saber si el usuario está logueado
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../login.php"); exit(); }

// Conectamos con la base de datos usando el archivo común de conexión
include '../../services/conexion.php';

// Recogemos los filtros de búsqueda que el usuario pueda enviar por GET
$f_nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$f_dni = isset($_GET['dni']) ? trim($_GET['dni']) : '';

// Escapamos los valores para evitar inyección SQL básica en los LIKE
$f_nombre = mysqli_real_escape_string($conn, $f_nombre);
$f_dni = mysqli_real_escape_string($conn, $f_dni);

$condiciones = [];
if ($f_nombre !== '') $condiciones[] = "nombre LIKE '%$f_nombre%'";
if ($f_dni !== '') $condiciones[] = "dni LIKE '%$f_dni%'";

$sql = "SELECT * FROM propietarios";
if (!empty($condiciones)) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}
$resultado = mysqli_query($conn, $sql);
$total_filas = mysqli_num_rows($resultado);

// Guardamos los resultados en un arreglo para poder recorrerlos con foreach
$propietarios = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

// Si no hay resultados, forzamos un arreglo vacío para evitar errores en el bucle
if ($propietarios === null) {
    $propietarios = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Propietarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/estilos.css">
    <link rel="icon" type="image/svg+xml" href="https://api.iconify.design/fa6-solid/paw.svg?color=%2328a745">
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
        <div class="cabecera-crud">
            <h2>Listado de Dueños</h2>
            <a href="crear.php" class="btn-anadir">Nuevo Propietario</a>
        </div>

        <form method="GET" action="" class="form-filtros">
            <input type="text" name="nombre" placeholder="Nombre..." value="<?php echo htmlspecialchars($f_nombre); ?>">
            <input type="text" name="dni" placeholder="DNI..." value="<?php echo htmlspecialchars($f_dni); ?>">
            <div class="filtros-botones">
                <button type="submit" class="btn-filtrar">Buscar</button>
                <a href="index.php" class="btn-limpiar">Limpiar</a>
            </div>
        </form>

        <?php if ($f_nombre !== '' || $f_dni !== ''): ?>
            <p class="filtros-activos"><?php echo $total_filas; ?> resultado(s) encontrado(s)</p>
        <?php endif; ?>

        <?php if (isset($_SESSION['mensaje'])) { ?>
            <div class="alerta-exito">
                <?php
                    $mensajeExito = $_SESSION['mensaje'];

                    echo "<i class=\"fa-solid fa-circle-check\"></i> " . $mensajeExito; // Mostrar mensaje de éxito

                    unset($_SESSION['mensaje']); // Limpiar el mensaje para que no se repita en recargas
                ?>
            </div>
        <?php } ?>

    <div class="tabla-responsive">
        <table class="tabla-crud">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Recorremos cada propietario y mostramos sus datos en la tabla
                    foreach ($propietarios as $f) {
                        $nombre = htmlspecialchars($f['nombre']);
                        $apellidos = htmlspecialchars($f['apellidos']);
                        $dni = htmlspecialchars($f['dni']);
                        $telefono = htmlspecialchars($f['telefono']);
                        $email = htmlspecialchars($f['email']);
                        $id = $f['id'];
                ?>
                    <tr>
                        <td>
                            <?php echo $nombre; ?>
                        </td>
                        <td>
                            <?php echo $apellidos; ?>
                        </td>
                        <td>
                            <?php echo $dni; ?>
                        </td>
                        <td>
                            <?php echo $telefono; ?>
                        </td>
                        <td>
                            <?php echo $email; ?>
                        </td>
                        <td class="acciones-tabla">
                            <a href="editar.php?id=<?php echo $id; ?>" class="btn-editar">
                                Editar
                            </a>
                            <a href="../../processes/propietarios/borrar.proc.php?id=<?php echo $id; ?>" class="btn-borrar" onclick="return confirm('¿Borrar dueño?')">
                                Borrar
                            </a>
                        </td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
    </main>
</body>
</html>