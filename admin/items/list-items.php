<?php 
include_once(__DIR__ . "/../header.php");
include_once(ADMIN_DIR . "/admin-functions.php");
$Encryption = new Encryption();
if(empty($_GET['PageNumber'])){ $_GET['PageNumber'] = 1; }
if(empty($_GET['RowSize'])){ $_GET['RowSize'] = 25; }
if(empty($_GET['SortOn'])){ $_GET['SortOn'] = 'i.created_on'; }
if(empty($_GET['SortType'])){ $_GET['SortType'] = 'DESC'; }
$request = $_GET;
$search='';
$product_range='';
$section='';
if(!empty($_GET['search_col'])){
	$search=trim($_GET['search_col']);
}
if(!empty($_GET['product_range'])){
	$product_range=$Encryption->decode(trim($_GET['product_range']));
}
if(!empty($_GET['section'])){
	$section=$Encryption->decode(trim($_GET['section']));
}
$result = send_rest(array(
	"function" => "Admin/ListItems",
	"page_no" => $request["PageNumber"],
	"row_size" => $request["RowSize"],
	"sort_on" => $request["SortOn"],
	"sort_type" => $request["SortType"],
	"search" => $search,  
	"product_range" => $product_range,
	"section" => $section
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
			            <div>Items</div>
			        </div>
			        <div class="col-8 text-right section-col">
			           	<div class="text-right add-btn">
	           	    <button class="btn btn-danger load_ajax delete_selected_btn" disabled><i class="fas fa-minus-circle"></i> Delete </button>
					<a class="btn theme-btn load_ajax"  href="<?php echo ADMIN_URL."/items/detail-items.php?referer=".rawurlencode(ADMIN_URL."/items/list-items.php/?".http_build_query($request)); ?>" > <i class="fas fa-plus-circle"></i> Add new Item</a>
				</div>
				</div>
			    </div> 
			</div>
			<div class="table-div mt-1">
				<div class="row">
					<div class="col-6 col-md-4 col-lg-3">
						<form id='SearchProducts'>
							<?php 
							$p_request = $request;
							if(isset($p_request['search_col'])){
								unset($p_request['search_col']);
							}
							?>
							<input type='hidden' id='reload_url' value='<?php echo ADMIN_URL."/items/list-items.php?".http_build_query($p_request); ?>'>
								<div class="search-items input-group search-group">
									<div class="input-group-prepend">
										<button type='submit' class="input-group-text" id="search_input"><i class="fas fa-search"></i></button>
									</div>
									<input type="text" class="form-control" id="search_col" placeholder="Search Item" aria-describedby="inputGroupPrepend2" value='<?php echo (!empty($_GET['search_col']))?safe_output($_GET['search_col']):""; ?>'>
								</div>
						</form>
					</div>
					<div class="col-6 col-md-4 col-lg-3">
						<?php 
							$result=send_rest(
								array(
									"function"=>"Admin/GetProductRangesList"
								)
							);
						?>
						<select class="select2 form-control" id="product_range_filter_in_items" name="product_range">
							<option value=''>Select Product Range</option>
							<?php 
							if(!empty($result['data'])){
								foreach($result['data'] as $range){
									?>
									<option value="<?php echo $Encryption->encode($range['product_range_id']); ?>" <?php if(!empty($_GET['product_range'])){ echo ($range['product_range_id']==$Encryption->decode($_GET['product_range']))?"selected":""; } ?>><?php if($range['product_range_type']==1){ echo "Stocked - "; }elseif($range['product_range_type']==2){ echo "Irregular - "; }else{echo "";} ?><?php echo $range['product_range_name']; ?></option>
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
					<div class="col-12 col-md-4 col-lg-3">
						<?php 
							$res_section=send_rest(
								array(
									"function"=>"Admin/GetSectionList"
								)
							);
						?>
						<select class="select2 form-control" id="section_filter_in_items" name="section">
							<option value=''>Select Sections</option>
							<?php 
							if(!empty($res_section['data'])){
								foreach($res_section['data'] as $sections){
									?>
									<option value="<?php echo $Encryption->encode($sections['section_id']); ?>" <?php if(!empty($_GET['section'])){ echo ($sections['section_id']==$Encryption->decode($_GET['section']))?"selected":""; } ?>><?php echo $sections['section_name']; ?></option>
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
				</div>
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
								    <th><input type="checkbox" name="" id="single_checkbox_event"></th>
									<?php $sort_data = get_sort_data($request, "section_name"); ?>
									<th class="sorting" data-SortOn="section_name" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/items/list-items.php/?".http_build_query($sort_data->link); ?>">Section Name<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "product_range_name"); ?>
									<th class="sorting" data-SortOn="product_range_name" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/items/list-items.php/?".http_build_query($sort_data->link); ?>">Product Range Name<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "length"); ?>
									<th class="sorting" data-SortOn="length" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/items/list-items.php/?".http_build_query($sort_data->link); ?>">Length<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "quantity"); ?>
									<th class="sorting" data-SortOn="quantity" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/items/list-items.php/?".http_build_query($sort_data->link); ?>">Quantity<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "created_on"); ?>
									<th class="sorting" data-SortOn="created_on" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/items/list-items.php/?".http_build_query($sort_data->link); ?>">Created On<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "is_enabled"); ?>
									<th class="sorting" data-SortOn="is_enabled" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/items/list-items.php/?".http_build_query($sort_data->link); ?>">Status<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<th scope="col">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($pagging_list as $pagg_row){
									$PkId = "'".$Encryption->encode($pagg_row['item_id'])."'";
									$PkId2 = $Encryption->encode($pagg_row['item_id']);									
									?>
									<tr id="Trow_<?php echo $pagg_row["item_id"]; ?>">
									    <td><input type="checkbox" name="" data-item-id=<?php echo  $Encryption->encode($pagg_row["item_id"]); ?> class="checkboxes-del"></td>
										<td><?php echo $pagg_row["section_name"]; ?></td>
										<td><?php echo $pagg_row["product_range_name"]; ?></td>
										<td><?php echo $pagg_row["length"]; ?></td>
										<td><?php echo $pagg_row["quantity"]; ?></td>
										<td><?php echo ui_datetime($pagg_row['created_on']); ?></td>
										<td>
											<input type="checkbox" name="is_enabled" data-item-id=<?php echo  $Encryption->encode($pagg_row["item_id"]); ?> class="mobileToggle _toggle" id="is_enabled"  value="1" <?php if($pagg_row['is_enabled']==0){ } else echo"checked";?>>
											<div class="mobile-wrapper toggleWrapper">
                                                <input type="checkbox" name="is_enabled" class="mobileToggle show_msg_by_toggle _toggleProductRange" id="is_enabled_<?php echo  $Encryption->encode($pagg_row["item_id"]); ?>" value="1" <?php if($pagg_row['is_enabled']==0){ } else{ echo "checked"; } ?> data-item-id="<?php echo  $Encryption->encode($pagg_row["item_id"]); ?>">
                                                <label for="is_enabled_<?php echo  $Encryption->encode($pagg_row["item_id"]); ?>"></label>
                                            </div>	
										</td>
										<td class="actions_td">
											<a class="load_ajax edit_button" title="Edit" href="<?php echo ADMIN_URL."/items/detail-items.php/?item_id=".$PkId2."&referer=".rawurlencode(ADMIN_URL."/items/list-items.php/?".http_build_query($request)); ?>" ><i class="fa fa-edit"></i></a>
										</td>
									</tr>
								<?php 
								} 
								?>
							</tbody>
						</table>
					<?php 
						get_pagination($request, $total_pages, $total_records, "/admin/items/list-items.php", $pagging_list);
					}
					?>
					<br>
					<br>
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