<?php

class GestorUsuario
{
	private $mysqli;

	public function __construct()
	{
		$conexion = new Conexion();
		$this->mysqli = $conexion->abrir();
		if (!$this->mysqli) {
			throw new RuntimeException('No se pudo conectar a la base de datos.');
		}
	}

	public function __destruct()
	{
		if ($this->mysqli) {
			$this->mysqli->close();
		}
	}

	public function asegurarTablaUsuarios()
	{
		$sql = 'CREATE TABLE IF NOT EXISTS `usuarios` (
			`UsuId` int(11) NOT NULL AUTO_INCREMENT,
			`UsuUsuario` varchar(50) NOT NULL,
			`UsuClave` varchar(255) NOT NULL,
			`UsuNombre` varchar(100) NOT NULL,
			`UsuActivo` tinyint(1) NOT NULL DEFAULT 1,
			PRIMARY KEY (`UsuId`),
			UNIQUE KEY `UsuUsuario` (`UsuUsuario`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
		return (bool) $this->mysqli->query($sql);
	}

	public function requiereConfiguracionInicial()
	{
		$this->asegurarTablaUsuarios();
		$result = $this->mysqli->query('SELECT COUNT(*) AS total FROM usuarios WHERE UsuActivo = 1');
		if (!$result) {
			return true;
		}
		$fila = $result->fetch_assoc();
		return ((int) ($fila['total'] ?? 0)) === 0;
	}

	public function usuarioExiste($usuario)
	{
		$this->asegurarTablaUsuarios();
		$sql = 'SELECT UsuId FROM usuarios WHERE UsuUsuario = ? LIMIT 1';
		$stmt = $this->mysqli->prepare($sql);
		if (!$stmt) {
			return false;
		}
		$stmt->bind_param('s', $usuario);
		$stmt->execute();
		$result = $stmt->get_result();
		$existe = $result && $result->num_rows > 0;
		$stmt->close();
		return $existe;
	}

	/**
	 * @return string ok | existe | error
	 */
	public function crearUsuario($usuario, $clave, $nombre)
	{
		$this->asegurarTablaUsuarios();
		if ($this->usuarioExiste($usuario)) {
			return 'existe';
		}

		$hash = password_hash($clave, PASSWORD_DEFAULT);
		$sql = 'INSERT INTO usuarios (UsuUsuario, UsuClave, UsuNombre, UsuActivo) VALUES (?, ?, ?, 1)';
		$stmt = $this->mysqli->prepare($sql);
		if (!$stmt) {
			return 'error';
		}
		$stmt->bind_param('sss', $usuario, $hash, $nombre);
		$ok = $stmt->execute();
		$stmt->close();
		return $ok ? 'ok' : 'error';
	}

	public function crearPrimerAdministrador($usuario, $clave, $nombre)
	{
		if (!$this->requiereConfiguracionInicial()) {
			return false;
		}
		return $this->crearUsuario($usuario, $clave, $nombre) === 'ok';
	}

	public function autenticar($usuario, $clave)
	{
		$this->asegurarTablaUsuarios();
		$sql = 'SELECT UsuId, UsuUsuario, UsuClave, UsuNombre FROM usuarios WHERE UsuUsuario = ? AND UsuActivo = 1 LIMIT 1';
		$stmt = $this->mysqli->prepare($sql);
		if (!$stmt) {
			return false;
		}
		$stmt->bind_param('s', $usuario);
		$stmt->execute();
		$result = $stmt->get_result();
		$fila = $result ? $result->fetch_assoc() : null;
		$stmt->close();

		if (!$fila || !password_verify($clave, $fila['UsuClave'])) {
			return false;
		}

		return $fila;
	}
}
