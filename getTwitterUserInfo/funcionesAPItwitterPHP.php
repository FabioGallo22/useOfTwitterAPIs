<?php
	
/* Esta funcion dado un id de usuario retorna una cadena que contiene los últimos 200 tweets, cada uno sepadado por un punto. */	
function obtenerUltimosTweetsUsuario($userId){	
	$cadenaAretornar = "";
	require_once('TwitterAPIExchange.php');
	
	/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
	$settings = array(
		'oauth_access_token' 		=> "YOUR_oauth_access_token",
		'oauth_access_token_secret' => "YOUR_oauth_access_token_secret",
		'consumer_key' 				=> "YOUR_consumer_key",
		'consumer_secret' 			=> "YOUR_consumer_secret"
	);
	
	
	// --------------------------------------
	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	$getfield = '?user_id=' . $userId . "&count=200";     // Info sobre posibles opciones para el campo getField 		https://github.com/J7mbo/twitter-api-php/wiki/Get-a-user's-tweets
	$requestMethod = 'GET';

	$twitter = new TwitterAPIExchange($settings);
	$response = $twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest();
		
		
	$twitter_feed = json_decode($response, true);
	 
	// check for errors
	if(isset($twitter_feed['errors'])) {
		foreach($twitter_feed['errors'] as $error) {
			echo "(".$error['code'].") ".$error['message']."\n";
		}
	} 
	else{
		// Loop thru and spit out the text of each tweet returned
//		$i = 1;
		foreach($twitter_feed as $tweet) {
//			echo "\n\t ($i) Texto: " .  $tweet['text'];
			$cadenaAretornar = $cadenaAretornar . $tweet['text'] . ".";
//			echo "\n\t      Created at: " . $tweet['created_at'];
//			$i += 1;
		}
	}
	return $cadenaAretornar;
}






/* Esta funcion dado un id de usuario retorna un array que contiene los últimos 200 tweets */	
function obtenerUltimosTweetsUsuarioEnArray($userId){	
	$arrayAretornar = Array();
	require_once('TwitterAPIExchange.php');
	
	/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
	$settings = array(
		'oauth_access_token' 		=> "YOUR_oauth_access_token",
		'oauth_access_token_secret' => "YOUR_oauth_access_token_secret",
		'consumer_key' 				=> "YOUR_consumer_key",
		'consumer_secret' 			=> "YOUR_consumer_secret"
	);
	

	// --------------------------------------
	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	$getfield = '?user_id=' . $userId . "&count=200";     // Info sobre posibles opciones para el campo getField 		https://github.com/J7mbo/twitter-api-php/wiki/Get-a-user's-tweets
	$requestMethod = 'GET';

	$twitter = new TwitterAPIExchange($settings);
	$response = $twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest();
		
		
	$twitter_feed = json_decode($response, true);
	 
	// check for errors
	if(isset($twitter_feed['errors'])) {
		foreach($twitter_feed['errors'] as $error) {
			echo "(".$error['code'].") ".$error['message']."\n";
			return null;
		}
	} 
	else{
		// Loop thru and spit out the text of each tweet returned
		$i = 0;
		foreach($twitter_feed as $tweet) {
//			echo "\n\t ($i) Texto: " .  $tweet['text'];
			$arrayAretornar[$i] = $tweet['text']; 			
//			echo "\n\t      Created at: " . $tweet['created_at'];
			$i += 1;
		}
	}
	return $arrayAretornar;
}











