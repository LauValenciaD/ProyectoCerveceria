<?php
ob_start(); 
session_start();
require_once "funciones.php";
// Recuperar las variables de sesión
$producto_id = $_SESSION["producto_id"] ?? '';

    //mostrar datos del producto
    $sql = "SELECT * FROM productos WHERE ID_PRODUCTO = ?"; // Busca el producto con el id del sesion
    $sentencia = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($sentencia, "s", $producto_id);
    mysqli_stmt_execute($sentencia);
    $result = mysqli_stmt_get_result($sentencia);
    $producto = mysqli_fetch_assoc($result);

    mysqli_stmt_close($sentencia); // Cierra
    
    if (isset($_POST["carrito"])) {
        $cantidad = $_POST["unidades"];
        // Llamar a la función para agregar el producto al carrito
        agregarAlCarrito($carrito_id, $_POST["producto_id"], $cantidad, $con);
        $_SESSION["cantidad_prod"] = contarArticulos($carrito_id, $con);
    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                            <li class="list-group-item"><strong>Denominación:</strong>
                                <?= $producto["Denominacion_Cerveza"] ?></li>
                            <li class="list-group-item"><strong>Marca:</strong> <?= $producto["Marca"] ?></li>
                            <li class="list-group-item"><strong>Tipo:</strong> <?= $producto["Tipo_Cerveza"] ?></li>
                            <li class="list-group-item"><strong>Formato:</strong> <?= $producto["Formato"] ?></li>
                            <li class="list-group-item"><strong>Cantidad:</strong> <?= $producto["Cantidad"] ?></li>
                            <li class="list-group-item"><strong>Alérgenos:</strong>
                                <?= !empty($producto["alergias"]) ? implode(", ", $producto["alergias"]) : "Sin alérgenos" ?>
                            </li>
                            <li class="list-group-item"><strong>Fecha de consumo preferente:</strong>
                                <?= $producto["Fecha_Consumo"] ?></li>
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
                    <form action="detalles.php" method="post" enctype="multipart/form-data">
                        <!-- Botón de envío -->
                        <div class="text-center pb-3">
                            <!-- Si el usuario es admin, no podrá comprar -->
                            <div <?php
                            if ($root === true) {
                                echo 'style= "display:none;"';
                            }
                            ?>>
                                <input type='hidden' name='producto_id' value=' <?= $producto['ID_PRODUCTO'] ?>' />
                                <!--  Se puede añadir más de una unidad al carrito -->
                                <label for="unidades">Unidades:</label>
                                <input type="number" name="unidades" id="unidades" value=1>
                                <button type='submit' name='carrito' class='btn btn-success m-1'>Añadir al carrito <i
                                        class='fa-solid fa-cart-shopping'></i></button>
                            </div>
                            <a href="catalogo.php" class="btn btn-secondary">Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <?php include_once 'footer.php' ?> <!-- el footer -->
</body>

</html>