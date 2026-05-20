-- OPCIONAL: el sistema crea la tabla usuarios automáticamente.
-- Si no hay ningún usuario, la pantalla de acceso muestra "Crear cuenta administrador".
-- Use este script solo si desea precargar un usuario sin pasar por el asistente.

CREATE TABLE IF NOT EXISTS `usuarios` (
  `UsuId` int(11) NOT NULL AUTO_INCREMENT,
  `UsuUsuario` varchar(50) NOT NULL,
  `UsuClave` varchar(255) NOT NULL,
  `UsuNombre` varchar(100) NOT NULL,
  `UsuActivo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`UsuId`),
  UNIQUE KEY `UsuUsuario` (`UsuUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuario de ejemplo (opcional): admin / AdminMuelitas2026
-- INSERT INTO `usuarios` (`UsuUsuario`, `UsuClave`, `UsuNombre`, `UsuActivo`) VALUES
-- ('admin', '$2y$10$kNmvTth1QRWuU20d77e3RO0XXfrKzQgGYlGAJK3CPNclD.OVGTeFm', 'Administrador', 1);
