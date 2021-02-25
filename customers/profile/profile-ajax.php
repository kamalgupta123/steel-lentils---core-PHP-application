<?php
include_once(dirname(__FILE__)."/../../site_config.php");
$Encryption=new Encryption();
if(isset($_POST['action']) && $_POST['action']=='SaveProfile'){ 
    $customer_id=0;
    if(isset($_SESSION['sl_user']['user_id'])){
        $customer_id=$_SESSION['sl_user']['user_id'];
    }
    $result = send_rest(array(
		"function" => "Customers/SaveProfile",
        "data" => $_POST,
        "customer_id" => $customer_id
	));
	header('Content-type: application/json');
	echo json_encode($result);
}
?>