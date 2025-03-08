<?php
ob_start(); 
session_start();
//borrar las busquedas anteriores de la barra de busqueda superior
if (isset($_SESSION['encontrados'])) {
    unset($_SESSION['encontrados']);
}
header("Location: busqueda.php");
exit();

ob_end_flush();