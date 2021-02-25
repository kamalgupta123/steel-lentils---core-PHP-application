<?php 
include_once(__DIR__ ."/../../header.php");
$error=0;
$Encryption = new Encryption();
if(empty($_SESSION['sl_user']['user_id'])){
    header('Location: '.SITE_URL."/login.php");
}
if(isset($_GET["customer_address_id"])){
    $customer_address_id = $Encryption->decode($_GET["customer_address_id"]);
    $result = send_rest(array(
        "function" => "Customers/GetAddressDetails",
        "customer_address_id" => $customer_address_id
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
	<div class="row">
		<div class="col-6">
			<h4 class="order-head"><?php if(!empty($d_row)){ echo 'Edit ';}else{ echo "Add ";}?>Address</h4>
		</div>
	</div>
	<form id="SaveAddressForm" class="address-form mt-3"  data-new-key="customer_address_id" class="submit_ajax" data-action-url="<?php echo SITE_URL."/customers/addresses/addresses-ajax.php";?>" data-add-url="<?php echo SITE_URL."/customers/addresses/detail-addresses.php/?referer=".rawurlencode($back_url); ?>" data-back-url="<?php echo $back_url; ?>" enctype="multipart/form-data" autocomplete="off">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<input type="hidden" name="action" value="SaveAddress">
					<input type="hidden" value="<?php if(!empty($_GET["customer_address_id"])){echo $_GET["customer_address_id"];} ?>" name="customer_address_id">
					<label for="exampleFormControlInput1">Street Address 1</label>
					<input type="text" class="form-control validate[required]" id="street-address-one" value='<?php if(!empty($d_row)){ echo $d_row['street_address1']; }?>' name="street_address_one" placeholder="Street Address 1">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput1">Street Address 2</label>
					<input type="text" class="form-control validate[required]" id="street-address-two" value='<?php if(!empty($d_row)){ echo $d_row['street_address2']; }?>' name="street_address_two" placeholder="Street Address 2">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput1">City</label>
					<input type="text" class="form-control validate[required]" id="exampleFormControlInput1" value='<?php if(!empty($d_row)){ echo $d_row['city']; }?>' name="city" placeholder="Email Address">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="inputState">State</label>
					<select id="inputState" name="state" class="form-control validate[required]">
						<option value=''>Select State</option>
						<option value="ACT" <?php if(!empty($d_row) && $d_row['state']=='ACT'){ echo "selected";}else{}?>>ACT</option>
						<option value="NSW" <?php if(!empty($d_row) && $d_row['state']=='NSW'){ echo "selected";}else{}?>>NSW</option>
						<option value="NT" <?php if(!empty($d_row) && $d_row['state']=='NT'){ echo "selected";}else{}?>>NT</option>
						<option value="QLD" <?php if(!empty($d_row) && $d_row['state']=='QLD'){ echo "selected";}else{}?>>QLD</option>
						<option value="SA" <?php if(!empty($d_row) && $d_row['state']=='SA'){ echo "selected";}else{}?>>SA</option>
						<option value="TAS" <?php if(!empty($d_row) && $d_row['state']=='TAS'){ echo "selected";}else{}?>>TAS</option>
						<option value="VIC" <?php if(!empty($d_row) && $d_row['state']=='VIC'){ echo "selected";}else{}?>>VIC</option>
						<option value="WA" <?php if(!empty($d_row) && $d_row['state']=='WA'){ echo "selected";}else{}?>>WA</option>
					</select>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="exampleFormControlInput1">Zip</label>
					<input type="text" class="form-control validate[required] custom[integer] minSize[6]" value='<?php if(!empty($d_row)){ echo $d_row['zip']; }?>' name="zip" id="exampleFormControlInput1" placeholder="Zip">
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
			<div class="col-md-4">
				<div class="form-check">
					<div class="toggleWrapper">
						<input type="checkbox" name="is_buisness_address" class="mobileToggle show_msg_by_toggle" id="is_buisness_address"  value="1" <?php if(!empty($d_row) && $d_row['is_buisness_address']==0){ } else echo"checked";?>>
						<label for="is_buisness_address"><span id="show_label" class="secondary-label"><?php if(!empty($d_row) && $d_row['is_buisness_address']==0){ echo "It's Buisness Address";} else echo "Not Buisness Address";?></span></label>
					</div>
				</div>
			</div>
			<div class="col-12"> <div class="mt-4"> <button class="btn theme-btn save_btn_action save-address-form" data-form-id="SaveAddressForm">Save Changes</button></div></div>
		</div>
	</form>
</div>
<?php 
}
include_once(__DIR__ ."/../../footer.php");
?>