function consultarConsultar(){
	var url = "index.php?accion=consultarCita&consultarDocumento="+encodeURIComponent($("#consultarDocumento").val());
	$("#paciente2").load(url);
}

function cancelarConsultar(){
	var url = "index.php?accion=cancelarCita&cancelarDocumento="+encodeURIComponent($("#cancelarDocumento").val());
	$("#paciente3").load(url);
}

function confirmarCancelar(numero){
	if (!confirm("Esta seguro que desea cancelar la cita "+numero+"?")) {
		return false;
	}
	$.ajax({
		url: "index.php",
		type: "GET",
		data: {
			accion: "confirmarCancelar",
			numero: numero
		},
		cache: false,
		success: function(mensaje) {
			alert(mensaje);
			cancelarConsultar();
		},
		error: function() {
			alert("No se pudo cancelar la cita. Recargue la pagina e intente de nuevo.");
		}
	});
	return false;
}
