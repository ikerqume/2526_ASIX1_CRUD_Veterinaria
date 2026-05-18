<?php
// Aunque nuestra intencion sea irnos, primero tenemos que arrancar o recuperar la sesion actual
// Necesitamos decirle a PHP cual es la sesion que queremos destruir
session_start();

// El primer paso para cerrar la sesion es vaciarla por completo
// Esto elimina todas las variables que habiamos creado al hacer login (como el ID del usuario o su nombre)
session_unset();

// Y aqui damos el golpe de gracia: destruimos la sesion por completo en el servidor
// Con esto nos aseguramos de que nadie pueda entrar dando al boton de "Atras" en su navegador
session_destroy();

// Una vez que hemos cerrado todas las puertas y borrado los datos, mandamos al usuario a la pantalla de login
header("Location: ../view/login.php");

// Y como siempre cortamos la ejecucion del script aqui mismo por pura seguridad
exit();
?>