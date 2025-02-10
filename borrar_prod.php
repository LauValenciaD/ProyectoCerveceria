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

        if (isset($_POST["submit"])) {
            if ($producto["Foto"] !== null) {
                if (file_exists($producto["Foto"])) {
                    unlink($producto["Foto"]); // Borrar la foto del servidor
                }
            }
            $actualizacion = mysqli_prepare($con, "DELETE FROM productos
                                        WHERE ID_PRODUCTO = ?");
            if ($actualizacion) {
                // Vincular los parámetros para la consulta preparada
                mysqli_stmt_bind_param($actualizacion, 'i', $producto_id);
                // Ejecutar la consulta
                if (mysqli_stmt_execute($actualizacion)) {
                    header("Location: catalogo.php");
                    exit();
                } else {
                    die("Error en la ejecución del UPDATE: " . mysqli_error($con));
                }
            }
        }
        ?>
        <?php include_once 'header.php' ?> <!-- el header -->
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
                        <form action="borrar_prod.php" method="post" enctype="multipart/form-data">
                            <!-- Botón de envío -->
                            <div class="text-center pb-3">
                                <input type="submit" class="btn btn-danger" name="submit" value="Borrar">
                                <a href="catalogo.php" class="btn btn-secondary">Volver</a>
                            </div> </form>
                    </div>
                </div>
            </section>
        </main>
<?php include_once 'footer.php' ?> <!-- el footer -->
    </body>

</html>