<?php
// Arrancamos la sesion para poder usar nuestros mensajes de aviso (los cartelitos rojos o verdes)
session_start();

// Nos traemos el archivo de conexion para poder comunicarnos con la base de datos
include '../../services/conexion.php';

// Nos aseguramos de que el usuario ha llegado aqui tras pulsar el boton de actualizar en el formulario
if (isset($_POST['btn_editar_masc'])) {

    // Primero recogemos el chip original que teniamos guardado oculto en el formulario
    // Esto es vital porque lo necesitamos para el WHERE, para saber a que mascota exacta estamos modificando
    $chip_original = trim($_POST['chip_original']);
    
    // Y aqui recogemos el chip nuevo por si el usuario lo ha modificado en la caja de texto
    $nuevo_chip = trim($_POST['nuevo_chip']); 

    // Recogemos el resto de datos que nos manda el formulario y les pasamos un trim para limpiar espacios accidentales
    $nombre = trim($_POST['nombre_masc']);
    $sexo = $_POST['sexo'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $id_raza = $_POST['id_raza'];
    $id_prop = $_POST['id_prop'];
    $id_vet = $_POST['id_vet'];

    // Esta es la parte clave que nos pide el guion para evitar tener dos mascotas con el mismo chip
    // Primero comprobamos mediante un if si el usuario ha tocado o cambiado el numero del chip
    if ($nuevo_chip !== $chip_original) {
        
        // Si lo ha cambiado, hacemos una busqueda rapida en la base de datos para ver si ese nuevo chip ya existe
        $sql_check = "SELECT chip FROM mascotas WHERE chip = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "s", $nuevo_chip);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        // Si la busqueda nos devuelve mas de 0 resultados significa que ese chip ya lo tiene otro animal
        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            // Guardamos el mensaje de error para mostrarlo en rojo en la pantalla
            $_SESSION['error'] = "Error: Ya existe otra mascota registrada con este CHIP.";
            mysqli_stmt_close($stmt_check);
            mysqli_close($conn);
            
            // Lo devolvemos al formulario y cortamos en seco la ejecucion con exit() para que no se haga el UPDATE
            header("Location: ../../view/mascotas/editar.php?chip=" . $chip_original);
            exit();
        }
        // Si no existe, cerramos esta pequeña comprobacion y seguimos adelante con normalidad
        mysqli_stmt_close($stmt_check);
    }

    // Como todo esta correcto y no hay chips duplicados, preparamos la orden para guardar los cambios
    // Fijate que en el SET tambien actualizamos el campo chip por si acaso lo han cambiado por uno valido
    $sql = "UPDATE mascotas SET chip=?, nombre=?, sexo=?, fecha_nacimiento=?, raza_id=?, propietario_id=?, veterinario_id=? WHERE chip=?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Si el servidor falla al preparar la peticion avisamos para que no explote la web
    if ($stmt === false) {
        $_SESSION['error'] = "Error al preparar la consulta.";
        header("Location: ../../view/mascotas/editar.php?chip=" . $chip_original);
        exit;
    }
    
    // Enlazamos todos los datos con la consulta. 
    // Usamos 's' para los datos de texto o fechas, e 'i' para los numeros enteros (las claves foraneas).
    // Ojo: el ultimo dato que le pasamos es el $chip_original porque es el que va despues del WHERE
    mysqli_stmt_bind_param($stmt, "ssssiiis", $nuevo_chip, $nombre, $sexo, $fecha_nacimiento, $id_raza, $id_prop, $id_vet, $chip_original);

    // Ejecutamos los cambios en la base de datos. Si todo va bien mandamos al usuario a la tabla principal con un mensajito verde
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "Mascota actualizada con éxito.";
        header("Location: ../../view/mascotas/index.php");
    } else {
        // Si falla la insercion guardamos el error y lo devolvemos al formulario
        $_SESSION['error'] = "Error al actualizar los datos.";
        header("Location: ../../view/mascotas/editar.php?chip=" . $chip_original);
    }

    // Por ultimo recogemos todo y cerramos la conexion para mantener el servidor optimizado
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>