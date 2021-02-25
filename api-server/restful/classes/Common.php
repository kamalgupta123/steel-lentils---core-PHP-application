<?php
include_once(__DIR__ ."/../autoload.php");

class Common {
	 function validateDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) === $date;
	}

}
?>