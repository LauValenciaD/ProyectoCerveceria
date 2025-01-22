<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="estilo.css">
    <!-- Hecho por Laura Valencia Díaz -->
    <title>Cervecería - Laura Valencia</title>
</head>
<body>
    <div class="container">
        <?php
        //VERIFICAR QUE EL NETBEANS TENGA LA URL DEL PROYECTO CORRECTA PARA QUE FUNCIONE Y QUE EL PUERTO DE LA BD ESTE BIEN
        function comprobarNombre($dato) {
            $dato = trim($dato);
            $dato = stripslashes($dato);
            $dato = htmlspecialchars($dato);
            return $dato;
        }   
            function cifrarClave($clave) {
            return password_hash($clave, PASSWORD_DEFAULT);
        }

        if (isset($_POST["submit"])) {
            $user = comprobarNombre($_POST["user"]);
            $password = $_POST["clave"];
            $errores = array();

            if (empty($user) || empty($password)) {
                array_push($errores, "Faltan datos.");
            }

            //PONER MENSAJE DE ERROR SI HAY CARACTERES RAROS?

            if (count($errores) > 0) { // Si hay errores, los imprime
                foreach ($errores as $error) {
                    echo "<div style='background-color: red; color: white;'>$error</div>";
                }
            } else {
                // Conexión a la base de datos
                require_once "conexion.php";
                $sql = "SELECT * FROM usuario WHERE correo = ?";
                $sentencia = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($sentencia, "s", $user);
                mysqli_stmt_execute($sentencia);
                $result = mysqli_stmt_get_result($sentencia);
                $registrado = mysqli_fetch_assoc($result); //devuelve false si no encuentra el usuario

                if ($registrado) { // Si el usuario ya está registrado
                        if (password_verify($password, $registrado["password"])) {
                            session_start();
                            $_SESSION["user"] = $user;
                            if ($user == "root") { 
                                header("Location: admin_dashboard.php");
                                exit();
                            }
                           else{
                            header("Location: dashboard.php");
                           exit();}
                        } else {
                            echo "<div style='background-color: red; color: white;'>La contraseña no coincide</div>";
                        } 
                        //CODIGO PARA REGISTRAR USUARIOS CON CLAVE CIFRADA
//                } else { // Si el usuario NO está registrado
//                       $passwordHash = cifrarClave($password); //por seguridad guardamos la clave cifrada
//                    $sql = "INSERT INTO usuario (correo, password) VALUES (?, ?)";
//                    $sentencia = mysqli_prepare($con, $sql);
//
//                    if ($sentencia) {
//                         mysqli_stmt_bind_param($sentencia, "ss", $user, $passwordHash);
//                        mysqli_stmt_execute($sentencia);
//                        echo "<div style='background-color: green; color: white;'>Usuario registrado correctamente.</div>";
//                    } else {
//                        die("Error inesperado: algo salió mal.");
//                    }
                }
            }
        }
        ?>

        <form action="index.php" method="post">
            <h1>APP REGISTRO CERVECERO</h1>
            <h3>Conectar</h3>
            <fieldset>
               <div class="datos">
                <p>Escriba su correo y contraseña</p> <br>

                <p><label for="user">Email:</label>
                    <input type="text" name="user" id="user" /></p> <br>
                <p><label for="clave">Contraseña:</label>
                    <input type="password" name="clave" id="clave" /></p> <br>
               </div>
            </fieldset>
            <p>
                <strong>Nota:</strong> El usuario Usuario se llama <strong>user@gmail.com</strong> y su contraseña también es <strong>user</strong>.
            </p>
            <p>
                <strong>Nota:</strong> El usuario Administrador se llama <strong>root</strong> y su contraseña también es <strong>root</strong>.
            </p>
               <div class="form-row submit-btn">
               <div class="input-data">
                  <div class="inner"></div> 
                  <input type="submit" value="Conectar" name="submit">
               </div>
            </div>
        </form>
    </div>
</body>
</html>
