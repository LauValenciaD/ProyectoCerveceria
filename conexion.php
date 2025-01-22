<!-- Hecho por Laura Valencia Díaz -->
<?php 
// datos para la conexion a MySQL
define('DB_SERVER', 'localhost:3310'); //CUIDADO CON EL PUERTO, CAMBIAR AL DEL MySQL
define('DB_NAME', 'cervecerialaura'); 
define('DB_USER', 'root'); 
define('DB_PASS', ''); 

// Conexión usando mysqli
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

// Comprobar conexion a la BD
if (mysqli_connect_errno()) {
    echo "Fallo al conectar a MySQL: " . mysqli_connect_error();
    exit();
}
?>
