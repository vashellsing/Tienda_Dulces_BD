<?php
// Abrimos la memoria de sesiones si no estaba abierta
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Ya no bloqueamos aquí con redirecciones. Dejamos pasar a todos.
?>

<main class="contenedor">
    <h1 class="titulo-seccion">Tu Carrito de Compras</h1>

    <div class="diseno-carrito">
        <section class="lista-carrito" id="lista-carrito">
            <p style="text-align: center; font-size: 18px; padding: 40px 0">
                Tu carrito está vacío en este momento.
            </p>
        </section>

        <aside class="resumen-carrito">
            <h3 style="margin-bottom: 20px; font-size: 24px">
                Resumen del Pedido
            </h3>

            <div class="fila-resumen">
                <span style="font-size: 18px">Total a pagar:</span>
                <span
                    id="total-carrito"
                    style="
                    font-size: 22px;
                    font-weight: bold;
                    color: var(--color-acento);
                ">$ 0</span>
            </div>

            <button
                id="btn-comprar"
                class="btn btn-primario btn-bloque"
                style="margin-top: 25px">
                Finalizar Compra
            </button>

            <p
                id="mensaje-auth"
                class="mensaje-error"
                style="margin-top: 15px"></p>
        </aside>
    </div>
</main>