<?php 
include_once(__DIR__ ."/../../header.php");
?>
<div class="col-lg-9">
	<div class="row">
		<div class="col-md-4 col-6">
			<h4 class="order-head">My Orders</h4>
		</div>
		<div class="col-md-8 col-6">
			<div class="input-group search-group">
				<input type="text" class="form-control" id="validationDefaultUsername" placeholder="Search" aria-describedby="inputGroupPrepend2" required>
				<div class="input-group-prepend">
					<span class="input-group-text" id="inputGroupPrepend2"><i class="fas fa-search"></i></span>
				</div>
			</div>
		</div>
	</div>
	<div class="order-filter">
		<div class="row">
			<div class="col-md-3 col-sm-4 filter-col fisrt-col">
				<div class="form-group">
					<label for="exampleInputEmail1">Filter by delivery date</label>
					<input type="date" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="DD/MM/YYYY">
				</div>
			</div>
			<div class="col-md-3 col-sm-4 filter-col second-col">
				<div class="form-group">
					<label for="exampleInputEmail1">Filter by order date</label>
					<input type="date" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="DD/MM/YYYY">
				</div>
			</div>
			<div class="col-md-3 col-sm-4 filter-col third-col">
				<div class="form-group">
					<label for="exampleFormControlSelect1">Filter by order Status</label>
					<select class="form-control" id="exampleFormControlSelect1">
						<option>Processing</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="table-responsive mt-3">
		<table class="table order-table">
			<thead>
				<tr>
					<th scope="col">Order ID</th>
					<th scope="col">Order Status</th>
					<th scope="col">Delivery Address</th>
					<th scope="col">Delivery Date</th>
					<th scope="col">Order Date</th>
					<th scope="col">Order Total</th>
					<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>OID9777866</td>
					<td><div class="process-div">Processing</div></td>
					<td>12 Jacolite Street, Madeley, Western Australia, 6065</td>
					<td>29/05/2020</td>
					<td>27/05/2020</td>
					<td>$190.80</td>
					<td><a href="" class="action-link"><i class="fas fa-eye"></i></a></td>
				</tr>
			</tbody>
		</table>
	</div>
	<nav aria-label="..." class="pagination-nav">
		<ul class="pagination">
			<li class="page-item ">
				<a class="page-link" href="#" tabindex="-1"><i class="fas fa-fast-backward"></i></a>
			</li>
			<li class="page-item active"><a class="page-link" href="#">1</a></li>
			<li class="page-item ">
				<a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
			</li>
			<li class="page-item"><a class="page-link" href="#">3</a></li>
			<li class="page-item">
				<a class="page-link" href="#"><i class="fas fa-fast-forward"></i></a>
			</li>
		</ul>
	</nav>
</div>
<?php 
include_once(__DIR__ ."/../../footer.php");
?>