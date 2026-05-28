<?php
// Importamos la conexión
require_once 'includes/conexion.php';

$mensaje = '';
$exito = false; // Nos dirá si redirigir al login o no

// Comprobamos si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nombre = trim($_POST['nombre']);
  $correo = trim($_POST['correo']);
  $contrasena = $_POST['contrasena'];

  // Encriptamos la contraseña
  $hash = password_hash($contrasena, PASSWORD_DEFAULT);

  try {
    $sql = "INSERT INTO usuarios (nombre, correo, contrasena) VALUES (:nombre, :correo, :contrasena)";
    $stmt = $conn->prepare($sql);

    $stmt->execute([
      ':nombre' => $nombre,
      ':correo' => $correo,
      ':contrasena' => $hash
    ]);

    $mensaje = "¡Cuenta creada con éxito! Por favor inicia sesión.";
    $exito = true; // Todo salió perfecto

  } catch (PDOException $e) {
    if ($e->getCode() == 23000) {
      $mensaje = "Este correo ya está registrado. Intenta con otro.";
    } else {
      $mensaje = "Error al registrar: " . $e->getMessage();
    }
  }
}
?>

<main class="contenedor contenedor-login">
  <div class="tarjeta-login">
    <h2 class="titulo-seccion">Crear Cuenta</h2>
    <p class="subtitulo-login">
      Únete para comprar tus regalos más rápido.
    </p>

    <form id="formulario-registro" action="" method="POST">
      <div class="grupo-input">
        <label for="nombre-reg">Nombre Completo</label>
        <input
          type="text"
          id="nombre-reg"
          name="nombre"
          placeholder="Juan Pérez"
          required />
      </div>

      <div class="grupo-input">
        <label for="correo-reg">Correo Electrónico</label>
        <input
          type="email"
          id="correo-reg"
          name="correo"
          placeholder="ejemplo@correo.com"
          required />
      </div>

      <div class="grupo-input">
        <label for="contrasena-reg">Contraseña</label>
        <input
          type="password"
          id="contrasena-reg"
          name="contrasena"
          placeholder="Mínimo 6 caracteres..."
          minlength="6"
          required />
        <small style="display:block; margin-top:8px; color:#777;">
          Debe tener mínimo 6 caracteres y al menos una mayúscula, un número o un símbolo.
        </small>
      </div>

      <p id="mensaje-error-reg" class="mensaje-error" style="display: none;"></p>

      <button type="submit" class="btn btn-primario btn-bloque">
        Registrarme
      </button>
    </form>

    <p style="text-align: center; margin-top: 20px">
      ¿Ya tienes cuenta?
      <a
        href="index.php?vista=login"
        style="color: var(--color-primario); font-weight: bold">Inicia sesión aquí</a>
    </p>
  </div>
</main>

<?php if ($mensaje != ''): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Invocamos la función mostrarModal que está en tu main.js
      mostrarModal("<?php echo $mensaje; ?>", () => {
        // Si fue exitoso, redirigimos al login al cerrar el modal
        <?php if ($exito): ?>
          window.location.href = 'index.php?vista=login';
        <?php endif; ?>
      });
    });
  </script>
<?php endif; ?>