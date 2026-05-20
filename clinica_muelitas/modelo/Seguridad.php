<?php

class Seguridad
{
	private static $config = null;

	public static function configuracion()
	{
		if (self::$config === null) {
			$ruta = dirname(__DIR__) . '/config/config.php';
			if (!is_file($ruta)) {
				die('Falta el archivo config/config.php. Copie config.example.php como config.php.');
			}
			self::$config = require $ruta;
		}
		return self::$config;
	}

	public static function registroPermitido()
	{
		$cfg = self::configuracion();
		return !empty($cfg['permitir_registro']);
	}

	private static function rutaCookieSesion()
	{
		$base = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
		$base = str_replace('\\', '/', $base);
		if ($base === '/' || $base === '.' || $base === '') {
			return '/';
		}
		return rtrim($base, '/') . '/';
	}

	public static function iniciar()
	{
		$cfg = self::configuracion();

		if (session_status() === PHP_SESSION_NONE) {
			session_name($cfg['session_name']);
			session_set_cookie_params([
				'lifetime' => 0,
				'path' => self::rutaCookieSesion(),
				'domain' => '',
				'secure' => self::esHttps(),
				'httponly' => true,
				'samesite' => 'Lax',
			]);
			session_start();
		}

		self::enviarCabecerasSeguridad();
		self::renovarActividad($cfg['session_timeout']);

		if (empty($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}
	}

	private static function esHttps()
	{
		return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
	}

	public static function enviarCabecerasSeguridad()
	{
		if (headers_sent()) {
			return;
		}
		header('X-Frame-Options: SAMEORIGIN');
		header('X-Content-Type-Options: nosniff');
		header('Referrer-Policy: strict-origin-when-cross-origin');
		header('X-XSS-Protection: 1; mode=block');
		header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:;");
	}

	private static function renovarActividad($timeout)
	{
		$ahora = time();
		if (isset($_SESSION['ultimo_acceso']) && ($ahora - $_SESSION['ultimo_acceso']) > $timeout) {
			self::cerrarSesion();
			return;
		}
		$_SESSION['ultimo_acceso'] = $ahora;
	}

	public static function estaAutenticado()
	{
		return !empty($_SESSION['usuario_id']) && !empty($_SESSION['usuario_nombre']);
	}

	public static function cerrarSesion()
	{
		$_SESSION = [];
		if (ini_get('session.use_cookies')) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
		}
		session_destroy();
	}

	public static function obtenerTokenCsrf()
	{
		return $_SESSION['csrf_token'] ?? '';
	}

	public static function validarCsrf($token)
	{
		return is_string($token)
			&& $token !== ''
			&& hash_equals(self::obtenerTokenCsrf(), $token);
	}

	public static function campoCsrf()
	{
		$token = htmlspecialchars(self::obtenerTokenCsrf(), ENT_QUOTES, 'UTF-8');
		return '<input type="hidden" name="_csrf" value="' . $token . '">';
	}

	public static function esc($texto)
	{
		return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
	}

	public static function usuarioValido($usuario)
	{
		return (bool) preg_match('/^[a-zA-Z0-9._-]{4,50}$/', (string) $usuario);
	}

	public static function claveValida($clave)
	{
		return is_string($clave) && strlen($clave) >= 8 && strlen($clave) <= 128;
	}

	public static function documentoValido($doc)
	{
		return (bool) preg_match('/^[a-zA-Z0-9]{4,20}$/', (string) $doc);
	}

	public static function medicoIdValido($id)
	{
		return (bool) preg_match('/^[a-zA-Z0-9]{1,20}$/', (string) $id);
	}

	public static function textoValido($texto, $max = 80)
	{
		$texto = trim((string) $texto);
		return $texto !== ''
			&& mb_strlen($texto) <= $max
			&& (bool) preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s'.-]+$/u", $texto);
	}

	public static function fechaValida($fecha)
	{
		$d = DateTime::createFromFormat('Y-m-d', (string) $fecha);
		return $d && $d->format('Y-m-d') === $fecha;
	}

	public static function horaValida($hora)
	{
		return (bool) preg_match('/^\d{2}:\d{2}(:\d{2})?$/', (string) $hora);
	}

	public static function loginBloqueado()
	{
		$cfg = self::configuracion();
		if (empty($_SESSION['login_bloqueo_hasta'])) {
			return false;
		}
		if (time() < $_SESSION['login_bloqueo_hasta']) {
			return true;
		}
		unset($_SESSION['login_intentos'], $_SESSION['login_bloqueo_hasta']);
		return false;
	}

	public static function registrarIntentoFallido()
	{
		$cfg = self::configuracion();
		$_SESSION['login_intentos'] = ($_SESSION['login_intentos'] ?? 0) + 1;
		if ($_SESSION['login_intentos'] >= $cfg['login_max_intentos']) {
			$_SESSION['login_bloqueo_hasta'] = time() + $cfg['login_bloqueo_segundos'];
		}
	}

	public static function limpiarIntentosLogin()
	{
		unset($_SESSION['login_intentos'], $_SESSION['login_bloqueo_hasta']);
	}
}
