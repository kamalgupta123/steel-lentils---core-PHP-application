<?php
include_once(dirname(__FILE__)."/../../site_config.php");
$Encryption=new Encryption();
if(isset($_POST['action']) && $_POST['action']=='SaveAddress'){ 
    $customer_id=0;
    if(isset($_SESSION['sl_user']['user_id'])){
        $customer_id=$_SESSION['sl_user']['user_id'];
    }
    $result = send_rest(array(
		"function" => "Customers/SaveAddress",
        "data" => $_POST,
        "customer_id" => $customer_id,
        "customer_address_id" => $Encryption->decode($_POST['customer_address_id'])
	));
	header('Content-type: application/json');
	echo json_encode($result);
}
if(isset($_POST['action']) && $_POST['action']=='DeleteAddresses'){ 
        $address_ids = explode(",",$_POST['address_ids']);
        $addressIds = array();
        foreach ($address_ids as $value) {
            $addressIds[] = (int)$Encryption->decode($value);
        }
        $data = send_rest(array(
            "function" => "Customers/DeleteAddress",
            "address" => $addressIds
        ));
        header('Content-type: application/json');
        echo json_encode($data);
}
?>