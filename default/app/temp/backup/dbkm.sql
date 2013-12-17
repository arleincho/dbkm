/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50614
 Source Host           : localhost
 Source Database       : dbkm

 Target Server Type    : MySQL
 Target Server Version : 50614
 File Encoding         : utf-8

 Date: 12/17/2013 02:58:19 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `acceso`
-- ----------------------------
DROP TABLE IF EXISTS `acceso`;
CREATE TABLE `acceso` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del acceso',
  `usuario_id` int(11) NOT NULL COMMENT 'Identificador del usuario que accede',
  `tipo_acceso` int(1) NOT NULL DEFAULT '1' COMMENT 'Tipo de acceso (entrata o salida)',
  `ip` varchar(45) DEFAULT NULL COMMENT 'DirecciÃ³n IP del usuario que ingresa',
  `registrado_at` datetime DEFAULT NULL COMMENT 'Fecha de registro del acceso',
  PRIMARY KEY (`id`),
  KEY `fk_acceso_usuario_idx` (`usuario_id`),
  CONSTRAINT `fk_acceso_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Tabla que registra los accesos de los usuarios al sistema';

-- ----------------------------
--  Records of `acceso`
-- ----------------------------
BEGIN;
INSERT INTO `acceso` VALUES ('1', '2', '1', '::1', '2013-12-17 02:38:04'), ('2', '2', '2', '::1', '2013-12-17 02:49:06'), ('3', '2', '1', '::1', '2013-12-17 02:55:05');
COMMIT;

-- ----------------------------
--  Table structure for `backup`
-- ----------------------------
DROP TABLE IF EXISTS `backup`;
CREATE TABLE `backup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `denominacion` varchar(200) NOT NULL,
  `tamano` varchar(45) DEFAULT NULL,
  `archivo` varchar(45) NOT NULL,
  `registrado_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_backup_usuario_idx` (`usuario_id`),
  CONSTRAINT `fk_backup_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Tabla que contiene las copias de seguridad del sistema';

-- ----------------------------
--  Records of `backup`
-- ----------------------------
BEGIN;
INSERT INTO `backup` VALUES ('1', '2', 'Sistema inicial', '4,09 KB', 'backup-1.sql.gz', '2013-01-01 00:00:01');
COMMIT;

-- ----------------------------
--  Table structure for `ciudad`
-- ----------------------------
DROP TABLE IF EXISTS `ciudad`;
CREATE TABLE `ciudad` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la ciudad',
  `ciudad` varchar(45) NOT NULL COMMENT 'Nombre de la cuidad',
  `registrado_at` datetime DEFAULT NULL COMMENT 'Fecha de registro',
  `modificado_in` datetime DEFAULT NULL COMMENT 'Fecha de la Ãºltima modificaciÃ³n',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Tabla que contiene las ciudades que se manejan del sistema';

-- ----------------------------
--  Records of `ciudad`
-- ----------------------------
BEGIN;
INSERT INTO `ciudad` VALUES ('1', 'OcaÃ±a', '2013-01-01 00:00:01', null);
COMMIT;

-- ----------------------------
--  Table structure for `empresa`
-- ----------------------------
DROP TABLE IF EXISTS `empresa`;
CREATE TABLE `empresa` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la empresa',
  `razon_social` varchar(100) NOT NULL COMMENT 'Nombre de la empresa',
  `siglas` varchar(45) DEFAULT NULL COMMENT 'Siglas del nombre de la empresa',
  `nit` varchar(15) NOT NULL COMMENT 'NÃºmero de identificaciÃ³n tributaria de la empresa',
  `dv` int(2) DEFAULT NULL COMMENT 'Digito de verificaciÃ³n del NIT',
  `representante_legal` varchar(100) NOT NULL COMMENT 'Nombre del representante legal de la empresa',
  `nuip` bigint(20) NOT NULL COMMENT 'NÃºmero de identificaciÃ³n personal',
  `tipo_nuip_id` int(1) NOT NULL COMMENT 'Tipo de identificaciÃ³n',
  `pagina_web` varchar(45) DEFAULT NULL,
  `logo` varchar(45) DEFAULT NULL,
  `registrado_at` varchar(45) DEFAULT NULL,
  `modificado_in` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empresa_tipo_nuip_idx` (`tipo_nuip_id`),
  CONSTRAINT `fk_empresa_tipo_nuip` FOREIGN KEY (`tipo_nuip_id`) REFERENCES `tipo_nuip` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Tabla que contiene la informaciÃ³n bÃ¡sica de la empresa';

