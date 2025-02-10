<?php
require_once "conexion.php";
require_once "funcion_carrito.php";
//si no está iniciada la sesión, te obliga a iniciar sesión
if (!isset($_SESSION['user'])) {
    header("Location:index.php");
}
$user = $_SESSION['user'];
$root = false;
if ($user == "root") {
    $root = true;
}
if (isset($_SESSION["usuario_id"]) && $root === false) {
    inicializarCarrito($_SESSION["usuario_id"], $con);
    $carrito_id = $_SESSION['carrito_id'];
    $_SESSION["cantidad_prod"] = contarArticulos($carrito_id, $con);
}
