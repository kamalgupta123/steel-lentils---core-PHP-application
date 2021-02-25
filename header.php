<?php 
include_once(__DIR__ ."/common_header.php");

$first_name='';
$last_name='';
$cust_id=0;
$unique_cart_key='';
if(isset($_SESSION['unique_cart_key'])){
	$unique_cart_key=$_SESSION['unique_cart_key'];
}
if(!empty($_SESSION['sl_user'])){
	$result = send_rest(array(
		"function" => "Customers/getLoginDetails",
		"user_type_id" => $_SESSION['sl_user']['user_id']
	));
	$first_name = $result['data'][0]['first_name'];
	$last_name = $result['data'][0]['last_name'];
	$cust_id=$_SESSION['sl_user']['user_id'];
}
// get items added in cart 
$items_in_cart = send_rest(array(
	"function"=>"Customers/DeliveryTruckItemsCount",
	"customer_id"=>$cust_id,
	"unique_cart_key"=>$unique_cart_key
));
$items = $items_in_cart['data'];
?>
<header class="site-header">
    <div class="top-head">
		<div class="container">
			<div class="row py-2">
				<div class="col-md-6 col-4 logo-col">
					<a href="<?php echo SITE_URL; ?>">  <img src="<?php echo RESOURCES_URL; ?>/images/dowcon-steel.png" class="logo-img"></a>
				</div>
				<div class="col-md-6 col-8">
					<ul class="upper-list">
						<li>
							<div class="">
								<img src="<?php echo RESOURCES_URL; ?>/images/truck.png" class="truck-img">
							</div>
                              <a href="javascript:;" class="delivery-link">Delivery Truck<br><span><?php echo $items; ?> Item<?php echo ($items>1)?"s":""; ?></span></a>
                              <a href="javascript:;" class="delivery-link2"><?php echo $items; ?></a>
						</li>
						<li class="ml-3">
							<div class="">
								<img src="<?php echo RESOURCES_URL; ?>/images/default-user.png" class="user-img">
							</div>
							<?php if(!empty($_SESSION['sl_user'])){ ?>
							<div class="dropdown upper-dropdown">
								<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Welcome,<br><span><?php if(!empty($first_name) && !empty($last_name)){ echo $first_name.' '.$last_name;}?></span>
								</button>

								<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
								    <a class="dropdown-item" href="<?php echo SITE_URL; ?>/customers/profile/profile.php">Dashboard</a>
									<a class="dropdown-item" href="<?php echo SITE_URL; ?>/logout.php">Logout</a>
								</div>
							</div>
							<?php }else{ ?>
							    <div class='customer_links'><a href='<?php echo SITE_URL; ?>/login.php'>Login</a> | <a href='<?php echo SITE_URL; ?>/signup.php'>SignUp</a></div>
							<?php } ?>
						</li>
					</ul>
				</div>
			</div>
		</div>
    </div>
</header>
<section class="section">
    <?php if(!empty($_SESSION['sl_user']) && (strpos($_SERVER['REQUEST_URI'], "/profile.php") || strpos($_SERVER['REQUEST_URI'], "/addresses") || strpos($_SERVER['REQUEST_URI'], "/contacts") || strpos($_SERVER['REQUEST_URI'], "/payments") || strpos($_SERVER['REQUEST_URI'], "/orders"))){ ?>
		<div class="banner-div banner-div-subpage">
			<div class="container">
				<div class="upper-banner-div">
					<div class="banner-content banner-content-subpage">
						<h3 class="banner-head">Dashboard</h3>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row pt-5 pb-4">
				<div class="col-lg-3">
					<div class="order-nav">
						<ul>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/profile.php")){echo 'active';} ?>">
								<a class='load_ajax' href="<?php echo SITE_URL ?>/customers/profile/profile.php">My Account</a>
							</li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/addresses")){echo 'active';} ?>">
								<a href="<?php echo SITE_URL ?>/customers/addresses/list-addresses.php" class="address-link load_ajax">Address</a>
							</li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/contacts")){echo 'active';} ?>">
								<a href="<?php echo SITE_URL ?>/customers/contacts/list-contacts.php" class="address-link load_ajax">Contacts</a>
							</li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/payments")){echo 'active';} ?>">
								<a href="<?php echo SITE_URL ?>/customers/payments/list-payments.php" class="payment-link load_ajax">Payment</a>
							</li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'], "/orders")){echo 'active';} ?>">
								<a href="<?php echo SITE_URL ?>/customers/orders/list-orders.php" class="order-link load_ajax">My Orders</a>
							</li>
							<li>
								<a href="<?php echo SITE_URL ?>/logout.php" class="logout-link load_ajax">Logout</a>
							</li>
						</ul>
					</div>
				</div>
	<?php } ?>