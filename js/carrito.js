const listaCarrito = document.getElementById('lista-carrito');
const totalCarrito = document.getElementById('total-carrito');
const btnComprar = document.getElementById('btn-comprar');

let carrito = [];
const usuarioLogueado = localStorage.getItem('usuarioLogueado') === 'true';

// 1. CARGA INTELIGENTE
const obtenerCarritoContenido = async () => {
    if (usuarioLogueado) {
        // Logueado -> Pide datos a MySQL
        const respuesta = await fetch('api/api_carrito.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ accion: 'obtener' }) 
        });
        const data = await respuesta.json();
        carrito = data.exito ? data.carrito : [];
    } else {
        // Invitado -> Lee del navegador
        carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    }
    renderizarCarrito();
};

// 2. RENDERIZADO GENERAL
const renderizarCarrito = () => {
    if (!listaCarrito) return;
    listaCarrito.innerHTML = '';

    if (carrito.length === 0) {
        listaCarrito.innerHTML = '<p style="text-align: center; font-size: 18px; padding: 40px 0;">Tu carrito está vacío.</p>';
        totalCarrito.textContent = formatearPrecio(0);
        return;
    }

    let total = 0;
    carrito.forEach((producto, indice) => {
        // Unificamos nombres de llaves por si vienen de BD o Local
        const id_llave = usuarioLogueado ? producto.carrito_id : indice;
        const prod_id = producto.id;
        total += producto.precio * producto.cantidad;

        const item = document.createElement('div');
        item.style.display = 'flex';
        item.style.justifyContent = 'space-between';
        item.style.alignItems = 'center';
        item.style.padding = '15px 0';
        item.style.borderBottom = '1px solid #eee';

        item.innerHTML = `
            <div style="display: flex; align-items: center; gap: 15px;">
                <img src="recursos/img/${producto.imagen}" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover;">
                <span style="font-size: 16px; font-weight: bold;">${producto.nombre}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <button class="btn-restar" data-llave="${id_llave}" data-prodid="${prod_id}" data-cant="${producto.cantidad}">-</button>
                <span>${producto.cantidad}</span>
                <button class="btn-sumar" data-llave="${id_llave}" data-prodid="${prod_id}" data-cant="${producto.cantidad}">+</button>
                <span style="font-size: 16px; color: var(--color-acento); font-weight: bold;">${formatearPrecio(producto.precio * producto.cantidad)}</span>
                <button class="btn btn-eliminar" data-llave="${id_llave}" data-prodid="${prod_id}">Eliminar</button>
            </div>
        `;
        listaCarrito.appendChild(item);
    });
    totalCarrito.textContent = formatearPrecio(total);
    asignarEventosModificadores();
};

// 3. EVENTOS (+ , - , ELIMINAR) SEGÚN EL ROL
const asignarEventosModificadores = () => {
    document.querySelectorAll('.btn-sumar').forEach(b => b.addEventListener('click', e => cambiarCantidad(e, 1)));
    document.querySelectorAll('.btn-restar').forEach(b => b.addEventListener('click', e => cambiarCantidad(e, -1)));
    document.querySelectorAll('.btn-eliminar').forEach(b => b.addEventListener('click', eliminarItem));
};

const cambiarCantidad = async (e, cambio) => {
    const cantActual = parseInt(e.target.getAttribute('data-cant'));
    const nuevaCant = cantActual + cambio;

    if (usuarioLogueado) {
        const id_carrito = e.target.getAttribute('data-llave');
        await fetch('api/api_carrito.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ accion: 'actualizar', carrito_id: id_carrito, cantidad: nuevaCant })
        });
    } else {
        const index = e.target.getAttribute('data-llave');
        if (nuevaCant > 0) {
            carrito[index].cantidad = nuevaCant;
        } else {
            carrito.splice(index, 1);
        }
        localStorage.setItem('carrito', JSON.stringify(carrito));
    }
    obtenerCarritoContenido();
    if (typeof actualizarContadorCarrito === 'function') actualizarContadorCarrito();
};

const eliminarItem = async (e) => {
    if (usuarioLogueado) {
        const id_carrito = e.target.getAttribute('data-llave');
        await fetch('api/api_carrito.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ accion: 'eliminar', carrito_id: id_carrito })
        });
    } else {
        const index = e.target.getAttribute('data-llave');
        carrito.splice(index, 1);
        localStorage.setItem('carrito', JSON.stringify(carrito));
    }
    obtenerCarritoContenido();
    if (typeof actualizarContadorCarrito === 'function') actualizarContadorCarrito();
};

// 4. EL BLOQUEO FINAL DE COMPRA
const procesarCompra = async () => {
    // Si no está logueado, ¡freno de mano inmediato!
    if (!usuarioLogueado) {
        mostrarModal('Debes iniciar sesión para poder realizar tu compra.', () => {
            window.location.href = 'index.php?vista=login';
        });
        return;
    }

    if (carrito.length === 0) return;

    const respuesta = await fetch('api/api_carrito.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ accion: 'comprar' })
    });
    const data = await respuesta.json();

    if (data.exito) {
        mostrarModal(data.mensaje, () => { obtenerCarritoContenido(); });
    }
};

if (btnComprar) btnComprar.addEventListener('click', procesarCompra);
document.addEventListener('DOMContentLoaded', obtenerCarritoContenido);