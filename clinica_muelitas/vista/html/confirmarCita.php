<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Clínica Muelitas — Detalle de cita</title>
	<link rel="stylesheet" type="text/css" href="vista/css/estilos.css">
</head>
<body>

	<div id="contenedor">

		<?php $paginaActiva = 'asignar'; require_once __DIR__ . '/fragmentos/cabecera.php'; ?>

		<main id="contenido">
			<?php $fila = $result->fetch_object(); ?>
			<h2>Información de la cita</h2>

			<table class="tabla-detalle">
				<tr class="fila-seccion">
					<th colspan="2">Datos del paciente</th>
				</tr>
				<tr>
					<td>Documento</td>
					<td><?php echo Seguridad::esc($fila->PacIdentificacion); ?></td>
				</tr>
				<tr>
					<td>Nombres</td>
					<td><?php echo Seguridad::esc($fila->PacNombres . ' ' . $fila->PacApellidos); ?></td>
				</tr>

				<tr class="fila-seccion">
					<th colspan="2">Datos del médico</th>
				</tr>
				<tr>
					<td>Documento</td>
					<td><?php echo Seguridad::esc($fila->MedIdentificacion); ?></td>
				</tr>
				<tr>
					<td>Nombre</td>
					<td><?php echo Seguridad::esc($fila->MedNombres . ' ' . $fila->MedApellidos); ?></td>
				</tr>

				<tr class="fila-seccion">
					<th colspan="2">Datos de la cita</th>
				</tr>
				<tr>
					<td>Fecha</td>
					<td><?php echo Seguridad::esc($fila->CitFecha); ?></td>
				</tr>
				<tr>
					<td>Hora</td>
					<td><?php echo Seguridad::esc($fila->CitHora); ?></td>
				</tr>
				<tr>
					<td>Consultorio</td>
					<td><?php echo Seguridad::esc($fila->ConNumero . ' — ' . $fila->ConNombre); ?></td>
				</tr>
				<tr>
					<td>Estado</td>
					<td><?php echo Seguridad::esc($fila->CitEstado); ?></td>
				</tr>
				<tr>
					<td>Observaciones</td>
					<td><?php echo Seguridad::esc($fila->CitObservaciones); ?></td>
				</tr>
				<tr>
					<td colspan="2" class="celda-accion">
						<a class="btn-enlace" href="index.php?accion=reporte&amp;numero=<?php echo (int) $fila->CitNumero; ?>" target="_blank" rel="noopener">Generar reporte PDF</a>
					</td>
				</tr>
			</table>
		</main>

		<?php require_once __DIR__ . '/fragmentos/pie.php'; ?>
	</div>

</body>
</html>
