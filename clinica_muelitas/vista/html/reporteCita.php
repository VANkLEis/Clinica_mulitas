<style type="text/css">
	.titulo { text-align: center; color: #1a7a78; font-size: 18pt; font-weight: bold; }
	.subtitulo { text-align: center; color: #555; font-size: 10pt; margin-bottom: 8mm; }
	.tabla { width: 100%; border-collapse: collapse; font-size: 10pt; }
	.tabla th { background-color: #1a7a78; color: #fff; padding: 3mm; text-align: left; }
	.tabla td { border-bottom: 1px solid #d4e4e3; padding: 2.5mm 3mm; }
	.etiqueta { width: 38%; font-weight: bold; color: #2a3b3a; }
</style>
<page>
	<p class="titulo">Clínica Muelitas</p>
	<p class="subtitulo">Comprobante de cita odontológica</p>

	<table class="tabla">
		<tr><th colspan="2">Datos del paciente</th></tr>
		<tr>
			<td class="etiqueta">Documento</td>
			<td><?php echo htmlspecialchars($fila->PacIdentificacion); ?></td>
		</tr>
		<tr>
			<td class="etiqueta">Nombre</td>
			<td><?php echo htmlspecialchars($fila->PacNombres . ' ' . $fila->PacApellidos); ?></td>
		</tr>

		<tr><th colspan="2">Datos del médico</th></tr>
		<tr>
			<td class="etiqueta">Documento</td>
			<td><?php echo htmlspecialchars($fila->MedIdentificacion); ?></td>
		</tr>
		<tr>
			<td class="etiqueta">Nombre</td>
			<td><?php echo htmlspecialchars($fila->MedNombres . ' ' . $fila->MedApellidos); ?></td>
		</tr>

		<tr><th colspan="2">Datos de la cita</th></tr>
		<tr>
			<td class="etiqueta">Número de cita</td>
			<td><?php echo htmlspecialchars($fila->CitNumero); ?></td>
		</tr>
		<tr>
			<td class="etiqueta">Fecha</td>
			<td><?php echo htmlspecialchars($fila->CitFecha); ?></td>
		</tr>
		<tr>
			<td class="etiqueta">Hora</td>
			<td><?php echo htmlspecialchars($fila->CitHora); ?></td>
		</tr>
		<tr>
			<td class="etiqueta">Consultorio</td>
			<td><?php echo htmlspecialchars($fila->ConNumero . ' — ' . $fila->ConNombre); ?></td>
		</tr>
		<tr>
			<td class="etiqueta">Estado</td>
			<td><?php echo htmlspecialchars($fila->CitEstado); ?></td>
		</tr>
		<tr>
			<td class="etiqueta">Observaciones</td>
			<td><?php echo htmlspecialchars($fila->CitObservaciones); ?></td>
		</tr>
	</table>
</page>
