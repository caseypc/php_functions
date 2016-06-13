<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Clean a string pasted from an unknown source (i.e. Microsoft Word) that might have invalid UTF-8 chars
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



// DEBUG
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


// Get User's IP address, by-pass proxy IPs
function getIP(){
	if (!empty($_SERVER['HTTP_CLIENT_IP'])){$ip=$_SERVER['HTTP_CLIENT_IP'];}//check ip from share internet
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];}//to check ip is pass from proxy
	else{$ip=$_SERVER['REMOTE_ADDR'];}//this is the best you can get
	return $ip;
}


// CLEAN
function clean($string){
	$string = stripslashes($string);
	$string = htmlentities($string);
	$string = strip_tags($string);
	$string = addslashes($string);
	return $string;
}


// Truncate (shorten) a string. Parameters: (String, Length, Delimiter). Ex. shorten($text, 30, '&raquo;');
function shorten($str, $n, $delim='...') {
   $len = strlen($str);
   if ($len > $n) {
       preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches);
       return rtrim($matches[1]) . $delim;
   } else {
       return $str;
   }
}


// Time passed from timestamp
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


// Replaces special characters with non-special equivalents
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

    $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                                'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                                'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                                'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                                'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
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



















<?php

function debug($var) {
	echo '<pre>';
	echo "File:".__FILE__."<br>";
	echo "Line:" .__LINE__."<br>";
	print_r($var);
	echo '</pre>';
}


// Write message to log file
function file_log($msg) {
	$myFile = "testLogFile.txt";
	$fh = fopen($myFile, 'a') or elgg_log("can't open file", 'ERROR');
	$stringData = date('Y.m.d H:i:s: ').$msg."\n";
	fwrite($fh, $stringData);
	fclose($fh);
}




// Sanitize input
function sanitize($in) {
	return addslashes(htmlspecialchars(strip_tags(trim($in))));
}

// Sanitize input for database use
function dbsanitize($in) {
	return addslashes(htmlspecialchars(strip_tags(trim(mysql_real_escape_string($in))));
}







// Clean a string pasted from an unknown source (i.e. Microsoft Word) that might have invalid UTF-8 chars
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








// Advanced Method to Retrieve Client IP Address
function get_ip_address() {
	$ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
	foreach ($ip_keys as $key) {
		if (array_key_exists($key, $_SERVER) === true) {
			foreach (explode(',', $_SERVER[$key]) as $ip) {
				// trim for safety measures
				$ip = trim($ip);
				// attempt to validate IP
				if (validate_ip($ip)) {
					return $ip;
				}
			}
		}
	}
	return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}
/**
* Ensures an ip address is both a valid IP and does not fall within
* a private network range.
*/
function validate_ip($ip) {
	if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
		return false;
	}
	return true;
}










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






// Detect location by IP
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
        
        if ( preg_match('{<li>City : ([^<]*)</li>}i', $content, $regs) )  {
            $city = $regs[1];
        }
        if ( preg_match('{<li>State/Province : ([^<]*)</li>}i', $content, $regs) )  {
            $state = $regs[1];
        }

        if( $city!='' && $state!='' ){
          $location = $city . ', ' . $state;
          return $location;
        }else{
          return $default; 
        }
        
    }





// get lat & long values of an address
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




// Calculate distance between two points
/*
Example:

$point1 = array('lat' => 40.770623, 'long' => -73.964367);
$point2 = array('lat' => 40.758224, 'long' => -73.917404);
$distance = getDistanceBetweenPointsNew($point1['lat'], $point1['long'], $point2['lat'], $point2['long']);
foreach ($distance as $unit => $value) {
    echo $unit.': '.number_format($value,4).'<br />';
}
*/
function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
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





// Get info about your memory usage
// In order to optimize your scripts, you may definitely want to know how many amount of RAM they use on your server. This snippet will check memory and then print initial, final and peak usages.
echo "Initial: ".memory_get_usage()." bytes \n";
/* prints
Initial: 361400 bytes
*/

// let's use up some memory
for ($i = 0; $i < 100000; $i++) {
	$array []= md5($i);
}

// let's remove half of the array
for ($i = 0; $i < 100000; $i++) {
	unset($array[$i]);
}

echo "Final: ".memory_get_usage()." bytes \n";
/* prints
Final: 885912 bytes
*/

echo "Peak: ".memory_get_peak_usage()." bytes \n";
/* prints
Peak: 13687072 bytes
*/





//Get current weather using Google API
$xml = simplexml_load_file('http://www.google.com/ig/api?weather=ADDRESS');
  $information = $xml->xpath("/xml_api_reply/weather/current_conditions/condition");
  echo $information[0]->attributes();



function get_favicon($url){
  $url = str_replace("http://",'',$url);
  return "http://www.google.com/s2/favicons?domain=".$url;
}







// Generate CSV file from a PHP array
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







