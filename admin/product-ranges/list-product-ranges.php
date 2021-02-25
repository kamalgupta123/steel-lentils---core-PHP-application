<?php 
include_once(__DIR__ . "/../header.php");
include_once(ADMIN_DIR . "/admin-functions.php");

$Encryption = new Encryption();
if(empty($_GET['PageNumber'])){ $_GET['PageNumber'] = 1; }
if(empty($_GET['RowSize'])){ $_GET['RowSize'] = 25; }
if(empty($_GET['SortOn'])){ $_GET['SortOn'] = 's.created_on'; }
if(empty($_GET['SortType'])){ $_GET['SortType'] = 'DESC'; }
$request = $_GET;
$search='';
$product_range_type='';
if(!empty($_GET['search_col'])){
    $search=trim($_GET['search_col']);
}
if(!empty($_GET['product_range_type'])){
	$product_range_type=$_GET['product_range_type'];
}
//echo $search;
$result = send_rest(array(
	"function" => "Admin/ListProductRanges",
	"page_no" => $request["PageNumber"],
	"row_size" => $request["RowSize"],
	"sort_on" => $request["SortOn"],
	"sort_type" => $request["SortType"],
	"search" => $search,
	"product_range_type" => $product_range_type
));
$total_records = $result["data"]["total_records"];
$total_pages = $result["data"]["total_pages"];
$pagging_list = $result["data"]["pagging_list"];
$tabs_link = $request;
?>
<div class="container-fluid">
    <div class="row head-row">
        <div class="col-md-12">
			<div class="heading-div ">
			    <div class="row">
			        <div class="col-4">
			            <div>Product Ranges</div>
			        </div>
			        <div class="col-8 text-right section-col">
			           	<div class="text-right add-btn">
					<a class="btn theme-btn load_ajax"  href="<?php echo ADMIN_URL."/product-ranges/detail-product-ranges.php?referer=".rawurlencode(ADMIN_URL."/product-ranges/list-product-ranges.php/?".http_build_query($request)); ?>" > <i class="fas fa-plus-circle"></i> Add new Product Range</a>
				    </div>
			        </div>
			        </div> 
			</div>
			<div class="table-div mt-1">
			    <form id='SearchProducts'>
			    <div class="row">
				<div class="col-12 col-sm-auto">
				
						<?php 
						$p_request = $request;
						if(isset($p_request['search_col'])){
							unset($p_request['search_col']);
						}
						?>
						<input type='hidden' id='reload_url' value='<?php echo ADMIN_URL."/product-ranges/list-product-ranges.php?".http_build_query($p_request); ?>'>
						<div class="product-range input-group search-group">
							<div class="input-group-prepend">
								<button type='submit' class="input-group-text" id="search_input"><i class="fas fa-search"></i></button>
							</div>
							<input type="text" class="form-control" id="search_col" placeholder="Search Product Range" aria-describedby="inputGroupPrepend2"  value='<?php echo (!empty($_GET['search_col']))?safe_output($_GET['search_col']):""; ?>'>
						</div>
					
					</div>
				<!--<div class="col-2"></div>-->
			    <div class="col-12 col-sm-auto pl-sm-0">
			        <div class="form-group range-group">
						<select class="select2 form-control" id="product_range_filter" name="product_range">
							<option value=''>Select Product Range Type</option>
							<option value='3' <?php if($product_range_type==3){echo "selected";}?>>All</option>
							<option value='2' <?php if($product_range_type==2){echo "selected";}?>>Irregular</option>
							<option value='1' <?php if($product_range_type==1){echo "selected";}?>>Stocked</option>
						</select>
					</div>
			    </div>
						</div>
						</form>
						<div class="row">
						    <div class="col-12">
				<div class="table-responsive-lg mt-3">
					<?php
					if($total_records==0){
						?>
						<div class="no_records_div">No records to display.</div>
					<?php
					}
					else{
						$pagination_link = $request;
						?>
						<table class="table customer-table">
							<thead class="">
								<tr>
									<?php $sort_data = get_sort_data($request, "product_range_name"); ?>
									<th class="sorting" data-SortOn="product_range_name" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/product-ranges/list-product-ranges.php/?".http_build_query($sort_data->link); ?>">Product Range Name<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "product_range_type"); ?>
									<th class="sorting" data-SortOn="product_range_type" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/product-ranges/list-product-ranges.php/?".http_build_query($sort_data->link); ?>">Product Range Type<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "s.created_on"); ?>
									<th class="sorting" data-SortOn="s.created_on" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/product-ranges/list-product-ranges.php/?".http_build_query($sort_data->link); ?>">Created Date<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "is_enabled"); ?>
									<th class="sorting" data-SortOn="is_enabled" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/product-ranges/list-product-ranges.php/?".http_build_query($sort_data->link); ?>">Status<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<th scope="col">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($pagging_list as $pagg_row){
									$PkId = "'".$Encryption->encode($pagg_row['product_range_id'])."'";
									$PkId2 = $Encryption->encode($pagg_row['product_range_id']);									
									?>
									<tr id="Trow_<?php echo $pagg_row["product_range_id"]; ?>">
										<td><?php echo getExcerpt($pagg_row["product_range_name"],"40"); ?></td>
										<td><?php if($pagg_row['product_range_type']=='2'){ echo 'Irregular';}else{echo 'Stocked';} ?></td>
										<td><?php echo ui_datetime($pagg_row['created_on']); ?></td>
										<td>
											<div class="mobile-wrapper toggleWrapper">
													<input type="checkbox" name="is_enabled" class="mobileToggle show_msg_by_toggle _toggleProductRange" id="is_enabled_<?php echo  $Encryption->encode($pagg_row["product_range_id"]); ?>" value="1" <?php if($pagg_row['is_enabled']==0){ } else{ echo"checked"; } ?> data-product-range-id="<?php echo  $Encryption->encode($pagg_row["product_range_id"]); ?>">
													<label for="is_enabled_<?php echo  $Encryption->encode($pagg_row["product_range_id"]); ?>"></label>
											</div>
										</td>
										<td class="actions_td">
											<a class="load_ajax edit_button" title="Edit" href="<?php echo ADMIN_URL."/product-ranges/detail-product-ranges.php/?product_range_id=".$PkId2."&referer=".rawurlencode(ADMIN_URL."/product-ranges/list-product-ranges.php/?".http_build_query($request)); ?>" ><i class="fa fa-edit"></i></a>
										</td>
									</tr>
								<?php 
								} 
								?>
							</tbody>
						</table>
					<?php 
						get_pagination($request, $total_pages, $total_records, "/admin/product-ranges/list-product-ranges.php", $pagging_list);
					}
					?>
				</div>
				</div>
				</div>
			</div>
			<div class="copyright-text mt-4">Copyrights 2020, Dowcon</div>
		</div>
	</div>
 </div>
<?php
include_once(ADMIN_DIR . "/footer.php");
?>