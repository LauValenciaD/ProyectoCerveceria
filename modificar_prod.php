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
        <?php include_once 'header.php' ?> <!-- el header -->
        <main>
            <!--  formulario de insercion con los datos que hay en BD-->
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
                                           class="form-control" value="<?= $producto['Denominacion_Cerveza'] ?>">
                                </div>
                            </div>


                            <!-- marca -->
                            <div class="mb-3 row">
                                <label for="marca" class="col-sm-4 col-form-label fw-bold">Marca:</label>
                                <div class="col-sm-8">
                                    <select name="marca" id="marca" class="form-select"> <!-- busca la opcion seleccionada -->
                                        <option value="Heineken" <?= ($producto["Marca"] == "Heineken") ? "selected" : "" ?>>Heineken</option>
                                        <option value="Mahou" <?= ($producto["Marca"] == "Mahou") ? "selected" : "" ?>>Mahou</option>
                                        <option value="Damm" <?= ($producto["Marca"] == "Damm") ? "selected" : "" ?>>Damm</option>
                                        <option value="Estrella Galicia" <?= ($producto["Marca"] == "Estrella Galicia") ? "selected" : "" ?>>Estrella Galicia</option>
                                        <option value="Alhambra" <?= ($producto["Marca"] == "Alhambra") ? "selected" : "" ?>>Alhambra</option>
                                        <option value="Cruzcampo" <?= ($producto["Marca"] == "Cruzcampo") ? "selected" : "" ?>>Cruzcampo</option>
                                        <option value="Artesana" <?= ($producto["Marca"] == "Artesana") ? "selected" : "" ?>>Artesana</option>
                                    </select>

                                </div>
                            </div>

                            <!-- Tipo de Cerveza -->
                            <div
                                class="mb-3 d-flex flex-nowrap gap-3 justify-content-between align-items-center">
                                <label class="fw-bold">Tipo de cerveza:</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <?php
                                    $tipos = ["Lager", "Pale Ale", "Cerveza Negra", "Abadía", "Rubia"];
                                    foreach ($tipos as $tipo) {
                                        $checked = ($producto["Tipo_Cerveza"] == $tipo) ? 'checked' : '';
                                        $id = strtolower(str_replace(' ', '_', $tipo)); // Convierte "Cerveza Negra" en "cerveza_negra"
                                        echo "
                                            <div class='d-flex align-items-center gap-2'>
                                            <input type='radio' name='tipo' id='$id' value='$tipo' $checked>
                                            <label for='$id' class='mb-0'>$tipo</label>
                                            </div>";
                                    }
                                    ?>
                                </div>

                            </div>

                            <!-- formato -->
                            <div class="mb-3 row">
                                <label for="formato" class="col-sm-4 col-form-label fw-bold">Formato:</label>
                                <div class="col-sm-8">
                                    <select name="formato" id="formato" class="form-select">
                                        <?php
                                        $formatos = ["Lata", "Botella", "Pack"];
                                        foreach ($formatos as $formato) {
                                            $selected = ($producto["Formato"] == $formato) ? 'selected' : '';
                                            echo "<option value='$formato' $selected>$formato</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <!-- Tamaño -->
                            <div class="mb-3 row">
                                <label for="cantidad" class="col-sm-4 col-form-label fw-bold">Tamaño:</label>
                                <div class="col-sm-8">
                                    <select name="cantidad" id="cantidad" class="form-select">
                                        <option value="default">No cambiar</option>
                                        <?php
                                        $tamanos = ["Botellín", "Tercio", "Medio litro", "Litro"];
                                        foreach ($tamanos as $tamano) {
                                            $selected = ($producto["Cantidad"] == $tamano) ? 'selected' : '';
                                            echo "<option value='$tamano' $selected>$tamano</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <!-- Alergenos -->
                            <div class="mb-3 d-flex flex-nowrap gap-3 justify-content-between">
                                <label class="fw-bold">Alérgenos:</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <?php
                                    $alergenos = [
                                        "gluten" => "Gluten",
                                        "huevo" => "Huevo",
                                        "cacahuete" => "Cacahuete",
                                        "soja" => "Soja",
                                        "lacteo" => "Lácteo",
                                        "sulfitos" => "Sulfitos",
                                        "sinAlergenos" => "Sin alérgenos"
                                    ];

                                    // Obtener los alérgenos seleccionados desde la base de datos
                                    $alergenosSeleccionados = explode(',', $producto["Alergias"]);

                                    foreach ($alergenos as $valor => $etiqueta) {
                                        $checked = in_array($valor, $alergenosSeleccionados) ? 'checked' : '';
                                        echo "
                                            <div class='form-check'>
                                                <input type='checkbox' name='alergenos[]' id='$valor' value='$valor' class='form-check-input' $checked>
                                                <label for='$valor' class='form-check-label'>$etiqueta</label>
                                            </div>";
                                    }
                                    ?>
                                </div>
                            </div>



                            <!-- Fecha -->
                            <div class="mb-3 row">
                                <label for="fecha" class="col-sm-4 col-form-label fw-bold">Fecha de Consumo Preferente:</label>
                                <div class="col-sm-8">
                                    <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo $producto['Fecha_Consumo']; ?>">
                                </div>
                            </div>

                            <!-- Foto actual -->
                            <div class="mb-3 row">
                                <p class="col-sm-4 col-form-label fw-bold">Foto de la
                                    cerveza actual:</p>
                                <div class="col-sm-8">
                                    <?= !empty($producto["Foto"]) ? "<img src='" . $producto["Foto"] . "' style='height: 250px; width: 200px'>" : "Sin foto" ?>
                                </div>
                            </div>
                                



                            <!-- Foto -->
                            <div class="mb-3 row">
                                
                                <label for="foto" class="col-sm-4 col-form-label fw-bold">Cambiar foto:</label>
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
                                    <input type="text" name="precio" id="precio" class="form-control" value="<?=
                                    $producto['Precio']?>">
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
                                        <textarea name="observaciones" id="observaciones" class="form-control"><?php echo $producto['Observaciones']; ?></textarea>

                                    </div>
                                </div>

                                <!-- Botón de envío -->
                                <div class="text-center">
                                    <input type="submit" class="btn btn-primary" name="submit" value="Modificar">
                                    <a href="catalogo.php" class="btn btn-secondary">Volver</a>
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