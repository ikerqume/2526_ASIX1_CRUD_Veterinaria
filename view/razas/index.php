<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../../services/conexion.php';

$filtro_nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$filtro_nombre = mysqli_real_escape_string($conn, $filtro_nombre);

$sql = "SELECT * FROM razas";
if ($filtro_nombre !== '') {
    $sql .= " WHERE nombre LIKE '%$filtro_nombre%'";
}
$resultado = mysqli_query($conn, $sql);
$total_filas = mysqli_num_rows($resultado);

// Guardamos los resultados en un arreglo para usar foreach
$razas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

if ($razas === null) {
    $razas = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Razas</title>
    <!-- Fíjate en los ../../ para salir de razas y de view -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/estilos.css">
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
            <h2>Listado de Razas</h2>
            <!-- Botón para ir al formulario de crear -->
            <a href="crear.php" class="btn-anadir">Añadir Nueva Raza</a>
        </div>

        <form method="GET" action="" class="form-filtros">
            <input type="text" name="nombre" placeholder="Buscar raza..." value="<?php echo htmlspecialchars($filtro_nombre); ?>">
            <div class="filtros-botones">
                <button type="submit" class="btn-filtrar">Filtrar</button>
                <a href="index.php" class="btn-limpiar">Limpiar</a>
            </div>
        </form>

        <?php if ($filtro_nombre !== '') echo "<p class='filtros-activos'>$total_filas resultado(s) encontrado(s)</p>"; ?>

        <?php if (isset($_SESSION['mensaje'])) { ?>
            <div class="alerta-exito">
                <?php
                    $mensajeExito = $_SESSION['mensaje'];

                    echo "<i class=\"fa-solid fa-circle-check\"></i> " . $mensajeExito; // Mostrar mensaje de éxito

                    unset($_SESSION['mensaje']); // Limpiar el mensaje para que no se repita en recargas
                ?>
            </div>
        <?php } ?>

        <?php if (isset($_SESSION['error'])) { ?>
            <div class='mensaje-php error-php'>
                <?php
                    echo $_SESSION['error']; // Mostrar el mensaje de error

                    unset($_SESSION['error']); // Limpiar el mensaje para que no se repita en recargas
                ?>
            </div>
        <?php } ?>
   
   <div class="tabla-responsive">
        <table class="tabla-crud">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Características Físicas</th>
                    <th>Comportamiento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($razas) > 0) { ?>
                    <?php foreach ($razas as $fila) { ?>
                        <?php
                            $id = $fila['id'];

                            $nombre = htmlspecialchars($fila['nombre']);

                            $fisicas = htmlspecialchars($fila['caracteristicas_fisicas']);

                            $comportamiento = htmlspecialchars($fila['comportamiento']);
                        ?>
                        <tr>
                            <td>
                                <?php echo $id; ?>
                            </td>
                            <td>
                                <?php echo $nombre; ?>
                            </td>
                            <td>
                                <?php echo $fisicas; ?>
                            </td>
                            <td>
                                <?php echo $comportamiento; ?>
                            </td>
                            <td class="acciones-tabla">
                                <a href="editar.php?id=<?php echo $id; ?>" class="btn-editar">
                                    Editar
                                </a>
                                <a href="../../processes/razas/borrar.proc.php?id=<?php echo $id; ?>" class="btn-borrar" onclick="return confirm('¿Estás seguro de borrar esta raza?')">
                                    Borrar
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" class="texto-centro">
                            No hay razas registradas.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    </main>

</body>
</html>
<?php mysqli_close($conn); ?>