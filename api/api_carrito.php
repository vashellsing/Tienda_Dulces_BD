<?php
session_start();
// Ojo a los dos puntos: retrocedemos una carpeta para encontrar includes/
require_once '../includes/conexion.php';

header('Content-Type: application/json');
$datos = json_decode(file_get_contents('php://input'), true);

if (!isset($datos['accion'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'Acción no especificada.']);
    exit;
}

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'NoAutenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$accion = $datos['accion'];

try {
    // 1. AGREGAR AL CARRITO
    if ($accion == 'agregar') {
        $producto_id = $datos['producto_id'];
        $sql = "SELECT id, cantidad FROM carrito WHERE usuario_id = :usuario_id AND producto_id = :producto_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id, ':producto_id' => $producto_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            $sql_update = "UPDATE carrito SET cantidad = cantidad + 1 WHERE id = :id";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->execute([':id' => $item['id']]);
        } else {
            $sql_insert = "INSERT INTO carrito (usuario_id, producto_id, cantidad) VALUES (:usuario_id, :producto_id, 1)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->execute([':usuario_id' => $usuario_id, ':producto_id' => $producto_id]);
        }
        echo json_encode(['exito' => true, 'mensaje' => 'Producto agregado a tu carrito.']);
    }

    // 2. OBTENER EL CARRITO (Para dibujarlo en pantalla)
    elseif ($accion == 'obtener') {
        $sql = "SELECT c.id as carrito_id, c.cantidad, p.id, p.nombre, p.precio, p.imagen 
                FROM carrito c 
                JOIN productos p ON c.producto_id = p.id 
                WHERE c.usuario_id = :usuario_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['exito' => true, 'carrito' => $productos]);
    }

    // 3. ACTUALIZAR CANTIDAD (+ ó -)
    elseif ($accion == 'actualizar') {
        $carrito_id = $datos['carrito_id'];
        $cantidad = $datos['cantidad'];

        if ($cantidad > 0) {
            $sql = "UPDATE carrito SET cantidad = :cantidad WHERE id = :id AND usuario_id = :usuario_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':cantidad' => $cantidad, ':id' => $carrito_id, ':usuario_id' => $usuario_id]);
        } else {
            // Si la cantidad llega a 0, lo borramos
            $sql = "DELETE FROM carrito WHERE id = :id AND usuario_id = :usuario_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $carrito_id, ':usuario_id' => $usuario_id]);
        }
        echo json_encode(['exito' => true]);
    }

    // 4. ELIMINAR UN PRODUCTO DEL TODO
    elseif ($accion == 'eliminar') {
        $carrito_id = $datos['carrito_id'];
        $sql = "DELETE FROM carrito WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $carrito_id, ':usuario_id' => $usuario_id]);
        echo json_encode(['exito' => true]);
    }

    // 5. PROCESAR LA COMPRA (Checkout)
    elseif ($accion == 'comprar') {
        // Obtenemos todo lo que hay en el carrito
        $sql = "SELECT c.producto_id, c.cantidad, p.precio 
                FROM carrito c JOIN productos p ON c.producto_id = p.id 
                WHERE c.usuario_id = :usuario_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($items) === 0) {
            echo json_encode(['exito' => false, 'mensaje' => 'El carrito está vacío.']);
            exit;
        }

        // Calculamos el total
        $total = 0;
        foreach ($items as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        // Creamos la factura general (Tabla 'pedidos')
        $sql_pedido = "INSERT INTO pedidos (usuario_id, total) VALUES (:usuario_id, :total)";
        $stmt_pedido = $conn->prepare($sql_pedido);
        $stmt_pedido->execute([':usuario_id' => $usuario_id, ':total' => $total]);

        // Recuperamos el ID de la factura que se acaba de crear
        $pedido_id = $conn->lastInsertId();

        // Guardamos el detalle de los productos (Tabla 'detalles_pedido')
        $sql_detalle = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (:pedido_id, :producto_id, :cantidad, :precio_unitario)";
        $stmt_detalle = $conn->prepare($sql_detalle);

        foreach ($items as $item) {
            $stmt_detalle->execute([
                ':pedido_id' => $pedido_id,
                ':producto_id' => $item['producto_id'],
                ':cantidad' => $item['cantidad'],
                ':precio_unitario' => $item['precio']
            ]);
        }

        // Vaciamos el carrito porque ya se compró
        $sql_clear = "DELETE FROM carrito WHERE usuario_id = :usuario_id";
        $stmt_clear = $conn->prepare($sql_clear);
        $stmt_clear->execute([':usuario_id' => $usuario_id]);

        echo json_encode(['exito' => true, 'mensaje' => '¡Compra realizada con éxito! Tus dulces están en camino.']);
    }
} catch (PDOException $e) {
    echo json_encode(['exito' => false, 'mensaje' => 'Error: ' . $e->getMessage()]);
}
