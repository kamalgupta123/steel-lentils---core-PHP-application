<?php
include_once(__DIR__ ."/../autoload.php"); 

class Admin
{ 	
	/* 
	input params - 
		"function" => "UpdateStaffIsEnabled" - function to update staff
		"inputdata" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"msg" => '',
			"errors" => array(),
			"data" => array()
		);
	*/
	function UpdateStaffIsEnabled($inputdata){
        global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
		);
		
		if(!empty($inputdata)){

			$is_enabled = safe_str($inputdata['data']['check_items']);
			$skill_id = safe_str($inputdata['skill_id']);
		
			if(!empty($skill_id)){
				$sql = "UPDATE sl_staff_skills_info SET is_enabled=".$is_enabled." WHERE skill_id=".safe_str($skill_id);
			}

			$result = $con->query($sql);
			
			if($result){
				$response['status']=1;
				$response['msg']="Updated Successfully";
			}
			else{
				$response['status']=0;
				$response['errors'][]=$con->error;
			}
		}
		return $response;
	}

	/* 
	input params - 
		"function" => "/GetSkillDetails" - function to get the skill details 
		"skill_id" => $skill_id
	output params - 
		$data = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
	*/
	function GetSkillDetails($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
		$sql='select * from sl_staff_skills_info where skill_id='.safe_str($inputdata['skill_id']).' and delete_flag=0';
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
		"function" => "SaveStaff" - function to save Staff Details to database 
		"inputdata" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"msg" => '',
			"errors" => array(),
			"data" => array()
		);
	*/
	function SaveStaff($inputdata){
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
			$email = safe_str($inputdata['data']['email']);
			$skill = safe_str($inputdata['data']['skill']);
			$customer_id = safe_str($inputdata['customer_id']);
			$is_enabled=0;
			if(!empty($inputdata['data']['is_enabled'])){
				$is_enabled = safe_str($inputdata['data']['is_enabled']);
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
				"email"=>[
					"validate"=>"required",
					"label"=>"Email"
				],
				"skill"=>[
					"validate"=>"required",
					"label"=>"Skill"
				]
			);

			$PSValidationEngine = new PSValidationEngine($inputdata['data'], $validations,true);
			$response['errors']=$PSValidationEngine->validate();
			
			$sql_reg = "select i.*,c.*,s.* from `sl_staff_info` i inner join sl_staff_skills_info c on c.skill_id = i.staff_id inner join sl_user_login_info s on s.user_type=2 where i.delete_flag=0 and c.delete_flag=0 and s.delete_flag=0 and s.email='".$email."'";

			$res_reg = $con->query($sql_reg);
			if($res_reg->num_rows){
				$response['errors'][]="Same Email already exists";
			}
			
			if(empty($response['errors'])){
		    	if(empty($inputdata['skill_id']) && empty($inputdata['user_login_id'])){
					$sql = "INSERT INTO `sl_user_login_info`(`user_type`,`user_type_id`,`password`,`pwd_verification_check`,`first_name`, `last_name`, `phone`, `email`,`is_enabled`, `created_on`,`delete_flag`) VALUES (2,".$customer_id.",'',0,'".$first_name."','".$last_name."','','".$email."',".$is_enabled.",'".date('Y-m-d H:i:s')."',0)";

					$sequel = "INSERT INTO `sl_staff_skills_info`(`skill_name`, `is_enabled`, `created_on`,`delete_flag`) VALUES ('".$skill."','".$is_enabled."','".date('Y-m-d H:i:s')."',0)";
    			}
    			else{
					if(!empty($inputdata['user_login_id'])){
						$sql = "UPDATE `sl_user_login_info` SET `first_name`='".$first_name."',`last_name`='".$last_name."',`email`='".$email."',`is_enabled`=".$is_enabled.",`created_on`='".date('Y-m-d H:i:s')."' WHERE user_login_id=".safe_str($inputdata['user_login_id']);
					}
					if(!empty($inputdata['skill_id'])){
						$sequel="UPDATE `sl_staff_skills_info` SET `skill_name`='".$skill."',`is_enabled`='".$is_enabled."',`created_on`='".date('Y-m-d H:i:s')."' WHERE skill_id =".$inputdata['skill_id'];
					}
    			}
    
    			$result = $con->query($sql);
    			$res = $con->query($sequel);
    			
				if($result && $res){
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
		"function" => "ListStaff" - function to get the list of Staff
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
	function ListStaff($inputdata){
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
			$where = " and (c.skill_name like '%".safe_str($inputdata['search'])."%' or s.email like '%".safe_str($inputdata['search'])."%' or s.first_name like '%".safe_str($inputdata['search'])."%')";
		}
		
		$pcount_qry = "select count(*) as total_count from `sl_staff_info` i inner join sl_staff_skills_info c on c.skill_id = i.staff_id inner join sl_user_login_info s on s.user_type=2 where i.delete_flag=0 and c.delete_flag=0 and s.delete_flag=0".$where;
		
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
		$get_det_query="select i.*,c.*,s.user_login_id,s.first_name,s.last_name,s.email from `sl_staff_info` i inner join sl_staff_skills_info c on c.skill_id = i.staff_id inner join sl_user_login_info s on s.user_type=2 where i.delete_flag=0 and c.delete_flag=0 and s.delete_flag=0".$where." order by $sort_on $sort_type LIMIT $row_size OFFSET ".($page_no-1)*$row_size;
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
		"function" => "/GetTruckDetails" - function to get the details of a Truck
		"truck_id" => $truck_id
	output params - 
		$data = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
	*/
	function GetTruckDetails($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
		$sql='select * from sl_trucks_info where truck_id='.safe_str($inputdata['truck_id']).' and delete_flag=0';
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
		"function" => "UpdateTruksIsEnabled" - function to update is enabled of trucks
		"inputdata" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"msg" => '',
			"errors" => array(),
			"data" => array()
		);
	*/
	function UpdateTrucksIsEnabled($inputdata){
        global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
		);
		
		if(!empty($inputdata)){

			$is_enabled = safe_str($inputdata['data']['check_items']);
			$truck_id = safe_str($inputdata['truck_id']);
		
			if(!empty($truck_id)){
				$sql = "UPDATE sl_trucks_info SET is_enabled=".safe_str($is_enabled)." WHERE truck_id=".safe_str($truck_id);
			}

			$result = $con->query($sql);
			
			if($result){
				$response['status']=1;
				$response['msg']="Updated Successfully";
			}
			else{
				$response['status']=0;
				$response['errors'][]=$con->error;
			}
		}
		return $response;
	}
	/* 
	input params - 
		"function" => "ListTrucks" - function to get the list of Trucks
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
	function ListTrucks($inputdata){
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
			$where = " and (i.registration_number like '%".safe_str($inputdata['search'])."%')";
		}
		
		$pcount_qry = "select count(*) as total_count from `sl_trucks_info` i where i.delete_flag=0".$where;
		
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
		$get_det_query="select i.* from sl_trucks_info i where i.delete_flag=0".$where." order by $sort_on $sort_type LIMIT $row_size OFFSET ".($page_no-1)*$row_size;
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
		"function" => "SaveTrucks" - function to save Trucks to database 
		"inputdata" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"msg" => '',
			"errors" => array(),
			"data" => array()
		);
	*/
	function SaveTrucks($inputdata){
        global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
		);
		
		if(!empty($inputdata)){
			$validations=array(
				"registration_number"=>[
					"validate"=>"required",
					"label"=>"Registration Number"
				]
			);

			$PSValidationEngine = new PSValidationEngine($inputdata['data'], $validations,true);
			$response['errors']=$PSValidationEngine->validate();	

			if(empty($response['errors'])){
				$registration_number = safe_str($inputdata['data']['registration_number']);
				$sql_reg = "SELECT * FROM `sl_trucks_info` WHERE `registration_number`='".$registration_number."'";
				$res_reg = $con->query($sql_reg);
				if($res_reg->num_rows){
					$response['errors'][]="Same registration number";
				}
				else{
					$is_enabled=0;
					if(!empty($inputdata['data']['is_enabled'])){
						$is_enabled = safe_str($inputdata['data']['is_enabled']);
					}
						if(empty($inputdata['truck_id'])){
							$sql = "INSERT INTO `sl_trucks_info`(`registration_number`,`is_enabled`,`created_on`) VALUES (".safe_str($registration_number).",".safe_str($is_enabled).",'".date('Y-m-d H:i:s')."')";
						}
						else{
							$sql = "UPDATE `sl_trucks_info` SET `registration_number`=".safe_str($registration_number).",`is_enabled`=".safe_str($is_enabled).",`modified_on`='".date('Y-m-d H:i:s')."' WHERE truck_id=".safe_str($inputdata['truck_id']);
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
		}
		return $response;
	}


	/* 
	input params - 
		"function" => "/GetCustomerDetails" - function to get the details of a Customer
		"customer" => $customer_id
	output params - 
		$data = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
	*/
	function GetCustomerDetailsThree($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
		$sql='select * from sl_customers_info where customer_id='.safe_str($inputdata['customer']).' and delete_flag=0';
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
		"function" => "/GetCustomerDetails" - function to get the details of a Customer
		"customer" => $customer_id
	output params - 
		$data = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
	*/
	function GetCustomerDetailsTwo($inputdata){
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
		"function" => "/GetCustomerDetails" - function to get the details of Customer
		"customer" => $customer_id
	output params - 
		$data = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
	*/
	function GetCustomerDetails($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
		$sql='select * from sl_user_login_info where user_login_id='.safe_str($inputdata['customer_id']).' and delete_flag=0';
		
		// $sql1='select * from sl_customer_addresses_info where customer_address_id='.safe_str($inputdata['customer_address_id']).' and delete_flag=0';

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
		"function" => "SaveCustomers" - function to save Customers to database 
		"inputdata" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"msg" => '',
			"errors" => array(),
			"data" => array()
		);
	*/
	function SaveCustomers($inputdata){
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
			$email = safe_str($inputdata['data']['email']);
			$phone = safe_str($inputdata['data']['phone']);
			$buisness_name = safe_str($inputdata['data']['buisness_name']);
			$street_address_one = safe_str($inputdata['data']['street_address_one']);
			$street_address_two = safe_str($inputdata['data']['street_address_two']);
			$city = safe_str($inputdata['data']['city']);
			$state = safe_str($inputdata['data']['state']);
			$zip = safe_str($inputdata['data']['zip']);
			$customer_id = safe_str($inputdata['customer_id']);
			$password = safe_str($inputdata['data']['password']);
			$is_enabled=0;
			if(!empty($inputdata['data']['is_enabled'])){
				$is_enabled = safe_str($inputdata['data']['is_enabled']);
			}
			$credit_limit = 0;
			$payment_terms = 0;
			if(!empty($credit_limit)){
				$credit_limit = safe_str($inputdata['data']['credit_limit']);
			}
			if(!empty($payment_terms)){
				$payment_terms = safe_str($inputdata['data']['payment_terms']);
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
				"email"=>[
					"validate"=>"required",
					"label"=>"Email"
				],
				"phone"=>[
					"validate"=>"required",
					"label"=>"Phone"
				],
				"buisness_name"=>[
					"validate"=>"required",
					"label"=>"Buisness Name"
				],
				"street_address_one"=>[
					"validate"=>"required",
					"label"=>"Street Address One"
				]
			);

			$PSValidationEngine = new PSValidationEngine($inputdata['data'], $validations,true);
			$response['errors']=$PSValidationEngine->validate();	

			if(empty($response['errors'])){
		    	if(empty($inputdata['user_login_id']) && empty($inputdata['customer_address_id'])){
					$sql = "INSERT INTO `sl_user_login_info`(`user_type`,`user_type_id`,`password`,`pwd_verification_check`,`first_name`, `last_name`, `phone`, `temp_email`,`is_enabled`, `created_on`) VALUES (1,".$customer_id.",'".md5($password)."','','".$first_name."','".$last_name."','".$phone."','".$email."',".$is_enabled.",'".date('Y-m-d H:i:s')."')";
					// echo $sql;

					$sequel = "INSERT INTO `sl_customer_addresses_info`(`customer_id`, `street_address1`, `street_address2`, `city`, `state`, `zip`, `is_enabled`, `created_on`,`delete_flag`,`is_buisness_address`) VALUES (".$customer_id.",'".$street_address_one."','".$street_address_two."','".$city."','".$state."','".$zip."',".$is_enabled.",'".date('Y-m-d H:i:s')."',0,0)";
					// echo $sequel;

					$sql2 = "UPDATE `sl_customers_info` SET `business_name`='".$buisness_name."' WHERE customer_id=".$customer_id;
					// echo $sql2;
    			}
    			else{
					if(!empty($inputdata['user_login_id'])){
						$sql = "UPDATE `sl_user_login_info` SET `first_name`='".$first_name."',`last_name`='".$last_name."',`phone`='".$phone."',`temp_email`='".$email."',`password`='".md5($password)."',`created_on`='".date('Y-m-d H:i:s')."' WHERE user_login_id=".safe_str($inputdata['user_login_id']);
					}
					if(!empty($inputdata['customer_address_id'])){
						$sequel="UPDATE `sl_customer_addresses_info` SET `street_address1`='".$street_address_one."',`street_address2`='".$street_address_two."',`city`='".$city."',`state`='".$state."',`zip`='".$zip."',`modified_on`='".date('Y-m-d H:i:s')."' WHERE customer_address_id =".$inputdata['customer_address_id'];
					}
					if(!empty($customer_id)){
						$sql2="UPDATE `sl_customers_info` SET `business_name`='".$buisness_name."' WHERE customer_id=".$customer_id;
					}
    			}
    
    			$result = $con->query($sql);
    			$res = $con->query($sequel);
    			$res2 = $con->query($sql2);
    			
				if($result && $res && $res2){
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
		"function" => "/DeleteCustomers" - function to delete customers
		"items"=>$itemsId
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function DeleteCustomers($customersId){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
		$customers = implode(",",$customersId['customer']);
		$sql="UPDATE `sl_user_login_info` SET `delete_flag`=1 WHERE user_login_id IN (".safe_str($customers).")";
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
		"function" => "ListCustomers" - function to get the list of Customers
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
	function ListCustomers($inputdata){
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
			$where = " and (i.first_name like '%".safe_str($inputdata['search'])."%' or i.email like '%".safe_str($inputdata['search'])."%' or i.phone like '%".safe_str($inputdata['search'])."%')";
		}
		
		$pcount_qry = "select count(*) as total_count from `sl_user_login_info` i inner join sl_customers_info c on c.customer_id = i.user_type_id inner join sl_customer_addresses_info s on s.customer_id=i.user_type_id  where i.delete_flag=0 and c.delete_flag=0 and s.delete_flag=0 group by i.phone".$where;
		
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
		$get_det_query="select i.*,c.business_name,s.* from sl_user_login_info i inner join sl_customers_info c on c.customer_id = i.user_type_id inner join sl_customer_addresses_info s on s.customer_id=i.user_type_id where i.delete_flag=0".$where." group by i.phone order by $sort_on $sort_type LIMIT $row_size OFFSET ".($page_no-1)*$row_size;
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
			$where = " and (i.street_address1 like '%".safe_str($inputdata['search'])."%' or i.city like '%".safe_str($inputdata['search'])."%' or i.state like '%".safe_str($inputdata['search'])."%' or i.zip like '%".safe_str($inputdata['search'])."%')";
		}
		
		$pcount_qry = "select count(*) as total_count from `sl_customer_contacts_info` i inner join sl_customers_info c on i.customer_id=c.customer_id inner join sl_customer_addresses_info s on i.customer_id=s.customer_id where i.delete_flag=0".$where;
		
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
		$get_det_query="select * from sl_customer_contacts_info i  inner join sl_customers_info c on c.customer_id = i.customer_id inner join sl_customer_addresses_info s on s.customer_id=i.customer_id where i.delete_flag=0".$where." order by $sort_on $sort_type LIMIT $row_size OFFSET ".($page_no-1)*$row_size;

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
		echo $pagging_list;
		return $response;
	}
	
	/* 
	input params - 
		"function" => "UpdateSectionIsEnabled" - function to update is enabled of Section
		"inputdata" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"msg" => '',
			"errors" => array(),
			"data" => array()
		);
	*/
	function UpdateSectionIsEnabled($inputdata){
        global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
		);
		
		if(!empty($inputdata)){

			$is_enabled = safe_str($inputdata['data']['check_items']);
			$section_id = safe_str($inputdata['section_id']);
		
			if(!empty($section_id)){
				$sql = "UPDATE sl_sections_info SET is_enabled=".$is_enabled." WHERE section_id=".safe_str($section_id);
			}

			$result = $con->query($sql);
			
			if($result){
				$response['status']=1;
				$response['msg']="Updated Successfully";
			}
			else{
				$response['status']=0;
				$response['errors'][]=$con->error;
			}
		}
		return $response;
	}

	/* 
	input params - 
		"function" => "UpdateProductRangeIsEnabled" - function to update is enabled of product range
		"inputdata" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"msg" => '',
			"errors" => array(),
			"data" => array()
		);
	*/
	function UpdateProductRangeIsEnabled($inputdata){
        global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
		);
		
		if(!empty($inputdata)){

			$is_enabled = safe_str($inputdata['data']['check_items']);
			$product_range_id = safe_str($inputdata['product_range_id']);
		
			if(!empty($product_range_id)){
				$sql = "UPDATE sl_product_ranges_info SET is_enabled=".$is_enabled." WHERE product_range_id=".safe_str($product_range_id);
			}

			$result = $con->query($sql);
			
			if($result){
				$response['status']=1;
				$response['msg']="Updated Successfully";
			}
			else{
				$response['status']=0;
				$response['errors'][]=$con->error;
			}
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
		$where = "";
		
		if(!empty($inputdata['search'])){
			$where = " and (i.street_address1 like '%".safe_str($inputdata['search'])."%' or i.city like '%".safe_str($inputdata['search'])."%' or i.state like '%".safe_str($inputdata['search'])."%' or i.zip like '%".safe_str($inputdata['search'])."%')";
		}
		
		$pcount_qry = "select count(*) as total_count from `sl_customer_addresses_info` i where i.delete_flag=0 and i.customer_id=".safe_str($inputdata['customer_id']).$where;
		
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
		$get_det_query="select * from sl_customer_addresses_info i where i.delete_flag=0 and i.customer_id=".safe_str($inputdata['customer_id']).$where." order by $sort_on $sort_type LIMIT $row_size OFFSET ".($page_no-1)*$row_size;
		// echo $get_det_query;
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
		"function" => "UpdateItemsIsEnabled" - function to update is enabled of items
		"inputdata" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"msg" => '',
			"errors" => array(),
			"data" => array()
		);
	*/
	function UpdateItemsIsEnabled($inputdata){
        global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
		);
		
		if(!empty($inputdata)){

			$is_enabled = safe_str($inputdata['data']['check_items']);
			$item_id = safe_str($inputdata['item_id']);
		
			if(!empty($item_id)){
				$sql = "UPDATE sl_items_info SET is_enabled=".$is_enabled." WHERE item_id=".safe_str($item_id);
			}

			$result = $con->query($sql);
			
			if($result){
				$response['status']=1;
				$response['msg']="Updated Successfully";
			}
			else{
				$response['status']=0;
				$response['errors'][]=$con->error;
			}
		}
		return $response;
	}

    /*
	input - 
		"function"=>"GetSectionList"
	output - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function GetSectionList(){
		global $con;
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);

		$query = "SELECT * FROM sl_sections_info WHERE is_enabled=1 AND `delete_flag`=0 ORDER BY section_name";

		$result = $con->query($query);
		$i=0;
		while($row = $result->fetch_assoc()){
			$data['data'][$i] = $row;
			$i++;
		}
		return $data;
	}

    /* 
	input params - 
		"function" => "Admin/saveAdminDetails", function to save admin details
        "adminData" => $_POST,
        "id" => $Encryption->decode($_POST['admin_id'])
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
    function saveAdminDetails($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
		
		$validations=array(
			"first_name"=>[
				"validate"=>"required",
				"label"=>"First Name"
			],
			"last_name"=>[
				"validate"=>"required",
				"label"=>"Last Name"
			],
			"user_email"=>[
				"validate"=>"required,custom[email]",
				"label"=>"Email"
			]
		);
		$PSValidationEngine = new PSValidationEngine($inputdata['adminData'], $validations,true);
		$response['errors']=$PSValidationEngine->validate();
		if(empty($response['errors'])){
		    $password="";
		    if(!empty($inputdata['adminData']['pass'])){
		        if($inputdata['adminData']['pass']!=$inputdata['adminData']['c_pass']){
		            $response['errors'][]="Passwords donot match.";
		        }else{
		            $password=", password='".safe_str(md5($inputdata['adminData']['pass']))."'";
		        }
		    }
		    if(empty($response['errors'])){
    			$sql="UPDATE `sl_user_login_info` SET
    			    `first_name`='".safe_str($inputdata['adminData']['first_name'])."',
    			    `last_name`='".safe_str($inputdata['adminData']['last_name'])."',
    			    `email`='".safe_str($inputdata['adminData']['user_email'])."',
    			    `modified_on`='".date('Y-m-d H:i:s')."'
    			    ".$password."
    			    where user_type_id='".safe_str($inputdata['id'])."' and user_login_id='".safe_str($inputdata['id'])."' and delete_flag=0
    			    ";
    			$result=$con->query($sql);
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
		"function" => "Admin/SaveGlobalSettings",
		"fields_data" => $_POST, 
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
    function SaveGlobalSettings($inputdata){
        //print_r($inputdata);
		global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
		);
		$error=0;
		foreach($inputdata['fields_data']['MetaKeyToEdit'] as $key=>$val){
		    if(empty($val)){
		        $error=1;
		    }
		}
		if($error==1){
		    $response['errors'][] = "All fields are required";
		}else{
		    foreach($inputdata['fields_data']['MetaKeyToEdit'] as $key=>$val){
		        $sql="select * from sl_meta_info where meta_id='".safe_str($key)."'";
		        $res=$con->query($sql);
		        if($res->num_rows){
		            $update="update sl_meta_info set meta_value='".safe_str($val)."',modified_on='".date('Y-m-d H:i:s')."' where meta_id='".safe_str($key)."'";
		            $res=$con->query($update);
		            if(!$res){
		                $response['errors'][] = $con->error;
		            }
		        }else{
		            $insert="insert into sl_meta_info(meta_id,meta_value,created_on)values('".safe_str($key)."','".safe_str($val)."','".date('Y-m-d H:i:s')."')";
		            $res=$con->query($insert);
		            if(!$res){
		                $response['errors'][] = $con->error;
		            }
		        }
		    }
		}
		if(empty($response['errors'])){
		    $response['status']=1;
		    $response['msg']="Saved successfully";
		}
		return $response;
	}    
    /* 
	input params - 
		"function"=>"Admin/GetGlobalSettings"   
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
    function GetGlobalSettings($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
		);
		$query = "select * from `sl_meta_info` a where delete_flag=0";
		if($result = $con->query($query)){
			while($res = $result->fetch_assoc()){
				$response['status'] = 1;
				$response["data"][$res['meta_id']] = $res;
			}
		}
		return $response;
	}    
    
    /* 
	input params - 
		"function" => "/getAdminDetails" - function to delete items
		"id" => $_SESSION['sl_admin']['user_id'],
         "type" => $_SESSION['sl_admin']['user_type']
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
    function getAdminDetails($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
		);
		$query = "select * from `sl_user_login_info` a where a.user_type_id='".safe_str($inputdata['id'])."' and a.user_type='".safe_str($inputdata['type'])."' and a.delete_flag=0";
		if($result = $con->query($query)){
			if($res = $result->fetch_assoc()){
				$response['status'] = 1;
				$response["data"] = $res;
			}
		}
		return $response;
	}  
	  
    /* 
	input params - 
		"function" => "/DeleteItems" - function to delete items
		"items"=>$itemsId
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function DeleteItems($itemsId){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
		$items =implode(",",$itemsId['items']);
		$sql="UPDATE `sl_items_info` SET `delete_flag`=1 WHERE item_id IN (".safe_str($items).")";
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
	"function" => "GetProductRecommendationDetails" - function to get the details of a section 
	"product_recommendation_id" => $product_recommendation_id
output params - 
	$data = array(
		"status" => 0,
		"errors" => array(),
		"msg" => '',
		"data" => array()
	);
*/
function GetProductRecommendationDetails($inputdata){
	global $con;
	$response = array(
		"status" => 0,
		"errors" => array(),
		"msg" => '',
		"data" => array()
	);
	$sql='select * from sl_product_recommendations_info s where s.delete_flag=0 and s.product_recommendation_id="'.safe_str($inputdata['product_recommendation_id']).'"';
	$result=$con->query($sql);
	if($result->num_rows){
		$response['data'] = $result->fetch_assoc();
		$response['status']=1;
	}else{
		$response['msg'] = "Failed to get details";
	}
	return $response;
}
/* 
	input params - 
		"function" => "/GetIrregularSectionsforRecommendation" - function to get irregular sections for selected Product Recommendation
		"product_recommendation_id"=>$pagg_row["product_recommendation_id"]
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function GetIrregularSectionsforRecommendation($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);

		$sql='SELECT a.*,b.section_name FROM `sl_irregular_sections_recommendations_mapping_info` a left join sl_sections_info b on b.section_id=a.irregular_section_id and b.delete_flag=0 where product_recommendation_id="'.safe_str($inputdata['product_recommendation_id']).'"';
		$result=$con->query($sql);
		if($result->num_rows){
			while ($row = $result->fetch_assoc()) {
				$response['data'][] = $row;				
			}
			$response['status']=1;
		}else{
			$response['errors'] = $con->error;
		}
		return $response;
	}

    /* 
	input params - 
		"function" => "ListProductRecommendation" - function to get the list of ProductRecommendation
		"page_no" => $request["PageNumber"],
		"row_size" => $request["RowSize"],
		"sort_on" => $request["SortOn"],
		"sort_type" => $request["SortType"]
	output params - 
		$response = array(
			"status" => false,
			"data" => array(),
			"msg"=>'',
			"errors" => array()
		);
	*/

	function ListProductRecommendation($inputdata){
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

		$pcount_qry = "select count(*) as total_count from `sl_product_recommendations_info` i where i.delete_flag=0".$where;
		
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
		$get_det_query="select i.*,s.section_name,j.length from sl_product_recommendations_info i LEFT JOIN sl_sections_info s ON i.recommended_section_id = s.section_id and s.delete_flag=0 LEFT JOIN sl_items_info j on j.item_id=i.recommended_item_id and j.delete_flag=0 order by $sort_on $sort_type LIMIT $row_size OFFSET ".($page_no-1)*$row_size;
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
		"function" => "/SaveProductRecommendation1" - function to save product recommendation
		"product_recommendation" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
	*/

	function SaveProductRecommendation1($inputdata){
	    //print_r($inputdata1);
		global $con;
		$response = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
		$inputdata1=$inputdata['product_recommendation'];
		//print_r($inputdata1);
		$is_enabled=0;
		if(isset($inputdata1['is_enabled'])){
			$is_enabled=1;
		}	
		$validations=array(
			"to"=>[
				"validate"=>"required",
				"label"=>"To Length"
			],
			"suggested_stocked_section"=>[
				"validate"=>"required",
				"label"=>"Recommended Stocked Section"
			],
			"suggested_stocked_length"=>[
				"validate"=>"required",
				"label"=>"Recommended Stocked Length"
			]
		);
		$PSValidationEngine = new PSValidationEngine($inputdata['product_recommendation'], $validations,true);
		$response['errors']=$PSValidationEngine->validate();
		if(empty($inputdata1['states'])){
			$response['errors'][]= "Please select atleast 1 Irregular section";
		}
		if($inputdata1['from']>$inputdata1['to']){
			$response['errors'][]= "From Length should be less than To Length.";
		}
		if($inputdata1['from']>=0){
		    // no validation 
		}elseif($inputdata1['from']=='' && $inputdata1['from']!=0){
		    $response['errors'][]= "From Length is required.";
		}elseif($inputdata1['from']<0){
		    $response['errors'][]= "From length must have minimum value 0.";
		}
		if($inputdata1['to']<=0){
		    $response['errors'][]= "To length cannot be negative or equal to 0.";
		}
		if(empty($response['errors'])){
			if(empty($inputdata1['product_recommendation_id'])){
				$sql="INSERT INTO `sl_product_recommendations_info`(`length_from`, `length_to`, `recommended_section_id`, `recommended_item_id`, `is_enabled`, `created_on`) VALUES ('".safe_str($inputdata1['from'])."','".safe_str($inputdata1['to'])."','".safe_str($inputdata1['suggested_stocked_section'])."','".safe_str($inputdata1['suggested_stocked_length'])."','".safe_str($is_enabled)."','".date('Y-m-d H:i:s')."')";
				$result=$con->query($sql);
				if($result){
					foreach( $inputdata1['states'] as $element ){
						$sql1 = "INSERT INTO `sl_irregular_sections_recommendations_mapping_info`(`product_recommendation_id`, `irregular_section_id`) VALUES (".$con->insert_id.",".safe_str($element).")";
						$result5=$con->query($sql1);
						if(!$result5){
							$response['errors'][] = $con->error;
						}
					}
				}else{
					$response['errors'][] = $con->error;
				}
			}else{
				$id=$inputdata1["product_recommendation_id"];
				$from = $inputdata1['from'];
				$to = $inputdata1['to'];
				$stocked_section = $inputdata1['suggested_stocked_section'];
				$stocked_length = $inputdata1['suggested_stocked_length'];
				$is_enabled=0;
				if(isset($inputdata1['is_enabled'])){
					$is_enabled=1;
				}
				
				$q ="UPDATE `sl_product_recommendations_info` SET `length_from`='".safe_str($from)."',`length_to`='".safe_str($to)."',`recommended_section_id`='".safe_str($stocked_section)."',`recommended_item_id`='".safe_str($stocked_length)."',`is_enabled`='".safe_str($is_enabled)."',`modified_on`='".date('Y-m-d H:i:s')."' WHERE product_recommendation_id=".$id;
				$result2=$con->query($q);
				if($result2){
					$sql1 = "DELETE FROM sl_irregular_sections_recommendations_mapping_info where product_recommendation_id='".safe_str($id)."'";
					$result3=$con->query($sql1);
					$irregular_sections = array();
					$irregular_sections = $inputdata1['states'];
					if(!empty($irregular_sections)){
						foreach( $irregular_sections as $element ){
							$sql2 = "INSERT INTO `sl_irregular_sections_recommendations_mapping_info`(`product_recommendation_id`, `irregular_section_id`) VALUES (".$id.",".$element.")";
							$result4=$con->query($sql2);
							if(!$result4){
								$response['errors'][] = $con->error;
							}
						}
					}
				}else{
					$response['errors'][] = $con->error;
				}
			}
		}
        //print_r($response['errors']);
		if(empty($response['errors'])){
			$response['status']=1;
			$response['msg']="Saved Successfully";
		}
		else{
			$response['status']=0;
			$response['errors'][]=$con->error;
		}
		return $response;
	}

	/* 
	input params - 
		"function" => "/SaveProductRecommendation" - function to save product recommendation
		"product_recommendation" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
	

	function SaveProductRecommendation($inputdata){
		global $con;

		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);

		//$edit = $inputdata['product_recommendation']['edit'];

		//if(!empty($edit)){
			$query="SELECT `product_recommendation_id` FROM `sl_product_recommendations_info`";

			$result1 = $con->query($query);
			
			if ($result1->num_rows > 0) {
				while($row = $result1->fetch_assoc()) {
					$id=$row["product_recommendation_id"];
					$from = $inputdata['product_recommendation']['from'.$id];
					$to = $inputdata['product_recommendation']['to'.$id];
					$stocked_section = $inputdata['product_recommendation']['suggested_stocked_section'.$id];
					$stocked_length = $inputdata['product_recommendation']['suggested_stocked_length'.$id];
					$is_enabled=0;
					if(isset($inputdata['product_recommendation']['is_enabled'.$id])){
						$is_enabled=1;
					}
					//$operator = $inputdata['product_recommendation']['select-operators'.$id];
					
					$q ="UPDATE `sl_product_recommendations_info` SET `length_from`='".safe_str($from)."',`length_to`='".safe_str($to)."',`recommended_section_id`='".safe_str($stocked_section)."',`recommended_item_id`='".safe_str($stocked_length)."',`is_enabled`='".safe_str($is_enabled)."',`modified_on`='".date('Y-m-d H:i:s')."' WHERE product_recommendation_id=".$id;
					$result2=$con->query($q);
					if($result2){
						$sql1 = "DELETE FROM sl_irregular_sections_recommendations_mapping_info where product_recommendation_id='".safe_str($id)."'";
						$result3=$con->query($sql1);
						$irregular_sections = array();
						$irregular_sections = $inputdata['product_recommendation']['states'.$id];
						if(!empty($irregular_sections)){
							foreach( $irregular_sections as $element ){
								$sql2 = "INSERT INTO `sl_irregular_sections_recommendations_mapping_info`(`product_recommendation_id`, `irregular_section_id`) VALUES (".$id.",".$element.")";
								$result4=$con->query($sql2);
								if(!$result4){
									$data['errors'][] = $con->error;
								}
							}
						}
					}else{
						$data['errors'][] = $con->error;
					}
				}
			}
			if(empty($data['errors'])){
				$response['status']=1;
				$response['msg']="Saved Successfully";
			}
			else{
				$response['status']=0;
				$response['errors']=$con->error;
			}
		//}
		return $response;
	}*/

	/* 
	input params - 
		"function" => "/GetItemsForSections" - function to get items for stocked sections
		"suggested_stocked_section" => $suggested_stocked_section
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function GetItemsForSections($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);

		$sql='select * FROM sl_items_info where section_id='.safe_str($inputdata['suggested_stocked_section'])." and is_enabled=1 and delete_flag=0";
	
		$result=$con->query($sql);
		if($result->num_rows){
			while ($row = $result->fetch_assoc()) {
				$response['data'][] = $row;				
			}
			$response['status']=1;
		}else{
			$response['errors'] = $con->error;
		}
		return $response;
	}

	/* 
	input params - 
		"function" => "/GetStockedSections" - function to get stocked sections
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function GetStockedSections(){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);

		$sql='SELECT section_id,section_name FROM sl_sections_info s INNER JOIN sl_product_ranges_info p ON s.product_range_id = p.product_range_id and p.product_range_type=1 and p.delete_flag=0 and p.is_enabled=1  where s.delete_flag=0 and s.is_enabled=1';
	
		$result=$con->query($sql);
		
		if($result->num_rows){
			while ($row = $result->fetch_assoc()) {
				$response['data'][] = $row;				
			}
			$response['status']=1;
		}else{
			$response['errors'] = $con->error;
		}
		return $response;
	}

	/* 
	input params - 
		"function" => "/GetIrregularSections" - function to get irregular sections
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function GetIrregularSections(){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);

		$sql='SELECT section_id,section_name FROM sl_sections_info s INNER JOIN sl_product_ranges_info p ON s.product_range_id = p.product_range_id and p.product_range_type=2 and p.delete_flag=0 and p.is_enabled=1  where s.delete_flag=0 and s.is_enabled=1';
	
		$result=$con->query($sql);
		if($result->num_rows){
			while ($row = $result->fetch_assoc()) {
				$response['data'][] = $row;				
			}
			$response['status']=1;
		}else{
			$response['errors'] = $con->error;
		}
		return $response;
	}

	/* 
     /* 
	input params - 
		"function" => "Admin/CheckIfCustomLengthAllowed" - function to get the details of a product range
		"product_range_id" => $product_range_id
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function CheckIfCustomLengthAllowed($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
		$sql='select * from sl_product_ranges_info where delete_flag=0 and product_range_id='.safe_str($inputdata['product_range_id']);
	
		$result=$con->query($sql);
		if($result->num_rows){
			$response['data'] = $result->fetch_assoc();
			if($response['data']['is_custom_length_allowed']==1){
				$response['status']=1;
			}else{
				$response['status']=2;
			}
			if($response['data']['product_range_type']==1){
				$response['data']=1;
			}else{
				$response['data']=2;
			}
		}else{
			$response['errors'][]=$con->error;
		}
		return $response;
	}
	/* 
	input params - 
		"function" => "/GetItemDetails" - function to get the details of a Item
		"item_id" => $item_id
	output params - 
		$data = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
	*/
	function GetItemDetails($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => '',
			"msg" => '',
			"data" => array()
		);
		$sql='select * from sl_items_info where item_id='.safe_str($inputdata['item_id']).' and delete_flag=0';
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
		"function" => "ListProductRanges" - function to get the list of ProductRanges
		"page_no" => $request["PageNumber"],
		"row_size" => $request["RowSize"],
		"sort_on" => $request["SortOn"],
		"sort_type" => $request["SortType"],
		"search" => $search
	output params - 
		$response = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function ListItems($inputdata){
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
		$where1="";
		
		if(!empty($inputdata['search'])){
			$where = " and (i.length like '%".safe_str($inputdata['search'])."%' or s.section_name like '%".safe_str($inputdata['search'])."%')";
		}
		if(!empty($inputdata['product_range'])){
			$where .= " and s.product_range_id = '".safe_str($inputdata['product_range'])."'";
		}
		if(!empty($inputdata['section'])){
			$where .= " and i.section_id = '".safe_str($inputdata['section'])."'";
		}
		$pcount_qry = "select count(*) as total_count from `sl_items_info` i LEFT JOIN sl_sections_info s ON i.section_id = s.section_id LEFT JOIN sl_product_ranges_info p ON  s.product_range_id=p.product_range_id where i.delete_flag=0".$where;
// 		echo $pcount_qry;
		
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
		$get_det_query="select p.product_range_name,i.*,s.section_name from sl_items_info i LEFT JOIN sl_sections_info s ON i.section_id = s.section_id LEFT JOIN sl_product_ranges_info p ON  s.product_range_id=p.product_range_id where i.delete_flag=0".$where." order by $sort_on $sort_type LIMIT $row_size OFFSET ".($page_no-1)*$row_size;
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
		"function" => "SaveItems" - function to save Items to database 
		"inputdata" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"msg" => '',
			"errors" => array(),
			"data" => array()
		);
	*/
	function SaveItems($inputdata){
        global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
		);
		
		if(!empty($inputdata)){

			$section = safe_str($inputdata['section']);
			$price = safe_str($inputdata['price']);
			$length = safe_str($inputdata['length']);
			$weight = safe_str($inputdata['weight']);
			$quantity = safe_str($inputdata['quantity']);
			$order_threshold_value = safe_str($inputdata['order_threshold_value']);
			$created_on = safe_str($inputdata['created_on']);
			$modified_on = safe_str($inputdata['modified_on']);
			$is_enabled = safe_str($inputdata['is_enabled']);
			
			$validations=array(
				"price"=>[
					"validate"=>"required,min[0]",
					"label"=>"Price"
				],
				"length"=>[
					"validate"=>"required,min[1]",
					"label"=>"Length"
				],
				"weight"=>[
					"validate"=>"min[0]",
					"label"=>"Weight"
				],
				"quantity"=>[
					"validate"=>"min[0],custom[integer]",
					"label"=>"Quantity"
				],
				"order_threshold_value"=>[
					"validate"=>"min[0],custom[integer]",
					"label"=>"Order Threshold Value"
				],
				"section"=>[
					"validate"=>"required",
					"label"=>"Section"
				]
			);

			$PSValidationEngine = new PSValidationEngine($inputdata, $validations,true);
			$response['errors']=$PSValidationEngine->validate();
			$is_enable='';
			$condition = "";
            if(!empty($inputdata['item_id'])){
			    $condition = " and item_id<>".$inputdata['item_id'];
		    }
			if(!empty($section)){
				$q="SELECT * FROM `sl_sections_info` WHERE section_id=".$section;
				$r=$con->query($q);
				if ($r->num_rows > 0) {
					while($row = $r->fetch_assoc()) {
						$is_enable = $row["is_enabled"];
					}
				}
				$sec_id=0;
				if(!empty($inputdata['item_id'])){
			    	$p="select section_id from sl_items_info where item_id='".safe_str($inputdata['item_id'])."'";
			    	$result=$con->query($p);
			    	$row=$result->fetch_assoc();
			    	$sec_id=$row['section_id'];
				}
				// $response['data'][] = $is_enabled;
				if($is_enable==0 && $section!=$sec_id){
					$response['errors'][] = "Selected Section is disabled.";	
				}
			}
			$count = 0;
			$sql="select * from sl_items_info where section_id='".safe_str($section)."' and length='".safe_str($length)."' and delete_flag=0".$condition;    
		    $res=$con->query($sql);
			if ($res->num_rows) {
				$response['errors'][]="Item Length for same Section already exists";
			}	
			//echo $count;
			if(empty($response['errors'])){
		    	if(empty($inputdata['item_id'])){
    				$sql = "INSERT INTO `sl_items_info`(`section_id`, `length`, `price`, `weight`, `quantity`, `order_threshold_value`, `is_enabled`, `created_on`) VALUES (".$section.",".$length.",".$price.",".$weight.",".$quantity.",".$order_threshold_value.",".$is_enabled.",'".date('Y-m-d H:i:s')."')";
    			}
    			else{
    				$sql = "UPDATE sl_items_info SET section_id=".safe_str($section).",length='".$length."',price=".$price.",weight=".$weight.",quantity=".$quantity.",order_threshold_value=".$order_threshold_value.",is_enabled=".$is_enabled.",modified_on='".date('Y-m-d H:i:s')."' WHERE item_id=".safe_str($inputdata['item_id']);
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
			if(empty($response['errors'])){
			    $response['status']=1;   
			}
		}
		return $response;
	}

	/* 
	input params - 
		"function" => "/GetProductRangeDetails" - function to get the details of a product range
		"product_range_id" => $product_range_id
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function GetSection($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
		if(!empty($inputdata['item_id'])){
			$sql='select s.*,p.product_range_type from sl_sections_info s left join sl_product_ranges_info p on p.product_range_id=s.product_range_id and p.delete_flag=0 WHERE s.delete_flag=0 group by s.section_id order by s.section_name asc';
		}
		else{
	    	$sql='select s.*,p.product_range_type from sl_sections_info s left join sl_product_ranges_info p on p.product_range_id=s.product_range_id and p.delete_flag=0 WHERE s.delete_flag=0 and s.is_enabled=1 group by s.section_id order by s.section_name asc';   
		}
	
		if($result = $con->query($sql)){
			while($row = $result->fetch_assoc()) {
				$response["data"][] = $row;
				$response['status']=1;
			}
		}
		else{
			$response['errors'][]=$con->error;
		}
		return $response;
	}
    /* 
    input params - 
    	"function" => "UpdateSections" - function to insert/update the data of a renter 
    	"fields_data" => $_POST,
    	"section_id" => $section_id
    output params - 
    	$data = array(
    		"status" => 0,
    		"errors" => array(),
    		"msg" => '',
    		"data"=>array()
    	);
    */
    function UpdateSections($inputdata){
    	// print_r($inputdata);die;
    	global $con;
    	$response = array(
    		"status" => 0,
    		"errors" => array(),
    		"msg" => '',
    		"data" => array()
    	);
    	$validations=array(
    		"section_name"=>[
    			"validate"=>"required",
    			"label"=>"Section Name"
    		],
    		"product_range"=>[
    			"validate"=>"required",
    			"label"=>"Product Range"
    		]
    	);
    	$PSValidationEngine = new PSValidationEngine($inputdata['fields_data'], $validations,true);
    	$response['errors']=$PSValidationEngine->validate();
    	// check if selected product_range_id is same as old one 
    	$enable_check=1;
    	if(!empty($inputdata['section_id'])){
    		// means update 
    		// get old product_range_id
    		$Admin = new Admin();
    		$section_details = $Admin->GetSectionDetails(array(
    				"section_id" => $inputdata['section_id']
    		));
    		$product_range_id = 0;
    		if(!empty($section_details['data']['product_range_id'])){
    			$product_range_id = $section_details['data']['product_range_id'];
    			if($product_range_id==$inputdata['fields_data']['product_range']){
    				$enable_check=0;
    			}
    		}
    	}
		// get selected product range details
		$Admin = new Admin();
		$product_range_details = $Admin->GetProductRangeDetails(array(
				"product_range_id" => $inputdata['fields_data']['product_range']
		));
    	if($enable_check==1 && !empty($inputdata['fields_data']['product_range'])){
    		$is_enabled = 0;
    		if(!empty($product_range_details['data'])){
    			$is_enabled = $product_range_details['data']['is_enabled'];
    		}
    		if($is_enabled==0){
    			$response['errors'][] = "Selected Product Range is disabled.";
    		}
    	}
    	// also check if section already exists within same product range 
		$where="";
		if(!empty($inputdata['section_id'])){
			$where=" and section_id!='".safe_str($inputdata['section_id'])."'";
		}
		$sql="select * from sl_sections_info where section_name='".safe_str($inputdata['fields_data']['section_name'])."' and product_range_id='".safe_str($inputdata['fields_data']['product_range'])."' and delete_flag=0".$where;
		$res=$con->query($sql);
		if($res->num_rows){
			$response['errors'][] = "Section name for same Product Range already exists.";
		}
		// also check if custom length to update and max length price to update
		$update_price_per_cut=0;
		$update_max_length_price=0;
		if(!empty($product_range_details['data'])){
			$is_custom_length_allowed = $product_range_details['data']['is_custom_length_allowed'];
			$product_range_type = $product_range_details['data']['product_range_type'];
			if($is_custom_length_allowed==1){
				$update_price_per_cut=1;
				// check for validation 
				if(!is_numeric($inputdata['fields_data']['price_per_cut'])){
				    $response['errors'][] = "Enter a numeric value for Price per cut";
				}elseif($inputdata['fields_data']['price_per_cut']<0){
				    $response['errors'][] = "Price per cut must have minimum value 0";
				}
			}
			if($product_range_type==2){
				$update_max_length_price=1;
				// check for validation 
				if(!is_numeric($inputdata['fields_data']['max_length_price'])){
				    $response['errors'][] = "Enter a numeric value for 6000mm Length Price";
				}elseif($inputdata['fields_data']['max_length_price']<0){
				    $response['errors'][] = "6000mm Length Price must have minimum value 0";
				}
			}
		}
    	if(empty($response['errors'])){
    		$in_fields = $inputdata['fields_data'];
    		$is_enabled=0;
    		if(!empty($in_fields['is_enabled'])){
    			$is_enabled=1;
    		}
			$price_per_cut=0;
			$price_per_cut1="";
			if($update_price_per_cut==1){
				$price_per_cut=$in_fields['price_per_cut'];
				$price_per_cut1=" ,price_per_cut='".safe_str($in_fields['price_per_cut'])."'";
			}
			$max_length_price=0;
			$max_length_price1="";
			if($update_max_length_price==1){
				$max_length_price=$in_fields['max_length_price'];
				$max_length_price1=" ,max_length_price='".safe_str($in_fields['max_length_price'])."'";
			}
    		
    		if(empty($inputdata['section_id'])){
    			// insert 
    			$sql="insert into sl_sections_info(`product_range_id`, `section_name`, `is_enabled`, `created_on`,`price_per_cut`,`max_length_price`) values('".safe_str($in_fields['product_range'])."','".safe_str($in_fields['section_name'])."','".$is_enabled."','".date('Y-m-d H:i:s')."','".safe_str($price_per_cut)."','".safe_str($max_length_price)."')";
    			$insert=$con->query($sql);
    			if($insert){
    				// ok 
    			}else{
    				$response['errors'][] = $con->error;
    			}
    		}else{
    			// update 
    			$sql="Update `sl_sections_info` set  
    				`product_range_id`='".safe_str($in_fields['product_range'])."',
    				`section_name`='".safe_str($in_fields['section_name'])."',
    				`is_enabled`='".$is_enabled."',
    				`modified_on`='".date('Y-m-d H:i:s')."'
					".$price_per_cut1."
					".$max_length_price1."
    				where `section_id`='".safe_str($inputdata['section_id'])."'";
    			$update=$con->query($sql);
    			if($update){
    				// ok
    			}else{
    				$response['errors'][] = $con->error;
    			}
    		}
    		if(empty($response['errors'])){
    			$response["status"] = 1;
    			if(empty($inputdata['section_id'])){
    				$response["msg"] = "Section added successfully.";
    			}else{
    				$response["msg"] = "Section updated successfully.";
    			}
    		}
    		
    	}
    	return $response;
    }

    /* 
    input params - 
    	"function" => "ListProductRanges" - function to get the list of ProductRanges
    	"page_no" => $request["PageNumber"],
    	"row_size" => $request["RowSize"],
    	"sort_on" => $request["SortOn"],
    	"sort_type" => $request["SortType"],
    	"search" => $search
    output params - 
    	$response = array(
    		"status" => 0,
    		"errors" => array(),
    		"msg" => '',
    		"data" => array()
    	);
    */
    function ListProductRanges($inputdata){
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
    	$where1="";
    	
    	if(!empty($inputdata['search'])){
			$where = " and s.product_range_name like '%".safe_str($inputdata['search'])."%'";
			$where1 = " where s.product_range_name like '%".safe_str($inputdata['search'])."%'";
		}
		if($inputdata['product_range_type']!=3){
			if(!empty($inputdata['product_range_type'])){
				$where .= " and s.product_range_type = '".safe_str($inputdata['product_range_type'])."'";
				$where1 .= " where s.product_range_type = '".safe_str($inputdata['product_range_type'])."'";
			}
			if(!empty($inputdata['search']) && !empty($inputdata['product_range_type'])){
				$where = " and s.product_range_name like '%".safe_str($inputdata['search'])."%' and s.product_range_type = '".safe_str($inputdata['product_range_type'])."'";
				$where1 = " where s.product_range_name like '%".safe_str($inputdata['search'])."%' and s.product_range_type = '".safe_str($inputdata['product_range_type'])."'";
			}
		}
    	$pcount_qry = "select count(*) as total_count from `sl_product_ranges_info` s where s.delete_flag=0".$where;
    	//echo $pcount_qry;
    	
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
    	$get_det_query="select * from sl_product_ranges_info s".$where1." order by $sort_on $sort_type LIMIT $row_size OFFSET ".($page_no-1)*$row_size;
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
		"function" => "/GetProductRangeDetails" - function to get the details of a product range
		"product_range_id" => $product_range_id
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function GetProductRangeDetails($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
		$sql='select * from sl_product_ranges_info where delete_flag=0 and product_range_id="'.safe_str($inputdata['product_range_id']).'"';
	
		$result=$con->query($sql);
		if($result->num_rows){
			$response['data'] = $result->fetch_assoc();
			$response['status']=1;
		}else{
			$response['errors'][]=$con->error;
		}
		return $response;
	}

	/* 
	input params - 
		"function" => "SaveProductRange" - function to save ProductRange to database 
		"ProductRangeData" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => ''
		);
	*/
	function SaveProductRange($inputdata){
        global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array()
		);
		
		if(!empty($inputdata)){
			$product_range_name = safe_str($inputdata['product_range_name']);
			$product_range_type = safe_str($inputdata['product_range_type']);
			$is_enabled = safe_str($inputdata['is_enabled']);
			$is_custom_length_allowed = safe_str($inputdata['is_custom_length_allowed']);
			
			if($product_range_type==2){
			    $is_custom_length_allowed = 1;
			}
		
			$validations=array(
				"product_range_name"=>[
					"validate"=>"required",
					"label"=>"Product Range Name"
				],
				"product_range_type"=>[
					"validate"=>"required",
					"label"=>"Product Range Type"
				]
			);

			$PSValidationEngine = new PSValidationEngine($inputdata, $validations,true);
			$response['errors']=$PSValidationEngine->validate();
			if(empty($response['errors'])){
				$count = 0;
				
				$condition = "";
                if(!empty($inputdata['product_range_id'])){
				    $condition = " and product_range_id<>".$inputdata['product_range_id'];
			    }
			    
				$sql_count="SELECT count(*) as total_count  FROM `sl_product_ranges_info` WHERE product_range_name='".$product_range_name."' AND product_range_type=".$product_range_type.$condition;

				$res = $con->query($sql_count);

				if ($res->num_rows > 0) {
						while($row = $res->fetch_assoc()) {
							$count = $row["total_count"];
						}
				}
				
                if($count==0){
    				if(empty($inputdata['product_range_id'])){
    					$query = "INSERT INTO sl_product_ranges_info(product_range_name, product_range_type, is_custom_length_allowed, is_enabled, created_on, delete_flag) VALUES ('".$product_range_name."',".$product_range_type.",".$is_custom_length_allowed.",".$is_enabled.",'".date('Y-m-d H:i:s')."',0)";
    				}
    				else{
    					$query = "UPDATE sl_product_ranges_info SET product_range_id=".safe_str($inputdata['product_range_id']).",product_range_name='".$product_range_name."',product_range_type=".$product_range_type.",is_custom_length_allowed=".$is_custom_length_allowed.",is_enabled=".$is_enabled.",modified_on='".date('Y-m-d H:i:s')."',delete_flag=0 WHERE product_range_id=".safe_str($inputdata['product_range_id']);
    				}
    				$insert_product_ranges = $con->query($query);
    				if(empty($response['errors'])){
        				if($insert_product_ranges){
        					$response['status']=1;
        					$response['msg']="Saved Successfully";
        				}
        				else{
        					$response['status']=0;
        					$response['errors'][]=$sql_count;
        				}
    				}
                }
                else{
					$response['status']=0;
					$response['errors'][]="Product Range already exists.";
				}
			}
			
		}
		return $response;
	}
    /*
	input - 
		"function"=>"GetProductRangesList",
		"product_range_id"=>$product_range_id
	output - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function GetProductRangesList($inputdata){
		global $con;
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
		$where="";
		if(!empty($inputdata['product_range_id'])){
			$where=" or (product_range_id='".safe_str($inputdata['product_range_id'])." and delete_flag=0')";
		}
		$query = "SELECT * FROM sl_product_ranges_info WHERE is_enabled=1 AND `delete_flag`=0 ".$where." ORDER BY product_range_name";
		$result = $con->query($query);
		$i=0;
		while($row = $result->fetch_assoc()){
			$data['data'][$i] = $row;
			$i++;
		}
		return $data;
	}
	
    /* 
	input params - 
		"function" => "GetSectionDetails" - function to get the details of a section 
		"section_id" => $section_id
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function GetSectionDetails($inputdata){
		global $con;
		$response = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
		$sql='select * from sl_sections_info s where s.delete_flag=0 and s.section_id="'.safe_str($inputdata['section_id']).'"';
		$result=$con->query($sql);
		if($result->num_rows){
			$response['data'] = $result->fetch_assoc();
			$response['status']=1;
		}else{
			$response['msg'] = "Failed to get details";
		}
		return $response;
	}
	
	/* 
	input params - 
		"function" => "ListSections" - function to get the list of sections 
		"page_no" => $request["PageNumber"],
		"row_size" => $request["RowSize"],
		"sort_on" => $request["SortOn"],
		"sort_type" => $request["SortType"],
		"search" => $search,
		"product_range" => $product_range,
		"product_range_type" => $product_range_type
	output params - 
		$response = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function ListSections($inputdata){
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
			$where = " and section_name like '%".safe_str($inputdata['search'])."%'";
		}
        if(!empty($inputdata['product_range'])){
			$where .= " and s.product_range_id = '".safe_str($inputdata['product_range'])."'";
		}
		if(!empty($inputdata['product_range_type'])){
			$where .= " and p.product_range_type = '".safe_str($inputdata['product_range_type'])."'";
		}
		$pcount_qry = "select count(*) as total_count from `sl_sections_info` s left join sl_product_ranges_info p on p.product_range_id=s.product_range_id and p.delete_flag=0 where s.delete_flag=0".$where;
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
		$get_det_query="select s.*,p.product_range_name,p.product_range_type from sl_sections_info s left join sl_product_ranges_info p on p.product_range_id=s.product_range_id and p.delete_flag=0 where s.delete_flag=0 ".$where." order by $sort_on $sort_type LIMIT $row_size OFFSET ".($page_no-1)*$row_size;
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
				$response["status"] = true;
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
		"function" => "getPostCodes" - function to get Post Codes 
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function getPostCodes(){
		global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array(),
			"data" => array()
		);
		$query = "select postcode from sl_delivery_postcodes_info";
		if($result = $con->query($query)){
			while($row = $result->fetch_assoc()) {
				$response["data"][] = $row;
			}
			$response['status'] = 1;
		}
		else{
			$response['status'] = 0;
			$response['errors'][] = $con->error;
		}
		return $response;
	}  
	
	
	/* 
	input params - 
		"function" => "PostCodes_Save" - function to save Post Codes to database
		"PostCodeData" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => ''
		);
	*/
	function PostCodes_Save($inputdata){
        global $con;
		$response = array(
			"status" => 0,
			"msg"=>'',
			"errors" => array()
		);
		$data=$inputdata['PostCodeData'];
		if(empty($response['errors'])){
    		if(!empty($inputdata['PostCodeData'])){
    			$truncate = "TRUNCATE TABLE sl_delivery_postcodes_info";
    			$res = $con->query($truncate);
    			if($res){
    				$values = "";
    				foreach($data as $postcode) {
    				    if(!empty($postcode)){
    					    $values.="('".$postcode."',1"."),";
    				    }
    				}
    				$values = substr($values,0,strlen($values)-1);
    				$insert_postcode_query = "INSERT INTO `sl_delivery_postcodes_info`(`postcode`, `is_enabled`) VALUES ".$values;
    				$insert_postcode = $con->query($insert_postcode_query);
    				if($insert_postcode){
    					$response['status']=1;
    					$response['msg']="Successfully saved";
    				}
    				else{
    					$response['status']=0;
    					$response['errors'][]=$con->error;
    				}
    			}
    			else{
    				$response['status']=0;
    				$response['errors'][]=$con->error;
    			}
    			
    		}else{
    		    $response['errors'][]="Please enter a Postcode";
    		}
		}
		return $response;
	}


	/* 
	input params - 
		"function" => "Admin_Login" - function to validate Admin Login 
		"loginData" => $_POST
	output params - 
		$data = array(
			"status" => 0,
			"errors" => array(),
			"msg" => '',
			"data" => array()
		);
	*/
	function Admin_Login($inputdata){
        global $con;
		$response = array(
			"status" => 0,
			"data" => array(),
			"msg"=>'',
			"errors" => array()
		);
        $password = safe_str($inputdata['loginData']['PASSWORD']);
        $email = safe_str($inputdata['loginData']['EMAIL']);
		$data=$inputdata['loginData'];
		$validations=array(
			"EMAIL"=>[
				"validate"=>"required,custom[email]",
				"label"=>"Email"
			],
			"PASSWORD"=>[
				"validate"=>"required",
				"label"=>"Password"
			]
		);
		$PSValidationEngine = new PSValidationEngine($inputdata['loginData'], $validations,true);
		$response['errors']=$PSValidationEngine->validate();
		if(!empty($password) && !empty($email) && empty($response['errors']))
		{
			$checkemail="Select * from sl_user_login_info where (user_type=1 or user_type=4) and email = '".$email."'";
			$checkQry = $con->query($checkemail);
			if($checkQry){
				if ($checkQry->num_rows) {
					$row = $checkQry->fetch_assoc();
					if($row['is_enabled']==1){
						if(strcmp($password,$row['password'])==0){
							$response['status']=1;
							$response['data']['user_id']=$row['user_type_id'];
							$response['data']['user_type']=$row['user_type'];
							$response['msg']="You are logged in";
						}
						else
						{
							$response['errors'][]="Incorrect email or password";
						}
					}
					else{
						$response['errors'][]="User is inactive";
					}
				}
				else{
					$response['errors'][] = "Incorrect email xyz or password";
				}
			}
			else{
				$response['errors'][] = 'Something went wrong. Please try again!';
			}
			
		}
		return $response;
	}
}
?>