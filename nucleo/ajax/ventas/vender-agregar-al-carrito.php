<?php require_once('../../iniciar_app.php');
if (empty($_POST['producto_id']) || empty($_POST['cntd'])) {
    $error = "Complete los campos requeridos";
}
$producto = ObtenerProducto($_POST['producto_id']);

$_SESSION['venta']['carrito'] = ($_SESSION['venta']['carrito']) ? $_SESSION['venta']['carrito'] : [];

$LoteColumna = array_column($producto['stocks'], 'lote');
$_LoteColumna = array_column($_SESSION['venta']['carrito'], 'lote');
$_idColumna = array_column($_SESSION['venta']['carrito'], 'id');

if ($producto) {
    if (!empty($_POST['lote'])) {
        $id = array_search($_POST['lote'], $_LoteColumna);
        $loteID = array_search($_POST['lote'], $LoteColumna);
        if (!in_array($_POST['lote'], $LoteColumna)) {
            $error = "Lote de productos no existe";
        } else if (($_POST['cntd'] + $_SESSION['venta']['carrito'][$id]['cntd']) > $producto['stocks'][$loteID]['cntd']) {
            $error = "Lote de productos sin stock suficiente";
        }
    } else {
        $id = array_search($producto['id'], $_idColumna);
        if (($_POST['cntd'] + $_SESSION['venta']['carrito'][$id]['cntd']) > $producto['cntd']) {
            $error = "Productos sin stock suficiente";
        }
    }
}

if (empty($error)) {
    if (in_array($_POST['producto_id'], $_idColumna)) {
        $modificar = ['lote' => false, 'prod' => false];
        $cntd_lote = 0;
        $cntd = 0;
        foreach ($_SESSION['venta']['carrito'] as $clave => $carrito) {
            if ($carrito['id'] == $producto['id']) {
                if (!empty($carrito['lote'])) {
                    if (!empty($_POST['lote']) && ($_POST['lote'] == $carrito['lote'])) {
                        $cntd_lote += ($carrito['cntd'] + $_POST['cntd']);
                        $modificar['lote'] = true;
                        $_SESSION['venta']['carrito'][$clave]['cntd'] += $_POST['cntd'];
                    } else {
                        $cntd_lote += $carrito['cntd'];
                    }
                } else if (empty($carrito['lote'])) {
                    $cntd = ($carrito['cntd'] + $_POST['cntd']);
                    $modificar['prod'] = true;
                    $modificar['prodID'] = $clave;
                }
            }
        }

        if ($modificar['prod'] == true) {
            $cntd = (($cntd_lote + $cntd) >= $producto['cntd']) ? ((($cntd_lote == $producto['cntd']) || ($producto['cntd'] - $cntd_lote) <= 0) ? 'quitar' : $producto['cntd'] - $cntd_lote) : $cntd;
            if ($cntd == 'quitar') {
                $carrito = [];
                foreach ($_SESSION['venta']['carrito'] as $clave => $valor) {
                    if ($modificar['prodID'] != $clave) {
                        $carrito[] = $valor;
                    }
                }
                $_SESSION['venta']['carrito'] = $carrito;
            } else {
                $_SESSION['venta']['carrito'][$modificar['prodID']]['cntd'] = $cntd;
            }
        }

        if (($modificar['lote'] == false && !empty($_POST['lote'])) || ($modificar['prod'] == false && empty($_POST['lote']))) {
            if (!empty($_POST['lote'])) {
                $cntd = $_POST['cntd'];
                $lote = Obtener(T_STOCK, $producto['id'], 'producto_id', $_POST['lote'], 'lote');
            } else {
                $cntd = (($cntd_lote + $_POST['cntd']) >= $producto['cntd']) ? ((($cntd_lote == $producto['cntd']) || ($producto['cntd'] - $cntd_lote) <= 0) ? 'quitar' : $producto['cntd'] - $cntd_lote) : $_POST['cntd'];
                $lote = ['f_emision'=>'','f_vencimiento'=>''];
            }
            $_SESSION['venta']['carrito'][] = [
                'id' => $producto['id'],
                'codigo' => $producto['codigo'],
                'nombre' => $producto['nombre'],
                'precio_venta' => $producto['precio_venta'],
                'precio_compra' => $producto['precio_compra'],
                'cntd' => $cntd,
                'lote' => $_POST['lote'],
                'f_emision' => $lote['f_emision'],
                'f_vencimiento' => $lote['f_vencimiento']
            ];
        }
    } else {
        if (!empty($_POST['lote'])) {
            $lote = Obtener(T_STOCK, $producto['id'], 'producto_id', $_POST['lote'], 'lote');
        } else {
            $lote = ['f_emision'=>'','f_vencimiento'=>''];
        }
        $_SESSION['venta']['carrito'][] = [
            'id' => $producto['id'],
            'codigo' => $producto['codigo'],
            'nombre' => $producto['nombre'],
            'precio_venta' => $producto['precio_venta'],
            'precio_compra' => $producto['precio_compra'],
            'cntd' => $_POST['cntd'],
            'lote' => $_POST['lote'],
            'f_emision' => $lote['f_emision'],
            'f_vencimiento' => $lote['f_vencimiento']
        ];
    }


    $dato = array('estado' => 200, 'mensaje' => "Producto agregado correctamente");
} else {
    $dato = array('estado' => 400, 'mensaje' => $error);
}
echo json_encode($dato);
exit();
