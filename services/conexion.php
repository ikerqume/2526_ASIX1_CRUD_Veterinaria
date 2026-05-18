<?php
// Usamos __DIR__ para traernos nuestro archivo de credenciales de forma totalmente segura
// Esto garantiza que el servidor encuentre el config.php este donde este, evitando problemas de rutas
include __DIR__ . '/config.php';

// Intentamos abrir la conexion real con nuestra base de datos
// Le pasamos las variables del servidor, el usuario, la clave y el nombre de la BD que teniamos en el config
$conn = mysqli_connect($nombreservidor, $username, $password, $dbname);

// Hacemos una comprobacion de seguridad basica pero imprescindible
// Si la conexion falla porque la base de datos esta caida o hay algun dato mal, 
// matamos la ejecucion de la pagina usando die() para que no se quede cargando al infinito y nos muestre el error
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Este ultimo detalle es super importante para que la aplicacion quede profesional
// Forzamos la conexion a utf8mb4. Asi nos aseguramos de que las eñes, los acentos e incluso los emojis
// se guarden y se muestren perfectamente en pantalla sin que aparezcan simbolos raros
mysqli_set_charset($conn, "utf8mb4"); 
?>