<?php 
include_once(dirname(__FILE__)."/header.php");
// echo date("Y-m-d H:i:s");
// die;
?>
<div class="login-box-body verify-login-box-body">
    <div class="container">
        <div class="row"><div class="col-md-4 offset-md-4">
<?php
    $error=0;
    if(empty($_GET['token'])){
		$error=1;
    }else{
		$Encryption = new Encryption();
		$token=$_GET['token'];
		
		$result = send_rest(array(
			"function" => "Customers/check_if_token_exists",
			"email_token" => $token
        ));
        //print_r($result);
		if(!empty($result['status'])){
			//print_r($result);
			$result_email = send_rest(array(
				"function" => "Customers/check_if_email_already_taken",
				"email" => $result['data']['temp_email'],
			));
			// var_dump($result);
			
			if(!$result['status']){
				//echo 1;
				$error=1;
			}else{
				if($result_email['status']){
					$error=3;
				}else{
					if($result['data']['email']==$result['data']['temp_email']){
						$error=2;  
					}elseif($token == $result['data']['email_confirmation_token']){
						$datefromdb = $result['data']['email_token_created_on'];
						$today_date = date("Y-m-d H:i:s");
						$datetime1 = strtotime($datefromdb);//database date and time
						$datetime2 = strtotime($today_date);//current date and time
						$interval  = abs($datetime2 - $datetime1);
						$minutes_counter  = round($interval / 60);
						
						if ($minutes_counter>=120) { // 60 mins has passed
						  $error=2;
						}else{
							$result2 = send_rest(array(
								"function" => "Customers/UpdateCustomerAfterEmailVerification",
								"email" => $result['data']['temp_email'],
								"customer_id" => $result['data']['user_type_id']
							));
							if($result2['status']){
								// all good
							}else{
								$error=4;
							}
						}	
						
					}else{
						//echo 2;
						$error=1;
					}
				}
			}
		}else{
			//echo 3;
			$error=1;
		}
    }
    
    if($error==1){?>
    <div class="toggle_menu2 text-center">
		<div> 
			<div class="verify-div">
			    <img src="<?php echo RESOURCES_URL; ?>/images/incorrect.png" class='verify_img'>
				<p class="alert alert-danger">Your token is invalid.</p>
				
			</div>
		</div>
	</div>
	<?php }
    if($error==2){?>
		<div class="toggle_menu2 text-center">
			<div> 
				<div class="verify-div">
					<p class="alert alert-warning">Oh no, the link has already expired.</p>
				</div>
				</div>
		</div>
   <?php } 
   if($error==3){?>
	<div class="toggle_menu2 text-center">
		<div> 
			<div class="verify-div">
			    <img src="<?php echo RESOURCES_URL; ?>/images/broken-link.png" class='verify_img1'>
				<p class="alert alert-danger">This email has already been taken by another user.</p>
			</div>
		</div>
	</div>
   <?php }
   if($error==4){?>
	<div class="toggle_menu2">
		<div> 
			<div class="verify-div">
			    <img src="<?php echo RESOURCES_URL; ?>/images/incorrect.png" class='verify_img1'>
				<p class="alert alert-danger">Failed to update customer.</p>
				
			</div>
		</div>
	</div>
   <?php }
    if($error==0){?>
		<div class="toggle_menu2">
			<div> 
				<div class="verify-div">
				    <img src="<?php echo RESOURCES_URL; ?>/images/emal-success.png" class='verify_img'>
					<p class="alert alert-success">Thanks for verifying your email.</p>
					<h3 style='margin:0'>Just a moment..!!!</h3>
					<h4 style='margin:0'>We are redirecting you to login page.</h4><br>
				</div><br>
				<?php
				header( "refresh:5;url=".SITE_URL."/login.php" );
				?>
			</div>
		</div>
	<?php
    }
	?>
	</div>
	</div>
	</div>
</div>
<?php
	include_once(dirname(__FILE__)."/footer.php");
?>