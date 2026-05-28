<?php
// Nos aseguramos de tener acceso a la sesión actual
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vaciamos todas las variables de sesión
session_unset();

// Destruimos la sesión en el servidor
session_destroy();

// Redirigimos al usuario a la página principal
echo "<script>window.location.href = 'index.php';</script>";
exit;
