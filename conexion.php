<!-- Hecho por Laura Valencia Díaz -->
<?php

// Configurar para que mysqli lance excepciones en caso de error
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Datos para la conexión a MySQL usando variables de entorno
define('DB_SERVER', getenv('DB_HOST'));  // Usar la variable de entorno de Render para el host
define('DB_NAME', getenv('DB_NAME'));    // Usar la variable de entorno de Render para el nombre de la base de datos
define('DB_USER', getenv('DB_USER'));    // Usar la variable de entorno de Render para el usuario
define('DB_PASS', getenv('DB_PASS'));    // Usar la variable de entorno de Render para la contraseña

// Ruta al certificado CA desde la variable de entorno
define('SSL_CA', getenv('DB_SSL_CA'));   // Usar la variable de entorno de Render para el archivo de certificado CA

// Conexión usando mysqli con SSL
$con = mysqli_init(); // Inicializar la conexión

try {
    // Configuración de la conexión SSL
    mysqli_real_connect(
        $con,
        DB_SERVER,
        DB_USER,
        DB_PASS,
        DB_NAME,
        getenv('DB_PORT'), // Usar el puerto desde la variable de entorno
        null, // Usar null si no necesitas un socket específico
        MYSQLI_CLIENT_SSL // Indicar que la conexión será SSL
    );

    // Verificar si hay un certificado SSL configurado
    if (defined('SSL_CA') && SSL_CA) {
        mysqli_ssl_set($con, NULL, NULL, SSL_CA, NULL, NULL);
    }

    
} catch (mysqli_sql_exception $e) {
    // Captura el error y lo muestra
    echo "Error al conectar a la base de datos: " . $e->getMessage();
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

