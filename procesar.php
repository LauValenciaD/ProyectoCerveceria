<?php
session_start();
$user = $_SESSION['user'];
if ($user !== "root") { //si no ha iniciado sesion con root se redirije al inicio
    header("Location: index.php");
}
// Recuperar las variables de sesión
$denominacion = $_SESSION["denominacion"] ?? '';
$marca = $_SESSION["marca"] ?? '';
$tipo = $_SESSION["tipo"] ?? '';
$formato = $_SESSION["formato"] ?? '';
$cantidad = $_SESSION["cantidad"] ?? '';
$alergenos = $_SESSION["alergenos"] ?? [];
$fecha = $_SESSION["fecha"] ?? '';
$precio = $_SESSION["precio"] ?? '';
$observaciones = $_SESSION["observaciones"] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" />
    <title>Procesado</title>
</head>

<body>
    <?php
    echo "<h1> Inserción de Cervezas </h1>";
    if (isset($_REQUEST["embase"]) && isset($_REQUEST["marca"]) && isset($_REQUEST["advertencia"]) && isset($_REQUEST["fecha"]) && isset($_REQUEST["alergenos"])) {
        $cerveza = $_REQUEST["cerveza"];
        $nombre = $_REQUEST["denominacion"];
        $embase = $_POST["embase"];
        $cantidad = $_REQUEST["cantidad"];
        $marca = $_REQUEST["marca"];
        $advertencia = $_REQUEST["advertencia"];
        $fecha = $_REQUEST["fecha"];
        $observaciones = $_REQUEST["observaciones"];




        // Mostrar los valores seleccionados
        echo "Nombre: $cerveza <br> Denominación: $nombre <br> Embase: $embase <br> Cantidad: $cantidad <br> Marca: $marca <br>";
        echo "Has seleccionado los siguientes alérgenos:<br>";
        foreach ($opcionesSeleccionadas as $opcion) {
            echo htmlspecialchars($opcion) . "<br>";
        }
        echo "Advertencia: $advertencia <br> Fecha: $fecha <br> Observaciones: $observaciones <br>";
        comprobarImg2();






        function comprobarImg()
        {
            //if (isset($_FILES['foto'])){
            $errores = $_FILES['foto']['error'];
            if ($errores !== 0) {
                echo "<br> <strong> Hay un error en la imagen o falta subir la imagen. </strong> El error es $errores .<br>";
            } else {
                $nombre = $_FILES['foto']['name'];
                $tamanio = $_FILES['foto']['size'];
                $tipo = $_FILES['foto']['type'];
                $origen = $_FILES['foto']['tmp_name'];



                if ($tamanio > 1000000) {
                    echo "La imagen es demasiado grande. <br>";
                }
                if ($tipo !== "image/jpeg" || $tipo !== "image/png") {
                    echo "La imagen debe ser jpg o png. <br>";
                    echo $tipo;
                } else {

                    $destino = "/cursophp/cerveceria/archivos/" . $nombre;
                    move_uploaded_file($origen, $destino);
                    echo "La imagen fue subida correctamente. <br>";
                    echo "<img src=$destino alt='Imagen subida'>";
                }
            }

            /*} else {
                echo "No has subido na de na.";} */
        }

        function comprobarImg2()
        {
            // Verificar si hay errores en la subida del archivo
            $errores = $_FILES['foto']['error'];
            if ($errores !== 0) {
                echo "<br> <strong> Hay un error en la imagen o falta subir la imagen. </strong> El error es $errores .<br>";
            } else {
                // Obtener información del archivo
                $nombre = $_FILES['foto']['name'];
                $tamanio = $_FILES['foto']['size'];
                $tipo = $_FILES['foto']['type'];
                $origen = $_FILES['foto']['tmp_name'];

                // Verificar tamaño (1MB = 1,000,000 bytes)
                if ($tamanio > 1000000) {
                    echo "La imagen es demasiado grande. <br>";
                }

                // Verificar tipo de archivo
                if ($tipo !== "image/jpeg" && $tipo !== "image/png") {
                    echo "La imagen debe ser jpg o png. <br>";
                    echo "Tipo de archivo: $tipo <br>";
                } else {
                    // Definir la ruta de destino relativa
                    $destino = "archivos/" . $nombre;  // Ruta relativa a la carpeta "archivos"
    
                    // Mover el archivo a la ruta de destino
                    if (move_uploaded_file($origen, $destino)) {
                        echo "La imagen fue subida correctamente. <br>";
                        // Mostrar la imagen subida usando la ruta relativa
                        echo "<img src='$destino' alt='Imagen subida'>";
                    } else {
                        echo "Error al mover el archivo a la carpeta de destino. <br>";
                    }
                }
            }
        }
    }
    ?>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light shadow d-flex justify-content-center">
            <!-- logo -->
            <div class="container d-flex justify-content-between align-items-center" id="header">
                <a class="navbar-brand logo" href="index.php"><img class="img-fluid" src="./assets/img/beer-logo.png"
                        alt="" id="logo" /></a>
                <h2>Cervecería online</h2>
                <!-- botón menu móvil -->
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#container_main_nav" aria-controls="container_main_nav" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!--  menu collapse -->
                <div class="collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-center"
                    id="container_main_nav">
                    <div class="flex-fill">
                        <ul class="nav navbar-nav mx-lg-auto d-flex justify-content-center">
                            <li class="nav-item">
                                <a class="nav-link nav-title" href="index.html">HOME</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-title" href="#">CATÁLOGO</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- barra de busqueda -->
                <div class="search-container ms-lg-3 d-none d-lg-block">
                    <form action="" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control nav-title" placeholder="Buscar..." />
                            <div class="input-group-text">
                                <i class="fa fa-fw fa-search"></i>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- grupo de iconos -->
                <div class="navbar align-self-center d-flex flex-nowrap" id="grupoIconos">
                    <a class="nav-icon d-inline d-lg-none" href="#" data-bs-toggle="modal"
                        data-bs-target="#container_search" id="inputMobileSearch">
                        <i class="fa fa-fw fa-search text-dark mr-2"></i>
                    </a>
                    <a class="nav-icon position-relative text-decoration-none" href="#">
                        <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                    </a>
                    <a class="nav-icon position-relative text-decoration-none" href="#">
                        <i class="fa fa-fw fa-user text-dark mr-3"></i>
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <footer class="py-3 my-4 border-top">
        <ul class="nav justify-content-center pb-3 mb-3">
            <li class="nav-item">
                <a href="#" class="nav-link px-2 text-body-secondary">Inicio</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link px-2 text-body-secondary">Ver catálogo</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link px-2 text-body-secondary">Iniciar sesión</a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link px-2 text-body-secondary">Cerrar sesión</a>
            </li>
        </ul>
        <p class="text-center text-body-secondary">
            © 2025. Hecho por Laura Valencia
        </p>
    </footer>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>