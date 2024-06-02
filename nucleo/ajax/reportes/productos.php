<?php require_once('../../iniciar_app.php');

$producto = ($_POST['producto_id'] != '') ? "AND producto.id = '{$_POST['producto_id']}'" : "";
$categoria = ($_POST['categoria_id'] != '') ? "AND categoria.id = '{$_POST['categoria_id']}'" : "";
$sub_categoria = ($_POST['sub_categoria_id'] != '') ? "AND sub_categoria.id = '{$_POST['sub_categoria_id']}'" : "";
$marca = ($_POST['marca'] != '') ? "AND marca.id = '{$_POST['marca']}'" : "";
if($_POST['tipo_movimiento']=='abastecimiento' || $_POST['tipo_movimiento']=='todo'){
    $consulta = "SELECT abastecimiento_det.*, abastecimiento.fecha, abastecimiento.id as movimiento_id  FROM " . T_ABASTECIMIENTO_DET . " abastecimiento_det
    JOIN " . T_ABASTECIMIENTO . " abastecimiento ON abastecimiento_det.abastecimiento_id = abastecimiento.id
    JOIN " . T_PRODUCTO . " producto ON abastecimiento_det.producto_id = producto.id
    
    JOIN " . T_marca . " marca ON producto.marca = marca.id
    WHERE abastecimiento.fecha BETWEEN '{$_POST['f_inicio']}' AND '{$_POST['f_final']}' $producto $categoria $sub_categoria $marca GROUP BY abastecimiento_det.id";
    $consulta = mysqli_query($lfmc['sql'], $consulta);
    while ($dato = mysqli_fetch_assoc($consulta)) {
        $dato['tipo'] = 'abastecimiento';
        $lfmc['productos'][] = $dato;
    }
}
if($_POST['tipo_movimiento']=='venta' || $_POST['tipo_movimiento']=='todo'){
    $consulta = "SELECT venta_det.*, venta.fecha, venta.id as movimiento_id FROM " . T_VENTA_DET . " venta_det
    JOIN " . T_VENTA . " venta ON venta_det.venta_id = venta.id
    JOIN " . T_PRODUCTO . " producto ON venta_det.producto_id = producto.id
    JOIN " . T_CATEGORIA . " categoria ON producto.categoria_id = categoria.id
    JOIN " . T_SUB_CATEGORIA . " sub_categoria ON producto.sub_categoria_id = sub_categoria.id
    JOIN " . T_marca . " marca ON producto.marca = marca.id
    WHERE venta.fecha BETWEEN '{$_POST['f_inicio']}' AND '{$_POST['f_final']}' $producto $categoria $sub_categoria $marca GROUP BY venta_det.id";
    $consulta = mysqli_query($lfmc['sql'], $consulta);
    while ($dato = mysqli_fetch_assoc($consulta)) {
        $dato['tipo'] = 'venta';
        $lfmc['productos'][] = $dato;
    }
}

$html = CargarPagina('reportes/productos', true);

$dato = array('estado' => 200, 'mensaje' => "Reporte realizado correctamente", 'html' => ((!empty($html))?$html:'<div class="text-center"><h2>Sin datos para mostrar</h2></div>'));
echo json_encode($dato);
exit();
