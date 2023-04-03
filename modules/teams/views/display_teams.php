<!-- Breadcrumbs -->
<div class="breadcrumbs overlay">
	<div class="container">
		<div class="bread-inner">
			<div class="row">
				<div class="col-12">
					<h2>Meet Our Qualified Staff</h2>
					<ul class="bread-list">
						<li><a href="<?= BASE_URL ?>">Home</a></li>
						<li><i class="icofont-simple-right"></i></li>
						<li class="active">Staffs</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Breadcrumbs -->
		


<!-- Start Team -->
<section id="team" class="team section single-page">
	<div class="container">
		<div class="row">

            <?php 
                foreach ($rows as $team) {
                    if($team->picture != '') {
                        $team_picture = BASE_URL.'teams_pics/'.$team->id.'/'.$team->picture;
                    } else {
                        $team_picture = BASE_URL.'teams_module/img/home-img1.jpg';
                    }		
                    $view_team = BASE_URL.'teams/view_staff/'.$team->url_string;
            ?>
			<div class="col-lg-4 col-md-6 col-12">
				<!-- Single Team -->
				<div class="single-team">
					<div class="t-head">
						<img src="<?= $team_picture ?>" alt="<?= $team->full_name ?>">
						<div class="t-icon">
							<a href="<?= $view_team ?>" class="btn">More Information</a>
						</div>
					</div>
					<div class="t-bottom">
						<p><?= $team->job_title ?></p>
						<h2><a href="doctor-details.html"> <?= $team->full_name ?> </a></h2>
					</div>
				</div>
				<!-- End Single Team -->
			</div>	
            <?php } ?>
		</div>
	</div>
</section>
<!--/ End Team -->