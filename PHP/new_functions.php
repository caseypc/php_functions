function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
 
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
 
    $bytes /= pow(1024, $pow); 
 
    return round($bytes, $precision) . ' ' . $units[$pow]; 
}


function isAjax() {
return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}



function file_newname($path, $filename){
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




function createthumb($name,$filename,$new_w,$new_h,$type){
	$system=explode('.',$name);
	if ($type == "jpg" || $type == "jpeg"){
		$src_img=imagecreatefromjpeg($name);
	}
	if ($type == "png"){
		$src_img=imagecreatefrompng($name);
	}
	if ($type == "gif"){
		$src_img=imagecreatefromgif($name);
	}
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
	
	if ($type == "png")
	{
		imagepng($dst_img,$filename); 
	}
	if ($type == "gif")
	{
		imagegif($dst_img,$filename); 
	} 
	if ($type == "jpg" || $type == "jpeg")
	{	
		imagejpeg($dst_img,$filename); 
	}
	imagedestroy($dst_img); 
	imagedestroy($src_img); 
}




/*
Backup your MySQL database
backup_tables('localhost','username','password','blog');
*/
/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name,$tables = '*')
{
 
	$link = mysql_connect($host,$user,$pass);
	mysql_select_db($name,$link);
 
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
 
	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		for ($i = 0; $i < $num_fields; $i++) 
		{
			$return.= 'DROP TABLE '.$table.';';
 
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$return.= "\n\n".$row2[1].";\n\n";
 
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
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




/**
 * AutoLinkUrls()
 * 
 * @param mixed $str
 * @param bool $popup
 * @return void
 Automatically turn all text urls into working Hyperlinks
 Does not require http in the text to link. It must start with www however. Optionaly make the links popup in a new window.
 */
function AutoLinkUrls($str,$popup = FALSE){
    if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches)){
		$pop = ($popup == TRUE) ? " target=\"_blank\" " : "";
		for ($i = 0; $i < count($matches['0']); $i++){
			$period = '';
			if (preg_match("|\.$|", $matches['6'][$i])){
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


Google bot crawl notifier

$email = "test@test.com";
 
if(eregi("googlebot",$HTTP_USER_AGENT))
{
    if ($QUERY_STRING != "")
    {
        $url = "http://".$SERVER_NAME.$PHP_SELF.'?'.$QUERY_STRING;
    } else {
        $url = "http://".$SERVER_NAME.$PHP_SELF;
    }
    $date = date("F j, Y, g:i a");
    mail($email, "[Googlebot] $url", "$date - Google crawled $url");
} 








/*
$url = "http://google.com";
$source = display_sourcecode($url);
echo $source;
*/
function display_sourcecode($url)
{
$lines = file($url);
$output = "";
foreach ($lines as $line_num => $line) { 
	// loop thru each line and prepend line numbers
	$output.= "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br>\n";
}
}








/*
Show Facebook likes
$page = "facebookpagename";
$count = fb_fan_count($page);
echo $count;
?>
*/
function fb_fan_count($facebook_name)
{
    $data = json_decode(file_get_contents("https://graph.facebook.com/".$facebook_name));
    $likes = $data->likes;
    return $likes;
}











/*
Convert URLs in string to hyperlinks
*
/
function makeClickableLinks($text) 
{  
 $text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_+.~#?&//=]+)',  
 '<a href="\1">\1</a>', $text);  
 $text = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_+.~#?&//=]+)',  
 '\1<a href="http://\2">\2</a>', $text);  
 $text = eregi_replace('([_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3})',  
 '<a href="mailto:\1">\1</a>', $text);  
  
return $text;  
}








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









/*
zip a file
$files=array('file1.jpg', 'file2.jpg', 'file3.gif');  
create_zip($files, 'myzipfile.zip', true);
*/
function create_zip($files = array(),$destination = '',$overwrite = false) {  
    //if the zip file already exists and overwrite is false, return false  
    if(file_exists($destination) && !$overwrite) { return false; }  
    //vars  
    $valid_files = array();  
    //if files were passed in...  
    if(is_array($files)) {  
        //cycle through each file  
        foreach($files as $file) {  
            //make sure the file exists  
            if(file_exists($file)) {  
                $valid_files[] = $file;  
            }  
        }  
    }  
    //if we have good files...  
    if(count($valid_files)) {  
        //create the archive  
        $zip = new ZipArchive();  
        if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {  
            return false;  
        }  
        //add the files  
        foreach($valid_files as $file) {  
            $zip->addFile($file,$file);  
        }  
        //debug  
        //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;  
          
        //close the zip -- done!  
        $zip->close();  
          
        //check to make sure the file exists  
        return file_exists($destination);  
    }  
    else  
    {  
        return false;  
    }  
}












/*
unzip a file
unzip('test.zip','unziped/test'); //File would be unzipped in unziped/test folder
*/
function unzip($location,$newLocation)
{
        if(exec("unzip $location",$arr)){
            mkdir($newLocation);
            for($i = 1;$i< count($arr);$i++){
                $file = trim(preg_replace("~inflating: ~","",$arr[$i]));
                copy($location.'/'.$file,$newLocation.'/'.$file);
                unlink($location.'/'.$file);
            }
            return TRUE;
        }else{
            return FALSE;
        }
}












/*
Resize a image
*/
function resize_image($filename, $tmpname, $xmax, $ymax)  
{  
    $ext = explode(".", $filename);  
    $ext = $ext[count($ext)-1];  
  
    if($ext == "jpg" || $ext == "jpeg")  
        $im = imagecreatefromjpeg($tmpname);  
    elseif($ext == "png")  
        $im = imagecreatefrompng($tmpname);  
    elseif($ext == "gif")  
        $im = imagecreatefromgif($tmpname);  
      
    $x = imagesx($im);  
    $y = imagesy($im);  
      
    if($x <= $xmax && $y <= $ymax)  
        return $im;  
  
    if($x >= $y) {  
        $newx = $xmax;  
        $newy = $newx * $y / $x;  
    }  
    else {  
        $newy = $ymax;  
        $newx = $x / $y * $newy;  
    }  
      
    $im2 = imagecreatetruecolor($newx, $newy);  
    imagecopyresized($im2, $im, 0, 0, 0, 0, floor($newx), floor($newy), $x, $y);  
    return $im2;   
}








/*
Read CSV file
$csvFile = "test.csv";
$csv = readCSV($csvFile);
$a = csv[0][0]; // This will get value of Column 1 & Row 1
*/
function readCSV($csvFile){
	$file_handle = fopen($csvFile, 'r');
	while (!feof($file_handle) ) {
		$line_of_text[] = fgetcsv($file_handle, 1024);
	}
	fclose($file_handle);
	return $line_of_text;
}





/*
Creating a CSV file a array
$data[0] = "apple";
$data[1] = "oranges";
generateCsv($data, $delimiter = ',', $enclosure = '"');
*/
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









/*
Get latest tweet(s) from Twitter account
$handle = "twittername";
my_twitter($handle);
*/
function my_twitter($username) 
{
 	$no_of_tweets = 1;
 	$feed = "http://search.twitter.com/search.atom?q=from:" . $username . "&rpp=" . $no_of_tweets;
 	$xml = simplexml_load_file($feed);
	foreach($xml->children() as $child) {
		foreach ($child as $value) {
			if($value->getName() == "link") $link = $value['href'];
			if($value->getName() == "content") {
				$content = $value . "";
		echo '<p class="twit">'.$content.' <a class="twt" href="'.$link.'" title="">&nbsp; </a></p>';
			}	
		}
	}	
}




/*
Encode email address with htmlenties
*/
function encode_email($email='info@domain.com', $linkText='Contact Us', $attrs ='class="emailencoder"' )  
{  
    // remplazar aroba y puntos  
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
    foreach($email as $e)  
    {  
            $encoded .= "document.write('$e');";  
    }  
    $encoded .= "document.write('$part3');";  
    foreach($linkText as $l)  
    {  
            $encoded .= "document.write('$l');";  
    }  
    $encoded .= "document.write('$part4');";  
    $encoded .= '</script>';  
  
    return $encoded;  
}









/*
Truncate text at word break
*/
function myTruncate($string, $limit, $break=".", $pad="...") {   
    // return with no change if string is shorter than $limit    
    if(strlen($string) <= $limit)   
        return $string;   
      
    // is $break present between $limit and the end of the string?    
    if(false !== ($breakpoint = strpos($string, $break, $limit))) {  
        if($breakpoint < strlen($string) - 1) {   
            $string = substr($string, 0, $breakpoint) . $pad;   
        }   
    }  
    return $string;   
}  
/***** Example ****/  
$short_string=myTruncate($long_string, 100, ' '); 














/*
Calculate age using birth date
*/
function age($date){
    $time = strtotime($date);
    if($time === false){
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
