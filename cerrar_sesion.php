<!-- Hecho por Laura Valencia Díaz -->
<?php
ob_start(); 
session_start();
// Cierra la sesión y te lleva al inicio de sesión
session_destroy();
header("Location: index.php");

ob_end_flush();
?>

