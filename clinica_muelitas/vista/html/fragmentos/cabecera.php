<?php
if (!isset($paginaActiva)) {
	$paginaActiva = isset($_GET['accion']) ? $_GET['accion'] : 'inicio';
}

function claseMenuActivo($actual, $clave) {
	return ($actual === $clave) ? ' class="activo"' : '';
}

$nombreUsuario = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : '';
?>
		<header id="encabezado">
			<div class="marca">
				<div class="logo-clinica" aria-hidden="true">
					<?php include __DIR__ . '/../../imagenes/logo.svg'; ?>
				</div>
				<div class="marca-texto">
					<p class="nombre-clinica">Clínica Muelitas</p>
					<p class="tagline">Gestión de citas odontológicas</p>
				</div>
			</div>
			<?php if ($nombreUsuario !== '') { ?>
			<div class="sesion-usuario">
				<span class="sesion-nombre"><?php echo Seguridad::esc($nombreUsuario); ?></span>
				<a class="sesion-salir" href="index.php?accion=logout">Cerrar sesión</a>
			</div>
			<?php } ?>
		</header>

		<nav id="menu" aria-label="Menú principal">
			<ul>
				<li><a href="index.php"<?php echo claseMenuActivo($paginaActiva, 'inicio'); ?>>Inicio</a></li>
				<li><a href="index.php?accion=asignar"<?php echo claseMenuActivo($paginaActiva, 'asignar'); ?>>Asignar cita</a></li>
				<li><a href="index.php?accion=consultar"<?php echo claseMenuActivo($paginaActiva, 'consultar'); ?>>Consultar</a></li>
				<li><a href="index.php?accion=cancelar"<?php echo claseMenuActivo($paginaActiva, 'cancelar'); ?>>Cancelar</a></li>
			</ul>
		</nav>
