<?php require_once('../../iniciar_app.php');
if (empty($_POST['producto_id']) || empty($_POST['cntd']) || empty($_POST['lote']) || empty($_POST['f_emision']) || empty($_POST['f_vencimiento'])) {
    $error = "Complete los campos requeridos";
}
$producto = ObtenerProducto($_POST['producto_id']);

$_SESSION['abastecimiento']['carrito'] = ($_SESSION['abastecimiento']['carrito']) ? $_SESSION['abastecimiento']['carrito'] : [];

if (empty($error)) {
    if (in_array($_POST['producto_id'], array_column($_SESSION['abastecimiento']['carrito'], 'id'))) {
        $new = true;
        foreach ($_SESSION['abastecimiento']['carrito'] as $clave => $carrito) {
            if ($carrito['id'] == $producto['id']) {
                if (!empty($_POST['lote']) && ($_POST['lote'] == $carrito['lote'])) {
                    $new = false;
                    $_SESSION['abastecimiento']['carrito'][$clave]['cntd'] += $_POST['cntd'];
                }
            }
        }
        if ($new == true) {
            $_SESSION['abastecimiento']['carrito'][] = [
                'id' => $producto['id'],
                'codigo' => $producto['codigo'],
                'nombre' => $producto['nombre'],
                'precio_venta' => $producto['precio_venta'],
                'precio_compra' => $producto['precio_compra'],
                'f_emision'=> $_POST['f_emision'],
                'f_vencimiento'=> $_POST['f_vencimiento'],
                'cntd' => $_POST['cntd'],
                'lote' => $_POST['lote']
            ];
        }
    } else {
        $_SESSION['abastecimiento']['carrito'][] = [
            'id' => $producto['id'],
            'codigo' => $producto['codigo'],
            'nombre' => $producto['nombre'],
            'precio_venta' => $producto['precio_venta'],
            'precio_compra' => $producto['precio_compra'],
            'f_emision'=> $_POST['f_emision'],
            'f_vencimiento'=> $_POST['f_vencimiento'],
            'cntd' => $_POST['cntd'],
            'lote' => $_POST['lote']
        ];
    }
    $dato = array('estado' => 200, 'mensaje' => "Producto agregado correctamente");
} else {
    $dato = array('estado' => 400, 'mensaje' => $error);
}
echo json_encode($dato);
exit();
