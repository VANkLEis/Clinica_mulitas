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
					<td><a href="index.php?accion=verCita&amp;numero=<?php echo (int) $fila->CitNumero; ?>">Ver</a></td>
				</tr>
				<?php 
					}
				 ?>

			</table>

			<?php 
				}
				else{
			 ?>

			<p class="mensaje-vacio">El paciente no tiene citas asignadas.</p>

			 <?php 
			 	}
			  ?>
