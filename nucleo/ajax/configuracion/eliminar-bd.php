<?php require_once('../../iniciar_app.php');
if (unlink('../../.'.$_GET['bd_ruta'])) {
    $dato = array('estado' => 200, 'mensaje' => "Respaldado eliminado con éxito");
} else {
    $dato = array('estado' => 400, 'mensaje' => "Error al eliminar");
}
echo json_encode($dato);
exit();
