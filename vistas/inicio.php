<?php
// Incluimos la conexión para poder consultar la base de datos
require_once 'includes/conexion.php';

try {
    // Traemos solo los primeros 3 productos de la base de datos
    $sql = "SELECT * FROM productos LIMIT 3";
    $stmt = $conn->query($sql);
    $destacados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $destacados = [];
}
?>

<main>
    <section class="hero-portada contenedor" id="inicio">
        <div class="hero-contenido">
            <span class="hero-badge">Regalos bonitos</span>
            <h1 class="hero-titulo">Endulza tus <span>mejores</span> momentos</h1>
            <p class="hero-texto">Descubre nuestra exclusiva colección de dulces, desayunos sorpresa, flores y peluches preparados con mucho amor para regalar momentos inolvidables.</p>
            <div class="hero-botones">
                <a href="index.php?vista=catalogo" class="btn btn-primario">Ver catálogo</a>
                <a href="#nosotros" class="btn btn-secundario">Conócenos</a>
            </div>
        </div>
        <div class="hero-visual">
            <span class="hero-etiqueta">Favorito del mes</span>
            <img src="recursos/img/caja_dulces.png" alt="Caja de dulces" />
        </div>
    </section>

    <section class="contenedor seccion-destacados" id="productos">
        <h2 class="titulo-seccion">Nuestros favoritos</h2>
        <div id="contenedor-destacados" class="cuadricula-productos">
            <?php foreach ($destacados as $producto): ?>
                <div class="tarjeta-producto">
                    <img src="recursos/img/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                    <p class="precio">$ <?php echo number_format($producto['precio'], 0, ',', '.'); ?></p>

                    <button class="btn btn-primario btn-agregar"
                        data-id="<?php echo $producto['id']; ?>"
                        data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
                        data-precio="<?php echo $producto['precio']; ?>"
                        data-imagen="<?php echo htmlspecialchars($producto['imagen']); ?>">
                        Agregar al Carrito
                    </button>
                    <a href="index.php?vista=producto&id=<?php echo $producto['id']; ?>" class="btn-detalles">Ver detalles</a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <section class="contenedor" id="nosotros">
        <h2 class="titulo-seccion">¿Por qué elegirnos?</h2>

        <div class="seccion-info-grid">
            <article class="tarjeta-info">
                <h3>Regalos personalizados</h3>
                <p>
                    Creamos detalles especiales para cumpleaños, aniversarios y
                    fechas importantes.
                </p>
            </article>

            <article class="tarjeta-info">
                <h3>Productos artesanales</h3>
                <p>
                    Seleccionamos dulces, flores y peluches con una presentación
                    bonita y cuidada.
                </p>
            </article>

            <article class="tarjeta-info">
                <h3>Compra fácil</h3>
                <p>
                    Navega, agrega al carrito, inicia sesión y finaliza tu pedido en
                    pocos pasos.
                </p>
            </article>
        </div>
    </section>

    <section class="contenedor seccion-contacto" id="contacto">
        <div>
            <h2 class="titulo-seccion" style="text-align: left; margin-bottom: 15px">
                Contacto
            </h2>
            <p class="texto-contacto">
                ¿Necesitas un regalo especial? Escríbenos y te ayudamos a armar el
                detalle perfecto.
            </p>
        </div>

        <div class="contacto-caja">
            <p><strong>Correo:</strong> pedidos@sweetdreams.com</p>
            <p><strong>Teléfono:</strong> +57 300 000 0000</p>
            <p><strong>Horario:</strong> Lunes a sábado, 8:00 a.m. - 6:00 p.m.</p>

            <a href="index.php?vista=catalogo" class="btn btn-primario btn-bloque">Comprar ahora</a>
        </div>
    </section>

</main>



<script>
    document.addEventListener('DOMContentLoaded', () => {
        const botonesInicio = document.querySelectorAll('.seccion-destacados .btn-agregar');

        if (botonesInicio.length > 0) {
            botonesInicio.forEach(boton => {
                boton.addEventListener('click', async () => {
                    const producto = {
                        id: parseInt(boton.getAttribute('data-id')),
                        nombre: boton.getAttribute('data-nombre'),
                        precio: parseInt(boton.getAttribute('data-precio')),
                        imagen: boton.getAttribute('data-imagen')
                    };

                    const usuarioLogueado = localStorage.getItem('usuarioLogueado');

                    if (usuarioLogueado === 'true') {
                        try {
                            const respuesta = await fetch('api/api_carrito.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    accion: 'agregar',
                                    producto_id: producto.id
                                })
                            });
                            const data = await respuesta.json();
                            if (data.exito) {
                                mostrarModal(`¡${producto.nombre} añadido a tu cuenta!`);
                                actualizarContadorCarrito();
                            }
                        } catch (error) {
                            console.error(error);
                        }
                    } else {
                        const carritoActual = JSON.parse(localStorage.getItem('carrito')) || [];
                        const indice = carritoActual.findIndex(item => item.id === producto.id);
                        if (indice !== -1) {
                            carritoActual[indice].cantidad = (carritoActual[indice].cantidad || 1) + 1;
                        } else {
                            producto.cantidad = 1;
                            carritoActual.push(producto);
                        }
                        localStorage.setItem('carrito', JSON.stringify(carritoActual));
                        mostrarModal(`¡${producto.nombre} añadido al carrito!`);
                        actualizarContadorCarrito();
                    }
                });
            });
        }
    });
</script>