<!-- Breadcrumbs -->
<div class="breadcrumbs overlay">
	<div class="container">
		<div class="bread-inner">
			<div class="row">
				<div class="col-12">
					<h2><?= $headline ?></h2>
					<ul class="bread-list">
						<li><a href="<?= BASE_URL ?>">Home</a></li>
						<li><i class="icofont-simple-right"></i></li>
						<li class="active"><?= $services_obj->service_name ?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Breadcrumbs -->

<!-- Start Service Details Area -->
<section class="pf-details section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="inner-content">
					<?= $html_pictures ?>
					<div class="date">
						<ul>
							<li><span>Pictures :</span> Click on the picture to have a larger view</li>
						</ul>
					</div>
					<div class="body-text">
						<h3><?= $services_obj->service_name ?></h3>
						<p><?= nl2br($services_obj->service_description) ?></p>
						<div class="share">
							<h4>Share Now -</h4>
							<ul>
								<li><a href="#"><i class="fa fa-facebook-official" aria-hidden="true"></i></a></li>
								<li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
								<li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- End Service Details Area -->