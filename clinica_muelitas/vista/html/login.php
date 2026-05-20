<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Clínica Muelitas — Acceso seguro</title>
	<link rel="stylesheet" type="text/css" href="vista/css/estilos.css">
</head>
<body class="pagina-login">
	<div class="login-contenedor">
		<div class="login-marca">
			<div class="logo-clinica logo-clinica--grande" aria-hidden="true">
				<?php include __DIR__ . '/../imagenes/logo.svg'; ?>
			</div>
			<h1>Clínica Muelitas</h1>
			<p>Sistema interno de citas — acceso autorizado</p>
		</div>

		<form class="login-formulario" method="post" action="index.php?accion=login" autocomplete="off">
			<?php echo Seguridad::campoCsrf(); ?>
			<h2>Iniciar sesión</h2>

			<?php if (!empty($mensajeLogin)) { ?>
				<p class="login-ok" role="status"><?php echo Seguridad::esc($mensajeLogin); ?></p>
			<?php } ?>

			<?php if (!empty($errorLogin)) { ?>
				<p class="login-error" role="alert"><?php echo Seguridad::esc($errorLogin); ?></p>
			<?php } ?>

			<label for="usuario">Usuario</label>
			<input type="text" id="usuario" name="usuario" required maxlength="50" autofocus>

			<label for="clave">Contraseña</label>
			<input type="password" id="clave" name="clave" required maxlength="128">

			<button type="submit">Entrar al sistema</button>
		</form>

		<?php if (Seguridad::registroPermitido()) { ?>
		<div class="login-opciones">
			<p>¿No tiene cuenta?</p>
			<a class="login-btn-secundario" href="index.php?accion=registro">Crear cuenta</a>
		</div>
		<?php } ?>

		<p class="login-aviso">Los datos de pacientes están protegidos. No comparta sus credenciales.</p>
	</div>
</body>
</html>
