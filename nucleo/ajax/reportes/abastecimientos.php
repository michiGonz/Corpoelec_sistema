<?php require_once('../../iniciar_app.php');

$usuario = ($_POST['usuario_id'] != '') ? "AND abastecimiento.usuario_id = '{$_POST['usuario_id']}'" : "";
$producto = ($_POST['producto_id'] != '') ? "AND producto.id = '{$_POST['producto_id']}'" : "";
$categoria = ($_POST['categoria_id'] != '') ? "AND categoria.id = '{$_POST['categoria_id']}'" : "";
$sub_categoria = ($_POST['sub_categoria_id'] != '') ? "AND sub_categoria.id = '{$_POST['sub_categoria_id']}'" : "";
$marca = ($_POST['marca'] != '') ? "AND marca.id = '{$_POST['marca']}'" : "";

$consulta = "SELECT abastecimiento.* FROM " . T_ABASTECIMIENTO . " abastecimiento

JOIN " . T_ABASTECIMIENTO_DET . " abastecimiento_det ON abastecimiento.id = abastecimiento_det.abastecimiento_id

JOIN " . T_PRODUCTO . " producto ON abastecimiento_det.producto_id = producto.id

JOIN " . T_CATEGORIA . " categoria ON producto.categoria_id = categoria.id

JOIN " . T_SUB_CATEGORIA . " sub_categoria ON producto.sub_categoria_id = sub_categoria.id

JOIN " . T_marca . " marca ON producto.marca = marca.id

WHERE abastecimiento.fecha BETWEEN '{$_POST['f_inicio']}' AND '{$_POST['f_final']}' $usuario $producto $categoria $sub_categoria $marca GROUP BY abastecimiento.id";

$consulta = mysqli_query($lfmc['sql'], $consulta);
while ($dato = mysqli_fetch_assoc($consulta)) {
    $lfmc['abastecimientos'][] = $dato;
}
$html = CargarPagina('reportes/abastecimientos', true);

$dato = array('estado' => 200, 'mensaje' => "Reporte realizado correctamente", 'html' => ((!empty($html))?$html:'<div class="text-center"><h2>Sin datos para mostrar</h2></div>'));
echo json_encode($dato);
exit();
