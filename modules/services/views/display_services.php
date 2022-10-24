<?php
    $service = segment(1);
?>

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
								<li class="active"><?= $service ?></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Breadcrumbs -->






		<!-- Start service -->
		<section class="services section">
			<div class="container">
				<div class="row">

                <?php  if (count($rows)>0) { ?>
                    <?php foreach($rows as  $row) { ?>
                
					<div class="col-lg-4 col-md-6 col-12">
						<div class="single-service">
							<i class="icofont icofont-prescription"></i>
							<h4><a href="<?php BASE_URL ?>services/service/<?= $row->url_string ?>"><?= $row->service_name ?></a></h4>
							<p><?php 

                            $description = $row->service_description;
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
                        <a href="<?php BASE_URL ?>services/service/<?= $row->url_string ?>" style="color:#55be90;" >read more</a>
						</div>
					</div>  

                    <?php 
                        } 
                    }
                    ?> 

				</div>
			</div>
		</section>
		<!--/ End service -->
		
		<!-- clients -->
		<div class="clients overlay">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-12">
						<div class="owl-carousel clients-slider">
							<div class="single-clients">
								<img src="img/client1.png" alt="#">
							</div>
							<div class="single-clients">
								<img src="img/client2.png" alt="#">
							</div>
							<div class="single-clients">
								<img src="img/client3.png" alt="#">
							</div>
							<div class="single-clients">
								<img src="img/client4.png" alt="#">
							</div>
							<div class="single-clients">
								<img src="img/client5.png" alt="#">
							</div>
							<div class="single-clients">
								<img src="img/client1.png" alt="#">
							</div>
							<div class="single-clients">
								<img src="img/client2.png" alt="#">
							</div>
							<div class="single-clients">
								<img src="img/client3.png" alt="#">
							</div>
							<div class="single-clients">
								<img src="img/client4.png" alt="#">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--/Ens clients -->
		


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