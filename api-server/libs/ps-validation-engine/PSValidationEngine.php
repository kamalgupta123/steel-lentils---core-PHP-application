<?php 
include_once (__DIR__ . "/custom_regex.php");
class PSValidationEngine {
	var $inputdata = array();
	var $validations = array();
	
	function __construct($inputdata, $validations) {
		$this->inputdata = $inputdata;
		$this->validations = $validations;
	}
	function validate(){
		$errors = array();
		$error_index = 0;
		foreach($this->validations as $field => $vdata){
			$validators = explode(",",$vdata['validate']);
			$field_label = $vdata['label'];
			foreach($validators as $validator){
				$func_name = trim($validator);
				if(!empty($func_name)){ // prevent extra comma typed by mistake
					$before_bracket = strstr($func_name,"[", true);
					if($before_bracket !== false){
						$func_name = $before_bracket;
					}
					// var_dump($validator);
					if(method_exists($this, $func_name)){
						$error = $this->$func_name($field, $field_label, $validator);
						// var_dump($error);
						if(!empty($error)){
							if(strpos($validator,"groupRequired")!==false){
								if(empty($vdata['custom_required_msg'])){
									throw new Exception("custom_required_msg parameter missing for $validator called on field $field");
								}
								$errors[$validator] = $vdata['custom_required_msg'];
							}
							else{
								if(stripos($validator,"required")!==false){
									if(!empty($vdata['custom_required_msg'])){
										$errors[$error_index] = $vdata['custom_required_msg'];
									}
									else{
										$errors[$error_index] = $error;
									}
								}
								elseif(!empty($vdata['custom_error_msg'])){
									$errors[$error_index] = $vdata['custom_error_msg'];
								}
								else{
									$errors[$error_index] = $error;
								}
							}
							$error_index++;
						}
					}
					else{
						throw new Exception("Undefined function $func_name called for field $field");
					}
				}
			}
		}
		// print_r($this->validations);
		$errors = array_values($errors); // remove grouped error keys
		return $errors;
	}
	function required($field, $field_label, $validator){
		if(empty($this->inputdata[$field])){
			return $field_label." is required";
		}
		return 0;
	}
	function groupRequired($field, $field_label, $validator){
		$validator = trim($validator);
		array_search_id($validator, $this->validations, array(), $paths);
		$value_found = 0;
		if(!empty($paths)){
			$paths = array_keys($paths);
			foreach($paths as $path){
				if(!empty($this->inputdata[$path])){
					$value_found = 1;
					break;
				}
			}
		}
		if($value_found==0){
			return $field_label." is group required";
		}
		return 0;
	}
	function custom($field, $field_label, $validator){
		global $CUSTOM_REGEX_ARRAY;
		$custom_validator = trim(str_ireplace(array("custom[","]"),"",$validator));
		
		if(empty($CUSTOM_REGEX_ARRAY[$custom_validator])){
			throw new Exception("Undefined custom regex $custom_validator called for field $field");
		}
		else{
			if(!empty($this->inputdata[$field])){
				$regex = $CUSTOM_REGEX_ARRAY[$custom_validator]['regex'];
				if(!preg_match($regex,$this->inputdata[$field])){
					if(!empty($CUSTOM_REGEX_ARRAY[$custom_validator]['alertText'])){
						return str_ireplace("[field_label]",$field_label,$CUSTOM_REGEX_ARRAY[$custom_validator]['alertText']);
					}
					else{
						throw new Exception("alertText is not defined for custom regex $custom_validator called for field $field");
					}
				}
			}
		}
		// var_dump($custom_validator);
		return 0;
	}
	
	function min($field, $field_label, $validator){
		if(!empty($this->inputdata[$field])){
			$min_value = trim(str_ireplace(array("min[","]"),"",$validator));
			if($this->inputdata[$field] < $min_value){
				return $field_label." must have minimum value $min_value";
			}
		}
	}
	
