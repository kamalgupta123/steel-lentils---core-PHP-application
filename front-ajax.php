<?php
include_once(dirname(__FILE__)."/site_config.php");

if(isset($_POST['action']) && $_POST['action']=="AddToCart"){ 
	$Encryption=new Encryption();
	$item_id = 0;
	if(!empty($_POST['item_id'])){
		$item_id = $Encryption->decode($_POST['item_id']);
	}
	$unique_cart_key='';
	if(isset($_SESSION['unique_cart_key'])){
		$unique_cart_key=$_SESSION['unique_cart_key'];
	}
	$customer_id=0;
	if(isset($_SESSION['sl_user']['user_id'])){
		$customer_id=$_SESSION['sl_user']['user_id'];
	}
	$irregular=0;
	if(isset($_POST['irregular'])){
		$irregular=$_POST['irregular'];
	}
	$length='';
	if(isset($_POST['length'])){
		$length=$_POST['length'];
	}
	$custom=0;
	if(isset($_POST['is_custom'])){
		$custom=$_POST['is_custom'];
	}
	$result = send_rest(array(
		"function" => "Customers/AddToCart",
		"item_id" => $item_id,
		"unique_cart_key" => $unique_cart_key,
		"customer_id" => $customer_id,
		"irregular" => $irregular,
		"quantity" => $_POST['quantity'],
		"length" => $length,
		"custom" => $custom
	));
	if($result['status']==1){
		if(!empty($result['token'])){
			$_SESSION['unique_cart_key'] = $result['token'];
		}
	}
	/* if(isset($_SESSION['unique_cart_key'])){
		$is_order_there = send_rest(array(
			"function" => "Customers/CheckOrderExists",
			"unique_cart_key" => $_SESSION['unique_cart_key']
		));
		if($is_order_there['data']==1){
			$loginId=0;
			if(isset($_SESSION['sl_user'])){
				$loginId = $_SESSION['sl_user']['user_id'];
			}
			$result = send_rest(array(				
				"function" => "Customers/UpdateOrder",
				"loginId" => $loginId,
				"unique_cart_key" => $_SESSION['unique_cart_key'],
				"item_id" => $Encryption->decode($_POST['item_id']),
				"quantity" => $_POST['quantity'],
				"data"=> $_POST
			));
		}
	}
	else{
		
		if($token_exists==0){
			$_SESSION['unique_cart_key'] = md5(uniqid(rand(), true));
			$loginId=0;
			if(isset($_SESSION['sl_user'])){
				$loginId = $_SESSION['sl_user']['user_id'];
			}			
			$result = send_rest(array(
				"function" => "Customers/StoreOrder",
				"unique_cart_key" => $_SESSION['unique_cart_key'],
				"item_id" => $Encryption->decode($_POST['item_id']),
				"quantity" => $_POST['quantity'],
				"loginId" => $loginId,
				"data"=> $_POST
			));
		}
	} */
	header('Content-type: application/json');
	echo json_encode($result);
}
if(isset($_POST['action']) && $_POST['action']=="SaveSignUpForm"){ 
	$result = send_rest(array(
		"function" => "Customers/SignUp",
		"SignUpData" => $_POST
    ));
	header('Content-type: application/json');
	echo json_encode($result);	
}
if(isset($_POST['admin_login'])){ 
	$Encryption=new Encryption();
	$inputData = array();
  	$inputData['EMAIL']=$_POST['email'];
  	$inputData['PASSWORD']=$_POST['pass'];
	//print_r($inputData);
	if(!empty($inputData['PASSWORD']))
	{
		$inputData['PASSWORD']=md5($_POST['pass']);
    }
	$result = send_rest(array(
		"function" => "Admin/Admin_Login",
		"loginData" => $inputData,
    ));
	if($result['status']==1)
	{
		$_SESSION['sl_admin']['user_type'] = $result['data']['user_type'];
		$_SESSION['sl_admin']['user_id'] = $result['data']['user_id'];
	}
	header('Content-type: application/json');
	echo json_encode($result);	
}
if(isset($_POST['login'])){ 
	$Encryption=new Encryption();
	$inputData = array();
	$result = send_rest(array(
		"function" => "Customers/Login",
		"loginData" => $_POST
    ));
	if($result['status']==1)
	{
		$_SESSION['sl_user']['user_id'] = $result['data']['user_id'];
	}
	header('Content-type: application/json');
	echo json_encode($result);	
}
if(isset($_POST["forgot_pwd"])){
	$result = send_rest(array(
		"function" => "Customers/ForgotPassword",
		"email" => $_POST['email'],
    ));
	header('Content-type: application/json');
	echo json_encode($result);
}
if(isset($_POST["reset_password"])){
	$result = send_rest(array(
		"function" => "Customers/ResetPassword",
		"fields_data" => $_POST,
    ));
	header('Content-type: application/json');
	echo json_encode($result);
}
?>