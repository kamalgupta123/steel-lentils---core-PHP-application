<?php
include_once(__DIR__ ."/../api_site_config.php");
include_once(API_BASE_DIR."/restful/autoload.php");
// Cross Origin headers
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

}

header('Content-type: application/json'); 
$json = file_get_contents('php://input');
$json_output = array();

if(!$_POST && !$_GET && !$json){

    $output = array(
        "status"=>false,
        "data" => array(),
        "msg"=>"Not valid Request or empty params."
    );
    echo json_encode($output);die;
} else{
	if($_POST && !$json && !$_GET){
		$json_output = json_decode($_POST['data'],true);
	}if($_GET && !$json && !$_POST){
		$json_output = $_GET;
	}  elseif (!$_POST && $json && !$_GET){
		$json_output = json_decode($json,true);
	}
}
if(!empty($json_output['api_key']) && ($json_output['api_key'] == APP_API_KEY || $json_output['api_key'] == WEB_API_KEY)) {
	if(!empty($json_output['function'])){
		$FunctionName = $json_output['function'];
	}
	else{
		echo json_encode(array(
				"status" => 0,
				"errors" => array(
					"Function Name is Missing"
				)
			));
		die;
	}
	if(strpos($json_output['function'],"/")===false){
		$FunctionName = "Common/".$json_output['function'];
	}
	
	$FunctionData=explode("/",$FunctionName);
	$class_name = $FunctionData[0];
	$function_name = $FunctionData[1];
	//echo $class_name;
	if(class_exists($class_name)){
		$Obj = new $class_name();
		if(method_exists($Obj, $function_name)){
			$output = $Obj->$function_name($json_output);
			echo json_encode($output);
		}
		else{
			echo json_encode(array(
					"status" => 0,
					"errors" => array(
						"Function not found name"
					)
				));
			die;    
		}
	}
	else {
		echo json_encode(array(
				"status" => 0,
				"errors" => array(
					"Class not found name"
				)
			));
		die;
	}
} else{
	echo json_encode(array(
			"status" => 0,
			"errors" => array(
				"Invalid api key"
			)
		));
	die;
}
?>