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
