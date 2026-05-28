<?php
require_once 'includes/conexion.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$producto = null;
$relacionados = [];

if ($id > 0) {
  try {
    $sql = "SELECT * FROM productos WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
      $sql_rel = "SELECT * FROM productos WHERE categoria = :categoria AND id != :id LIMIT 3";
      $stmt_rel = $conn->prepare($sql_rel);
      $stmt_rel->execute([
        ':categoria' => $producto['categoria'],
        ':id' => $id
      ]);
      $relacionados = $stmt_rel->fetchAll(PDO::FETCH_ASSOC);
    }
  } catch (PDOException $e) {
  }
}
?>

<main class="contenedor detalle-page">
  <?php if ($producto): ?>
    <section class="detalle-producto-card">
      <div class="detalle-col-izq">
        <div class="detalle-imagen-caja">
          <img id="detalle-img" src="recursos/img/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" />
        </div>
      </div>

      <div class="detalle-col-der">
        <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
        <p class="producto-precio">$ <?php echo number_format($producto['precio'], 0, ',', '.'); ?></p>
        <div class="separador"></div>
        <h3>Descripción:</h3>
        <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>

        <div class="acciones-compra">
          <button class="btn btn-primario btn-grande btn-agregar-api"
            data-id="<?php echo $producto['id']; ?>"
            data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
            data-precio="<?php echo $producto['precio']; ?>"
            data-imagen="<?php echo htmlspecialchars($producto['imagen']); ?>">
            Agregar al carrito
          </button>
          <a href="index.php?vista=catalogo" class="btn-volver">← Volver al catálogo</a>
        </div>
      </div>
    </section>

    <?php if (count($relacionados) > 0): ?>
      <section class="relacionados">
        <h2 class="titulo-seccion">Productos relacionados</h2>
        <div class="cuadricula-productos">
          <?php foreach ($relacionados as $rel): ?>
            <div class="tarjeta-producto">
              <img src="recursos/img/<?php echo htmlspecialchars($rel['imagen']); ?>" alt="<?php echo htmlspecialchars($rel['nombre']); ?>">
              <h3><?php echo htmlspecialchars($rel['nombre']); ?></h3>
              <p class="precio">$ <?php echo number_format($rel['precio'], 0, ',', '.'); ?></p>

              <button class="btn btn-primario btn-agregar-api"
                data-id="<?php echo $rel['id']; ?>"
                data-nombre="<?php echo htmlspecialchars($rel['nombre']); ?>"
                data-precio="<?php echo $rel['precio']; ?>"
                data-imagen="<?php echo htmlspecialchars($rel['imagen']); ?>">
                Agregar al Carrito
              </button>
              <a href="index.php?vista=producto&id=<?php echo $rel['id']; ?>" class="btn-detalles">Ver detalles</a>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

  <?php else: ?>
    <div style="text-align: center; padding: 50px 20px;">
      <h2 class="titulo-seccion">Producto no encontrado</h2>
      <p>Lo sentimos, parece que este dulce ya no está disponible.</p>
      <a href="index.php?vista=catalogo" class="btn btn-primario" style="margin-top:20px;">Ver catálogo</a>
    </div>
  <?php endif; ?>
</main>