-- ----------------------------
--  Records of `empresa`
-- ----------------------------
BEGIN;
INSERT INTO `empresa` VALUES ('1', 'Intera', 'Intera', '1091652165', '6', 'IvÃ¡n David MelÃ©ndez', '1091652165', '1', 'http://www.intera.co', 'default.png', '2013-01-01 00:00:01', '2013-12-17 2:39:24');
COMMIT;

-- ----------------------------
--  Table structure for `estado_usuario`
-- ----------------------------
DROP TABLE IF EXISTS `estado_usuario`;
CREATE TABLE `estado_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del estado del usuario',
  `usuario_id` int(11) NOT NULL COMMENT 'Identificador del usuario',
  `estado_usuario` int(11) NOT NULL COMMENT 'CÃ³digo del estado del usuario',
  `descripcion` varchar(100) NOT NULL COMMENT 'Motivo del cambio de estado',
  `fecha_estado_at` datetime DEFAULT NULL COMMENT 'Fecha del cambio de estado',
  PRIMARY KEY (`id`),
  KEY `fk_estado_usuario_usuario_idx` (`usuario_id`),
  CONSTRAINT `fk_estado_usuario_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Tabla que contiene los estados de los usuarios';

-- ----------------------------
--  Records of `estado_usuario`
-- ----------------------------
BEGIN;
INSERT INTO `estado_usuario` VALUES ('1', '1', '2', 'Bloqueado por ser un usuario sin privilegios', '2013-01-01 00:00:01'), ('2', '2', '1', 'Activo por ser el Super Usuario del sistema', '2013-01-01 00:00:01');
COMMIT;

-- ----------------------------
--  Table structure for `menu`
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del menÃº',
  `menu_id` int(11) DEFAULT NULL COMMENT 'Identificador del menÃº padre',
  `recurso_id` int(11) DEFAULT NULL COMMENT 'Identificador del recurso',
  `menu` varchar(45) NOT NULL COMMENT 'Texto a mostrar del menÃº',
  `url` varchar(60) DEFAULT NULL COMMENT 'Url del menÃº',
  `posicion` int(11) DEFAULT '0' COMMENT 'PosisiÃ³n dentro de otros items',
  `icono` varchar(45) DEFAULT NULL COMMENT 'Icono a mostrar ',
  `activo` int(1) NOT NULL DEFAULT '1' COMMENT 'MenÃº activo o inactivo',
  `visibilidad` int(1) NOT NULL DEFAULT '1' COMMENT 'Indica si el menÃº se muestra en el backend o en el frontend',
  PRIMARY KEY (`id`),
  KEY `fk_menu_recurso_idx` (`recurso_id`),
  KEY `fk_menu_menu_idx` (`menu_id`),
  CONSTRAINT `fk_menu_recurso` FOREIGN KEY (`recurso_id`) REFERENCES `recurso` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_menu_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='Tabla que contiene los menÃº para los usuarios';

-- ----------------------------
--  Records of `menu`
-- ----------------------------
BEGIN;
INSERT INTO `menu` VALUES ('1', null, null, 'Dashboard', '#', '10', 'icon-home', '1', '1'), ('2', '1', '2', 'Dashboard', 'dashboard/', '11', 'icon-home', '1', '1'), ('3', null, null, 'Sistema', '#', '900', 'icon-cogs', '1', '1'), ('4', '3', '4', 'Accesos', 'sistema/acceso/listar/', '901', 'icon-exchange', '1', '1'), ('5', '3', '5', 'AuditorÃ­as', 'sistema/auditoria/', '902', 'icon-eye-open', '1', '1'), ('6', '3', '6', 'Backups', 'sistema/backup/listar/', '903', 'icon-hdd', '1', '1'), ('7', '3', '7', 'Mantenimiento', 'sistema/mantenimiento/', '904', 'icon-bolt', '1', '1'), ('8', '3', '8', 'MenÃºs', 'sistema/menu/listar/', '905', 'icon-list', '1', '1'), ('9', '3', '9', 'Perfiles', 'sistema/perfil/listar/', '906', 'icon-group', '1', '1'), ('10', '3', '10', 'Permisos', 'sistema/privilegio/listar/', '907', 'icon-magic', '1', '1'), ('11', '3', '11', 'Recursos', 'sistema/recurso/listar/', '908', 'icon-lock', '1', '1'), ('12', '3', '12', 'Usuarios', 'sistema/usuario/listar/', '909', 'icon-user', '1', '1'), ('13', '3', '13', 'Visor de sucesos', 'sistema/sucesos/', '910', 'icon-filter', '1', '1'), ('14', '3', '14', 'Sistema', 'sistema/configuracion/', '911', 'icon-wrench', '1', '1'), ('15', null, null, 'Configuraciones', '#', '800', 'icon-wrench', '1', '1'), ('16', '15', '15', 'Empresa', 'config/empresa/', '801', 'icon-briefcase', '1', '1'), ('17', '15', '16', 'Sucursales', 'config/sucursal/listar/', '802', 'icon-sitemap', '1', '1');
COMMIT;

