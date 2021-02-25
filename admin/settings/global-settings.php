<?php
include_once(__DIR__ .'/../header.php');
include_once(ADMIN_DIR .'/admin-functions.php');

//print_r($user_details);
$global_settings = send_rest(array(
     "function"=>"Admin/GetGlobalSettings"   
));
$details = $global_settings['data'];
?>
<script>
$(document).ready(function(){
	$("#AdminGlobalSettings").validationEngine({
		promptPosition: "topRight:-90"
	});
});
</script>
<div class="container-fluid">
	<div class="row head-row">
        <div class="col-md-12">
			<div class="heading-div ">  Global Settings</div>
			<div class="row owner-row">
				 <div class="col-lg-12">
					<div class="card">
						<div class="card-body">
							<form id="AdminGlobalSettings" class="submit_ajax " data-reload="true" data-action-url="<?php echo ADMIN_URL."/settings/settings-ajax.php"; ?>" data-back-url="" enctype="multipart/form-data" autocomplete="off">
								<input type="hidden" name="SaveGlobalSettings" value="action">
								<div class="row ">
									<div class="form-group col-lg-4 col-md-6">
										<label for="email">Admin Contact Email Address<span class="required_star"> *</span></label>
										<input type="text" class="form-control validate[required,custom[email]]" id="email" name="MetaKeyToEdit[1]" placeholder="Enter Admin Contact Email Address" value="<?php if(!empty($details[1])){ echo $details[1]['meta_value']; }?>">
									</div>
									<div class="form-group col-lg-4 col-md-6">
										<label for="gst">GST (%)<span class="required_star"> *</span></label>
										<input type="text" class="form-control validate[required,custom[number],min[0]]" id="gst" name="MetaKeyToEdit[2]" placeholder="Enter GST" value="<?php if(!empty($details[2])){ echo $details[2]['meta_value']; }?>">
									</div>
								   <div class="form-group col-lg-4 col-md-6">
										<label for="from_email">From Email Address<span class="required_star"> *</span></label>
										<input type="text" class="form-control validate[required,custom[email]]" id="from_email" name="MetaKeyToEdit[3]" placeholder="Enter From Email Address" value="<?php if(!empty($details[3])){ echo $details[3]['meta_value']; }?>">
									</div>
								</div>
								<div class="row ">
									<div class="form-group col-lg-4 col-md-6">
										<label for="from_name">From Name<span class="required_star"> *</span></label>
										<input type="text" class="form-control validate[required]" id="from_name" name="MetaKeyToEdit[4]" placeholder="Enter From Name" value="<?php if(!empty($details[4])){ echo $details[4]['meta_value']; }?>">
									</div>
								</div>
								<h4 class="mt-0">Website Contact Information</h4>
								<div class='row'>
								    <div class="form-group col-lg-4 col-md-6">
										<label for="address">Address<span class="required_star"> *</span></label>
										<input type="text" class="form-control validate[required]" id="address" name="MetaKeyToEdit[5]" placeholder="Enter Address" value="<?php if(!empty($details[5])){ echo $details[5]['meta_value']; }?>">
									</div>
									<div class="form-group col-lg-4 col-md-6">
										<label for="website_link">Website Link<span class="required_star"> *</span></label>
										<input type="text" class="form-control validate[required] custom[url]" id="website_link" name="MetaKeyToEdit[6]" placeholder="Enter Website Link" value="<?php if(!empty($details[6])){ echo $details[6]['meta_value']; }?>">
									</div>
									<div class="form-group col-lg-4 col-md-6">
										<label for="contact_number_one">Contact Number 1<span class="required_star"> *</span></label>
										<input type="text" class="form-control validate[required] custom[phone] minSize[10]" id="contact_number_one" name="MetaKeyToEdit[7]" placeholder="Enter Contact Number" value="<?php if(!empty($details[7])){ echo $details[7]['meta_value']; }?>">
									</div>
									<div class="form-group col-lg-4 col-md-6">
										<label for="email">Email<span class="required_star"> *</span></label>
										<input type="text" class="form-control validate[required] custom[email]" id="email" name="MetaKeyToEdit[8]" placeholder="Enter the Email" value="<?php if(!empty($details[8])){ echo $details[8]['meta_value']; }?>">
									</div>
									<div class="form-group col-lg-4 col-md-6">
										<label for="contact_number_two">Contact Number 2<span class="required_star"> *</span></label>
										<input type="text" class="form-control validate[required] custom[phone] minSize[10]" id="contact_number_two" name="MetaKeyToEdit[9]" placeholder="Enter the Contact Number" value="<?php if(!empty($details[9])){ echo $details[9]['meta_value']; }?>">
									</div>
								</div>
								<button type="submit" class="btn save_btn_action theme-btn float-md-right" data-form-id="AdminGlobalSettings">Save Changes</button>
								
							</form>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>

<?php
include_once(__DIR__ .'/../footer.php');
?>