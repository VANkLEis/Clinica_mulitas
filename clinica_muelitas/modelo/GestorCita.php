<?php 

	class GestorCita{

		private $conexion;
		private $mysqli;

		public function __construct(){
			$this->conexion = new Conexion();
			$this->mysqli = $this->conexion->abrir();
		}

		public function __destruct(){
			$this->conexion->cerrar();
		}

		public function agregarCita($cita){
    try {
        $fecha= $cita->obtenerFecha();
        $hora= $cita->obtenerHora();
        $paciente= $cita->obtenerPaciente();
        $medico= $cita->obtenerMedico();
        $consultorio= (int)$cita->obtenerConsultorio(); 
        $estado= 'solicitada';
        $observaciones= $cita->obtenerObservaciones();

        $sql= "INSERT INTO citas 
        (CitFecha,CitHora,CitPaciente,CitMedico,CitConsultorio,CitEstado,CitObservaciones)
        VALUES (?,?,?,?,?,?,?)";

        $stmt = $this->mysqli->prepare($sql);

        if (!$stmt) {
            die("Error en prepare: " . $this->mysqli->error);
        }

        $stmt->bind_param(
            "ssssiss",
            $fecha,
            $hora,
            $paciente,
            $medico,
            $consultorio,
            $estado,
            $observaciones
        );

        if (!$stmt->execute()) {
            die("Error en execute: " . $stmt->error);
        }

        $citaId = $this->mysqli->insert_id;

        $stmt->close();
        return $citaId;

    } catch (Exception $e) {
        die("Error general: " . $e->getMessage());
    }
}

		public function consultarCitaPorId($id){
			$sql= "SELECT pacientes.*, medicos.*, consultorios.*, citas.*
			 FROM pacientes, medicos, consultorios, citas 
			 WHERE citas.CitPaciente= pacientes.PacIdentificacion 
			 AND citas.CitMedico= medicos.MedIdentificacion 
			 AND citas.CitConsultorio= consultorios.ConNumero
			 AND citas.CitNumero= ?";
			$stmt = $this->mysqli->prepare($sql);
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			return $result;
		}

		public function consultarCitaPorDocumento($doc){
			$sql= "SELECT citas.*, pacientes.PacNombres, pacientes.PacApellidos 
			FROM citas 
			INNER JOIN pacientes ON citas.CitPaciente = pacientes.PacIdentificacion
			WHERE citas.CitPaciente= ?
			AND citas.CitEstado = 'solicitada' ";
			$stmt = $this->mysqli->prepare($sql);
			$stmt->bind_param("s", $doc);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			return $result;
		}


		public function consultarPaciente($doc){
			$sql= "SELECT * FROM pacientes WHERE PacIdentificacion = ? ";
			$stmt = $this->mysqli->prepare($sql);
			$stmt->bind_param("s", $doc);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			return $result;
		}

		public function agregarPaciente($paciente){
			$identificacion = $paciente->obtenerIdentificacion();
			$nombres = $paciente->obtenerNombres();
			$apellidos = $paciente->obtenerApellidos();
			$fechaNacimiento = $paciente->obtenerFechaNacimiento();
			$sexo = $paciente->obtenerSexo();
			$sql= "INSERT INTO pacientes VALUES (?, ?, ?, ?, ?)";
			$stmt = $this->mysqli->prepare($sql);
			$stmt->bind_param("sssss", $identificacion, $nombres, $apellidos, $fechaNacimiento, $sexo);
			$stmt->execute();
			$filasAfectadas = $stmt->affected_rows;
			$stmt->close();
			return $filasAfectadas;
		}

		public function consultarMedicos(){
			$sql= "SELECT * FROM medicos";
			$result = $this->mysqli->query($sql);
			return $result;
		}

		public function consultarConsultorios(){
			$sql= "SELECT * FROM consultorios";
			$result = $this->mysqli->query($sql);
			return $result;
		}

		public function consultarHorasDisponibles($med, $fech){
			$sql= "SELECT hora FROM horas WHERE hora NOT IN(SELECT CitHora FROM citas WHERE CitMedico = ? AND CitFecha = ? AND CitEstado = 'solicitada')";
			$stmt = $this->mysqli->prepare($sql);
			$stmt->bind_param("ss", $med, $fech);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			return $result;
		}

		public function cancelarCita($cita){
			$sql= "UPDATE citas SET CitEstado='cancelada' WHERE CitNumero = ?";
			$stmt = $this->mysqli->prepare($sql);
			$stmt->bind_param("i", $cita);
			$stmt->execute();
			$filasAfectadas = $stmt->affected_rows;
			$stmt->close();
			return $filasAfectadas;
		}

	}

 ?>