/*--------------------------------------------------------------------------------
| Find out if your email has been read
|--------------------------------------------------------------------------------*/
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

/*--------------------------------------------------------------------------------
| Extract keywords from a webpage
|--------------------------------------------------------------------------------*/
$meta = get_meta_tags('http://www.emoticode.net/');
$keywords = $meta['keywords'];
$keywords = explode(',', $keywords );// Split keywords
$keywords = array_map( 'trim', $keywords );// Trim them
$keywords = array_filter( $keywords );// Remove empty values
print_r( $keywords );

/*--------------------------------------------------------------------------------
| Get info about your memory usage
|--------------------------------------------------------------------------------*/
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
