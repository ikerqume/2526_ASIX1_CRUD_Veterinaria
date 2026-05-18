<?php
// Arrancamos la sesion para poder enviarle al usuario los mensajitos rojos de error o verdes de exito
session_start();

// Nos traemos la conexion a la base de datos para poder operar
include '../../services/conexion.php';

// Nos aseguramos de que los datos nos llegan de forma segura porque el usuario ha pulsado el boton de enviar del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recogemos todo lo que el usuario ha escrito. Usamos trim para limpiar los espacios accidentales al principio o al final
    // y forzamos a que los identificadores de raza, dueño y veterinario sean numeros enteros
    $chip = trim($_POST['chip'] ?? '');
    $nombre = trim($_POST['nombre_masc'] ?? '');
    $sexo = trim($_POST['sexo'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $id_raza = (int)($_POST['id_raza'] ?? 0);
    $id_prop = (int)($_POST['id_prop'] ?? 0);
    $id_vet = (int)($_POST['id_vet'] ?? 0);

    // Hacemos una primera comprobacion muy basica: si se han dejado algun campo vital en blanco
    // cortamos el proceso, guardamos el error en la sesion y los mandamos de vuelta a rellenarlo
    if (empty($chip) || empty($nombre) || empty($sexo) || empty($fecha_nacimiento) || empty($id_raza) || empty($id_prop) || empty($id_vet)) {
        $_SESSION['error'] = "Por favor, rellena todos los campos.";
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }

    // Comprobamos que la fecha que han escrito existe de verdad en el calendario y tiene el formato correcto
    $fecha_nacimiento_obj = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
    $fecha_errors = DateTime::getLastErrors();
    if ($fecha_nacimiento_obj === false || $fecha_errors['warning_count'] > 0 || $fecha_errors['error_count'] > 0) {
        $_SESSION['error'] = "La fecha de nacimiento no tiene un formato válido (YYYY-MM-DD).";
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }
    
    // Tambien comparamos esa fecha con el dia de hoy para evitar que registren animales que nacen en el futuro
    if ($fecha_nacimiento > date('Y-m-d')) {
        $_SESSION['error'] = "La fecha de nacimiento no puede ser una fecha futura.";
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }

    // Esta parte es super importante: antes de guardar nada buscamos si ese CHIP ya lo tiene otro animal
    // Asi evitamos tener animales duplicados y que la base de datos colapse
    $chip_esc = mysqli_real_escape_string($conn, $chip);
    $check_chip = mysqli_query($conn, "SELECT chip FROM mascotas WHERE chip = '$chip_esc'");
    
    if ($check_chip === false) {
        $_SESSION['error'] = "Error al verificar el chip: " . mysqli_error($conn);
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }
    
    // Si la busqueda nos devuelve algun resultado significa que el chip ya existe, asi que cancelamos el registro
    if (mysqli_num_rows($check_chip) > 0) {
        $_SESSION['error'] = "El chip ya está registrado en otra mascota.";
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }

    // Por pura precaucion revisamos que la raza, el dueño y el veterinario elegidos sigan existiendo en la base de datos
    $check_raza = mysqli_query($conn, "SELECT id FROM razas WHERE id = $id_raza");
    if (mysqli_num_rows($check_raza) == 0) {
        $_SESSION['error'] = "La raza seleccionada no existe.";
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }

    $check_prop = mysqli_query($conn, "SELECT id FROM propietarios WHERE id = $id_prop");
    if (mysqli_num_rows($check_prop) == 0) {
        $_SESSION['error'] = "El propietario seleccionado no existe.";
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }

    $check_vet = mysqli_query($conn, "SELECT id FROM veterinarios WHERE id = $id_vet");
    if (mysqli_num_rows($check_vet) == 0) {
        $_SESSION['error'] = "El veterinario seleccionado no existe.";
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }

    // Como todos los datos son correctos y seguros preparamos la orden para guardar la nueva mascota
    // Seguimos usando las interrogaciones por seguridad para evitar la inyeccion de codigo SQL
    $sql = "INSERT INTO mascotas (chip, nombre, sexo, fecha_nacimiento, raza_id, propietario_id, veterinario_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    // Si el servidor falla al preparar la orden avisamos al usuario
    if ($stmt === false) {
        $_SESSION['error'] = "Error al preparar el guardado de la mascota.";
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }

    // Enlazamos las variables con sus tipos exactos s para los textos y fechas y la letra i para los numeros enteros
    mysqli_stmt_bind_param($stmt, "ssssiii", $chip, $nombre, $sexo, $fecha_nacimiento, $id_raza, $id_prop, $id_vet);

    // Ejecutamos la orden final. Si la base de datos nos dice que ok mandamos al usuario a ver la tabla con un mensaje verde
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "¡Mascota registrada correctamente!";
        header("Location: ../../view/mascotas/index.php");
    } else {
        // Si la base de datos rechaza guardar mostramos un mensaje de error
        $_SESSION['error'] = "Vaya, algo ha fallado al guardar. Por favor revisa los datos.";
        header("Location: ../../view/mascotas/crear.php");
    }

    // Recogemos todo y cerramos la conexion para mantener el servidor optimizado
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {
    // Si alguien intenta entrar a este archivo escribiendo la ruta en el navegador sin enviar el formulario
    // lo echamos inmediatamente de vuelta a la lista de mascotas
    header("Location: ../../view/mascotas/index.php");
}

?>