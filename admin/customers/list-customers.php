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
$customer_id=0;
if(!empty($_GET['search_col'])){
	$search=trim($_GET['search_col']);
}
if(!empty($_SESSION['sl_admin']['user_id'])){
	$customer_id = $_SESSION['sl_admin']['user_id'];
}
$result = send_rest(array(
	"function" => "Admin/ListCustomers",
	"page_no" => $request["PageNumber"],
	"row_size" => $request["RowSize"],
	"sort_on" => $request["SortOn"],
	"sort_type" => $request["SortType"],
	"customer_id" => $customer_id,
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
			            <div>Customers</div>
			        </div>
			        <div class="col-8 text-right section-col">
			           	<div class="text-right add-btn">
	           	    <button class="btn btn-danger load_ajax delete-customers" disabled><i class="fas fa-minus-circle"></i> Delete </button>
					<a class="btn theme-btn load_ajax"  href="<?php echo ADMIN_URL."/customers/detail-customers.php?referer=".rawurlencode(ADMIN_URL."/customers/list-customers.php/?".http_build_query($request)); ?>" > <i class="fas fa-plus-circle"></i> Add new Customer</a>
				</div>
				</div>
			    </div> 
			</div>
			<div class="table-div mt-1">
				<div class="row">
					<div class="col-6 col-md-4 col-lg-3">
						<form id='SearchCustomers'>
							<?php 
							$p_request = $request;
							if(isset($p_request['search_col'])){
								unset($p_request['search_col']);
							}
							?>
							<input type='hidden' id='reload_url' value='<?php echo ADMIN_URL."/customers/list-customers.php?".http_build_query($p_request); ?>'>
								<div class="search-customers input-group search-group">
									<div class="input-group-prepend">
										<button type='submit' class="input-group-text" id="search_input"><i class="fas fa-search"></i></button>
									</div>
									<input type="text" class="form-control" id="search_col" placeholder="Search Customers" aria-describedby="inputGroupPrepend2" value='<?php echo (!empty($_GET['search_col']))?safe_output($_GET['search_col']):""; ?>'>
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
								    <th><input type="checkbox" name="" id="single_checkbox_event"></th>
									<?php $sort_data = get_sort_data($request, "	first_name"); ?>
									<th class="sorting" data-SortOn="first_name" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/customers/list-customers.php/?".http_build_query($sort_data->link); ?>">Customer Name<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "email"); ?>
									<th class="sorting" data-SortOn="email" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/customers/list-customers.php/?".http_build_query($sort_data->link); ?>">Email<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "phone"); ?>
									<th class="sorting" data-SortOn="phone" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/customers/list-customers.php/?".http_build_query($sort_data->link); ?>">Phone<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "street_address1"); ?>
									<th class="sorting" data-SortOn="street_address1" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/customers/list-customers.php/?".http_build_query($sort_data->link); ?>">Buisness Address<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "business_name"); ?>
									<th class="sorting" data-SortOn="business_name" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/customers/list-customers.php/?".http_build_query($sort_data->link); ?>">Buisness Name<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "business_name"); ?>
									<th scope="col">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($pagging_list as $pagg_row){
									$PkId = "'".$Encryption->encode($pagg_row['customer_address_id'])."'";
									$PkId2 = $Encryption->encode($pagg_row['user_login_id']);									
									?>
									<tr id="Trow_<?php echo $pagg_row["user_login_id"]; ?>">
									    <td><input type="checkbox" name="" data-customer-id=<?php echo  $Encryption->encode($pagg_row["user_login_id"]); ?> class="checkboxes-del"></td>
										<td><?php echo $pagg_row["first_name"]." ".$pagg_row["last_name"]; ?></td>
										<td><?php echo $pagg_row["email"]; ?></td>
										<td><?php echo $pagg_row["phone"]; ?></td>
										<td><?php if($pagg_row["is_buisness_address"]==1){echo $pagg_row["street_address1"].",".$pagg_row["street_address2"].",".$pagg_row["city"].",".$pagg_row["state"].",".$pagg_row["zip"];}else{}?></td>
										<td><?php echo $pagg_row["business_name"]; ?></td>
										<td class="actions_td">
											<a class="load_ajax edit_button" title="Edit" href="<?php echo ADMIN_URL."/customers/detail-customers.php/?user_login_id=".$PkId2."&customer_address_id=".$PkId."&referer=".rawurlencode(ADMIN_URL."/customers/list-customers.php/?".http_build_query($request)); ?>" ><i class="fa fa-edit"></i></a>
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