<?php
$WEEK_ARRAY = array( 1=>"monday", 2=>"tuesday",  3=>"wednesday", 4=>"thursday", 5=>"friday", 6=>"saturday", 7=>"sunday" );

function safe_output($str){
	return htmlspecialchars($str,ENT_QUOTES);
}


function get_enabled_status($is_enabled){
	if($is_enabled==1){
		return '<span class="enabled text-success">Yes</span>';
	}else{
		return '<span class="not_enabled text-danger">No</span>';
	}
}

  function Rev_Sort($val)
  {
	  if($val=="Asc")
	  {
		 return "Desc";
	  }
	  else
	  {
		  return "Asc";
	  }
  }

function send_rest($inputdata, $ECHO=0){
	$array = htmlspecialchars_decode_deep2((array) $inputdata);
	$array['api_key'] = WEB_API_KEY;
	$FAST_REST=0;
	if($FAST_REST == 1){
		// call the requested function directly
		$FunctionName = $array['function'];
		$Restful = new Restful();
		$result2 = $Restful->$FunctionName($array);
	}
	else{

		$data = "";
		$MULTIPART_BOUNDARY = '--------------------------'.microtime(true);
		$data .= "--".$MULTIPART_BOUNDARY."\r\n";
		if(!empty($array['FILES'])){
			foreach($array['FILES'] as $fname => $file){
				if(!empty($file['name'])){
					$ps_f_array = array();
					if(!is_array($file['name'])){
						$ps_f_array['name'][0] = $file['name'];
						$ps_f_array['tmp_name'][0] = $file['tmp_name'];
						$ps_f_array['type'][0] = $file['type'];
						$ps_f_array['error'][0] = $file['error'];
						$ps_f_array['size'][0] = $file['size'];
					}
					else{
						$ps_f_array = $file;
					}
					// print_r($ps_f_array);
					for($pp=0; $pp<count($ps_f_array['name']); $pp++){
						$filename = $ps_f_array['tmp_name'][$pp];
						if(!empty($filename)){
						if(!is_array($file['name'])){
							$FORM_FIELD = $fname;
						}
						else{
							$FORM_FIELD = $fname."[$pp]";
						}
						$file_contents = file_get_contents($filename);
						$data .= "Content-Disposition: form-data; name=\"".$FORM_FIELD."\"; filename=\"".$ps_f_array['name'][$pp]."\"\r\n".
						"Content-Type: ".$ps_f_array['type'][$pp]."\r\n\r\n".
						$file_contents."\r\n";
						$data .= "--".$MULTIPART_BOUNDARY."\r\n";
						}
					}
				}
			}
		}
		if(isset($array['FILES']))
		{
			unset($array['FILES']);
		}
		$json = json_encode($array, true);
		$data .= "--".$MULTIPART_BOUNDARY."\r\n".
		"Content-Disposition: form-data; name=\"data\"\r\n\r\n".
		"$json\r\n";

		$data .= "--".$MULTIPART_BOUNDARY."--\r\n";
		$options = array(
			'http' => array(
				'method' => 'POST',
				'content' => $data ,
				'header'=> "Content-Type: multipart/form-data; boundary=" .$MULTIPART_BOUNDARY
			),
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			)
		);

		$url = API_URL;
		$context = stream_context_create( $options );
		if($ECHO == 1){
			echo $result = file_get_contents( $url, false, $context );
		}
		else{
			$result = file_get_contents( $url, false, $context );
		}
			$result2 = json_decode($result, true);
	}

		$result3 = stripslashes_deep2($result2);

	switch(json_last_error()) {
		case JSON_ERROR_DEPTH:
		echo ' - Maximum stack depth exceeded';
		break;
		case JSON_ERROR_CTRL_CHAR:
		echo ' - Unexpected control character found';
		break;
		case JSON_ERROR_SYNTAX:
		echo ' - Syntax error, malformed JSON';
		break;
	}
	return $result3;
}

