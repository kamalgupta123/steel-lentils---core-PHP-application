<?php 
include_once(dirname(__FILE__)."/site_config.php");

if(isset($_SESSION['sl_user'])){
	unset($_SESSION['sl_user']);
	unset($_SESSION['unique_cart_key']);
}
header("Location: ".SITE_URL."/login.php");
exit();