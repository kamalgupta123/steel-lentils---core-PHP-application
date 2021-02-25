<?php
include_once(__DIR__ .'/../header.php');
include_once(ADMIN_DIR . "/admin-functions.php");

$error = 0;
$Encryption = new Encryption(); 
if(isset($_GET["product_range_id"])){
	$product_range_id = $Encryption->decode($_GET["product_range_id"]);
	
	$result = send_rest(array(
		"function" => "Admin/GetProductRangeDetails",
		"product_range_id" => $product_range_id
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
		$("#ProductRanges").validationEngine({
			promptPosition: "topRight:-90"
		});
		var spinner = $( ".spinner" ).spinner();
	});
	</script>

	<div class="container-fluid">
		<div class="row head-row">
			<div class="col-md-12">
				<div class="heading-div "> <?php if(!empty($d_row)){ echo 'Edit ';}else{ echo "Add ";}?>Product Ranges</div>
				<div class="row owner-row">
					<div class="col-lg-12">
						<div class="card">
							<div class="card-body">
								<?php $back_url=""; if(isset($_GET['referer'])){ $back_url = $_GET['referer']; } ?>
								<form id="ProductRanges" data-new-key="product_ranges_id" class="submit_ajax" data-action-url="<?php echo ADMIN_URL."/product-ranges/product-ranges-ajax.php";?>" data-add-url="<?php echo ADMIN_URL."/product-ranges/detail-product-ranges.php/?referer=".rawurlencode($back_url); ?>" data-back-url="<?php echo $back_url; ?>" enctype="multipart/form-data" autocomplete="off">
									<div class="row ">
										<div class="form-group col-lg-4 col-md-6">
										<input type="hidden" name="action" value="SaveProductRanges">
										<input type="hidden" value="<?php if(!empty($_GET["product_range_id"])){echo $_GET["product_range_id"];} ?>" name="product_range_id">
											<label for="product_range_name">Product Range Name<span class="required_star"> *</span></label>
											<input type="text" class="form-control validate[required]" id="product_range_name" name="product_range_name" value='<?php if(!empty($d_row)){ echo $d_row['product_range_name']; }?>'placeholder="Enter Product Range Name">
										</div>
										<div class="form-group col-lg-4 col-md-6">
											<label for="product_range_type">Product Range Type</label>
											<select id="product_range_type" class="form-control" name="product_range_type">
												<option value="2" <?php if(!empty($d_row) && $d_row['product_range_type']==2){ echo "selected"; }else{}?>>Irregular</option>
												<option value="1" <?php if(!empty($d_row) && $d_row['product_range_type']==1){ echo "selected"; }else{}?>>Stocked</option>
											</select>
										</div>
										<div class="form-group col-lg-4 col-md-6">
											<label for="created_on">Created On</label>
											<input type="text" class="form-control" id="created_on" name="created_on" value="<?php if(!empty($d_row)){ echo ui_datetime($d_row['created_on']); }?>" disabled>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-lg-6 col-md-6">
											<label for="modified_on">Modified On</label>
											<input type="text" class="form-control" id="modified_on" name="modified_on" value="<?php if(!empty($d_row)){ echo ui_datetime($d_row['modified_on']); }?>" disabled>
										</div>
										<div class="form-group col-md-6 col-lg-6">
										    <div class="toggleWrapper">
											<input type="checkbox" name="is_enabled" class="mobileToggle  show_msg_by_toggle" id="is_enabled" value="1" <?php if(!empty($d_row) && $d_row['is_enabled']==0){} else {echo"checked";}?>>
											<label for="is_enabled"><span id="show_label" class="secondary-label"><?php if(!empty($d_row) && $d_row['is_enabled']==0){ echo "Click to Enable";} else echo "Click to Disable";?></span></label>
											<input type="checkbox" name="is_custom_length_allowed" class="mobileToggle  show_msg_by_toggle mobileToggle2" id="is_custom_length_allowed" value="1" <?php if(!empty($d_row) && $d_row['product_range_type']==2){echo "checked";echo " disabled";}else{} if(!empty($d_row) && $d_row['is_custom_length_allowed']==1){echo"checked";} else {}?>>
											<label for="is_custom_length_allowed" class="ml-sm-3"><span id="show_label" class="secondary-label">Allow Custom Length</span></label>
											</div>
										</div>
									</div>
									<button type="submit" class="btn float-md-right save_btn_action theme-btn" data-form-id="ProductRanges">Save Changes</button>
									<button type="button" class="cancel_btn_action  cancel-btn btn btn-default">Cancel</button>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>

<?php
}
include_once(__DIR__ .'/../footer.php');
?>