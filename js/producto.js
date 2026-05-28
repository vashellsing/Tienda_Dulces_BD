/* =========================================================================
   producto.js - Lógica Híbrida (Invitados y Logueados)
   ========================================================================= */

document.addEventListener('DOMContentLoaded', () => {
    const botonesAgregar = document.querySelectorAll('.btn-agregar-api');

    if (botonesAgregar.length > 0) {
        botonesAgregar.forEach(boton => {
            boton.addEventListener('click', async () => {
                
                // Recopilamos todos los datos del HTML
                const producto = {
                    id: parseInt(boton.getAttribute('data-id')),
                    nombre: boton.getAttribute('data-nombre'),
                    precio: parseInt(boton.getAttribute('data-precio')),
                    imagen: boton.getAttribute('data-imagen')
                };

                const usuarioLogueado = localStorage.getItem('usuarioLogueado');

                if (usuarioLogueado === 'true') {
                    // SI ESTÁ LOGUEADO -> Petición a MySQL
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
                    // SI ES INVITADO -> Guarda en memoria local
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