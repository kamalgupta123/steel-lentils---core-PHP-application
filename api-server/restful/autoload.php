<?php 
include_once(__DIR__ ."/../api_site_config.php");
include_once(dirname(__FILE__)."/../libs/ps-validation-engine/PSValidationEngine.php");

spl_autoload_register(function($class_name) {
    include __DIR__ ."/classes/$class_name.php";
});

?>