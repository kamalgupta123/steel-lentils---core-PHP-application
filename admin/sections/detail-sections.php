<?php
include_once(__DIR__ .'/../header.php');
include_once(ADMIN_DIR . "/admin-functions.php");

$error = 0;
$Encryption = new Encryption(); 
if(isset($_GET["section_id"])){
	$section_id = $Encryption->decode($_GET["section_id"]);
	
	$result = send_rest(array(
		"function" => "Admin/GetSectionDetails",
		"section_id" => $section_id
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
    	    $('[data-toggle="tooltip"]').tooltip();
    		
    		$("#form_id").validationEngine({
    			promptPosition: "topRight:-90"
    		});
    // 		var spinner = $( ".spinner" ).spinner();
    // 		$('.select2').select2();
    	
    	});
    	</script>
        <div class="container-fluid">
    	<div class="row head-row">
            <div class="col-md-12">
    			<div class="heading-div "><?php if(!empty($d_row)){ echo 'Edit';}else{ echo "Add";} ?> Section</div>
    			    <div class="row owner-row">
    				    <div class="col-lg-12">
    					    <div class="card">
    						    <div class="card-body">
    						        <?php $back_url=""; if(isset($_GET['referer'])){ $back_url = $_GET['referer']; } ?>
    							    <form id="form_id" data-new-key="section_id" class="submit_ajax" data-action-url="<?php echo ADMIN_URL."/sections/sections-ajax.php"; ?>" data-add-url="<?php echo ADMIN_URL."/sections/detail-sections.php/?referer=".rawurlencode($back_url); ?>" data-back-url="<?php echo $back_url; ?>" enctype="multipart/form-data" autocomplete="off" >
    									<input type="hidden" name="action" value="SaveSections">
    									<input type="hidden" name="section_id" value="<?php echo (!empty($d_row))?$Encryption->encode($d_row['section_id']):"";  ?>">
        								<div class="row ">
        									<div class="form-group col-lg-4 col-md-6">
        										<label for="section_name">Section Name<span class="required_star"> *</span></label>
        										<input type="text" class="form-control validate[required]" id="section_name" name="section_name" placeholder="Enter Section Name" value="<?php if(!empty($d_row)){ echo $d_row['section_name']; }?>">
        									</div>
        									<div class="form-group col-lg-4 col-md-6">
        										<label for="product_range">Select Product Range<span class="required_star"> *</span></label>
        										<select class="select2 form-control validate[required]" id="product_range" name="product_range">
													<option value=''>Select</option>
													<?php 
														$selected_product_range_id=0;
														if(!empty($d_row)){
															$selected_product_range_id=$d_row['product_range_id'];
														}
														// get Product Ranges 
														$result=send_rest(
															array(
																"function"=>"Admin/GetProductRangesList",
																"product_range_id"=>$selected_product_range_id
															)
														);
														if(!empty($result['data'])){
															foreach($result['data'] as $range){
																?>
																<option value="<?php echo $Encryption->encode($range['product_range_id']); ?>" <?php if(!empty($d_row)){ echo ($range['product_range_id']==$d_row['product_range_id'])?"selected":""; } ?>><?php if($range['product_range_type']==1){ echo "Stocked - "; }elseif($range['product_range_type']==2){ echo "Irregular - "; }else{echo "";} ?><?php echo $range['product_range_name']; ?></option>
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
												<?php 
												// get selected Product Range details 
												$disable_price_per_cut=1;
												$disable_6000_price=1;
												if(!empty($d_row['product_range_id'])){
													$product_range_details=send_rest(
															array(
																"function"=>"Admin/GetProductRangeDetails",
																"product_range_id"=>$d_row['product_range_id']
															)
														);
													if(!empty($product_range_details['data'])){
														$is_custom_length_allowed=$product_range_details['data']['is_custom_length_allowed'];
														$product_range_type=$product_range_details['data']['product_range_type'];
														if($is_custom_length_allowed==1){
															$disable_price_per_cut=0;
														}
														if($product_range_type==2){
															$disable_6000_price=0;
														}
													}
												}
												?>
        										<label for="price_per_cut" >Price Per Cut <span data-toggle="tooltip" data-placement="top" class="relative" title="This field is only enabled if selected Product Range has allowed custom length."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label> 
        										<div class="price-star">
        										<input type="text" class="form-control validate[custom[number],min[0]]" id="price_per_cut" name="price_per_cut" placeholder="Enter Price Per Cut" value="<?php if(!empty($d_row)){ echo $d_row['price_per_cut']; }?>" <?php if(!empty($disable_price_per_cut)){ echo "disabled"; } ?>>
        										<i class="fas fa-dollar-sign"></i></div>
        									</div>
        								</div>
										<div class='row'>
										    <div class="form-group col-lg-4 col-md-6">
        										<label for="max_length_price" >6000mm Length Price <span data-toggle="tooltip" data-placement="top" class="relative" title="This field is only enabled if selected Product Range is Irregular type."><i class="fa fa-info-circle" aria-hidden="true"></i></span></label> 
        										<div class="price-star">
													<input type="text" class="form-control validate[custom[number],min[0]]" id="max_length_price" name="max_length_price" placeholder="Enter 6000mm Length Price" value="<?php if(!empty($d_row)){ echo $d_row['max_length_price']; }?>" <?php if(!empty($disable_6000_price)){ echo "disabled"; } ?>>
													<i class="fas fa-dollar-sign"></i>
												</div>
        									</div>
											<div class="form-group col-lg-4 col-md-6">
												<label for="created_on">Created On</label>
												<input type="text" class="form-control " id="created_on" name="created_on" placeholder="" value="<?php if(!empty($d_row)){ echo ui_datetime($d_row['created_on']); }?>" disabled>
											</div>
											<div class="form-group col-lg-4 col-md-6">
												<label for="modified_on">Modified On</label>
												<input type="text" class="form-control " id="modified_on" name="modified_on" placeholder="" value="<?php if(!empty($d_row['modified_on'])){ echo ui_datetime($d_row['modified_on']); }?>" disabled>
											</div>
										</div>
										<div class='row'>
											<div class="form-group col-lg-4 col-md-6">
											    <div class="toggleWrapper">
												<input type="checkbox" name="is_enabled" class="mobileToggle show_msg_by_toggle" id="is_enabled"  value="1" <?php if(!empty($d_row) && $d_row['is_enabled']==0){ } else echo"checked";?>>
												<label for="is_enabled"><span id="show_label" class="secondary-label"><?php if(!empty($d_row) && $d_row['is_enabled']==0){ echo "Click to Enable";} else echo "Click to Disable";?></span></label>
												</div>
											</div>
										</div>
        								<button type="submit" class="btn save_btn_action theme-btn float-md-right" data-form-id="form_id">Save Changes</button>
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