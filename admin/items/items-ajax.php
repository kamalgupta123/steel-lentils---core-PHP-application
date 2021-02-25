<?php
include_once(dirname(__FILE__)."/../../site_config.php");
include_once(dirname(__FILE__)."/../admin-config.php");
include_once(BASE_DIR . "/common_functions.php");
include_once(ADMIN_DIR . "/admin-functions.php");
$Encryption = new Encryption();
if(isset($_POST['action']) && $_POST['action']=='saveIsEnabled'){ 
	$result = send_rest(array(
		"function" => "Admin/UpdateItemsIsEnabled",
		"data" => $_POST,
		"item_id" => $Encryption->decode($_POST['item_id'])
	));
	header('Content-type: application/json');
	echo json_encode($result);
}
if(isset($_POST['action']) && $_POST['action']=='DeleteItems'){ 
	$item_ids = explode(",",$_POST['item_ids']);
	$itemIds = array();
	foreach ($item_ids as $value) {
		$itemIds[] = (int)$Encryption->decode($value);
	}
	$data = send_rest(array(
		"function" => "Admin/DeleteItems",
		"items" => $itemIds
	));
	header('Content-type: application/json');
	echo json_encode($data);
}
if(isset($_POST['action']) && $_POST['action']=='SaveItems'){ 
    
    $section = 0;
    $length = 0;
    $price = 0;
    $weight = 0;
	$quantity = 0;
	$order_threshold_value = 0;
	$created_on = 0;
	$modified_on = 0;
	$is_enabled = 0;
	$item_id = 0;
    $Encryption=new Encryption();
    if(!empty($_POST['sections'])){
		$section = $Encryption->decode($_POST['sections']);
	}
    if(!empty($_POST['length'])){
		$length = $_POST['length'];
	}
    if(!empty($_POST['price'])){
		$price = $_POST['price'];
	}
    if(!empty($_POST['weight'])){
		$weight = $_POST['weight'];
	}
    if(!empty($_POST['quantity'])){
		$quantity = $_POST['quantity'];
	}
    if(!empty($_POST['order_threshold_value'])){
		$order_threshold_value = $_POST['order_threshold_value'];
	}
    if(!empty($_POST['created_on'])){
		$created_on = $_POST['created_on'];
	}
    if(!empty($_POST['modified_on'])){
		$modified_on = $_POST['modified_on'];
	}
    if(!empty($_POST['is_enabled'])){
		$is_enabled = $_POST['is_enabled'];
	}
	if(!empty($_POST['item_id'])){
		$item_id=$Encryption->decode($_POST['item_id']);
	}

    $data = send_rest(array(
		"function" => "Admin/SaveItems",
		"section" => $section,
		"length" => $length,
		"price" => $price,
		"weight" => $weight,
		"quantity" => $quantity,
		"order_threshold_value" => $order_threshold_value,
		"created_on" => $created_on,
		"modified_on" => $modified_on,
		"is_enabled" => $is_enabled,
		"item_id" => $item_id
    ));
    
	header('Content-type: application/json');
	echo json_encode($data);
}
?>