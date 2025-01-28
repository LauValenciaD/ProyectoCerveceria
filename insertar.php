<?php
session_start();
$user = $_SESSION['user'];
if ($user !== "root") { //si no ha iniciado sesion con root se redirije al inicio
    header("Location: index.php");
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
        <?php

        //VERIFICAR QUE EL NETBEANS TENGA LA URL DEL PROYECTO CORRECTA PARA QUE FUNCIONE Y QUE EL PUERTO DE LA BD ESTE BIEN
        function comprobarNombre($dato) {
            $dato = trim($dato);
            $dato = stripslashes($dato);
            $dato = htmlspecialchars($dato);
            return $dato;
        }

        if (isset($_POST["submit"])) {
            $denominacion = comprobarNombre($_POST["denominacion"]);
            $marca = $_POST["marca"];
            $fecha = $_POST["fecha"];
            $precio = $_POST["precio"];
            $observaciones = comprobarNombre($_POST["observaciones"]);

            $errores = false;
            $precioMal = false;

            // Validaciones
            if (empty($denominacion)) {
                $errores = true;
            }

            if (!isset($_POST["tipo"])) {
                $errores = true;
            } else {
                $tipo = $_POST["tipo"]; // Aquí solo se asigna si está definido
            }


            if (!isset($_POST["alergenos"])) {
                $errores = true;
            }
            if (isset($_POST['alergenos'])) {
                // Verificamos si es un array
                if (is_array($_POST['alergenos'])) {
                    $alergenos = $_POST['alergenos'];
                } else {
                    // Si solo hay una opción seleccionada, convertirla en un array
                    $alergenos = $_POST['alergenos[]'];
                }
            }

            if (empty($fecha)) {
                $errores = true;
            }

            if (empty($precio) || !is_numeric($precio) || $precio <= 0) {
                $precioMal = true;
                $errores = true;
            }

            // Si no hay errores, procesa e inserta la cerveza
            if (!$errores) {
                $_SESSION["denominacion"] = $denominacion;
                $_SESSION["marca"] = $marca;
                $_SESSION["tipo"] = $tipo;
                $_SESSION["formato"] = $_POST["formato"];
                $_SESSION["cantidad"] = $_POST["cantidad"];
                $_SESSION["alergenos"] = $alergenos;
                $_SESSION["fecha"] = $fecha;
                $_SESSION["precio"] = $precio;
                $_SESSION["observaciones"] = $observaciones;


                header("Location: procesar.php");
                exit;
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
        <main>
            <!--  formulario de insercion -->
            <section class="mt-3">
                <div class="container-fluid">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <img src="./assets/img/cerveza.jpg" class="img-fluid rounded" alt="Foto cervezas">
                        </div>
                        <div class="col-md-6 col-lg-8 col-xl-7 offset-xl-1">
                            <div class="container my-4">
                                <h1 class="text-center mb-4">Añadir nueva cerveza</h1>
                                <p class="text-center">Introduzca los datos de la cerveza</p>
                                <form action="insertar.php" method="post" enctype="multipart/form-data">
                                    <fieldset>

                                        <!-- Denominacion -->
                                        <div class="mb-3 row">
                                            <label for="denominacion" class="col-sm-4 col-form-label fw-bold">Denominación
                                                cerveza:</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="denominacion" id="denominacion"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <?php
                                        if (isset($_POST["submit"]) && $errores && empty($denominacion)) {
                                            echo "<div class= 'text-danger'>El nombre no puede estar vacío</div>";
                                        }
                                        ?>

                                        <!-- marca -->
                                        <div class="mb-3 row">
                                            <label for="marca" class="col-sm-4 col-form-label fw-bold">Marca:</label>
                                            <div class="col-sm-8">
                                                <select name="marca" id="marca" class="form-select">
                                                    <option value="Heineken">Heineken</option>
                                                    <option value="Mahou">Mahou</option>
                                                    <option value="Damm">Damm</option>
                                                    <option value="Estrella Galicia">Estrella Galicia</option>
                                                    <option value="Alhambra">Alhambra</option>
                                                    <option value="Cruzcampo">Cruzcampo</option>
                                                    <option value="Artesana">Artesana</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Tipo de Cerveza -->
                                        <div
                                            class="mb-3 d-flex flex-nowrap gap-3 justify-content-between align-items-center">
                                            <label class="fw-bold">Tipo de cerveza:</label>
                                            <div class="d-flex flex-wrap gap-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="radio" name="tipo" id="lager" value="Lager">
                                                    <label for="botellin" class="mb-0">Lager</label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="radio" name="tipo" id="paleAle" value="Pale Ale">
                                                    <label for="botellin" class="mb-0">Pale Ale</label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="radio" name="tipo" id="cervezaNegra" value="Cerveza Negra">
                                                    <label for="botellin" class="mb-0">Cerveza negra</label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="radio" name="tipo" id="abadia" value="Abadía">
                                                    <label for="botellin" class="mb-0">Abadía</label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="radio" name="tipo" id="rubia" value="Rubia">
                                                    <label for="botellin" class="mb-0">Rubia</label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        if (isset($_POST["submit"]) && $errores && !isset($_POST["tipo"])) {
                                            echo "<div class= 'text-danger'>El tipo no puede estar vacío</div>";
                                        }
                                        ?>

                                        <!-- formato -->
                                        <div class="mb-3 row">
                                            <label for="formato" class="col-sm-4 col-form-label fw-bold">Formato:</label>
                                            <div class="col-sm-8">
                                                <select name="formato" id="formato" class="form-select">
                                                    <option value="Lata">Lata</option>
                                                    <option value="Botella">Botella</option>
                                                    <option value="Pack">Pack</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Tamaño -->
                                        <div class="mb-3 row">
                                            <label for="cantidad" class="col-sm-4 col-form-label fw-bold">Tamaño:</label>
                                            <div class="col-sm-8">
                                                <select name="cantidad" id="cantidad" class="form-select">
                                                    <option value="Botellin">Botellín</option>
                                                    <option value="Tercio">Tercio</option>
                                                    <option value="Medio litro">Medio litro</option>
                                                    <option value="Litro">Litro</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Alergenos -->
                                        <div class="mb-3 d-flex flex-nowrap gap-3 justify-content-between">
                                            <label class="fw-bold">Alérgenos:</label>
                                            <div class="d-flex flex-wrap gap-3">
                                                <div class="form-check">
                                                    <input type="checkbox" name="alergenos[]" id="gluten" value="gluten"
                                                           class="form-check-input">
                                                    <label for="gluten" class="form-check-label">Gluten</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" name="alergenos[]" id="huevo" value="huevo"
                                                           class="form-check-input">
                                                    <label for="huevo" class="form-check-label">Huevo</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" name="alergenos[]" id="cacahuete"
                                                           value="cacahuete" class="form-check-input">
                                                    <label for="cacahuete" class="form-check-label">Cacahuete</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" name="alergenos[]" id="soja" value="soja"
                                                           class="form-check-input">
                                                    <label for="soja" class="form-check-label">Soja</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" name="alergenos[]" id="lacteo" value="lacteo"
                                                           class="form-check-input">
                                                    <label for="lacteo" class="form-check-label">Lácteo</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" name="alergenos[]" id="sulfitos" value="sulfitos"
                                                           class="form-check-input">
                                                    <label for="sulfitos" class="form-check-label">Sulfitos</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" name="alergenos[]" id="sinAlergenos"
                                                           value="sinAlergenos" class="form-check-input">
                                                    <label for="sinAlergenos" class="form-check-label">Sin alérgenos</label>
                                                </div>

                                            </div>
                                        </div>
                                        <?php
                                        if (isset($_POST["submit"]) && $errores && !isset($_REQUEST["alergenos"])) {
                                            echo "<div class= 'text-danger'>Debes marcar los alérgenos</div>";
                                        }
                                        ?>


                                        <!-- Fecha -->
                                        <div class="mb-3 row">
                                            <label for="fecha" class="col-sm-4 col-form-label fw-bold">Fecha de Consumo
                                                Preferente:</label>
                                            <div class="col-sm-8">
                                                <input type="date" name="fecha" id="fecha" class="form-control">
                                            </div>
                                        </div>
                                        <?php
                                        if (isset($_POST["submit"]) && $errores && empty($fecha)) {
                                            echo "<div class= 'text-danger'>Debes elegir la fecha</div>";
                                        }
                                        ?>




                                        <!-- Foto -->
                                        <div class="mb-3 row">
                                            <label for="foto" class="col-sm-4 col-form-label fw-bold">Foto de la
                                                cerveza:</label>
                                            <div class="col-sm-8">
                                                <input type="file" name="foto" id="foto" class="form-control">
                                            </div>
                                        </div>

                                        <!-- precio -->
                                        <div class="mb-3 row">
                                            <label for="precio" class="col-sm-4 col-form-label fw-bold">Precio:</label>
                                            <div class="col-sm-7 d-flex flex-nowrap gap-2 align-items-center">
                                                <input type="number" name="precio" id="precio" class="form-control">
                                                <span>€</span>
                                            </div>
                                        </div>
                                        <?php
                                        if (isset($_POST["submit"]) && $precioMal) {
                                            echo "<div class= 'text-danger'>El precio debe ser un número y ser mayor que cero. Se admiten decimales.</div>";
                                        }
                                        ?>

                                        <!-- Observaciones -->
                                        <div class="mb-3 row">
                                            <label for="observaciones"
                                                   class="col-sm-4 col-form-label fw-bold">Observaciones:</label>
                                            <div class="col-sm-8">
                                                <textarea name="observaciones" id="observaciones"
                                                          class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <!-- Botón de envío -->
                                        <div class="text-center">
                                            <input type="submit" class="btn btn-primary" name="submit" value="Registrar">
                                        </div>
                                    </fieldset>
                                </form>
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