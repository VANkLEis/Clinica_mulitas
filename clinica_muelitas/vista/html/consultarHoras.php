<option value="-1" selected>- Seleccione la hora -</option>
<?php
	$hayHoras = false;
	while ($fila = $result->fetch_object()) {
		$hayHoras = true;
		$valorHora = (string) $fila->hora;
		$textoHora = strlen($valorHora) >= 5 ? substr($valorHora, 0, 5) : $valorHora;
?>
<option value="<?php echo Seguridad::esc($valorHora); ?>"><?php echo Seguridad::esc($textoHora); ?></option>
<?php
	}
	if (!$hayHoras) {
?>
<option value="-1" disabled>No hay horas disponibles ese día</option>
<?php } ?>
