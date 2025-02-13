<footer class="py-3 my-4 border-top">
    <ul class="nav justify-content-center pb-3 mb-3">
        <li class="nav-item" <?php if (isset($_SESSION['user'])) {
                echo 'style= "display:none;"';
            } ?>>
            <a href="index.php" class="nav-link px-2 text-body-secondary">Iniciar sesión</a> 
        </li>
        <li class="nav-item"  <?php if (!isset($_SESSION['user'])) {
                echo 'style= "display:none;"';
            } ?>>
            <a href="cerrar_sesion.php" class="nav-link px-2 text-body-secondary">Cerrar sesión</a>
        </li>
        <li class="nav-item">
            <a href="borrar_busqueda.php" class="nav-link px-2 text-body-secondary">Búsqueda avanzada</a>
        </li>
        <li class="nav-item" <?php
        if (!isset($_SESSION['user']) || $root === false) {
            echo 'style= "display:none;"';
        }
        ?>>
            <a href="insertar.php" class="nav-link px-2 text-body-secondary">Insertar producto</a>
        </li>
    </ul>
    <p class="text-center text-body-secondary">
        © 2025. Hecho por Laura Valencia
    </p>
</footer>

<script src="assets/js/bootstrap.bundle.min.js"></script>