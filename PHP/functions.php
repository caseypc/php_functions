<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

<?php
/*--------------------------------------------------------------------------------
| Debug - Produces a nicely formatted debug message with option var_dump and backtrace.
|--------------------------------------------------------------------------------*/
function debug($var, $dump = false, $backtrace = true) {
	if (error_reporting() > 0) {
		if ($backtrace) {
			$calledFrom = debug_backtrace();
			echo '<strong>' . trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', $calledFrom[0]['file'])) . '</strong> (line <strong>' . $calledFrom[0]['line'] . '</strong>)';
		}
		echo '<pre class="debug">';
		$function = ($dump) ? 'var_dump' : 'print_r';
		$function($var);
		echo '</pre>';
	}
}

/*--------------------------------------------------------------------------------
| Debug - Simple
|--------------------------------------------------------------------------------*/
function debug_simple($var) {
	echo '<pre>';
	echo "File:".__FILE__."<br>";
	echo "Line:" .__LINE__."<br>";
	print_r($var);
	echo '</pre>';
}

/*--------------------------------------------------------------------------------
| Clean and sanitize a string that may contain invalid UTF-8 chars.
|--------------------------------------------------------------------------------*/
function clean($value, $escape = true, $quotes = true) {
	$a = array(
		'À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å',
		'æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','Ā','ā','Ă','ă','Ą','ą','Ć','ć','Ĉ','ĉ','Ċ','ċ','Č',
		'č','Ď','ď','Đ','đ','Ē','ē','Ĕ','ĕ','Ė','ė','Ę','ę','Ě','ě','Ĝ','ĝ','Ğ','ğ','Ġ','ġ','Ģ','ģ','Ĥ','ĥ','Ħ','ħ','Ĩ','ĩ','Ī','ī','Ĭ','ĭ','Į','į','İ',
		'ı','Ĳ','ĳ','Ĵ','ĵ','Ķ','ķ','Ĺ','ĺ','Ļ','ļ','Ľ','ľ','Ŀ','ŀ','Ł','ł','Ń','ń','Ņ','ņ','Ň','ň','ŉ','Ō','ō','Ŏ','ŏ','Ő','ő','Œ','œ','Ŕ','ŕ','Ŗ','ŗ',
		'Ř','ř','Ś','ś','Ŝ','ŝ','Ş','ş','Š','š','Ţ','ţ','Ť','ť','Ŧ','ŧ','Ũ','ũ','Ū','ū','Ŭ','ŭ','Ů','ů','Ű','ű','Ų','ų','Ŵ','ŵ','Ŷ','ŷ','Ÿ','Ź','ź','Ż',
		'ż','Ž','ž','ſ','ƒ','Ơ','ơ','Ư','ư','Ǎ','ǎ','Ǐ','ǐ','Ǒ','ǒ','Ǔ','ǔ','Ǖ','ǖ','Ǘ','ǘ','Ǚ','ǚ','Ǜ','ǜ','Ǻ','ǻ','Ǽ','ǽ','Ǿ','ǿ'
	);
	$b = array(
		'A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a',
		'ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C',
		'c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I',
		'i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r',
		'R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z',
		'z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o'
	);
	$value = str_replace($a, $b, $value);
	// handle additional special character replacements
	$search = array(chr(160), chr(169), chr(174));
	$replace = array('&nbsp;', '&copy;', '&reg;');
	$value = str_replace($search, $replace, $value);
	// filter out remaining unwanted chars
	$value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
	// check if escaping
	if ($escape === TRUE) {
		return escape($value, $quotes);
	}
	return $value;
}
function escape($value, $quotes = true) {
	$quotes = $quotes ? ENT_QUOTES : ENT_NOQUOTES;
	return htmlentities($value, $quotes, 'UTF-8', FALSE);
}

