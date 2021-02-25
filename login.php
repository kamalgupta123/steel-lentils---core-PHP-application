<?php 
include_once(__DIR__ .'/common_header.php');

if(!empty($_SESSION['sl_user'])){
    header('Location: '.SITE_URL."/index.php");
}
?>
<script>
$(document).ready(function(){
	$("#login-form").validationEngine({
		promptPosition: "topRight:-90"
	});
	$(document).on('click','.forgot-link',function(){
		$('#forgot_pwd_modal').modal('show');
	});
});
</script>
<section class="section">
	<div class="login-background">
		<div class="bg-cover">
			<div>
				<a href="<?php echo SITE_URL; ?>"><img src="<?php echo RESOURCES_URL; ?>/images/dowcon-steel.png" class="login-logo"></a>
				<h4 class="login-head">Login</h4>
				<form class="login-form front_login_form" id="login-form" enctype="multipart/form-data" autocomplete="off">
					<div class="form-group">
						<label for="email">Email address</label>
						<input type="text" class="form-control validate[required,custom[email]]" name="email" id="email" placeholder="Email Address">
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" class="form-control validate[required]" name="password" id="password" placeholder="Password">
					</div>
					<button type="submit" class="btn theme-btn sign-in-btn">Sign in</button>
					<a href="#" class="forgot-link">Forgot Password?</a>
					<div class="sign-up-link">Don't have an account? <a href="<?php echo SITE_URL ?>/signup.php">Sign up here</a></div>
				</form>
				<div class="login-copy-text">Copyrights 2020 Dowcon, All rights Reserved</div>
			</div>
		</div>
	</div>
</section>
<div class="modal fade" tabindex="-1" role="dialog" id="forgot_pwd_modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Forgot Your Password?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id='ForgotPasswordForm'>
					<div class="row">
						<div class="col-12">
							<div class="form-group form-group-ico">
							    <label>Enter your email address to reset</label>
								<input type="text" name="email" class="form-control form-control-md bj-username validate[required,custom[email]]" placeholder="Email" autocomplete="off">							
							</div>
						</div>
						<div class="col-12">
							<button type="submit" id='forgot_btn' class="btn theme-btn btn-block btn-md">Submit</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>