<?php
include_once(dirname(__FILE__)."/../../site_config.php");
$Encryption=new Encryption();
if(isset($_POST['action']) && $_POST['action']=='DeleteCustomer'){ 
        $customer_ids = explode(",",$_POST['customer_ids']);
        $customerIds = array();
        foreach ($customer_ids as $value) {
            $customerIds[] = (int)$Encryption->decode($value);
        }
        $data = send_rest(array(
            "function" => "Admin/DeleteCustomers",
            "customer" => $customerIds
        ));
        header('Content-type: application/json');
        echo json_encode($data);
}
if(isset($_POST['action']) && $_POST['action']=='SaveCustomers'){ 

    if(!empty($_SESSION['sl_admin']['user_id'])){
        $customer_id = $_SESSION['sl_admin']['user_id'];
    }

    $data = send_rest(array(
		"function" => "Admin/SaveCustomers",
         "data" => $_POST,
         "customer_id" => $customer_id,
         "customer_address_id" => $Encryption->decode($_POST['customer_address_id']),
         "user_login_id" =>  $Encryption->decode($_POST['user_login_id'])
     ));
    
	header('Content-type: application/json');
	echo json_encode($data);
}
?>