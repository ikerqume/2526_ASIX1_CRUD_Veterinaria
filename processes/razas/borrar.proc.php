<?php
// Arrancamos la sesion para poder gestionar los mensajes que le mostraremos al usuario en pantalla
session_start();

// Hacemos una comprobacion de seguridad rapida: si alguien intenta entrar aqui sin haberse logueado
// lo mandamos directamente a la pantalla de inicio de sesion para proteger el sistema
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../view/login.php");
    exit();
}

// Nos traemos el archivo de conexion para poder comunicarnos con nuestra base de datos
include '../../services/conexion.php';

// Comprobamos que realmente nos esten pasando el identificador de la raza a traves de la URL y que no este vacio
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    // Guardamos ese identificador en una variable para que sea mas comodo trabajar con el
    $id = $_GET['id'];

    // Preparamos la orden para borrar la raza. Utilizamos una consulta preparada con la interrogacion
    // para cumplir con la rubrica de seguridad y evitar inyecciones de codigo
    $sql = "DELETE FROM razas WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Si el servidor ha preparado bien la orden procedemos a ejecutarla
    if ($stmt) {
        
        // Enlazamos nuestro identificador a la consulta indicando con la letra i que es un numero entero
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        // Ejecutamos la orden en la base de datos. Si nos devuelve un ok guardamos el mensaje de exito
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['mensaje'] = "La raza ha sido eliminada del sistema.";
        } else {
            // Si por algun motivo la base de datos rechaza el borrado preparamos el mensaje de error
            $_SESSION['error'] = "No se pudo eliminar la raza.";
        }
        
        // Cerramos esta peticion especifica para ir limpiando memoria
        mysqli_stmt_close($stmt);
    } else {
        // Por si acaso la propia conexion de la consulta fallase antes de ejecutarse
        $_SESSION['error'] = "Error en la consulta de borrado.";
    }
    
    // Cerramos la conexion general a la base de datos para mantener el servidor optimizado
    mysqli_close($conn);
} else {
    // Si han intentado cargar este archivo sin pasarnos un ID por la direccion web les preparamos este aviso
    $_SESSION['error'] = "ID no proporcionado.";
}

// Por ultimo da igual si hemos borrado la raza con exito o si ha habido algun error
// siempre redirigimos al usuario de vuelta a la tabla principal para que vea como ha quedado todo
header("Location: ../../view/razas/index.php");
exit();
?>