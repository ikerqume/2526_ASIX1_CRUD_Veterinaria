<?php
// Arrancamos la sesion para poder guardar los mensajes de aviso que luego le mostraremos al usuario
session_start();

// Nos traemos el archivo que tiene las credenciales para conectar con la base de datos
include '../../services/conexion.php';

// Primero comprobamos si realmente nos han enviado el identificador del chip a traves de la direccion web
if (isset($_GET['chip'])) {
    
    // Guardamos ese chip en una variable para manejarlo mejor
    $chip = $_GET['chip'];

    // Preparamos la orden de la base de datos para borrar a la mascota usando su chip porque es su identificador unico
    $sql = "DELETE FROM mascotas WHERE chip = ?";
    
    // Lo hacemos utilizando consultas preparadas por pura seguridad para evitar que nos cuelen codigo malicioso
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $chip);

    // Ejecutamos el borrado y si la base de datos nos confirma que ha ido bien guardamos un mensaje de exito
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "Mascota eliminada del sistema.";
    } else {
        // Si falla por algun motivo guardamos el mensaje de error para avisar de que algo ha ido mal
        $_SESSION['error'] = "No se pudo eliminar.";
    }

    // Cerramos la consulta y la conexion para no dejar procesos colgados consumiendo memoria en el servidor
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

// Por ultimo da igual si ha ido bien o mal siempre devolvemos al usuario a la tabla de mascotas para que vea los resultados
header("Location: ../../view/mascotas/index.php");
exit();
?>