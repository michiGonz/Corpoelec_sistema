<?php require_once('../../iniciar_app.php');
if (empty($_POST['id']) && $_POST['id']!=0) {
    $error = "Complete los campos requeridos";
}
if (empty($error)) {
    if ($_POST["id"] == 'todo') {
        $_SESSION['abastecimiento']['carrito']=[];
    } else {
        foreach ($_SESSION['abastecimiento']['carrito'] as $clave => $producto) {
            if ($_POST['id'] != $clave) {
                $carrito[] = $producto;
            }
        }
        $_SESSION['abastecimiento']['carrito'] = $carrito;
    }
    $dato = array('estado' => 200, 'mensaje' => "Producto removido correctamente".$_POST['id']);
} else {
    $dato = array('estado' => 400, 'mensaje' => $error);
}
echo json_encode($dato);
exit();
