<?php
// Arrancamos la sesion para poder comprobar si el usuario esta logueado y para leer los mensajes de alerta
session_start();

// Hacemos nuestra clasica comprobacion de seguridad para evitar intrusos
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Nos traemos el archivo de conexion a la base de datos
include '../../services/conexion.php';

// Recogemos lo que el usuario haya escrito en los buscadores de nombre y especialidad
// Usamos trim para quitar los espacios vacios por si se le ha escapado alguno al teclear
$f_nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$f_especialidad = isset($_GET['especialidad']) ? trim($_GET['especialidad']) : '';

// Como vamos a meter estos textos directamente en la consulta SQL, usamos esta funcion nativa de mysqli
// Sirve para escapar caracteres especiales y evitar que nos hagan inyeccion de codigo SQL desde el buscador
$f_nombre = mysqli_real_escape_string($conn, $f_nombre);
$f_especialidad = mysqli_real_escape_string($conn, $f_especialidad);

// Preparamos un array vacio donde iremos acumulando los filtros que el usuario quiera aplicar
$condiciones = [];

// Si ha escrito algo en el nombre, lo metemos en el array. Usamos LIKE para que busque coincidencias parciales
if ($f_nombre !== '') $condiciones[] = "nombre LIKE '%$f_nombre%'";

// Y si ha escrito algo en especialidad lo añadimos tambien, logrando asi que los filtros se sumen
if ($f_especialidad !== '') $condiciones[] = "especialidad LIKE '%$f_especialidad%'";

// Empezamos a montar la orden para la base de datos seleccionando todos los veterinarios
$sql = "SELECT * FROM veterinarios";

// Si el array de condiciones tiene algo dentro, le pegamos el WHERE a la consulta y unimos los filtros con AND
if (!empty($condiciones)) {
    $sql .= " WHERE " . implode(" AND ", $condiciones);
}

// Ejecutamos la peticion a la base de datos
$resultado = mysqli_query($conn, $sql);

// Guardamos todos los resultados devueltos en un array asociativo para poder recorrerlo comodamente luego en el HTML
$veterinarios = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

// Por pura seguridad, si la base de datos no nos devuelve nada forzamos a que la variable sea un array vacio
if ($veterinarios === null) {
    $veterinarios = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Veterinarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/estilos.css">
    <link rel="icon" type="image/svg+xml" href="https://api.iconify.design/fa6-solid/paw.svg?color=%230056b3">
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
            <a href="../propietarios/index.php">Propietarios</a>
            <a href="index.php" class="activo">Veterinarios</a>
            <a href="../razas/index.php">Razas</a>
            <a href="../../processes/logout.proc.php" class="btn-cerrar">Cerrar Sesion</a>
        </nav>
    </header>

    <a href="javascript:history.back()" class="btn-volver">
        <i class="fa-solid fa-circle-arrow-left"></i>
    </a>

    <main class="contenedor-crud">
        <div class="cabecera-crud">
            <h2>Listado de Veterinarios</h2>
            <a href="crear.php" class="btn-anadir">Añadir Nuevo Veterinario</a>
        </div>

        <form method="GET" action="" class="form-filtros">
            <input type="text" name="nombre" placeholder="Nombre..." value="<?php echo htmlspecialchars($f_nombre); ?>">
            <input type="text" name="especialidad" placeholder="Especialidad..." value="<?php echo htmlspecialchars($f_especialidad); ?>">
            <div class="filtros-botones">
                <button type="submit" class="btn-filtrar">Buscar</button>
                <a href="index.php" class="btn-limpiar">Limpiar</a>
            </div>
        </form>

        <?php if (isset($_SESSION['mensaje'])) { ?>
            <div class="alerta-exito">
                <?php
                    $mensajeExito = $_SESSION['mensaje'];
                    echo "<i class=\"fa-solid fa-circle-check\"></i> " . $mensajeExito; 
                    
                    // Super importante: borramos el mensaje de la sesion justo despues de pintarlo para que no salga siempre
                    unset($_SESSION['mensaje']); 
                ?>
            </div>
        <?php } ?>

        <?php if (isset($_SESSION['error'])) { ?>
            <div class='mensaje-php error-php'>
                <?php
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']); 
                ?>
            </div>
        <?php } ?>

        <div class="tabla-responsive">
            <table class="tabla-crud">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Especialidad</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Salario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            <tbody>
                <?php if (count($veterinarios) > 0) { ?>
                    <?php foreach ($veterinarios as $fila) { ?>
                        <?php
                            // Limpiamos los datos con htmlspecialchars justo antes de imprimirlos en el HTML
                            // Esto evita que si alguien ha colado una etiqueta HTML rara en la base de datos se ejecute en nuestra tabla
                            $id = $fila['id'];
                            $nombre = htmlspecialchars($fila['nombre']);
                            $apellidos = htmlspecialchars($fila['apellidos']);
                            $especialidad = htmlspecialchars($fila['especialidad']);
                            $telefono = htmlspecialchars($fila['telefono']);
                            $email = htmlspecialchars($fila['email']);
                            $salario = htmlspecialchars($fila['salario']);
                        ?>
                        <tr>
                            <td><?php echo $id; ?></td>
                            <td><?php echo $nombre; ?></td>
                            <td><?php echo $apellidos; ?></td>
                            <td><?php echo $especialidad; ?></td>
                            <td><?php echo $telefono; ?></td>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $salario; ?></td>
                            
                            <td class="acciones-tabla">
                                <a href="editar.php?id=<?php echo $id; ?>" class="btn-editar">
                                    Editar
                                </a>
                                
                                <a href="../../processes/veterinarios/borrar.proc.php?id=<?php echo $id; ?>" class="btn-borrar" onclick="return confirm('¿Estás seguro de borrar este veterinario?')">
                                    Borrar
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="8" class="texto-centro">
                            No hay veterinarios registrados.
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>       
    </main>

</body>
</html>
<?php 
// Al final del archivo cerramos la conexion general con la base de datos
mysqli_close($conn); 
?>