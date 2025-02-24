<!-- Hecho por Laura Valencia Díaz -->
<?php

// Datos para la conexión a MySQL usando variables de entorno

define('DB_SERVER', getenv('DB_HOST'));  // Usar la variable de entorno de Render para el host
define('DB_NAME', getenv('DB_NAME'));    // Usar la variable de entorno de Render para el nombre de la base de datos
define('DB_USER', getenv('DB_USER'));    // Usar la variable de entorno de Render para el usuario
define('DB_PASS', getenv('DB_PASS'));    // Usar la variable de entorno de Render para la contraseña

// Conexión usando mysqli
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

// Comprobar conexión a la BD
if (mysqli_connect_errno()) {
    echo "Fallo al conectar a MySQL: " . mysqli_connect_error();
    exit();
}





/*
// datos para la conexion a MySQL en local

define('DB_SERVER', 'localhost:3307'); //CUIDADO CON EL PUERTO, CAMBIAR AL DEL MySQL CORRESPONDIENTE

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
    */

