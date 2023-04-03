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
						<li class="active">Projects</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Breadcrumbs -->


	<!-- Single News -->
	<section class="blog grid section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-12">
					<div class="row">
					<?php
						foreach ($projects as $project) {
                            
							if($project->picture != '') {
								$project_picture = BASE_URL.'projects_pics/'.$project->id.'/'.$project->picture;
							} else {
								$project_picture = BASE_URL.'projects_module/img/home-img1.jpg';
							}		
							$view_project = BASE_URL.'projects/view_project/'.$project->url_string;
					?>
						<div class="col-lg-4 col-md-6 col-12">
							<!-- Single Blog -->
							<div class="single-news">
								<div class="news-head">
									<img src="<?= $project_picture ?>" alt="<?= $project->client_name ?>" style="height:350px">
								</div>

                                <div class="news-body">
									<div class="news-content">
										<div class="date"><?= $project->year ?></div>
										<h2><a href="<?= $view_project ?>"><?= $project->client_name ?></a></h2>
										<p class="text"><?php                         
                                            // strip tags to avoid breaking any html
                                            $string = strip_tags($project->project_description );
                                            if (strlen($string) > 130) {
                                                // truncate string
                                                $stringCut = substr($string, 0, 130);
                                                $endPoint = strrpos($stringCut, ' ');

                                                //if the string doesn't contain any space then it will cut without word basis.
                                                $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                                $string .= '... <br><b><a style="color:#32B87D;" href='.$view_project.'>Read More</a></b>';
                                            }
                                            echo $string;
                                        
                                        ?></p>
									</div>
								</div>
							</div>
							<!-- End Single Blog -->
						</div>
						<?php
							}
						?>
						
						<div class="col-12">
							<!-- Pagination -->
							<?php  echo Pagination::display($data); ?>
							<!--/ End Pagination -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--/ End Single News -->