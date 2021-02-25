<?php 
include_once(__DIR__ .'/common_header.php');
?>
<script>
$(document).ready(function(){
	$("#SignUpForm").validationEngine({
		promptPosition: "topRight:-90"
	});
});
</script>
<section class="section">
	<div class="login-background sign-up-background">
		<div class="bg-cover">
			<div class="container">
				<div class="row"><div class="col-12 text-center"><a href="<?php echo SITE_URL; ?>" class="login-logo"> <img src="<?php echo RESOURCES_URL."/images/dowcon-steel.png"; ?>"></a></div></div>
				<div class="signup-div">
					<h4 class="login-head signup-head">Sign Up</h4>
						<?php $back_url=""; if(isset($_GET['referer'])){ $back_url = $_GET['referer']; } ?>
						<form id="SignUpForm" class="signup-form mt-4" enctype="multipart/form-data" autocomplete="off">
							<div class="row ">
								<div class="col-sm-6 col-md-4">
									<div class="form-group">
                                    <input type="hidden" name="action" value="SaveSignUpForm">
										<label for="first_name">First Name<em>*</em></label>
										<input type="text" class="form-control validate[required]" id="first_name" placeholder="First Name" name="first_name">
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="form-group">
										<label for="last_name">Last name<em>*</em></label>
										<input type="text" class="form-control validate[required]" id="last_name" placeholder="Last name" name="last_name">
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="form-group">
										<label for="exampleInputEmail1">Email address<em>*</em></label>
										<input type="email" class="form-control validate[required] custom[email]" id="InputEmail1" placeholder="Email Address" name="email">
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="form-group">
										<label for="phone">Phone<em>*</em></label>
										<input type="text" class="form-control validate[required,custom[phone]]" id="phone" placeholder="Phone" name="phone">
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="form-group">
										<label for="password">Password<em>*</em></label>
										<input type="password" class="form-control validate[required,minSize[6]]" id="password" placeholder="Password" name="password">
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="form-group">
										<label for="confirm-password">Confirm Password<em>*</em></label>
										<input type="password" class="form-control validate[required,equals[password],minSize[6]]" id="confirm-password" placeholder="Confirm Password" name="confirm-password">
									</div>
								</div>
								<div class="col-12"><h5 class="signup-minihead mt-4"><strong>Business Details</strong></h5></div>
								<div class="col-12">  
									<div class="form-group">
										<label for="buisness_name">Business name</label>
										<input type="text" class="form-control validate[condRequired[gridCheck]]" id="buisness_name" placeholder="Business name" name="buisness_name">
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="form-group">
										<label for="street_address">Street Address 1</label>
										<input type="text" class="form-control validate[condRequired[gridCheck]]" id="street_address" placeholder="Street Address 1" name="street_address">
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="form-group">
										<label for="street_address_two">Street Address 2</label>
										<input type="text" class="form-control validate[condRequired[gridCheck]]" id="street_address_two" placeholder="Street Address 2" name="street_address_two">
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="form-group">
										<label for="city">City </label>
										<input type="text" class="form-control validate[condRequired[gridCheck]]" id="city" placeholder="City " name="city">
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="form-group">
										<label for="state">State</label>
										<select id="state" name="state" class="form-control validate[condRequired[gridCheck]]">
											<option value=''>Select State</option>
											<option value="ACT">ACT</option>
											<option value="NSW">NSW</option>
											<option value="NT">NT</option>
											<option value="QLD">QLD</option>
											<option value="SA">SA</option>
											<option value="TAS">TAS</option>
											<option value="VIC">VIC</option>
											<option value="WA">WA</option>
										</select>
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="form-group">
										<label for="zip">Postcode</label>
										<input type="text" name="zip" class="form-control validate[custom[postcode],condRequired[gridCheck]]" id="zip" placeholder="Postcode">
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="form-group">
										<label for="abn">ABN</label>
										<input type="text" name="abn" class="form-control" id="abn" placeholder="ABN">
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<div class="form-group">
										<label for="acn">ACN</label>
										<input type="text" name="acn" class="form-control" id="acn" placeholder="ACN">
									</div>
								</div>
								<div class="col-12 mt-3">
									<div class="form-group">
										<div class="custom-control custom-checkbox">
											<input class="custom-control-input" type="checkbox" id="gridCheck" name="check" value="check">
											<label class="custom-control-label" for="gridCheck">
												Apply for Credit Account
											</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-4">
									<button type="submit" class="btn theme-btn sign-in-btn">Register</button>
								</div>
							</div>
							<div class="sign-up-link">Already registered?<a href="<?php echo SITE_URL ?>/login.php"> Sign in here</a>
							</div>
					</form>
				</div>
				<div class="toggle_menu2 toggle_div mt-5" style="display: none;">
					<div>	
						<div class="verify-div text-center">
							<h1 class="text-success"><strong>Thank You!!</strong></h1>
							<h3 class="mt-3">You have been successfully registered.</h3>
							<p>We have sent you an email for email verification. Please verify your email to access your account.</p>
						</br>
						</div>
						<div class='text-center'>
							<a href='<?php echo SITE_URL; ?>' class="btn theme-btn">Go To Home Page</a>
						</div>
					</div>
				</div>
				<div class="login-copy-text signup-copy-text">Copyrights 2020 Dowcon, All rights Reserved</div>
			</div>
		</div>
	</div>
</section>
</body>
</html>