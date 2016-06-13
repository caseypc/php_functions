<?php
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
?>
