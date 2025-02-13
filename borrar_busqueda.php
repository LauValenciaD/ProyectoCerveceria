<?php
//borrar las busquedas anteriores
if (isset($_SESSION['encontrados'])) {
    unset($_SESSION['encontrados']);
}
header("Location: busqueda.php");
exit();

