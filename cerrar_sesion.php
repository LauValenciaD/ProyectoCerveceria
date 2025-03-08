<!-- Hecho por Laura Valencia Díaz -->
<?php
ob_start();  // Inicia el buffer de salida

session_start();  // Inicia la sesión
session_unset();  // Elimina todas las variables de sesión
session_destroy();  // Destruye la sesión

header("Location: index.php");  // Redirige al inicio de sesión
exit();  // Asegúrate de que el script se detenga aquí

ob_end_flush();  // Finaliza el buffer de salida
?>
