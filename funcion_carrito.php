<?php
function inicializarCarrito($usuarioId, $conexion) {
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
}
function agregarAlCarrito($carrito_id,$producto_id, $cantidad, $conexion) {
    // Verificar si el producto ya está en el carrito
    $query = "SELECT cantidad FROM productos_carritos WHERE id_carrito = ? AND id_producto = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $carrito_id, $producto_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        $nuevaCantidad = $fila['cantidad'] + $cantidad;
        $query = "UPDATE productos_carritos SET cantidad = ? WHERE id_carrito = ? AND id_producto = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("iii", $nuevaCantidad, $carrito_id, $producto_id);
    } else {
        $query = "INSERT INTO productos_carritos (id_carrito, id_producto, cantidad) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("iii", $carrito_id, $producto_id, $cantidad);
    }

    $stmt->execute();
    $stmt->close();
}


function mostrarCarrito($conexion) {
    if (!isset($_SESSION["carrito_id"])) return [];

    $carrito_id = $_SESSION["carrito_id"];
    $query = "SELECT c.id_producto, p.Denominacion_Cerveza, p.Precio, c.cantidad 
              FROM productos_carritos c 
              JOIN productos p ON c.id_producto = p.ID_PRODUCTO
              WHERE c.id_carrito = ?";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $carrito_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}



?>
