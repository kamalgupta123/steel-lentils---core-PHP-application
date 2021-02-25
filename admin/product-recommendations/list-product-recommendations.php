<?php 
include_once(__DIR__ . "/../header.php");
include_once(ADMIN_DIR . "/admin-functions.php");

$Encryption = new Encryption();
if(empty($_GET['PageNumber'])){ $_GET['PageNumber'] = 1; }
if(empty($_GET['RowSize'])){ $_GET['RowSize'] = 25; }
if(empty($_GET['SortOn'])){ $_GET['SortOn'] = 'i.created_on'; }
if(empty($_GET['SortType'])){ $_GET['SortType'] = 'DESC'; }
$request = $_GET;

$result = send_rest(array(
	"function" => "Admin/ListProductRecommendation",
	"page_no" => $request["PageNumber"],
	"row_size" => $request["RowSize"],
	"sort_on" => $request["SortOn"],
	"sort_type" => $request["SortType"]
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
			            <div>Product Recommendations</div>
			        </div>
			        <div class="col-8 text-right section-col">
			           	<div class="text-right add-btn">
							<a class="btn theme-btn load_ajax" href="<?php echo ADMIN_URL."/product-recommendations/detail-product-recommendations.php?referer=".rawurlencode(ADMIN_URL."/product-recommendations/list-product-recommendations.php/?".http_build_query($request)); ?>"> <i class="fas fa-plus-circle"></i> Add new Product Recommendation </a>
						</div>
					</div>
			    </div> 
			</div>
			<div class="table-div mt-1">
			    <div class="row">
				<div class="col-12">
				<div class="table-responsive mt-3">
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
									<?php //$sort_data = get_sort_data($request, "section_name"); ?>
									<th class="" data-SortOn="" scope="col">Irregular Sections</th>
									<?php //$sort_data = get_sort_data($request, "length_from"); ?>
									<th class="" data-SortOn="" scope="col">From Length</th>
									<?php //$sort_data = get_sort_data($request, "length_to"); ?>
									<th class="" data-SortOn="" scope="col">To Length</th>
									<?php //$sort_data = get_sort_data($request, "recommended_section_id"); ?>
									<th class="" data-SortOn="" scope="col">Recommended Section</th>
									<?php //$sort_data = get_sort_data($request, "recommended_item_id"); ?>
									<th class="" data-SortOn="" scope="col">Recommended Length</th>
									<?php $sort_data = get_sort_data($request, "created_on"); ?>
									<th class="sorting" data-SortOn="created_on" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/product-recommendations/list-product-recommendations.php/?".http_build_query($sort_data->link); ?>">Created Date<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<?php $sort_data = get_sort_data($request, "is_enabled"); ?>
									<th class="sorting" data-SortOn="is_enabled" scope="col"><a class="load_ajax" href="<?php echo ADMIN_URL."/product-recommendations/list-product-recommendations.php/?".http_build_query($sort_data->link); ?>">Status<i class="fa <?php echo $sort_data->css; ?>"></i></a></th>
									<th scope="col">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								//print_r($pagging_list);
								foreach($pagging_list as $pagg_row){
								    //print_r($pagg_row);
									$PkId = "'".$Encryption->encode($pagg_row['product_recommendation_id'])."'";
									$PkId2 = $Encryption->encode($pagg_row['product_recommendation_id']);									
									?>
									<tr id="Trow_<?php echo $pagg_row["product_recommendation_id"]; ?>">
										<td>
											<?php
											if(!empty($pagg_row)){
												// get selected options 
												$get_irregular_sections = send_rest(
													array(
														"function"=>"Admin/GetIrregularSectionsforRecommendation",
														"product_recommendation_id"=>$pagg_row["product_recommendation_id"]
													)
												);
												$selected_sections=array();
												if(!empty($get_irregular_sections['data'])){
													$i=0;
													foreach($get_irregular_sections['data'] as $selected_section){
														if($i!=0){
															echo ", ";
														}
														echo $selected_section['section_name'];
														$i++;
													}
												}
											}
											?>
										</td>
										<td>
											<?php echo $pagg_row["length_from"];?>
										</td>
										<td>
											<?php echo $pagg_row["length_to"];?>
										</td>
										<td>
											<?php echo $pagg_row["section_name"]; ?>
										</td>
										<td>
											<?php echo $pagg_row['length']; ?>
										</td>
										<td><?php echo ui_datetime($pagg_row['created_on']); ?></td>
										<td><?php if($pagg_row['is_enabled']=='1'){ echo '<span class="badge badge-success">Enabled</span>';}else{ echo '<span class="badge badge-danger">Disabled</span>';}?></td>
										<td class="actions_td">
											<a class="load_ajax edit_button" title="Edit" href="<?php echo ADMIN_URL."/product-recommendations/detail-product-recommendations.php/?product_recommendation_id=".$PkId2."&referer=".rawurlencode(ADMIN_URL."/product-recommendations/list-product-recommendations.php/?".http_build_query($request)); ?>" ><i class="fa fa-edit"></i></a>
										</td>
									</tr>
								<?php
								} 
								?>
							</tbody>
						</table>
					
				</div>
					<?php 
							get_pagination($request, $total_pages, $total_records, "/admin/product-recommendations/list-product-recommendations.php", $pagging_list);
						}
					?>
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