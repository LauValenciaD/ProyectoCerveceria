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
$_SESSION["cantidad_prod"] = contarArticulos($carrito_id, $con);
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
        if (isset($_POST["modificar"])) {
            $_SESSION["producto_id"] = $_POST["producto_id"];
            header("Location:modificar_prod.php");
            exit();
        }
        if (isset($_POST["borrar"])) {
            $_SESSION["producto_id"] = $_POST["producto_id"];
            header("Location:borrar_prod.php");
            exit();
        }
        if (isset($_POST["detalles"])) {
            $_SESSION["producto_id"] = $_POST["producto_id"];
            header("Location:detalles.php");
            exit();
        }

        if (isset($_POST["carrito"])) {
            $cantidad = 1;
            // Llamar a la función para agregar el producto al carrito
            agregarAlCarrito($carrito_id, $_POST["producto_id"], $cantidad, $con);
            $_SESSION["cantidad_prod"] = contarArticulos($carrito_id, $con);
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
                                <li class="nav-item" <?php if (!$root) {
            echo 'style= "display:none;"';
        }
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
                        <a class="nav-icon position-relative text-decoration-none" href="ver_carrito.php">
                            <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i> <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-light text-dark"><?php if ($_SESSION['cantidad_prod'] > 0) { echo $_SESSION['cantidad_prod'];} ?></span>
                        </a>
                        <a class="nav-icon position-relative text-decoration-none" href="">
                            <i class="fa fa-fw fa-user text-dark mr-3"></i>
                        </a>
<?php echo '<p class= "m-0">Hola, ' . $user . '</p>'; ?>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <section>
                <!-- preparar tabla de productos -->
                <h1 class="text-center mt-2">Catálogo de productos</h1>
                <div class='container mt-5'>
                    <table class='table table-bordered table-hover'>
                        <thead class="table-info">
                            <tr>
                                <th scope="col">Denominación</th>
                                <th scope="col">Marca</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Formato</th>
                                <th scope="col">Tamaño</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Foto</th>
                                <th scope="col"<?php if (!$root) {
    echo 'style= "display:none;"';
}
?>>Opciones de administrador</th>
                                <th scope="col"<?php if ($root) {
    echo 'style= "display:none;"';
}
?>>Opciones de compra</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $peticion = mysqli_prepare($con, "SELECT * FROM productos");
                            mysqli_stmt_execute($peticion);
                            $resultado = mysqli_stmt_get_result($peticion);
                            $productos = mysqli_fetch_all($resultado, MYSQLI_ASSOC); //guardar todos los productos
                            //añadir los productos a la tabla
                            foreach ($productos as $producto) {
                                echo "<tr scope='row'>";
                                echo "<td>" . $producto["Denominacion_Cerveza"] . "</td>";
                                echo "<td>" . $producto["Marca"] . "</td>";
                                echo "<td>" . $producto["Tipo_Cerveza"] . "</td>";
                                echo "<td>" . $producto["Formato"] . "</td>";
                                echo "<td>" . $producto["Cantidad"] . "</td>";
                                echo "<td>" . $producto["Precio"] . "</td>";
                                if (!empty($producto["Foto"])) {
                                    echo "<td><img src='" . $producto['Foto'] . "' alt='Imagen " . $producto["Denominacion_Cerveza"] . "' style='width: 50px; height: 50px;'></td>";
                                } else {
                                    echo "<td>Sin foto</td>";
                                }

                                // Botones para modificar y borrar
                                if ($root) {
                                    echo "<td colspan='3'>";
                                    echo "<form method='POST' action='catalogo.php'>";
                                    echo "<input type='hidden' name='producto_id' value='" . $producto['ID_PRODUCTO']
                                    . "' />";
                                    echo "<button type='submit' name='detalles' class='btn btn-primary'>Ver más detalles</button>";
                                    echo "<button type='submit' name='modificar' class='btn btn-warning'>Modificar</button>"
                                    ;
                                    echo "<button type='submit' name='borrar' class='btn btn-danger m-1'>Eliminar</button>"
                                    ;
                                    echo "</form>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                if (!$root) {
                                    echo "<td colspan='3'>";
                                    echo "<form method='POST' action='catalogo.php'>";
                                    echo "<input type='hidden' name='producto_id' value='" . $producto['ID_PRODUCTO']
                                    . "' />";
                                    echo "<button type='submit' name='detalles' class='btn btn-primary'>Ver más detalles</button>"
                                    ;
                                    echo "<button type='submit' name='carrito' class='btn btn-success m-1'>Añadir al carrito <i class='fa-solid fa-cart-shopping'></i></button>"
                                    ;
                                    echo "</form>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                </div>

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