<?php
// Iniciamos la sesión para poder guardar quién se logueó
// session_start();
// Importamos la conexión a la base de datos
require_once 'includes/conexion.php';

$mensaje = '';
$exito = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Recibimos los datos gracias al atributo 'name' de los inputs
  $correo = trim($_POST['correo']);
  $contrasena = $_POST['contrasena'];

  try {
    // Buscamos al usuario por su correo
    $sql = "SELECT id, nombre, contrasena FROM usuarios WHERE correo = :correo";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':correo' => $correo]);

    // Obtenemos los datos del usuario
    $usuarioDB = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificamos si existe el usuario y si la contraseña coincide con el hash guardado
    if ($usuarioDB && password_verify($contrasena, $usuarioDB['contrasena'])) {

      // ¡Login exitoso! Guardamos los datos en la sesión de PHP
      $_SESSION['usuario_id'] = $usuarioDB['id'];
      $_SESSION['usuario_nombre'] = $usuarioDB['nombre'];

      $mensaje = "¡Bienvenido de nuevo, " . $usuarioDB['nombre'] . "!";
      $exito = true;
    } else {
      $mensaje = "Correo o contraseña incorrectos.";
    }
  } catch (PDOException $e) {
    $mensaje = "Error al iniciar sesión: " . $e->getMessage();
  }
}
?>

<main class="contenedor contenedor-login">
  <div class="tarjeta-login">
    <h2 class="titulo-seccion">Iniciar Sesión</h2>
    <p class="subtitulo-login">Ingresa para poder realizar tus compras.</p>

    <form id="formulario-login" action="" method="POST">
      <div class="grupo-input">
        <label for="correo">Correo Electrónico</label>
        <input
          type="email"
          id="correo"
          name="correo"
          placeholder="ejemplo@correo.com"
          required />
      </div>

      <div class="grupo-input">
        <label for="contrasena">Contraseña</label>
        <input
          type="password"
          id="contrasena"
          name="contrasena"
          placeholder="******"
          required />
      </div>

      <p id="mensaje-error" class="mensaje-error" style="display: none;"></p>

      <button type="submit" class="btn btn-primario btn-bloque">
        Ingresar
      </button>
    </form>

    <p style="text-align: center; margin-top: 20px">
      ¿No tienes cuenta?
      <a
        href="index.php?vista=registro"
        style="color: var(--color-primario); font-weight: bold">Regístrate aquí</a>
    </p>
  </div>
</main>

<?php if ($mensaje != ''): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      mostrarModal("<?php echo $mensaje; ?>", () => {
        <?php if ($exito): ?>
          // Guardamos en localStorage SOLO para que tu menú superior (main.js)
          // sepa que debe ocultar "Login" y mostrar "Cerrar Sesión"
          localStorage.setItem('usuarioLogueado', 'true');
          localStorage.setItem('nombreUsuario', '<?php echo $usuarioDB['nombre']; ?>');

          // Redirigimos al inicio
          window.location.href = 'index.php?vista=inicio';
        <?php endif; ?>
      });
    });
  </script>
<?php endif; ?>