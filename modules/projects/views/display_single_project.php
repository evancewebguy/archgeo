<!-- Breadcrumbs -->
<div class="breadcrumbs overlay">
	<div class="container">
		<div class="bread-inner">
			<div class="row">
				<div class="col-12">
					<h2><?= $headline ?></h2>
					<p><span><b style="color:#e3dee8;">( click on image to view large photos of this project)</b><span></p>
					<ul class="bread-list">
						<li><a href="<?= BASE_URL ?>">Home</a></li>
						<li><i class="icofont-simple-right"></i></li>
						<li class="active"><?= $projects_obj->client_name ?></li>
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

				 <!--Slick Carousel Slider-->
				 <?= $html_pictures ?>

					<div class="date">
                	    <ul>
							<li><span>Nature of the Project :</span> <?= $projects_obj->nature_of_the_project ?></li>
							<li><span>Date :</span> <?= $projects_obj->year ?></li>
							<!-- <li><span>Client :</span> <?= $projects_obj->client_name ?></li> -->
							<li><span>Status :</span> <?= $projects_obj->status ?></li>
						</ul>
					</div>
					<div class="body-text">
						<h3><?= $projects_obj->client_name ?></h3>
						<p><?= $projects_obj->project_description ?></p>
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