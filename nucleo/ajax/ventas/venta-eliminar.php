<?php require_once('../../iniciar_app.php');

if (Eliminar(T_VENTA, $_GET['id'])) {
    foreach (ObtenerTodos(T_VENTA_DET, $_GET['id'], 'venta_id') as $det) {
        $lotes = ObtenerTodos(T_STOCK, $det['producto_id'], 'producto_id');
        if (in_array($det['lote'], array_column($lotes, 'lote'))) {
            $lote = $lotes[array_search($det['lote'], array_column($lotes, 'lote'))];
            Actualizar(T_STOCK, $lote['id'], [
                'cntd' => $lote['cntd'] + $det['cntd']
            ]);
        } else {
            Registrar(T_STOCK, [
                'producto_id' => $det['producto_id'],
                'cntd' => $det['cntd'],
                'lote' => $det['lote'],
                'f_emision' => $det['f_emision'],
                'f_vencimiento' => $det['f_vencimiento']
            ]);
        }
    }
    Eliminar(T_VENTA_DET, $_GET['id'], 'venta_id');
    $dato = array('estado' => 200, 'mensaje' => "Venta eliminada correctamente");
} else {
    $dato = array('estado' => 400, 'mensaje' => "Error al eliminar");
}
echo json_encode($dato);
exit();
