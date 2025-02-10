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
        <?php include_once 'header.php' ?> <!-- el header -->
        <main>
        <section class="container my-4">
    <h2>Tu ticket de la compra</h2>

    <?php
$productos = mostrarCarrito($con);

if (empty($productos)) {
    echo "<p>No hay artículos.</p>";
} else {
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered text-center">';
    echo '<thead class="table-dark">';
    echo '<tr>';
    echo '<th>Producto</th>';
    echo '<th>Cantidad</th>';
    echo '<th>Precio Unitario</th>';
    echo '<th>Subtotal</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $precioTotal = 0;

    foreach ($productos as $producto) {
        $subtotal = $producto['cantidad'] * $producto['Precio'];
        $precioTotal += $subtotal;

        echo '<tr>';
        echo "<td>{$producto['Denominacion_Cerveza']}</td>";
        echo "<td>{$producto['cantidad']}</td>";
        echo "<td>{$producto['Precio']}€</td>";
        echo "<td>{$subtotal}€</td>";
        echo '</tr>';
    }

    echo '</tbody>';
    echo '<tfoot>';
    echo '<tr>';
    echo '<td colspan="3" class="text-end"><strong>Total:</strong></td>';
    echo "<td><strong>{$precioTotal}€</strong></td>";
    echo '</tr>';
    echo '</tfoot>';
    echo '</table>';
    echo '</div>';

    // Botón para finalizar compra
    echo '<div class="text-end mt-3">';
    echo '<a href="ver_carrito.php" class="btn btn-primary btn-lg">Finalizar compra</a>';
    echo '</div>';
}
vaciarCarrito($carrito_id, $con);
    ?>

</section>


        </main>
<?php include_once 'footer.php' ?> <!-- el footer -->
        </body>

</html>
