<?php 
include_once(__DIR__ .'/../common_header.php'); 

if(!empty($_SESSION['sl_admin'])){
    header('Location: '.ADMIN_URL."/profile.php");
}
?>
<script>
$(document).ready(function(){
	$("#adminloginform").validationEngine({
		promptPosition: "topRight:-90"
	});
});
</script>
<section class="section">
	<div class="pg-title login-title-background pt-3 pb-3">
		<div class="container">
			<div>
				<h3><b class="login_title">Login</b></h3>
				<p >Enter the Login Credentials</p>
			</div>
		</div>
	</div>
	<div class="unix-login">
		<div class="container">
			<div class="row">
				<div class="col-12 col-lg-6 offset-lg-3 col-md-8 offset-md-2">
					<div class="login-content">
						<div class="login-form login_wrap">
							<h2  class="text-center">Admin Login</h2>
							<form id="adminloginform">
								<div class="form-group">
									<label class="mb-0">Email address <span class="required_star">*</span></label>
									<input type="email" class="form-control validate[required,custom[email]]" placeholder="Email" id="email" name="email">
								</div>
								<div class="form-group">
									<label class="mb-0">Password <span class="required_star">*</span></label>
									<input type="password" class="form-control validate[required]" placeholder="Password" id="pass" name="pass">
								</div>
								<div class="row align-items-center">
									<div class="col-12 col-md-6 col-lg-6">
										
									</div>
									<div class="col-12 mt-2 mt-md-0 mt-lg-0 col-md-6 col-lg-6 text-right ">
										 <button type="submit" class="btn btn-theme btn-flat ">LOGIN</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php 
include_once(__DIR__ .'/footer.php'); 
?>