	function max($field, $field_label, $validator){
		if(!empty($this->inputdata[$field])){
			$max_value = trim(str_ireplace(array("max[","]"),"",$validator));
			if($this->inputdata[$field] > $max_value){
				return $field_label." can only have maximum value $max_value";
			}
		}
	}
	function minSize($field, $field_label, $validator){
		if(!empty($this->inputdata[$field])){
			$min_value = trim(str_ireplace(array("minSize[","]"),"",$validator));
			if(strlen($this->inputdata[$field]) < $min_value){
				return $field_label." must have minimum $min_value characters";
			}
		}
	}
	function maxSize($field, $field_label, $validator){
		if(!empty($this->inputdata[$field])){
			$max_value = trim(str_ireplace(array("maxSize[","]"),"",$validator));
			if(strlen($this->inputdata[$field]) > $max_value){
				return $field_label." can only have maximum $max_value characters";
			}
		}
	}
	function equals($field, $field_label, $validator){
		$equal_field = trim(str_ireplace(array("equals[","]"),"",$validator));
		if(!empty($this->inputdata[$field]) && $this->inputdata[$equal_field]){
			if($this->inputdata[$field] != $this->inputdata[$equal_field]){
				$equal_field_label = $this->validations[$equal_field]['label'];
				return $field_label." do not match with $equal_field_label";
			}
		}
	}
	
	function past($field,$field_label,$validator)
	{
		if(!empty($this->inputdata[$field]))
		{
			$datevalue = trim(str_ireplace(array("past[","]"),"",$validator));
			if($datevalue=="NOW")
			{
				$validate_against=date_create(date("d-m-Y"));	
			}
			elseif(date_create($datevalue))
			{
				$validate_against=date_create($datevalue);
			}
			else
			{
				if(!empty($this->inputdata[$datevalue]) and date_create($this->inputdata[$datevalue]))
				{
					$validate_against=date_create($this->inputdata[$datevalue]);
				}
				else
				{
					return $datevalue." does not contain a valid Date";
				}
			}
			if($date_validate=date_create($this->inputdata[$field]))
			{
				//Do Nothing
			}
			else
			{
				return $field_label." is not a valid date in the format DD-MM-YYYY";
			}
			if(date_diff($date_validate,$validate_against)->format("%R%a")<0)
			{
				$date_show=date_format($validate_against,"d-m-Y");
				return $field_label." must be before $date_show";
			}
		}
	}
	function future($field,$field_label,$validator)
	{
		if(!empty($this->inputdata[$field]))
		{
			if($datevalue=="NOW")
			{
				$validate_against=date_create(date("Y-m-d"));	
			}
			elseif(date_create($datevalue))
			{
				$validate_against=date_create($datevalue);
			}
			else
			{
				if(!empty($this->inputdata[$datevalue]) and date_create($this->inputdata[$datevalue]))
				{
					$validate_against=$this->inputdata[$datevalue];
				}
				else
				{
					return $datevalue." does not contain a valid Date format(DD-MM-YYYY)";
				}
			}
			if($date_validate=date_create($this->inputdata[$field]))
			{
				//Do Nothing
			}
			else
			{
				return $field_label." is not a valid date in the format DD-MM-YYYY";
			}
			if(date_diff($date_validate,$validate_against)->format("%R%a")>0)
			{
				$date_show=date_format($validate_against,"d-m-Y");
				return $field_label." must be after $date_show";
			}
		}
	}
}

// helper functions
function array_search_id($search_value, $array, $id_path, &$paths) { 
	if(is_array($array) && count($array) > 0) { 
		foreach($array as $key => $value) { 
		  $temp_path = $id_path; 
			// Adding current key to search path 
			array_push($temp_path, $key); 
			// Check if this value is an array 
			// with atleast one element 
			if(is_array($value) && count($value) > 0) { 
				$res_path = array_search_id($search_value, $value, $temp_path, $paths); 
				if ($res_path) { 
					// return $res_path; 
					$paths[$key] = $res_path;
				} 
			} 
			else if(strpos($value,$search_value)!==false) { 
				return $temp_path; 
			} 
		} 
	} 
	return false; 
}
?>