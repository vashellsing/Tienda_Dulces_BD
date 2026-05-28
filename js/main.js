/* =========================================================================
   FUNCIONES DE UTILIDAD Y MODALES
   ========================================================================= */ 

const formatearPrecio = (precio) => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP', 
        minimumFractionDigits: 0
    }).format(precio);
};

const actualizarContadorCarrito = async () => {
    const contadorElemento = document.getElementById('contador-carrito');
    if (!contadorElemento) return;

    const usuarioLogueado = localStorage.getItem('usuarioLogueado');

    if (usuarioLogueado === 'true') {
        // 1. SI ESTÁ LOGUEADO: Le pregunta a la Base de Datos
        try {
            const respuesta = await fetch('api/api_carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'obtener' })
            });
            const data = await respuesta.json();
            if (data.exito && data.carrito) {
                const totalItems = data.carrito.reduce((suma, item) => suma + item.cantidad, 0);
                contadorElemento.style.display = totalItems === 0 ? 'none' : 'inline-block';
                contadorElemento.textContent = totalItems;
            } else {
                contadorElemento.style.display = 'none';
            }
        } catch (error) {
            contadorElemento.style.display = 'none';
        }
    } else {
        // 2. SI ES INVITADO: Cuenta lo que hay en el LocalStorage
        const carritoLocal = JSON.parse(localStorage.getItem('carrito')) || [];
        const totalItems = carritoLocal.reduce((suma, item) => suma + (item.cantidad || 1), 0);
        
        if (totalItems === 0) {
            contadorElemento.style.display = 'none';
        } else {
            contadorElemento.style.display = 'inline-block';
            contadorElemento.textContent = totalItems;
        }
    }
};
// Función para crear modales dinámicos (Movida arriba para mayor seguridad)
const mostrarModal = (mensaje, accionAlCerrar = null) => {
    const overlay = document.createElement('div');
    overlay.classList.add('modal-overlay');

    const caja = document.createElement('div');
    caja.classList.add('modal-caja');

    caja.innerHTML = `
        <p>${mensaje}</p>
        <button class="btn btn-primario btn-cerrar-modal">Aceptar</button>
    `;

    overlay.appendChild(caja);
    document.body.appendChild(overlay);

    const btnCerrar = caja.querySelector('.btn-cerrar-modal');
    btnCerrar.addEventListener('click', () => {
        overlay.remove(); 
        if (accionAlCerrar) {
            accionAlCerrar();
        }
    });
};

/* =========================================================================
   LÓGICA DE LA PÁGINA PRINCIPAL
   ========================================================================= */ 

const sincronizarCarritoInvitado = async () => {
    const usuarioLogueado = localStorage.getItem('usuarioLogueado');
    const carritoLocal = JSON.parse(localStorage.getItem('carrito')) || [];

    if (usuarioLogueado === 'true' && carritoLocal.length > 0) {
        for (const producto of carritoLocal) {
            // Enviamos el dulce a la BD tantas veces como cantidades acumuló el invitado
            for (let i = 0; i < (producto.cantidad || 1); i++) {
                await fetch('api/api_carrito.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ accion: 'agregar', producto_id: producto.id })
                });
            }
        }
        // Una vez guardados todos en MySQL, limpiamos el rastro local
        localStorage.removeItem('carrito');
        actualizarContadorCarrito();
    }
};

/* =========================================================================
   LÓGICA DEL MENÚ DE NAVEGACIÓN (SESIÓN)
   ========================================================================= */

const verificarSesionMenu = () => {
    const itemLogin = document.getElementById('item-login');
    const itemLogout = document.getElementById('item-logout');
    const btnCerrarSesion = document.getElementById('btn-cerrar-sesion');

    const usuarioLogueado = localStorage.getItem('usuarioLogueado');

    if (usuarioLogueado === 'true') {
        if (itemLogin) itemLogin.style.display = 'none';
        if (itemLogout) itemLogout.style.display = 'block';
    } else {
        if (itemLogin) itemLogin.style.display = 'block';
        if (itemLogout) itemLogout.style.display = 'none';
    }

    if (btnCerrarSesion) {
        // Para que no se multipliquen los eventos si se llama varias veces
        const nuevoBtnCerrar = btnCerrarSesion.cloneNode(true);
        btnCerrarSesion.parentNode.replaceChild(nuevoBtnCerrar, btnCerrarSesion);

        nuevoBtnCerrar.addEventListener('click', (evento) => {
            evento.preventDefault(); 
            
            // 1. Borramos la memoria del navegador
            localStorage.removeItem('usuarioLogueado');
            localStorage.removeItem('nombreUsuario');
            
            // 2. Ejecutamos el archivo PHP que destruye la sesión en el servidor
            mostrarModal('Has cerrado sesión exitosamente.', () => {
                window.location.href = 'index.php?vista=logout'; 
            });
        });
    }
};

/* =========================================================================
   INICIALIZACIÓN
   ========================================================================= */

document.addEventListener('DOMContentLoaded', () => {
    sincronizarCarritoInvitado(); 
    actualizarContadorCarrito();
    verificarSesionMenu();
});