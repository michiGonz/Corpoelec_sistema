<?php require_once('../../iniciar_app.php');

if (Eliminar(T_ABASTECIMIENTO, $_GET['id'])) {
    foreach (ObtenerTodos(T_ABASTECIMIENTO_DET, $_GET['id'], 'abastecimiento_id') as $det) {
        $lotes = ObtenerTodos(T_STOCK, $det['producto_id'], 'producto_id');
        if (in_array($det['lote'], array_column($lotes, 'lote'))) {
            $lote = $lotes[array_search($det['lote'], array_column($lotes, 'lote'))];
            if ((($lote['cntd'] - $det['cntd']) <= 0) && (($det['cntd'] - $lote['cntd']) <= 0)) {
                Eliminar(T_STOCK, $lote['id']);
            } else {
                Actualizar(T_STOCK, $lote['id'], [
                    'cntd' => (($lote['cntd'] - $det['cntd']) <= 0) ? ($det['cntd'] - $lote['cntd']) : ($lote['cntd'] - $det['cntd'])
                ]);
            }
        }
    }
    Eliminar(T_ABASTECIMIENTO_DET, $_GET['id'], 'abastecimiento_id');
    $dato = array('estado' => 200, 'mensaje' => "Abastecimiento eliminado correctamente");
} else {
    $dato = array('estado' => 400, 'mensaje' => "Error al eliminar");
}
echo json_encode($dato);
exit();
