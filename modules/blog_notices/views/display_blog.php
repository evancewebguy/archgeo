

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
								<li class="active">Blogs</li>
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
					<div class="col-lg-8 col-12">
						<div class="row">
						<?php
							foreach ($blog_notices as $blog) {
								if($blog->picture != '') {
									$blog_notices_picture = BASE_URL.'blog_notices_pics/'.$blog->blog_notices_id.'/'.$blog->picture;
								} else {
									$blog_notices_picture = BASE_URL.'blog_notices_module/img/home-img1.jpg';
								}		
								$view_blog_notice = BASE_URL.'blog_notices/blog/'.$blog->url_string;
								$blog_categories = $blog->categories;
						?>

							<div class="col-lg-6 col-md-6 col-12">
								<!-- Single Blog -->
								<div class="single-news">
									<div class="news-head">
										<img src="<?= $blog_notices_picture ?>" alt="#">
									</div>
									<div class="news-body">
										<div class="news-content">

											<div class="date">
												<?= date('M j \, Y',  strtotime($blog->published_date)) ?>
											</div>
											<h2><a href="<?= $view_blog_notice ?>"><?= $blog->blog_title ?></a></h2>
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

					<div class="col-lg-4 col-12">
						<div class="main-sidebar">

							<!-- Single Widget -->
							<div class="single-widget recent-post">
								<h3 class="title">Featured Projects</h3>

								<!-- Single Post -->
								<div class="single-post">
									<div class="image">
										<img src="img/blog-sidebar1.jpg" alt="#">
									</div>
									<div class="content">
										<h5><a href="#">We have annnocuced our new product.</a></h5>
										<ul class="comment">
											<li><i class="fa fa-calendar" aria-hidden="true"></i>Jan 11, 2020</li>
											<li><i class="fa fa-commenting-o" aria-hidden="true"></i>35</li>
										</ul>
									</div>
								</div>
								<!-- End Single Post -->

								<!-- Single Post -->
								<div class="single-post">
									<div class="image">
										<img src="img/blog-sidebar2.jpg" alt="#">
									</div>
									<div class="content">
										<h5><a href="#">Top five way for solving teeth problems.</a></h5>
										<ul class="comment">
											<li><i class="fa fa-calendar" aria-hidden="true"></i>Mar 05, 2019</li>
											<li><i class="fa fa-commenting-o" aria-hidden="true"></i>59</li>
										</ul>
									</div>
								</div>
								<!-- End Single Post -->
								<!-- Single Post -->
								<div class="single-post">
									<div class="image">
										<img src="img/blog-sidebar3.jpg" alt="#">
									</div>
									<div class="content">
										<h5><a href="#">We provide highly business soliutions.</a></h5>
										<ul class="comment">
											<li><i class="fa fa-calendar" aria-hidden="true"></i>June 09, 2019</li>
											<li><i class="fa fa-commenting-o" aria-hidden="true"></i>44</li>
										</ul>
									</div>
								</div>
								<!-- End Single Post -->

								<!-- Single Post -->
								<div class="single-post">
									<div class="image">
										<img src="img/blog-sidebar1.jpg" alt="#">
									</div>
									<div class="content">
										<h5><a href="#">We have annnocuced our new product.</a></h5>
										<ul class="comment">
											<li><i class="fa fa-calendar" aria-hidden="true"></i>Jan 11, 2020</li>
											<li><i class="fa fa-commenting-o" aria-hidden="true"></i>35</li>
										</ul>
									</div>
								</div>
								<!-- End Single Post -->

								<!-- Single Post -->
								<div class="single-post">
									<div class="image">
										<img src="img/blog-sidebar1.jpg" alt="#">
									</div>
									<div class="content">
										<h5><a href="#">We have annnocuced our new product.</a></h5>
										<ul class="comment">
											<li><i class="fa fa-calendar" aria-hidden="true"></i>Jan 11, 2020</li>
											<li><i class="fa fa-commenting-o" aria-hidden="true"></i>35</li>
										</ul>
									</div>
								</div>
								<!-- End Single Post -->

								<!-- Single Post -->
								<div class="single-post">
									<div class="image">
										<img src="img/blog-sidebar1.jpg" alt="#">
									</div>
									<div class="content">
										<h5><a href="#">We have annnocuced our new product.</a></h5>
										<ul class="comment">
											<li><i class="fa fa-calendar" aria-hidden="true"></i>Jan 11, 2020</li>
											<li><i class="fa fa-commenting-o" aria-hidden="true"></i>35</li>
										</ul>
									</div>
								</div>
								<!-- End Single Post -->


								<div class="button mt-4">
									<a class="btn" href="<?php BASE_URL ?>" style="color:white;" >View all featured projects</a>
								</div>

							</div>
							<!--/ End Single Widget -->



							<!-- Single Widget -->
							<!-- <div class="single-widget side-tags">
								<h3 class="title">Tags</h3>
								<ul class="tag">
									<li><a href="#">business</a></li>
									<li><a href="#">wordpress</a></li>
									<li><a href="#">html</a></li>
									<li><a href="#">multipurpose</a></li>
									<li><a href="#">education</a></li>
									<li><a href="#">template</a></li>
									<li><a href="#">Ecommerce</a></li>
								</ul>
							</div> -->
							<!--/ End Single Widget -->


						</div>
					</div>
				</div>
			</div>
		</section>
		<!--/ End Single News -->