<?php 
include_once(__DIR__ .'/site_config.php');

error_reporting(E_ALL); 
ini_set('display_errors','On');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dowcon Steel Lentils</title>
  <meta charset="utf-8">
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
</head>
<body class="body">
    <div class="wrapper mpage_container">
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
    <script type="text/javascript">
		var SITE_URL = "<?php echo SITE_URL; ?>";
		var ADMIN_URL = "<?php echo ADMIN_URL; ?>";
		var IMAGE_EXTENSIONS = <?php echo IMAGE_EXTENSIONS; ?>;
		var MAX_FILE_SIZE = "<?php echo MAX_FILE_SIZE; ?>";
		var DOC_FILE_EXTENSIONS = <?php echo DOC_FILE_EXTENSIONS; ?>;
	</script>
	<div class="ajax_raDiv ajax_loading" style="display: none;"></div>
	<div class="ajax_raTransp ajax_loading" style="display: none;"></div>
