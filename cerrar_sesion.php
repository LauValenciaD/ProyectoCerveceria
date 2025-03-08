<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

ob_start();  // Inicia el almacenamiento en búfer de salida

echo "Iniciando sesión...";  // Esto es solo para depurar y verificar si se muestra correctamente

session_start();

echo "Sesión iniciada";  // Esto también debe mostrarse si no hay problemas

session_unset();
session_destroy();

echo "Sesión destruida";  // Este mensaje se debe mostrar antes de la redirección

header("Location: index.php");
exit(); // Evita que continúe ejecutándose el código

ob_end_flush();  // Finaliza el almacenamiento en búfer
?>
