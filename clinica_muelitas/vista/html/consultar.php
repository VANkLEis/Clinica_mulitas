<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Clínica Muelitas — Consultar citas</title>
	<meta name="csrf-token" content="<?php echo Seguridad::esc(Seguridad::obtenerTokenCsrf()); ?>">
	<link rel="stylesheet" type="text/css" href="vista/css/estilos.css">
	<script src="vista/jquery/jquery-1.11.3.min.js"></script>
	<script src="vista/js/script2.js?v=2.2"></script>
</head>
<body>
	<div id="contenedor">

		<?php $paginaActiva = 'consultar'; require_once __DIR__ . '/fragmentos/cabecera.php'; ?>

		<main id="contenido">
			<h2>Consultar Cita</h2>
			
			<form action="index.php?accion=consultarCita" method="post" id="frmConsultar">
				<table>
					<tr>
						<td>Documento del paciente</td>
						<td><input type="text" name="consultarDocumento" id="consultarDocumento"></td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="button" value="consultar" onclick="consultarConsultar()">
						</td>
					</tr>
					<tr>
						<td colspan="2"><div id="paciente2"></div></td>
					</tr>
				</table>
			</form>
		</main>

		<?php require_once __DIR__ . '/fragmentos/pie.php'; ?>
	</div>
</body>
</html>
