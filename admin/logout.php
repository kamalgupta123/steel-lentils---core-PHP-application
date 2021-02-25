<?php 
include_once(dirname(__FILE__)."/admin-config.php");

if(isset($_SESSION['sl_admin'])){
	unset($_SESSION['sl_admin']);
}
header("Location: ".ADMIN_URL);
exit();