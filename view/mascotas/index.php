<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include '../../services/conexion.php';

// Recogemos lo que el usuario haya escrito en el buscador (si no ha escrito nada, se queda vacío)
$f_nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';

// Preparamos la búsqueda
$condiciones = [];
if ($f_nombre !== '') {
    $condiciones[] = "mascotas.nombre LIKE '%$f_nombre%'";
}

// Consulta uniendo las tablas para sacar los nombres en vez de los números (IDs)
$sql = "SELECT mascotas.*, razas.nombre AS r_nom, propietarios.nombre AS p_nom, veterinarios.nombre AS v_nom 
        FROM mascotas
        INNER JOIN razas ON mascotas.raza_id = razas.id
        INNER JOIN propietarios ON mascotas.propietario_id = propietarios.id
        INNER JOIN veterinarios ON mascotas.veterinario_id = veterinarios.id";

// Si el usuario buscó un nombre, añadimos el filtro a la consulta
if (!empty($condiciones)) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}

$resultado = mysqli_query($conn, $sql);
$total_filas = mysqli_num_rows($resultado);

// Guardamos los resultados en un arreglo para usar foreach
$mascotas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

if ($mascotas === null) {
    $mascotas = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
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

        <form method="GET" action="" class="form-filtros">
            <input type="text" name="nombre" placeholder="Nombre mascota..." value="<?php echo $f_nombre; ?>">
            <div class="filtros-botones">
                <button type="submit" class="btn-filtrar">Buscar Paciente</button>
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
                    <th>Dueño</th>
                    <th>Veterinario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($mascotas as $fila) {
                        $chip = $fila['chip'];

                        $nombre = $fila['nombre'];

                        $raza = $fila['r_nom'];

                        $dueno = $fila['p_nom'];

                        $veterinario = $fila['v_nom'];
                ?>
                    <tr>
                        <td>
                            <?php echo $chip; ?>
                        </td>
                        <td>
                            <?php echo $nombre; ?>
                        </td>
                        <td>
                            <?php echo $raza; ?>
                        </td>
                        <td>
                            <?php echo $dueno; ?>
                        </td>
                        <td>
                            <?php echo $veterinario; ?>
                        </td>
                        <td class="acciones-tabla">
                            <a href="editar.php?chip=<?php echo $chip; ?>" class="btn-editar">
                                Editar
                            </a>
                            <a href="../../processes/mascotas/borrar.proc.php?chip=<?php echo $chip; ?>" class="btn-borrar">
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