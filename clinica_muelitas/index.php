<?php

require_once 'modelo/Seguridad.php';
Seguridad::iniciar();

require_once 'modelo/conexion.php';
require_once 'modelo/GestorUsuario.php';
require_once 'modelo/GestorCita.php';
require_once 'modelo/cita.php';
require_once 'modelo/paciente.php';
require_once 'controlador/controlador.php';

if (isset($_GET['accion'])) {
	$accion = $_GET['accion'];
} elseif (isset($_POST['accion'])) {
	$accion = $_POST['accion'];
} else {
	$accion = null;
}
$errorLogin = '';
$mensajeLogin = '';
$configuracionInicial = false;

if ($accion === 'logout') {
	Seguridad::cerrarSesion();
	header('Location: index.php?accion=login');
	exit;
}

if ($accion === 'login') {
	try {
		$gestorUsuario = new GestorUsuario();
		$configuracionInicial = $gestorUsuario->requiereConfiguracionInicial();
	} catch (Exception $e) {
		$errorLogin = 'No se pudo conectar a la base de datos. Revise config/config.php y que MySQL esté activo.';
		$configuracionInicial = false;
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && $errorLogin === '') {
		if (!Seguridad::validarCsrf($_POST['_csrf'] ?? '')) {
			$errorLogin = 'La sesión expiró. Recargue la página e intente de nuevo.';
		} elseif (Seguridad::loginBloqueado()) {
			$errorLogin = 'Demasiados intentos fallidos. Espere 15 minutos e intente de nuevo.';
		} else {
			$usuario = trim($_POST['usuario'] ?? '');
			$clave = $_POST['clave'] ?? '';
			$fila = $gestorUsuario->autenticar($usuario, $clave);
			if ($fila) {
				session_regenerate_id(true);
				$_SESSION['usuario_id'] = (int) $fila['UsuId'];
				$_SESSION['usuario_nombre'] = $fila['UsuNombre'];
				Seguridad::limpiarIntentosLogin();
				header('Location: index.php');
				exit;
			}
			Seguridad::registrarIntentoFallido();
			$errorLogin = 'Usuario o contraseña incorrectos.';
		}
	}

	if ($errorLogin === '' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
		try {
			if (!isset($gestorUsuario)) {
				$gestorUsuario = new GestorUsuario();
			}
			if ($gestorUsuario->requiereConfiguracionInicial()) {
				header('Location: index.php?accion=registro');
				exit;
			}
		} catch (Exception $e) {
			if ($errorLogin === '') {
				$errorLogin = 'No se pudo conectar a la base de datos. Revise config/config.php y que MySQL esté activo.';
			}
		}
	}

	if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'cuenta_creada') {
		$mensajeLogin = 'Cuenta creada correctamente. Ya puede iniciar sesión.';
	}

	require_once 'vista/html/login.php';
	exit;
}

if ($accion === 'registro') {
	$errorRegistro = '';
	$configuracionInicial = false;

	if (!Seguridad::registroPermitido()) {
		$errorRegistro = 'El registro de nuevas cuentas está deshabilitado.';
	} else {
		try {
			$gestorUsuario = new GestorUsuario();
			$configuracionInicial = $gestorUsuario->requiereConfiguracionInicial();
		} catch (Exception $e) {
			$errorRegistro = 'No se pudo conectar a la base de datos. Revise config/config.php y que MySQL esté activo.';
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && $errorRegistro === '') {
			if (!Seguridad::validarCsrf($_POST['_csrf'] ?? '')) {
				$errorRegistro = 'La sesión expiró. Recargue la página e intente de nuevo.';
			} else {
				$usuario = trim($_POST['usuario'] ?? '');
				$clave = $_POST['clave'] ?? '';
				$clave2 = $_POST['clave_confirmar'] ?? '';
				$nombre = trim($_POST['nombre'] ?? '');

				if (!Seguridad::usuarioValido($usuario)) {
					$errorRegistro = 'El usuario debe tener entre 4 y 50 caracteres (letras, números, punto, guion).';
				} elseif (!Seguridad::claveValida($clave)) {
					$errorRegistro = 'La contraseña debe tener al menos 8 caracteres.';
				} elseif ($clave !== $clave2) {
					$errorRegistro = 'Las contraseñas no coinciden.';
				} elseif (!Seguridad::textoValido($nombre, 100)) {
					$errorRegistro = 'Indique un nombre válido.';
				} else {
					$resultado = $gestorUsuario->crearUsuario($usuario, $clave, $nombre);
					if ($resultado === 'ok') {
						$fila = $gestorUsuario->autenticar($usuario, $clave);
						if ($fila) {
							session_regenerate_id(true);
							$_SESSION['usuario_id'] = (int) $fila['UsuId'];
							$_SESSION['usuario_nombre'] = $fila['UsuNombre'];
							Seguridad::limpiarIntentosLogin();
							header('Location: index.php');
							exit;
						}
						header('Location: index.php?accion=login&mensaje=cuenta_creada');
						exit;
					} elseif ($resultado === 'existe') {
						$errorRegistro = 'Ese nombre de usuario ya está en uso. Elija otro.';
					} else {
						$errorRegistro = 'No se pudo crear la cuenta. Intente de nuevo.';
					}
				}
			}
		}
	}

	require_once 'vista/html/registro.php';
	exit;
}

