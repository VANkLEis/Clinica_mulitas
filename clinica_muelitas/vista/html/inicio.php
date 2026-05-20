<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Clínica Muelitas — Inicio</title>
	<link rel="stylesheet" type="text/css" href="vista/css/estilos.css">
</head>
<body>
	<div id="contenedor">

		<?php $paginaActiva = 'inicio'; require_once __DIR__ . '/fragmentos/cabecera.php'; ?>

		<main id="contenido">
			<h2>Bienvenido</h2>
			<div class="bienvenida">
				<p class="intro">
					Desde aquí puede gestionar las citas de la clínica: registrar pacientes,
					programar consultas, revisar horarios y cancelar citas cuando sea necesario.
				</p>

				<div class="tarjetas-accion">
					<a class="tarjeta-accion" href="index.php?accion=asignar">
						<strong>Asignar cita</strong>
						<span>Agendar una nueva consulta para un paciente</span>
					</a>
					<a class="tarjeta-accion" href="index.php?accion=consultar">
						<strong>Consultar citas</strong>
						<span>Ver las citas programadas por documento</span>
					</a>
					<a class="tarjeta-accion" href="index.php?accion=cancelar">
						<strong>Cancelar cita</strong>
						<span>Anular una cita existente</span>
					</a>
				</div>
			</div>
		</main>

		<?php require_once __DIR__ . '/fragmentos/pie.php'; ?>
	</div>
</body>
</html>