/*--------------------------------------------------------------------------------
| Replaces special characters with non-special equivalents
|--------------------------------------------------------------------------------*/
function normalize_special_characters( $str ){
	# Quotes cleanup
	$str = ereg_replace( chr(ord("`")), "'", $str );        # `
	$str = ereg_replace( chr(ord("´")), "'", $str );        # ´
	$str = ereg_replace( chr(ord("„")), ",", $str );        # „
	$str = ereg_replace( chr(ord("`")), "'", $str );        # `
	$str = ereg_replace( chr(ord("´")), "'", $str );        # ´
	$str = ereg_replace( chr(ord("“")), "\"", $str );        # “
	$str = ereg_replace( chr(ord("”")), "\"", $str );        # ”
	$str = ereg_replace( chr(ord("´")), "'", $str );        # ´
	$unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 
		'È'=>'E', 'É'=>'E',	'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 
		'Ø'=>'O', 'Ù'=>'U',	'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 
		'æ'=>'a', 'ç'=>'c',	'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 
		'ô'=>'o', 'õ'=>'o',	'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y');
	$str = strtr( $str, $unwanted_array );
	# Bullets, dashes, and trademarks
	$str = ereg_replace( chr(149), "&#8226;", $str );    # bullet •
	$str = ereg_replace( chr(150), "&ndash;", $str );    # en dash
	$str = ereg_replace( chr(151), "&mdash;", $str );    # em dash
	$str = ereg_replace( chr(153), "&#8482;", $str );    # trademark
	$str = ereg_replace( chr(169), "&copy;", $str );    # copyright mark
	$str = ereg_replace( chr(174), "&reg;", $str );        # registration mark
	return $str;
}

/*--------------------------------------------------------------------------------
| Sanitize input
|--------------------------------------------------------------------------------*/
function sanitize($in) {
	return addslashes(htmlspecialchars(strip_tags(trim($in))));
}

/*--------------------------------------------------------------------------------
| Sanitize input for database use
|--------------------------------------------------------------------------------*/
function dbsanitize($in) {
	return addslashes(htmlspecialchars(strip_tags(trim(mysql_real_escape_string($in)))));
}

/*--------------------------------------------------------------------------------
| Truncate (shorten) a string. Parameters: (String, Length, Delimiter). Ex. shorten($text, 30, '&raquo;');
|--------------------------------------------------------------------------------*/
function shorten($str, $n, $delim='...') {
	$len = strlen($str);
	if ($len > $n) {
		preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches);
		return rtrim($matches[1]) . $delim;
	} else {
		return $str;
	}
}

/*--------------------------------------------------------------------------------
| Truncate text at word break - $short_string=myTruncate($long_string, 100, ' ');
|--------------------------------------------------------------------------------*/
function myTruncate($string, $limit, $break=".", $pad="...") {   
	if(strlen($string) <= $limit) {// return with no change if string is shorter than $limit
		return $string; 
	}
	if(false !== ($breakpoint = strpos($string, $break, $limit))) {// is $break present between $limit and the end of the string?
		if($breakpoint < strlen($string) - 1) {
			$string = substr($string, 0, $breakpoint) . $pad;
		}
	}
	return $string; 
}

/*--------------------------------------------------------------------------------
| Time passed from timestamp
|--------------------------------------------------------------------------------*/
function time_passed($time){
	$timestring = '';
	$time = time()-$time;
	$weeks = $time/604800;
	$days = ($time%604800)/86400;
	$hours = (($time%604800)%86400)/3600;
	$minutes = ((($time%604800)%86400)%3600)/60;
	$seconds = (((($time%604800)%86400)%3600)%60);
	if(floor($weeks)) $timestring .= floor($weeks)." weeks ";
	if(floor($days)) $timestring .= floor($days)." days ";
	if(floor($hours)) $timestring .= floor($hours)." hours ";
	if(floor($minutes)) $timestring .= floor($minutes)." minutes ";
	if(!floor($minutes)&&!floor($hours)&&!floor($days)) $timestring .= floor($seconds)." seconds ";
	return $timestring;
}

/*--------------------------------------------------------------------------------
| Write message to log file
|--------------------------------------------------------------------------------*/
function file_log($msg) {
	$myFile = "testLogFile.txt";
	$fh = fopen($myFile, 'a') or elgg_log("can't open file", 'ERROR');
	$stringData = date('Y.m.d H:i:s: ').$msg."\n";
	fwrite($fh, $stringData);
	fclose($fh);
}

/*--------------------------------------------------------------------------------
| Get User's IP address, by-pass proxy IPs
|--------------------------------------------------------------------------------*/
function getIP(){
	if (!empty($_SERVER['HTTP_CLIENT_IP'])){$ip=$_SERVER['HTTP_CLIENT_IP'];}//check ip from share internet
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];}//to check ip is pass from proxy
	else{$ip=$_SERVER['REMOTE_ADDR'];}//this is the best you can get
	return $ip;
}

/*--------------------------------------------------------------------------------
| Advanced Method to Retrieve Client IP Address
|--------------------------------------------------------------------------------*/
function get_ip_address() {
	$ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
	foreach ($ip_keys as $key) {
		if (array_key_exists($key, $_SERVER) === true) {
			foreach (explode(',', $_SERVER[$key]) as $ip) {
				$ip = trim($ip);// trim for safety measures
				if (validate_ip($ip)) {// attempt to validate IP
					return $ip;
				}
			}
		}
	}
	return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}
