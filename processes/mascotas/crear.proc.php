<?php
// mantenemos la sesion
session_start();

//incluimos el archivo conexion
include '../../services/conexion.php';

// Comprobamos si el script recibe un envío POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Recogemos y limpiamos los datos del formulario
    $chip = trim($_POST['chip'] ?? '');
    $nombre = trim($_POST['nombre_masc'] ?? '');
    $sexo = trim($_POST['sexo'] ?? '');
    $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
    $id_raza = (int)($_POST['id_raza'] ?? 0);
    $id_prop = (int)($_POST['id_prop'] ?? 0);
    $id_vet = (int)($_POST['id_vet'] ?? 0);

    // 2. Validación muy simple: que no estén vacíos
    if (empty($chip) || empty($nombre) || empty($sexo) || empty($fecha_nacimiento) || empty($id_raza) || empty($id_prop) || empty($id_vet)) {
        $_SESSION['error'] = "Por favor, rellena todos los campos.";
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }

    // 2. Validar que la fecha de nacimiento no sea futura y tenga formato correcto
    $fecha_nacimiento_obj = DateTime::createFromFormat('Y-m-d', $fecha_nacimiento);
    $fecha_errors = DateTime::getLastErrors();
    if ($fecha_nacimiento_obj === false || $fecha_errors['warning_count'] > 0 || $fecha_errors['error_count'] > 0) {
        $_SESSION['error'] = "La fecha de nacimiento no tiene un formato válido (YYYY-MM-DD).";
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }
    // Comprobación simple y fiable contra la fecha actual (formato YYYY-MM-DD)
    if ($fecha_nacimiento > date('Y-m-d')) {
        $_SESSION['error'] = "La fecha de nacimiento no puede ser una fecha futura.";
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }

    // 2.5. Verificamos que el chip no esté duplicado
    $chip_esc = mysqli_real_escape_string($conn, $chip);
    $check_chip = mysqli_query($conn, "SELECT chip FROM mascotas WHERE chip = '$chip_esc'");
    if ($check_chip === false) {
        $_SESSION['error'] = "Error al verificar el chip: " . mysqli_error($conn);
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }
    if (mysqli_num_rows($check_chip) > 0) {
        $_SESSION['error'] = "El chip ya está registrado en otra mascota.";
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }

    // 2.6. Verificamos que los IDs existan en sus tablas
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

    // 3. Preparamos la consulta
    $sql = "INSERT INTO mascotas (chip, nombre, sexo, fecha_nacimiento, raza_id, propietario_id, veterinario_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        $_SESSION['error'] = "Error al preparar el guardado de la mascota.";
        header("Location: ../../view/mascotas/crear.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssssiii", $chip, $nombre, $sexo, $fecha_nacimiento, $id_raza, $id_prop, $id_vet);

    // 4. Ejecutamos y comprobamos si ha ido bien
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['mensaje'] = "¡Mascota registrada correctamente!";
        header("Location: ../../view/mascotas/index.php");
    } else {
        $_SESSION['error'] = "Vaya, algo ha fallado al guardar. Por favor revisa los datos.";
        header("Location: ../../view/mascotas/crear.php");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {
    // Si alguien intenta entrar aquí sin pasar por el formulario, lo echamos
    header("Location: ../../view/mascotas/index.php");
}

?>