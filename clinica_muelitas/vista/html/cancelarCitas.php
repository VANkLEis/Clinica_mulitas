				<?php 
					if($result->num_rows >0) {
				 ?>

			<table class="tabla-resultados">
				<tr><th>Número</th> <th>Fecha</th> <th>Hora</th></tr>


			<?php 
				while($fila= $result->fetch_object())
				{
			 ?>
				<tr>
					<td><?php echo (int) $fila->CitNumero; ?></td>
					<td><?php echo Seguridad::esc($fila->CitFecha); ?></td>
					<td><?php echo Seguridad::esc($fila->CitHora); ?></td>
					<td><a href="javascript:void(0)" class="link-cancelar-cita" data-numero="<?php echo (int) $fila->CitNumero; ?>">Cancelar</a></td>
				</tr>
				<?php 
					}
				 ?>

			</table>

			<?php 
				}
				else{
			 ?>

			<p class="mensaje-vacio">El paciente no tiene citas que se puedan cancelar.</p>

			 <?php 
			 	}
			  ?>
