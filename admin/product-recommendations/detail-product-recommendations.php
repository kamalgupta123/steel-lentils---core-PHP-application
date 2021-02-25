<?php
include_once(__DIR__ .'/../header.php');
include_once(ADMIN_DIR . "/admin-functions.php");

$error = 0;
$Encryption = new Encryption(); 
if(isset($_GET["product_recommendation_id"])){
	$product_recommendation_id = $Encryption->decode($_GET["product_recommendation_id"]);
	
	$result = send_rest(array(
		"function" => "Admin/GetProductRecommendationDetails",
		"product_recommendation_id" => $product_recommendation_id
	));
	
	if(empty($result['status'])){
		$error = 1;
		echo '<div class="alert alert-danger" style="padding:10px;margin:10px">Failed to get details</div>';
	}
	else{
		$d_row2 = $result['data'];
	}
}	
$result = send_rest(array(
	"function" => "Admin/GetIrregularSections"
));

if(!empty($result['data'])){
	$d_row = $result['data'];
}
$result1 = send_rest(array(
	"function" => "Admin/GetStockedSections"
));

if(!empty($result1['data'])){
	$d_row1 = $result1['data'];
}

if($error==0){	
?>

<script>
	$(document).ready(function(){
		$("#ProductRecommendations1").validationEngine({
			promptPosition: "topRight:-90"
		});
		var spinner = $( ".spinner" ).spinner();
		$('.select2').select2();
	});
</script>
<div class="container-fluid">
	<div class="row head-row">
		<div class="col-md-12">
			<div class="heading-div "><?php if(!empty($d_row2)){ echo 'Edit';}else{ echo "Add";} ?> Product Recommendation</div>
			<div class="row owner-row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">
						    <?php $back_url=""; if(isset($_GET['referer'])){ $back_url = $_GET['referer']; } ?>
							<form id="ProductRecommendations1" data-new-key="product_recommendation_id" class="submit_ajax" data-action-url="<?php echo ADMIN_URL."/product-recommendations/product-recommendations-ajax.php";?>" data-add-url="<?php echo ADMIN_URL."/product-recommendations/detail-product-recommendations.php/?referer=".rawurlencode($back_url); ?>" data-back-url="<?php echo $back_url; ?>" enctype="multipart/form-data" autocomplete="off">
								<div class="row ">
									<div class="form-group col-lg-4 col-md-6">
										<input type="hidden" value="<?php if(!empty($_GET["product_recommendation_id"])){echo $_GET["product_recommendation_id"];} ?>" name="product_recommendation_id">
										<input type="hidden" value="SaveProductRecommendations1" name="action">
										<label for="selected_irregular_section">Select Irregular Section<span class="required_star"> *</span></label>
										<select id="selected_irregular_section" class="form-control select2 validate[required]" name="states[]" multiple="multiple">
											<option value="">Select</option>
											<?php
											$selected_sections=array();
											if(!empty($d_row2)){
												// get selected options 
												$get_irregular_sections = send_rest(
													array(
														"function"=>"Admin/GetIrregularSectionsforRecommendation",
														"product_recommendation_id"=>$d_row2["product_recommendation_id"]
													)
												);
												if(!empty($get_irregular_sections['data'])){
													foreach($get_irregular_sections['data'] as $selected_section){
														$selected_sections[] = $selected_section['irregular_section_id'];
													}
												}
											}
											if(!empty($d_row)){
												foreach($d_row as $pagg_row){
												?>
													<option value="<?php echo $Encryption->encode($pagg_row['section_id']); ?>" <?php echo (in_array($pagg_row['section_id'],$selected_sections))?"selected":""; ?>><?php echo $pagg_row['section_name'] ?></option>
												<?php
												}
											}
											?>
										</select>
									</div>
									<div class="form-group col-lg-4 col-md-6">
										<label for="selected_length_range">Select Length Range<span class="required_star"> *</span></label>
										<div class="row">
											<div class="col-lg-6">
												<input type="text" class="form-control validate[required]" id="from" name="from" value="<?php echo (!empty($d_row2))?$d_row2["length_from"]:""; ?>" placeholder="from">
											</div>
											<div class="col-lg-6">
												<input type="text" class="form-control validate[required]" id="to" name="to" value="<?php echo (!empty($d_row2))?$d_row2["length_to"]:""; ?>" placeholder="to">
											</div>
										</div>		
									</div>
									<div class="form-group col-lg-4 col-md-6">
										<label for="suggested_stocked_section">Suggested Stocked Section<span class="required_star"> *</span></label>
										<select id="suggested_stocked_section" data-pid="0" name="suggested_stocked_section" class="form-control select2 validate[required]">
											<option value="">Select</option>
											<?php
											if(!empty($d_row1)){
												foreach($d_row1 as $pagg_row){
											?>
												<option value="<?php echo $Encryption->encode($pagg_row['section_id']); ?>" <?php if(!empty($d_row2)){ echo ($pagg_row['section_id']==$d_row2["recommended_section_id"])?"selected":""; } ?>><?php echo $pagg_row['section_name']; ?></option>
											<?php
												}
											}
											?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-lg-4 col-md-6">
										<label for="suggested_stocked_length">Suggested Stocked Length<span class="required_star"> *</span>
										</label>
										<select id="suggested_stocked_length3" name="suggested_stocked_length" class="form-control select2 length_0 validate[required]">
											<option value="">Select</option>
											<?php 
											if(!empty($d_row2)){
												// get items for selected section 
												$get_items = send_rest(
													array(
														"function"=>"Admin/GetItemsForSections",
														"suggested_stocked_section"=>$d_row2["recommended_section_id"]
													)
												);
												if(!empty($get_items['data'])){
													foreach($get_items['data'] as $item){
														?>
														<option <?php echo ($d_row2["recommended_item_id"]==$item['item_id'])?"selected":""; ?> value="<?php echo $Encryption->encode($item['item_id']); ?>"><?php echo $item['length']; ?></option>
													<?php 
													}
												}
											}
											?>
										</select>
									</div>
									<div class="form-group col-lg-4 col-md-6">
										<label for="created_on">Created On</label>
										<input type="text" class="form-control " id="created_on" name="created_on" placeholder="" value="<?php if(!empty($d_row2)){ echo ui_datetime($d_row2['created_on']); }?>" disabled>
									</div>
									<div class="form-group col-lg-4 col-md-6">
										<label for="modified_on">Modified On</label>
										<input type="text" class="form-control " id="modified_on" name="modified_on" placeholder="" value="<?php if(!empty($d_row2['modified_on'])){ echo ui_datetime($d_row2['modified_on']); }?>" disabled>
									</div>
								</div>
								<div class='row'>
									<div class="form-group col-md-6 col-lg-6">
										<div class="toggleWrapper mt-0">
											<input type="checkbox" name="is_enabled" class="mobileToggle  show_msg_by_toggle" id="is_enabled" value="1" <?php if(!empty($d_row2) && $d_row2['is_enabled']==0){} else {echo"checked";}?>>
											<label for="is_enabled"><span id="show_label" class="secondary-label"><?php if(!empty($d_row2) && $d_row2['is_enabled']==0){ echo "Click to Enable";} else echo "Click to Disable";?></span></label>
										</div>
									</div>
								</div>
								<button type="submit" class="btn theme-btn float-md-right save_btn_action" data-form-id="ProductRecommendations1">Save Changes</button>
								<button type="button" class="cancel_btn_action cancel-btn btn btn-default">Cancel</button>
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