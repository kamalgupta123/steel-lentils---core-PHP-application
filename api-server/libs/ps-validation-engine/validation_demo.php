<?php 
include_once(__DIR__ ."/PSValidationEngine.php");
echo "<pre>";
$validations = [
	"first_name" => [
		"validate"=>"required", 
		"label"=>"First Name",
	], // optional fields "custom_error_msg"=>"", "custom_required_msg"=>""
	"email_address" => [
		"validate"=>"required,custom[email]", 
		"label"=>"Email Address",
	],
	"password" => [
		"validate"=>"required,minSize[5],maxSize[15]", 
		"label"=>"Password",
	],
	"conf_password" => [
		"validate"=>"required,equals[password]", 
		"label"=>"Confirm Password",
	],
	"usd_price" => [
		"validate"=>"groupRequired[price],custom[number],min[10]", 
		"label"=>"USD Price", 
		"custom_required_msg"=>"Either USD Price or AUD Price must be filled", 
		"custom_error_msg"=>""
	],
	"aud_price" => [
		"validate"=>"groupRequired[price],custom[number],max[100]", 
		"label"=>"AUD Price", 
		"custom_required_msg"=>"Either USD Price or AUD Price must be filled", 
	],
	"ip_address" => [
		"validate"=>"custom[ipv4]", 
		"label"=>"IP Address",
	],
	"website" => [
		"validate"=>"custom[url]",
		"label"=>"Website",
	],
	"Dob"=>[
		'validate'=>"required,custom[date],past[Doj]",
		'label'=>"Date of Birth"
	]
];

$inputdata = array (
	"first_name" => "Hello",
	"last_name" => "",
	"email_address" => "fgsdf@jsdf.com",
	"password" => "123456789123456",
	"conf_password" => "123456789123456",
	"usd_price" => "10",
	"aud_price" => "100",
	"ip_address" => "192.168.1.1",
	"website" => "https://github.com/posabsolute/jQuery-Validation-Engine",
	"Dob"=>"21-10-1997",
	"Doj"=>"10-08-2019"
);

$PSValidationEngine = new PSValidationEngine($inputdata, $validations);
print_r($PSValidationEngine->validate());


?>