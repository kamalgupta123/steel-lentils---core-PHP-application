<?php 
include_once(dirname(__FILE__)."/header.php");
?>
<script>
$(document).ready(function(){
	$("#reset_pass").validationEngine();
	$(document).on("click", "#forgot_redirect", function(){
		window.location=SITE_URL.'/login.php';
	});
});
</script>
<div class="login-box-body verify-login-box-body">
    <div class="container">
	<?php
    $error=0;
    if(empty($_GET['token'])){
		$error=1;
    }
    else{
		$token = $_GET['token'];
		$result  = send_rest(array(
			"function" => "Customers/get_customer_details_by_token",
			"reset_password_verification_token" => $token
		));
		if(empty($result['data'])){
			$error=1;
		}else{
			if($result['data']['pwd_verification_check']==1){
				$error=2;
			}else{
				$datefromdb = $result['data']['pwd_verification_token_created_on'];
				$today_date = date("Y-m-d H:i:s");
				$datetime1 = strtotime($datefromdb);//database date and time
				$datetime2 = strtotime($today_date);//current date and time
				$interval  = abs($datetime2 - $datetime1);
				$minutes_counter  = round($interval / 60);
				
				if ($minutes_counter>=120) { // 60 mins has passed
				   $error=2;
				}else{
					// ok 
				}
			}
		}
    }
    if($error==1){
		echo "<div class='alert alert-danger'>Your token is Invalid.</div>";
    }
    if($error==2){
		echo "<div class='alert alert-danger'>Your token is Expired.</div>";
    }
    if($error==0){
		?>
		<div class="parent_resgistration_form reset_form_hide">
			<!--<div class="welcome">-->
			<!--	<h3>WELCOME, <?php echo output($result['data']['first_name']); ?></h3>-->
			<!--	<h4>RESET PASSWORD</h4>-->
			<!--</div>-->
			<div class="row">
			    <div class="col-md-6 offset-md-3">
    			<form id="reset_pass">
    				<div class="parent_fields">
    				    <h3>Reset Password</h3>
    				    <p>Hi <span class="text-highlight font-weight-semibold{"><?php echo output($result['data']['first_name']); ?></span>, please enter your password below to reset.</p>
    					<div class="form-group confirm_registeration_form reset_pass">
    						<input type="hidden" value="<?php echo  $_GET['token'];?>" name="token">
    						<label for="New Password">New Password</label>
    						<input class="form-control validate[required,minSize[6]] text-input" type="password" name="password" id="password" />
    						<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    					</div>
    					<div class="form-group confirm_registeration_form">
    						<label for="New Password">Confirm Password</label>
    						<input class="form-control validate[required,minSize[6],equals[password]] text-input" type="password" name="confirm_password" id="confirm_password" />
    						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
    					</div>
    					<div class="form-group customer_register cust_verify">
    						<input type="submit" class="btn theme-btn" name="reset_login_button" id="reset_login_button" value="Submit"/>
    					</div>
    				</div>
    			</form>
			 </div>
			</div>
		</div>
	<?php } ?>
	<div class="container reset_pwd_container" style="display:none">
		<div class="success_message alert alert-success" style="box-shadow: 3px 5px 8px rgba(0, 0, 0, 0.5);background-color: #06d8ca!important;border:0px!important;">
			<h3>Thank You! Your Password has been successfully reset.</h3>
			<button type="button" id="forgot_redirect" class="btn service_select select_category" style='background-color: #fd6944 !important;'>Click here to login to your account</button>
		</div>
	</div>
	</div>
</div>
<?php
	include_once(dirname(__FILE__)."/footer.php");
?>