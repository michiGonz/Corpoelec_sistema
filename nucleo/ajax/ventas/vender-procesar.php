<?php require_once('../../iniciar_app.php');

if (count($_SESSION['venta']['carrito']) <= 0) {
    $error = "Debe seleccionar por lo menos un producto";
}

foreach ($_SESSION['venta']['carrito'] as $clave => $carrito) {
    $producto = ObtenerProducto($carrito['id']);
    if (!empty($carrito['lote'])) {
        foreach ($producto['stocks'] as $stock) {
            if ($stock['lote'] == $carrito['lote']) {
                if ($stock['cntd'] < $carrito['cntd']) {
                    $error = "Lote \"{$carrito['lote']}\" del producto \"{$producto['nombre']}\" sin stock suficiente, stock disponible: \"{$stock['cntd']}\"";
                }
            }
        }
    } else {
        if ($carrito['cntd'] > $producto['cntd']) {
            $error = "Producto \"{$producto['nombre']}\" sin stock suficiente, stock disponible: \"{$producto['cntd']}\"";
        }
    }
}
if (empty($error)) {
    if ($id_venta = (Registrar(T_VENTA, [
        'usuario_id' => $_SESSION['usuario']['id'],
        'metodo_pago' => $_POST['metodo_pago'],
        'total' => $_POST['total'],
        'fecha' => $_POST['fecha']
    ], true))) {
        foreach ($_SESSION['venta']['carrito'] as $clave => $carrito) {
            Registrar(T_VENTA_DET, [
                'venta_id' => $id_venta,
                'producto_id' => $carrito['id'],
                'precio_venta' => $carrito['precio_venta'],
                'precio_compra' => $carrito['precio_compra'],
                'cntd' => $carrito['cntd'],
                'lote' => $carrito['lote'],
                'f_emision' => $carrito['f_emision'],
                'f_vencimiento' => $carrito['f_vencimiento']
            ]);
            $stocks = ObtenerTodos(T_STOCK, $carrito['id'], 'producto_id');
            usort($stocks, function ($a, $b) {
                return strtotime($b['f_vencimiento']) <= strtotime($a['f_vencimiento']);
            });
            $producto_id =  $carrito['id'];
            $lote = $carrito['lote'];
            $posicion = array_reduce(array_keys($stocks), function ($llevar, $clave) use ($producto_id, $lote, $stocks) {
                if ($stocks[$clave]['producto_id'] == $producto_id && $stocks[$clave]['lote'] == $lote) {
                    return $clave;
                }
                return $llevar;
            });
            if ($posicion !== null) {
                $stocks[$posicion]['cntd'] -= $carrito['cntd'];
                Actualizar(T_STOCK, $stocks[$posicion]['id'], [
                    'cntd' => ($stocks[$posicion]['cntd'] - $carrito['cntd']),
                ]);
            } else {
                $sobrante = 0;
                foreach ($stocks as $clave => $stock) {
                    if ($stock['$producto_id'] == $carrito['id']) {
                        $carrito['cntd'] = ($sobrante != 0) ? $sobrante : $carrito['cntd'];
                        if (($stock['cntd'] - $carrito['cntd']) < 0) {
                            $sobrante = ($carrito['cntd'] - $stock['cntd']);
                            $restar = $stock['cntd'];
                            Eliminar(T_STOCK, $stock['id']);
                        } else {
                            if (($stock['cntd'] - $carrito['cntd']) == 0) {
                                echo "<br>quitar stock";
                                Eliminar(T_STOCK, $stock['id']);
                            } else {
                                Actualizar(T_STOCK, $stock['id'], [
                                    'cntd' => ($stock['cntd'] - $carrito['cntd']),
                                ]);
                            }
                            $sobrante = 0;
                        }
                        if ($sobrante == 0) {
                            break;
                        }
                    }
                }
            }
        }
        $_SESSION['venta'] = NULL;
        unset($_SESSION['venta']);
        $dato = array('estado' => 200, 'mensaje' => 'Venta realizada correctamente');
    } else {
        $dato = array('estado' => 400, 'mensaje' => 'Error al guardar venta');
    }
} else {
    $dato = array('estado' => 400, 'mensaje' => $error);
}

echo json_encode($dato);
exit();
