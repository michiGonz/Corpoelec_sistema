-- Estructura de la tabla 'abastecimiento'
 drop table abastecimiento;
CREATE TABLE IF NOT EXISTS `abastecimiento` (
  `id` int(11) NOT NULL auto_increment,
  `usuario_id` int(11) NOT NULL,
  `total` double NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`)
);

-- Datos de la tabla 'abastecimiento'

-- Estructura de la tabla 'abastecimiento_det'
 drop table abastecimiento_det;
CREATE TABLE IF NOT EXISTS `abastecimiento_det` (
  `id` int(11) NOT NULL auto_increment,
  `abastecimiento_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `precio_venta` double NOT NULL,
  `precio_compra` double NOT NULL,
  `cntd` int(11) NOT NULL,
  `lote` varchar(250) DEFAULT '',
  `f_emision` date NOT NULL,
  `f_vencimiento` date NOT NULL,
  PRIMARY KEY (`id`)
);

-- Datos de la tabla 'abastecimiento_det'

-- Estructura de la tabla 'categoria'
 drop table categoria;
CREATE TABLE IF NOT EXISTS `categoria` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Datos de la tabla 'categoria'

-- Estructura de la tabla 'categoria_sub'
 drop table categoria_sub;
CREATE TABLE IF NOT EXISTS `categoria_sub` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(255) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Datos de la tabla 'categoria_sub'

-- Estructura de la tabla 'marca'
 drop table marca;
CREATE TABLE IF NOT EXISTS `marca` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Datos de la tabla 'marca'
INSERT INTO `marca` VALUES ('4','Lenovo');
INSERT INTO `marca` VALUES ('5','HP');
INSERT INTO `marca` VALUES ('6','CDP');
INSERT INTO `marca` VALUES ('7','DELL');
INSERT INTO `marca` VALUES ('8','VIT');

-- Estructura de la tabla 'producto'
 drop table producto;
CREATE TABLE IF NOT EXISTS `producto` (
  `id` int(11) NOT NULL auto_increment,
  `serial` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `departamento` varchar(255) NOT NULL,
  `marca_id` varchar(255) NOT NULL,
  `modelo` varchar(255) NOT NULL,
  `trabajador` varchar(255) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`)
);

-- Datos de la tabla 'producto'
INSERT INTO `producto` VALUES ('5','MXL52435KG','CPU','GERENCIA REGIONAL DE PROTECCION Y PREVENCION','8','ELITEDESK','Alexander Roca','2024-05-30');
INSERT INTO `producto` VALUES ('6','MXL52435KG','prueba beta','sistemas','6','elitedesk','maria camila','2024-05-30');
INSERT INTO `producto` VALUES ('7','MXL52435KG','prueba beta','sistemas','7','elitedesk','patricia','2024-05-30');

-- Estructura de la tabla 'stock'
 drop table stock;
CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(11) NOT NULL auto_increment,
  `producto_id` int(11) NOT NULL,
  `cntd` int(11) NOT NULL,
  `lote` varchar(255) NOT NULL,
  `f_vencimiento` date NOT NULL,
  `f_emision` date NOT NULL,
  PRIMARY KEY (`id`)
);

-- Datos de la tabla 'stock'
INSERT INTO `stock` VALUES ('1','1','0','','0000-00-00','2024-05-09');
INSERT INTO `stock` VALUES ('2','2','0','','0000-00-00','0000-00-00');
INSERT INTO `stock` VALUES ('3','3','0','','0000-00-00','0000-00-00');
INSERT INTO `stock` VALUES ('4','4','0','','0000-00-00','0000-00-00');
INSERT INTO `stock` VALUES ('5','5','0','','0000-00-00','0000-00-00');
INSERT INTO `stock` VALUES ('6','6','0','','0000-00-00','0000-00-00');
INSERT INTO `stock` VALUES ('7','7','0','','0000-00-00','0000-00-00');

-- Estructura de la tabla 'usuario'
 drop table usuario;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(255) NOT NULL DEFAULT '',
  `usuario` varchar(20) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `tipo` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
);

-- Datos de la tabla 'usuario'
INSERT INTO `usuario` VALUES ('2','michi','MICHI21','$2y$10$HLV15dIxfW8HjLU735yHt.qoCddtIYY.rcDUQfO8qvXZs.8IAoLae','administrador');

-- Estructura de la tabla 'venta'
 drop table venta;
CREATE TABLE IF NOT EXISTS `venta` (
  `id` int(11) NOT NULL auto_increment,
  `usuario_id` int(11) NOT NULL,
  `metodo_pago` varchar(20) NOT NULL DEFAULT '',
  `total` double NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`)
);

-- Datos de la tabla 'venta'

-- Estructura de la tabla 'venta_det'
 drop table venta_det;
CREATE TABLE IF NOT EXISTS `venta_det` (
  `id` int(11) NOT NULL auto_increment,
  `venta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `precio_venta` double NOT NULL,
  `precio_compra` double NOT NULL,
  `cntd` int(11) NOT NULL,
  `lote` varchar(250) DEFAULT '',
  `f_vencimiento` date NOT NULL,
  `f_emision` date NOT NULL,
  PRIMARY KEY (`id`)
);

-- Datos de la tabla 'venta_det'

