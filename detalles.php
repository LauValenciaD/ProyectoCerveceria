<?php
session_start();
//si no está iniciada la sesión, te obliga a iniciar sesión
if (!isset($_SESSION['user'])) {
    header("Location:index.php");
    exit();
}
$user = $_SESSION['user'];
$root = false;
if ($user == "root") {
    $root = true;
}
// Recuperar las variables de sesión
$producto_id = $_SESSION["producto_id"] ?? '';
?>
<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8" />
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/beer-logo.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="assets/css/bootstrap.css" />
        <link rel="stylesheet" href="assets/css/style.css" />
        <link rel="stylesheet" href="assets/css/fontawesome.min.css" />
        <!-- Hecho por Laura Valencia Díaz -->
        <title>Cervecería - Laura Valencia</title>
    </head>

    <body>
        <?php
        require_once "conexion.php";   //mostrar datos del producto
        $sql = "SELECT * FROM productos WHERE ID_PRODUCTO = ?";
        $sentencia = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($sentencia, "s", $producto_id);
        mysqli_stmt_execute($sentencia);
        $result = mysqli_stmt_get_result($sentencia);
        $producto = mysqli_fetch_assoc($result);

        mysqli_stmt_close($sentencia);
        
        if (isset($_POST["carrito"])) {
            $_SESSION["producto_id"] = $_POST["producto_id"];
            header("Location:detalles.php");
            exit();
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
                                    <a class="nav-link nav-title" href="cerrar_sesion.php">HOME</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-title" href="catalogo.php">CATÁLOGO</a>
                                </li>
                                 <!-- si el usuario no es admin, no verá esta opción -->
                                  <li class="nav-item" <?php if (!$root) {echo 'style= "display:none;"';}
                                   ?>>
                                    <a class="nav-link nav-title" href="insertar.php">INSERTAR</a>
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
                       <?php  echo '<p class= "m-0">Hola, ' . $user . '</p>'; ?>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <section>
                <div class="container mt-5">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white text-center">
                            <h1 class="mb-0">Detalles de la Cerveza</h1>
                        </div>
                        <div class="card-body">
                            <!--            imprime los datos de la cerveza-->
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Denominación:</strong> <?= $producto["Denominacion_Cerveza"] ?></li>
                                <li class="list-group-item"><strong>Marca:</strong> <?= $producto["Marca"] ?></li>
                                <li class="list-group-item"><strong>Tipo:</strong> <?= $producto["Tipo_Cerveza"] ?></li>
                                <li class="list-group-item"><strong>Formato:</strong> <?= $producto["Formato"] ?></li>
                                <li class="list-group-item"><strong>Cantidad:</strong> <?= $producto["Cantidad"] ?></li>
                                <li class="list-group-item"><strong>Alérgenos:</strong> 
                                    <?= !empty($producto["alergias"]) ? implode(", ", $producto["alergias"]) : "Sin alérgenos" ?>
                                </li>
                                <li class="list-group-item"><strong>Fecha de consumo preferente:</strong> <?= $producto["Fecha_Consumo"] ?></li>
                                <li class="list-group-item"><strong>Precio:</strong> <?= $producto["Precio"] ?> €</li>
                                <li class="list-group-item"><strong>Observaciones:</strong>
                                    <!--                    si está vacía imprime este mensaje-->
                                    <?= !empty($producto["Observaciones"]) ? $producto["Observaciones"] : "Sin observaciones" ?>
                                </li>
                                <!--                    si está vacía imprime este mensaje-->
                                <li class="list-group-item">
                                    <strong>Foto:</strong>
                                    <?= !empty($producto["Foto"]) ? "<img src='" . $producto["Foto"] . "' style='height: 250px'>" : "Sin foto" ?>
                                </li>
                            </ul>
                        </div>
                         <form action="detalles.php" method="post" enctype="multipart/form-data">
                            <!-- Botón de envío -->
                            <div class="text-center pb-3">
                                <input type="submit" class="btn btn-success" name="carrito" value="Añadir al carrito">
                            <a href="catalogo.php" class="btn btn-secondary">Volver</a>
                        </div> </form>
                    </div>
                </div>
            </section>
        </main>
        <footer class="py-3 my-4 border-top">
            <ul class="nav justify-content-center pb-3 mb-3">
                <li class="nav-item">
                    <a href="http://localhost/ProyectoCerveceria/index.php" class="nav-link px-2 text-body-secondary">Iniciar sesión</a>
                </li>
                <li class="nav-item">
                    <a href="http://localhost/ProyectoCerveceria/cerrar_sesion.php" class="nav-link px-2 text-body-secondary">Cerrar sesión</a>
                </li>
            </ul>
            <p class="text-center text-body-secondary">
                © 2025. Hecho por Laura Valencia
            </p>
        </footer>

        <script src="assets/js/bootstrap.bundle.min.js"></script>
    </body>

</html>