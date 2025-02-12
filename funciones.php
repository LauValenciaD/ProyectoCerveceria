<?php

require_once "conexion.php";
require_once "funcion_carrito.php";
//si no está iniciada la sesión, te obliga a iniciar sesión
if (!isset($_SESSION['user'])) {
    header("Location:index.php");
}
$user = $_SESSION['user'];
$_SESSION['root'] = false;
if ($user == "root") {
    $_SESSION['root'] = true;
}
$root = $_SESSION['root'];
if (isset($_SESSION["usuario_id"]) && $root === false) {
    inicializarCarrito($_SESSION["usuario_id"], $con);
    $carrito_id = $_SESSION['carrito_id'];
    $_SESSION["cantidad_prod"] = contarArticulos($carrito_id, $con);
}
//la busqueda de la barra de busqueda
if (isset($_POST['btnbuscar'])) {
    $buscar =  $_POST['txtbuscar'];

    $query = "
        SELECT *
        FROM productos pro
        WHERE pro.Denominacion_Cerveza LIKE '$buscar%' 
        OR pro.Marca LIKE '$buscar%' 
        OR pro.Tipo_Cerveza LIKE '$buscar%' 
        OR pro.Formato LIKE '$buscar%' 
        OR pro.Cantidad LIKE '$buscar%' 
        OR pro.Precio LIKE '$buscar%'";

    $resultado = mysqli_query($con, $query);
    $productosBuscar = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

    $_SESSION['encontrados'] = $productosBuscar;
}