// Whois query using PHP
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
 
    // You find resources and lists 
    // like these on wikipedia: 
    //
    // <a href="http://de.wikipedia.org/wiki/Whois">http://de.wikipedia.org/wiki/Whois</a>
    //
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
 
    // connect to whois server:
    if ($conn = fsockopen ($nic_server, 43)) {
        fputs($conn, $domain."\r\n");
        while(!feof($conn)) {
            $output .= fgets($conn,128);
        }
        fclose($conn);
    }
    else { die('Error: Could not connect to ' . $nic_server . '!'); }
 
    return $output;
}










// password strength
/**
 * 
 * @param String $string
 * @return float
 * 
 * Returns a float between 0 and 100. The closer the number is to 100 the
 * the stronger password is; further from 100 the weaker the password is.
 */
function password_strength($string){
    $h    = 0;
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

var_dump(password_strength("Correct Horse Battery Staple"));
echo "<br>";
var_dump(password_strength("Super Monkey Ball"));
echo "<br>";
var_dump(password_strength("Tr0ub4dor&3"));
echo "<br>";
var_dump(password_strength("abc123"));
echo "<br>";
var_dump(password_strength("sweet"));





// Best cURL function.... Ever!
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






/**
 * Return the elapsed time in a human readable format.
 *
 * Examples:
 *   Elapsed time up to 30 seconds looks like this: 0.031s
 *   Elapsed time over 30 seconds look like this: 2h 34m 42s
 *
 * @param $begin Begin timestamp with microseconds ($begin = microtime();)
 * @param $end End timestamp with microseconds ($end = microtime();)
 *
 * @return The elapsed time in a human readable format.
 */
// Example usage
// $begin = microtime();
// do stuff
// ...
// $end = microtime();
// echo elapsed_time($begin, $end);
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
            if ($time > 0) {
                $buffer .= "$time$unit ";
            }
        }
    } else {
        $buffer =  number_format($time_diff, 3) . 's';
    }

    return trim($buffer);
}






// Find out if your email has been read
error_reporting(0);
Header("Content-Type: image/jpeg");
//Get IP
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip=$_SERVER['HTTP_CLIENT_IP'];
}  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip=$_SERVER['REMOTE_ADDR'];
}
//Time
$actual_time = time();
$actual_day = date('Y.m.d', $actual_time);
$actual_day_chart = date('d/m/y', $actual_time);
$actual_hour = date('H:i:s', $actual_time);
//GET Browser
$browser = $_SERVER['HTTP_USER_AGENT'];
//LOG
$myFile = "log.txt";
$fh = fopen($myFile, 'a+');
$stringData = $actual_day . ' ' . $actual_hour . ' ' . $ip . ' ' . $browser . ' ' . "\r\n";
fwrite($fh, $stringData);
fclose($fh);
//Generate Image (Es. dimesion is 1x1)
$newimage = ImageCreate(1,1);
$grigio = ImageColorAllocate($newimage,255,255,255);
ImageJPEG($newimage);
ImageDestroy($newimage);







// Extract keywords from a webpage
$meta = get_meta_tags('http://www.emoticode.net/');
$keywords = $meta['keywords'];
$keywords = explode(',', $keywords );// Split keywords
$keywords = array_map( 'trim', $keywords );// Trim them
$keywords = array_filter( $keywords );// Remove empty values
print_r( $keywords );






// Create Data URI’s
// Data URI’s can be useful for embedding images into HTML/CSS/JS to save on HTTP requests. The following function will create a Data URI based on $file for easier embedding.
function data_uri($file, $mime) {
    $contents=file_get_contents($file);
    $base64=base64_encode($contents);
    echo "data:$mime;base64,$base64";
}








// Email error logs to yourself
// Our custom error handler
function nettuts_error_handler($number, $message, $file, $line, $vars){
    $email = "
        <p>An error ($number) occurred on line 
        <strong>$line</strong> and in the <strong>file: $file.</strong> 
        <p> $message </p>";
        
    $email .= "<pre>" . print_r($vars, 1) . "</pre>";
    
    $headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    
    // Email the error to someone...
    error_log($email, 1, 'you@youremail.com', $headers);

    // Make sure that you decide how to respond to errors (on the user's side)
    // Either echo an error message, or kill the entire project. Up to you...
    // The code below ensures that we only "die" if the error was more than
    // just a NOTICE. 
    if ( ($number !== E_NOTICE) && ($number < 2048) ) {
        die("There was an error. Please try again later.");
    }
}

// We should use our custom function to handle errors.
set_error_handler('nettuts_error_handler');

// Trigger an error... (var doesn't exist)
echo $somevarthatdoesnotexist;












// Add (th, st, nd, rd, th) to the end of a number
function ordinal($cdnl){ 
    $test_c = abs($cdnl) % 10; 
    $ext = ((abs($cdnl) %100 < 21 && abs($cdnl) %100 > 4) ? 'th' 
            : (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1) 
            ? 'th' : 'st' : 'nd' : 'rd' : 'th')); 
    return $cdnl.$ext; 
}  
for($i=1;$i<100;$i++){ 
    echo ordinal($i).'<br>'; 
} 
?>
