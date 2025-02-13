<?php
session_start();
require_once 'funciones.php';
//si no está iniciada la sesión, te obliga a iniciar sesión
if (!isset($_SESSION['user'])) {
    header("Location:index.php");
    exit();
}
$user = $_SESSION['user'];
if ($user !== "root") { //si no ha iniciado sesion con root se redirije al inicio
    alert("Debes ser root para estar en esta página.");
    header("Location: index.php");
    exit();
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

        //VERIFICAR QUE EL NETBEANS TENGA LA URL DEL PROYECTO CORRECTA PARA QUE FUNCIONE Y QUE EL PUERTO DE LA BD ESTE BIEN
      

        if (isset($_POST["submit"])) {
            $denominacion = comprobarNombre($_POST["denominacion"]);
            $marca = $_POST["marca"];
            $fecha = $_POST["fecha"];
            $precio = $_POST["precio"];
            $observaciones = comprobarNombre($_POST["observaciones"]);

            $errores = false;
            $precioMal = false;
            $mensaje = "";
            $errorFoto = false;

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
            if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
                // Si se ha subido una foto correctamente
                $rutaFoto = comprobarImg($mensaje);
                if ($mensaje != "") {
                    $errores = true;
                    $errorFoto = true;
                }
            } elseif (!isset($_FILES["foto"]) || $_FILES["foto"]["error"] !== UPLOAD_ERR_OK) {
                // Si no se ha subido ninguna foto o hubo un error en la subida
                $rutaFoto = "";
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
                $_SESSION["rutaFoto"] = $rutaFoto;

                header("Location: procesar.php");
                exit;
            }
        }

        function comprobarImg(&$mensaje) {
            // Verificar si hay errores en la subida del archivo
            $errors = $_FILES['foto']['error'];
            $mensaje = "";
            if ($errors !== UPLOAD_ERR_OK) {
                $mensaje = "<div class='text-danger'><strong> Hay un error en la imagen. </strong> El error es $errors .</div>";
            } else { // Obtener información del archivo
                $nombre = $_FILES['foto']['name'];
                $tamanio = $_FILES['foto']['size'];
                $tipo = $_FILES['foto']['type'];
                $origen = $_FILES['foto']['tmp_name'];

                if ($tamanio > 1000000) { // Verificar tamaño
                    $mensaje = "<div class='text-danger'>La imagen es demasiado grande. Máximo 1 MB.</div>";
                }
                if ($tipo !== "image/jpeg" && $tipo !== "image/png") { // Verificar tipo de archivo
                    $mensaje = "<div class='text-danger'>La imagen debe ser jpg o png. Tipo de archivo: $tipo </div>";
                } else {

                    $destino = "archivos/" . $nombre;  // Ruta relativa a la carpeta "archivos"
                    // Mover el archivo a la ruta de destino
                    if (move_uploaded_file($origen, $destino)) {

                        return $destino; //devolvemos la ruta
                    } else {
                        $mensaje = "<div class='text-danger'>Error al mover el archivo a la carpeta de destino.</div>";
                    }
                }
            }
        }
        ?>
        <?php include_once 'header.php' ?> <!-- el header -->
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
                                                    <label for="lager" class="mb-0">Lager</label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="radio" name="tipo" id="paleAle" value="Pale Ale">
                                                    <label for="paleAle" class="mb-0">Pale Ale</label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="radio" name="tipo" id="cervezaNegra" value="Cerveza Negra">
                                                    <label for="cervezaNegra" class="mb-0">Cerveza negra</label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="radio" name="tipo" id="abadia" value="Abadía">
                                                    <label for="botellin" class="mb-0">Abadía</label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="radio" name="tipo" id="rubia" value="Rubia">
                                                    <label for="abadia" class="mb-0">Rubia</label>
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
                                        <?php
                                        if (isset($_POST["submit"]) && $errorFoto) {
                                            echo $mensaje;
                                        }
                                        ?>

                                        <!-- precio -->
                                        <div class="mb-3 row">
                                            <label for="precio" class="col-sm-4 col-form-label fw-bold">Precio:</label>
                                            <div class="col-sm-7 d-flex flex-nowrap gap-2 align-items-center">
                                                <!--sería más correcto usar type=number pero se ha hecho así para poner un validador de números-->
                                                <input type="text" name="precio" id="precio" class="form-control">
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
                        <?php include_once 'footer.php' ?> <!-- el footer -->
                        </body>

                        </html>