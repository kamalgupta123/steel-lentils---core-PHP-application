<?php 
include_once(__DIR__ .'/header.php');
$Encryption = new Encryption();
$product_ranges = send_rest(array(
	"function" => "Customers/getProductRanges",
	"product_range_type" => 1
));
$product_ranges_irregular = send_rest(array(
	"function" => "Customers/getProductRanges",
	"product_range_type" => 2
));
?>
<script>
	$(document).ready(function(){
		$(".AddToCart").validationEngine({
			promptPosition: "topRight:-90"
		});
		$(".AddIrregularToCart").validationEngine({
			promptPosition: "topRight:-90"
		});
		var spinner = $( ".spinner" ).spinner();
	    $('.select2').select2();
	});
</script>
<div class="banner-div">
	<div class="container">
		<div class="upper-banner-div">
			<div class="banner-content">
				<h3 class="banner-head">Structural Steel Specialists</h3>
				<div class="banner-text">Building with steel across Melbourne</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<h4 class="text-center lower-banner-head">Dowcon Steel offers next day delivery on our stocked hot dipped galvanised Lintels in suburban melbourne as long as your order is submitted by 10 am</h4>
	<div class="row mt-5 banner-row">
		<div class="col-md-4 text-center">
			<img src="<?php echo RESOURCES_URL; ?>/images/hand.png" class="hand-icon">
			<div class="hand-icon-text">To keep costs low buy stock sizes in stock lengths</div>
		</div>
		<div class="col-md-4 text-center secondary-col">
			<img src="<?php echo RESOURCES_URL; ?>/images/van.png" class="hand-icon">
			<div class="hand-icon-text">Free crane truck delivery on orders over $500.</div>
		</div>
		<div class="col-md-4 text-center secondary-col">
			<img src="<?php echo RESOURCES_URL; ?>/images/coins.png" class="hand-icon">
			<div class="hand-icon-text">Free crane truck delivery on orders over $500.</div>
		</div>
	</div>
</div>
<div class="stock-section">
	<div class="container">
		<h4 class="text-center stock-head">Stock sections and lengths (same day or next day delivery)</h4>
			<div class="inner-stock-section mt-4">
			<?php if(!empty($product_ranges['data'])){
			    foreach($product_ranges['data'] as $product_range) { ?>	
				<h5 class="inner-stock-head"><?php echo $product_range['product_range_name'];?></h5>
				<div class="row inner-row mt-4">
					<?php 
					$sections = send_rest(array(
						"function" => "Customers/getSections",
						"product_range_id" => $product_range['product_range_id'],
						"product_range_type" => 1
					));
					foreach($sections['data'] as $section) {
					?>
					<div class="col-md-6 mt-4">
						<div class="dropdown angle-dropdown">
							<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php 
									echo $section['section_name'];
									$items = send_rest(array(
										"function" => "Customers/getItems",
										"section_id" => $section['section_id']
									));
									$percentage = send_rest(array(
										"function" => "Customers/getMetaValue"
									));
									$gst_percentage = $percentage['data']['meta_value'];
							?>
							</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<?php foreach($items['data'] as $item) { 
									$gst = ($gst_percentage / 100) * $item['price'];
								?>
								<form class="AddToCart" enctype="multipart/form-data" autocomplete="off">
    								<div class="dropdown-item" >
    									<div class="row amount-row"> 
    										<div class="col-lg-5 amount-col"><span><?php echo $item['length'];?> - $<?php echo $item['price']+$gst; ?> + GST</span></div>
    										<div class="col-lg-5 space-col"><input type="text" name="quantity" placeholder="Quantity" class="quantity-input validate[required] custom[integer] min[1]"></div>
    										<div class="col-lg-2"><button class="btn truck-btn theme-btn add-to-cart" data-item-id="<?php echo $Encryption->encode($item['item_id']); ?>" data-custom="0"><img src="<?php echo RESOURCES_URL; ?>/images/mini-truck.png"></button></div>
    									</div>
    								</div>
								</form>
									<!-- <a class="dropdown-item" href="#"><?php //echo $item['item_id']; ?></a> -->
								<?php } ?>
								<?php if($product_range['is_custom_length_allowed']==1){ ?>
									<form class="AddToCart" enctype="multipart/form-data" autocomplete="off">
										<div class="dropdown-item" >
											<div class="row amount-row"> 
												<div class="col-lg-5 amount-col"><input type="text" name="length" placeholder="Enter Custom Length" class="quantity-input validate[required] custom[integer] min[1]"></div>
												<div class="col-lg-5 space-col"><input type="text" name="quantity" placeholder="Quantity" class="quantity-input validate[required] custom[integer] min[1]"></div>
												<div class="col-lg-2"><button class="btn truck-btn theme-btn add-to-cart" data-item-id="<?php echo $Encryption->encode($section['section_id']); ?>" data-custom="1"><img src="<?php echo RESOURCES_URL; ?>/images/mini-truck.png"></button></div>
											</div>
										</div>
									</form>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php } ?>
					</div>
					<?php } }else{ ?>
					    <div class='no_records_div'>No records to display</div>
					<?php } ?>
		</div>
	</div>
</div>
<div class="stock-section stock-section2">
	<div class="container">
		<h4 class="text-center stock-head">Irregular sections and lengths - charged as 6000mm long lengths plus cuts (5-7 working day lead time)</h4>
			<div class="inner-stock-section irregular-section mt-4">
				<div class="row m-0">
				<?php
				if(!empty($product_ranges_irregular['data'])){
				foreach($product_ranges_irregular['data'] as $product_range_irregular) { ?>
					<div class="col-lg-6 equal-col">
						<h4 class=""><?php echo $product_range_irregular['product_range_name'];?></h4>
						<hr class="angle-line">
						<?php
							$sections_irregular = send_rest(array(
								"function" => "Customers/getSections",
								"product_range_id" => $product_range_irregular['product_range_id'],
								"product_range_type" => 2
							));
							foreach($sections_irregular['data'] as $section_irregular) {
								// $items_irregular = send_rest(array(
									// "function" => "Customers/getItems",
									// "section_id" => $section_irregular['section_id']
								// ));
								// foreach($items_irregular['data'] as $item_irregular) {
								
						?>
						<form class="AddIrregularToCart" enctype="multipart/form-data" autocomplete="off">
    						<div class="row amount-row last-row angle-row pb-0"> 
    							<div class="col-sm-2 amount-col"><span><?php echo $section_irregular['section_name']; ?></span></div>
    							<div class="col-5 col-sm-4 length-right pr-0"><input type="text" name="length" placeholder="Add Length" class="quantity-input validate[required] custom[integer] min[1]"></div>
    							<div class="col-sm-4 col-5 col-sm-4 col-5 col-sm-4 length-left"><input type="text" name="quantity" placeholder="Quantity" class="quantity-input validate[required] custom[integer] min[1]"></div>
    							<div class="col-sm-2 col-2 pl-0"><button class="btn truck-btn theme-btn add-irregular-to-cart" data-section-id="<?php echo $Encryption->encode($section_irregular['section_id']); ?>"><img src="<?php echo RESOURCES_URL; ?>/images/mini-truck.png"></button></div>
    						</div>
    					</form>
						<hr>
						<?php //} ?>
						<?php } ?>
					</div> 
					<?php } }else{ ?>
					    <div class='no_records_div'>No records to display</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php 
include_once(__DIR__ .'/footer.php');
?>
