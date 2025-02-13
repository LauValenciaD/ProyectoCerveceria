<?php
session_start();
require_once 'funciones.php';

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
    $cantidad = 1;
    // Llamar a la función para agregar el producto al carrito
    agregarAlCarrito($carrito_id, $_POST["producto_id"], $cantidad, $con);
    $_SESSION["cantidad_prod"] = contarArticulos($carrito_id, $con);
}
//si hay busqueda en de la barra de busqueda
if (isset($_SESSION['encontrados'])) {
    $nada = false;
    $productos = $_SESSION['encontrados'];
    if (empty($productos)) {
        $nada = true;
    }

    //Si la búsqueda viene del buscador avanzado
    if (isset($_POST["submit"])) {
        $nada = false;
        // Recoger los valores del formulario
        $denominacion = $_POST['denominacion'];
        $marca = $_POST['marca'];
        $formato = $_POST['formato'];
        $cantidad = $_POST['cantidad'];

        // Construcción de la consulta SQL
        $sql = "SELECT * FROM productos WHERE 1=1";

        if (!empty($denominacion)) {
            $sql .= " AND Denominacion_Cerveza LIKE '%$denominacion%'";
        }

        if ($marca !== "default") {
            $sql .= " AND Marca = '$marca'";
        }

        if ($formato !== "default") {
            $sql .= " AND Formato = '$formato'";
        }

        if ($cantidad !== "default") {
            $sql .= " AND Cantidad = '$cantidad'";
        }



        // Ejecutar la consulta
        $resultado = $con->query($sql);

        // Verificar si hay resultados
        if ($resultado->num_rows == 0) {
            $nada = true;
        }

        // Guardar los productos en un array
        $productos = $resultado->fetch_all(MYSQLI_ASSOC);
        // Cerrar la conexión
        $con->close();
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
            <?php include_once 'header.php' ?>
            <main>
                <section>
                    <!--  formulario de insercion -->
                    <section class="mt-3">
                        <div class="container-fluid">
                            <div class="row d-flex justify-content-center align-items-center h-100">

                                <div class="col-md-6 col-lg-8 col-xl-7 offset-xl-1">
                                    <div class="container my-4">
                                        <h2 class="text-center mb-4">Buscador avanzado</h2>
                                        <p class="text-center">Introduzca los datos de la cerveza. No es necesario completar todos los campos.</p>
                                        <form action="busqueda.php" method="post">
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

                                                <!-- marca -->
                                                <div class="mb-3 row">
                                                    <label for="marca" class="col-sm-4 col-form-label fw-bold">Marca:</label>
                                                    <div class="col-sm-8">
                                                        <select name="marca" id="marca" class="form-select">
                                                            <option value="default">No buscar</option>
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


                                                <!-- formato -->
                                                <div class="mb-3 row">
                                                    <label for="formato" class="col-sm-4 col-form-label fw-bold">Formato:</label>
                                                    <div class="col-sm-8">
                                                        <select name="formato" id="formato" class="form-select">
                                                            <option value="default">No buscar</option>
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
                                                            <option value="default">No buscar</option>
                                                            <option value="Botellin">Botellín</option>
                                                            <option value="Tercio">Tercio</option>
                                                            <option value="Medio litro">Medio litro</option>
                                                            <option value="Litro">Litro</option>
                                                        </select>
                                                    </div>
                                                </div>


                                                <!-- Botón de envío -->
                                                <div class="text-center">
                                                    <input type="submit" class="btn btn-primary" name="submit" value="Buscar">
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    </section>
                    <?php
                    if ($nada == false) {
                        echo '<section>';
                        echo '    <h1 class="text-center mt-2">Resultados de la búsqueda</h1>';
                        echo '    <div class="container mt-5">';

                        echo "<table class='table table-bordered table-hover'>
        <thead class='table-info'>
            <tr>
                <th scope='col'>Denominación</th>
                <th scope='col'>Marca</th>
                <th scope='col'>Tipo</th>
                <th scope='col'>Formato</th>
                <th scope='col'>Tamaño</th>
                <th scope='col'>Precio</th>
                <th scope='col'>Foto</th>
                <th scope='col'";
                        if (!$root) {
                            echo ' style="display:none;"';
                        }
                        echo ">Opciones de administrador</th>
                <th scope='col'";
                        if ($root) {
                            echo ' style="display:none;"';
                        }
                        echo ">Opciones de compra</th>
            </tr>
        </thead>
        <tbody>";
                        //añadir los productos a la tabla
                        foreach ($productos as $producto) {
                            echo "<tr scope='row'>";
                            echo "<td>" . $producto["Denominacion_Cerveza"] . "</td>";
                            echo "<td>" . $producto["Marca"] . "</td>";
                            echo "<td>" . $producto["Tipo_Cerveza"] . "</td>";
                            echo "<td>" . $producto["Formato"] . "</td>";
                            echo "<td>" . $producto["Cantidad"] . "</td>";
                            echo "<td>" . $producto["Precio"] . "</td>";
                            if (!empty($producto["Foto"])) {
                                echo "<td><img src='" . $producto['Foto'] . "' alt='Imagen " . $producto["Denominacion_Cerveza"] . "' style='width: 50px; height: 50px;'></td>";
                            } else {
                                echo "<td>Sin foto</td>";
                            }

                            // Botones para modificar y borrar
                            if ($root === true) {
                                echo "<td colspan='3'>";
                                echo "<form method='POST' action='catalogo.php'>";
                                echo "<input type='hidden' name='producto_id' value='" . $producto['ID_PRODUCTO']
                                . "' />";
                                echo "<button type='submit' name='detalles' class='btn btn-primary m-1'>Ver más detalles</button>";
                                echo "<button type='submit' name='modificar' class='btn btn-warning'>Modificar</button>"
                                ;
                                echo "<button type='submit' name='borrar' class='btn btn-danger m-1'>Eliminar</button>"
                                ;
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            if ($root === false) {
                                echo "<td colspan='2'>";
                                echo "<form method='POST' action='catalogo.php'>";
                                echo "<input type='hidden' name='producto_id' value='" . $producto['ID_PRODUCTO']
                                . "' />";
                                echo "<button type='submit' name='detalles' class='btn btn-primary'>Ver más detalles</button>"
                                ;
                                echo "<button type='submit' name='carrito' class='btn btn-success m-1'>Añadir al carrito <i class='fa-solid fa-cart-shopping'></i></button>"
                                ;
                                echo "</form>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                    } else {
                        echo "<h3 class='text-center'>No se encontraron resultados de la búsqueda</h3>";
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