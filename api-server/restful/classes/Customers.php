<?php
include_once(__DIR__ ."/../autoload.php"); 

class Customers
{   
	/* 
	input params - 
		"function" => "SaveProfile" - function to save Profile to database 
		"inputdata" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"msg" => '',
			"errors" => array(),
			"data" => array()
		);
	*/
	function SaveProfile($inputdata){
        global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
        );
		if(!empty($inputdata)){
			$first_name = safe_str($inputdata['data']['first_name']); 
			$last_name = safe_str($inputdata['data']['last_name']); 
			$phone = safe_str($inputdata['data']['phone']); 
			$email = safe_str($inputdata['data']['email']); 
			$password = safe_str($inputdata['data']['password']); 
			$business_name = safe_str($inputdata['data']['business_name']); 
			$business_address = safe_str($inputdata['data']['business_address']); 
			$abn = safe_str($inputdata['data']['abn']); 
			$acn = safe_str($inputdata['data']['acn']); 
			$credit_account=0;
			$notify=0;
            if(isset($inputdata['data']['notify'])){
                $notify = safe_str($inputdata['data']['notify']);
            }
			$sms=0;
            if(isset($inputdata['data']['sms'])){
                $sms = safe_str($inputdata['data']['sms']);
            }
			$email_sms=0;
            if(isset($inputdata['data']['email_sms'])){
                $email_sms = safe_str($inputdata['data']['email_sms']);
			}
			$get_notified_via = 0;
			if($notify==1){
				$get_notified_via=1;
			}
			if($sms==1){
				$get_notified_via=2;
			}
			if($email_sms==1){
				$get_notified_via=3;
			}
            if(isset($inputdata['data']['credit_account'])){
                $credit_account = safe_str($inputdata['data']['credit_account']);
            }
			$order=0;
            if(isset($inputdata['data']['order'])){
                $order = safe_str($inputdata['data']['order']);
            }
			$cutting=0;
            if(isset($inputdata['data']['cutting'])){
                $cutting = safe_str($inputdata['data']['cutting']);
            }
			$packing=0;
            if(isset($inputdata['data']['packing'])){
                $packing = safe_str($inputdata['data']['packing']);
            }
			$loading=0;
            if(isset($inputdata['data']['loading'])){
                $loading = safe_str($inputdata['data']['loading']);
            }
			$delivered=0;
            if(isset($inputdata['data']['delivered'])){
                $delivered = safe_str($inputdata['data']['delivered']);
            }

			$validations=array(
				"first_name"=>[
					"validate"=>"required",
					"label"=>"First Name"
				],
				"last_name"=>[
					"validate"=>"required",
					"label"=>"Last Name"
				],
				"phone"=>[
					"validate"=>"required",
					"label"=>"Phone"
				],
				"email"=>[
					"validate"=>"required",
					"label"=>"Email"
				]
            );  
			$PSValidationEngine = new PSValidationEngine($inputdata['data'], $validations,true);
			$response['errors']=$PSValidationEngine->validate();
			if(empty($response['errors'])){

				$sql = "UPDATE `sl_user_login_info` SET `first_name`='".safe_str($first_name)."',`last_name`='".safe_str($last_name)."',`phone`='".safe_str($phone)."',`email`='".safe_str($email)."',`password`='".md5(safe_str($password))."',`created_on`='".date('Y-m-d H:i:s')."'  WHERE `user_type`=3";

				$sql2 = "UPDATE `sl_customers_info` SET `business_name`='".safe_str($business_name)."',`abn_number`=".safe_str($abn).",`acn_number`=".safe_str($abn).",`get_notified_via`=".safe_str($get_notified_via).",`modified_on`='".date('Y-m-d H:i:s')."' WHERE `customer_id`=".safe_str($inputdata['customer_id']);
				
				$sequel = "TRUNCATE sl_customer_notifications_mapping_info";
				$rslt =  $con->query($sequel); 

				if($order==1){
					$sql3 = "INSERT INTO `sl_customer_notifications_mapping_info`(`customer_id`, `notification_type`) VALUES (".safe_str($inputdata['customer_id']).",1)";
				}
				if($cutting==1){
					$sql4 = "INSERT INTO `sl_customer_notifications_mapping_info`(`customer_id`, `notification_type`) VALUES (".safe_str($inputdata['customer_id']).",2)";
				}
				if($packing==1){
					$sql5 = "INSERT INTO `sl_customer_notifications_mapping_info`(`customer_id`, `notification_type`) VALUES (".safe_str($inputdata['customer_id']).",3)";
				}
				if($loading==1){
					$sql6 = "INSERT INTO `sl_customer_notifications_mapping_info`(`customer_id`, `notification_type`) VALUES (".safe_str($inputdata['customer_id']).",4)";
				}
				if($delivered==1){
					$sql7 = "INSERT INTO `sl_customer_notifications_mapping_info`(`customer_id`, `notification_type`) VALUES (".safe_str($inputdata['customer_id']).",5)";
				}
    
                $result = $con->query($sql);  			
                $res = $con->query($sql2);  			
                $res1 = $con->query($sql3);  			
                $res2 = $con->query($sql4);  			
                $res3 = $con->query($sql5);  			
                $res4 = $con->query($sql6);  			
				$res5 = $con->query($sql7);  
	
				if($result && $rslt && $res && $res1 && $res2 && $res3 && $res4 && $res5){
					$response['status']=1;
					$response['msg']="Saved Successfully";
				}
				else{
					$response['status']=0;
					$response['errors'][]=$con->error;
				}
			}
		}
		return $response;
	}

	/* 
	input params - 
		"function" => "/DeleteContact" - function to delete Contacts
		"contact"=>$contactId
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function DeleteContact($contactId){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
		$contact =implode(",",$contactId['contact']);
		$sql="UPDATE `sl_customer_contacts_info` SET `delete_flag`=1 WHERE 	customer_contact_id IN (".safe_str($contact).")";
		$result=$con->query($sql);
		if($result){
			$response['status']=1;
		}else{
			$response['errors'] = $con->error;
		}
		return $response;
	}

	  /* 
	input params - 
		"function" => "/GetContactDetails" - function to get the contact details
		"customer_contact_id" => $customer_contact_id
	output params - 
		$data = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
	*/
	function GetContactDetails($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
        $sql='select * from sl_customer_contacts_info where customer_contact_id='.safe_str($inputdata['customer_contact_id']).' and delete_flag=0';
		$result=$con->query($sql);
		if($result->num_rows){
			$response['data'] = $result->fetch_assoc();
			$response['status']=1;
		}else{
			$response['errors'] = $con->error;
		}
		return $response;
	}
	
	/* 
	input params - 
		"function" => "ListContacts" - function to get the list of Contacts
		"page_no" => $request["PageNumber"],
		"row_size" => $request["RowSize"],
		"sort_on" => $request["SortOn"],
		"sort_type" => $request["SortType"]
	output params - 
		$response = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function ListContacts($inputdata){
		global $con;

		$response = array(
			"status" => false,
			"data" => array(),
			"msg"=>'',
			"errors" => array()
		);
		$page_no = $inputdata['page_no'];
		$row_size = $inputdata['row_size'];
		$sort_on = $inputdata['sort_on'];
		$sort_type = $inputdata['sort_type'];
		$where = "";
		
		if(!empty($inputdata['search'])){
			$where = " and (i.first_name like '%".safe_str($inputdata['search'])."%' or i.last_name like '%".safe_str($inputdata['search'])."%' or i.phone like '%".safe_str($inputdata['search'])."%' or i.email like '%".safe_str($inputdata['search'])."%')";
		}
		
		$pcount_qry = "select count(*) as total_count from `sl_customer_contacts_info` i where i.delete_flag=0 and i.customer_id=".safe_str($inputdata['customer_id']).$where;
		
		$pcount_result = $con->query($pcount_qry);
		$pcount_row = $pcount_result->fetch_assoc();
		$total_records = $pcount_row["total_count"];
		$total_pages = ceil($total_records / $row_size);
		if ($total_pages == 0) {
			$total_pages = 1;
		}
		if ($page_no > $total_pages) {
			$page_no = $total_pages;
		}
		$get_det_query="select * from sl_customer_contacts_info i where i.delete_flag=0 and i.customer_id=".safe_str($inputdata['customer_id']).$where." order by $sort_on $sort_type LIMIT $row_size OFFSET ".($page_no-1)*$row_size;

		$pagging_list = array();
		$pagg_result = $con->query($get_det_query);
		if($pagg_result) {
			$pagg_count = $pagg_result->num_rows;
			if ($pagg_count > 0) {
				$i = 0;
				while ($row = $pagg_result->fetch_assoc()) {
					$pagging_list[$i] = $row;
					$i++;					
				}
				if(!empty($pagging_list)) {
					$response["status"] = true;
				}
			}
			else {
				$response["status"] = false;
				$response["msg"] = "No data found";
			}
		}
		else {
			$response['errors'][] = $con->error;
		}
		$response['data']["total_records"] = $total_records;
		$response['data']["total_pages"] = $total_pages;
		$response['data']["pagging_list"] = $pagging_list;
		return $response;
	}

	/* 
	input params - 
		"function" => "SaveContacts" - function to save Contacts to database 
		"inputdata" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"msg" => '',
			"errors" => array(),
			"data" => array()
		);
	*/
	function SaveContacts($inputdata){
        global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
        );
		if(!empty($inputdata)){
			$is_enabled=0;
			$first_name = safe_str($inputdata['data']['first_name']);
			$last_name = safe_str($inputdata['data']['last_name']);
			$phone = safe_str($inputdata['data']['phone']);
			$alternate_phone = safe_str($inputdata['data']['alternate_phone']);
			$email = safe_str($inputdata['data']['email']);
			$is_enabled=0;
            if(isset($inputdata['data']['is_enabled'])){
                $is_enabled = safe_str($inputdata['data']['is_enabled']);
            }
            $customer_id = safe_str($inputdata['customer_id']);
			$validations=array(
				"first_name"=>[
					"validate"=>"required",
					"label"=>"First Name"
				],
				"last_name"=>[
					"validate"=>"required",
					"label"=>"Last Name"
				],
				"phone"=>[
					"validate"=>"required",
					"label"=>"Phone"
				],
				"email"=>[
					"validate"=>"required",
					"label"=>"Email"
				]
            );  
			$PSValidationEngine = new PSValidationEngine($inputdata['data'], $validations,true);
			$response['errors']=$PSValidationEngine->validate();
			if(empty($response['errors'])){
                if(empty($inputdata['customer_contact_id'])){
                    $sql = "INSERT INTO `sl_customer_contacts_info`(`customer_id`,`first_name`, `last_name`, `phone`, `alternate_phone`, `email`, `is_enabled`, `created_on`) VALUES (".$customer_id.",'".$first_name."','".$last_name."','".$phone."','".$alternate_phone."','".$email."',".$is_enabled.",'".date('Y-m-d H:i:s')."')";
                }
                else{
					$sql = "UPDATE sl_customer_contacts_info SET first_name='".$first_name."',last_name='".$last_name."',phone='".$phone."',	alternate_phone='".$alternate_phone."',email='".$email."',is_enabled=".$is_enabled.",modified_on='".date('Y-m-d H:i:s')."' WHERE customer_contact_id=".safe_str($inputdata['customer_contact_id']);
                }
                $result = $con->query($sql);  			
				if($result){
					$response['status']=1;
					$response['msg']="Saved Successfully";
				}
				else{
					$response['status']=0;
					$response['errors'][]=$con->error;
				}
			}
		}
		return $response;
	}
	
	/* 
	input params - 
		"function" => "/DeleteAddress" - function to delete Address
		"address"=>$addressId
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function DeleteAddress($addressId){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
		$address =implode(",",$addressId['address']);
		$sql="UPDATE `sl_customer_addresses_info` SET `delete_flag`=1 WHERE customer_address_id IN (".safe_str($address).")";
		$result=$con->query($sql);
		if($result){
			$response['status']=1;
		}else{
			$response['errors'] = $con->error;
		}
		return $response;
	}

    /* 
	input params - 
		"function" => "ListAddresses" - function to get the list of Addresses
		"page_no" => $request["PageNumber"],
		"row_size" => $request["RowSize"],
		"sort_on" => $request["SortOn"],
		"sort_type" => $request["SortType"]
	output params - 
		$response = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function ListAddresses($inputdata){
		global $con;

		$response = array(
			"status" => false,
			"data" => array(),
			"msg"=>'',
			"errors" => array()
		);
		$page_no = $inputdata['page_no'];
		$row_size = $inputdata['row_size'];
		$sort_on = $inputdata['sort_on'];
		$sort_type = $inputdata['sort_type'];
		
		$pcount_qry = "select count(*) as total_count from `sl_customer_addresses_info` i where i.delete_flag=0 and i.customer_id";
		
		$pcount_result = $con->query($pcount_qry);
		$pcount_row = $pcount_result->fetch_assoc();
		$total_records = $pcount_row["total_count"];
		$total_pages = ceil($total_records / $row_size);
		if ($total_pages == 0) {
			$total_pages = 1;
		}
		if ($page_no > $total_pages) {
			$page_no = $total_pages;
		}
		$get_det_query="select * from sl_customer_addresses_info i where i.delete_flag=0 order by $sort_on $sort_type LIMIT $row_size OFFSET ".($page_no-1)*$row_size;
		//echo $get_det_query;
		$pagging_list = array();
		$pagg_result = $con->query($get_det_query);
		if($pagg_result) {
			$pagg_count = $pagg_result->num_rows;
			if ($pagg_count > 0) {
				$i = 0;
				while ($row = $pagg_result->fetch_assoc()) {
					$pagging_list[$i] = $row;
					$i++;					
				}
				if(!empty($pagging_list)) {
					$response["status"] = true;
				}
			}
			else {
				$response["status"] = false;
				$response["msg"] = "No data found";
			}
		}
		else {
			$response['errors'][] = $con->error;
		}
		$response['data']["total_records"] = $total_records;
		$response['data']["total_pages"] = $total_pages;
		$response['data']["pagging_list"] = $pagging_list;
		return $response;
	}
    
    /* 
	input params - 
		"function" => "/GetAddressDetails" - function to get the address details
		"customer_address_id" => $customer_address_id
	output params - 
		$data = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
	*/
	function GetAddressDetails($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
        $sql='select * from sl_customer_addresses_info where customer_address_id='.safe_str($inputdata['customer_address_id']).' and delete_flag=0';
		$result=$con->query($sql);
		if($result->num_rows){
			$response['data'] = $result->fetch_assoc();
			$response['status']=1;
		}else{
			$response['errors'] = $con->error;
		}
		return $response;
    }
    
    /* 
	input params - 
		"function" => "SaveAddress" - function to save Addresses to database 
		"inputdata" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"msg" => '',
			"errors" => array(),
			"data" => array()
		);
	*/
	function SaveAddress($inputdata){
        global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
        );
		
		if(!empty($inputdata)){
			$is_enabled=0;
			$is_buisness_address = 0;
			$street_address_one = safe_str($inputdata['data']['street_address_one']);
			$street_address_two = safe_str($inputdata['data']['street_address_two']);
			$city = safe_str($inputdata['data']['city']);
			$state = safe_str($inputdata['data']['state']);
            $zip = safe_str($inputdata['data']['zip']);
            if(isset($inputdata['data']['is_enabled'])){
                $is_enabled = safe_str($inputdata['data']['is_enabled']);
            }
            if(isset($inputdata['data']['is_buisness_address'])){
                $is_buisness_address = safe_str($inputdata['data']['is_buisness_address']);
            }
            $customer_id = safe_str($inputdata['customer_id']);
			$validations=array(
				"street_address_one"=>[
					"validate"=>"required",
					"label"=>"Saved Address"
				],
				"street_address_two"=>[
					"validate"=>"required",
					"label"=>"Business Address"
				],
				"city"=>[
					"validate"=>"required",
					"label"=>"City"
				],
				"zip"=>[
					"validate"=>"required",
					"label"=>"Zip"
				]
            );
            
			$PSValidationEngine = new PSValidationEngine($inputdata['data'], $validations,true);
			$response['errors']=$PSValidationEngine->validate();
			if($street_address_one==$street_address_two){
				$response['errors'][]="Two addresses cannot be same";
			}
			if(empty($response['errors'])){
                if(empty($inputdata['customer_address_id'])){
                    $sql = "INSERT INTO `sl_customer_addresses_info`(`customer_id`,`street_address1`, `street_address2`, `city`, `state`, `zip`, `is_enabled`, `created_on`,`is_buisness_address`) VALUES (".$customer_id.",'".$street_address_one."','".$street_address_two."','".$city."','".$state."',".$zip.",".$is_enabled.",'".date('Y-m-d H:i:s')."',".$is_buisness_address.")";
                    $result = $con->query($sql);
                }
                else{
    				$sql = "UPDATE sl_customer_addresses_info SET street_address1='".$street_address_one."',street_address2='".$street_address_two."',city='".$city."',state='".$state."',zip=".$zip.",is_enabled=".$is_enabled.",modified_on='".date('Y-m-d H:i:s')."',is_buisness_address=".$is_buisness_address." WHERE customer_address_id=".safe_str($inputdata['customer_address_id']);
                }
                $result = $con->query($sql);
    			
				if($result){
					$response['status']=1;
					$response['msg']="Saved Successfully";
				}
				else{
					$response['status']=0;
					$response['errors'][]=$con->error;
				}
			}
		}
		return $response;
	}

    /* 
    input params - 
        "function"=>"Customers/DeliveryTruckItemsCount"  // function to get count of items in Delivery Truck 
		"customer_id"=>$cust_id,
		"unique_cart_key"=>$unique_cart_key
    output params - 
        $data = array(
            "status" => 0,
            "errors" => array(),
            "msg" => '',
            "data" => array()
        );
    */
    function DeliveryTruckItemsCount($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "errors" => '',
            "msg" => '',
            "data" => ''
        );
		$count=0;
		$check_for_session_key=0;
		if(!empty($inputdata['customer_id'])){
			$sql="select count(*) as count from sl_order_items_info oi inner join sl_orders_info s on s.order_id=oi.order_id and s.delete_flag=0 where oi.delete_flag=0 and s.customer_id='".safe_str($inputdata['customer_id'])."' and s.order_status=7";
			$res=$con->query($sql);
			if($res->num_rows){
				$row=$res->fetch_assoc();
				$count=$row['count'];
				if($count==0){
					$check_for_session_key=1;
				}
			}else{
				$check_for_session_key=1;
			}
		}else{
			$check_for_session_key=1;
		}
			
		if(!empty($inputdata['unique_cart_key']) && $check_for_session_key==1){
			$sql="select count(*) as count from sl_order_items_info oi inner join sl_orders_info s on s.order_id=oi.order_id and s.delete_flag=0 where oi.delete_flag=0 and s.unique_cart_key='".safe_str($inputdata['unique_cart_key'])."'";
			$res=$con->query($sql);
			if($res->num_rows){
				$row=$res->fetch_assoc();
				$count=$row['count'];
			}
		}
		$response['data'] = $count;
		$response['status'] = 1;
		
		return $response;
	}
	/* 
    input params - 
        "function" => "Customers/AddToCart", function to create order and add items to the order 
		"item_id" => $item_id,
		"unique_cart_key" => $unique_cart_key,
		"customer_id" => $customer_id,
		"irregular" => $irregular,
		"quantity" => $_POST['quantity'],
		"length" => $length
    output params - 
        $data = array(
            "status" => 0,
            "errors" => array(),
            "msg" => '',
            "data" => array()
        );
    */
    function AddToCart($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "errors" => '',
            "msg" => '',
            "data" => ''
        );
		// validations 
		$validations=array(
			"quantity"=>[
				"validate"=>"required,custom[integer],min[1]",
				"label"=>"Quantity"
			],
			"item_id"=>[
				"validate"=>"required",
				"label"=>"Item"
			]
		);
		if(!empty($inputdata['length'])){
			$validations2=array(
				"length"=>[
					"validate"=>"custom[integer],min[1]",
					"label"=>"Length"
				]
			);
			$validations=$validations+$validations2;
		}
		$PSValidationEngine = new PSValidationEngine($inputdata, $validations,true);
		$response['errors']=$PSValidationEngine->validate();
		if(!empty($inputdata['custom'])){
			// check if it did not cross the maximum length 
			$sql="select max(length) from sl_items_info where section_id='".$inputdata['item_id']."' and is_enabled=1 and delete_flag=0";
			$res=$con->query($sql);
			if($res->num_rows){
				$row=$res->fetch_assoc();
				$max_length=$row['max(length)'];
				// check if input length is not greater than max length 
				if($inputdata['length']>$max_length){
					$response['errors'][]="Max custom length that can be ordered for this section is ".$max_length;
				}
			}
		}
		if(empty($response['errors'])){
			$order_id = 0;
			$create_order=0;
			$order_customer_id=0;
			if(!empty($inputdata['unique_cart_key'])){
				$token=$inputdata['unique_cart_key'];
				// echo 1;
				// means session is already set 
				// check if order already exists 
				$sql="select * from sl_orders_info where unique_cart_key='".safe_str($inputdata['unique_cart_key'])."' and delete_flag=0";
				$res=$con->query($sql);
				if($res->num_rows){
					// means order already exists for this user 
					$row=$res->fetch_assoc();
					$order_id = $row['order_id'];
					$order_customer_id=$row['customer_id'];
				}
			}else{
				// echo 2;
				// create a unique key 
				$token_exists=1;
				while($token_exists==1){
					$token = md5(uniqid(rand(), true));
					// check if this token exists in db already
					$sql="select * from sl_orders_info where unique_cart_key='".safe_str($token)."' and delete_flag=0";
					$result=$con->query($sql);
					if($result->num_rows){
						// key found 
					}else{
						$token_exists = 0;
					}
				}
				$response['token'] = $token;
			}
			// get max order_number if create order 
			$sql="select max(order_number) as max_order_number from sl_orders_info";
			$result=$con->query($sql);
			if($result->num_rows){
				$row=$result->fetch_assoc();
				$order_no = $row['max_order_number'];
				if($order_no==0){
					$new_order_number=1000;
				}else{
					$new_order_number = $order_no+1;
				}
			}else{
				$new_order_number=1000;
			}
			
			$order_status=7; // not placed yet 
			if($inputdata['customer_id']==0){
				// means user is not logged in 
				if(empty($order_id)){
					// create order 
					$create_order=1;
					$customer_id=0;
				}else{
					// order exists, so nothing to do 
				}
			}else{
				// user is logged in 
				// if(empty($order_id)){
				// check if any order exists for this customer 
				$sql="select * from sl_orders_info where customer_id='".safe_str($inputdata['customer_id'])."' and order_status=7 and delete_flag=0";
				$res=$con->query($sql);
				if($res->num_rows){
					// order already exists 
					$row=$res->fetch_assoc();
					$order_id=$row['order_id'];
				}else{
					if(empty($order_id)){
						$create_order=1;
						$customer_id=$inputdata['customer_id'];
					}else{
						// check if order customer id is not 0. if it is 0, update it to session customer id 
						if(empty($order_customer_id)){
							$update="update sl_orders_info set customer_id='".safe_str($inputdata['customer_id'])."' where order_id='".safe_str($order_id)."'";
							$res=$con->query($update);
							if(!$res){
								$response['errors'][] = $con->error;
							}
						}
					}
				}
				// }else{
					
				// }
			}
			if($create_order==1){
				$insert="INSERT INTO `sl_orders_info`(`customer_id`, `unique_cart_key`, `order_number`,`order_status`, `created_on`) VALUES ('".safe_str($customer_id)."','".safe_str($token)."','".safe_str($new_order_number)."','".safe_str($order_status)."','".date('Y-m-d H:i:s')."')";
				$result1=$con->query($insert);
				if($result1){
					$order_id = $con->insert_id;
				}else{
					$response['errors'][] = $con->error;
				}
			}
			if(empty($response['errors'])){
				if(!empty($inputdata['irregular'])){
					// means irregular section added 
					$sql1="INSERT INTO `sl_order_items_info`(`order_id`, `item_id`, `section_id`, `length`, `quantity`, `created_on`) VALUES (".safe_str($order_id).",0,".safe_str($inputdata['item_id']).",".safe_str($inputdata['length']).",".safe_str($inputdata['quantity']).",'".date('Y-m-d H:i:s')."')";
				}
				else{
					// means stocked item
					if(empty($inputdata['custom'])){
    					// check if same item already exists 
    					$sql="select * from sl_order_items_info where item_id='".safe_str($inputdata['item_id'])."' and order_id='".safe_str($order_id)."' and delete_flag=0";
    					$res=$con->query($sql);
    					if($res->num_rows){
    						// update quantity in this item 
    						$sql1="update sl_order_items_info set quantity = quantity+".$inputdata['quantity'].", modified_on='".date('Y-m-d H:i:s')."' where item_id='".safe_str($inputdata['item_id'])."' and order_id='".safe_str($order_id)."' and delete_flag=0";
    					}else{
    						$sql1="INSERT INTO `sl_order_items_info`(`order_id`, `item_id`, `quantity`, `created_on`) VALUES (".safe_str($order_id).",".safe_str($inputdata['item_id']).",".safe_str($inputdata['quantity']).",'".date('Y-m-d H:i:s')."')";
    					}
					}else{
						$sql1="INSERT INTO `sl_order_items_info`(`order_id`, `item_id`, `section_id`, `length`, `quantity`, `created_on`) VALUES (".safe_str($order_id).",0,".safe_str($inputdata['item_id']).",".safe_str($inputdata['length']).",".safe_str($inputdata['quantity']).",'".date('Y-m-d H:i:s')."')";
					}
				}
				$result1=$con->query($sql1);
				if($result1){
					// ok
				}else{
					$response['errors'] = $con->error;
				}   
			}
		}
		if(empty($response['errors'])){
			$response['status']=1;
			$response['msg']="Added to cart successfully";
		}
		return $response;
	}
    /* 
    input params - 
        "function" => "/UpdateOrder" - function to update orders 
        Updatedata => $inputdata
    output params - 
        $data = array(
            "status" => 0,
            "errors" => array(),
            "msg" => '',
            "data" => array()
        );
    */
    function UpdateOrder($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "errors" => '',
            "msg" => '',
            "data" => ''
        );
        $query = "SELECT order_id from sl_orders_info WHERE unique_cart_key='".safe_str($inputdata['unique_cart_key'])."'";
        $res=$con->query($query);
        $order_id=0;
        if($res->num_rows){
            while ($row = $res->fetch_assoc()) {
                $order_id = $row['order_id'];				
            }
        }
        if(!empty($order_id)){
            $sql="UPDATE `sl_orders_info` SET `customer_id`=".safe_str($inputdata['loginId'])." WHERE order_id=".safe_str($order_id);

            $result=$con->query($sql);

            if(!empty($inputdata['data']['irregular'])){
                $sql1="INSERT INTO `sl_order_items_info`(`order_id`, `item_id`, `section_id`, `length`, `price`, `price_per_cut`, `total_cuts`, `weight`, `quantity`, `is_special_order`, `created_on`) VALUES (".safe_str($order_id).",0,".safe_str($inputdata['item_id']).",".safe_str($inputdata['data']['length']).",0,0,0,0,".safe_str($inputdata['quantity']).",0,'".date('Y-m-d H:i:s')."')";
            }
            else{
                $sql1="INSERT INTO `sl_order_items_info`(`order_id`, `item_id`, `section_id`, `length`, `price`, `price_per_cut`, `total_cuts`, `weight`, `quantity`, `is_special_order`, `created_on`) VALUES (".safe_str($order_id).",".safe_str($inputdata['item_id']).",0,0,0,0,0,0,".safe_str($inputdata['quantity']).",0,'".date('Y-m-d H:i:s')."')";
            }
            $result1=$con->query($sql1);
            if($result1 && $result){
                $response['status']=1;
            }else{
                $response['status']=0;
                $response['errors'] = $con->error;
            }   
        }
        return $response;
    }

    /* 
    input params - 
        "function" => "/tokenExists" - function to check if the token with unique value already exists
        "token" => "inputdata"
    output params - 
        $data = array(
            "status" => 0,
            "errors" => array(),
            "msg" => '',
            "data" => array()
        );
    */
    function tokenExists($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "errors" => '',
            "msg" => '',
            "data" => ''
        );
        $sql="select * from sl_orders_info where unique_cart_key='".safe_str($inputdata['token'])."' and delete_flag=0";
        $result=$con->query($sql);
        if($result->num_rows){
            $response['status']=1;
        }
        return $response;
    }
    /* 
    input params - 
        "function" => "/CheckOrderExists" - function to check if the order with unique key already exists
        "unique_cart_key" => $inputdata
    output params - 
        $data = array(
            "status" => 0,
            "errors" => array(),
            "msg" => '',
            "data" => array()
        );
    */
    function CheckOrderExists($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "errors" => '',
            "msg" => '',
            "data" => ''
        );
        $sql="SELECT * FROM `sl_orders_info` WHERE unique_cart_key='".safe_str($inputdata['unique_cart_key'])."'";
        $result=$con->query($sql);
        if(!empty($result->num_rows)){
            $response['data']=1;
        }
        if($result){
            $response['status']=1;
        }else{
            $response['status']=0;
            $response['errors'] = $con->error;
        }
        return $response;
    }

    /* 
    input params - 
        "function" => "/StoreOrderItems" - function to store order and order items
        StoreOrder => $inputdata
    output params - 
        $data = array(
            "status" => 0,
            "errors" => array(),
            "msg" => '',
            "data" => array()
        );
    */
    function StoreOrder($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "errors" => array(),
            "msg" => '',
            "data" => ''
        );
        if(!empty($inputdata)){
            $validations=array(
                "quantity"=>[
                    "validate"=>"required,min[1]",
                    "label"=>"Quantity"
                ]
            );
            $PSValidationEngine = new PSValidationEngine($inputdata, $validations,true);
            $response['errors']=$PSValidationEngine->validate();
            if(empty($response['errors'])){
                $sql1="INSERT INTO `sl_orders_info`(`customer_id`, `unique_cart_key`, `order_number`, `first_name`, `last_name`, `phone`, `alternate_phone`, `email`, `street_address2`, `city`, `state`, `zip`, `sub_total`, `shipping_cost`, `gst`, `total_weight`, `delivery_date`, `delivery_asap`, `deliver_offcuts`, `order_status`, `created_on`) VALUES (".safe_str($inputdata['loginId']).",'".safe_str($inputdata['unique_cart_key'])."',0,'','','',NULL,'','','','','',0,0,0,0,NULL,0,0,0,'".date('Y-m-d H:i:s')."')";
                $result1=$con->query($sql1);
                $id = $con->insert_id;
                if($result1){
                    $response['status']=1;
                }else{
                    $response['status']=0;
                    $response['errors'][] = $con->error;
                }
                if(!empty($inputdata['data']['irregular'])){
                    $sql="INSERT INTO `sl_order_items_info`(`order_id`, `item_id`, `section_id`, `length`, `price`, `price_per_cut`, `total_cuts`, `weight`, `quantity`, `is_special_order`, `created_on`) VALUES (".safe_str($id).",0,".safe_str($inputdata['item_id']).",".safe_str($inputdata['data']['length']).",0,0,0,0,".safe_str($inputdata['quantity']).",0,'".date('Y-m-d H:i:s')."')";
                }
                else{
                    $sql="INSERT INTO `sl_order_items_info`(`order_id`, `item_id`, `section_id`, `length`, `price`, `price_per_cut`, `total_cuts`, `weight`, `quantity`, `is_special_order`, `created_on`) VALUES (".safe_str($id).",".safe_str($inputdata['item_id']).",0,0,0,0,0,0,".safe_str($inputdata['quantity']).",0,'".date('Y-m-d H:i:s')."')";
                }
                $result=$con->query($sql);
                if($result){
                    $response['status']=1;
                }else{
                    $response['status']=0;
                    $response['errors'][] = $con->error;
                }
            }
        }
        return $response;
    }
    /* 
	input params -
		"function" => "Customers/ResetPassword",
		"fields_data" => $_POST,
	Function is used to reset password for a customer
	output - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"data" => array(),
			"msg" => ""
		);
	*/
	function ResetPassword($inputdata){
		global $con;
		$data = array(
			"status" => 0,
			"errors" => array(),
			"data" => array(),
			"msg" => ""
		);
		if(!empty($inputdata['fields_data']['token'])){
			$sql="select * from sl_user_login_info where pwd_verification_token='".safe_str($inputdata['fields_data']['token'])."' and user_type=3 and delete_flag=0";
			$res=$con->query($sql);
			if(!empty($res->num_rows)){
				$row=$res->fetch_assoc();
				//print_r($row);
				$datefromdb = $row['pwd_verification_token_created_on'];
				$today_date = date("Y-m-d H:i:s");
				$datetime1 = strtotime($datefromdb);//database date and time
				$datetime2 = strtotime($today_date);//current date and time
				$interval  = abs($datetime2 - $datetime1);
				$minutes_counter  = round($interval / 60);
				
				if ($minutes_counter<120) {
					if(!empty($inputdata['fields_data']['password'])){
					    //echo $inputdata['fields_data']['confirm_password'];
						if($inputdata['fields_data']['password']==$inputdata['fields_data']['confirm_password']){
							$password = md5(safe_str($inputdata['fields_data']['password']));
							$up_qry = "UPDATE `sl_user_login_info` SET `password`='".safe_str(md5($inputdata['fields_data']['password']))."', `pwd_verification_token`=NULL, `pwd_verification_token_created_on`=NULL, `pwd_verification_check`=1, `modified_on`='".date("Y-m-d H:i:s")."' WHERE user_login_id = '".safe_str($row['user_login_id'])."' AND `delete_flag`=0";
							$up_result = $con->query($up_qry);
							if($up_result){
								// ok 
							}else{
								$data['errors'][] = $con->error;
							}
						}else{
							$data['errors'][] = "Error. Passwords donot match.";
						}
					}else{
						$data['errors'][] = "Error. Please enter a password.";
					}
				}else{
					$data['errors'][] = "Error. Token has expired.";
				}
			}else{
				$data['errors'][] = "Error. Invalid Token for the customer.";
			}
		}else{
			$data['errors'][] = "Error. Invalid Token.";
		}
		if(empty($data['errors'])){
			$data['status'] = 1;
		}
		return $data;
	}
	/* 
	input params - 
		"function" => "Customers/get_customer_details_by_token",
		"reset_password_verification_token" => $token
	function is used to get details of customers based on reset_password_verification_token
	output -  
		$data = array(
			"status" => 0,
			"data" => array(),
			"errors" => array(),
			"msg" => ""
		);
	*/
	function get_customer_details_by_token($inputdata){
		global $con;
		$data = array(
			"status" => 0,
			"data" => array(),
			"errors" => array(),
			"msg" => ""
		);
		$sql = "SELECT * FROM `sl_user_login_info` WHERE `pwd_verification_token`='".safe_str($inputdata['reset_password_verification_token'])."'  and user_type=3 and `delete_flag`=0";
		$res = $con->query($sql);
		if($res){
			$data['data']=$res->fetch_assoc();
		}else{
			$data['errors'][] = $con->error;
		}
		if(empty($data['errors'])){
			$data['status'] = 1;
		}
		return $data;
	}	
	/* 
	input params - 
		"function" => "ForgotPassword" - function to update customer details after email verification 
		"email" => $_POST,
	output params - 
		$response = array(
            "status" => 0,
            "msg" => '',
			"errors" => array()
		);
	*/
    function ForgotPassword($inputdata){
        //print_r($inputdata);
        global $con;
        $response = array(
            "status" => 0,
            "msg"=>'',
            "errors" => array(),
			"data" => array()
        );
		$email   = $inputdata['email'];
		// check if email exists 
		$sql="select * from sl_user_login_info where email='".safe_str($email)."' and user_type=3 and delete_flag=0";
		$result=$con->query($sql);
		if(empty($result->num_rows)){
			$response['errors'][] = "This email address does not exists.";
		}else{
			// get customer details 
			$row=$result->fetch_assoc();
			$token_expiry_date = date("Y-m-d H:i:s", strtotime('+1 hour'));  // reset pwd link valid for 2 hours 
			$name = $row['first_name'];
			$token_exists=1;
			while($token_exists==1){
				$token = md5(openssl_random_pseudo_bytes(32));
				// check if this token exists in db already 
				$sql1="select * from sl_user_login_info where pwd_verification_token='".$token."' and user_type=3 and delete_flag=0";
				$result1=$con->query($sql1);
				if(empty($result1->num_rows)){
					$token_exists=0;
				}
			}
			
			// update details in sl_user_login_info
			$update="update sl_user_login_info set pwd_verification_token='".$token."',pwd_verification_token_created_on='".date('Y-m-d H:i:s')."',modified_on='".date('Y-m-d H:i:s')."' where user_login_id='".$row['user_login_id']."'";
			$res=$con->query($update);
			$tokenEnc = $token;
			if($res){
				$to = $email;
				$verify_link = SITE_URL."/resetting_password.php?token=" . $tokenEnc;
				$body="Hello ".$name.",<br><br>We received a request to recover your password. Click on the link below to recover and reset your password, and you are good to go. <small>(Link is valid for next 2 hours)</small><br><a href='".$verify_link."'>".$verify_link."</a><br><br>If you ignore this message, your password will not be changed.";
				$subject="Password Recovery Request";
				$mail = p_mail($to,$subject,$body);
				if ($mail){
					// ok 
				} 
				else{
					$response['errors'][] = "Error sending in mail.";
				}
			}else{
			    //echo "ddsd";
				$response['errors'][] = $con->error;
			}
			//print_r($response['errors']);
		}
		
		if(empty($response['errors'])){
			$response['status']=1;
		}
		return $response;
	}
     /* 
    input params - 
        "function" => "/getMetaValue" - function to get gst percentage
    output params - 
        $data = array(
            "status" => 0,
            "errors" => array(),
            "msg" => '',
            "data" => array()
        );
    */
    function getMetaValue(){
        global $con;
        $response = array(
            "status" => 0,
            "errors" => '',
            "msg" => '',
            "data" => ''
        );

        $sql="SELECT meta_value FROM sl_meta_info WHERE meta_id=2 and delete_flag=0";

        $result=$con->query($sql);
        if($result->num_rows){
            while ($row = $result->fetch_assoc()) {
                $response['data'] = $row;				
            }
            $response['status']=1;
        }else{
            $response['status']=0;
            $response['errors'] = $con->error;
        }
        return $response;
    }


    /* 
    input params - 
        "function" => "/getIrregularProductRanges" - function to get product ranges
    output params - 
        $data = array(
            "status" => 0,
            "errors" => array(),
            "msg" => '',
            "data" => array()
        );
    */
    function getIrregularProductRanges(){
        global $con;
        $response = array(
            "status" => 0,
            "errors" => '',
            "msg" => '',
            "data" => array()
        );

        $sql="SELECT * FROM sl_product_ranges_info WHERE product_range_type=2 and delete_flag=0 and is_enabled=1";

        $result=$con->query($sql);
        if($result->num_rows){
            while ($row = $result->fetch_assoc()) {
                $response['data'][] = $row;				
            }
            $response['status']=1;
        }else{
            $response['status']=0;
            $response['errors'] = $con->error;
        }
        return $response;
    }



    /* 
    input params - 
        "function" => "/getItems" - function to get items
        "section_id" => $inputdata
    output params - 
        $data = array(
            "status" => 0,
            "errors" => array(),
            "msg" => '',
            "data" => array()
        );
    */
    function getItems($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "errors" => '',
            "msg" => '',
            "data" => array()
        );

        $sql="SELECT * FROM sl_items_info WHERE section_id=".safe_str($inputdata['section_id'])." and delete_flag=0 and is_enabled=1 order by length";

        $result=$con->query($sql);
        if($result->num_rows){
            while ($row = $result->fetch_assoc()) {
                $response['data'][] = $row;				
            }
            $response['status']=1;
        }else{
            $response['status']=0;
            $response['errors'] = $con->error;
        }
        return $response;
    }  
    
    /* 
    input params - 
        "function" => "/getSections" - function to get sections
        "product_range_id" => $inputdata,
        "product_range_type" => $product_range_type
    output params - 
        $data = array(
            "status" => 0,
            "errors" => array(),
            "msg" => '',
            "data" => array()
        );
    */
    function getSections($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "errors" => '',
            "msg" => '',
            "data" => array()
        );
        if($inputdata['product_range_type']==1){
            $sql="SELECT * FROM sl_sections_info s inner join sl_items_info i on i.section_id=s.section_id and i.delete_flag=0 and i.is_enabled=1 WHERE s.product_range_id=".safe_str($inputdata['product_range_id'])." and s.delete_flag=0 and s.is_enabled=1 group by s.section_id order by s.section_name";
        }elseif($inputdata['product_range_type']==2){
             $sql="SELECT * FROM sl_sections_info s WHERE s.product_range_id=".safe_str($inputdata['product_range_id'])." and s.delete_flag=0 and s.is_enabled=1 group by s.section_id order by s.section_name";
        }
        $result=$con->query($sql);
        if($result->num_rows){
            while ($row = $result->fetch_assoc()) {
                $response['data'][] = $row;				
            }
            $response['status']=1;
        }else{
            $response['status']=0;
            $response['errors'] = $con->error;
        }
        return $response;
    }         

    /* 
    input params - 
        "function" => "/getProductRanges" - function to get product ranges
        "product_range_type" => $product_range_type
    output params - 
        $data = array(
            "status" => 0,
            "errors" => array(),
            "msg" => '',
            "data" => array()
        );
    */
    function getProductRanges($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "errors" => '',
            "msg" => '',
            "data" => array()
        );
        if($inputdata['product_range_type']==1){
            $sql="SELECT * FROM sl_product_ranges_info p inner join sl_sections_info s on s.product_range_id=p.product_range_id and s.delete_flag=0 and s.is_enabled=1 inner join sl_items_info i on i.section_id=s.section_id and i.delete_flag=0 and i.is_enabled=1 WHERE p.product_range_type='".safe_str($inputdata['product_range_type'])."' and p.delete_flag=0 and p.is_enabled=1 group by p.product_range_id order by p.product_range_name";
        }elseif($inputdata['product_range_type']==2){
            $sql="SELECT * FROM sl_product_ranges_info p inner join sl_sections_info s on s.product_range_id=p.product_range_id and s.delete_flag=0 and s.is_enabled=1 WHERE p.product_range_type='".safe_str($inputdata['product_range_type'])."' and p.delete_flag=0 and p.is_enabled=1 group by p.product_range_id order by p.product_range_name";
        }
        
        $result=$con->query($sql);
        if($result->num_rows){
            while ($row = $result->fetch_assoc()) {
                $response['data'][] = $row;				
            }
            $response['status']=1;
        }else{
            $response['status']=0;
            $response['errors'] = $con->error;
        }
        return $response;
    }

    /* 
    input params - 
        "function" => "/getLoginDetails" - function to get login details of user
        "user_type_id" => $inputdata
    output params - 
        $data = array(
            "status" => 0,
            "errors" => array(),
            "msg" => '',
            "data" => array()
        );
    */
    function getLoginDetails($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "errors" => '',
            "msg" => '',
            "data" => array()
        );

        $sql="SELECT * FROM `sl_user_login_info` where user_type_id=".safe_str($inputdata['user_type_id'])." and delete_flag=0 and is_enabled=1";

        $result=$con->query($sql);
        if($result->num_rows){
            while ($row = $result->fetch_assoc()) {
                $response['data'][] = $row;				
            }
            $response['status']=1;
        }else{
            $response['status']=0;
            $response['errors'] = $con->error;
        }
        return $response;
    }
    
    /* 
	input params - 
		"function" => "UpdateCustomerAfterEmailVerification" - function to update customer details after email verification 
		"email" => $email,
		"customer_id" => $result['customer_id']
	output params - 
		$response = array(
            "status" => 0,
            "msg" => '',
			"errors" => array()
		);
	*/
    function UpdateCustomerAfterEmailVerification($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "msg"=>'',
            "errors" => array()
        );
		if(empty($response['errors'])){
			$sql="update sl_user_login_info set email='".safe_str($inputdata['email'])."',email_confirmation_token='',email_token_created_on='',modified_on='".date('Y-m-d H:i:s')."' where user_type_id='".safe_str($inputdata['customer_id'])."' and user_type=3 and delete_flag=0";
			$res=$con->query($sql);
			if($res){
				$response['status']=1;
			}else{
				$response['errros'][] = $con->error;
			}
		}
		return $response;
	}
	
	/* 
	input params - 
		"function" => "check_if_email_already_taken" - function to check if email to verify is already taken by some other user 
		"email" => $result['temp_email'],
	output params - 
		$response = array(
            "status" => 0,
            "msg" => '',
			"errors" => array()
		);
	*/
    function check_if_email_already_taken($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "msg"=>'',
            "errors" => array()
        );
		$sql="select * from sl_user_login_info where email='".safe_str($inputdata['email'])."' and user_type=3 and delete_flag=0";
		$res=$con->query($sql);
		if($res->num_rows){
			$response['status']=1;
		}
		return $response;
	}
	
    /* 
	input params - 
		"function" => "check_if_token_exists" - function to check if email verification token is valid  
		"email_token" => $token
	output params - 
		$response = array(
            "status" => 0,
            "msg" => '',
			"errors" => array()
		);
	*/
    function check_if_token_exists($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "msg"=>'',
            "errors" => array()
        );
		$sql="select * from sl_user_login_info where email_confirmation_token='".safe_str($inputdata['email_token'])."' and user_type=3 and delete_flag=0";
		$res=$con->query($sql);
		if($res->num_rows){
			$response['status']=1;
			$response['data']=$res->fetch_assoc();
		}
		return $response;
	}
	
    /* 
	input params - 
		"function" => "Login" - function to save login details to database
		"loginData" => $inputdata
	output params - 
		$response = array(
            "status" => 0,
            "msg" => '',
			"errors" => array()
		);
	*/
    function Login($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "msg"=>'',
            "errors" => array()
        );
        $password = safe_str(md5($inputdata['loginData']['password']));
        $email = safe_str($inputdata['loginData']['email']);
        $data=$inputdata['loginData'];
        $validations=array(
            "email"=>[
                "validate"=>"required,custom[email]",
                "label"=>"Email"
            ],
            "password"=>[
                "validate"=>"required",
                "label"=>"Password"
            ]
        );
        $PSValidationEngine = new PSValidationEngine($inputdata['loginData'], $validations,true);
        $response['errors']=$PSValidationEngine->validate();
        if(!empty($password) && !empty($email) && empty($response['errors']))
        {
            $checkemail="Select * from sl_user_login_info where email = '".$email."' and user_type=3 and delete_flag=0";
            $checkQry = $con->query($checkemail);
            if($checkQry){
                if ($checkQry->num_rows) {
                    $row = $checkQry->fetch_assoc();
                    if($row['is_enabled']==1){
                        if(strcmp($password,$row['password'])==0){
                                $response['status']=1;
                                $response['data']['user_id']=$row['user_type_id'];
                                $response['msg']="You are logged in";
                        }
                        else
                        {
                            $response['errors'][]="Incorrect email or password";
                        }
                    }
                    else{
                        $response['errors'][]="Customer is inactive";
                    }
                }
                else{
                    $response['errors'][] = "Incorrect email or password";
                }
            }
            else{
                $response['errors'][] = 'Something went wrong. Please try again!';
            }
        }
        return $response;
    }

    /* 
	input params - 
		"function" => "SignUp" - function to save SignUp Details to database
		"signupdata" => $inputdata
	output params - 
		$response = array(
            "status" => 0,
            "msg" => '',
			"errors" => array()
		);
	*/
    function SignUp($inputdata){
        global $con;
        $response = array(
            "status" => 0,
            "msg"=>'',
            "errors" => array()
        );
        if(!empty($inputdata)){
            $validations=array(
                "first_name"=>[
                    "validate"=>"required",
                    "label"=>"First Name"
                ],
                "last_name"=>[
                    "validate"=>"required",
                    "label"=>"Last Name"
                ],
                "email"=>[
                    "validate"=>"required,custom[email]",
                    "label"=>"Email"
                ],
                "password"=>[
                    "validate"=>"required",
                    "label"=>"Password"
                ],
				"phone"=>[
                    "validate"=>"required,custom[phone]",
                    "label"=>"Phone"
                ],
            );
            if(isset($inputdata['SignUpData']['check'])){
				$validations2=array(
					"buisness_name"=>[
						"validate"=>"required",
						"label"=>"Business name"
					],
					"street_address"=>[
						"validate"=>"required",
						"label"=>"Street Address 1"
					],
					"street_address_two"=>[
						"validate"=>"required",
						"label"=>"Street Address 2"
					],
					"city"=>[
						"validate"=>"required",
						"label"=>"City"
					],
					"state"=>[
						"validate"=>"required",
						"label"=>"State"
					],
					"zip"=>[
						"validate"=>"required,custom[postcode]",
						"label"=>"Postcode"
					],
				);
				$validations = $validations+$validations2;
			}
            $PSValidationEngine = new PSValidationEngine($inputdata['SignUpData'], $validations,true);
            $response['errors']=$PSValidationEngine->validate();
            //print_r($response['errors']);
            $check=0;
            if(isset($inputdata['SignUpData']['check'])){
                $check=1;
            }
			// check if email already exists
			$sql="select * from sl_user_login_info where email='".safe_str($inputdata['SignUpData']['email'])."' and delete_flag=0 and user_type=3";
			$res=$con->query($sql);
			if($res->num_rows){
				$response['errors'][] = "This email is already taken by another customer.";
			}
            if(empty($response['errors'])){
				$token_exists=1;
				while($token_exists==1){
					$token = md5(openssl_random_pseudo_bytes(32));
					// check if this token exists in db already 
					$sql1="select * from sl_user_login_info where user_type=3 and delete_flag=0 and email_confirmation_token='".$token."'";
					$result1=$con->query($sql1);
					if(empty($result1->num_rows)){
						$token_exists=0;
					}
				}
				
				$tokenEnc =  $token;
                $sql = "INSERT INTO `sl_customers_info`(`business_name`, `abn_number`, `acn_number`,`credit_account_applied`,`created_on`) VALUES ('".safe_str($inputdata['SignUpData']['buisness_name'])."','".safe_str($inputdata['SignUpData']['abn'])."','".safe_str($inputdata['SignUpData']['acn'])."',".safe_str($check).",'".date('Y-m-d H:i:s')."')";
                $res=$con->query($sql);
                
                $insert_id = $con->insert_id;             
                if(!empty($insert_id)){
                    $query = "INSERT INTO `sl_user_login_info`(`user_type`,`user_type_id`, `first_name`, `last_name`, `phone`, `temp_email`, `password`, `is_enabled`, `created_on`,`email_confirmation_token`,`email_token_created_on`) VALUES (3,".safe_str($insert_id).",'".safe_str($inputdata['SignUpData']['first_name'])."','".safe_str($inputdata['SignUpData']['last_name'])."','".safe_str($inputdata['SignUpData']['phone'])."','".safe_str($inputdata['SignUpData']['email'])."','".safe_str(md5($inputdata['SignUpData']['password']))."',1,'".date('Y-m-d H:i:s')."','".$token."','".date('Y-m-d H:i:s')."')"; 
                    $result=$con->query($query); 
					if(!$result){
						$response['errors'][] = $con->error;
                    }else{
                        $query_two = "INSERT INTO `sl_customer_addresses_info`(`customer_id`, `street_address1`, `street_address2`, `city`, `state`, `zip`, `is_enabled`, `created_on`) VALUES (".$insert_id.",'".$inputdata['SignUpData']['street_address']."','".$inputdata['SignUpData']['street_address_two']."','".$inputdata['SignUpData']['city']."','".$inputdata['SignUpData']['state']."','".$inputdata['SignUpData']['zip']."',1,'".date('Y-m-d H:i:s')."')";
                        $result_two=$con->query($query_two); 
                        if(!$result_two){
    						$response['errors'][] = $con->error;
                        }
                    }
                }else{
					$response['errors'][] = $con->error;
				}
				if(empty($response['errors'])){
					// send email for verification 
					$to = $inputdata['SignUpData']['email'];
					$subject="Email Verification";
					$body="Hello ".$inputdata['SignUpData']['first_name'].",<br><br>Thank you for signing up. Please click on link below to verify your email address. <small>(Link valid for next 2 hours)</small><br><a href='".SITE_URL."/verify-email.php?token=".$tokenEnc."'>".SITE_URL."/verify-email.php?token=" . $tokenEnc."</a><br>";
					$mail = p_mail($to,$subject,$body);
					if($mail==1){
						// ok
						// check if customer applied for credit account 
						if(isset($inputdata['SignUpData']['check'])){
							// send mail to Admin 
							$res = $con->query("select meta_value from sl_meta_info where meta_id=2");
							$row = $res->fetch_assoc();
							$to = $row['meta_value'];  // admin email address
							$subject = "Credit Account applied";
							$body="Hello Admin,<br><br>A new registered customer has applied for the Credit Account.";
							$mail=p_mail($to,$subject,$body);
							if($mail==1){
								// ok 
							}else{
								$response['errors'][] = "Error in sending mail to Admin.";
							}
						}
					}else{
						$response['errors'][] = "Error in sending mail.";
					}
				}
                if(empty($response['errors'])){
                    $response['status']=1;
                    $response['msg']="Saved successfully";
                }
                else{
                    $response['status']=0;
                    $response['errors'][]=$con->error;
                }
            }          
        }
        return $response;
    }
}
?>