/* Esta funcion dado un id de usuario retorna un array que contiene dos posiciones: 
	[0]: '0'/'1', si es '0' indica que hubo un error, si es '1' indica que se pudo acceder a los tweets del usuario.
	[1]: contiene el error o la lista de [0]fecha-[1]tweet (200) según sea el caso (depende del valor de [0])

	______________________________________________________
	Ejemplo de una salida cuando no hay error:
	Array
	(
		[0] => 1
		[1] => Array
			(
				[0] => Array
					(
						[0] => 2018-08-18 15:54:54
						[1] => RT @Sanicat_EU: Today is International #HomelessAnimalsDay ΓÇô so many animals are in need of a loving home. If youΓÇÖre considering adopting oΓÇª
					)

				[1] => Array
					(
						[0] => 2018-08-18 15:54:43
						[1] => RT @HeyWhatDay: It's International Homeless Animals Day!  #InternationalHomelessAnimalsDay #NationalHomelessAnimalsDay #HomelessAnimalsDay hΓÇª
					)
				...
				[199] => Array
                (
                    [0] => 2018-08-16 16:27:22
                    [1] => RT @GeorgeBaileyDog: When I go to work with Dad it is utterly exhausting. Taking a little snoozle break while I cuddle my Chicken. https://ΓÇª
                )

			)
	)
	
	______________________________________________________
	Ejemplo de salida cuando hay error porque el usuario no existe/tweets privados (para ambos errores el mensaje es el mismo)
	Array
	(
		[0] => 0
		[1] =>  request: /1.1/statuses/user_timeline.json ---  error: Not authorized. ---
	)
*/	

function obtenerUltimosTweetsUsuarioEnArrayConFechas($userId){	
	$arrayAretornar = Array();
	require_once('TwitterAPIExchange.php');
	
	/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
	$settings = array(
		'oauth_access_token' 		=> "YOUR_oauth_access_token",
		'oauth_access_token_secret' => "YOUR_oauth_access_token_secret",
		'consumer_key' 				=> "YOUR_consumer_key",
		'consumer_secret' 			=> "YOUR_consumer_secret"
	);
	

	// --------------------------------------
	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	$getfield = '?user_id=' . $userId . "&count=200";     // Info sobre posibles opciones para el campo getField 		https://github.com/J7mbo/twitter-api-php/wiki/Get-a-user's-tweets
	$requestMethod = 'GET';

	$twitter = new TwitterAPIExchange($settings);
	$response = $twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest();
			
	$twitter_feed = json_decode($response, true);
	 
	// check for errors
	if(isset($twitter_feed['errors']) || isset($twitter_feed['error'])) {
		$mensajeError = "";
		foreach($twitter_feed as $key=>$value) {
			$mensajeError = $mensajeError ." $key: $value --- ";
		}
		// se guarda el error en el array de salida.
		$arrayAretornar['0'] = '0';
		$arrayAretornar['1'] = $mensajeError;
	}
	else{
		// Loop thru and spit out the text of each tweet returned
		$i = 0;
		$arrayTweets = Array();
		foreach($twitter_feed as $tweet) {
			// Se formatea la hora para que coincida con el formato de la BD
			$arregloCreatedAt = (" ", $tweet['created_at']); // Thu Jan 23 05:00:07 +0000 2014
			$fechaCreatedAt = date_create_from_format('j-M-Y', $arregloCreatedAt[2] . "-" . $arregloCreatedAt[1] . "-" . $arregloCreatedAt[5]);
			$fechaCreatedAt = date_format($fechaCreatedAt, 'Y-m-d') . " " . $arregloCreatedAt[3]; // lo ultimo es la hora

			$arrayTweets[$i] = Array($fechaCreatedAt, $tweet['text']); 		
			$i += 1;
		}
		// se guarda todo en el array de salida
		$arrayAretornar['0'] = '1';
		$arrayAretornar['1'] = $arrayTweets;
		
	}
	return $arrayAretornar;
}

