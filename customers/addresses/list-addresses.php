<?php 
include_once(__DIR__ ."/../../header.php");
include_once(dirname(__FILE__)."/../../site_config.php");
$Encryption = new Encryption();
if(empty($_SESSION['sl_user']['user_id'])){
    header('Location: '.SITE_URL."/login.php");
}
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
	"function" => "Admin/ListAddresses",
	"page_no" => $request["PageNumber"],
	"row_size" => $request["RowSize"],
	"sort_on" => $request["SortOn"],
	"sort_type" => $request["SortType"],
	"customer_id" => $_SESSION['sl_user']['user_id'],
	"search" => $search
));
$total_records = $result["data"]["total_records"];
$total_pages = $result["data"]["total_pages"];
$pagging_list = $result["data"]["pagging_list"];
$tabs_link = $request;
?>
<div class="col-lg-9">
	<div class="row">
		<div class="col-md-3 col-3">
			<h4 class="order-head">Address</h4>
		</div>
		<div class="col-md-5 col-5">
			<form id='SearchAddress'>
					<?php 
					$p_request = $request;
					if(isset($p_request['search_col'])){
						unset($p_request['search_col']);
					}
					?>
					<input type='hidden' id='reload_url' value='<?php echo SITE_URL."/customers/addresses/list-addresses.php?".http_build_query($p_request); ?>'>
					<div class="search-addresses input-group search-group">
						<div class="input-group-prepend">
							<button type='submit' class="input-group-text" id="search_input"><i class="fas fa-search"></i></button>
						</div>
						<input type="text" class="form-control" id="search_col" placeholder="Search Address" aria-describedby="inputGroupPrepend2" value='<?php echo (!empty($_GET['search_col']))?safe_output($_GET['search_col']):""; ?>'>
					</div>
			</form>
		</div>
		<div class="col-md-2 col-2">
			<button class="btn btn-danger delete-margin load_ajax delete_selected" disabled><i class="fas fa-minus-circle"></i> Delete </button>
		</div>
		<div class="col-md-2 col-2 text-right">
			<a class="btn theme-btn load_ajax"  href="<?php echo SITE_URL."/customers/addresses/detail-addresses.php?referer=".rawurlencode(SITE_URL."/customers/addresses/detail-addresses.php/?".http_build_query($request)); ?>" > <i class="fas fa-plus-circle"></i> Add Address</a>
		</div>
	</div>
	<!-- <div class="order-filter">
		<div class="row">
			<div class="col-md-4 col-sm-6 filter-col">
				<div class="form-group">
					<label for="exampleFormControlSelect1">Filter Address</label>
					<select class="form-control" id="exampleFormControlSelect1">
						<option>Select</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
					</select>
				</div>
			</div>
		</div>
	</div> -->
	<div class="table-responsive mt-3 table-margin">
		<?php
		if($total_records==0){
		?>
		<div class="no_records_div">No records to display.</div>
		<?php
		}
		else{
			$pagination_link = $request;
		?>
		<table class="table order-table">
			<thead>	
				<tr>
					<th><input type="checkbox" name="" id="single_checkbox_event"></th>
					<?php $sort_data = get_sort_data($request, "street_address1"); ?>
					<th class="sorting" data-SortOn="street_address1" scope="col"><a class="load_ajax" href="<?php echo SITE_URL."/customers/addresses/list-addresses.php/?".http_build_query($sort_data->link); ?>">Address<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
					<?php $sort_data = get_sort_data($request, "is_buisness_address"); ?>
					<th class="sorting" data-SortOn="is_buisness_address" scope="col"><a class="load_ajax" href="<?php echo SITE_URL."/customers/addresses/list-addresses.php/?".http_build_query($sort_data->link); ?>">BuisnessAddress<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
					<?php $sort_data = get_sort_data($request, "city"); ?>
					<th class="sorting" data-SortOn="city" scope="col"><a class="load_ajax" href="<?php echo SITE_URL."/customers/addresses/list-addresses.php/?".http_build_query($sort_data->link); ?>">City<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
					<?php $sort_data = get_sort_data($request, "state"); ?>
					<th class="sorting" data-SortOn="state" scope="col"><a class="load_ajax" href="<?php echo SITE_URL."/customers/addresses/list-addresses.php/?".http_build_query($sort_data->link); ?>">State<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
					<?php $sort_data = get_sort_data($request, "zip"); ?>
					<th class="sorting" data-SortOn="zip" scope="col"><a class="load_ajax" href="<?php echo SITE_URL."/customers/addresses/list-addresses.php/?".http_build_query($sort_data->link); ?>">Postcode<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
					<?php $sort_data = get_sort_data($request, "created_on"); ?>
					<th class="sorting" data-SortOn="created_on" scope="col"><a class="load_ajax" href="<?php echo SITE_URL."/customers/addresses/list-addresses.php/?".http_build_query($sort_data->link); ?>">Created On<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
					<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
					foreach($pagging_list as $pagg_row){
						$PkId = "'".$Encryption->encode($pagg_row['customer_address_id'])."'";
						$PkId2 = $Encryption->encode($pagg_row['customer_address_id']);						
					?>
					<tr id="Trow_<?php echo $pagg_row["customer_address_id"]; ?>">
						<td><input type="checkbox" name="" data-address-id=<?php echo  $Encryption->encode($pagg_row["customer_address_id"]); ?> class="checkboxes-del"></td>
						<td><?php echo $pagg_row["street_address1"]." ".$pagg_row["street_address2"]; ?></td>
						<td><span class="<?php if($pagg_row['is_buisness_address']=='1'){ echo "badge badge-success";}else{ echo "badge badge-danger";}?>"><?php if($pagg_row['is_buisness_address']=='1'){ echo "Enabled";}else{ echo "Disabled";}?></span></td>
						<td><?php echo $pagg_row["city"]; ?></td>
						<td><?php echo $pagg_row["state"]; ?></td>
						<td><?php echo $pagg_row['zip']; ?></td>
						<td><?php echo ui_datetime($pagg_row['created_on']); ?></td>
						<td><a class="load_ajax edit_button" title="Edit" href="<?php echo SITE_URL."/customers/addresses/detail-addresses.php/?customer_address_id=".$PkId2."&referer=".rawurlencode(SITE_URL."/customer/addresses/list-addresses.php/?".http_build_query($request)); ?>" ><i class="fa fa-edit"></i></a></td>
					</tr> 
				<?php 
					} 
				?>
			</tbody>
		</table>
		<?php 
			get_pagination($request, $total_pages, $total_records, "/customers/addresses/list-addresses.php", $pagging_list);
			}
		?>
	</div>
</div>
<?php 
include_once(__DIR__ ."/../../footer.php");
?>