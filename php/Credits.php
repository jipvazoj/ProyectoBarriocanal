<?php include '../php/Menus.php' ?>
<!DOCTYPE html>
<html>
<head>
  <?php include '../html/Head.html'?>
  <?php 
	define("API_OWM","2c166a145ac561fb98d039878d560c06");
	$ipcliente=$_SERVER['REMOTE_ADDR'];
	$datoscliente = json_decode(file_get_contents('http://www.geoplugin.net/json.gp?ip='.$ipcliente),true);
    $realIP = file_get_contents("http://ipecho.net/plain");
	$datosservidor = json_decode(file_get_contents('http://ipinfo.io/'.$realIP.'/geo'),true);
	$loc = explode(",", $datosservidor['loc']);
	$latitudserver = $loc[0];
	$longitudserver=$loc[1];
?>
<link rel="icon" type="image/png" href="../images/icon/favicon-32x32.png" sizes="32x32" />
	<script src="../js/jquery-3.4.1.min.js"></script>
	<script src="../js/ShowMap.js"></script>
	<script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>
	<link rel="stylesheet" type="text/css" href="../styles/Credits.css" media="screen" />
	<link rel="stylesheet" href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css" type="text/css">
	
</head>
<body onload="mapa(<?php echo $datoscliente['geoplugin_longitude'];?>, <?php echo $datoscliente['geoplugin_latitude'];?> );">
  <section style='overflow-y:scroll;' class="main" id="s1">
    <div>
		<input class="spoilerbutton" type="button" value="Mostrar desarrolladores" onclick="this.value=this.value=='Mostrar desarrolladores'?'Ocultar desarrolladores':'Mostrar desarrolladores';">
		<div class="spoiler"><div>
				<h1>DATOS DE LOS DESARROLLADORES</h1><br>
				<h2>Julen Suárez Ventosa (Computadores) - Ignacio Barriocanal Fernández (Computación)<br>
				<img align="center" src="../images/steph.png" height="80" width="120" /><img align="center" src="../images/klay.png" height="80" width="120" /><br>
					<a href="mailto:jsuarez017@ikasle.ehu.es">jsuarez017@ikasle.ehu.es</a> - <a href="mailto:ibarriocanal003@ikasle.ehu.es">ibarriocanal003@ikasle.ehu.es</a>
				</h2>
		</div></div>
		<br>		
		<input class="spoilerbutton" type="button" value="Mostrar tu ubicación" onclick="this.value=this.value=='Mostrar tu ubicación'?'Ocultar tu ubicación':'Mostrar tu ubicación';">
		<div class="spoiler"><div>
		<?php
			echo "<h2>Tu dirección IP se encuentra ubicada en:<br>";
			echo $datoscliente['geoplugin_city'].", ".$datoscliente['geoplugin_region'].", ".$datoscliente['geoplugin_countryName']."<br>";
			echo "Latitud: ".$datoscliente['geoplugin_latitude']." <br> Longitud: ".$datoscliente['geoplugin_longitude']."</h2>";
			echo "<br>";
		?>
		<div id="map" class="map">
		    <div id="popup" class="ol-popup">
                <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                <div id="popup-content"><b>Cliente</b><br>Esta es tu ubicación.</div>
            </div>
        </div>
	    <?php
			echo "<h2>La dirección IP del servidor se encuentra ubicada en:<br>";
			echo $datosservidor['city'].", ".$datosservidor['region'].", ".$datosservidor['country']."<br>";
			echo "Latitud: ".$latitudserver." <br> Longitud: ".$longitudserver."</h2>";
			echo "<br>";
		?>
		</div></div>
		<br>
		<input class="spoilerbutton" type="button" value="Mostrar previsión del tiempo en tu ubicación" onclick="this.value=this.value=='Mostrar previsión del tiempo en tu ubicación'?'Ocultar previsión del tiempo en tu ubicación':'Mostrar previsión del tiempo en tu ubicación';">
		<div class="spoiler"><div>
		<?php
			function k_to_c($temp) {
				if ( !is_numeric($temp) ) { return false; }
				return round(($temp - 273.15));
			}		
			$datostiempo = json_decode(file_get_contents("https://api.openweathermap.org/data/2.5/forecast?daily&lat=".$datoscliente['geoplugin_latitude']."&lon=".$datoscliente['geoplugin_longitude']."&APPID=".API_OWM."&lang=es&cnt=40"),true);
			echo "<h2>Previsión para los próximos 5 días en ".$datoscliente['geoplugin_city'].", ".$datoscliente['geoplugin_region'].", ".$datoscliente['geoplugin_countryName']."</h2>";
			echo "* Intervalos cada 3 horas.<br><br>";
			echo"<div style='width:50%;height:500px;overflow-y:scroll;margin:auto;'>
								<table>
									<tr>
										<th>DIA-HORA PREVISIÓN</th>
										<th>TEMPERATURA MÍNIMA/MÁXIMA</th>
										<th>DESCRIPCIÓN PREVISIÓN</th>
										<th>ICONO</th>
									</tr>
			";
			foreach($datostiempo['list'] as $dia){
				$temp_min = k_to_c($dia['main']['temp_min']);
				$temp_max = k_to_c($dia['main']['temp_max']);
				echo"<tr>
						<td>".$dia['dt_txt']."</td>
						<td>".$temp_min."/".$temp_max."</td>
						<td>".$dia['weather'][0]['description']."</td>
						<td style='text-align:center;'> <img src='https://openweathermap.org/img/w/".$dia['weather'][0]['icon'].".png' height='50'/></td>
					</tr>
				";
			}
			echo"</table></div>";			
		?>
		</div></div>
    </div>
  </section>
  <?php include '../html/Footer.html' ?>
</body>
</html>