/* Esta funcion dado un id de usuario retorna un array que contiene los últimos 200 tweets y sus fechas. 
	Cada posicion del arreglo principal contiene otro arreglo con dos posiciones: la primera es la fecha y la segunda el tweet.
	Ejemplo de una salida:
	Array
	(
		[0] => Array
			(
				[0] => 2018-08-17 17:31:22
				[1] => RT @LolitatheDiva: Just got a donation from a wonderful caring person who has helped us before!!	People like that who understand and believΓÇª
			)

		[1] => Array
			(
				[0] => 2018-08-17 17:19:58
				[1] => RT @alcademics: Portland Cider Company in Oregon offers "apple recycling" of your backyard apples to make "community cider" where all profiΓÇª
			)
		...
		
		[199] => Array
        (
            [0] => 2018-08-15 16:05:17
            [1] => RT @SpotMagazine: Heads up! Health Advisory Issued for Toxic Algae in Willamette River https://t.co/tlgewrn7Ve https://t.co/UVGGrxo416
        )
*/
function obtenerUltimosTweetsUsuarioEnArrayConFechasVieja($userId){	
	$arrayAretornar = Array();
	require_once('TwitterAPIExchange.php');
	
	/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
	$settings = array(
		'oauth_access_token' 		=> "YOUR_oauth_access_token",
		'oauth_access_token_secret' => "YOUR_oauth_access_token_secret",
		'consumer_key' 				=> "YOUR_consumer_key",
		'consumer_secret' 			=> "YOUR_consumer_secret"
	);
	

	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	$getfield = '?user_id=' . $userId . "&count=200";     // Info sobre posibles opciones para el campo getField 		https://github.com/J7mbo/twitter-api-php/wiki/Get-a-user's-tweets
	$requestMethod = 'GET';

	$twitter = new TwitterAPIExchange($settings);
	$response = $twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest();
		
		
	$twitter_feed = json_decode($response, true);
	 
	// check for errors
	if(isset($twitter_feed['errors'])) {
		foreach($twitter_feed['errors'] as $error) {
			echo "(".$error['code'].") ".$error['message']."\n";
			return null;
		}
	} 
	else{
		// Loop thru and spit out the text of each tweet returned
		$i = 0;
		foreach($twitter_feed as $tweet) {
			// Se formatea la hora para que coincida con el formato de la BD
			$arregloCreatedAt = (" ", $tweet['created_at']); // Thu Jan 23 05:00:07 +0000 2014
			$fechaCreatedAt = date_create_from_format('j-M-Y', $arregloCreatedAt[2] . "-" . $arregloCreatedAt[1] . "-" . $arregloCreatedAt[5]);
			$fechaCreatedAt = date_format($fechaCreatedAt, 'Y-m-d') . " " . $arregloCreatedAt[3]; // lo ultimo es la hora

			$arrayAretornar[$i] = Array($fechaCreatedAt, $tweet['text']); 		
			$i += 1;
		}
	}
	return $arrayAretornar;
}


