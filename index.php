<?php
// 1. Iniciamos sesión aquí para que TODO el sitio sepa quién es el usuario
session_start();

// 2. Incluimos la parte superior de la página
include 'includes/header.php';

// 3. Revisamos la URL
$vista = isset($_GET['vista']) ? $_GET['vista'] : 'inicio';

// 4. Armamos la ruta
$ruta_vista = 'vistas/' . $vista . '.php';

// 5. Comprobamos e incluimos
if (file_exists($ruta_vista)) {
    include $ruta_vista;
} else {
    echo '<main class="contenedor" style="min-height: 50vh; padding-top: 5rem;">
            <h2 class="titulo-seccion">Error 404</h2>
            <p style="text-align: center;">La página que buscas no existe.</p>
          </main>';
}

// 6. Incluimos la parte inferior
include 'includes/footer.php';
