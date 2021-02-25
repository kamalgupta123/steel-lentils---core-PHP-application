<?php
include_once(dirname(__FILE__)."/admin-config.php");
include_once(__DIR__ .'/admin-functions.php');

if(isset($_POST['action']) && $_POST['action']=='AdminProfile'){
    $Encryption=new Encryption();
    $data = send_rest(array(
		"function" => "Admin/saveAdminDetails",
        "adminData" => $_POST,
        "id" => $Encryption->decode($_POST['admin_id'])
    )); 
    header('Content-type: application/json');
	echo json_encode($data);
}
?>
