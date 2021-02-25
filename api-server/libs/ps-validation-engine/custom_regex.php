<?php 
$CUSTOM_REGEX_ARRAY = [
	"phone"=> [
		"regex"=> "/^([\+][0-9]{1,3}([ \.\-])?)?([\(][0-9]{1,6}[\)])?([0-9 \.\-]{1,32})$/",
		"alertText" => "[field_label] is not a valid Phone format"
	],
	"email"=> [
		"regex"=> '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
		"alertText"=> "[field_label] is not a valid email format"
	],
	"postcode"=> [
		"regex"=>"/^[0-9]*$/",
		"alertText"=>"Invalid postcode format"
	],
	"integer"=> [
		"regex"=> "/^[\-\+]?\d+$/",
		"alertText"=> "[field_label] is not a valid integer"
	],
	"number"=> [
		// Number, including positive, negative, and floating decimal. credit=> orefalo
		// "regex"=> "/^[\-\+]?((([0-9][1,3])([,][0-9][3])*)|([0-9]+))?([\.]([0-9]+))?$/",
		"regex"=> "/^-?\d+(\.\d+)?$/",
		"alertText"=> "[field_label] is an Invalid decimal no."
	],
	"date"=>  [
		"regex"=>"^([0-2][0-9]|(3)[0-1])(-)(((0)[0-9])|((1)[0-2]))(-)\d{4}$^",
		"alertText"=> "[field_label] is not a date in the format DD-MM-YYYY"
	], 
	"ipv4"=> [
		"regex"=> "/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/",
		"alertText"=> "[field_label] is not a valid IP address format"
	],
	"url"=> [
		"regex"=> '#((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i',
		"alertText"=> "[field_label] has an Invalid URL Format"
	],
	"onlyNumberSp"=> [
		"regex"=> "/^[0-9\ ]+$/",
		// "alertText"=> "* Numbers only"
	],
	"onlyLetterSp"=> [
		"regex"=> "/^[a-zA-Z\ \']+$/",
		// "alertText"=> "* Letters only"
	],
	"onlyLetterAccentSp"=>[
		"regex"=> "/^[a-z\u00C0-\u017F\ ]+$/i",
		// "alertText"=> "* Letters only (accents allowed)"
	],
	"onlyLetterNumber"=> [
		"regex"=> "/^[0-9a-zA-Z]+$/",
		// "alertText"=> "* No special characters allowed"
	],
	"dateFormat"=>[
		"regex"=> "/^\d[4][\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$|^(?=>(?=>(?=>0?[13578]|1[02])(\/|-)31)|(?=>(?=>0?[1,3-9]|1[0-2])(\/|-)(?=>29|30)))(\/|-)(?=>[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?=>(?=>0?[1-9]|1[0-2])(\/|-)(?=>0?[1-9]|1\d|2[0-8]))(\/|-)(?=>[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(0?2(\/|-)29)(\/|-)(?=>(?=>0[48]00|[13579][26]00|[2468][048]00)|(?=>\d\d)?(?=>0[48]|[2468][048]|[13579][26]))$/",
		// "alertText"=> "* Invalid Date"
	],
	//tls warning=>homegrown not fielded 
	"dateTimeFormat"=> [
		"regex"=> "/^\d[4][\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])\s+(1[012]|0?[1-9])[1]=>(0?[1-5]|[0-6][0-9])[1]=>(0?[0-6]|[0-6][0-9])[1]\s+(am|pm|AM|PM)[1]$|^(?=>(?=>(?=>0?[13578]|1[02])(\/|-)31)|(?=>(?=>0?[1,3-9]|1[0-2])(\/|-)(?=>29|30)))(\/|-)(?=>[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^((1[012]|0?[1-9])[1]\/(0?[1-9]|[12][0-9]|3[01])[1]\/\d[2,4]\s+(1[012]|0?[1-9])[1]=>(0?[1-5]|[0-6][0-9])[1]=>(0?[0-6]|[0-6][0-9])[1]\s+(am|pm|AM|PM)[1])$/",
		// "alertText"=> "* Invalid Date or Date Format",
	]
];
?>