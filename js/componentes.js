const inicializarComponentes = () => {
    if (typeof verificarSesionMenu === 'function') verificarSesionMenu();
    if (typeof actualizarContadorCarrito === 'function') actualizarContadorCarrito();
};

document.addEventListener('DOMContentLoaded', inicializarComponentes);