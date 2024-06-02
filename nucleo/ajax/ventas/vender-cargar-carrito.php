<?php require_once('../../iniciar_app.php');

$dato = CargarPagina('ventas/vender-carrito',true);

echo json_encode($dato);
exit();
