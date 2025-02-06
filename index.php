<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8" />
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/beer-logo.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="assets/css/bootstrap.css" />
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Hecho por Laura Valencia Díaz -->
        <title>Cervecería - Laura Valencia</title>
    </head>

    <body>
        <?php
        require_once "conexion.php";

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
            $mensaje = "";
           

            if (empty($user)) {
               $mensaje = "<div class= 'text-danger'>El correo está vacío</div>";
               array_push($errores, $mensaje);
               
               
            }
            if (empty($password)) {
               $mensaje = "<div class= 'text-danger'>La contraseña está vacía</div>";
               array_push($errores, $mensaje);
               
            }


            if (count($errores) == 0) {
                // Conexión a la base de datos
                require_once "conexion.php";
                $sql = "SELECT * FROM usuario WHERE CORREO = ?";
                $sentencia = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($sentencia, "s", $user);
                mysqli_stmt_execute($sentencia);
                $result = mysqli_stmt_get_result($sentencia);
                $registrado = mysqli_fetch_assoc($result); //devuelve false si no encuentra el usuario

                if ($registrado) { // Si el usuario ya está registrado
                    if (password_verify($password, $registrado["PASSWORD"])) {
                        session_start();
                        $_SESSION["user"] = $user;
                        $_SESSION["usuario_id"] = $registrado["ID_USUARIO"]; //Guardamos el id para buscar el carrito luego
                            header("Location: catalogo.php");
                            exit();
                        } else {
                            $mensaje = "<div class= 'text-danger'>Contraseña errónea</div>";
                        array_push($errores, $mensaje);
                        }
                    } else {
                        $mensaje = "<div class= 'text-danger'>Usuario no registrado</div>";
                        array_push($errores, $mensaje);
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
        
        ?>
        <header>
            <nav class="navbar navbar-expand-lg navbar-light shadow d-flex justify-content-center">
                <!-- logo -->
                <div class="container d-flex justify-content-between align-items-center" id="header">
                    <a class="navbar-brand logo" href="index.php"><img class="img-fluid" src="./assets/img/beer-logo.png"
                                                                       alt="" id="logo" /></a>
                    <h2>Cervecería online</h2>
                    <!-- botón menu móvil -->
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                            data-bs-target="#container_main_nav" aria-controls="container_main_nav" aria-expanded="false"
                            aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!--  menu collapse -->
                    <div class="collapse navbar-collapse flex-fill d-lg-flex justify-content-lg-center"
                         id="container_main_nav">
                        <div class="flex-fill">
                        </div>
                    </div>

                    <!-- barra de busqueda -->
                    <div class="search-container ms-lg-3 d-none d-lg-block">
                        <form action="" method="get">
                            <div class="input-group">
                                <input type="text" class="form-control nav-title" placeholder="Buscar..." />
                                <div class="input-group-text">
                                    <i class="fa fa-fw fa-search"></i>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- grupo de iconos -->
                    <div class="navbar align-self-center d-flex flex-nowrap" id="grupoIconos">
                        <a class="nav-icon d-inline d-lg-none" href="#" data-bs-toggle="modal"
                           data-bs-target="#container_search" id="inputMobileSearch">
                            <i class="fa fa-fw fa-search text-dark mr-2"></i>
                        </a>
                        <a class="nav-icon position-relative text-decoration-none" href="#">
                            <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                        </a>
                        <a class="nav-icon position-relative text-decoration-none" href="#">
                            <i class="fa fa-fw fa-user text-dark mr-3"></i>
                        </a>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <section class="mt-3">
                <div class="container-fluid">
                    <div class="row d-flex justify-content-center align-items-center h-100">
                        <div class="col-md-9 col-lg-6 col-xl-5">
                            <img src="./assets/img/cerveza.jpg" class="img-fluid rounded" alt="Logo cerveceria">
                        </div>
                        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                            <h1>INICIAR SESIÓN</h1>
                            <form action="index.php" method="post">
                                <!-- Email -->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="text" name="user" id="user" class="form-control form-control-lg"
                                           placeholder="Introduce tu email..." />
                                    <label class="form-label" for="user">Email</label>
                                </div>

                                <!-- Password-->
                                <div data-mdb-input-init class="form-outline mb-3">
                                    <input type="password" name="clave" id="clave" class="form-control form-control-lg"
                                           placeholder="Introduce la contraseña..." />
                                    <label class="form-label" for="clave">Contraseña</label>
                                </div>

                                <div>
                                    <p><strong>Nota:</strong> El usuario se llama <strong>user@gmail.com</strong> y su
                                        contraseña también es
                                        <strong>user</strong>.
                                    </p>
                                    <p>
                                        <strong>Nota:</strong> El Administrador se llama <strong>root</strong> y su
                                        contraseña también es
                                        <strong>root</strong>.
                                    </p>
                                </div>
                                <?php
                                        if (isset($_POST["submit"]) && count($errores) > 0) {
                                            foreach ($errores as $mensaje) {
                                            echo $mensaje;
                                            }
                                        }
                                        ?>

                                <div class="text-center text-lg-start mt-4 pt-2">
                                    <input type="submit" data-mdb-button-init data-mdb-ripple-init
                                           class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;"
                                           value="Iniciar sesión" name="submit">

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </section>

        </main>
        <footer class="py-3 my-4 mt-4 border-top">
            <ul class="nav justify-content-center pb-3 mb-3">
                <li class="nav-item">
                    <a href="http://localhost/ProyectoCerveceria/index.php" class="nav-link px-2 text-body-secondary">Iniciar sesión</a>
                </li>
                <li class="nav-item">
                    <a href="http://localhost/ProyectoCerveceria/cerrar_sesion.php" class="nav-link px-2 text-body-secondary">Cerrar sesión</a>
                </li>
            </ul>
            <p class="text-center text-body-secondary">
                © 2025. Hecho por Laura Valencia
            </p>
        </footer>

        <script src="assets/js/bootstrap.bundle.min.js"></script>
    </body>

</html>