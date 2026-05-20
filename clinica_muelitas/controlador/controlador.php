
<?php 

	class Controlador{

		public function verPagina($ruta){
			require_once $ruta;
		}


		public function agregarCita($doc,$med,$fec,$hor,$con){
			$cita = new Cita("null",$fec,$hor,$doc,$med,$con,"solicitada", "ninguna");
			$gestorCita = new GestorCita();
			$id= $gestorCita->agregarCita($cita);
			$result= $gestorCita->consultarCitaPorId($id);
			require_once "vista/html/confirmarCita.php";
		}

		public function consultarCitas($doc){
			$gestorCita= new GestorCita();
			$result= $gestorCita->consultarCitaPorDocumento	($doc);
			require_once "vista/html/consultarCitas.php";
		}

		public function cancelarCitas($doc){
			$gestorCita= new GestorCita();
			$result= $gestorCita->consultarCitaPorDocumento($doc);
			require_once "vista/html/cancelarCitas.php";
		}

		public function consultarPaciente($doc){
			$gestorCita= new GestorCita();
			$result= $gestorCita->consultarPaciente($doc);
			require_once "vista/html/consultarPaciente.php";
		}

		public function agregarPaciente($doc, $nom, $ape, $fech, $sex){
			$paciente = new Paciente($doc, $nom, $ape, $fech, $sex);
			$gestorCita = new GestorCita();
			$registro = $gestorCita->agregarPaciente($paciente);
			if($registro>0){
				echo "Paciente insertado con exito";
			}
			else{
				echo "Error al insertar el nuevo paciente";
			}
		}

		public function cargarAsignar(){
			$gestorCita = new GestorCita();
			$result = $gestorCita->consultarMedicos();
			$result2 = $gestorCita->consultarConsultorios();
			require_once "vista/html/asignar.php";
		}

		public function consultarHorasDisponibles($med, $fech){
			$gestorCita = new GestorCita();
			$result = $gestorCita->consultarHorasDisponibles($med, $fech);
			require_once "vista/html/consultarHoras.php";
		}

		public function verCita($cita){
			$gestorCita = new GestorCita();
			$result = $gestorCita->consultarCitaPorId($cita);
			require_once "vista/html/confirmarCita.php";
		}

		public function confirmarCancelarCita($cita){
			$gestorCita = new GestorCita();
			$registros = $gestorCita->cancelarCita($cita);
			if($registros>0){
				echo "La cita se ha cancelado con exito";
			}
			else{
				echo "Error al cancelar cita, intente de nuevo";
			}
		}

		public function generarReporte($numeroCita){
			while (ob_get_level()) {
				ob_end_clean();
			}

			$numeroCita = (int) $numeroCita;
			if ($numeroCita <= 0) {
				die("Número de cita inválido.");
			}

			$gestorCita = new GestorCita();
			$result = $gestorCita->consultarCitaPorId($numeroCita);
			if (!$result || $result->num_rows === 0) {
				die("No se encontró la cita solicitada.");
			}

			$fila = $result->fetch_object();

			ob_start();
			require "vista/html/reporteCita.php";
			$content = ob_get_clean();

			require_once "vista/pdf/html2pdf/html2pdf.class.php";

			try {
				$html2pdf = new HTML2PDF("P", "A4", "es");
				$html2pdf->pdf->SetDisplayMode("fullpage");
				$html2pdf->writeHTML($content);
				$html2pdf->Output("Cita_" . $numeroCita . ".pdf");
			} catch (HTML2PDF_exception $e) {
				die("Error al generar el PDF: " . $e->getMessage());
			}
		}



	}


 ?>