<?php require_once('../../iniciar_app.php');
$usuario = Obtener(T_USUARIO, Strtoupper($_POST['id']));
if (empty($_POST['nombre']) || empty($_POST['usuario'])) {
    $error = 'complete los campos requeridos';
}
if (Existe(T_USUARIO, Strtoupper($_POST['usuario']), 'usuario') && Strtoupper($_POST['usuario']) != $usuario['usuario']) {
    $error = 'Nombre de usuario ya existe';
}
if (empty($error)) {
    $datos = array(
        'usuario' => Strtoupper($_POST['usuario']),
        'nombre' => $_POST['nombre'],
        'tipo' => $_POST['tipo']
    );
    if (!empty($_POST['clave'])) {
        $datos['clave'] = password_hash($_POST['clave'], PASSWORD_DEFAULT);
    }
    if (Actualizar(T_USUARIO, $usuario['id'], $datos)) {
        if ($_SESSION['usuario']['id'] == $usuario['id']) {
            $_SESSION['usuario'] = Obtener(T_USUARIO, $usuario['id']);
        }
        $dato = array('estado' => 200, 'mensaje' => 'Usuario actualizado correctamente');
    } else {
        $dato = array('estado' => 400, 'mensaje' => 'Error al guardar');
    }
} else {
    $dato = array('estado' => 400, 'mensaje' => $error);
}

echo json_encode($dato);
exit();