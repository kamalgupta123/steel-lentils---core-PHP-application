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
if(!empty($_GET['search_col'])){
	$search=trim($_GET['search_col']);
}
$result = send_rest(array(
	"function" => "Admin/ListTrucks",
	"page_no" => $request["PageNumber"],
	"row_size" => $request["RowSize"],
	"sort_on" => $request["SortOn"],
	"sort_type" => $request["SortType"],
	"search" => $search
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
			            <div>Trucks</div>
			        </div>
			        <div class="col-8 text-right section-col">
			           	<div class="text-right add-btn">
					<a class="btn theme-btn load_ajax"  href="<?php echo ADMIN_URL."/trucks/detail-trucks.php?referer=".rawurlencode(ADMIN_URL."/trucks/list-trucks.php/?".http_build_query($request)); ?>" > <i class="fas fa-plus-circle"></i> Add New Truck</a>
				</div>
				</div>
			    </div> 
			</div>
			<div class="table-div mt-1">
				<div class="row">
					<div class="col-6 col-md-4 col-lg-3">
						<form id='SearchTrucks'>
							<?php 
							$p_request = $request;
							if(isset($p_request['search_col'])){
								unset($p_request['search_col']);
							}
							?>
							<input type='hidden' id='reload_url' value='<?php echo ADMIN_URL."/trucks/list-trucks.php?".http_build_query($p_request); ?>'>
								<div class="search-trucks input-group search-group">
									<div class="input-group-prepend">
										<button type='submit' class="input-group-text" id="search_input"><i class="fas fa-search"></i></button>
									</div>
									<input type="text" class="form-control" id="search_col" placeholder="Search Trucks" aria-describedby="inputGroupPrepend2" value='<?php echo (!empty($_GET['search_col']))?safe_output($_GET['search_col']):""; ?>'>
								</div>
						</form>
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
									<?php $sort_data = get_sort_data($request, "registration_number"); ?>
									<th class="sorting" data-SortOn="registration_number" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/trucks/list-trucks.php/?".http_build_query($sort_data->link); ?>">Vehicle Registration No.<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "is_enabled"); ?>
									<th class="sorting" data-SortOn="is_enabled" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/trucks/list-trucks.php/?".http_build_query($sort_data->link); ?>">Status<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "created_on"); ?>
									<th class="sorting" data-SortOn="created_on" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/trucks/list-trucks.php/?".http_build_query($sort_data->link); ?>">Created On<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<th scope="col">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($pagging_list as $pagg_row){
									$PkId = "'".$Encryption->encode($pagg_row['truck_id'])."'";
									$PkId2 = $Encryption->encode($pagg_row['truck_id']);									
									?>
									<tr id="Trow_<?php echo $pagg_row["truck_id"]; ?>">
										<td><?php echo $pagg_row["registration_number"];?></td>
										<td> 
											<div class="mobile-wrapper toggleWrapper">
													<input type="checkbox" name="is_enabled" class="mobileToggle show_msg_by_toggle _toggleTrucks" id="is_enabled_<?php echo  $Encryption->encode($pagg_row["truck_id"]); ?>" value="1" <?php if($pagg_row['is_enabled']==0){ } else{ echo"checked"; } ?> data-truck-id="<?php echo  $Encryption->encode($pagg_row["truck_id"]); ?>">
													<label for="is_enabled_<?php echo  $Encryption->encode($pagg_row["truck_id"]); ?>"></label>
											</div>
										</td>
										<td><?php echo ui_datetime($pagg_row["created_on"]); ?></td>
										<td class="actions_td">
											<a class="load_ajax edit_button" title="Edit" href="<?php echo ADMIN_URL."/trucks/detail-trucks.php/?truck_id=".$PkId2."&referer=".rawurlencode(ADMIN_URL."/trucks/list-trucks.php/?".http_build_query($request)); ?>" ><i class="fa fa-edit"></i></a>
										</td>
									</tr>
								<?php 
								} 
								?>
							</tbody>
						</table>
					<?php 
						get_pagination($request, $total_pages, $total_records, "/admin/customers/list-customers.php", $pagging_list);
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