<?php if(!empty($_SESSION['sl_user'])){ ?>
	  </div>
</div>
<?php } ?>
</section>
<?php
$global_settings = send_rest(array(
	"function"=>"Admin/GetGlobalSettings"   
));
$details = $global_settings['data'];
?>
<footer class="site-footer">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<a href="<?php echo SITE_URL; ?>"><img src="<?php echo RESOURCES_URL; ?>/images/dowcon-steel.png" class="logo-img"></a>
				<div class="copyright-text">Copyrights 2020 Dowcon,<br> All rights Reserved</div>
			</div>
			<div class="col-md-4 secondary-dropdown">
				<div class="footer-list-head">MENU</div>
				<ul class="footer-list">
					<li>
						<a href="#">About us</a>
					</li>
					<li>
						<a href="#">Contact us</a>
					</li>
					<li>
						<a href="#">FAQs</a>
					</li>
					<li>
						<a href="#">Terms</a>
					</li>
				</ul>
			</div>
			<div class="col-md-4 secondary-dropdown">
				<div class="footer-list-head">Contact</div>
				<ul class="contact-list">
					<li class="location-item">
						<?php echo $details[5]['meta_value']; ?>	
					</li>
					<li class="location-item browse-item">
						<a href="<?php echo $details[6]['meta_value']; ?>" target='_blank'><?php echo $details[6]['meta_value']; ?></a>
					</li>
					<li class="location-item phone-item">
						<a href="tel:<?php echo $details[6]['meta_value']; ?>"><?php echo $details[7]['meta_value']; ?></a>
					</li>
					<li class="location-item mail-item">
						<a href="mailto:<?php echo $details[6]['meta_value']; ?>"><?php echo $details[8]['meta_value']; ?></a>
					</li>
					<?php if(!empty($details[9]['meta_value'])){ ?>
					<li class="location-item phone-item">
						<a href="tel:<?php echo $details[6]['meta_value']; ?>"><?php echo $details[9]['meta_value']; ?></a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<div class="copyright-text2">Copyrights 2020 Dowcon, All rights Reserved</div>
	</div>
</footer>
</div>
</body>
</html>