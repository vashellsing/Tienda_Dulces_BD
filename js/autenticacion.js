/* =========================================================================
   autenticacion.js - Actualizado para PHP y MySQL
   ========================================================================= */

// ¡Hemos eliminado la función manejarLogin y el preventDefault!
// Ahora permitimos que el navegador envíe el formulario por POST 
// directamente a nuestro archivo login.php.

document.addEventListener('DOMContentLoaded', () => {
    // Solo mantenemos la actualización visual del menú superior 
    // (Cambiar "Login" por "Cerrar Sesión" si ya hay sesión)
    if (typeof verificarSesionMenu === 'function') {
        verificarSesionMenu();
    }
});