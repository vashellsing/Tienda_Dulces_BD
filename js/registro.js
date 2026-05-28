document.addEventListener('DOMContentLoaded', () => {
    const formRegistro = document.getElementById('formulario-registro');
    const mensajeError = document.getElementById('mensaje-error-reg');

    if (formRegistro) {
        formRegistro.addEventListener('submit', (e) => {
            // Ya no hacemos e.preventDefault() aquí arriba.

            const contrasena = document.getElementById('contrasena-reg').value;

            // Validación de contraseña:
            const tieneLongitudMinima = contrasena.length >= 6;
            const tieneMayuscula = /[A-Z]/.test(contrasena);
            const tieneNumero = /[0-9]/.test(contrasena);
            const tieneEspecial = /[!@#$%^&*(),.?":{}|<>_\-\\/[\];'`~+=]/.test(contrasena);

            // Si la validación FALLA, entonces SÍ detenemos el envío a PHP
            if (!tieneLongitudMinima || (!tieneMayuscula && !tieneNumero && !tieneEspecial)) {
                e.preventDefault(); // Detiene el POST hacia el servidor
                mensajeError.textContent =
                    "La contraseña debe tener mínimo 6 caracteres y al menos una mayúscula, un número o un carácter especial.";
                mensajeError.style.display = 'block';
                return; // Salimos de la función
            }

            // Ocultamos el error por si estaba visible de un intento anterior
            mensajeError.style.display = 'none';

            // Si llegamos hasta aquí, la validación pasó. 
            // Como no ejecutamos e.preventDefault(), el navegador enviará 
            // automáticamente los datos por POST a tu PHP.
            // Eliminamos la lógica de localStorage porque ahora PHP usa MySQL.
        });
    }
});