<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../../services/conexion.php';

// Recogemos lo que el usuario haya escrito en el buscador
$f_nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';

// Preparamos la búsqueda
$condiciones = [];
if ($f_nombre !== '') {
    $condiciones[] = "mascotas.nombre LIKE '%$f_nombre%'";
}

// Consulta uniendo las tablas
$sql = "SELECT mascotas.*, razas.nombre AS r_nom, propietarios.nombre AS p_nom, veterinarios.nombre AS v_nom 
        FROM mascotas
        LEFT JOIN razas ON mascotas.raza_id = razas.id
        LEFT JOIN propietarios ON mascotas.propietario_id = propietarios.id
        LEFT JOIN veterinarios ON mascotas.veterinario_id = veterinarios.id";

if (!empty($condiciones)) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}

$resultado = mysqli_query($conn, $sql);
$total_filas = mysqli_num_rows($resultado);

$mascotas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

if ($mascotas === null) {
    $mascotas = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Mascotas</title>
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
        <div class="cabecera-crud">
            <h2>Listado de Mascotas</h2>
            <a href="crear.php" class="btn-anadir">Añadir Mascota</a>
        </div>

        <?php if (isset($_SESSION['mensaje'])) { ?>
            <div class="alerta-exito">
                <?php
                    $mensajeExito = $_SESSION['mensaje'];

                    echo "<i class=\"fa-solid fa-circle-check\"></i> " . $mensajeExito; // Mostrar mensaje de éxito

                    unset($_SESSION['mensaje']); // Limpiar el mensaje para que no se repita en recargas
                ?>
            </div>
        <?php } ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alerta-error">
                <i class="fa-solid fa-circle-xmark"></i> <?php echo $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); // Lo borramos para que no salga al recargar ?>
            </div>
        <?php endif; ?>
        <form method="GET" action="" class="form-filtros">
            <input type="text" name="nombre" placeholder="Nombre mascota..." value="<?php echo $f_nombre; ?>">
            <div class="filtros-botones">
                <button type="submit" class="btn-filtrar">Buscar Mascotas</button>
                <a href="index.php" class="btn-limpiar">Limpiar</a>
            </div>
        </form>

        <?php if ($f_nombre !== ''): ?>
            <p class="filtros-activos"><?php echo $total_filas; ?> resultado(s) encontrado(s)</p>
        <?php endif; ?>

    <div class="tabla-responsive">
        <table class="tabla-crud">
            <thead>
                <tr>
                    <th>Chip</th>
                    <th>Nombre</th>
                    <th>Raza</th>
                    <th>Propietario</th>
                    <th>Veterinario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mascotas as $fila): ?>
                    <tr>
                        <td><?php echo $fila['chip']; ?></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['r_nom'] ?? 'Sin raza'; ?></td>
                        <td><?php echo $fila['p_nom'] ?? 'Sin propietario'; ?></td>
                        <td><?php echo $fila['v_nom'] ?? 'Sin veterinario'; ?></td>
                        <td class="acciones-tabla">
                            <a href="editar.php?chip=<?php echo $fila['chip']; ?>" class="btn-editar">Editar</a>
                            <a href="../../processes/mascotas/borrar.proc.php?chip=<?php echo $fila['chip']; ?>" class="btn-borrar">Borrar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </main>
</body>
</html>