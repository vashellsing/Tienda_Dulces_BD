<footer class="pie-pagina">
    <p>&copy; 2026 Sweet Dreams. Todos los derechos reservados.</p>
</footer>


<script src="js/componentes.js"></script>
<script src="js/main.js"></script>

<?php
// Verificamos si la variable existe para evitar el error "Undefined variable"
$vista_actual = isset($vista) ? $vista : 'inicio';

switch ($vista_actual) {
    case 'carrito':
        echo '<script src="js/carrito.js"></script>';
        break;
    case 'catalogo':
        echo '<script src="js/catalogo.js"></script>';
        break;
    case 'login':
        echo '<script src="js/autenticacion.js"></script>';
        break;
    case 'registro':
        echo '<script src="js/autenticacion.js"></script>' . "\n";
        echo '    <script src="js/registro.js"></script>';
        break;
    case 'producto':
        echo '<script src="js/producto.js"></script>';
        break;
}
?>

</body>

</html>