function stripslashes_deep2($value) {
	if ( is_array($value) ) {
		$value = array_map('stripslashes_deep2', $value);
	} elseif ( is_object($value) ) {
		$vars = get_object_vars( $value );
		foreach ($vars as $key=>$data) {
			$value->{$key} = stripslashes_deep2( $data );
		}
	} elseif ( is_string( $value ) ) {
		$value = sanitize_output($value);
	}
	return $value;
}

function sanitize_output($data) {
	$data = trim($data);
	$data = htmlspecialchars($data,ENT_QUOTES);
	return $data;
}

function htmlspecialchars_decode_deep2($value) {
	 if ( is_array($value) ) {
	  $value = array_map('htmlspecialchars_decode_deep2', $value);
	 } elseif ( is_object($value) ) {
	  $vars = get_object_vars( $value );
	  foreach ($vars as $key=>$data) {
	   $value->{$key} = htmlspecialchars_decode_deep2( $data );
	  }
	 } elseif ( is_string( $value ) ) {
	  $value = stripslashes(htmlspecialchars_decode($value, ENT_QUOTES));
	 }
	 return $value;
}

function get_img_instructions($extra_instrictions){
	$file_size = bytesToSize(MAX_FILE_SIZE);
	$allowed_extensions = array();
	$allowed_extensions = implode(" , ",json_decode(IMAGE_EXTENSIONS));
	return "<div class='img_instructions'>Maximum upload size : <b>".$file_size."</b> <br> Allowed Extensions : <b>".$allowed_extensions."</b>".$extra_instrictions."</div>";
}

function safe_str($str){
	global $con;
	return $con->real_escape_string(trim($str));
}

function p_round($price){
	return number_format(round($price,2), 2, '.', '');
}

function ui_time($time24hour){
	// 24-hour time to 12 time 
	try
	{
	$time=new DateTime($time24hour);
	}
	catch (Exception $e)
	{
		$time="00:00";
	}
	return $time->format("h:i a");
}

function ui_date($date_format){
	try
	{
	$date=new DateTime($date_format);
	}
	catch (Exception $e)
	{
		$date="0000-00-00";
	}
	return $date->format("d-m-Y");
}

function ui_datetime($datetime){
	if($datetime!=""){
		return ui_date($datetime)." ".ui_time($datetime); 
	}
}

function mysql_time($time12hour){
	// 12-hour time to mysql time 
	try
	{
	$time=new DateTime($time24hour);
	}
	catch (Exception $e)
	{
		$time="00:00:00";
	}
	return $time->format("H:i:s");
}

function mysql_date($mydate){
	try
	{
	$date=new DateTime($mydate);
	}
	catch (Exception $e)
	{
		$date="0000-00-00";
	}
	return $date->format("Y-m-d");
}

function mysql_datetime($datetime){
	if($datetime!=""){
		return mysql_date($datetime)." ".mysql_time($datetime); 
	}
}

