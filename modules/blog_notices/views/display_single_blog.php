
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
								<li class="active">single blog</li>
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
							<!-- <div class="image-slider">
								<div class="pf-details-slider">
									<img src="<?= BASE_URL ?>frontend/img/call-bg.jpg" alt="#">
									<img src="<?= BASE_URL ?>frontend/img/call-bg.jpg" alt="#">
									<img src="<?= BASE_URL ?>frontend/img/call-bg.jpg" alt="#">
								</div>
							</div> -->
							<?= $html_pictures ?>
							

							<div class="date">
								<ul>
									<li><span>Pictures :</span> Understand More about this service through the pictures</li>
								</ul>
							</div>
							<div class="body-text">
								<h3><?= $blog_notices_obj->blog_title ?></h3>
								<p><?= $blog_notices_obj->notice ?></p>

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
		<!-- End Service Details Area