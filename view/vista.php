<?php
// Lo primero que hacemos en la pagina principal es arrancar o retomar la sesion del usuario
session_start();

// Esta es nuestra barrera de seguridad indispensable para proteger la zona privada de la aplicacion
// Si alguien intenta escribir directamente 'vista.php' en el navegador sin haber iniciado sesion,
// comprobamos que no tiene un user_id, le bloqueamos el paso y lo mandamos de vuelta al login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Limpiamos el nombre de usuario usando la funcion htmlspecialchars por pura seguridad
// Esto evita problemas de inyeccion de codigo (XSS) en caso de que el nombre guardado contenga caracteres extraños
$username = htmlspecialchars($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perriatra - Panel Principal</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header class="navbar">
        <div class="navbar-logo">
            <img src="../img/logo.png" alt="Logo Perriatra">
            <h2>Clinica Perriatra</h2>
        </div>
        <nav class="navbar-enlaces">
            <a href="vista.php" class="activo">Inicio</a>
            <a href="mascotas/index.php">Mascotas</a>
            <a href="propietarios/index.php">Propietarios</a>
            <a href="veterinarios/index.php">Veterinarios</a>
            <a href="razas/index.php">Razas</a>
            <a href="../processes/logout.proc.php" class="btn-cerrar">Cerrar Sesion</a>
        </nav>
    </header>

    <main class="contenedor-dashboard">
    <div class="cabecera-dashboard">
        <h1>Bienvenido, <?php echo $_SESSION['username']; ?></h1>
        <p>¿Qué sección necesitas gestionar hoy?</p>
    </div>

    <div class="grid-tarjetas">
        
        <a href="mascotas/index.php" class="tarjeta tarjeta-mascotas">
            <i class="fa-solid fa-paw tarjeta-icono"></i>
            <h3>Mascotas</h3>
            <p>Fichas clínicas y registro animal.</p>
            <span class="tarjeta-accion">Ver →</span>
        </a>

        <a href="propietarios/index.php" class="tarjeta tarjeta-propietarios">
            <i class="fa-solid fa-user-group tarjeta-icono"></i>
            <h3>Dueños</h3>
            <p>Datos de contacto de propietarios.</p>
            <span class="tarjeta-accion">Ver →</span>
        </a>

        <a href="veterinarios/index.php" class="tarjeta tarjeta-veterinarios">
            <i class="fa-solid fa-stethoscope tarjeta-icono"></i>
            <h3>Doctores</h3>
            <p>Plantilla de veterinarios clínicos.</p>
            <span class="tarjeta-accion">Ver →</span>
        </a>

        <a href="razas/index.php" class="tarjeta tarjeta-razas">
            <i class="fa-solid fa-dna tarjeta-icono"></i>
            <h3>Razas</h3>
            <p>Configuración de tipos y especies.</p>
            <span class="tarjeta-accion">Ver →</span>
        </a>
    </div>
</main>

</body>
</html>