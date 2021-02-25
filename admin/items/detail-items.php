<?php 
include_once(__DIR__ . "/../header.php");
include_once(ADMIN_DIR . "/admin-functions.php");

$error = 0;
$Encryption = new Encryption(); 
if(isset($_GET["item_id"])){
    $item_id = $Encryption->decode($_GET["item_id"]);

    $result = send_rest(array(
        "function" => "Admin/GetItemDetails",
        "item_id" => $item_id
    ));
    
    if(empty($result['status'])){
        $error = 1;
        echo '<div class="alert alert-danger" style="padding:10px;margin:10px">Failed to get details</div>';
    }
    else{
        $d_row = $result['data'];
    }
    
    $result1 = send_rest(array(
        "function" => "Admin/GetSection",
        "item_id" => $item_id
    )); 
    
    if(empty($result1['status'])){
        //$error1 = 1;
        //echo '<div class="alert alert-danger" style="padding:10px;margin:10px">Failed to get details</div>';
    }
    else{
        $d_row1 = $result1['data'];
    }
    
}
else{
    
    $result1 = send_rest(array(
        "function" => "Admin/GetSection"
    ));
    
    if(empty($result1['status'])){
        //$error = 1;
        //echo '<div class="alert alert-danger" style="padding:10px;margin:10px">Failed to get details</div>';
    }
    else{
        $d_row1 = $result1['data'];
    }
    
}

if($error==0){
?>

<script>
	$(document).ready(function(){
		$("#SaveItemsForm").validationEngine({
			promptPosition: "topRight:-90"
		});
		var spinner = $( ".spinner" ).spinner();
	    $('.select2').select2();
	});
</script>

<div class="container-fluid">
    <div class="row head-row">
        <div class="col-md-12">
			<div class="heading-div ">
			    <div class="row">
			        <div class="col-4">
			            <div>Add Items</div>
			        </div>
				</div>
            </div>
        </div> 
    </div>
    <div class="table-div mt-1">
        <div class="row">
            <div class="col-lg-12">
                <?php $back_url=""; if(isset($_GET['referer'])){ $back_url = $_GET['referer']; } ?>
                <form id="SaveItemsForm" data-new-key="item_id" class="submit_ajax" data-action-url="<?php echo ADMIN_URL."/items/items-ajax.php";?>" data-add-url="<?php echo ADMIN_URL."/items/detail-items.php/?referer=".rawurlencode($back_url); ?>" data-back-url="<?php echo $back_url; ?>" enctype="multipart/form-data" autocomplete="off">
                    <div class="row ">
                        <div class="form-group col-lg-4 col-md-6 save-col">
                        <input type="hidden" name="action" value="SaveItems">
                        <input type="hidden" value="<?php if(!empty($_GET["item_id"])){echo $_GET["item_id"];} ?>" name="item_id">
                        <label for="sections">Select section<span class="required_star">*</span></label>
                        <select id="sections" name="sections" class="form-control select2 validate[required]">
                            <option value="">Select</option>
                            <?php
							if(!empty($d_row1)){
                                foreach($d_row1 as $pagg_row){
                            ?>
								<option <?php if(!empty($d_row['section_id']) && $d_row['section_id']==$pagg_row['section_id']) { echo "selected"; } ?> value="<?php echo $Encryption->encode($pagg_row['section_id']); ?>"><?php 
								if($pagg_row['product_range_type']==1){
								    echo "Stocked - ";
								}elseif($pagg_row['product_range_type']==2){
								    echo "Irregular - ";
								}
								echo $pagg_row['section_name'];
								?></option>
                            <?php
								}
							}else{
								?>
								<option value=''>No records to display</option>
								<?php 
							}
                            ?>
                        </select>
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="length">Length<span class="required_star">*</span></label>
                            <input type="text" class="form-control validate[required] custom[integer] min[1]" id="length" name="length" value='<?php if(!empty($d_row)){ echo $d_row['length']; }?>' placeholder="Enter Length">
                        </div>
                        <div class="form-group col-lg-4 col-md-6 ">
                            <label for="price">Price<span class="required_star">*</span></label>
                            <div class="price-star">
                            <input type="text" class="form-control validate[required] custom[number] min[0]" id="price" name="price" value='<?php if(!empty($d_row)){ echo $d_row['price']; }?>'placeholder="Enter Price">
                            <i class="fas fa-dollar-sign"></i></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="weight">Weight</label>
                            <input type="text" class="form-control custom[number] min[0]" id="weight" name="weight" value='<?php if(!empty($d_row)){ echo $d_row['weight']; }?>' placeholder="Enter weight">
                        </div>
                        <div class="form-group col-lg-4 col-md-6 quantity-col sample-col">
                            <label for="quantity">Quantity<span class="required_star"></span></label><br>
                            <input type="text" class="form-control spinner validate[custom[integer],min[0]]" id="quantity" name="quantity" value='<?php if(!empty($d_row)){ echo $d_row['quantity']; }?>' placeholder="Enter Quantity">
                        </div>
                        <div class="form-group col-lg-4 col-md-6 quantity-col">
                            <label for="order_threshold_value">Order Threshold Value</label><br>
                            <input type="text" class="spinner form-control validate[custom[integer],min[0]]" id="order_threshold_value" name="order_threshold_value" value='<?php if(!empty($d_row)){ echo $d_row['order_threshold_value']; }?>' placeholder="Enter Order Threshold Value">
                        </div>
                    </div>          
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="created_on">Created On</label>
                            <input type="text" class="form-control" id="created_on" name="created_on" value="<?php if(!empty($d_row)){ echo $d_row['created_on']; }?>" disabled>
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label for="modified_on">Modified On</label>
                            <input type="text" class="form-control" id="modified_on" name="modified_on" value="<?php if(!empty($d_row)){ echo $d_row['modified_on']; }?>" disabled>
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                             <div class="toggleWrapper">
                                <input type="checkbox" name="is_enabled" class="mobileToggle show_msg_by_toggle" id="is_enabled"  value="1" <?php if(!empty($d_row) && $d_row['is_enabled']==0){ } else echo"checked";?>>
                                <label for="is_enabled"><span id="show_label" class="secondary-label"><?php if(!empty($d_row) && $d_row['is_enabled']==0){ echo "Click to Enable";} else echo "Click to Disable";?></span></label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn theme-btn float-md-right save_btn_action" data-form-id="SaveItemsForm">Save Changes</button>
                    <button type="button" class="cancel_btn_action cancel-btn btn btn-default">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    <div class="copyright-text mt-4">Copyrights 2020, Dowcon</div>
</div>
<?php
}
include_once(ADMIN_DIR . "/footer.php");
?>