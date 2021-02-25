<?php
include_once(dirname(__FILE__)."/../../site_config.php");
include_once(dirname(__FILE__)."/../admin-config.php");
include_once(BASE_DIR . "/common_functions.php");
include_once(ADMIN_DIR . "/admin-functions.php");
$Encryption = new Encryption();
if(isset($_POST['action']) && $_POST['action']=='SaveProductRanges'){ 
    $product_range_name = '';
    $product_range_type = '';
    $created_on = '';
    $modified_on = '';
	$is_enabled = 0;
	$is_custom_length_allowed = 0;
	$product_range_id = 0;

    if(!empty($_POST['product_range_name'])){
		$product_range_name = $_POST['product_range_name'];
	}
    if(!empty($_POST['product_range_type'])){
		$product_range_type = $_POST['product_range_type'];
	}
    if(!empty($_POST['is_enabled'])){
		$is_enabled = $_POST['is_enabled'];
	}
    if(!empty($_POST['is_custom_length_allowed'])){
		$is_custom_length_allowed = $_POST['is_custom_length_allowed'];
	}
	$Encryption=new Encryption();
	if(!empty($_POST['product_range_id'])){
		$product_range_id=$Encryption->decode($_POST['product_range_id']);
	}

    $data = send_rest(array(
		"function" => "Admin/SaveProductRange",
		"product_range_name" => $product_range_name,
		"product_range_type" => $product_range_type,
		"is_enabled" => $is_enabled,
		"is_custom_length_allowed" => $is_custom_length_allowed,
		"product_range_id" => $product_range_id
	));
	header('Content-type: application/json');
	echo json_encode($data);
}

if(isset($_POST['action']) && $_POST['action']=='saveIsEnabled'){ 
	$result = send_rest(array(
		"function" => "Admin/UpdateProductRangeIsEnabled",
		"data" => $_POST,
		"product_range_id" => $Encryption->decode($_POST['product_range_id'])
	));
	header('Content-type: application/json');
	echo json_encode($result);
}

?>