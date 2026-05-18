<?php
// Iniciamos la sesion para comprobar que el usuario tiene permiso para estar aqui
session_start();

// Si no hay un id de usuario guardado en la sesion lo echamos de patitas a la pantalla de login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Nos traemos el archivo que conecta con la base de datos para poder lanzar consultas
include '../../services/conexion.php';

// Recogemos lo que el usuario haya escrito en los dos buscadores (Nombre de mascota y Nombre de dueño)
// Usamos trim para limpiar espacios accidentales y evitar fallos en la busqueda
$f_nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$f_propietario = isset($_GET['propietario']) ? trim($_GET['propietario']) : '';

// Creamos un array vacio donde iremos acumulando los filtros que el usuario decida usar
$condiciones = [];

// Si escribe algo en el buscador de mascotas lo añadimos a nuestras condiciones usando un LIKE para busquedas parciales
if ($f_nombre !== '') {
    $condiciones[] = "mascotas.nombre LIKE '%$f_nombre%'";
}

// Si tambien escribe algo en el buscador de dueños lo acumulamos al array. Al ser independiente, logramos el filtro sumativo
if ($f_propietario !== '') {
    $condiciones[] = "propietarios.nombre LIKE '%$f_propietario%'";
}


// Seleccionamos todos los datos del animal y traemos de forma alias (AS) el nombre de su raza, dueño y veterinario asignado.
// ATENCION AL DETALLE: Usamos LEFT JOIN en lugar de INNER JOIN por una razon logica muy potente de cara a la clinica.
// Si usaramos INNER JOIN y un perro no tuviera un veterinario asignado en ese momento (porque por ejemplo lo hemos despedido),
// ¡ese perro borraria o desaparecería por completo de esta tabla! Con LEFT JOIN nos aseguramos de traer SIEMPRE todas las mascotas,
// y si alguna casilla como la raza o el veterinario viene vacia de la base de datos, simplemente nos pintara un "Sin asignar".
$sql = "SELECT mascotas.*, razas.nombre AS r_nom, propietarios.nombre AS p_nom, veterinarios.nombre AS v_nom 
        FROM mascotas
        LEFT JOIN razas ON mascotas.raza_id = razas.id
        LEFT JOIN propietarios ON mascotas.propietario_id = propietarios.id
        LEFT JOIN veterinarios ON mascotas.veterinario_id = veterinarios.id";

// Si el array de condiciones no esta vacio, concatenamos un WHERE a la consulta uniendo los filtros con un "AND"
if (!empty($condiciones)) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}

// Ejecutamos la consulta final combinada contra la conexion de la base de datos
$resultado = mysqli_query($conn, $sql);

// Contamos el numero total de filas obtenidas. Esto cumple con el requisito del PDF de mostrar el recuento de resultados
$total_filas = mysqli_num_rows($resultado);

// Guardamos todos los datos en una matriz asociativa para poder recorrerlos limpiamente en el HTML
$mascotas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

// Por pura seguridad, si la consulta no devuelve absolutamente nada forzamos a que sea un array vacio para que el bucle no de error
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
    <link rel="icon" type="image/svg+xml" href="https://api.iconify.design/fa6-solid/paw.svg?color=%2328a745">
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
                    echo "<i class=\"fa-solid fa-circle-check\"></i> " . $mensajeExito; 
                    unset($_SESSION['mensaje']); // Lo eliminamos inmediatamente para que no reaparezca si el usuario refresca la pagina
                ?>
            </div>
        <?php } ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alerta-error">
                <i class="fa-solid fa-circle-xmark"></i> <?php echo $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); // Lo limpiamos para que desaparezca al recargar ?>
            </div>
        <?php endif; ?>

        <form method="GET" action="" class="form-filtros">
            <input type="text" name="nombre" placeholder="Nombre mascota..." value="<?php echo htmlspecialchars($f_nombre); ?>">
            <input type="text" name="propietario" placeholder="Nombre dueño..." value="<?php echo htmlspecialchars($f_propietario); ?>">
            <div class="filtros-botones">
                <button type="submit" class="btn-filtrar">Buscar</button>
                <a href="index.php" class="btn-limpiar">Limpiar</a>
            </div>
        </form>

        <?php if ($f_nombre !== '' || $f_propietario !== ''): ?>
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