<?php require_once('../../iniciar_app.php');

if (count($_SESSION['abastecimiento']['carrito']) <= 0) {
    $error = "Debe seleccionar por lo menos un producto";
}
if (empty($error)) {
    if ($id_venta = (Registrar(T_ABASTECIMIENTO, [
        'usuario_id' => $_SESSION['usuario']['id'],
        'total' => $_POST['total'],
        'fecha' => $_POST['fecha']
    ], true))) {
        foreach ($_SESSION['abastecimiento']['carrito'] as $clave => $carrito) {
            Registrar(T_ABASTECIMIENTO_DET, [
                'abastecimiento_id' => $id_venta,
                'producto_id' => $carrito['id'],
                'precio_venta' => $carrito['precio_venta'],
                'precio_compra' => $carrito['precio_compra'],
                'cntd' => $carrito['cntd'],
                'lote' => $carrito['lote'],
                'f_emision' => $carrito['f_emision'],
                'f_vencimiento' => $carrito['f_vencimiento']
            ]);

            $stocks = ObtenerTodos(T_STOCK, $carrito['id'], 'producto_id');
            $stocksLote = array_column($stocks, 'lote');
            if (in_array($carrito['lote'], $stocksLote)) {
                $id = array_search($carrito['lote'], $stocksLote);
                if ($id !== false) {
                    $stock = $stocksLote[$id];
                    Actualizar(T_STOCK, $stock['id'], [
                        'cntd' => ($stock['cntd'] + $carrito['cntd']),
                    ]);
                }
            } else {
                Registrar(T_STOCK, [
                    'producto_id' => $carrito['id'],
                    'cntd' => $carrito['cntd'],
                    'lote' => strtoupper($carrito['lote']),
                    'f_vencimiento' => $carrito['f_vencimiento'],
                    'f_emision' => $carrito['f_emision']
                ]);
            }
        }
        $_SESSION['abastecimiento'] = NULL;
        unset($_SESSION['abastecimiento']);
        $dato = array('estado' => 200, 'mensaje' => 'Abastecimiento realizado correctamente');
    } else {
        $dato = array('estado' => 400, 'mensaje' => 'Error al guardar abastecimiento');
    }
} else {
    $dato = array('estado' => 400, 'mensaje' => $error);
}

echo json_encode($dato);
exit();
