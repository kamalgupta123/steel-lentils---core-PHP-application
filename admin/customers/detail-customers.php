<?php 
include_once(__DIR__ . "/../header.php");
include_once(ADMIN_DIR . "/admin-functions.php");

$error = 0;
$Encryption = new Encryption(); 
if(isset($_GET["user_login_id"])){
    $user_login_id = $Encryption->decode($_GET["user_login_id"]);
	$customer_address_id = $Encryption->decode($_GET["customer_address_id"]);
	$customer_id=0;
	if(!empty($_SESSION['sl_admin']['user_id'])){
        $customer_id = $_SESSION['sl_admin']['user_id'];
    }

    $result = send_rest(array(
        "function" => "Admin/GetCustomerDetails",
		"customer_id" => $user_login_id
    ));
    
    if(empty($result['status'])){
        $error = 1;
        echo '<div class="alert alert-danger" style="padding:10px;margin:10px">Failed to get details</div>';
    }
    else{
        $d_row = $result['data'];
	}
	
    $result1 = send_rest(array(
        "function" => "Admin/GetCustomerDetailsTwo",
		"customer_address_id" => $customer_address_id
    ));
    
    if(empty($result1['status'])){
        $error = 1;
        echo '<div class="alert alert-danger" style="padding:10px;margin:10px">Failed to get details</div>';
    }
    else{
        $d_row1 = $result1['data'];
	}
	
    $result2 = send_rest(array(
		"function" => "Admin/GetCustomerDetailsThree",
		"customer" => $customer_id
    )
);
    
    if(empty($result2['status'])){
        $error = 1;
        echo '<div class="alert alert-danger" style="padding:10px;margin:10px">Failed to get details</div>';
    }
    else{
        $d_row2 = $result2['data'];
    }
}
if($error==0){
?>

<script>
	$(document).ready(function(){
		$("#SaveCustomersForm").validationEngine({
			promptPosition: "topRight:-90"
		});
		var spinner = $( ".spinner" ).spinner();
	    $('.select2').select2();
	});
</script>

<div class="container-fluid">
    <div class="row head-row">
        <div class="col-md-12">
			<div class="heading-div ">
			    <div class="row">
			        <div class="col-4">
			            <div>Add Customer</div>
			        </div>
				</div>
            </div>
        </div> 
    </div>
    <div class="table-div mt-1">
        <div class="row">
            <div class="col-lg-12">
                <?php $back_url=""; if(isset($_GET['referer'])){ $back_url = $_GET['referer']; } ?>
                <form id="SaveCustomersForm" data-new-key="user_type_id" class="submit_ajax" data-action-url="<?php echo ADMIN_URL."/customers/customers-ajax.php";?>" data-add-url="<?php echo ADMIN_URL."/customers/detail-customers.php/?referer=".rawurlencode($back_url); ?>" data-back-url="<?php echo $back_url; ?>" enctype="multipart/form-data" autocomplete="off">
                    <div class="row ">
                        <div class="form-group col-lg-4 col-md-6 save-col">
							<input type="hidden" name="action" value="SaveCustomers">
							<input type="hidden" value="<?php if(!empty($_GET["user_login_id"])){echo $_GET["user_login_id"];} ?>" name="user_login_id">    
							<input type="hidden" value="<?php if(!empty($_GET["customer_address_id"])){echo $_GET["customer_address_id"];} ?>" name="customer_address_id">    
							<label for="first_name">First Name<span class="required_star">*</span></label>
                            <input type="text" class="form-control validate[required]" id="first_name" name="first_name" value='<?php if(!empty($d_row)){ echo $d_row['first_name']; }?>' placeholder="Enter First Name">                    
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="last_name">Last Name<span class="required_star">*</span></label>
                            <input type="text" class="form-control validate[required]" id="last_name" name="last_name" value='<?php if(!empty($d_row)){ echo $d_row['last_name']; }?>' placeholder="Enter Last Name">
                        </div>
                        <div class="form-group col-lg-4 col-md-6 ">
							<label for="email">Email<span class="required_star">*</span></label>
                            <input type="text" class="form-control validate[required] custom[email]" id="email" name="email" value='<?php if(!empty($d_row)){ echo $d_row['email']; }?>' placeholder="Enter Email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control validate[required] custom[number]" id="phone" name="phone" value='<?php if(!empty($d_row)){ echo $d_row['phone']; }?>' placeholder="Enter Phone">
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="buisness_name">Buisness Name<span class="required_star"></span></label><br>
                            <input type="text" class="form-control validate[required]" id="buisness_name" name="buisness_name" value='<?php if(!empty($d_row2)){ echo $d_row2['business_name']; }?>' placeholder="Enter Buisness Name">
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="street_address_one">Street Address One</label><br>
                            <input type="text" class="form-control validate[required]" id="street_address_one" name="street_address_one" value='<?php if(!empty($d_row1)){ echo $d_row1['street_address1']; }?>' placeholder="Enter Street Address One">
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="street_address_two">Street Address Two</label><br>
                            <input type="text" class="form-control validate[required]" id="street_address_two" name="street_address_two" value='<?php if(!empty($d_row1)){ echo $d_row1['street_address2']; }?>' placeholder="Enter Street Address Two">
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="city">City</label><br>
                            <input type="text" class="form-control validate[required]" id="city" name="city" value='<?php if(!empty($d_row1)){ echo $d_row1['city']; }?>' placeholder="Enter City">
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="state">State</label><br>
                            <input type="text" class="form-control validate[required]" id="state" name="state" value='<?php if(!empty($d_row1)){ echo $d_row1['state']; }?>' placeholder="Enter State">
                        </div>
                    </div>          
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-6">
                                <label for="password">Password</label><br>
                                <input type="text" class="form-control validate[required] minSize[6]" id="password" name="password" value='<?php if(!empty($d_row)){ echo $d_row['password']; }?>' placeholder="Enter Password">
                        </div>
					    <div class="form-group col-lg-4 col-md-6">
                            <label for="zip">Postcode</label><br>
                            <input type="text" class="form-control validate[required] custom[integer]" id="zip" name="zip" value='<?php if(!empty($d_row1)){ echo $d_row1['zip']; }?>' placeholder="Enter Postcode">
                        </div>toggleWrapperedit Limit</label>
							<div class="price-star">
                            <input type="text" class="form-control validate[required] custom[integer]" id="credit_limit" name="credit_limit" value="" disabled>
							<i class="fas fa-dollar-sign"></i>
							</div>
                        </div>
						<div class="form-group col-lg-4 col-md-6">
                            <label for="payment_terms">Payment Terms</label>
                            <select name="payment_terms" class="form-control validate[required] payment_terms" id="" disabled>
								<option value="">Choose:</option>
								<option value="1">pay upfront online</option>
								<option value="2">preset credit limit</option>
							</select>
                        </div>
						<div class="form-group col-lg-4 col-md-6">
                            <label for="created_on">Created On</label>
                            <input type="text" class="form-control" id="created_on" name="created_on" value="<?php if(!empty($d_row)){ echo $d_row['created_on']; }?>" disabled>
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="modified_on">Modified On</label>
                            <input type="text" class="form-control" id="modified_on" name="modified_on" value="<?php if(!empty($d_row)){ echo $d_row['modified_on']; }?>" disabled>
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                             <div class="toggleWrapper">
                                <input type="checkbox" name="is_enabled" class="mobileToggle show_msg_by_toggle" id="is_enabled"  value="1" <?php if(!empty($d_row) && $d_row['is_enabled']==0){ } else echo"checked";?>>
                                <label for="is_enabled"><span id="show_label" class="secondary-label"><?php if(!empty($d_row) && $d_row['is_enabled']==0){ echo "Click to Enable";} else echo "Click to Disable";?></span></label>
								<input type="checkbox" name="is_enabled" class="mobileToggle show_msg_by_toggle toggleCredit" id="is_enabled2"  value="1">
                                <label for="is_enabled2"><span id="show_label" class="secondary-label">Apply for credit account</span></label>
								<input type="checkbox" name="is_enabled" class="mobileToggle show_msg_by_toggle toggleCredit" id="is_enabled3"  value="1">
                                <label for="is_enabled3"><span id="show_label" class="secondary-label">Put Account on hold</span></label>
							</div>
                        </div>
                    </div>
                    <button type="submit" class="btn theme-btn float-md-right save_btn_action" data-form-id="SaveCustomersForm">Save Changes</button>
                    <button type="button" class="cancel_btn_action cancel-btn btn btn-default">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    <div class="copyright-text mt-4">Copyrights 2020, Dowcon</div>
</div>
<?php
}
include_once(ADMIN_DIR . "/footer.php");
?>