<?php
include_once '/pi-login/test-w.php';
// https://github.com/gboudreau/nest-api
require_once 'nest.class.php';

?>

<section class="capteurs">

	<h2> eedomus test </h2>
	<?php
	// définition des variables
	$api_user = $ee_apiuser  ; // a récupérer sur votre compte eedomus
	$api_secret = $ee_passwd; // a récupérer sur votre compte eedomus
	$periph_id = 156595; // code API du périphérique à mettre à jour (http://doc.eedomus.com/index.php/Code_API)

	// construction de l'URL de l'API
	$url = "http://api.eedomus.com/get?";
	$url .= "api_user=$api_user";
	$url .= "&api_secret=$api_secret";
	$url .= "&action=periph.list";


	// appel de l'API
	$result = file_get_contents($url);

	// on controle le résultat
	if (strpos($result, '"success": 1') == false)
	{
	  echo "Une erreur est survenue: [".$result."]";
	}
	else
	{
	  // Tout s'est bien passé, si nécessaire on peut exécuter un code spécifique ici
	}
	?>

	<p>eedomus url : pas affiché<?php // var_dump( $url ); ?></p>
	<pre><?php // print_r ( $result ) ; ?> </pre>
	
	<h2>Eedomus</h2>

	<?php
	
	function eedomus_url( $action, $user_id, $pw ) {
		/* 
		* eedomus_url( "&action=auth.test", $ee_apiuser, $ee_passwd ) construit
		* https://api.eedomus.com/get?api_user=USER&api_secret=SECRET&action=auth.test
		* eedomus_url( "&action=periph.list", $ee_apiuser, $ee_passwd ) construit
		* https://api.eedomus.com/get?api_user=USER&api_secret=SECRET&action=periph.list		
		* eedomus_url( "&action=periph.caract&periph_id=156595", $ee_apiuser, $ee_passwd ) construit
		* https://api.eedomus.com/get?api_user=USER&api_secret=SECRET&action=periph.caract&periph_id=156595
		*
		* ces 3 url renvoient bien une info en Json, en provoenance de l'eedomus dans un navigateur
		*/
		
		$eedomus_get = "https://api.eedomus.com/get?api_user=" ;
		$eedomus_get .= $user_id ;
		$eedomus_get .= "&api_secret=" ;
		$eedomus_get .= $pw ;
		$eedomus_get .= $action ;

		return $eedomus_get ;
	}

	$ee_test = eedomus_url( "&action=auth.test", $ee_apiuser, $ee_passwd ) ; 
	$ee_list = eedomus_url( "&action=periph.list", $ee_apiuser, $ee_passwd ) ; 
	$ee_156595 = eedomus_url( "&action=periph.caract&periph_id=156595", $ee_apiuser, $ee_passwd ) ; 

	function ald_decode_french( $string ) {
		
		$contents = utf8_encode( $string ); 
		$results = json_decode( $contents ); // si json_decode( $contents, TRUE ); on obtient un array
		return $results ;
		
	}

	
	// json_decode avec TRUE decode et transforme en array	
	?>

	<pre><?php // print_r ( file_get_contents( $ee_list ) ) ; ?> </pre>
	
	<p>eedomus connexion : <?php var_dump( json_decode( file_get_contents( $ee_test ), true ) ); ?></p>
	<pre><?php print_r ( json_decode( file_get_contents( $ee_test ), true ) ); ?> </pre>

	<p>eedomus liste des périphériques : <?php // var_dump( ald_decode_french( file_get_contents( $ee_list ) )  ); ?></p>
	<pre><?php print_r ( ald_decode_french( file_get_contents( $ee_list ) ) ); ?> </pre>

	<p>eedomus valeur : <?php var_dump( json_decode( file_get_contents( $ee_156595 ), true ) ); ?></p>
	<pre><?php print_r ( json_decode( file_get_contents( $ee_156595 ), true ) ); ?> </pre>
	
	
	<h2>Nest thermostat</h2>
	<?php
	// see https://github.com/gboudreau/nest-api/blob/master/examples.php
	// Your Nest username and password.
	$username = $nest_user ;
	$password = $nest_pw ;

	// The timezone you're in.
	// See http://php.net/manual/en/timezones.php for the possible values.
	date_default_timezone_set('Europe/Paris');

	// Here's how to use this class:
	$nest = new Nest($username, $password);

	// Location information
	$locations = $nest->getUserLocations();

	// Devices list (thermostats)
	$devices_serials = $nest->getDevices();
	
	// Device information
	$infos = $nest->getDeviceInfo($devices_serials[0]);

	// Last 10 days energy report
	$energy_report = $nest->getEnergyLatest();

	
	function jlog($json) {
		echo json_encode($json, JSON_PRETTY_PRINT) . "\r\n";
	}	
	?>

	<p>Location information : <?php jlog($locations); ?></p>
	<p>Devices list (thermostats) : <?php jlog($devices_serials); ?></p>
	<p>Device information : <?php jlog($infos); ?></p>	
	<h4>température : <?php printf("%.02f °%s\n", $infos->current_state->temperature, $infos->scale); ?></h4>	
	<p>Last 10 days energy report : pas mis pour alléger la page. <?php // jlog($energy_report); ?></p>
	<h4>température et humidité ext  : <?php printf("%.02f °%s\n", $locations[0]->outside_temperature, $infos->scale); ?>, <?php printf("%.02f %s\n", $locations[0]->outside_humidity, "%"); ?></h4>	

	
	<?php
	/* essais
	
	$nest_away_url = "https://developer-api.nest.com/structures/" ;
	$nest_away_url .= $nest_structure ;
	$nest_away_url .= '/away' ;
	$nest_away_url = '?auth=' . $nest_auth ;

	$nest_away_string = file_get_contents( $nest_away_url ) ;
	$nest_away = json_decode($nest_away_string);

	// https://firebase-apiserver17-tah01-iad01.dapi.production.nest.com:9553/devices/thermostats/?auth=c.MeQYQ8pWejP2gAI2t0M1mZeCAP6wSUJUexybj279BXK5kjwxeL6ejdFkRNcYZqj1grrI0iRyKgw28LSu3q1GRAh9KxQPKogU9jTkUmwXxkWr5uAk4h13P6pvoASkVaktGQY6xiyFVmfnCs3a
	//$nest_data = 'https://developer-api.nest.com/devices/thermostats/?auth=' ;
	$nest_data = 'https://firebase-apiserver17-tah01-iad01.dapi.production.nest.com:9553/devices/thermostats/?auth=' ;
	$nest_data .= $nest_auth ;
	$nest_json_string = file_get_contents( $nest_data );
	$nest_parsed_json = json_decode( $nest_json_string );
	*/
	?>
	
	
	
	<h3>Wunderground</h3>
	<?php

	// http://stackoverflow.com/questions/20044579/how-to-get-a-value-from-wunderground-json
	$w_url = "https://api.wunderground.com/api/" . $w_apikey . "/conditions/q/pws:" . $w_passwd .".json" ;
	$json_string = file_get_contents( $w_url );
	$parsed_json = json_decode($json_string);
	$location = $parsed_json->{'current_observation'}->{'display_location'}->{'city'};
	$temp_c = $parsed_json->{'current_observation'}->{'temp_c'};
	$humidity = $parsed_json->{'current_observation'}->{'relative_humidity'};
	$wind_gust_km = $parsed_json->{'current_observation'}->{'wind_gust_kph'};
	$forecast_url = $parsed_json->{'current_observation'}->{'forecast_url'};
	
	echo "A ${location} : ${temp_c} °C, ${humidity} HR, rafales à ${wind_gust_km} km/h - <a target='_blank' href='${forecast_url}'>prévisions</a> ou <a target='_blank' href='http://wttr.in/ploneour-lanvern'>wttr mode txt</a>. \n";

	?>
	
	<h3>vieux EEdomus</h3>

	<?php include 'eedomus.php';?>

	<!-- Eedomus data -->
	<?php
	$eedomus_sensor_id = array(
		'temp_couloir' 	=> 166280,
		'temp_bureau' 	=> 450118,			
		'water' 		=> 450680,
		'temp_garage' 	=> 450689,
		'temp_ext' 		=> 450690,
		'temp_congel' 	=> 450691,
		'chaudiere' 	=> 450692		
	);
		
	foreach ( $eedomus_sensor_id as $key => $val ) {
		
		if ( ( $key == 'chaudiere' ) || ( $key == 'water' ) ) {
			
			$data[ $key ] = sprintf('%s', al_get_eedomus_value( $val , 'last_value' ) ) ;
							
		} else {
			// round values to 1 digit 
			$data[ $key ] = sprintf('%0.1f', al_get_eedomus_value( $val , 'last_value' ) ) ;
		}
	}
	?>

	<p>Couloir : <?php echo $data[ 'temp_couloir' ] ; ?> °C -- salle de bain : <?php echo $data[ 'temp_bureau' ] ; ?> °C -- Garage : <?php echo $data[ 'temp_garage' ] ; ?> °C Dehors : <?php echo $data[ 'temp_ext' ] ; ?> °C -- Congélateur : <?php echo $data[ 'temp_congel' ] ; ?> °C</p>
	<p>Temps chaudière : <?php echo $data[ 'chaudiere' ] ; ?> s. -- Niveau d'eau : <?php echo $data[ 'water' ] ; ?> (capacitance) </p>
	
</section>	
<!-- div container ENDS here -->	
<?php include_once ROOT . '/footer-2016.php';	?>