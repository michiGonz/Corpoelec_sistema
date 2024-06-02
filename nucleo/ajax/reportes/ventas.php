<?php require_once('../../iniciar_app.php');

$metodo_pago = ($_POST['metodo_pago'] != '') ? "AND venta.metodo_pago = '{$_POST['metodo_pago']}'" : "";
$usuario = ($_POST['usuario_id'] != '') ? "AND venta.usuario_id = '{$_POST['usuario_id']}'" : "";
$producto = ($_POST['producto_id'] != '') ? "AND producto.id = '{$_POST['producto_id']}'" : "";
$categoria = ($_POST['categoria_id'] != '') ? "AND categoria.id = '{$_POST['categoria_id']}'" : "";
$sub_categoria = ($_POST['sub_categoria_id'] != '') ? "AND sub_categoria.id = '{$_POST['sub_categoria_id']}'" : "";
$marca = ($_POST['marca'] != '') ? "AND marca.id = '{$_POST['marca']}'" : "";

$consulta = "SELECT venta.* FROM " . T_VENTA . " venta

JOIN " . T_VENTA_DET . " venta_det ON venta.id = venta_det.venta_id

JOIN " . T_PRODUCTO . " producto ON venta_det.producto_id = producto.id

JOIN " . T_CATEGORIA . " categoria ON producto.categoria_id = categoria.id

JOIN " . T_SUB_CATEGORIA . " sub_categoria ON producto.sub_categoria_id = sub_categoria.id

JOIN " . T_marca . " marca ON producto.marca = marca.id

WHERE venta.fecha BETWEEN '{$_POST['f_inicio']}' AND '{$_POST['f_final']}' $usuario $metodo_pago $producto $categoria $sub_categoria $marca GROUP BY venta.id";

$consulta = mysqli_query($lfmc['sql'], $consulta);
while ($dato = mysqli_fetch_assoc($consulta)) {
    $lfmc['ventas'][] = $dato;
}
$html = CargarPagina('reportes/ventas', true);

$dato = array('estado' => 200, 'mensaje' => "Reporte realizado correctamente", 'html' => ((!empty($html))?$html:'<div class="text-center"><h2>Sin datos para mostrar</h2></div>'));
echo json_encode($dato);
exit();