// Ensures an ip address is both a valid IP and does not fall within a private network range.
function validate_ip($ip) {
	if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
		return false;
	}
	return true;
}

/*--------------------------------------------------------------------------------
| Doanlow file function
|--------------------------------------------------------------------------------*/
function download($file){
	if (file_exists($file)) {
		if(is_dir($file)){return false;}
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.str_ireplace(' ','_',basename($file)).'"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: Keep-Alive');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: '.sprintf("%u", filesize($file)));
		@ob_clean();
		$handle = fopen($file, "rb");
		$chunksize=(sprintf("%u", (filesize($file))/1024));
		set_time_limit(0);
		while (!feof($handle)) {
			echo fgets($handle, $chunksize);
			flush();
		}
		fclose($handle);
		die;
	}else{return false;}
	return;
}

/*--------------------------------------------------------------------------------
| Detect location by IP
|--------------------------------------------------------------------------------*/
// Here is an useful code snippet to detect the location of a specific IP. The function below takes one IP as a parameter, and returns the location of the IP. If no location is found, UNKNOWN is returned.
function detect_city($ip) {
	$default = 'UNKNOWN';
	if (!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost')
		$ip = '8.8.8.8';
	$curlopt_useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)';
	$url = 'http://ipinfodb.com/ip_locator.php?ip=' . urlencode($ip);
	$ch = curl_init();
	$curl_opt = array(
		CURLOPT_FOLLOWLOCATION  => 1,
		CURLOPT_HEADER      => 0,
		CURLOPT_RETURNTRANSFER  => 1,
		CURLOPT_USERAGENT   => $curlopt_useragent,
		CURLOPT_URL       => $url,
		CURLOPT_TIMEOUT         => 1,
		CURLOPT_REFERER         => 'http://' . $_SERVER['HTTP_HOST'],
	);
	curl_setopt_array($ch, $curl_opt);
	$content = curl_exec($ch);
	if (!is_null($curl_info)) {
		$curl_info = curl_getinfo($ch);
	}
	curl_close($ch);
	if ( preg_match('{<li>City : ([^<]*)</li>}i', $content, $regs) ) { $city = $regs[1]; }
	if ( preg_match('{<li>State/Province : ([^<]*)</li>}i', $content, $regs) ) { $state = $regs[1]; }
	if ( $city!='' && $state!='' ) {
		$location = $city . ', ' . $state;
		return $location;
	}else{
		return $default; 
	}
}

/*--------------------------------------------------------------------------------
| get lat & long values of an address
|--------------------------------------------------------------------------------*/
//Functions returns the latitude & longitude values of an address (as an array) when the address is passed to the function (as a string) 
function getLatLong($address){
	if (!is_string($address))die("ERROR! - Invalid Address!");
	$_url = sprintf('http://maps.google.com/maps?output=js&q=%s',rawurlencode($address));
	$_result = false;
	if($_result = file_get_contents($_url)) {
		if(strpos($_result,'errortips') > 1 || strpos($_result,'Did you mean:') !== false) return false;
		preg_match('!center:\s*{lat:\s*(-?\d+\.\d+),lng:\s*(-?\d+\.\d+)}!U', $_result, $_match);
		$_coords['lat'] = $_match[1];
		$_coords['long'] = $_match[2];
	}
	return $_coords; // returns an array $_coords['lat'], $_coords['long']
}

/*--------------------------------------------------------------------------------
| Calculate distance between two points
|--------------------------------------------------------------------------------*/
/*
Example:

$point1 = array('lat' => 40.770623, 'long' => -73.964367);
$point2 = array('lat' => 40.758224, 'long' => -73.917404);
$distance = getDistanceBetweenPointsNew($point1['lat'], $point1['long'], $point2['lat'], $point2['long']);
foreach ($distance as $unit => $value) {
echo $unit.': '.number_format($value,4).'<br />';
}
*/
function getDistanceBetweenPoints($latitude1, $longitude1, $latitude2, $longitude2) {
	$theta = $longitude1 - $longitude2;
	$miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
	$miles = acos($miles);
	$miles = rad2deg($miles);
	$miles = $miles * 60 * 1.1515;
	$feet = $miles * 5280;
	$yards = $feet / 3;
	$kilometers = $miles * 1.609344;
	$meters = $kilometers * 1000;
	return compact('miles','feet','yards','kilometers','meters'); 
}