function url_slug($str, $options = array()) {
	// Make sure string is in UTF-8 and strip invalid UTF-8 characters
	$str = mb_convert_encoding((string) strtolower($str), 'UTF-8', mb_list_encodings());
	
	$defaults = array(
		'delimiter' => '-',
		'limit' => null,
		'lowercase' => true,
		'replacements' => array(),
		'transliterate' => false,
	);
	
	// Merge options
	$options = array_merge($defaults, $options);
	
	$char_map = array(
		// Latin
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
		'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'O' => 'O', 
		'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'U' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
		'ß' => 'ss', 
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
		'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'o' => 'o', 
		'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'u' => 'u', 'ý' => 'y', 'þ' => 'th', 
		'ÿ' => 'y',

		// Latin symbols
		'©' => '(c)',

		// Greek
		'?' => 'A', '?' => 'B', 'G' => 'G', '?' => 'D', '?' => 'E', '?' => 'Z', '?' => 'H', 'T' => '8',
		'?' => 'I', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => '3', '?' => 'O', '?' => 'P',
		'?' => 'R', 'S' => 'S', '?' => 'T', '?' => 'Y', 'F' => 'F', '?' => 'X', '?' => 'PS', 'O' => 'W',
		'?' => 'A', '?' => 'E', '?' => 'I', '?' => 'O', '?' => 'Y', '?' => 'H', '?' => 'W', '?' => 'I',
		'?' => 'Y',
		'a' => 'a', 'ß' => 'b', '?' => 'g', 'd' => 'd', 'e' => 'e', '?' => 'z', '?' => 'h', '?' => '8',
		'?' => 'i', '?' => 'k', '?' => 'l', 'µ' => 'm', '?' => 'n', '?' => '3', '?' => 'o', 'p' => 'p',
		'?' => 'r', 's' => 's', 't' => 't', '?' => 'y', 'f' => 'f', '?' => 'x', '?' => 'ps', '?' => 'w',
		'?' => 'a', '?' => 'e', '?' => 'i', '?' => 'o', '?' => 'y', '?' => 'h', '?' => 'w', '?' => 's',
		'?' => 'i', '?' => 'y', '?' => 'y', '?' => 'i',

		// Turkish
		'S' => 'S', 'I' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'G' => 'G',
		's' => 's', 'i' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'g' => 'g', 

		// Russian
		'?' => 'A', '?' => 'B', '?' => 'V', '?' => 'G', '?' => 'D', '?' => 'E', '?' => 'Yo', '?' => 'Zh',
		'?' => 'Z', '?' => 'I', '?' => 'J', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => 'O',
		'?' => 'P', '?' => 'R', '?' => 'S', '?' => 'T', '?' => 'U', '?' => 'F', '?' => 'H', '?' => 'C',
		'?' => 'Ch', '?' => 'Sh', '?' => 'Sh', '?' => '', '?' => 'Y', '?' => '', '?' => 'E', '?' => 'Yu',
		'?' => 'Ya',
		'?' => 'a', '?' => 'b', '?' => 'v', '?' => 'g', '?' => 'd', '?' => 'e', '?' => 'yo', '?' => 'zh',
		'?' => 'z', '?' => 'i', '?' => 'j', '?' => 'k', '?' => 'l', '?' => 'm', '?' => 'n', '?' => 'o',
		'?' => 'p', '?' => 'r', '?' => 's', '?' => 't', '?' => 'u', '?' => 'f', '?' => 'h', '?' => 'c',
		'?' => 'ch', '?' => 'sh', '?' => 'sh', '?' => '', '?' => 'y', '?' => '', '?' => 'e', '?' => 'yu',
		'?' => 'ya',

		// Ukrainian
		'?' => 'Ye', '?' => 'I', '?' => 'Yi', '?' => 'G',
		'?' => 'ye', '?' => 'i', '?' => 'yi', '?' => 'g',

		// Czech
		'C' => 'C', 'D' => 'D', 'E' => 'E', 'N' => 'N', 'R' => 'R', 'Š' => 'S', 'T' => 'T', 'U' => 'U', 
		'Ž' => 'Z', 
		'c' => 'c', 'd' => 'd', 'e' => 'e', 'n' => 'n', 'r' => 'r', 'š' => 's', 't' => 't', 'u' => 'u',
		'ž' => 'z', 

		// Polish
		'A' => 'A', 'C' => 'C', 'E' => 'e', 'L' => 'L', 'N' => 'N', 'Ó' => 'o', 'S' => 'S', 'Z' => 'Z', 
		'Z' => 'Z', 
		'a' => 'a', 'c' => 'c', 'e' => 'e', 'l' => 'l', 'n' => 'n', 'ó' => 'o', 's' => 's', 'z' => 'z',
		'z' => 'z',

		// Latvian
		'A' => 'A', 'C' => 'C', 'E' => 'E', 'G' => 'G', 'I' => 'i', 'K' => 'k', 'L' => 'L', 'N' => 'N', 
		'Š' => 'S', 'U' => 'u', 'Ž' => 'Z',
		'a' => 'a', 'c' => 'c', 'e' => 'e', 'g' => 'g', 'i' => 'i', 'k' => 'k', 'l' => 'l', 'n' => 'n',
		'š' => 's', 'u' => 'u', 'ž' => 'z'
	);
	
	// Make custom replacements
	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	
	// Transliterate characters to ASCII
	if ($options['transliterate']) {
		$str = str_replace(array_keys($char_map), $char_map, $str);
	}
	
	// Replace non-alphanumeric characters with our delimiter
	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	
	// Remove duplicate delimiters
	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	
	// Truncate slug to max. characters
	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	
	// Remove delimiter from ends
	$str = trim($str, $options['delimiter']);
	
	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}

