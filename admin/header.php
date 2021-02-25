<?php 
include_once(__DIR__ .'/admin-config.php');

if(!isset($_SESSION['sl_admin'])){
	
	// function current_page_url(){
		// $page_url   = 'http';
		// if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
			// $page_url .= 's';
		// }
		// return $page_url.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	// }
	//$_SESSION['pb_client_referrer']   = current_page_url();
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){    
		// means page requested by ajax
		
	}
	else{
		//means page requested directly
		ob_end_clean();
		header("Location: ".ADMIN_URL);

		exit();
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Steel Lentils Admin</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo RESOURCES_URL; ?>/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo RESOURCES_URL; ?>/jquery-ui-1.12.1/jquery-ui.min.css">
	<link rel="stylesheet" href="<?php echo RESOURCES_URL; ?>/uikit-3.0.0-beta.9/css/uikit.min.css">
	<link rel="stylesheet" href="<?php echo RESOURCES_URL; ?>/css/admin.css">
	<link rel="stylesheet" href="<?php echo RESOURCES_URL; ?>/css/all.min.css">
	<link rel="stylesheet" href="<?php echo RESOURCES_URL; ?>/css/custom.css">
	<link rel="stylesheet" href="<?php echo RESOURCES_URL; ?>/css/sl.css">
		<link rel="stylesheet" href="<?php echo RESOURCES_URL; ?>/css/iconmoon.css"> 
	<link rel="stylesheet" href="<?php echo RESOURCES_URL; ?>/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" href="<?php echo RESOURCES_URL; ?>/jQuery-Validation-Engine-master/css/validationEngine.jquery.css" type="text/css"/>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> 
	<script src="<?php echo RESOURCES_URL; ?>/jquery-ui-1.12.1/jquery-ui.min.js"></script>
	<script src="<?php echo RESOURCES_URL; ?>/jQuery-Validation-Engine-master/js/jquery.validationEngine.js" charset="utf-8"></script>
	<script src="<?php echo RESOURCES_URL; ?>/jQuery-Validation-Engine-master/js/languages/jquery.validationEngine-en.js" charset="utf-8"></script>
	<script src="<?php echo RESOURCES_URL; ?>/uikit-3.0.0-beta.9/js/uikit.min.js"></script>
	<script src="<?php echo RESOURCES_URL; ?>/js/modernizr-for-history-api.js"></script>
	<!-- Bootstrap dropdown require Popper.js -->
	<script src="<?php echo RESOURCES_URL; ?>/js/popper.min.js"></script>
	<script src="<?php echo RESOURCES_URL; ?>/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo RESOURCES_URL; ?>/js/admin.js"></script>
	<script src="<?php echo RESOURCES_URL; ?>/js/custom.js"></script>
	<script src="<?php echo RESOURCES_URL; ?>/js/bootstrap-datetimepicker.min.js"></script>
	<script src="<?php echo RESOURCES_URL; ?>/js/sl.js"></script>
	<style>
    .ajax_raDiv {
        background-image: url("<?php echo RESOURCES_URL; ?>/images/ajax-loader.gif");
        height: 50px;
        left: 49%;
        position: fixed;
        top: 40%;
        width: 50px;
        z-index: 1090;
      background-size: contain;
    }
    .ajax_raTransp {
        background: grey;
        height: 100%;
        opacity: 0.4;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1089;
    }
	</style>
</head>
<body>
    <div class="wrapper mpage_container">
	<script type="text/javascript">
		var SITE_URL = "<?php echo SITE_URL; ?>";
		var ADMIN_URL = "<?php echo ADMIN_URL; ?>";
		var IMAGE_EXTENSIONS = <?php echo IMAGE_EXTENSIONS; ?>;
		var MAX_FILE_SIZE = "<?php echo MAX_FILE_SIZE; ?>";
		var DOC_FILE_EXTENSIONS = <?php echo DOC_FILE_EXTENSIONS; ?>;
		$(document).ready(function () {

			$('#sidebarCollapse').on('click', function () {
				$('.left-sidebar').toggleClass('active');
			});

		});
		
	</script>
	<div class="ajax_raDiv ajax_loading" style="display: none;"></div>
	<div class="ajax_raTransp ajax_loading" style="display: none;"></div>
		<div class="mpage_container">
    	<script>
	        $(".toggle-nav").click(function() {
            $("body")
                .toggleClass("sidebar-show"), $(".toggle-nav i")
                .toggleClass("mdi mdi-menu"),
                $(".toggle-nav i")
                .addClass("mdi mdi-close");
        });
	    </script>
		<div id="main-wrapper">
		<?php if(!empty($_SESSION['sl_admin'])){
		$user_details = send_rest(array(
         	"function" => "Admin/getAdminDetails",
         	"id" => $_SESSION['sl_admin']['user_id'],
         	"type" => $_SESSION['sl_admin']['user_type']
        ));
        $user_details = $user_details['data'];
		?>
		<div class="dashboard-head">
			<div class="navbar-header">
				<a href="#"> <img src="<?php echo RESOURCES_URL; ?>/images/dowcon-steel.png" class="logo-img"></a>
			</div>
			<div class="navbar-collapse">
			
				<div class="admin-div">
					<div class="avatar-div">
						<img src="<?php echo RESOURCES_URL; ?>/images/Avatar.png" class="user-img">
				    </div>
					<div class="dropdown upper-dropdown">
						<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome Admin<br><span><?php echo (!empty($user_details))?$user_details['first_name'].' '.$user_details['last_name']:""; ?></span></button>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
							<a id="adminprofile" class="dropdown-item load_ajax" href="<?php echo ADMIN_URL."/profile.php"?>">Profile</a>
	                        <a id="loginView_logoutLinkButton" class="dropdown-item" href="<?php echo ADMIN_URL."/logout.php"?>">Logout</a>
						</div>
					</div>
				</div>
				<ul class="navbar-nav ml-4">
					<li class="nav-item m-r-10"> <a class="nav-link sidebartoggle hidden-sm-down text-muted  " href="javascript:void(0)"><i class="fas fa-bars"></i></a> </li>
				</ul>
			</div>
		</div>
		<div class="left-sidebar">
			<div class="scroll-sidebar">
				<nav class="sidebar-nav">
					<ul class="sidebar-list">
						<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/profile.php")){echo 'active';} ?>"><a class="sidebar-list-link dashboard-link load_ajax " href="<?php echo ADMIN_URL."/profile.php"?>"><span class="icon icon-user2"></span>Profile</a></li>
						<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/product-ranges/")){echo 'active';} ?>"><a class="sidebar-list-link range-link load_ajax " href="<?php echo ADMIN_URL."/product-ranges/list-product-ranges.php"?>"><span class="icon icon-box-plot"></span>Product Ranges</a></li>
						<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/sections/")){echo 'active';} ?>"><a class="sidebar-list-link section-link load_ajax " href="<?php echo ADMIN_URL."/sections/list-sections.php"?>"><span class="icon icon-intersect"></span>Sections</a></li>
						<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/items/")){echo 'active';} ?>"><a class="sidebar-list-link range-link load_ajax " href="<?php echo ADMIN_URL."/items/list-items.php"?>"><span class="icon icon-tag"></span>Items</a></li>
						<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/product-recommendations/")){echo 'active';} ?>"><a class="sidebar-list-link range-link load_ajax " href="<?php echo ADMIN_URL."/product-recommendations/list-product-recommendations.php"?>"><span class="icon icon-recommend"></span>Product Recommendations</a></li>
						<li><a class="sidebar-list-link staff-members-link" href="<?php echo ADMIN_URL."/staff/list-staff.php"?>"><span class="icon icon-engineers"></span>Staff Members</a></li>
						<li><a class="sidebar-list-link customers-link" href="<?php echo ADMIN_URL."/customers/list-customers.php"?>"><span class="icon icon-team"></span>Customers</a></li>
						<li><a class="sidebar-list-link trucks-link" href="<?php echo ADMIN_URL."/trucks/list-trucks.php"?>"><span class="icon icon-truck-dash"></span>Trucks</a></li>
						<li><a class="sidebar-list-link orders-link" href="#"><span class="icon icon-package"></span>Orders</a></li>
						<li><a class="sidebar-list-link payment-history-link" href="#"><span class="icon icon-currency-exchange"></span>Payment History</a></li>
						<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/settings/settings.php")){echo 'active';} ?>"><a class="sidebar-list-link settings-link load_ajax" href="<?php echo ADMIN_URL."/settings/settings.php"?>"><span class="icon icon-location-pin"></span>  Postcodes</a></li>
						<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/settings/global-settings.php")){echo 'active';} ?>"><a class="sidebar-list-link settings-link load_ajax" href="<?php echo ADMIN_URL."/settings/global-settings.php"?>"><span class="icon icon-adjust"></span> Global Settings</a></li>
						<?php /* ?>
						<li>
							<a class="has-menu" data-toggle="collapse" role="button" aria-expanded="false" href="#settingMenu"><span class="icon icon-adjust"></span> Settings</a>
							<ul id="settingMenu" class="collapse list-inline">
								<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/settings/settings.php")){echo 'active';} ?> pl-3"><a class="sidebar-list-link settings-link load_ajax" href="<?php echo ADMIN_URL."/settings/settings.php"?>">Postcodes</a></li>
								<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/settings/global-settings.php")){echo 'active';} ?> pl-3"><a class="sidebar-list-link settings-link load_ajax" href="<?php echo ADMIN_URL."/settings/global-settings.php"?>">Global Settings</a></li>
							</ul>
						</li>
						<?php */ ?>
					</ul>
				</nav>
			</div>
		</div>
		<div class="page-wrapper">
		<?php } ?>