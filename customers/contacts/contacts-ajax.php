<?php
include_once(dirname(__FILE__)."/../../site_config.php");
$Encryption=new Encryption();
if(isset($_POST['action']) && $_POST['action']=='SaveContacts'){ 
    $customer_id=0;
    if(isset($_SESSION['sl_user']['user_id'])){
        $customer_id=$_SESSION['sl_user']['user_id'];
    }
    $result = send_rest(array(
		"function" => "Customers/SaveContacts",
        "data" => $_POST,
        "customer_id" => $customer_id,
        "customer_contact_id" => $Encryption->decode($_POST['customer_contact_id'])
	));
	header('Content-type: application/json');
	echo json_encode($result);
}
if(isset($_POST['action']) && $_POST['action']=='DeleteContact'){ 
        $contact_ids = explode(",",$_POST['contact_ids']);
        $contactIds = array();
        foreach ($contact_ids as $value) {
            $contactIds[] = (int)$Encryption->decode($value);
        }
        $data = send_rest(array(
            "function" => "Customers/DeleteContact",
            "contact" => $contactIds
        ));
        header('Content-type: application/json');
        echo json_encode($data);
}
?>