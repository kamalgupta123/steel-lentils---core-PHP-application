<?php
include_once(dirname(__FILE__)."/../../site_config.php");
$Encryption=new Encryption();
if(isset($_POST['action']) && $_POST['action']=='SaveTrucks'){ 
	$truck_id=0;
	if(!empty($_POST['truck_id'])){
		$truck_id=$Encryption->decode($_POST['truck_id']);
	}
    $data = send_rest(array(
        "function" => "Admin/SaveTrucks",
		"data" => $_POST,
		"truck_id" => $truck_id
    ));
	header('Content-type: application/json');
	echo json_encode($data);
}
if(isset($_POST['action']) && $_POST['action']=='saveIsEnabled'){ 
	$result = send_rest(array(
		"function" => "Admin/UpdateTrucksIsEnabled",
		"data" => $_POST,
		"truck_id" => $Encryption->decode($_POST['truck_id'])
	));
	header('Content-type: application/json');
	echo json_encode($result);
}
?>