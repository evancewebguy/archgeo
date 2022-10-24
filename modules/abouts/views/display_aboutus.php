<?php
    $aboutus = 'About Us';
?>

<!-- Background image 1600 × 353 -->
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
						<li class="active"><?= $aboutus ?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Breadcrumbs -->


	<!-- Start About Area -->
    <section class="about-area section">
        <div class="container-fluid p-0">
            <div class="row m-0">
                <div class="col-lg-6 col-md-12 p-0">
                    <div class="about-image">
                        
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 p-0">
                    <div class="about-content">
                        <span>About Us</span>
                        <h2>Company Information</h2>
                        <p><?= $abouts_obj[0]->company_information ?></p>
                        <!-- <ul>
                            <li><i class="icofont-tick-mark"></i> Scientific Skills For getting a better result</li>
                            <li><i class="icofont-tick-mark"></i> Communication Skills to getting in touch</li>
                            <li><i class="icofont-tick-mark"></i> A Career Overview opportunity Available</li>
                            <li><i class="icofont-tick-mark"></i> A good Work Environment For work</li>
                            <li><i class="icofont-tick-mark"></i> Scientific Skills For getting a better result</li>
                            <li><i class="icofont-tick-mark"></i> Communication Skills to getting in touch</li>
                        </ul> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End About Area -->



    <!-- Start Our Vision Area -->
    <section class="our-vision-area ptb-100 pt-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="single-vision-box">
                        <div class="icon">
                            <i class="icofont-tick-mark"></i>
                        </div>
                        <h3>Our Mission</h3>
                        <p><?= $abouts_obj[0]->mission ?></p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="single-vision-box">
                        <div class="icon">
                            <i class="icofont-tick-mark"></i>
                        </div>
                        <h3>Our Vision</h3>
                        <p><?= $abouts_obj[0]->vision ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Our Vision Area -->






    		<!-- Start Fun-facts -->
		<div id="fun-facts" class="fun-facts section overlay">
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-md-6 col-12">
						<!-- Start Single Fun -->
						<div class="single-fun">
							<i class="icofont icofont-home"></i>
							<div class="content">
								<span class="counter">3468</span>
								<p>Happy Clients</p>
							</div>
						</div>
						<!-- End Single Fun -->
					</div>
					<div class="col-lg-3 col-md-6 col-12">
						<!-- Start Single Fun -->
						<div class="single-fun">
							<i class="icofont icofont-user-alt-3"></i>
							<div class="content">
								<span class="counter">557</span>
								<p>Specialist</p>
							</div>
						</div>
						<!-- End Single Fun -->
					</div>
					<div class="col-lg-3 col-md-6 col-12">
						<!-- Start Single Fun -->
						<div class="single-fun">
							<i class="icofont-simple-smile"></i>
							<div class="content">
								<span class="counter">4379</span>
								<p>Total Projects</p>
							</div>
						</div>
						<!-- End Single Fun -->
					</div>
					<div class="col-lg-3 col-md-6 col-12">
						<!-- Start Single Fun -->
						<div class="single-fun">
							<i class="icofont icofont-table"></i>
							<div class="content">
								<span class="counter">32</span>
								<p>Years of Experience</p>
							</div>
						</div>
						<!-- End Single Fun -->
					</div>
				</div>
			</div>
		</div>
		<!--/ End Fun-facts -->
		
        



        
	
    <!-- Start Our Mission Area -->
    <section class="our-mission-area ptb-100 pt-0">
        <div class="container-fluid p-0">
            <div class="row m-0">
                <div class="col-lg-6 col-md-12 p-0">
                    <div class="our-mission-content">
                        <span class="sub-title">Our Core Values</span>
                        <h2>Our Core Values</h2>
                        <?= $abouts_obj[0]->core_values ?>


                        <!-- <ul>
                            <li>
                                <div class="icon">
                                    <i class="icofont-doctor"></i>
                                </div>
                                <span>Professional Staff</span>
                                Lorem ipsum dolor sit amet sit, consectetur adipiscing elit.
                            </li>
                            <li>
                                <div class="icon">
                                   <i class="icofont-kid"></i>
                                </div>
                                <span>Newborn Care</span>
                                Lorem ipsum dolor sit amet sit, consectetur adipiscing elit.
                            </li>
                            <li>
                                <div class="icon">
                                    <i class="icofont-laboratory"></i>
                                </div>
                                <span>Sufficient Lab Tests</span>
                                Lorem ipsum dolor sit amet sit, consectetur adipiscing elit.
                            </li>
                            <li>
                                <div class="icon">
                                   <i class="icofont-tooth"></i>
                                </div>
                                <span>Tooth Extraction</span>
                                Lorem ipsum dolor sit amet sit, consectetur adipiscing elit.
                            </li>
                        </ul> -->
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 p-0">
                    <div class="our-mission-image">
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Our Mission Area -->



    	<!-- Start clients -->
		<?php if (count($clientlogos))  { ?>
		<div class="clients overlay">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-12">
						<div class="owl-carousel clients-slider">

							<?php
                                foreach ($clientlogos as $key => $logo) {
									$picture_path = BASE_URL ."clientlogos_module/clientlogos_pics/".$logo->id ."/".$logo->picture;
                            ?>

							<div class="single-clients">
								<img src="<?= $picture_path ?>" alt="#">
							</div>

							<?php } ?>			
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<!--/Ens clients -->
		



