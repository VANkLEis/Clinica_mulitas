<?php

	class Conexion{

		private $mysqli;
		private $sql;
		private $result;
		private $filasAfectadas;
		private $citaId;

		public function abrir(){
			$cfg = Seguridad::configuracion();
			$this->mysqli = new mysqli(
				$cfg['db_host'],
				$cfg['db_user'],
				$cfg['db_pass'],
				$cfg['db_name']
			);
			$this->mysqli->set_charset('utf8mb4');

			if($this->mysqli->connect_error){
				return null;
			}
			return $this->mysqli;
		}

		public function cerrar(){
			$this->mysqli->close();
		}

		public function consulta($sql){
			$this->sql= $sql;
			$this->result= $this->mysqli->query($this->sql);
			$this->filasAfectadas= $this->mysqli->affected_rows;
			$this->citaId= $this->mysqli->insert_id;
		}

		public function obtenerResult(){
			return $this->result;
		}

		public function obtenerFilasAfectadas(){
			return $this->filasAfectadas;
		}

		public function obtenerCitaId(){
			return $this->citaId;
		}

	}
