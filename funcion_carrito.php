<?php
// Inicia el carrito en cada página, si el usuario ya tenía un carrito de otra visita lo puede recuperar
function inicializarCarrito($usuarioId, $conexion)
{
    if (isset($_SESSION["carrito_id"])) {
        return; // Ya tiene un carrito en sesión
    }
    // Buscar si el usuario ya tiene un carrito
    $query = "SELECT ID_CARRITO FROM carrito WHERE Id_usuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        $_SESSION["carrito_id"] = $fila["ID_CARRITO"]; // Guardar en la sesión
    } else {
        // Si no tiene, crear un nuevo carrito
        $query = "INSERT INTO carrito (Id_usuario) VALUES (?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $_SESSION["carrito_id"] = $stmt->insert_id;
    }
    $stmt->execute();
    $stmt->close();
}


// Añade los productos a la tabla del carrito
function agregarAlCarrito($carrito_id, $producto_id, $cantidad, $conexion)
{
    // Verificar si el producto ya está en el carrito
    $query = "SELECT cantidad FROM productos_carritos WHERE id_carrito = ? AND id_producto = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $carrito_id, $producto_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) { // Si ya tenía el mismo producto, la cantidad se actualiza
        $nuevaCantidad = $fila['cantidad'] + $cantidad;
        $query = "UPDATE productos_carritos SET cantidad = ? WHERE id_carrito = ? AND id_producto = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("iii", $nuevaCantidad, $carrito_id, $producto_id);
    } else { // Si no tenía productos, se inserta el producto en el carrito 
        $query = "INSERT INTO productos_carritos (id_carrito, id_producto, cantidad) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("iii", $carrito_id, $producto_id, $cantidad);
    }

    $stmt->execute();
    $stmt->close();
}
function borrarDelCarrito($carrito_id, $producto_id, $conexion)
{
    $query = "DELETE FROM productos_carritos WHERE id_carrito = ? AND id_producto = ?"; //Busca el producto que tenga el mismo id y que sea del carrito correcto
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $carrito_id, $producto_id);
    $stmt->execute();
    $stmt->close();
}

// Pinta el carrito en la página del carrito
function mostrarCarrito($conexion)
{
    if (!isset($_SESSION["carrito_id"]))
        return []; //Si no encuentra el carrito, devuelve array vacío

    $carrito_id = $_SESSION["carrito_id"];
    // Hace una consulta uniendo el id del producto del carrito con el id del producto de la tabla Productos para coger sus datos
    $query = "SELECT c.id_producto, p.Denominacion_Cerveza, p.Precio, c.cantidad 
              FROM productos_carritos c 
              JOIN productos p ON c.id_producto = p.ID_PRODUCTO
              WHERE c.id_carrito = ?";

    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $carrito_id);
    $stmt->execute();
    // Devuelve los productos
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
// Cuando compra, se borrar los productos del carrito pero no se borra el carrito
function vaciarCarrito($carrito_id, $conexion)
{
    $query = "DELETE FROM productos_carritos WHERE id_carrito = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $carrito_id);
    $stmt->execute();
    $stmt->close();
}
// Para saber cuantos productos hay en el carrito y ponerlo en el icono del header
function contarArticulos($carrito_id, $conexion)
{
    $query = "SELECT SUM(cantidad) AS total FROM productos_carritos WHERE id_carrito = ?;";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $carrito_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();

    $stmt->execute();

    return $fila['total'] ?? 0; // Si es NULL, devuelve 0
}





