<?php 
include_once(__DIR__ ."/../../header.php");
$error=0;
$Encryption = new Encryption();
if(empty($_SESSION['sl_user']['user_id'])){
    header('Location: '.SITE_URL."/login.php");
}
if(isset($_GET["customer_contact_id"])){
    $customer_contact_id = $Encryption->decode($_GET["customer_contact_id"]);
    $result = send_rest(array(
        "function" => "Customers/GetContactDetails",
        "customer_contact_id" => $customer_contact_id
    ));
    if(empty($result['status'])){
        $error = 1;
        echo '<div class="alert alert-danger" style="padding:10px;margin:10px">Failed to get details</div>';
    }
    else{
        $d_row = $result['data'];
	}
}
if($error==0){
?>
<script>
	$(document).ready(function(){
		$("#SaveAddressForm").validationEngine({
			promptPosition: "topRight:-90"
		});
		var spinner = $( ".spinner" ).spinner();
	});
</script>
<div class="col-lg-9">
<?php $back_url=""; if(isset($_GET['referer'])){ $back_url = $_GET['referer']; } ?>
	<div class="row">
		<div class="col-6">
			<h4 class="order-head"><?php if(!empty($d_row)){ echo 'Edit ';}else{ echo "Add ";}?>Address</h4>
		</div>	
	</div>
	<form id="SaveContactsForm" class="address-form mt-3"  data-new-key="customer_contact_id" class="submit_ajax" data-action-url="<?php echo SITE_URL."/customers/contacts/contacts-ajax.php";?>" data-add-url="<?php echo SITE_URL."/customers/contacts/contacts-ajax.php/?referer=".rawurlencode($back_url); ?>" data-back-url="<?php echo $back_url; ?>" enctype="multipart/form-data" autocomplete="off">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<input type="hidden" name="action" value="SaveContacts">
					<input type="hidden" value="<?php if(!empty($_GET["customer_contact_id"])){echo $_GET["customer_contact_id"];} ?>" name="customer_contact_id">
					<label for="exampleFormControlInput1">First name</label>
					<input type="text" class="form-control validate[required]" id="first-name" value='<?php if(!empty($d_row)){ echo $d_row['first_name']; }?>' name="first_name" placeholder="First Name">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput1">Last Name</label>
					<input type="text" class="form-control validate[required]" id="last-name" value='<?php if(!empty($d_row)){ echo $d_row['last_name']; }?>' name="last_name" placeholder="Last Name">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput1">Phone</label>
					<input type="text" class="form-control validate[required]" id="exampleFormControlInput1" value='<?php if(!empty($d_row)){ echo $d_row['phone']; }?>' name="phone" placeholder="Phone">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput1">Alternate Phone</label>
					<input type="text" class="form-control validate[required]" id="exampleFormControlInput1" value='<?php if(!empty($d_row)){ echo $d_row['alternate_phone']; }?>' name="alternate_phone" placeholder="Alternate Phone">
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="exampleFormControlInput1">Email</label>
					<input type="text" class="form-control validate[required]" value='<?php if(!empty($d_row)){ echo $d_row['email']; }?>' name="email" id="exampleFormControlInput1" placeholder="Email">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput1">Created on </label>
					<input type="text" class="form-control validate[required]" id="exampleFormControlInput1" value='<?php if(!empty($d_row)){ echo $d_row['created_on']; }?>' name="created_on" placeholder="Created on" disabled>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput1">Modified on</label>
					<input type="text" class="form-control validate[required]" id="exampleFormControlInput1" value='<?php if(!empty($d_row)){ echo $d_row['modified_on']; }?>' name="modified_on" placeholder="Modified on" disabled>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-check">
					<div class="toggleWrapper">
						<input type="checkbox" name="is_enabled" class="mobileToggle show_msg_by_toggle" id="is_enabled"  value="1" <?php if(!empty($d_row) && $d_row['is_enabled']==0){ } else echo"checked";?>>
						<label for="is_enabled"><span id="show_label" class="secondary-label"><?php if(!empty($d_row) && $d_row['is_enabled']==0){ echo "Click to Enable";} else echo "Click to Disable";?></span></label>
					</div>
				</div>
			</div>
			<div class="col-12"> <div class="mt-4"> <button class="btn theme-btn save_btn_action save-address-form" data-form-id="SaveContactsForm">Save Changes</button><button type="button" class="cancel_btn_action cancel-btn detail-cancel btn btn-default">Cancel</button></div></div>
		</div>
	</form>
</div>
<?php 
}
include_once(__DIR__ ."/../../footer.php");
?>