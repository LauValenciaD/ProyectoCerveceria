<?php
session_start();
require_once "funciones.php";
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
        //este codigo se lanza cuando se pulse el boton de modificar o borrar
        if (isset($_POST["quitar"])) {
            borrarDelCarrito($carrito_id, $_POST["producto_id"], $con);
            $_SESSION["cantidad_prod"] = contarArticulos($carrito_id, $con);
        }
        ?>
        <?php include_once 'header.php' ?> <!-- el header -->
        <main>
            <section class="container my-4">
                <h2>Tu Carrito</h2>

                <?php
                $productos = mostrarCarrito($con);
                if (empty($productos)) {
                    echo "<p>El carrito está vacío.</p>";
                } else {
                    echo '<div class="list-group">';  // Lista de productos
                    $precioTotal = 0;
                    foreach ($productos as $producto) {
                        $subtotal = $producto['cantidad'] * $producto['Precio'];
                        $precioTotal += $subtotal;
                        echo '<div class="list-group-item d-flex justify-content-between align-items-center py-2">';
                        echo "<div>";
                        echo "<strong>{$producto['Denominacion_Cerveza']}</strong><br>";
                        echo "Cantidad: {$producto['cantidad']} x {$producto['Precio']}€<br>";
                        echo "<small>Total: {$subtotal}€</small>";
                        echo "</div>";
                        echo '<form method="POST" action="ver_carrito.php" class="d-inline">';
                        echo "<input type='hidden' name='producto_id' value='{$producto['id_producto']}'>";
                        echo "<button type='submit' name='quitar' class='btn btn-danger btn-sm'>Quitar</button>";
                        echo '</form>';
                        echo '</div>';
                    }
                    echo '</div>';

                    // Mostrar precio total y botón Comprar
                    echo "<div class='mt-4 text-end'>";
                    echo "<h4>Total: {$precioTotal}€</h4>";
                    echo '<a href="ticket.php" class="btn btn-primary btn-lg">Comprar</a>';
                    echo "</div>";
                }
                ?>

            </section>
        </main>
        <?php include_once 'footer.php' ?> <!-- el footer -->
    </body>

</html>
