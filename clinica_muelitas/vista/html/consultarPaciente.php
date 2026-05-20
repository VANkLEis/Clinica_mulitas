
				<?php 
					if($result->num_rows>0) {
				 ?>

			<table class="tabla-resultados">
				<tr><th>Identificación</th> <th>Nombre</th> <th>Sexo</th></tr>


			<?php 
				$fila= $result->fetch_object();
			 ?>
				<tr>
					<td><?php echo Seguridad::esc($fila->PacIdentificacion); ?></td>
					<td><?php echo Seguridad::esc($fila->PacNombres . ' ' . $fila->PacApellidos); ?></td>
					<td><?php echo Seguridad::esc($fila->PacSexo); ?></td>
					<td>Ver</td>
				</tr>

			</table>

			<?php 
				}
				else{
			 ?>

			<p class="mensaje-vacio">El paciente no existe en la base de datos.</p>
			<input type="button" name="ingPaciente" value="Registrar paciente" id="ingPaciente" onclick="mostarFormulario()">

			 <?php 
			 	}
			  ?>
