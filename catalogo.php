<?php
session_start();
$user = $_SESSION['user'];
$root = false;
if ($user == "root") {
    $root = true;
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
    <link rel="stylesheet" href="assets/css/fontawesome.min.css" />
    <!-- Hecho por Laura Valencia Díaz -->
    <title>Cervecería - Laura Valencia</title>
</head>

<body>
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
    <main>
        <section>
            <!-- preparar tabla de productos -->
            <div class='container mt-5'>
                <table class='table table-bordered table-hover'>
                    <thead class="table-info">
                        <tr>
                            <th scope="col">Denominación</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Formato</th>
                            <th scope="col">Tamaño</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require_once "conexion.php";
                            $peticion = mysqli_prepare($con, "SELECT * FROM productos");
                            mysqli_stmt_execute($peticion);
                            $resultado = mysqli_stmt_get_result($peticion);
                            $productos = mysqli_fetch_all($resultado, MYSQLI_ASSOC); //guardar todos los productos
                            //añadir los productos a la tabla
                            foreach ($productos as $registro) {
                                echo "<tr scope='row'>";
                                echo "<td>" . $registro["Denominacion_Cerveza"] . "</td>";
                                echo "<td>" . $registro["Marca"] . "</td>";
                                echo "<td>" . $registro["Tipo_Cerveza"] . "</td>";
                                echo "<td>" . $registro["Formato"] . "</td>";
                                echo "<td>" . $registro["Cantidad"] . "</td>";
                                echo "<td><img src='" . $registro['Foto'] . "' alt='ImagenCerveza " . $registro["Denominacion_Cerveza"] . "' style='width: 50px; height: 50px;'></td>";

                                // Botones para modificar y borrar
                                if ($root) {
                                echo "<td colspan='2'>";
                                echo "<form method='POST' action='#'>";
                                echo "<input type='hidden' name='eliminar_id' value='" . $registro['ID_PRODUCTO']
                                . "' />";
                                echo "<button type='submit' name='modify' class='btn btn-warning'>Modificar</button>"
                                ;
                                echo "<button type='submit' name='remove' class='btn btn-danger m-1'>Eliminar</button>"
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