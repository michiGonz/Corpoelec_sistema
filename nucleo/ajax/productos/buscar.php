<?php require_once('../../iniciar_app.php');

$busqueda = $_GET['buscar'];
$productos = [];

$consulta = "SELECT prd.*
FROM ".T_PRODUCTO." prd
JOIN ".T_STOCK." stk ON prd.id = stk.producto_id
JOIN ".T_CATEGORIA." ctg ON prd.categoria_id = ctg.id
JOIN ".T_SUB_CATEGORIA." sctg ON prd.sub_categoria_id = sctg.id
JOIN ".T_marca." mrc ON prd.marca = mrc.id
WHERE prd.nombre LIKE '%$busqueda%' OR prd.codigo LIKE '%$busqueda%' OR stk.lote LIKE '%$busqueda%' OR ctg.nombre LIKE '%$busqueda%' OR sctg.nombre LIKE '%$busqueda%' OR mrc.nombre LIKE '%$busqueda%'  GROUP BY prd.id LIMIT 10";

$consulta = mysqli_query($lfmc['sql'], $consulta);
while ($producto = mysqli_fetch_assoc($consulta)) {
    $productos[] = $producto;
}
$dato = array('estado' => 400, 'mensaje' => "Búsqueda terminada", 'productos' => $productos);


echo json_encode($dato);
exit();
