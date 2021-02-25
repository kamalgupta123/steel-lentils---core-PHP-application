<?php
include_once(__DIR__ .'/header.php');
include_once(__DIR__ .'/admin-functions.php');

$Encryption = new Encryption();
?>
<div class="container-fluid">
	<div class="row head-row">
        <div class="col-md-12">
			<div class="heading-div ">  Admin Profile</div>
			<div class="row owner-row">
				 <div class="col-lg-12">
					<div class="card">
						<div class="card-body">
							<form id="AdminProfile" data-new-key="user_login_id" class="submit_ajax" data-action-url="<?php echo SITE_URL."/customers/contacts/contacts-ajax.php";?>" data-add-url="<?php echo SITE_URL."/customers/contacts/contacts-ajax.php/?referer=".rawurlencode($back_url); ?>" data-back-url="<?php echo $back_url; ?>" enctype="multipart/form-data" autocomplete="off">
									<input type="hidden" name="action" value="AdminProfile">
									<input type="hidden" name="admin_id" value="<?php if(!empty($_SESSION['sl_admin'])){ echo $Encryption->encode($_SESSION['sl_admin']['user_id']);} ?>">
								<div class="row ">
									<div class="form-group col-lg-4 col-md-6">
										<label for="first_name">First Name<span class="required_star"> *</span></label>
										<input type="text" class="form-control validate[required]" id="first_name" name="first_name" placeholder="Enter First Name" value="<?php if(!empty($user_details)){ echo $user_details['first_name']; }?>">
									</div>
									<div class="form-group col-lg-4 col-md-6">
										<label for="last_name">Last Name</label>
										<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" value="<?php if(!empty($user_details)){ echo $user_details['last_name']; }?>">
									</div>
								   <div class="form-group col-lg-4 col-md-6">
										<label for="user_email">Email<span class="required_star"> *</span></label>
										<input type="text" class="form-control validate[required,custom[email]]" id="user_email" name="user_email" placeholder="Enter Email" value="<?php if(!empty($user_details)){ echo $user_details['email']; }?>">
									</div>
								</div>
								<div class="change_pass_sec">
									<h4 class="mt-0">Change Password <small><i>(Leave blank if you do not want to reset Password)</i></small></h4>
									<div class="row">
											<div class="form-group col-lg-6 col-md-6">
												<label for="pass">Password</label>
												<input type="password" class="form-control " id="pass" name="pass" placeholder="Enter Password" >
											</div>
											<div class="form-group col-lg-6 col-md-6">
												<label for="c_pass">Confirm Password</label>
												<input type="password" class="form-control validate[equals[pass]]" id="c_pass" name="c_pass" placeholder="Enter Confirm Password" >
											</div>
									</div>
								</div>
								<button type="submit" class="btn save-btn theme-btn float-md-right save_btn_action" data-form-id="AdminProfile">Save Changes</button>
							</form>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>

<?php
include_once(__DIR__ .'/footer.php');
?>