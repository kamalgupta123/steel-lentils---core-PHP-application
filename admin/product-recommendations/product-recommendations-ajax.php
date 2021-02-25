<?php
include_once(dirname(__FILE__)."/../../site_config.php");
include_once(dirname(__FILE__)."/../admin-config.php");
include_once(BASE_DIR . "/common_functions.php");
include_once(ADMIN_DIR . "/admin-functions.php");

if(!empty($_POST['suggested_items'])){
    $Encryption=new Encryption();
	$suggested_stocked_section = $Encryption->decode($_POST['sid']);
	//echo $suggested_stocked_section;
	$result = send_rest(array(
		"function" => "Admin/GetItemsForSections",
		"suggested_stocked_section" => $suggested_stocked_section
	));
	$item_id=0;
    if(!empty($_POST['pid'])){
        // get selected item_id
        $product_recommendation_id=$Encryption->decode($_POST['pid']);
        $result1 = send_rest(array(
	    	"function" => "Admin/GetProductRecommendationDetails",
		    "product_recommendation_id" => $product_recommendation_id
    	));
    	if(!empty($result1['data'])){
    	    $item_id=$result1['data']['recommended_item_id'];
    	}
    }
    //print_r($result['data']);
	ob_start();
	if(!empty($result['data'])){
		foreach ($result['data'] as $value) {
		    $selected="";
		    if(!empty($item_id)){
		        if($item_id==$value['item_id']){
		            $selected="selected";
		        }
		    }
			echo "<option value='".$Encryption->encode($value['item_id'])."' ".$selected.">".$value['length']."</option>";
		}
	}else{
		echo "<option value=''>No item found</option>";
	}
	$html = ob_get_clean();
	$result['html'] = $html;
	header('Content-type: application/json');
    echo json_encode($result);
}

/* if(isset($_POST['action']) && $_POST['action']=='SaveProductRecommendations'){ 
    $data = send_rest(array(
		"function" => "Admin/SaveProductRecommendation",
		"product_recommendation" => $_POST
	));
	header('Content-type: application/json');
    echo json_encode($data);
} */
if(isset($_POST['action']) && $_POST['action']=='SaveProductRecommendations1'){ 
	$Encryption=new Encryption();
	$irregular_sections = array();
	if(isset($_POST['states'])){
		foreach($_POST['states'] as $state){
			$irregular_sections[] = $Encryption->decode($state);
		}
	}
	$_POST['states'] = $irregular_sections;
	if(isset($_POST['suggested_stocked_section'])){
		$_POST['suggested_stocked_section'] = $Encryption->decode($_POST['suggested_stocked_section']);
	}
	if(isset($_POST['suggested_stocked_length'])){
		$_POST['suggested_stocked_length'] = $Encryption->decode($_POST['suggested_stocked_length']);
	}
	if(isset($_POST['product_recommendation_id'])){
		$_POST['product_recommendation_id'] = $Encryption->decode($_POST['product_recommendation_id']);
	}
    $data = send_rest(array(
		"function" => "Admin/SaveProductRecommendation1",
		"product_recommendation" => $_POST
	));
	header('Content-type: application/json');
    echo json_encode($data);
}



?>