if (!Seguridad::estaAutenticado()) {
	header('Location: index.php?accion=login');
	exit;
}

$controlador = new Controlador();

if ($accion !== null) {

	if ($accion === 'asignar') {
		$controlador->cargarAsignar();
	} elseif ($accion === 'consultar') {
		$controlador->verPagina('vista/html/consultar.php');
	} elseif ($accion === 'cancelar') {
		$controlador->verPagina('vista/html/cancelar.php');
	} elseif ($accion === 'guardarCita') {
		if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Seguridad::validarCsrf($_POST['_csrf'] ?? '')) {
			die('Solicitud no válida. Recargue la página e intente de nuevo.');
		}
		$docPaciente = trim((string) ($_POST['asignarDocumento'] ?? ''));
		$medico = trim((string) ($_POST['medico'] ?? ''));
		$fecha = trim((string) ($_POST['fecha'] ?? ''));
		$hora = trim((string) ($_POST['hora'] ?? ''));
		$consultorio = (int) ($_POST['consultorio'] ?? 0);

		if (
			$docPaciente !== '' &&
			Seguridad::documentoValido($docPaciente) &&
			$medico !== '' && $medico !== '-1' &&
			Seguridad::medicoIdValido($medico) &&
			$fecha !== '' &&
			Seguridad::fechaValida($fecha) &&
			$hora !== '' && $hora !== '-1' &&
			Seguridad::horaValida($hora) &&
			$consultorio > 0
		) {
			$controlador->agregarCita(
				$docPaciente,
				$medico,
				$fecha,
				$hora,
				$consultorio
			);
		} else {
			echo 'Error: Debes completar todos los campos correctamente (paciente, médico, fecha, hora y consultorio).';
		}
	} elseif ($accion === 'consultarCita') {
		$doc = trim($_GET['consultarDocumento'] ?? '');
		if (Seguridad::documentoValido($doc)) {
			$controlador->consultarCitas($doc);
		} else {
			echo '<p class="mensaje-vacio">Documento no válido.</p>';
		}
	} elseif ($accion === 'cancelarCita') {
		$doc = trim($_GET['cancelarDocumento'] ?? '');
		if (Seguridad::documentoValido($doc)) {
			$controlador->cancelarCitas($doc);
		} else {
			echo '<p class="mensaje-vacio">Documento no válido.</p>';
		}
	} elseif ($accion === 'consultarPaciente') {
		$doc = trim($_GET['documento'] ?? '');
		if (Seguridad::documentoValido($doc)) {
			$controlador->consultarPaciente($doc);
		} else {
			echo '<p class="mensaje-vacio">Documento no válido.</p>';
		}
	} elseif ($accion === 'ingresarpaciente') {
		if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !Seguridad::validarCsrf($_POST['_csrf'] ?? '')) {
			die('Solicitud no válida.');
		}
		$doc = trim($_POST['pacDocumento'] ?? '');
		$nom = trim($_POST['pacNombres'] ?? '');
		$ape = trim($_POST['pacApellidos'] ?? '');
		$fech = $_POST['pacNacimiento'] ?? '';
		$sex = $_POST['pacSexo'] ?? '';
		if (
			Seguridad::documentoValido($doc) &&
			Seguridad::textoValido($nom) &&
			Seguridad::textoValido($ape) &&
			Seguridad::fechaValida($fech) &&
			in_array($sex, ['M', 'F'], true)
		) {
			$controlador->agregarPaciente($doc, $nom, $ape, $fech, $sex);
		} else {
			echo 'Error: datos del paciente no válidos.';
		}
	} elseif ($accion === 'consultarHoras') {
		$med = trim($_GET['medico'] ?? '');
		$fech = $_GET['fecha'] ?? '';
		if (Seguridad::medicoIdValido($med) && Seguridad::fechaValida($fech)) {
			$controlador->consultarHorasDisponibles($med, $fech);
		} else {
			echo '<option value="-1" selected>- Seleccione médico y fecha -</option>';
		}
	} elseif ($accion === 'verCita') {
		$controlador->verCita((int) ($_GET['numero'] ?? 0));
	} elseif ($accion === 'confirmarCancelar') {
		$numeroCita = (int) ($_GET['numero'] ?? $_POST['numero'] ?? 0);
		if ($numeroCita <= 0) {
			die('Numero de cita no valido.');
		}
		if (
			$_SERVER['REQUEST_METHOD'] === 'POST'
			&& !Seguridad::validarCsrf($_POST['_csrf'] ?? '')
		) {
			die('Sesion expirada. Recargue la pagina e intente de nuevo.');
		}
		$controlador->confirmarCancelarCita($numeroCita);
	} elseif ($accion === 'reporte') {
		$controlador->generarReporte((int) ($_GET['numero'] ?? 0));
	}

} else {
	$controlador->verPagina('vista/html/inicio.php');
}
