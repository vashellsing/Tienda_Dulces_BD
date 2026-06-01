<?php
// Asegurarnos de que la sesión esté iniciada para poder leer el nombre
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="google-site-verification" content="tDhu0m7eWtZrA8QuQQhSEGSjPX5vphJ9MrjHHbiVuCU" />

    <title>Sweet Dreams | Desayunos Sorpresa y Dulces en Popayán</title>
    <link rel="icon" type="image/png" href="recursos/img/logo_3.png" />

    <meta name="description" content="Encuentra los mejores regalos, cajas de chocolates artesanales y desayunos sorpresa a domicilio en Popayán. Endulza tus momentos especiales con Sweet Dreams.">

    <meta property="og:title" content="Sweet Dreams | Regalos en Popayán">
    <meta property="og:description" content="Cajas de dulces y desayunos sorpresa hechos con amor. Compra online.">
    <meta property="og:image" content="https://dulces-detalles-ecommerse-deploy.onrender.com/recursos/img/logo_3.png">
    <meta property="og:url" content="https://dulces-detalles-ecommerse-deploy.onrender.com/">

    <link rel="stylesheet" href="css/variables.css" />
    <link rel="stylesheet" href="css/globales.css" />
    <link rel="stylesheet" href="css/componentes.css" />
    <link rel="stylesheet" href="css/paginas.css" />
</head>

<body>
    <header class="encabezado">
        <div class="contenedor-logo">
            <img src="recursos/img/logo_3.png" alt="Logo Tienda" class="logo" />
        </div>

        <nav class="navegacion">
            <ul class="menu">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="index.php?vista=catalogo">Productos</a></li>
                <li><a href="index.php#nosotros">Nosotros</a></li>
                <li><a href="index.php#contacto">Contacto</a></li>

                <?php if (isset($_SESSION['usuario_id'])): ?>

                    <li>
                        <span style="color: var(--color-primario); font-weight: bold; padding: 0.5rem 1rem; cursor: default;">
                            ¡Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>!
                        </span>
                    </li>

                    <li id="item-logout">
                        <a href="#" id="btn-cerrar-sesion">Cerrar Sesión</a>
                    </li>

                <?php else: ?>

                    <li id="item-login"><a href="index.php?vista=login">Iniciar Sesión</a></li>

                <?php endif; ?>
                <li>
                    <a href="index.php?vista=carrito" class="enlace-carrito">
                        🛒 Carrito <span id="contador-carrito" class="insignia">0</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>