/*--------------------------------------------------------------------------------
| Get current weather using Google API
|--------------------------------------------------------------------------------*/
function getGoogleWeather($address) {
	$xml = simplexml_load_file('http://www.google.com/ig/api?weather='.$address);
	$information = $xml->xpath("/xml_api_reply/weather/current_conditions/condition");
	return $information[0]->attributes();
}

/*--------------------------------------------------------------------------------
| Get Favicon from URL
|--------------------------------------------------------------------------------*/
function get_favicon($url){
	$url = str_replace("http://",'',$url);
	return "http://www.google.com/s2/favicons?domain=".$url;
}

/*--------------------------------------------------------------------------------
| Generate CSV file from a PHP array
|--------------------------------------------------------------------------------*/
function generateCsv($data, $delimiter = ',', $enclosure = '"') {
	$handle = fopen('php://temp', 'r+');
	foreach ($data as $line) {
		fputcsv($handle, $line, $delimiter, $enclosure);
	}
	rewind($handle);
	while (!feof($handle)) {
		$contents .= fread($handle, 8192);
	}
	fclose($handle);
	return $contents;
}

/*--------------------------------------------------------------------------------
| Whois query using PHP
|--------------------------------------------------------------------------------*/
// If you need to get the whois information for a specific domain, why not using PHP to do it? The following function take a domain name as a parameter, and then display the whois info related to the domain.
function whois_query($domain) {
	// fix the domain name:
	$domain = strtolower(trim($domain));
	$domain = preg_replace('/^http:\/\//i', '', $domain);
	$domain = preg_replace('/^www\./i', '', $domain);
	$domain = explode('/', $domain);
	$domain = trim($domain[0]);
	// split the TLD from domain name
	$_domain = explode('.', $domain);
	$lst = count($_domain)-1;
	$ext = $_domain[$lst];
	// You find resources and lists like these on wikipedia: <a href="http://de.wikipedia.org/wiki/Whois">http://de.wikipedia.org/wiki/Whois</a>
	$servers = array(
		"biz" => "whois.neulevel.biz",
		"com" => "whois.internic.net",
		"us" => "whois.nic.us",
		"coop" => "whois.nic.coop",
		"info" => "whois.nic.info",
		"name" => "whois.nic.name",
		"net" => "whois.internic.net",
		"gov" => "whois.nic.gov",
		"edu" => "whois.internic.net",
		"mil" => "rs.internic.net",
		"int" => "whois.iana.org",
		"ac" => "whois.nic.ac",
		"ae" => "whois.uaenic.ae",
		"at" => "whois.ripe.net",
		"au" => "whois.aunic.net",
		"be" => "whois.dns.be",
		"bg" => "whois.ripe.net",
		"br" => "whois.registro.br",
		"bz" => "whois.belizenic.bz",
		"ca" => "whois.cira.ca",
		"cc" => "whois.nic.cc",
		"ch" => "whois.nic.ch",
		"cl" => "whois.nic.cl",
		"cn" => "whois.cnnic.net.cn",
		"cz" => "whois.nic.cz",
		"de" => "whois.nic.de",
		"fr" => "whois.nic.fr",
		"hu" => "whois.nic.hu",
		"ie" => "whois.domainregistry.ie",
		"il" => "whois.isoc.org.il",
		"in" => "whois.ncst.ernet.in",
		"ir" => "whois.nic.ir",
		"mc" => "whois.ripe.net",
		"to" => "whois.tonic.to",
		"tv" => "whois.tv",
		"ru" => "whois.ripn.net",
		"org" => "whois.pir.org",
		"aero" => "whois.information.aero",
		"nl" => "whois.domain-registry.nl"
	);
	if (!isset($servers[$ext])){
		die('Error: No matching nic server found!');
	}
	$nic_server = $servers[$ext];
	$output = '';
	if ($conn = fsockopen ($nic_server, 43)) {// connect to whois server:
		fputs($conn, $domain."\r\n");
		while(!feof($conn)) {
			$output .= fgets($conn,128);
		}
		fclose($conn);
	} else { die('Error: Could not connect to ' . $nic_server . '!'); }
	return $output;
}

/*--------------------------------------------------------------------------------
| Password strength
|--------------------------------------------------------------------------------*/
// Returns a float between 0 and 100. The closer the number is to 100 the the stronger password is; further from 100 the weaker the password is.
function password_strength($string){
	$h = 0;
	$size = strlen($string);
	foreach(count_chars($string, 1) as $v){
		$p = $v / $size;
		$h -= $p * log($p) / log(2);
	}
	$strength = ($h / 4) * 100;
	if($strength > 100){
		$strength = 100;
	}
	return $strength;
}

