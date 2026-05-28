<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sweet Dreams - Tienda de Dulces y Regalos</title>

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

                <li id="item-login"><a href="index.php?vista=login">Iniciar Sesión</a></li>
                <li id="item-logout" style="display: none">
                    <a href="#" id="btn-cerrar-sesion">Cerrar Sesión</a>
                </li>
                <li>
                    <a href="index.php?vista=carrito" class="enlace-carrito">
                        🛒 Carrito <span id="contador-carrito" class="insignia">0</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>