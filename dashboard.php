<?php
session_start();
$user = $_SESSION['user'];
if (!isset($_SESSION["user"])) { //si no ha iniciado sesion se redirije al inicio
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="estilo.css">
        <!-- Hecho por Laura Valencia Díaz -->
        <title>Panel de usuario</title>
    </head>
    <body>
        <div class="container">
            <h1>Bienvenido al panel de usuario, <?php echo $user; ?></h1>
            <?php
            require_once "conexion.php";   //mostrar datos del usuario
            $sql = "SELECT * FROM usuarios WHERE usuario = ?";
            $sentencia = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($sentencia, "s", $user);
            mysqli_stmt_execute($sentencia);
            $result = mysqli_stmt_get_result($sentencia);
            $registrado = mysqli_fetch_assoc($result);
            echo '<fieldset>';
            echo "<div class='usuario'> <ul>";
            echo "<li>Nombre de usuario: " . $registrado["usuario"] . "</li>";
            echo "<li>Nombre: " . $registrado["nombre"] . "</li>";
            echo "<li>Apellidos: " . $registrado["apellidos"] . "</li>";
            echo "<li>DNI: " . $registrado["dni"] . "</li>";
            if ($registrado["foto"] !== null) {
                echo "<li>Foto: " . "<img src='" . $registrado["foto"] . "' alt='Imagen subida' style= 'max-width: 300px'>" . "</li>"; // Mostrar la imagen subida usando la ruta relativa
            } else {
                echo "<li>Foto: No hay imagen subida.</li>";
            }
            echo "</ul> </div>";

            echo '</fieldset>';

            function comprobarNombre($dato) {
                $dato = trim($dato);
                $dato = stripslashes($dato);
                $dato = htmlspecialchars($dato);
                return $dato;
            }

            if (isset($_POST["submit"])) {//si el usuario modifica los datos
                $nombre = comprobarNombre($_POST["nombre"]);
                $apellidos = comprobarNombre($_POST["apellidos"]);
                $dni = $_POST["dni"];
                $rutaFoto = comprobarImg(); //ruta del servidor donde se ha almacenado la foto
                $errores = array();

                if (empty($nombre) || empty($apellidos)) {
                    array_push($errores, "Para modificar los datos, debe rellenar todos los campos.");
                }

                if (strlen($dni) !== 9) {
                    array_push($errores, "El DNI debe tener 9 caracteres.");
                }

                if (count($errores) > 0) { // Si hay errores, los imprime
                    foreach ($errores as $error) {
                        echo "<div style='background-color: red; color: white;'>$error</div>";
                    }
                } else { //si no hay errores, hace un update de la tabla  
                    //borramos primero la foto para que no se llene el servidor
                    if ($registrado["foto"] !== null) {
                        if (file_exists($registrado["foto"])) { //la ruta registrada
                            unlink($registrado["foto"]); // Borrar la foto del servidor
                        }
                    }
                    $sentencia = mysqli_prepare($con, "UPDATE usuarios SET dni = '$dni', nombre = '$nombre', apellidos = '$apellidos', foto = '$rutaFoto' WHERE usuario = '$user'");
                    mysqli_stmt_execute($sentencia);
                    if ($sentencia) { //si la sentencia se ejecuta correctamente
                        header("Location: dashboard.php"); //recargar la pagina
                    } else {
                        die("Error inesperado: Algo salió mal.");
                    }
                }
            }

            function comprobarImg() {
                // Verificar si hay errores en la subida del archivo
                $errores = $_FILES['foto']['error'];
                if ($errores !== UPLOAD_ERR_OK) {
                    echo "<div style='background-color: red; color: white;'><strong> Hay un error en la imagen o falta subir la imagen. </strong> El error es $errores .</div>";
                } else { // Obtener información del archivo
                    $nombre = $_FILES['foto']['name'];
                    $tamanio = $_FILES['foto']['size'];
                    $tipo = $_FILES['foto']['type'];
                    $origen = $_FILES['foto']['tmp_name'];

                    if ($tamanio > 1000000) { // Verificar tamaño
                        echo "<div style='background-color: red; color: white;'>La imagen es demasiado grande. Máximo 1 MB.<br></div>";
                    }
                    if ($tipo !== "image/jpeg" && $tipo !== "image/png") { // Verificar tipo de archivo
                        echo "La imagen debe ser jpg o png. <br>";
                        echo "Tipo de archivo: $tipo <br>";
                    } else {

                        $destino = "archivos/" . $nombre;  // Ruta relativa a la carpeta "archivos"
                        // Mover el archivo a la ruta de destino
                        if (move_uploaded_file($origen, $destino)) {
                            echo "<div style='background-color: green; color: white;'>La imagen fue subida correctamente.</div>";
                            return $destino; //devolvemos la ruta
                        } else {
                            echo "<div style='background-color: red; color: white;'>Error al mover el archivo a la carpeta de destino.<br></div>";
                        }
                    }
                }
            }
            ?>
            <form action="dashboard.php" method="post" enctype="multipart/form-data">
                <h2>Modificar datos</h2>
                <fieldset>
                    <div class="datos">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" /> 
                        <p><label for="apellidos">Apellidos:</label>
                            <input type="text" name="apellidos" id="apellidos" /></p>
                        <p><label for="dni">DNI:</label>
                            <input type="text" name="dni" id="dni" /></p>
                        <p><label for="foto">Subir foto:</label>
                            <input type="file" name="foto" id="foto" /></p>
                    </div>
                    <br />

                    <div class="form-row submit-btn">
                        <div class="input-data">
                            <div class="inner"></div> 
                            <input type="submit" name="submit" value="Actualizar" />
                        </div> </div>
                </fieldset>
            </form>
            <a href="cerrar_sesion.php">Cerrar sesión</a>
        </div>
    </body>
</html>