/*--------------------------------------------------------------------------------
| Best cURL function.... Ever!
|--------------------------------------------------------------------------------*/
function xcurl($url,$ref=null,$post=array(),$ua="Mozilla/5.0 (X11; Linux x86_64; rv:2.2a1pre) Gecko/20110324 Firefox/4.2a1pre",$print=false) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	if(!empty($ref)) {
		curl_setopt($ch, CURLOPT_REFERER, $ref);
	}
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if(!empty($ua)) {
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
	}
	if(count($post) > 0){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);    
	}
	$output = curl_exec($ch);
	curl_close($ch);
	if($print) {
		print($output);
	} else {
		return $output;
	}
}

/*--------------------------------------------------------------------------------
| Return the elapsed time in a human readable format.
|--------------------------------------------------------------------------------*/ 
// Example usage
// $begin = microtime();
// do stuff
// ...
// $end = microtime();
// echo elapsed_time($begin, $end);
// returns time in a format that looks like this: 2h 34m 42s
function elapsed_time($begin, $end) {
	$begin = explode(' ', $begin);
	$end = explode(' ', $end);
	$time_diff = ($end[0] + $end[1]) - ($begin[0] + $begin[1]);
	$buffer = '';
	$time_table = array(
		'd' => (int) ($time_diff / 86400),
		'h' => $time_diff / 3600 % 24,
		'm' => $time_diff / 60 % 60,
		's' => $time_diff % 60
	);
	if ((int) $time_diff > 30) {
		$buffer = '';
		foreach ($time_table as $unit => $time) {
			if ($time > 0) { $buffer .= "$time$unit "; }
		}
	} else {
		$buffer =  number_format($time_diff, 3) . 's';
	}
	return trim($buffer);
}

/*--------------------------------------------------------------------------------
| Create Data URI's
|--------------------------------------------------------------------------------*/
// Data URI’s can be useful for embedding images into HTML/CSS/JS to save on HTTP requests. The following function will create a Data URI based on $file for easier embedding.
function data_uri($file, $mime) {
	$contents=file_get_contents($file);
	$base64=base64_encode($contents);
	echo "data:$mime;base64,$base64";
}

/*--------------------------------------------------------------------------------
| Email error logs to yourself - custom error handler
|--------------------------------------------------------------------------------*/
// We should use our custom function to handle errors.
// set_error_handler('nettuts_error_handler');
function nettuts_error_handler($number, $message, $file, $line, $vars){
	$email = "<p>An error ($number) occurred on line <strong>$line</strong> and in the <strong>file: $file.</strong></p><p>$message</p>";
	$email .= "<pre>" . print_r($vars, 1) . "</pre>";
	$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	// Email the error to someone...
	error_log($email, 1, 'you@youremail.com', $headers);
	// Make sure that you decide how to respond to errors (on the user's side) Either echo an error message, or kill the entire project. Up to you...
	// The code below ensures that we only "die" if the error was more than just a NOTICE. 
	if ( ($number !== E_NOTICE) && ($number < 2048) ) {
		die("There was an error. Please try again later.");
	}
}

/*--------------------------------------------------------------------------------
| Add (th, st, nd, rd, th) to the end of a number
|--------------------------------------------------------------------------------*/
function ordinal($cdnl){
	$test_c = abs($cdnl) % 10;
	$ext = ((abs($cdnl) %100 < 21 && abs($cdnl) %100 > 4) ? 'th' 
	: (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1) 
	? 'th' : 'st' : 'nd' : 'rd' : 'th'));
	return $cdnl.$ext;
}

/*--------------------------------------------------------------------------------
| Validate an email address - could be better
|--------------------------------------------------------------------------------*/
function isValidEmail($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
}

/*--------------------------------------------------------------------------------
| Validate an email address - Advanced regex
|--------------------------------------------------------------------------------*/
function isValidEmail_advanced($email) {
	$pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
	return preg_match($pattern,$email);
}

/*--------------------------------------------------------------------------------
| Validate an email address - Simple regex
|--------------------------------------------------------------------------------*/
function isValidEmail_simple($email) {
	$regex = '/([a-z0-9_]+|[a-z0-9_]+\.[a-z0-9_]+)@(([a-z0-9]|[a-z0-9]+\.[a-z0-9]+)+\.([a-z]{2,4}))/i';
	return preg_match($regex, $email);
}

