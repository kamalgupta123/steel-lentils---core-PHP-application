<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

// changes to be done after migrating to client server 
ob_start();
session_start();
date_default_timezone_set("Australia/Sydney");
// ini_set('precision', 2);

function base_url(){
    $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
    $base_url .= '://'. $_SERVER['HTTP_HOST'];
    return $base_url;
}
define('SUB_FOLDER', "/steel-lentils"); // without trailing slash
define('SITE_URL', base_url().SUB_FOLDER);
define('RPP',5);
define('BASE_DIR', dirname(__FILE__));
define('ADMIN_DIR', BASE_DIR."/admin");
define('ADMIN_URL', SITE_URL."/admin");
define('SITE_NAME', 'Steel Lentils');
define('RESOURCES_URL', SITE_URL.'/resources3');
define('RESOURCES_DIR', BASE_DIR.'/resources3');

define('API_URL', SITE_URL .'/api-server/restful/request-handler.php');
define('API_UPLOADS_URL', SITE_URL .'/api-server');
define('WEB_API_KEY', "lentils-steel+R@vDor123)$%^&*#$#@^%$*&()");

define('MAX_FILE_SIZE', 1024 * 1024 * 10); // 10 mb
define('IMAGE_EXTENSIONS', json_encode(array("jpg","jpeg","png","gif")));
define('DOC_FILE_EXTENSIONS', json_encode(array("pdf","doc","docx","ppt","pptx","xls","xlsx")));

define('DEFAULT_SORT_ON', 'created_on');
define('DEFAULT_SORT_TYPE', 'ASC');
define('ENCRYPTION_KEY', "S&L^D&S)I(T*W)3K4EY*&BBA");

include_once(BASE_DIR ."/common_functions.php");
$Enc = new Encryption();

?>