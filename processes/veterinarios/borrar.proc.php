<?php
// Inicia la sesion para poder leer el id del usuario logueado
session_start();
// Si el usuario no está logueado, corta la ejecución del script
if (!isset($_SESSION['user_id'])) { exit(); }
// Incluye la conexion a la base de datos
include '../../services/conexion.php';
// Comprueba que el id del veterinario viene por la URL, por ejemplo ?id=2
if (isset($_GET['id'])) {

    // Recoge el id del veterinario que viene por la URL
    $id = $_GET['id'];

    // Prepara la consulta DELETE con ? para evitar inyección SQL
    $sql = "DELETE FROM veterinarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Sustituye el ? por el id recogido, "i" indica que es un entero
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    // Si la consulta se ejecuta correctamente guarda un mensaje de éxito en sesión
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "Veterinario eliminado.";
    // Si falla guarda un mensaje de error en sesión
    } else {
        $_SESSION['error'] = "No se pudo eliminar el registro.";
    }
    // Cierra la consulta preparada para liberar memoria
    mysqli_stmt_close($stmt);
}
// Redirige de vuelta a la lista de veterinarios
header("Location: ../../view/veterinarios/index.php");
exit();