/*--------------------------------------------------------------------------------
| FormatBytes to other units
|--------------------------------------------------------------------------------*/
function formatBytes($bytes, $precision = 2) { 
	$units = array('B', 'KB', 'MB', 'GB', 'TB');
	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);
	$bytes /= pow(1024, $pow);
	return round($bytes, $precision) . ' ' . $units[$pow];
}

/*--------------------------------------------------------------------------------
| isAjax - Detect if the request came from AJAX
|--------------------------------------------------------------------------------*/
function isAjax() {
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

/*--------------------------------------------------------------------------------
| Creates a new file name by appending a _3 to the file name if the file already exists
|--------------------------------------------------------------------------------*/
function file_newname($path, $filename) {
	if ($pos = strrpos($filename, '.')) {
		$name = substr($filename, 0, $pos);
		$ext = substr($filename, $pos);
	} else {
		$name = $filename;
	}
	$newpath = $path.'/'.$filename;
	$newname = $filename;
	$counter = 0;
	while (file_exists($newpath)) {
		$newname = $name .'_'. $counter . $ext;
		$newpath = $path.'/'.$newname;
		$counter++;
	}
	return $newname;
}

/*--------------------------------------------------------------------------------
| Backup your MySQL database
| Backup just a table: <?php backup_tables('localhost','username','password','blog');?>
| Or backup the entire DB: <?php backup_tables('localhost','username','password');?>
|--------------------------------------------------------------------------------*/
function backup_tables($host,$user,$pass,$name,$tables='*') {
	$link = mysql_connect($host,$user,$pass);
	mysql_select_db($name,$link);
	if($tables == '*') {//get all of the tables
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result)) {
			$tables[] = $row[0];
		}
	} else {
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	foreach($tables as $table) {//cycle through
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		for ($i = 0;$i < $num_fields;$i++) {
			$return.= 'DROP TABLE '.$table.';';
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$return.= "\n\n".$row2[1].";\n\n";
			while($row = mysql_fetch_row($result)) {
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0;$j<$num_fields;$j++) {
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ;} else { $return.= '""';}
					if ($j<($num_fields-1)) { $return.= ',';}
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	//save file
	$handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
	fwrite($handle,$return);
	fclose($handle);
}

/*--------------------------------------------------------------------------------
| AutoLinkUrls - Automatically turn all text urls into working Hyperlinks.
| Does not require http in the text to link. It must start with www however. 
| Optionaly make the links popup in a new window.
|--------------------------------------------------------------------------------*/
function AutoLinkUrls($str,$popup = FALSE) {
	if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches)) {
		$pop = ($popup == TRUE) ? " target=\"_blank\" " : "";
		for ($i = 0;$i < count($matches['0']);$i++) {
			$period = '';
			if (preg_match("|\.$|", $matches['6'][$i])) {
				$period = '.';
				$matches['6'][$i] = substr($matches['6'][$i], 0, -1);
			}
			$str = str_replace($matches['0'][$i],
				$matches['1'][$i].'<a href="http'.
				$matches['4'][$i].'://'.
				$matches['5'][$i].
				$matches['6'][$i].'"'.$pop.'>http'.
				$matches['4'][$i].'://'.
				$matches['5'][$i].
				$matches['6'][$i].'</a>'.
			$period, $str);
		}//end for
	}//end if
	return $str;
}//end AutoLinkUrls

/*--------------------------------------------------------------------------------
| Convert URLs in string to hyperlinks
|--------------------------------------------------------------------------------*/
function makeClickableLinks($text) {  
	$text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_+.~#?&//=]+)', '<a href="\1">\1</a>', $text);
	$text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_+.~#?&//=]+)', '\1<a href="http://\2">\2</a>', $text);
	$text = eregi_replace('([_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3})', '<a href="mailto:\1">\1</a>', $text);
	return $text;
}

/*--------------------------------------------------------------------------------
| Google bot crawl notifier
|--------------------------------------------------------------------------------*/
function bot_notifer() {
	$email = "test@test.com";
	if(eregi("googlebot",$HTTP_USER_AGENT)) {
		if ($QUERY_STRING != "") {
			$url = "http://".$SERVER_NAME.$PHP_SELF.'?'.$QUERY_STRING;
		} else {
			$url = "http://".$SERVER_NAME.$PHP_SELF;
		}
		$date = date("F j, Y, g:i a");
		mail($email, "[Googlebot] $url", "$date - Google crawled $url");
	} 
}

