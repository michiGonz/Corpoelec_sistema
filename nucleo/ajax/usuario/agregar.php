<?php require_once('../../iniciar_app.php');
if (empty($_POST['nombre']) || empty($_POST['usuario'])) {
    $error = 'complete los campos requeridos';
}
if (Existe(T_USUARIO, Strtoupper($_POST['usuario']), 'usuario')) {
    $error = 'Nombre de usuario ya existe';
}
if (empty($error)) {
    if (empty($_POST['clave'])) {
        $_POST['clave'] = "corpoelec";
    }
    $datos = array(
        'usuario' => Strtoupper($_POST['usuario']),
        'nombre' => $_POST['nombre'],
        'tipo' => $_POST['tipo'],
        'clave' => (!empty($_POST['clave'])) ? password_hash($_POST['clave'], PASSWORD_DEFAULT) : password_hash('corpoelec', PASSWORD_DEFAULT),
    );
    if (Registrar(T_USUARIO,$datos)) {
        $dato = array('estado' => 200, 'mensaje' => 'Usuario agregado correctamente');
    } else {
        $dato = array('estado' => 400, 'mensaje' => 'Error al guardar');
    }
} else {
    $dato = array('estado' => 400, 'mensaje' => $error);
}

echo json_encode($dato);
exit();