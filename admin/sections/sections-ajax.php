<?php
include_once(__DIR__ . "/../admin-config.php");
include_once(BASE_DIR . "/common_functions.php");
include_once(ADMIN_DIR . "/admin-functions.php");
$Encryption = new Encryption();
if(isset($_POST['action']) && $_POST['action']=='saveIsEnabled'){ 
	$result = send_rest(array(
		"function" => "Admin/UpdateSectionIsEnabled",
		"data" => $_POST,
		"section_id" => $Encryption->decode($_POST['section_id'])
	));
	header('Content-type: application/json');
	echo json_encode($result);
}
if(isset($_POST['CheckIfCustomLengthAllowed'])){
//if(isset($_POST['action']) && $_POST['action']=="CheckIfCustomLengthAllowed"){
	$Encryption=new Encryption();
	$product_range_id=0;
	if(!empty($_POST['product_range_id'])){
		$product_range_id=$Encryption->decode($_POST['product_range_id']);
	}
	$data = send_rest(array(
		"function" => "Admin/CheckIfCustomLengthAllowed",
		"product_range_id" => $product_range_id
	));
	header('Content-type: application/json');
	echo json_encode($data);
}
?>