/*--------------------------------------------------------------------------------
| Display Sourcecode
| $url = "http://google.com";
| echo display_sourcecode($url);
|--------------------------------------------------------------------------------*/
function display_sourcecode($url) {
	$lines = file($url);
	$output = "";
	foreach ($lines as $line_num => $line) {// loop thru each line and prepend line numbers
		$output.= "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br>\n";
	}
}

/*--------------------------------------------------------------------------------
| Show Facebook fan page likes
| $page = "facebookpagename";
| echo fb_fan_count($page);
|--------------------------------------------------------------------------------*/
function fb_fan_count($facebook_name) {
	$data = json_decode(file_get_contents("https://graph.facebook.com/".$facebook_name));
	$likes = $data->likes;
	return $likes;
}

/*--------------------------------------------------------------------------------
| Create a Zip file
| $files=array('file1.jpg', 'file2.jpg', 'file3.gif');
| create_zip($files, 'myzipfile.zip', true);
|--------------------------------------------------------------------------------*/
function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false;}
	$valid_files = array();
	if(is_array($files)) {//if files were passed in...
		foreach($files as $file) {//cycle through each file
			if(file_exists($file)) {//make sure the file exists
				$valid_files[] = $file;
			}
		}
	}
	if(count($valid_files)) {//if we have good files...
		$zip = new ZipArchive();//create the archive
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		foreach($valid_files as $file) {//add the files
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		//close the zip -- done!
		$zip->close();
		return file_exists($destination);//check to make sure the file exists
	} else {
		return false;
	}
}

/*--------------------------------------------------------------------------------
| Unzip a file - unzip('test.zip','unziped/test');//File would be unzipped in unziped/test folder
|--------------------------------------------------------------------------------*/
function unzip($location,$newLocation) {
	if(exec("unzip $location",$arr)) {
		mkdir($newLocation);
		for($i = 1;$i< count($arr);$i++) {
			$file = trim(preg_replace("~inflating: ~","",$arr[$i]));
			copy($location.'/'.$file,$newLocation.'/'.$file);
			unlink($location.'/'.$file);
		}
		return TRUE;
	} else {
		return FALSE;
	}
}

/*--------------------------------------------------------------------------------
| Create Thumbnail
|--------------------------------------------------------------------------------*/
function createthumb($name,$filename,$new_w,$new_h,$type) {
	$system=explode('.',$name);
	if ($type == "jpg" || $type == "jpeg") { $src_img=imagecreatefromjpeg($name);}
	if ($type == "png") { $src_img=imagecreatefrompng($name);}
	if ($type == "gif") { $src_img=imagecreatefromgif($name);}
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);
	if ($old_x > $old_y) {
		$thumb_w=$new_w;
		$percent = ($new_w * 100) / $old_x;
		$thumb_h = ($percent * $old_y) / 100;
	}
	if ($old_x < $old_y) {
		$percent = ($new_h * 100) / $old_y;
		$thumb_w = ($percent * $old_x) / 100;
		$thumb_h=$new_h;
	}
	if ($old_x == $old_y) {
		$thumb_w=$new_w;
		$thumb_h=$new_h;
	}
	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	if ($type == "png") { imagepng($dst_img,$filename);}
	if ($type == "gif") { imagegif($dst_img,$filename);}
	if ($type == "jpg" || $type == "jpeg") { imagejpeg($dst_img,$filename);	}
	imagedestroy($dst_img);
	imagedestroy($src_img);
}

/*--------------------------------------------------------------------------------
| Resize Image
|--------------------------------------------------------------------------------*/
function resize_image($filename, $tmpname, $xmax, $ymax) {  
	$ext = explode(".", $filename);
	$ext = $ext[count($ext)-1];
	if ($ext == "jpg" || $ext == "jpeg") {
		$im = imagecreatefromjpeg($tmpname);
	} elseif ($ext == "png") {
		$im = imagecreatefrompng($tmpname);
	} elseif($ext == "gif") {
		$im = imagecreatefromgif($tmpname);
	}
	$x = imagesx($im);
	$y = imagesy($im);
	if($x <= $xmax && $y <= $ymax) {
		return $im;
	}
	if($x >= $y) {
		$newx = $xmax;
		$newy = $newx * $y / $x;
	} else {  
		$newy = $ymax;
		$newx = $x / $y * $newy;
	}
	$im2 = imagecreatetruecolor($newx, $newy);
	imagecopyresized($im2, $im, 0, 0, 0, 0, floor($newx), floor($newy), $x, $y);
	return $im2; 
}

