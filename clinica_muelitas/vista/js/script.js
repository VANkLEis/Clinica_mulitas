var anioActual = new Date().getFullYear();

function configurarDatepickerNacimiento() {
	if ($("#pacNacimiento").hasClass("hasDatepicker")) {
		$("#pacNacimiento").datepicker("destroy");
	}
	$("#pacNacimiento").val("");
	$("#pacNacimiento").datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		yearRange: "1940:2026",
		minDate: new Date(1940, 0, 1),
		maxDate: new Date(2026, 11, 31),
		defaultDate: new Date(1990, 0, 1)
	});
}

function configurarDatepickerCita() {
	if ($("#fecha").hasClass("hasDatepicker")) {
		$("#fecha").datepicker("destroy");
	}
	$("#fecha").datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		yearRange: anioActual + ":" + (anioActual + 10),
		minDate: 0,
		maxDate: null
	});
}

$(document).ready(function(){
	$("#asignarConsultar").click(function(){
		url= "index.php?accion=consultarPaciente&documento="+$("#asignarDocumento").val();
		$("#paciente").load(url);
	});

	$("#ingPaciente").click(function(){
		mostarFormulario();
	});

	$("#frmPaciente").dialog({
		autoOpen: false,
		height: 310,
		width: 400,
		modal: true,
		buttons: {
			"Insertar": insertarPaciente,
			"Cancelar": cancelar
		}
	});

	configurarDatepickerCita();

	$("#frmasignar").on("submit", function(e) {
		var hora = $("#hora").val();
		if (!hora || hora === "-1") {
			alert("Debe seleccionar una hora disponible.");
			e.preventDefault();
			return false;
		}
		if ($("#medico").val() === "-1") {
			alert("Debe seleccionar un médico.");
			e.preventDefault();
			return false;
		}
		if ($("#consultorio").val() === "0") {
			alert("Debe seleccionar un consultorio.");
			e.preventDefault();
			return false;
		}
	});
});


function mostarFormulario(){
	var documento = $("#asignarDocumento").val();
	$("#pacDocumento").attr("value", documento);
	configurarDatepickerNacimiento();
	$("#frmPaciente").dialog("open");
}

function insertarPaciente(){
	$(this).dialog("close");
	$.post("index.php?accion=ingresarpaciente", $("#agregarPaciente").serialize(), function(html) {
		$("#paciente").html(html);
	});
}

function cancelar(){
	$(this).dialog("close");
}

function cargarHoras(){
	if($("#medico").val()==="-1" || $("#fecha").val()===""){
		$("#hora").html("<option value='-1' selected>- Elija médico y fecha primero -</option>");
		return;
	}
	var queryString = "medico="+encodeURIComponent($("#medico").val())+"&fecha="+encodeURIComponent($("#fecha").val());
	var url = "index.php?accion=consultarHoras&"+queryString;
	$("#hora").load(url);
}

function seleccionarHora(){
	if ($("#medico").val()=="-1") {
		alert("Debe seleccionar un medico");
	}
	else if($("#fecha").val()==""){
		alert("Debe seleccionar una fecha");
	}
}
