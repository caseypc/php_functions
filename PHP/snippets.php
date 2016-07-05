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

















/*
Compress multiple CSS files
*/
header('Content-type: text/css');
ob_start('compress_css');
function compress_css($buffer) {
/* remove comments in css file */
$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
/* also remove tabs, spaces, newlines, etc. */
$buffer = str_replace(array("\r", "\n", "\r\n", "\t", '  ', '    ', '    '), '', $buffer);
return $buffer;
}

/* a list of your css files */
include('style.css');
include('css/menu.css');
include('css/typography.css');
include('css/print.css');
include('inc/css/footer.css');
ob_end_flush();











/*
Tracking pixel
*/
$img = imagecreate(1, 1);
$color['lime'] = imagecolorallocate($img, 0x00, 0xFF, 0x00);
imagecolortransparent($img, $color['lime']);
header("Content-type: image/png");
imagepng($img);
imagedestory($img);








<?php
/*
Simple PHP cacheing
*/
// define the path and name of cached file
$cachefile = 'cached-files/'.date('M-d-Y').'.php';
// define how long we want to keep the file in seconds. I set mine to 5 hours.
$cachetime = 18000;
// Check if the cached file is still fresh. If it is, serve it up and exit.
if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
include($cachefile);
exit;
}
// if there is either no file OR the file to too old, render the page and capture the HTML.
ob_start();
?>
<html>
output all your html here.
</html>
<?php
// We're done! Save the cached content to a file
$fp = fopen($cachefile, 'w');
fwrite($fp, ob_get_contents());
fclose($fp);
// finally send browser output
ob_end_flush();
?>











/*
Block IPs from acessing website
*/
if ( !file_exists('blocked_ips.txt') ) {
$deny_ips = array(
'127.0.0.1',
'192.168.1.1',
'83.76.27.9',
'192.168.1.163'
);
} else {
$deny_ips = file('blocked_ips.txt');
}
// read user ip adress:
$ip = isset($_SERVER['REMOTE_ADDR']) ? trim($_SERVER['REMOTE_ADDR']) : '';

// search current IP in $deny_ips array
if ( (array_search($ip, $deny_ips))!== FALSE ) {
// address is blocked:
echo 'Your IP adress ('.$ip.') was blocked!';
exit;
}
