<?php
session_start();
//si no está iniciada la sesión, te obliga a iniciar sesión
if (!isset($_SESSION['user'])) {
    header("Location:index.php");
}
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
            $_SESSION["producto_id"] = $_POST["producto_id"];
            header("Location:catalogo.php");
            exit();
        }
        ?>
        <?php include_once 'header.php' ?> <!-- el header -->
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
                                <th scope="col">Foto</th>
                                <th scope="col"<?php if (!$root) {
    echo 'style= "display:none;"';
}
?>>Opciones de administrador</th>
                                <th scope="col"<?php if (!$root) {
    echo 'style= "display:none;"';
}
?>>Opciones de compra</th>
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
                            foreach ($productos as $producto) {
                                echo "<tr scope='row'>";
                                echo "<td>" . $producto["Denominacion_Cerveza"] . "</td>";
                                echo "<td>" . $producto["Marca"] . "</td>";
                                echo "<td>" . $producto["Tipo_Cerveza"] . "</td>";
                                echo "<td>" . $producto["Formato"] . "</td>";
                                echo "<td>" . $producto["Cantidad"] . "</td>";
                                if (!empty($producto["Foto"])) {
                                    echo "<td><img src='" . $producto['Foto'] . "' alt='ImagenCerveza " . $producto["Denominacion_Cerveza"] . "' style='width: 50px; height: 50px;'></td>";
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
                                    echo "<button type='submit' name='carrito' class='btn btn-success m-1'>Añadir al carrito</button>"
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
<?php include_once 'footer.php' ?> <!-- el footer -->
    </body>

</html>