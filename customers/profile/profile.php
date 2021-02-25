<?php 
include_once(__DIR__ ."/../../header.php");
?>
<script>
	$(document).ready(function(){
		$("#SaveItemsForm").validationEngine({
			promptPosition: "topRight:-90"
		});
		var spinner = $( ".spinner" ).spinner();
	    $('.select2').select2();
	});
</script>
<div class="col-lg-9">
<?php $back_url=""; if(isset($_GET['referer'])){ $back_url = $_GET['referer']; } ?>
	<div class="row">
		<div class="col-6">
			<h4 class="order-head">Profile</h4>
		</div>
	</div>
	<form id="SaveProfileForm" class="address-form mt-3" data-new-key="customer_contact_id" data-action-url="<?php echo SITE_URL."/customers/profile/profile-ajax.php";?>" data-add-url="<?php echo SITE_URL."/customers/profile/profile-ajax.php/?referer=".rawurlencode($back_url); ?>" data-back-url="<?php echo $back_url; ?>" enctype="multipart/form-data" autocomplete="off">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<input type="hidden" name="action" value="SaveProfile">
					<label for="exampleFormControlInput1">First Name*</label>
					<input type="text" class="form-control validate[required]" id="exampleFormControlInput1" name="first_name" placeholder="First Name">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput2">Last Name*</label>
					<input type="text" class="form-control validate[required]" id="exampleFormControlInput2" name="last_name" placeholder="Last Name">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput3">Email Address*</label>
					<input type="text" class="form-control validate[required] custom[email]" id="exampleFormControlInput3" name="email" placeholder="Email Address">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput4">Phone*</label>
					<input type="text" class="form-control validate[required] custom[integer]" id="exampleFormControlInput4" name="phone" placeholder="Phone">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput5">Password*</label>
					<input type="password" class="form-control validate[required] minSize[6]" id="exampleFormControlInput5" name="password" placeholder="Password">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput6">Business Name</label>
					<input type="text" class="form-control validate[required]" id="exampleFormControlInput6" name="business_name" placeholder="Business Name">
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="exampleFormControlInput7">Business Address</label>
					<input type="text" class="form-control validate[required]" id="exampleFormControlInput7" name="business_address"  placeholder="Business Address">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput8">ABN</label>
					<input type="text" class="form-control validate[required]" id="exampleFormControlInput8" name="abn"  placeholder="ABN">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="exampleFormControlInput9">ACN</label>
					<input type="text" class="form-control validate[required]" id="exampleFormControlInput9" name="acn"  placeholder="ACN">
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-check">
					<input type="checkbox" class="form-check-input " value="1" name="credit_account" id="exampleCheck1">
					<label class="form-check-label" for="exampleCheck1">Apply for Credit Account </label>
				</div>
			</div>
			<div class="col-md-12 mt-3 checkbox-col">
				<label class="form-check-label notify-label mb-2" for="defaultCheck1">
				How to Notify?
				</label>
				<div class="form-check">
					<input class="form-check-input" type="checkbox" value="1" id="defaultCheck1" name="notify">
					<label class="form-check-label" for="defaultCheck1">
					Email
					</label>
				</div>
				<div class="form-check mt-2">
					<input class="form-check-input" type="checkbox" value="1" id="defaultCheck1" name="sms">
					<label class="form-check-label" for="defaultCheck1">
					SMS
					</label>
				</div>
				<div class="form-check mt-2">
					<input class="form-check-input" type="checkbox" value="1" id="defaultCheck1" name="email_sms">
					<label class="form-check-label" for="defaultCheck1">
					Email & SMS
					</label>
				</div>
			</div>
			<div class="col-md-12 mt-3 checkbox-col">
				<label class="form-check-label notify-label mb-2" for="defaultCheck1">
				When to Notify?
				</label>
				<div class="form-check">
					<input class="form-check-input" type="checkbox" value="1" name="order" id="defaultCheck1">
					<label class="form-check-label" for="defaultCheck1">
					Order assigned by admin
					</label>
				</div>
				<div class="form-check mt-2">
					<input class="form-check-input" type="checkbox" value="1" id="defaultCheck1" name="cutting">
					<label class="form-check-label" for="defaultCheck1">
					Cutting complete
					</label>
				</div>
				<div class="form-check mt-2">
					<input class="form-check-input" type="checkbox" value="1" id="defaultCheck1" name="packing">
					<label class="form-check-label" for="defaultCheck1">
					Packing complete
					</label>
				</div>
				<div class="form-check mt-2">
					<input class="form-check-input" type="checkbox" value="1" id="defaultCheck1" name="loading">
					<label class="form-check-label" for="defaultCheck1">
					Loading complete
					</label>
				</div>
				<div class="form-check mt-2">
					<input class="form-check-input" type="checkbox" value="1" id="defaultCheck1" name="delivered">
					<label class="form-check-label" for="defaultCheck1">
					Delivered
					</label>
				</div>
			</div>
			<div class="col-md-12">
				<div class="mt-4"> <button class="btn theme-btn save_btn_action" data-form-id="SaveProfileForm">Save Changes</button></div>
			</div>
		</div>
	</form>
</div>
<?php 
include_once(__DIR__ ."/../../footer.php");
?>