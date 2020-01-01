function htmlEscape(str){
    return str.replace(/[&<>'"]/g,x=>'&#'+x.charCodeAt(0)+';')
}

function mostrarTabla(){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			mostrar(this);
		}
	};
	xhttp.open("GET", "../xml/Questions.xml", true);
	xhttp.send();
	function mostrar(xml){
		document.getElementById("vertabla").innerHTML='';//empty
		document.getElementById("vermensajes").innerHTML='';//empty
		var i;
		var xmlDoc = xml.responseXML;
		var tabla="<table border=1><thead><tr><th>Author</th><th>Enunciado</th><th>Respuesta Correcta</th></tr></thead>";
		var x = xmlDoc.documentElement.getElementsByTagName("assessmentItem");
		for (i = 0; i <x.length; i++) { 
			tabla += "<tr><td>" +
			x[i].getAttribute('author') +
			"</td><td>" +
			htmlEscape(x[i].getElementsByTagName('p')[0].childNodes[0].nodeValue) +
			"</td><td>" +
			htmlEscape(x[i].getElementsByTagName('value')[0].childNodes[0].nodeValue) +
			"</td></tr>";
		}
		tabla+="</table>";//append tabla
		document.getElementById('vertabla').innerHTML=tabla;
	}
}
$(document).ready(function(){
    $('#numpreguntas').html("<img src='../images/loading.gif' width='15' />");
	$('#numpersonas').html("<img src='../images/loading.gif' width='15' />");
	setInterval(function(){num_preguntas();}, 6000);
	setInterval(function(){num_personas();}, 7000);
	
	function num_preguntas(){
		$.ajax({
			type:"POST",
			url: "../xml/Questions.xml",
			dataType: "xml",
			cache:false,
			success: function(questions){
				var parcial = 0;
				var total = $(questions).find('assessmentItems').children().length;
				var autor = $('#email').val();
				$(questions).find('assessmentItem').each(function(){
					var autorpregunta=$(this).attr('author');
					if (autorpregunta==autor){
						parcial=parcial+1;
					}		
				});
				var texto=parcial+"/"+total;
				$('#numpreguntas').html(texto);
			}
		});
	}

	function num_personas(){
		$.ajax({
			type:"POST",
			url: "../xml/Counter.xml",
			dataType: "xml",
			cache:false,
			success: function(counter){
				var total = $(counter).find('usersonline').children().length;
				$('#numpersonas').html(total);
			}
		});
	}
});

