<?php
session_start();
$user = $_SESSION['user'];
if ($user !== "root") { //si no ha iniciado sesion con root se redirije al inicio
    alert("Debes ser root para estar en esta página.");
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
$rutaFoto = $_SESSION["rutaFoto"] ?? ''
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
//insertar datos en la BD
        require_once "conexion.php";
        //convertir a string
        $stringAlergenos = implode(", ", $alergenos);
        $mensaje = "";

        $query = "INSERT INTO productos (Denominacion_Cerveza, Marca, Tipo_Cerveza, Formato, Cantidad, Alergias, Fecha_Consumo, Foto, Precio, Observaciones) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Preparar la sentencia
        $sentencia = mysqli_prepare($con, $query);

// Vincular los parámetros con los valores (string para todo menos el precio que es double)
        mysqli_stmt_bind_param($sentencia, 'ssssssssds', $denominacion, $marca, $tipo, $formato, $cantidad, $stringAlergenos, $fecha, $rutaFoto, $precio, $observaciones);

// Ejecutar la sentencia
        if (mysqli_stmt_execute($sentencia)) {
           $mensaje = "<div class='text-success'>Producto insertado correctamente.</div>";
        } else {
            echo "<div class='text-danger'>Error al insertar el producto: " . mysqli_error($con) . "</div>";
        }

// Cerrar la sentencia
        mysqli_stmt_close($sentencia);
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
                                <li class="nav-item">
                                    <a class="nav-link nav-title" href="#">INSERTAR</a>
                                </li> 
                                <li class="nav-item">
                                    <a class="nav-link nav-title" href="#">CERRAR SESIÓN</a>
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
                <div class="container mt-5">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white text-center">
                            <h1 class="mb-0">Detalles de la Cerveza</h1>
                        </div>
                        <div class="card-body">
                            <!--            imprime los datos de la cerveza-->
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Denominación:</strong> <?= $denominacion ?></li>
                                <li class="list-group-item"><strong>Marca:</strong> <?= $marca ?></li>
                                <li class="list-group-item"><strong>Tipo:</strong> <?= $tipo ?></li>
                                <li class="list-group-item"><strong>Formato:</strong> <?= $formato ?></li>
                                <li class="list-group-item"><strong>Cantidad:</strong> <?= $cantidad ?></li>
                                <li class="list-group-item"><strong>Alérgenos:</strong> 
                                    <?= !empty($alergenos) ? implode(", ", $alergenos) : "Sin alérgenos" ?>
                                </li>
                                <li class="list-group-item"><strong>Fecha de consumo preferente:</strong> <?= $fecha ?></li>
                                <li class="list-group-item"><strong>Precio:</strong> <?= $precio ?> €</li>
                                <li class="list-group-item"><strong>Observaciones:</strong> 
                                    <!--                    si está vacía imprime este mensaje-->
                                    <?= !empty($observaciones) ? $observaciones : "Sin observaciones" ?>
                                </li>
                                <!--                    si está vacía imprime este mensaje-->
                                <li class="list-group-item"><strong>Foto:</strong><?= !empty($rutaFoto) ? "<img src='$rutaFoto' style= 'height: 250px'>" : "Sin foto" ?>

                                </li>
                            </ul>
                        </div>
                        <div class="card-footer text-center">
                            <a href="insertar.php" class="btn btn-secondary">Volver</a>
                        </div>
                    </div>
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