/* Esta funcion dado un id de usuario userId, obtiene los últimos $cantTweets tweets y los almacena en $archivoSalida (29/11/19)
	en $fileResumenSalida guarda el id de usuario y la cantidad de tweets recuperados
*/
function obtenerYguardarTweetsUsuarioEnFile($userId, $cantTweets, $fileSalida, $fileResumenSalida){	
	$arrayAretornar = Array();
	require_once('TwitterAPIExchange.php');
	
	/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
	$settings = array(
		'oauth_access_token' 		=> "YOUR_oauth_access_token",
		'oauth_access_token_secret' => "YOUR_oauth_access_token_secret",
		'consumer_key' 				=> "YOUR_consumer_key",
		'consumer_secret' 			=> "YOUR_consumer_secret"
	);
	

	$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	$getfield = '?user_id=' . $userId . "&count=$cantTweets";     // Info sobre posibles opciones para el campo getField 		https://github.com/J7mbo/twitter-api-php/wiki/Get-a-user's-tweets
	$requestMethod = 'GET';

	$twitter = new TwitterAPIExchange($settings);
	$response = $twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest();
		
		
	$twitter_feed = json_decode($response, true);
	 
	// check for errors
	if(isset($twitter_feed['errors'])) {
		// hubo un error. se guarda en el archivo del archivo comenzando con la palabra ERROR \tab y luego el mensaje del error
		fwrite($fileSalida, "ERROR\t");
		foreach($twitter_feed['errors'] as $error) {
			fwrite($fileSalida, "(".$error['code'].") ".$error['message'] . "\t");
			// echo "(".$error['code'].") ".$error['message']."\n";
		}
		fwrite($fileSalida, PHP_EOL);
	} 
	else{
		// Loop thru and spit out the text of each tweet returned
		$i = 0;
		foreach($twitter_feed as $tweet) {
			// echo "\n ++++++++ salida un tweet: $tweet ";
			fwrite($fileSalida, json_encode($tweet) . PHP_EOL); // se convierte de json a string (visto en https://www.php.net/manual/es/function.json-encode.php)
			$i += 1;
		}
		fwrite($fileResumenSalida, $userId . "\t" . $i . "\t de \t" . $cantTweets . PHP_EOL);
	}
	return $arrayAretornar;
}


	/*  		EJEMPLO DE RESPUESTA DE LA API DE TWITTER 	*/
	/*
		[1] => Array
        (
            [created_at] => Tue Oct 30 03:34:11 +0000 2012
            [id] => 263121594531061760
            [id_str] => 263121594531061760
            [text] => My Dropbox Referral Link. I'd be surprised if people didn't have an account yet. Super-handy if you have a smartphone http://t.co/OQ8IDYBc
            [source] => <a href="http://www.dropbox.com" rel="nofollow">DropboxÂ </a>
            [truncated] => 
            [in_reply_to_status_id] => 
            [in_reply_to_status_id_str] => 
            [in_reply_to_user_id] => 
            [in_reply_to_user_id_str] => 
            [in_reply_to_screen_name] => 
            [user] => Array
                (
                    [id] => 106994601
                    [id_str] => 106994601
                    [name] => Karl Blessing
                    [screen_name] => KarlBlessing
                    [location] => Grand Rapids, Mi
                    [url] => http://kbeezie.com
                    [description] => Webdeveloper
                    [protected] => 
                    [followers_count] => 40
                    [friends_count] => 52
                    [listed_count] => 1
                    [created_at] => Thu Jan 21 08:30:25 +0000 2010
                    [favourites_count] => 0
                    [utc_offset] => -18000
                    [time_zone] => Eastern Time (US & Canada)
                    [geo_enabled] => 1
                    [verified] => 
                    [statuses_count] => 95
                    [lang] => en
                    [contributors_enabled] => 
                    [is_translator] => 
                    [profile_background_color] => FFFFFF
                    [profile_background_image_url] => http://a0.twimg.com/profile_background_images/68539068/python-logo.png
                    [profile_background_image_url_https] => https://si0.twimg.com/profile_background_images/68539068/python-logo.png
                    [profile_background_tile] => 
                    [profile_image_url] => http://a0.twimg.com/profile_images/2549311960/ooc473od4tv58hlffxvy_normal.jpeg
                    [profile_image_url_https] => https://si0.twimg.com/profile_images/2549311960/ooc473od4tv58hlffxvy_normal.jpeg
                    [profile_link_color] => 0F6FFF
                    [profile_sidebar_border_color] => FFFFFF
                    [profile_sidebar_fill_color] => FFFFFF
                    [profile_text_color] => 333333
                    [profile_use_background_image] => 
                    [default_profile] => 
                    [default_profile_image] => 
                    [following] => 
                    [follow_request_sent] => 
                    [notifications] => 
                )
 
            [geo] => 
            [coordinates] => 
            [place] => 
            [contributors] => 
            [retweet_count] => 0
            [favorite_count] => 0
            [entities] => Array
                (
                    [hashtags] => Array
                        (
                        )
 
                    [urls] => Array
                        (
                            [0] => Array
                                (
                                    [url] => http://t.co/OQ8IDYBc
                                    [expanded_url] => http://db.tt/fJi2Poc
                                    [display_url] => db.tt/fJi2Poc
                                    [indices] => Array
                                        (
                                            [0] => 118
                                            [1] => 138
                                        )
 
                                )
 
                        )
 
                    [user_mentions] => Array
                        (
                        )
 
                )
 
            [favorited] => 
            [retweeted] => 
            [possibly_sensitive] => 
            [lang] => en
        )		
	*/	
?>