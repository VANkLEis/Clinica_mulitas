<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Clínica Muelitas — Cancelar cita</title>
	<meta name="csrf-token" content="<?php echo Seguridad::esc(Seguridad::obtenerTokenCsrf()); ?>">
	<link rel="stylesheet" type="text/css" href="vista/css/estilos.css">
	<script src="vista/jquery/jquery-1.11.3.min.js"></script>
	<script src="vista/js/script2.js?v=2.2"></script>
	<script>
	$(document).on("click", ".link-cancelar-cita", function(e) {
		e.preventDefault();
		confirmarCancelar($(this).data("numero"));
	});
	</script>
</head>
<body>
	<div id="contenedor">

		<?php $paginaActiva = 'cancelar'; require_once __DIR__ . '/fragmentos/cabecera.php'; ?>

		<main id="contenido">
			<h2>Cancelar Cita</h2>
				<table>
					<tr>
						<td>Cancelar Cita</td>
						<td><input type="text" name="cancelarDocumento" id="cancelarDocumento"></td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="button" onclick="cancelarConsultar()" id="cancelarConsultar" value="Cancelar">
						</td>
					</tr>
					<tr>
						<td colspan="2"><div id="paciente3"></div></td>
					</tr>
				</table>
		</main>

		<?php require_once __DIR__ . '/fragmentos/pie.php'; ?>
	</div>
</body>
</html>
