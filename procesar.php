<?php
ob_start(); 
session_start();
require_once 'funciones.php';
$user = $_SESSION['user'];
if ($user !== "root") { //si no ha iniciado sesion con root se redirije al inicio
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
$rutaFoto = $_SESSION["rutaFoto"] ?? '';

    // Insertar datos en la BD
    // Convertir a string
    $stringAlergenos = implode(", ", $alergenos);
    $mensaje = "";

    $query = "INSERT INTO productos (Denominacion_Cerveza, Marca, Tipo_Cerveza, Formato, Cantidad, Alergias, Fecha_Consumo, Foto, Precio, Observaciones) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepara la sentencia
    $sentencia = mysqli_prepare($con, $query);

    // Vincula los parámetros con los valores (string para todo menos el precio que es double)
    mysqli_stmt_bind_param($sentencia, 'ssssssssds', $denominacion, $marca, $tipo, $formato, $cantidad, $stringAlergenos, $fecha, $rutaFoto, $precio, $observaciones);

    // Ejecuta la sentencia
    if (mysqli_stmt_execute($sentencia)) {
        $mensaje = "<div class='text-success'>Producto insertado correctamente.</div>";
    } else {
        echo "<div class='text-danger'>Error al insertar el producto: " . mysqli_error($con) . "</div>";
    }

    // Cierra la consulta
    mysqli_stmt_close($sentencia);
    ob_end_flush();
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
                            <li class="list-group-item">
                                <strong>Foto:</strong><?= !empty($rutaFoto) ? "<img src='$rutaFoto' style= 'height: 250px'>" : "Sin foto" ?>

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
    <?php include_once 'footer.php' ?> <!-- el footer -->
</body>

</html>