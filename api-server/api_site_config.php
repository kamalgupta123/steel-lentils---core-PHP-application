<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

// changes to be done after migrating to client server 
ob_start();
session_start();
date_default_timezone_set("Australia/Sydney");
// ini_set('precision', 2);

define("HOST", "localhost");
define("USER", "root");
define("PASSWORD", "k@m@l1997");
define("DATABASE", "steel_lentils");

global $con;
$con = new mysqli(HOST, USER, PASSWORD, DATABASE);
if($con->connect_errno){
	die ("Cannot connect to the database");
}
if (!$con->set_charset("utf8")) {
	die("Error loading character set utf8: ".$con->error);
}
// $con->query("SET GLOBAL sql_mode = 'NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';");
$con->query("SET SESSION sql_mode = 'NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';");

function base_url(){
    $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
    $base_url .= '://'. $_SERVER['HTTP_HOST'];
    return $base_url;
}
define('SITE_URL', "http://blinkcoders.com/steel-lentils/");
define('API_SUB_FOLDER', ""); // without trailing slash
define('API_SITE_URL', base_url().API_SUB_FOLDER);
define('API_BASE_DIR', dirname(__FILE__));
define('SITE_NAME', 'Steel Lentils');

define('WEB_API_KEY', "lentils-steel+R@vDor123)$%^&*#$#@^%$*&()");
define('APP_API_KEY', "dowcon-lentils+345785&^$4467470#$!^&*sdve");

define('MAX_FILE_SIZE', 1024 * 1024 * 10); // 10 mb
define('IMAGE_EXTENSIONS', json_encode(array("jpg","jpeg","png","gif")));
define('DOC_FILE_EXTENSIONS', json_encode(array("pdf","doc","docx","ppt","pptx","xls","xlsx")));

define('DEFAULT_SORT_ON', 'created_on');
define('DEFAULT_SORT_TYPE', 'ASC');

include_once(API_BASE_DIR."/api_common_functions.php");

?>