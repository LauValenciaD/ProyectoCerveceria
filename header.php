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
                    <ul class="nav navbar-nav mx-lg-auto d-flex justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link nav-title" href="cerrar_sesion.php">HOME</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-title" href="catalogo.php">CATÁLOGO</a>
                        </li>
                        <!-- si el usuario no es admin, no verá esta opción -->
                        <li class="nav-item" <?php
                        if ($root===false) {
                            echo 'style= "display:none;"';
                        }
                        ?>>
                            <a class="nav-link nav-title" href="insertar.php">INSERTAR</a>
                        </li>
                    </ul>
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
                <a class="nav-icon position-relative text-decoration-none" href="ver_carrito.php">
                    <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i> <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-light text-dark"><?php if ($root==false && $_SESSION['cantidad_prod'] > 0) {
                            echo $_SESSION['cantidad_prod'];
                        } ?></span>
                </a>
                <a class="nav-icon position-relative text-decoration-none" href="">
                    <i class="fa fa-fw fa-user text-dark mr-3"></i>
                </a>
<?php echo '<p class= "m-0">Hola, ' . $user . '</p>'; ?>
            </div>
        </div>
    </nav>
</header>