/*--------------------------------------------------------------------------------
| Read CSV file
| $csvFile = "test.csv";
| $csv = readCSV($csvFile);
| $a = csv[0][0];// This will get value of Column 1 & Row 1
|--------------------------------------------------------------------------------*/
function readCSV($csvFile) {
	$file_handle = fopen($csvFile, 'r');
	while (!feof($file_handle) ) {
		$line_of_text[] = fgetcsv($file_handle, 1024);
	}
	fclose($file_handle);
	return $line_of_text;
}

/*--------------------------------------------------------------------------------
| Creating a CSV file a array
| $data[0] = "apple";
| $data[1] = "oranges";
| generateCsv($data, $delimiter = ',', $enclosure = '"');
|--------------------------------------------------------------------------------*/
function generateCsv($data, $delimiter = ',', $enclosure = '"') {
	$handle = fopen('php://temp', 'r+');
	foreach ($data as $line) {
		fputcsv($handle, $line, $delimiter, $enclosure);
	}
	rewind($handle);
	while (!feof($handle)) {
		$contents .= fread($handle, 8192);
	}
	fclose($handle);
	return $contents;
}

/*--------------------------------------------------------------------------------
| Get latest tweet(s) from Twitter account
| $handle = "twittername";
| my_twitter($handle);
|--------------------------------------------------------------------------------*/
function my_twitter($username) {
	$no_of_tweets = 1;
	$feed = "http://search.twitter.com/search.atom?q=from:" . $username . "&rpp=" . $no_of_tweets;
	$xml = simplexml_load_file($feed);
	foreach($xml->children() as $child) {
		foreach ($child as $value) {
			if($value->getName() == "link") $link = $value['href'];
			if($value->getName() == "content") {
				$content = $value . "";
				echo '<p class="twit">'.$content.' <a class="twt" href="'.$link.'" title="">&nbsp;</a></p>';
			}
		}
	}
}

/*--------------------------------------------------------------------------------
| Encode email address with htmlenties to prevent spam
|--------------------------------------------------------------------------------*/
function encode_email($email='info@domain.com', $linkText='Contact Us', $attrs='class="emailencoder"') {  
	$email = str_replace('@', '&#64;', $email);
	$email = str_replace('.', '&#46;', $email);
	$email = str_split($email, 5);
	$linkText = str_replace('@', '&#64;', $linkText);
	$linkText = str_replace('.', '&#46;', $linkText);
	$linkText = str_split($linkText, 5);
	$part1 = '<a href="ma';
	$part2 = 'ilto&#58;';
	$part3 = '" '. $attrs .' >';
	$part4 = '</a>';
	$encoded = '<script type="text/javascript">';
	$encoded .= "document.write('$part1');";
	$encoded .= "document.write('$part2');";
	foreach($email as $e) {  
		$encoded .= "document.write('$e');";
	}
	$encoded .= "document.write('$part3');";
	foreach($linkText as $l) {  
		$encoded .= "document.write('$l');";
	}
	$encoded .= "document.write('$part4');";
	$encoded .= '</script>';
	return $encoded;
}

/*--------------------------------------------------------------------------------
| Calculate age using birth date
|--------------------------------------------------------------------------------*/
function age($date) {
	$time = strtotime($date);
	if ($time === false) {
		return '';
	}
	$year_diff = '';
	$date = date('Y-m-d', $time);
	list($year,$month,$day) = explode('-',$date);
	$year_diff = date('Y') - $year;
	$month_diff = date('m') - $month;
	$day_diff = date('d') - $day;
	if ($day_diff < 0 || $month_diff < 0) $year_diff-;
	return $year_diff;
}

/*--------------------------------------------------------------------------------
| Validate a US Social Security Number.
|--------------------------------------------------------------------------------*/
/*
3 digits from 001 to 899 (can't be 666)
2 digits from 01 to 99 (based on some US govt odd/even formula)
4 digits from 0001 to 9999 (assigned sequentially to individual people)
last section verifies that none of the number groups are all zeros
*/
function isValidSSN($ssn){
	if (!preg_match('#^([0-8]\d{2})([ \-]?)(\d{2})\2(\d{4})$#', $ssn)) {
		return false;
	}
	// Remove all spaces and/or dashes
	$ssn = str_replace(' ', '', $ssn);
	$ssn = str_replace('-', '', $ssn);
	if (substr($ssn, 0, 3) == '000' || substr($ssn, 0, 3) == '666') { return false; }
	if (substr($ssn, 4, 2) == '00') { return false; }
	if (substr($ssn, 7, 4) == '0000') { return false; }
	return true;
}
?>
