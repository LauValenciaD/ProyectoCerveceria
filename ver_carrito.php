<?php
session_start();
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
if (isset($_SESSION["usuario_id"])) {
    inicializarCarrito($_SESSION["usuario_id"], $con);
    $carrito_id = $_SESSION['carrito_id'];
}
?>
<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8" />
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/beer-logo.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="assets/css/bootstrap.css" />
        <link rel="stylesheet" href="assets/css/style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Hecho por Laura Valencia Díaz -->
        <title>Cervecería - Laura Valencia</title>
    </head>

    <body>
        <?php
        //este codigo se lanza cuando se pulse el boton de modificar o borrar
        if (isset($_POST["quitar"])) {
            borrarDelCarrito($carrito_id, $_POST["producto_id"], $con);
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
                        <a class="nav-icon position-relative text-decoration-none" href="ver_carrito.php">
                            <i class="fa fa-fw fa-user text-dark mr-3"></i>
                        </a>
                        <?php  echo '<p class= "m-0">Hola, ' . $user . '</p>'; ?>
                    </div>
                </div>
            </nav>
        </header>
        <main>
        <section class="container my-4">
    <h2>Tu Carrito</h2>

    <?php
    $productos = mostrarCarrito($con);
    if (empty($productos)) {
        echo "<p>El carrito está vacío.</p>";
    } else {
        echo '<div class="list-group">';  // Lista de productos
        $precioTotal = 0;
        foreach ($productos as $producto) {
            $subtotal = $producto['cantidad'] * $producto['Precio'];
            $precioTotal += $subtotal;
            echo '<div class="list-group-item d-flex justify-content-between align-items-center py-2">';
            echo "<div>";
            echo "<strong>{$producto['Denominacion_Cerveza']}</strong><br>";
            echo "Cantidad: {$producto['cantidad']} x {$producto['Precio']}€<br>";
            echo "<small>Total: {$subtotal}€</small>";
            echo "</div>";
            echo '<form method="POST" action="ver_carrito.php" class="d-inline">';
            echo "<input type='hidden' name='producto_id' value='{$producto['id_producto']}'>";
            echo "<button type='submit' name='quitar' class='btn btn-danger btn-sm'>Quitar</button>";
            echo '</form>';
            echo '</div>';
        }
        echo '</div>';

        // Mostrar precio total y botón Comprar
        echo "<div class='mt-4 text-end'>";
        echo "<h4>Total: {$precioTotal}€</h4>";
        echo '<form method="POST" action="comprar.php">';
        echo '<button type="submit" name="comprar" class="btn btn-primary btn-lg">Comprar</button>';
        echo '</form>';
        echo "</div>";
    }
    ?>

</section>


</section>

        </main>
        <footer class="py-3 my-4 border-top">
            <ul class="nav justify-content-center pb-3 mb-3">
                <li class="nav-item">
                    <a href="#" class="nav-link px-2 text-body-secondary">Inicio</a>
                </li>
                <li class="nav-item">
                    <a href="index.php" class="nav-link px-2 text-body-secondary">Iniciar sesión</a>
                </li>
                <li class="nav-item">
                    <a href="cerrar_sesion.php" class="nav-link px-2 text-body-secondary">Cerrar sesión</a>
                </li>
            </ul>
            <p class="text-center text-body-secondary">
                © 2025. Hecho por Laura Valencia
            </p>
        </footer>

        <script src="assets/js/bootstrap.bundle.min.js"></script>
        </body>

</html>