-- ----------------------------
--  Table structure for `perfil`
-- ----------------------------
DROP TABLE IF EXISTS `perfil`;
CREATE TABLE `perfil` (
  `id` int(2) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del perfil',
  `perfil` varchar(45) NOT NULL COMMENT 'Nombre del perfil',
  `estado` int(1) NOT NULL DEFAULT '1' COMMENT 'Indica si el perfil esta activo o inactivo',
  `plantilla` varchar(45) DEFAULT 'default' COMMENT 'Plantilla para usar en el sitema',
  `registrado_at` datetime DEFAULT NULL COMMENT 'Fecha de registro del perfil',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Tabla que contiene los grupos de los usuarios';

-- ----------------------------
--  Records of `perfil`
-- ----------------------------
BEGIN;
INSERT INTO `perfil` VALUES ('1', 'Super Usuario', '1', 'default', '2013-01-01 00:00:01'), ('2', 'Autorizadores', '1', 'default', '2013-12-17 02:44:44');
COMMIT;

-- ----------------------------
--  Table structure for `persona`
-- ----------------------------
DROP TABLE IF EXISTS `persona`;
CREATE TABLE `persona` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `nuip` bigint(20) NOT NULL COMMENT 'NÃºmero de identificaciÃ³n personal',
  `tipo_nuip_id` int(11) NOT NULL COMMENT 'Tipo de identificaciÃ³n',
  `telefono` varchar(45) DEFAULT NULL,
  `fotografia` varchar(45) DEFAULT 'default.png' COMMENT 'FotografÃ­a',
  `registrado_at` datetime DEFAULT NULL,
  `modificado_in` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_persona_tipo_nuip_idx` (`tipo_nuip_id`),
  CONSTRAINT `fk_persona_tipo_nuip` FOREIGN KEY (`tipo_nuip_id`) REFERENCES `tipo_nuip` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Tabla que contiene las personas que interactÃºan con el sistema';

-- ----------------------------
--  Records of `persona`
-- ----------------------------
BEGIN;
INSERT INTO `persona` VALUES ('1', 'Error', 'Error', '1010101010', '1', null, 'default.png', '2013-01-01 00:00:01', null), ('2', 'Arley', 'Wilches Marcelo', '80100103', '1', null, 'default.png', '2013-01-01 00:00:01', '2013-12-17 02:43:53');
COMMIT;

-- ----------------------------
--  Table structure for `recurso`
-- ----------------------------
DROP TABLE IF EXISTS `recurso`;
CREATE TABLE `recurso` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del recurso',
  `modulo` varchar(45) DEFAULT NULL COMMENT 'Nombre del mÃ³dulo',
  `controlador` varchar(45) DEFAULT NULL COMMENT 'Nombre del controlador',
  `accion` varchar(45) DEFAULT NULL COMMENT 'Nombre de la acciÃ³n',
  `recurso` varchar(100) DEFAULT NULL COMMENT 'Nombre del recurso',
  `descripcion` text NOT NULL COMMENT 'DescripciÃ³n del recurso',
  `activo` int(1) NOT NULL DEFAULT '1' COMMENT 'Estado del recurso',
  `registrado_at` datetime DEFAULT NULL COMMENT 'Fecha de registro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='Tabla que contiene los recursos a los que acceden los usuarios';

-- ----------------------------
--  Records of `recurso`
-- ----------------------------
BEGIN;
INSERT INTO `recurso` VALUES ('1', '*', null, null, '*', 'ComodÃ­n para la administraciÃ³n total (usar con cuidado)', '1', '2013-01-01 00:00:01'), ('2', 'dashboard', '*', '*', 'dashboard/*/*', 'PÃ¡gina principal del sistema', '1', '2013-01-01 00:00:01'), ('3', 'sistema', 'mi_cuenta', '*', 'sistema/mi_cuenta/*', 'GestiÃ³n de la cuenta del usuario logueado', '1', '2013-01-01 00:00:01'), ('4', 'sistema', 'acceso', '*', 'sistema/acceso/*', 'SubmÃ³dulo para la gestiÃ³n de ingresos al sistema', '1', '2013-01-01 00:00:01'), ('5', 'sistema', 'auditoria', '*', 'sistema/auditoria/*', 'SubmÃ³dulo para el control de las acciones de los usuarios', '1', '2013-01-01 00:00:01'), ('6', 'sistema', 'backup', '*', 'sistema/backup/*', 'SubmÃ³dulo para la gestiÃ³n de las copias de seguridad', '1', '2013-01-01 00:00:01'), ('7', 'sistema', 'mantenimiento', '*', 'sistema/mantenimiento/*', 'SubmÃ³dulo para el mantenimiento de las tablas', '1', '2013-01-01 00:00:01'), ('8', 'sistema', 'menu', '*', 'sistema/menu/*', 'SubmÃ³dulo del sistema para la creaciÃ³n de menÃºs', '1', '2013-01-01 00:00:01'), ('9', 'sistema', 'perfil', '*', 'sistema/perfil/*', 'SubmÃ³dulo del sistema para los perfiles de usuarios', '1', '2013-01-01 00:00:01'), ('10', 'sistema', 'privilegio', '*', 'sistema/privilegio/*', 'SubmÃ³dulo del sistema para asignar recursos a los perfiles', '1', '2013-01-01 00:00:01'), ('11', 'sistema', 'recurso', '*', 'sistema/recurso/*', 'SubmÃ³dulo del sistema para la gestiÃ³n de los recursos', '1', '2013-01-01 00:00:01'), ('12', 'sistema', 'usuario', '*', 'sistema/usuario/*', 'SubmÃ³dulo para la administraciÃ³n de los usuarios del sistema', '1', '2013-01-01 00:00:01'), ('13', 'sistema', 'sucesos', '*', 'sistema/suceso/*', 'SubmÃ³dulo para el listado de los logs del sistema', '1', '2013-01-01 00:00:01'), ('14', 'sistema', 'configuracion', '*', 'sistema/configuracion/*', 'SubmÃ³dulo para la configuraciÃ³n de la aplicaciÃ³n (.ini)', '1', '2013-01-01 00:00:01'), ('15', 'config', 'empresa', '*', 'config/empresa/*', 'SubmÃ³dulo para la configuraciÃ³n de la informaciÃ³n de la empresa', '1', '2013-01-01 00:00:01'), ('16', 'config', 'sucursal', '*', 'config/sucursal/*', 'SubmÃ³dulo para la administraciÃ³n de las sucursales', '1', '2013-01-01 00:00:01');
COMMIT;

-- ----------------------------
--  Table structure for `recurso_perfil`
-- ----------------------------
DROP TABLE IF EXISTS `recurso_perfil`;
CREATE TABLE `recurso_perfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recurso_id` int(11) NOT NULL,
  `perfil_id` int(11) NOT NULL,
  `registrado_at` datetime DEFAULT NULL,
  `modificado_in` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_recurso_perfil_recurso_idx` (`recurso_id`),
  KEY `fk_recurso_perfil_perfil_idx` (`perfil_id`),
  CONSTRAINT `fk_recurso_perfil_recurso` FOREIGN KEY (`recurso_id`) REFERENCES `recurso` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_recurso_perfil_perfil` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Tabla que contiene los recursos del usuario en el sistema segun su perfl';

-- ----------------------------
--  Records of `recurso_perfil`
-- ----------------------------
BEGIN;
INSERT INTO `recurso_perfil` VALUES ('1', '1', '1', '2013-01-01 00:00:01', null), ('2', '2', '2', '2013-12-17 02:44:44', null);
COMMIT;

-- ----------------------------
--  Table structure for `sucursal`
-- ----------------------------
DROP TABLE IF EXISTS `sucursal`;
CREATE TABLE `sucursal` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'IdentificaciÃ³n de la sucursal',
  `empresa_id` int(11) NOT NULL COMMENT 'Identificador de la empresa',
  `sucursal` varchar(45) NOT NULL COMMENT 'Nombre de la sucursal',
  `sucursal_slug` varchar(45) DEFAULT NULL COMMENT 'Slug de la sucursal',
  `direccion` varchar(45) DEFAULT NULL COMMENT 'DirecciÃ³n de la sucursal',
  `telefono` varchar(45) DEFAULT NULL COMMENT 'NÃºmero del telÃ©fono',
  `fax` varchar(45) DEFAULT NULL COMMENT 'NÃºmero del fax',
  `celular` varchar(45) DEFAULT NULL COMMENT 'NÃºmero de celular',
  `ciudad_id` int(11) NOT NULL COMMENT 'Identificador de la ciudad',
  `registrado_at` datetime DEFAULT NULL COMMENT 'Fecha de registro',
  `modificado_in` datetime DEFAULT NULL COMMENT 'Fecha de la Ãºltima modificaciÃ³n',
  PRIMARY KEY (`id`),
  KEY `fk_sucursal_empresa_idx` (`empresa_id`),
  KEY `fk_sucursal_ciudad_idx` (`ciudad_id`),
  CONSTRAINT `fk_sucursal_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_sucursal_ciudad` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudad` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Tabla que contiene las sucursales de la empresa';

-- ----------------------------
--  Records of `sucursal`
-- ----------------------------
BEGIN;
INSERT INTO `sucursal` VALUES ('1', '1', 'Oficina Principal', 'oficina-principal', 'DirecciÃ³n', '3162404183', '3162404183', '3162404183', '1', '2013-01-01 00:00:01', null);
COMMIT;

-- ----------------------------
--  Table structure for `tipo_nuip`
-- ----------------------------
DROP TABLE IF EXISTS `tipo_nuip`;
CREATE TABLE `tipo_nuip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_nuip` varchar(45) NOT NULL COMMENT 'Nombre del tipo de identificaciÃ³n',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Tabla que contiene los tipos de identificaciÃ³n de las personas';

-- ----------------------------
--  Records of `tipo_nuip`
-- ----------------------------
BEGIN;
INSERT INTO `tipo_nuip` VALUES ('1', 'C.C.'), ('2', 'C.E.'), ('3', 'PAS.'), ('4', 'T.I.'), ('5', 'N.D.');
COMMIT;

-- ----------------------------
--  Table structure for `usuario`
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador del usuario',
  `sucursal_id` int(11) DEFAULT NULL COMMENT 'Identificador a la sucursal a la cual pertenece',
  `persona_id` int(11) NOT NULL COMMENT 'Identificador de la persona',
  `login` varchar(45) NOT NULL COMMENT 'Nombre de usuario',
  `password` varchar(45) NOT NULL COMMENT 'ContraseÃ±a de acceso al sistea',
  `perfil_id` int(2) NOT NULL COMMENT 'Identificador del perfil',
  `email` varchar(45) DEFAULT NULL COMMENT 'DirecciÃ³n del correo electÃ³nico',
  `tema` varchar(45) DEFAULT 'default' COMMENT 'Tema aplicable para la interfaz',
  `app_ajax` int(1) DEFAULT '1' COMMENT 'Indica si la app se trabaja con ajax o peticiones normales',
  `datagrid` int(11) DEFAULT '30' COMMENT 'Datos por pÃ¡gina en los datagrid',
  `registrado_at` datetime DEFAULT NULL COMMENT 'Fecha de registro',
  `modificado_in` datetime DEFAULT NULL COMMENT 'Fecha de la Ãºltima modificaciÃ³n',
  PRIMARY KEY (`id`),
  KEY `fk_usuario_perfil_idx` (`perfil_id`),
  KEY `fk_usuario_persona_idx` (`persona_id`),
  KEY `fk_usuario_sucursal_idx` (`sucursal_id`),
  CONSTRAINT `fk_usuario_perfil` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_usuario_persona` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_usuario_sucursal` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursal` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Tabla que contiene los usuarios';

-- ----------------------------
--  Records of `usuario`
-- ----------------------------
BEGIN;
INSERT INTO `usuario` VALUES ('1', null, '1', 'error', 'd93a5def7511da3d0f2d171d9c344e91', '1', null, 'default', '1', '30', '2013-01-01 00:00:01', null), ('2', null, '2', 'admin', 'd93a5def7511da3d0f2d171d9c344e91', '1', 'arley.wilches@gmail.com', 'default', '1', '30', '2013-01-01 00:00:01', '2013-12-17 02:43:53');
COMMIT;

