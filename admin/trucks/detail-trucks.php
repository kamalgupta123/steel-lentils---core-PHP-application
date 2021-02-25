<?php 
include_once(__DIR__ . "/../header.php");
include_once(ADMIN_DIR . "/admin-functions.php");
$error = 0;
$Encryption = new Encryption(); 
if(isset($_GET["truck_id"])){
    $truck_id = $Encryption->decode($_GET["truck_id"]);

    $result = send_rest(array(
        "function" => "Admin/GetTruckDetails",
        "truck_id" => $truck_id
    ));
    
    if(empty($result['status'])){
        $error = 1;
        echo '<div class="alert alert-danger" style="padding:10px;margin:10px">Failed to get details</div>';
    }
    else{
        $d_row = $result['data'];
    }
}
if($error==0){
?>

<script>
	$(document).ready(function(){
		$("#SaveTrucksForm").validationEngine({
			promptPosition: "topRight:-90"
		});
	});
</script>

<div class="container-fluid">
    <div class="row head-row">
        <div class="col-md-12">
			<div class="heading-div ">
			    <div class="row">
			        <div class="col-4">
			            <div>Add Truck</div>
			        </div>
				</div>
            </div>
        </div> 
    </div>
    <div class="table-div mt-1">
        <div class="row">
            <div class="col-lg-12">
                <?php $back_url=""; if(isset($_GET['referer'])){ $back_url = $_GET['referer']; } ?>
                <form id="SaveTrucksForm" data-new-key="truck_id" class="submit_ajax" data-action-url="<?php echo ADMIN_URL."/trucks/trucks-ajax.php";?>" data-add-url="<?php echo ADMIN_URL."/trucks/detail-trucks.php/?referer=".rawurlencode($back_url); ?>" data-back-url="<?php echo $back_url; ?>" enctype="multipart/form-data" autocomplete="off">
                    <div class="row ">
                        <div class="form-group col-lg-4 col-md-6 save-col">
							<input type="hidden" name="action" value="SaveTrucks"> 
                            <input type="hidden" value="<?php if(!empty($_GET["truck_id"])){echo $_GET["truck_id"];} ?>" name="truck_id">      
							<label for="registration_number">Registration Number<span class="required_star">*</span></label>
                            <input type="text" class="form-control validate[required]" id="registration_number" value='<?php if(!empty($d_row)){ echo $d_row['registration_number']; }?>' name="registration_number" placeholder="Registration Number">                    
                        </div>
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
                                <input type="checkbox" name="is_enabled" class="mobileToggle show_msg_by_toggle" id="is_enabled"  value="1">
                                <label for="is_enabled"><span id="show_label" class="secondary-label">Click to enable</span></label>
							</div>
                        </div>
                    </div>
                    <button type="submit" class="btn theme-btn float-md-right save_btn_action" data-form-id="SaveTrucksForm">Save Changes</button>
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