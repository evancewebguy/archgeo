
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
						<li class="active">Staff Details</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Breadcrumbs -->


<?php $staff_picture = BASE_URL.'teams_pics/'.$teams_obj->id.'/'.$teams_obj->picture; ?>
				
<!-- Doctor Details -->
<div class="doctor-details-area section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="doctor-details-item doctor-details-left">
                    <img src="<?= $staff_picture ?>" alt="#">
                    <div class="doctor-details-contact">
                        <h3>Contact info</h3>
                        <ul class="basic-info">
                            <li>
                                <i class="icofont-ui-call"></i>
                                Call : <?= OUR_TELNUM ?>
                            </li>
                            <li>
                                <i class="icofont-ui-message"></i>
                                <?= OUR_EMAIL_ADDRESS ?>
                            </li>
                            <li>
                                <i class="icofont-location-pin"></i>
                                <?= OUR_ADDRESS ?>, <?= OUR_ADDRESS2 ?>
                            </li>
                        </ul>
						<!-- Social -->
						<ul class="social">
							<li><a href="#"><i class="icofont-facebook"></i></a></li>
							<li><a href="#"><i class="icofont-google-plus"></i></a></li>
							<li><a href="#"><i class="icofont-twitter"></i></a></li>
							<li><a href="#"><i class="icofont-vimeo"></i></a></li>
							<li><a href="#"><i class="icofont-pinterest"></i></a></li>
						</ul>
						<!-- End Social -->
						<div class="doctor-details-work">
							<h3>Working hours</h3>
							<ul class="time-sidual">
								<li class="day">Monday - Friday <span>08.00-17.00</span></li>
							</ul>
						</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="doctor-details-item">
                    <div class="doctor-details-right">
						<div class="doctor-name">
							<h3 class="name"><?= ucfirst($teams_obj->full_name) ?></h3>
							<p class="deg"><?= $teams_obj->job_title ?></p>
						</div>
                        <div class="doctor-details-biography">
                            <h3>Biography</h3>
                            <p><?= $teams_obj->biography ?></p>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Doctor Details -->