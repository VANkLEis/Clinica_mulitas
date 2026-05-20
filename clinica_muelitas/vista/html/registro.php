<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Clínica Muelitas — Crear cuenta</title>
	<link rel="stylesheet" type="text/css" href="vista/css/estilos.css">
</head>
<body class="pagina-login">
	<div class="login-contenedor">
		<div class="login-marca">
			<div class="logo-clinica logo-clinica--grande" aria-hidden="true">
				<?php include __DIR__ . '/../imagenes/logo.svg'; ?>
			</div>
			<h1>Clínica Muelitas</h1>
			<p><?php echo !empty($configuracionInicial) ? 'Configure la primera cuenta del sistema' : 'Registro de nuevo usuario'; ?></p>
		</div>

		<?php if (!Seguridad::registroPermitido()) { ?>

		<p class="login-error" role="alert"><?php echo Seguridad::esc($errorRegistro); ?></p>
		<p class="login-opciones"><a class="login-link" href="index.php?accion=login">Volver a iniciar sesión</a></p>

		<?php } else { ?>

		<form class="login-formulario" method="post" action="index.php?accion=registro" autocomplete="off">
			<?php echo Seguridad::campoCsrf(); ?>
			<h2>Crear cuenta</h2>

			<?php if (!empty($configuracionInicial)) { ?>
				<p class="login-info">Es la primera cuenta del sistema. Complete los datos y podrá entrar de inmediato.</p>
			<?php } else { ?>
				<p class="login-info">Registre su usuario para acceder al sistema de citas. Use una contraseña segura.</p>
			<?php } ?>

			<?php if (!empty($errorRegistro)) { ?>
				<p class="login-error" role="alert"><?php echo Seguridad::esc($errorRegistro); ?></p>
			<?php } ?>

			<label for="nombre">Nombre completo</label>
			<input type="text" id="nombre" name="nombre" required maxlength="100" autofocus placeholder="Ej. Ana Recepción" value="<?php echo Seguridad::esc($_POST['nombre'] ?? ''); ?>">

			<label for="usuario">Usuario de acceso</label>
			<input type="text" id="usuario" name="usuario" required maxlength="50" pattern="[a-zA-Z0-9._-]{4,50}" value="<?php echo Seguridad::esc($_POST['usuario'] ?? ''); ?>">

			<label for="clave">Contraseña</label>
			<input type="password" id="clave" name="clave" required minlength="8" maxlength="128" placeholder="Mínimo 8 caracteres">

			<label for="clave_confirmar">Confirmar contraseña</label>
			<input type="password" id="clave_confirmar" name="clave_confirmar" required minlength="8" maxlength="128">

			<button type="submit">Crear cuenta y entrar</button>
		</form>

		<div class="login-opciones">
			<p>¿Ya tiene cuenta?</p>
			<a class="login-link" href="index.php?accion=login">Iniciar sesión</a>
		</div>

		<?php } ?>
	</div>
</body>
</html>
