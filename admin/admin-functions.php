<?php 
// To prevent unauthorized access
if(!isset($_SESSION['sl_admin'])){
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){    
		// means page requested by ajax
		ob_end_clean();
		header('HTTP/1.0 401 Unauthorized');
		exit();
	}
	if(basename($_SERVER['PHP_SELF'])!='index.php'){
		//means page requested directly
		ob_end_clean();
	header('Location: '.ADMIN_URL);
		exit();
	}
}