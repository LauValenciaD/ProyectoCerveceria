<?php
session_start();
$user = $_SESSION['user'];
if ($user !== "root") { //si no ha iniciado sesion con root se redirije al inicio
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
        <title>Panel de admin</title>
    </head>
    <body>
        <div class="container">
            <h1>Bienvenido al panel de control, Admin</h1>
            <?php
            require_once "conexion.php";   //mostrar datos de todos los usuarios
            echo '<h2>Lista de usuarios de la BD </h2>';
            $sql = "SELECT * FROM usuarios";
            $sentencia = mysqli_prepare($con, $sql);
            mysqli_stmt_execute($sentencia);
            $result = mysqli_stmt_get_result($sentencia);
            $users_all = mysqli_fetch_all($result, MYSQLI_ASSOC); //guardar todos los usuarios
            echo '<fieldset>';
            foreach ($users_all as $registrado) { //imprimir datos
                echo "<div class='usuario'><ul>";
                echo "<li>Nombre de usuario: " . $registrado["usuario"] . "</li>";
                echo "<li>Nombre: " . $registrado["nombre"] . "</li>";
                echo "<li>Apellidos: " . $registrado["apellidos"] . "</li>";
                echo "<li>DNI: " . $registrado["dni"] . "</li>";
                if ($registrado["foto"] !== null) {
                    echo "<li>Foto: " . "<img src='" . $registrado["foto"] . "' alt='Imagen subida' style= 'max-width: 300px'>" . "</li>"; // Mostrar la imagen subida usando la ruta relativa
                } else {
                    echo "<li>Foto: No hay imagen subida.</li>";
                }
                echo "</ul></div>";
            }
            echo '</fieldset>';

            function comprobarNombre($dato) {
                $dato = trim($dato);
                $dato = stripslashes($dato);
                $dato = htmlspecialchars($dato);
                return $dato;
            }

            if (isset($_POST["borrar"])) {//si el admin pulsa el boton de borrar
                $nombre = comprobarNombre($_POST["nombre"]);
                $errores = array();

                if (empty($nombre)) {
                    array_push($errores, "Para borrar los datos, debe escribir el nombre de usuario.");
                }

                if ($nombre == 'root') {
                    array_push($errores, "No se puede eliminar el usuario root");
                }

                if (count($errores) > 0) { // Si hay errores, los imprime
                    foreach ($errores as $error) {
                        echo "<div style='background-color: red; color: white;'>$error</div>";
                    }
                } else { //si no hay errores, borra un usuario 
                    //borramos primero la foto para que no se llene el servidor
                    if ($registrado["foto"] !== null) {
                        if (file_exists($registrado["foto"])) {
                            unlink($registrado["foto"]); // Borrar la foto del servidor
                        }
                    }
                    $sentencia = mysqli_prepare($con, "DELETE FROM usuarios WHERE usuario = '$nombre'");
                    mysqli_stmt_execute($sentencia);
                    if ($sentencia) { //si la sentencia se ejecuta correctamente
                        header("Location: admin_dashboard.php"); //recargar la pagina
                    } else {
                        die("Error inesperado: Algo salió mal.");
                    }
                }
            }
            ?>
            <form action="admin_dashboard.php" method="post">
                <h2>Borrar usuario</h2>
                <fieldset>
                    <p><label for="nombre">Nombre de usuario a borrar:</label>
                        <input type="text" name="nombre" id="nombre" placeholder: "Nombre de usuario a borrar..."/>
                    </p>

                    <div class="form-row submit-btn">
                        <div class="input-data">
                            <div class="inner"></div> 
                            <input type="submit" name="borrar" value="Borrar" />
                        </div> </div>
                </fieldset>
            </form>
            <a href="cerrar_sesion.php">Cerrar sesión</a>
        </div>
    </body>
</html>

