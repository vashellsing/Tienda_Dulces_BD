/* =========================================================================
   catalogo.js - Lógica Híbrida (Invitados y Logueados)
   ========================================================================= */

document.addEventListener('DOMContentLoaded', () => {
    const botonesFiltro = document.querySelectorAll('.btn-filtro');
    const tarjetasProducto = document.querySelectorAll('.tarjeta-producto');
    const botonesAgregar = document.querySelectorAll('.btn-agregar');

    // 1. LÓGICA DE FILTROS
    if (botonesFiltro.length > 0 && tarjetasProducto.length > 0) {
        botonesFiltro.forEach(boton => {
            boton.addEventListener('click', (evento) => {
                botonesFiltro.forEach(btn => btn.classList.remove('activo'));
                const botonClickeado = evento.target;
                botonClickeado.classList.add('activo');

                const categoriaSeleccionada = botonClickeado.getAttribute('data-categoria');

                tarjetasProducto.forEach(tarjeta => {
                    const categoriaTarjeta = tarjeta.getAttribute('data-categoria');
                    if (categoriaSeleccionada === 'Todos' || categoriaSeleccionada === categoriaTarjeta) {
                        tarjeta.style.display = 'block';
                    } else {
                        tarjeta.style.display = 'none';
                    }
                });
            });
        });
    }

    // 2. LÓGICA DE AGREGAR AL CARRITO (HÍBRIDA)
    if (botonesAgregar.length > 0) {
        botonesAgregar.forEach(boton => {
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
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ accion: 'agregar', producto_id: producto.id })
                        });
                        const data = await respuesta.json();
                        
                        if (data.exito) {
                            if (typeof mostrarModal === 'function') mostrarModal(`¡${producto.nombre} añadido a tu cuenta!`);
                            if (typeof actualizarContadorCarrito === 'function') actualizarContadorCarrito();
                        }
                    } catch (error) { console.error("Error API:", error); }
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
                    
                    if (typeof mostrarModal === 'function') mostrarModal(`¡${producto.nombre} añadido al carrito!`);
                    if (typeof actualizarContadorCarrito === 'function') actualizarContadorCarrito();
                }
            });
        });
    }
});