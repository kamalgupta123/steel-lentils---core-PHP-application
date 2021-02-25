<?php
include_once(__DIR__ . "/../admin-config.php");
include_once(BASE_DIR . "/common_functions.php");
include_once(ADMIN_DIR . "/admin-functions.php");

if(isset($_POST['action']) && $_POST['action']=='postcodes_save'){ 
	$result = send_rest(array(
		"function" => "Admin/PostCodes_Save",
		"PostCodeData" => $_POST['postcodes'],
    ));
	header('Content-type: application/json');
    echo json_encode($result);
}
if(isset($_POST['SaveGlobalSettings'])){ 
	$result = send_rest(array(
		"function" => "Admin/SaveGlobalSettings",
		"fields_data" => $_POST,
    ));
	header('Content-type: application/json');
    echo json_encode($result);
}
?>