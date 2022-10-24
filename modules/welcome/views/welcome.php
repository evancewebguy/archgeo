<?php
flashdata();
?>
		<!-- Slider Area -->
		<section class="slider">
			<div class="hero-slider">

				<!-- Start Single Slider -->
				<div class="single-slider" style="background-image:url('<?= BASE_URL ?>frontend/img/defaults.png')">
					<div class="container">
						<div class="row">
							<div class="col-lg-12">
								<div class="text">
									<h1>Welcome to <span>Archgeo Construction Limited</span></h1>
									<p>
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sed nisl pellentesque, faucibus libero eu, gravida quam.
										<br>
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sed nisl pellentesque, faucibus libero eu, gravida quam. 
										<br>
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sed nisl pellentesque, faucibus libero eu, gravida quam. 
										<br>
										Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sed nisl pellentesque, faucibus libero eu, gravida quam. 
									</p>
									<!-- <div class="button">
										<a href="#" class="btn">View This Service</a>
										<a href="#" class="btn primary">Learn More</a>
									</div> -->
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End Single Slider -->

				<?php 
                    foreach ($sliders as $key => $slide) {
						$slider_pic = BASE_URL.'sliders_module/sliders_pics/'.$slide->id.'/'.$slide->picture;
                ?>

				<!-- Start Single Slider -->
				<div class="single-slider" style="background-image:url('<?= $slider_pic ?>')">
					<div class="container">
						<div class="row">
							<div class="col-lg-8">
								<div class="text">
									<h1><?= $slide->title ?></h1>
									<p><?= $slide->description ?> </p>
									<div class="button">
										<?php if (!empty($slide->link)) { ?>
										<a href="<?= BASE_URL ?><?= $slide->link ?>" class="btn">View <?= $slide->link ?></a>
										<?php  } ?>
										<a href="<?= BASE_URL ?>enquiries" class="btn primary">Conatct Now</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Start End Slider -->

				<?php
                    } 
				?>
			</div>
		</section>
		<!--/ End Slider Area -->
		
	

		<!-- Start Fun-facts -->
		<div id="fun-facts" class="fun-facts section overlay">
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-md-6 col-12">
						<!-- Start Single Fun -->
						<div class="single-fun">
							<i class="icofont icofont-home"></i>
							<div class="content">
								<span class="counter">368</span>
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
								<span class="counter">57</span>
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
								<span class="counter">379</span>
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
								<span class="counter">9</span>
								<p>Years of Experience</p>
							</div>
						</div>
						<!-- End Single Fun -->
					</div>
				</div>
			</div>
		</div>
		<!--/ End Fun-facts -->
		


		<!-- Start Why choose -->
		<section class="why-choose section" >
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-title">
							<h2>Here is what to know About Us</h2>
							<img src="img/sectionimg.png" alt="#">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6 col-12">
						<!-- Start Choose Left -->
						<div class="choose-left">
							<h3>Who We Are</h3>
							<?php
							/**
							 * 	get youtube introduction
							 *  get about info from about us
							 */					
							foreach ($abouts as $about) {
								$description = $about->company_information;
								$limit =600;
								if (strlen($description) > $limit) {
									$new_description = substr($description, 0, $limit) .'...';
									echo $new_description;
								} else {
									echo $description;
								}
							}
                            ?> 
							<p></p>
							<div class="button">
								<a class="btn" href="<?php BASE_URL ?>abouts" style="color:white;" >Read More About Us</a>
							</div>
						</div>
						<!-- End Choose Left -->
					</div>
					<div class="col-lg-6 col-12">
						<!-- Start Choose Rights -->
						<div class="choose-right" style="background-image:url('<?= BASE_URL ?>projects_pictures_thumb/7/IMG_2021X9tk.jpg');">
							<div class="video-image">
								<!-- Video Animation -->
								<div class="promo-video">
									<div class="waves-block">
										<div class="waves wave-1"></div>
										<div class="waves wave-2"></div>
										<div class="waves wave-3"></div>
									</div>
								</div>
								<!--/ End Video Animation -->
								<a href="https://www.youtube.com/watch?v=RFVXy6CRVR4" class="video video-popup mfp-iframe"><i class="fa fa-play"></i></a>
							</div>
						</div>
						<!-- End Choose Rights -->
					</div>
				</div>
			</div>
		</section>
		<!--/ End Why choose -->
		



		<!-- Start Call to action -->
		<section class="call-action overlay" data-stellar-background-ratio="0.5">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-12">
						<div class="content">
							<h2>Do you need Consultation services? Call <?= OUR_TELNUM ?></h2>
							<p>We will go through your requiments and advice you on the best course of action</p>
							<div class="button">
								<a href="<?= BASE_URL ?>enquiries" class="btn">Contact Now</a>
								<a href="<?= BASE_URL ?>services" class="btn second">Learn More<i class="fa fa-long-arrow-right"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--/ End Call to action -->


		<!-- Start portfolio -->
		<?php if(count($projects) > 0){ ?>
		<section class="portfolio section" >
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-title">
							<h2>We Make sure our projects are of international standards</h2>
							<img src="img/sectionimg.png" alt="#">
							<p>These are some of our projects. click to view details.</p>
						</div>
					</div>
				</div>
			</div>
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12 col-12">
						<div class="owl-carousel portfolio-slider">

							<?php
                                foreach ($projects as $project) {

									if($project->picture != '') {
										$project_picture = BASE_URL.'projects_pics/'.$project->id.'/'.$project->picture;
									} else {
										$project_picture = BASE_URL.'projects_module/img/home-img.png';
									}		
									$view_project = BASE_URL.'projects/view_project/'.$project->url_string;

                            ?>
							<div class="single-pf">
								<img src="<?= $project_picture ?>" alt="<?= $project->client_name ?>" style="height:300px;">
								<a href="<?= $view_project ?>" class="btn">View Details</a>
							</div>

							<?php  } ?>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--/ End portfolio -->
		<?php } ?>


		<!-- Start service -->
		<?php if (count($services)>0) { ?>
		<section class="services section">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-title">
							<h2>Here are the services we offer</h2>
							<img src="img/sectionimg.png" alt="#">
							<p>These are some of the services we offer. Click to view more about the service.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<?php 
						foreach($services as $service){
					?>
					<div class="col-lg-4 col-md-6 col-12">
						<!-- Start Single Service -->
						<div class="single-service">
							<i class="icofont icofont-prescription"></i>
							<h4><a href="<?php BASE_URL ?>services/service/<?= $service->url_string ?>"><?= $service->service_name ?></a></h4>
							<p>
								<?php
									$description = $service->service_description;
									$limit =100;

									if (strlen($description) > $limit){
										$new_description = substr($description, 0, $limit) .'...';
										echo $new_description;
									}else{
										echo $description;
									}
                            	?> 
                            <br>
							</p>	
							<a href="<?php BASE_URL ?>services/service/<?= $service->url_string ?>" style="color:#2b6633;" >read more</a>
						</div>
						<!-- End Single Service -->
					</div>


					<?php }  ?>
				</div>
			</div>
		</section>
		<?php } ?>
		<!--/ End service -->
		


		<!-- Start Testimonials -->
		<?php if(count($testimonies) > 0){ ?>
		<section class="section testimonials overlay" data-stellar-background-ratio="0.5">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-title">
							<h2>What Our Clients are saying About Us</h2>
							<img src="img/sectionimg.png" alt="#">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-12">
						<div class="owl-carousel testimonial-slider">

							<?php
                                foreach ($testimonies as $testimony) {
									$picture_path = BASE_URL ."/testimonys_module/testimonys_pics_thumbnails/".$testimony->id ."/".$testimony->picture
                            ?>
							<!-- Start Single Testimonial -->
							<div class="single-testimonial">

								<img src="<?= $picture_path ?>" alt="<?= $testimony->name ?>">
								<p><?php 
								$description = $testimony->testimony;
								$limit =100;

								if (strlen($description) > $limit){
									$new_description = substr($description, 0, $limit) .'...';
									echo $new_description;
								}else{
									echo $description;
								}
								
								?> </p>
								<p>platform: <b><?= $testimony->platform ?></b></p>
								<h4 class="name"><?= $testimony->name ?></h4>
							</div>
							<!-- End Single Testimonial -->
							<?php } ?>


						</div>
					</div>
				</div>
			</div>
		</section>
		<?php } ?>
		<!--/ End Testimonials -->
		


		<?php if(count($processes)){ ?>
		<!-- Start Project Life cycle -->		
		<section class="departments section">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-title">
							<h2>Our Projects Goes through this process.</h2>
							<img src="img/sectionimg.png" alt="#">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="department-tab">
							<!-- Nav Tab  -->                                                                                                   
							<ul class="nav nav-tabs" id="myTab" role="tablist">
							<?php
                                foreach ($processes as $key => $process) {
                                    if ($key == 0) {
                                        $dynamic_class = "active";
                                    } else {
                                        $dynamic_class = " ";
                                    } 

									if ($key == 0) {
										$icon = "icofont-workers-group";
									}elseif ($key == 1) {
										$icon = "icofont-computer";
									}elseif ($key == 2) {
										$icon = "icofont-money";
									}elseif ($key == 3) {
										$icon = "icofont-address-book";
									}elseif ($key == 4) {
										$icon = "icofont-flora-flower";
									}elseif ($key == 5) {
										$icon = "icofont-worker";
									}else{
										$icon = "icofont-workers-group";
									}
								
								?>

								<li class="nav-item">
									<a class="nav-link <?= $dynamic_class ?>" data-toggle="tab" 
										href="#t-tab<?= $key+1 ?>" role="tab">
										<i class="<?= $icon ?>"></i>
										<span class="first"><?= $processes[$key]->process_name ?></span>
									</a>
								</li>

								<?php } ?>
							</ul>
							<!--/ End Nav Tab -->
							<div class="tab-content" id="myTabContent">

							<?php
                                foreach ($processes as $key => $process) { 
									if($key == 0){
										$dynamic_class = "active";
									}else{
										$dynamic_class = " ";
									}
							?>	
								<!-- Tab 1 -->
								<div class='tab-pane fade show <?= $dynamic_class ?>' id='t-tab<?=	$key+1 ?>' role="tabpanel">
									<div class="row">
										<div class="col-lg-6">
											<div class="department-left">
												<h3><?= $processes[$key]->process_name ?></h3>
												<p class="p1">
												<?= $processes[$key]->process_description ?>
												</p>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="department-right">
												<img src="<?= BASE_URL ?>projects_pictures_thumb/7/IMG_2021X9tk.jpg" alt="#" style="height:300px; width:450px;">
											</div>
										</div>
									</div>
								</div>
								<!--/ End Tab 1 -->
							<?php } ?>

							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Departments -->
		<?php } ?>	
		

		<!-- Start Blog Area -->
		<?php if (count($articles))  { ?>
		<section class="blog section" id="blog">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-title">
							<h2>Keep up with Our Most Recent Archgeo News.</h2>
							<img src="img/sectionimg.png" alt="#">
							<p>These are our Latest News. Click to view more details</p>
						</div>
					</div>
				</div>
				<div class="row">

				<?php
					foreach($articles as $article){
						if($article->picture != '') {
							$blog_notices_picture = BASE_URL.'blog_notices_pics/'.$article->id.'/'.$article->picture;
						} else {
							$blog_notices_picture = BASE_URL.'blog_notices_module/img/home-img.png';
						}		
						$view_blog_notice = BASE_URL.'blog_notices/blog/'.$article->url_string;
				?>
					<div class="col-lg-4 col-md-6 col-12">
						<!-- Single Blog -->
						<div class="single-news">
							<div class="news-head">
								<a href="<?= $view_blog_notice ?>">
									<img src="<?= $blog_notices_picture ?>" alt="<?= $article->blog_title  ?>">
								</a>
							</div>
							<div class="news-body">
								<div class="news-content">
									<div class="date"><?= date('M j \, Y',  strtotime($article->published_date)) ?> </div>
									<h2><a href="<?= $view_blog_notice ?>"><?= $article->blog_title ?></a></h2>
									<p class="text">

									</p>
								</div>
							</div>
						</div>
						<!-- End Single Blog -->
					</div>
					<?php } ?>
				</div>
			</div>
		</section>
		<?php } ?>
		<!-- End Blog Area -->
		

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
		


		<!-- Start Newsletter Area -->
		<section class="newsletter section">
			<div class="container">
				<div class="row ">
					<div class="col-lg-6  col-12">
						<!-- Start Newsletter Form -->
						<div class="subscribe-text ">
							<h6>Sign up for newsletter</h6>
							<p class="">You will get Updates of our projects, services, blog,<br> 
							and get to be with us on the journey from <b> concepts to creation </b>.</p>
						</div>
						<!-- End Newsletter Form -->
					</div>
					<div class="col-lg-6  col-12">

					<?php
						$form_location = 'newsletters/submit_mail'; 
					?>
						<!-- Start Newsletter Form -->
						<div class="subscribe-form ">
							<!-- <form action="#" method="get" class="newsletter-inner"> -->
							<?= form_open($form_location, array("class" => "newsletter-inner")); ?>
								<input name="email_address" placeholder="Your email address" class="common-input" onfocus="this.placeholder = ''"
									onblur="this.placeholder = 'Your email address'" required="" type="email">
								<!-- <button class="btn">Subscribe</button> -->
								<?= form_submit('submit', 'Subscribe', array("class" => "btn")); ?>
							<?= form_close(); ?>
							<!-- </form> -->
						</div>
						<!-- End Newsletter Form -->
					</div>
				</div>
			</div>
		</section>
		<!-- /End Newsletter Area -->
	