class Encryption {
	public $skey;
	public function __construct($skey = ENCRYPTION_KEY) {
		$this->skey	= $skey;
	}
	public function safe_b64encode($string) {
		$data = base64_encode($string);
		$data = str_replace(array('+','/','='),array('-','_',''),$data);
		return $data;
	}
	public function safe_b64decode($string) {
		$data = str_replace(array('-','_'),array('+','/'),$string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}
	public function encode($value){
		if(!$value){return false;}
		$crypttext = openssl_encrypt($value,"AES-128-ECB",$this->skey);
		return trim($this->safe_b64encode($crypttext));	
	}
	public function decode($value){
		if(!$value){return false;}
		$crypttext = $this->safe_b64decode($value);
		$decrypttext = openssl_decrypt($crypttext,"AES-128-ECB",$this->skey);
		return trim($decrypttext);
	}
}

function time_elapsed_string($datetime, $full = false, $ago_only=0) {
	$now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hr',
        'i' => 'min',
        's' => 'sec',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
	//print_r($string);
	if ($ago_only==1 || array_key_exists("h",$string) || array_key_exists("i",$string) || array_key_exists("s",$string)){
	    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}else{
	    $date = date_create($datetime);
	    return ui_datetime($datetime);
	}
}

function get_sort_data($request, $column){
	$sort_link = $request;
	$sort_link["SortType"]="DESC";
	$sort_class = 'fa-sort';
	$sort_link["SortOn"]=$column;
	if($request['SortOn']==$column){ 
		if($request['SortType']=="DESC"){
			$sort_link["SortType"]="ASC";
			$sort_class = 'fa-sort-desc';
		}
		else{
			$sort_link["SortType"]="DESC";
			$sort_class = 'fa-sort-asc';
		}
	}
	return (object) array("link"=>$sort_link, "css"=>$sort_class);
}

function getExcerpt($str, $maxLength=100) {
	$startPos=0;
	if(strlen($str) > $maxLength) {
		$excerpt   = substr($str, $startPos, $maxLength-3);
		// $lastSpace = strrpos($excerpt, ' ');
		// $excerpt   = substr($excerpt, 0, $lastSpace);
		$excerpt  .= '...';
	} else {
		$excerpt = $str;
	}
	
	return $excerpt;
}

function bytesToSize($bytes) {
	$sizes = array('Bytes', 'KB', 'MB', 'GB', 'TB');
	if ($bytes == 0) return '0 Byte';
	$i = (int)(floor(log($bytes) / log(1024)));
	return round($bytes / pow(1024, $i), 2) . ' ' . $sizes[$i];
}

function get_is_active_html($is_active){
	$name = "Not Active";
	$class="not_active";
	if($is_active==1){
		$name = "Active";
		$class="active";
	}
	echo '<span class="is_active_html '.$class.'">'.$name.'</span>';
}

function get_pagination($request, $total_pages, $total_records, $base_path="",$pagging_list){
	global $ROWS_ARRAY;
	global $row_size_array;
	$ROW_ARRAY=$ROWS_ARRAY;
	$pagination_link = $request;
	$currentpage=$request['PageNumber'];
	$perpage=$request['RowSize'];
	$currentpage=min(max(1,$currentpage),$total_pages);
	$firsthalf=($currentpage<$total_pages/2)?'true':'false';
	$offset = ($request['PageNumber']-1)*$request['RowSize'];
	$offset=$offset+1;
	$last=$total_pages;
	if($last<1)
	{
		$last=1;
	}
	if($request['PageNumber']<1)
	{
		$request['PageNumber']=1;
	}
	else if($request['PageNumber']>$last)
	{
	$request['PageNumber']=$last;
	}
	$paginationCtrls='';
	$lpm1=$last-1;
	if($last!=1)
	{
        if($request['PageNumber'] > 1)
		{
            $previous=$request['PageNumber']-1;
			$pagination_link['PageNumber'] = $previous;
			$paginationCtrls.='<a class="load_ajax " href="'.SITE_URL."/".$base_path."/?".http_build_query($pagination_link).'"><span><i class="fa fa-angle-left"></i></span></a>';
		}
        if($last< 11)  
        {   
            for ($i=1;$i<=$last;$i++)
            {
            	$check_active = '';
                if($i==$request['PageNumber'])
				{
					$check_active = "active";
				}
                    $pagination_link['PageNumber'] = $i;
					$paginationCtrls.='<a  class="load_ajax '.$check_active.'" href="'.SITE_URL."/".$base_path."/?".http_build_query($pagination_link).'">'.$i.'</a>';                 			
			}
		}
		elseif($last>=11)   
		{
			if($request['PageNumber'] <7)        
			{
				for($i=1;$i<8;$i++)
				{
					$check_active = '';
					if($i==$request['PageNumber'])
					{
						$check_active = "active";
					}
					//else
					//{
						$pagination_link['PageNumber'] = $i;
						$paginationCtrls.='<a  class="load_ajax '.$check_active.'" href="'.SITE_URL."/".$base_path."/?".http_build_query($pagination_link).'">'.$i.'</a>';
					//}            
				}
				$paginationCtrls.= "...";
				for($i=$last-2;$i<=$last;$i++)
				{
					$check_active = '';
					if($i==$request['PageNumber'])
					{
						$check_active = "active";
					}
					$pagination_link['PageNumber'] = $i;
					$paginationCtrls.='<a  class="load_ajax '.$check_active.'" href="'.SITE_URL."/".$base_path."/?".http_build_query($pagination_link).'">'.$i.'</a>';
				}   
			}
			elseif($last-6>$request['PageNumber']&&$request['PageNumber']>6)
			{
				for($i=1;$i<=3;$i++)
				{
					$check_active = '';
					if($i==$request['PageNumber'])
					{
						$check_active = "active";
					}
					$pagination_link['PageNumber'] = $i;
					$paginationCtrls.='<a  class="load_ajax '.$check_active.'" href="'.SITE_URL."/".$base_path."/?".http_build_query($pagination_link).'">'.$i.'</a>';
				}
				$paginationCtrls.="...";
				for($i = $request['PageNumber']-2;$i<=$request['PageNumber']+2;$i++)
				{
					$check_active = '';
					if($i==$request['PageNumber'])
					{
						$check_active = "active";
					}
					//else
					//{
						$pagination_link['PageNumber'] = $i;
						$paginationCtrls.='<a  class="load_ajax '.$check_active.'" href="'.SITE_URL."/".$base_path."/?".http_build_query($pagination_link).'">'.$i.'</a>';
					//}
				}
				$paginationCtrls.= "...";
				for($i=$last-2;$i<=$last;$i++)
				{
					$check_active = '';
					if($i==$request['PageNumber'])
					{
						$check_active = "active";
					}
					$pagination_link['PageNumber'] = $i;
					$paginationCtrls.='<a  class="load_ajax '.$check_active.'" href="'.SITE_URL."/".$base_path."/?".http_build_query($pagination_link).'">'.$i.'</a>';
				}    
			}
			else
			{
				for($i=1;$i<=3;$i++)
				{
					$check_active = '';
					if($i==$request['PageNumber'])
					{
						$check_active = "active";
					}
					$pagination_link['PageNumber'] = $i;
					$paginationCtrls.='<a  class="load_ajax '.$check_active.'" href="'.SITE_URL."/".$base_path."/?".http_build_query($pagination_link).'">'.$i.'</a>';
				}
				$paginationCtrls.="...";
				for($i=$last-6;$i<=$last;$i++)
				{
					$check_active = '';
					if($i==$request['PageNumber'])
					{
						$check_active = "active";
					}
					//else
					//{
						$pagination_link['PageNumber'] = $i;
						$paginationCtrls.='<a  class="load_ajax '.$check_active.'" href="'.SITE_URL."/".$base_path."/?".http_build_query($pagination_link).'">'.$i.'</a>';
					//}                
				}
			}
		}  
		if($request['PageNumber']<$i-1)
		{
			$next=$request['PageNumber']+1;
			$pagination_link['PageNumber'] = $next;
			$paginationCtrls.='<a class="load_ajax" href="'.SITE_URL."/".$base_path."/?".http_build_query($pagination_link).'"><span><i class="fa fa-angle-right"></i></span></a>';
		}
	} 
	?>
	<div class="row p_pagination_conatiner">
		<div class="col-sm-4 pagination_counter">Showing <?php echo $offset;?> to <?php echo $offset+count($pagging_list)-1;?> of <?php echo $total_records;?> entries</div>
		<div class="col-sm-8 pagination_counter pagination_pageNumber">
			<?php echo $paginationCtrls;?>
		</div>
	</div>
	<?php
}

 
function get_pagination_search($request, $total_pages, $total_records, $base_path=""){
	global $ROWS_ARRAY;
	$pagination_link = $request;
	?>
	<!-- <div class="pagination_container">  -->
		<div class="pagination_inner set-page">
		  <ul class="pagination pull-left">
		    <li class="page-item ">
		    	<?php if($request['PageNumber']!=1){ $pagination_link['PageNumber'] = $request['PageNumber']-1; ?>
		      <a class="page-link search-page-link load_ajax" href="<?php echo SITE_URL."/".$base_path."/?".http_build_query($pagination_link); ?>" aria-label="Previous">
		        <i class="far fa-chevron-left size-icon" aria-hidden="true"></i>
		      </a>
		        <?php }else{ ?> 
		         <a class="page-link search-page-link" href="javascript:void(0);" aria-label="Previous">
		        	<i class="far fa-chevron-left size-icon" aria-hidden="true"></i>
		   		</a>
		        <?php 
		    	} 
		    	?>
		    </li>
		   <?php 
			   for ($i=1; $i <= $total_pages; $i++) { 
			   		echo '<li class="page-item ';
			   		if(isset($request['PageNumber']) && $request['PageNumber']==$i){
						echo 'active';			   			
			   		}
			   		echo ' ">';
			   		$pagination_link['PageNumber'] = $i;
			   		echo '<a class="page-link search-page-link load_ajax" href=" '; 
					echo SITE_URL."/".$base_path."/?".http_build_query($pagination_link);
			   		echo ' ">'.$i.'</a></li>';
			   }
		   ?>
		    <li class="page-item"><?php if($request['PageNumber']!=$total_pages){ $pagination_link['PageNumber'] = $request['PageNumber']+1; ?>
		      <a class="page-link search-page-link load_ajax" href="<?php echo SITE_URL."/".$base_path."/?".http_build_query($pagination_link); ?>" aria-label="Next">
		        <i class="far fa-chevron-right size-icon" aria-hidden="true"></i>
		      </a>
		        <?php 
		    	}else{ ?>
			        <a class="page-link search-page-link" href="javascript:void(0);" aria-label="Next">
			        	<i class="far fa-chevron-right size-icon" aria-hidden="true"></i>
			    	</a>

		        <?php 
		        }
		        ?>
		    </li>
		  </ul>
		</div>
	<!-- </div> -->
	<?php
}

function get_time_array($time_slot_size=1){
	if($time_slot_size==2){
		$time_difference = 30;
	}else{
		$time_difference = 15;
	}
	$TIME_ARRAY = array();
	$show_format = "h:i a";
	$value_format = "H:i:s";
	$starttime = date($show_format, strtotime("00:00:00"));
	$time = new DateTime($starttime);
	$interval = new DateInterval('PT'.$time_difference.'M');
	$temptime = $time->format($show_format);
	do {
		$v_time = $time->format($value_format);
		$TIME_ARRAY[$v_time] = $temptime;
		$time->add($interval);
		$temptime = $time->format($show_format);
	} while ($temptime !== $starttime);
	
	return $TIME_ARRAY;
}

function output($str){
	// return htmlspecialchars($str);
	return $str;
}

// project functions

?>