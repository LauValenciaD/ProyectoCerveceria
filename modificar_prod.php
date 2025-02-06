<?php
session_start();
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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

        // codigo para recuperar los datos de la cerveza a modificar y comprobar que no haya errores
        function comprobarNombre($dato) {
            $dato = trim($dato);
            $dato = stripslashes($dato);
            $dato = htmlspecialchars($dato);
            return $dato;
        }

        //codigo para comprobar que este bien la foto
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

                if ($tamanio > 4000000) { // Verificar tamaño
                    $mensaje = "<div class='text-danger'>La imagen es demasiado grande. Máximo 4 MB.</div>";
                } elseif ($tipo !== "image/jpeg" && $tipo !== "image/png") { // Verificar tipo de archivo
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

        if (isset($_POST["submit"])) {
            $denominacion = comprobarNombre($_POST["denominacion"]);
            $marca = $_POST["marca"];
            $fecha = $_POST["fecha"];
            $precio = $_POST["precio"];
            $formato = $_POST["formato"];
            $cantidad = $_POST["cantidad"];

            $tipo = "";
            $rutaFotoNueva = null;
            $rutaFoto = $producto["Foto"];
            $observaciones = comprobarNombre($_POST["observaciones"]);

            $precioMal = false;
            $mensaje = "";
            $errorFoto = false;

            // Validaciones
            if (empty($denominacion)) {
                $denominacion = $producto["Denominacion_Cerveza"];
            }

            if (!isset($_POST["tipo"])) {
                $tipo = $producto["Tipo_Cerveza"];
            } else {
                $tipo = $_POST["tipo"];
            }
            if ($marca == "default") {
                $marca = $producto["Marca"];
            }
            if ($formato == "default") {
                $formato = $producto["Formato"];
            }
            if ($cantidad == "default") {
                $cantidad = $producto["Cantidad"];
            }

            if (!isset($_POST["alergenos"])) {
                $alergenos = $producto["Alergias"];
            }
            if (isset($_POST['alergenos'])) {
                // Verificamos si es un array
                if (is_array($_POST['alergenos'])) {
                    $alergenos = $_POST['alergenos'];
                } else {
                    // Si solo hay una opción seleccionada, convertirla en un array
                    $alergenos = $_POST['alergenos[]'];
                }
                $stringAlergenos = implode(", ", $alergenos);
            }
            if (empty($fecha)) {
                $fecha = $producto["Fecha_Consumo"];
            }

            if (empty($precio)) {
                $precio = $producto["Precio"];
            } elseif (!is_numeric($precio) || $precio <= 0) {
                $precioMal = true;
            }
            if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
                // Si se ha subido una foto correctamente
                $rutaFotoNueva = comprobarImg($mensaje);
                //si hay error devuelve un mensaje
                if ($mensaje != "") {
                    $errorFoto = true;
                }
            } elseif (!isset($_FILES["foto"]) || $_FILES["foto"]["error"] !== UPLOAD_ERR_OK) {
                // Si no se ha subido ninguna foto o hubo un error en la subida
                $rutaFoto = $producto["Foto"];
            }


            // Si no hay errores, procesa y hace el update de la cerveza
            if (!$errorFoto && !$precioMal) {
                if ($rutaFotoNueva !== null) { // Si hay una nueva imagen
                    if (file_exists($producto["Foto"])) { // Si la antigua existe
                        unlink($producto["Foto"]); // Se borra la imagen antigua
                    }
                    $rutaFoto = $rutaFotoNueva; // Se actualiza la ruta con la nueva imagen
                }


                $actualizacion = mysqli_prepare($con, "UPDATE productos
                                        SET Denominacion_Cerveza = ?,
                                            Marca = ?,
                                            Tipo_Cerveza = ?,
                                            Formato = ?,
                                            Cantidad = ?,
                                            Alergias = ?,
                                            Fecha_Consumo = ?,
                                            Foto = ?,
                                            Precio = ?,
                                            Observaciones = ?
                                        WHERE ID_PRODUCTO = ?");
                if ($actualizacion) {
                    // Vincular los parámetros para la consulta preparada
                    mysqli_stmt_bind_param($actualizacion, 'ssssssssssi', $denominacion, $marca, $tipo, $formato, $cantidad, $stringAlergenos, $fecha, $rutaFoto, $precio, $observaciones, $producto_id);
                    // Ejecutar la consulta
                    if (mysqli_stmt_execute($actualizacion)) {
                        header("Location: modificar_prod.php");
                        exit();
                    } else {
                        die("Error en la ejecución del UPDATE: " . mysqli_error($con));
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
                                    <a class="nav-link nav-title" href="cerrar_sesion.php">HOME</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-title" href="catalogo.php">CATÁLOGO</a>
                                </li>
                                <li class="nav-item">
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
                        <div class="card-footer text-center">
                            <a href="catalogo.php" class="btn btn-secondary">Volver</a>
                        </div>
                    </div>
                </div>
            </section>
            <!--  formulario de insercion -->
            <section class="mt-3">
                <div class="container-fluid">
                    <h1 class="text-center mb-4 mt-4">Modificar los datos</h1>
                    <p class="text-center">Introduzca los datos de la cerveza que desee modificar. No es obligatorio completar todos los campos.</p>
                    <form action="modificar_prod.php" method="post" enctype="multipart/form-data">
                        <fieldset class="p-5">

                            <!-- Denominacion -->
                            <div class="mb-3 row">
                                <label for="denominacion" class="col-sm-4 col-form-label fw-bold">Denominación
                                    cerveza:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="denominacion" id="denominacion"
                                           class="form-control">
                                </div>
                            </div>


                            <!-- marca -->
                            <div class="mb-3 row">
                                <label for="marca" class="col-sm-4 col-form-label fw-bold">Marca:</label>
                                <div class="col-sm-8">
                                    <select name="marca" id="marca" class="form-select">
                                        <option value="default">No cambiar</option>
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

                            <!-- formato -->
                            <div class="mb-3 row">
                                <label for="formato" class="col-sm-4 col-form-label fw-bold">Formato:</label>
                                <div class="col-sm-8">
                                    <select name="formato" id="formato" class="form-select">
                                        <option value="default">No cambiar</option>
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
                                        <option value="default">No cambiar</option>
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


                            <!-- Fecha -->
                            <div class="mb-3 row">
                                <label for="fecha" class="col-sm-4 col-form-label fw-bold">Fecha de Consumo
                                    Preferente:</label>
                                <div class="col-sm-8">
                                    <input type="date" name="fecha" id="fecha" class="form-control">
                                </div>
                            </div>




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
                                <input type="submit" class="btn btn-primary" name="submit" value="Modificar">
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