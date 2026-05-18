<?php
// Lo primero de todo es arrancar la sesion porque si el usuario acierta sus datos 
// vamos a necesitar crearle su pase de entrada para navegar por la web
session_start();

// Nos traemos el archivo que tiene las claves para entrar a la base de datos
include '../services/conexion.php';

// Nos aseguramos de que nadie ha llegado a este archivo tecleando la direccion en el navegador
// Solo entraremos al proceso si el usuario ha pulsado de verdad el boton de iniciar sesion
if (isset($_POST['btn_login'])) {
    
    // Recogemos el email quitandole los espacios en blanco que haya podido meter por error y recogemos la contraseña
    $email = trim($_POST['email_login']);
    $password = $_POST['pass_login'];

    // Hacemos una criba super rapida: si se ha dejado algun campo en blanco no hacemos trabajar al servidor
    if (empty($email) || empty($password)) {
        // Le preparamos el aviso rojo y lo mandamos de vuelta a la pantalla de login
        $_SESSION['error_login'] = "Por favor, introduce correo y contrasena.";
        header("Location: ../view/login.php");
        exit();
    }

    // Como los campos tienen datos preparamos la orden para buscar en la tabla de usuarios a ver si el correo existe
    $sql = "SELECT id, username, password FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Si el servidor prepara bien la orden seguimos adelante
    if ($stmt) {
        
        // Enlazamos el correo que ha escrito el usuario a la consulta usando la letra s de string (texto)
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        
        // Recogemos los resultados que nos devuelve la base de datos tras ejecutar la busqueda
        $resultado = mysqli_stmt_get_result($stmt);

        // Hacemos un if para ver si nos ha devuelto alguna fila. Si es asi significa que el correo SI existe en el sistema
        if ($row = mysqli_fetch_assoc($resultado)) {
            
            // Aqui viene la magia y lo que nos pide el tribunal: verificar la contraseña encriptada.
            // Usamos password_verify que compara la contraseña normal que ha escrito el usuario con el chorro de caracteres del Hash
            if (password_verify($password, $row['password'])) {
                
                // Si coinciden a la perfeccion las credenciales son buenas. Le creamos sus variables de sesion
                // que seran como su pulsera VIP para moverse por la web privada sin que le echen
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                
                // Y le abrimos la puerta mandandolo al panel principal de la clinica
                header("Location: ../view/vista.php");
                exit();
                
            } else {
                // Si falla la funcion password_verify significa que el correo existe pero ha escrito mal la contraseña
                $_SESSION['error_login'] = "Contrasena incorrecta.";
                header("Location: ../view/login.php");
                exit();
            }
            
        } else {
            // Si la busqueda inicial no devolvio ninguna fila es que ese correo ni siquiera esta registrado
            $_SESSION['error_login'] = "No existe ninguna cuenta con ese correo.";
            header("Location: ../view/login.php");
            exit();
        }
        
        // Cerramos la peticion concreta para no consumir memoria
        mysqli_stmt_close($stmt);
    }
    
    // Y apagamos la conexion a la base de datos
    mysqli_close($conn);
    
} else {
    // Si alguien intenta saltarse el formulario y acceder a este archivo a la fuerza
    // lo bloqueamos y lo mandamos de patitas a la pagina de login
    header("Location: ../view/login.php");
    exit();
}
?>