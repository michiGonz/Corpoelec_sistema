<?php require_once('../../iniciar_app.php');
if (empty($_POST['nombre'])) {
    $error = 'complete los campos requeridos';
}
if (empty($error)) {
    if (Actualizar(T_marca, $_POST['id'], ['nombre' => $_POST['nombre']])) {
        $dato = array('estado' => 200, 'mensaje' => 'marca actualizada correctamente');
    } else {
        $dato = array('estado' => 400, 'mensaje' => 'Error al guardar');
    }
} else {
    $dato = array('estado' => 400, 'mensaje' => $error);
}

echo json_encode($dato);
exit();