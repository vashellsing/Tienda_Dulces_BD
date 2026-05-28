<?php
// Importamos la conexión a la base de datos
require_once 'includes/conexion.php';

// Preparamos un arreglo vacío por si hay un error
$productos = [];

try {
  // Traemos todos los productos de la base de datos
  $sql = "SELECT * FROM productos";
  $stmt = $conn->query($sql);
  $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "<p style='color:red;'>Error al cargar los productos: " . $e->getMessage() . "</p>";
}
?>

<main class="contenedor">
  <h1 class="titulo-seccion">Nuestro Catálogo</h1>

  <section class="barra-filtros">
    <button class="btn btn-filtro activo" data-categoria="Todos">Todos</button>
    <button class="btn btn-filtro" data-categoria="Desayunos">Desayunos</button>
    <button class="btn btn-filtro" data-categoria="Chocolates">Chocolates</button>
    <button class="btn btn-filtro" data-categoria="Dulces">Dulces</button>
    <button class="btn btn-filtro" data-categoria="Flores">Flores</button>
    <button class="btn btn-filtro" data-categoria="Peluches">Peluches</button>
  </section>

  <section id="contenedor-catalogo" class="cuadricula-productos">
    <?php foreach ($productos as $producto): ?>

      <div class="tarjeta-producto" data-categoria="<?php echo htmlspecialchars($producto['categoria']); ?>">

        <img src="recursos/img/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">

        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>

        <p class="precio">$ <?php echo number_format($producto['precio'], 0, ',', '.'); ?></p>

        <button class="btn btn-primario btn-agregar"
          data-id="<?php echo $producto['id']; ?>"
          data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
          data-precio="<?php echo $producto['precio']; ?>"
          data-imagen="<?php echo htmlspecialchars($producto['imagen']); ?>">
          Agregar al Carrito
        </button>

        <a href="index.php?vista=producto&id=<?php echo $producto['id']; ?>" class="btn-detalles">Ver detalles</a>

      </div>

    <?php endforeach; ?>
  </section>
</main>