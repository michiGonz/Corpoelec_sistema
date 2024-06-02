<?php require_once('../../iniciar_app.php');
if (empty($_POST['nombre']) || empty($_POST['departamento']) || empty($_POST['modelo']) || empty($_POST['serial']) || empty($_POST['marca_id']) || empty($_POST['trabajador']) || empty($_POST['fecha']) ) {
    $error = 'complete los campos requeridos';
}
if (empty($error)) {
    if (Actualizar(T_PRODUCTO, $_POST['id'], [
        'nombre' => $_POST['nombre'],
        'departamento' => $_POST['departamento'],
        'modelo' => $_POST['modelo'],
        'serial' => $_POST['serial'],
        'marca_id' => $_POST['marca_id'],
        'trabajador' => $_POST['trabajador'],
        'fecha' => $_POST['fecha'],
    ])) {
        $dato = array('estado' => 200, 'mensaje' => 'Producto actualizado correctamente');
    } else {
        $dato = array('estado' => 400, 'mensaje' => 'Error al guardar');
    }
} else {
    $dato = array('estado' => 400, 'mensaje' => $error);
}

echo json_encode($dato);
exit();
