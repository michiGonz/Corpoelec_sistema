<?php require_once('../../iniciar_app.php');

$dato = CargarPagina('abastecimientos/abastecer-carrito',true);

echo json_encode($dato);
exit();
