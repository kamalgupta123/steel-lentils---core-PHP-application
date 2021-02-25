<?php
include_once(dirname(__FILE__)."/../../site_config.php");
include_once(dirname(__FILE__)."/../admin-config.php");
include_once(BASE_DIR . "/common_functions.php");
include_once(ADMIN_DIR . "/admin-functions.php");
$Encryption = new Encryption();
if(isset($_POST['action']) && $_POST['action']=='SaveStaff'){ 
	if(!empty($_SESSION['sl_admin']['user_id'])){
        $customer_id = $_SESSION['sl_admin']['user_id'];
    }
	$result = send_rest(array(
		"function" => "Admin/SaveStaff",
		"data" => $_POST,
		"customer_id" => $customer_id,
		"skill_id" => $Encryption->decode($_POST['skill_id']),
		"user_login_id" =>  $Encryption->decode($_POST['user_login_id'])
	));
	header('Content-type: application/json');
	echo json_encode($result);
}
if(isset($_POST['action']) && $_POST['action']=='saveIsEnabled'){ 
	$result = send_rest(array(
		"function" => "Admin/UpdateStaffIsEnabled",
		"data" => $_POST,
		"skill_id" => $Encryption->decode($_POST['skill_id'])
	));
	header('Content-type: application/json');
	echo json_encode($result);
}
?>