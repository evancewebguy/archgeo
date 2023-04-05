<!DOCTYPE html>
<html lang="en">

<head>
		<!-- Meta Tag -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name='copyright' content='pavilan'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<!-- Title -->
        <title>Archgeo</title>
		
		<!-- Favicon -->
        <link rel="icon" href="<?= BASE_URL ?>frontend/img/archgeofavicon.png">
		
		<!-- Google Fonts -->
		 <link href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&amp;display=swap" rel="stylesheet">
	
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="<?= BASE_URL ?>frontend/css/bootstrap.min.css">
		<!-- Nice Select CSS -->
		<link rel="stylesheet" href="<?= BASE_URL ?>frontend/css/nice-select.css">
		<!-- Font Awesome CSS -->
        <link rel="stylesheet" href="<?= BASE_URL ?>frontend/css/font-awesome.min.css">
		<!-- icofont CSS -->
        <link rel="stylesheet" href="<?= BASE_URL ?>frontend/css/icofont.css">
		<!-- Slicknav -->
		<link rel="stylesheet" href="<?= BASE_URL ?>frontend/css/slicknav.min.css">
		<!-- Owl Carousel CSS -->
        <link rel="stylesheet" href="<?= BASE_URL ?>frontend/css/owl-carousel.css">
		<!-- Datepicker CSS -->
		<link rel="stylesheet" href="<?= BASE_URL ?>frontend/css/datepicker.css">
		<!-- Animate CSS -->
        <link rel="stylesheet" href="<?= BASE_URL ?>frontend/css/animate.min.css">
		<!-- Magnific Popup CSS -->
        <link rel="stylesheet" href="<?= BASE_URL ?>frontend/css/magnific-popup.css">
		
		<!-- Archgeo CSS -->
        <link rel="stylesheet" href="<?= BASE_URL ?>frontend/css/normalize.css">
        <link rel="stylesheet" href="<?= BASE_URL ?>frontend/style.css">
        <link rel="stylesheet" href="<?= BASE_URL ?>frontend/css/responsive.css">
		
		<!-- Color CSS -->
		<link rel="stylesheet" href="<?= BASE_URL ?>frontend/css/color/colorgreen.css">

		<link rel="stylesheet" id="colors">

		<?= $additional_public_includes_top ?>

    </head>
    <body>
	
		<!-- Preloader -->
        <div class="preloader">
            <div class="loader">
                <div class="loader-outter"></div>
                <div class="loader-inner"></div>
            </div>
        </div>
        <!-- End Preloader -->
		
		<!-- Archgeo Color Plate -->
		<div class="color-plate">
			<!-- <a class="color-plate-icon"><i class="fa fa-cog fa-spin"></i></a> -->
			<h4>Archgeo</h4>
			<p>Here is some awesome color's available on Archgeo Template.</p>
			<span class="color1"></span>
			<span class="color2"></span>
			<span class="color3"></span>
			<span class="color4"></span>
			<span class="color5"></span>
			<span class="color6"></span>
			<span class="color7"></span>
			<span class="color8"></span>
			<span class="color9"></span>
			<span class="color10"></span>
			<span class="color11"></span>
			<span class="color12"></span>
		</div>
		<!-- /End Color Plate -->
	
		<!-- Header Area -->
		<header class="header" >
			<!-- Topbar -->
			<div class="topbar">
				<div class="container">
					<div class="row">
						<div class="col-lg-6 col-md-5 col-12">
							<!-- Contact -->
							<ul class="top-link">
								<li><a href="<?= BASE_URL ?>abouts">About</a></li>
								<li><a href="<?= BASE_URL ?>faqs">FAQ</a></li>
							</ul>
							<!-- End Contact -->
						</div>
						<div class="col-lg-6 col-md-7 col-12">
							<!-- Top Contact -->
							<ul class="top-contact">
								<li><i class="fa fa-phone"></i><?= OUR_TELNUM ?></li>
								<li><i class="fa fa-envelope"></i><a href="mailto:<?= OUR_EMAIL_ADDRESS ?>"><?= OUR_EMAIL_ADDRESS ?></a></li>
							</ul>
							<!-- End Top Contact -->
						</div>
					</div>
				</div>
			</div>
			<!-- End Topbar -->



			<!-- Header Inner -->
			<div class="header-inner">
				<div class="container">
					<div class="inner">
						<div class="row">
							<div class="col-lg-3 col-md-3 col-12">
								<!-- Start Logo -->
								<div class="logo">
									<a href="<?= BASE_URL ?>"><img src="<?= BASE_URL ?>frontend/img/archlogo.png" alt="Archgeo Logo"></a>
								</div>
								<!-- End Logo -->
								<!-- Mobile Nav -->
								<div class="mobile-nav"></div>
								<!-- End Mobile Nav -->
							</div>
							<div class="col-lg-9 col-md-9 col-12">
			
								<!-- Main Menu -->
								<div class="main-menu">
									<nav class="navigation">
										<ul class="nav menu">
											<li class="<?= $current_module == "" ? 'active': '';?>"><a href="<?= BASE_URL ?>">Home </a></li>
                                            
											<li class="<?= $current_module == "abouts" ? 'active': ''; ?>"><a href="<?= BASE_URL ?>abouts">About Us</a></li>

											<!-- <li class="<?= $current_module == "abouts" ? 'active': ''; ?>" ><a href="<?= BASE_URL ?>abouts">About Us <i class="icofont-rounded-down"></i></a>
												<ul class="dropdown">
													<li><a href="<?= BASE_URL ?>teams">Our Team</a></li>
												</ul>
											</li> -->

											<li class="<?= $current_module == "projects" ? 'active': '';?>"><a href="<?= BASE_URL ?>projects">Our Projects </a></li>

											<li class="<?= $current_module == "services" ? 'active': ''; ?>"><a href="<?= BASE_URL ?>services">Services <i class="icofont-rounded-down"></i></a>
												<ul class="dropdown">
													<!-- set this dropdown dynamic -->
													<?= Modules::run('services/_draw_services_list','services') ?>
												</ul>
											</li>
											<!-- <li class="<?= $current_module == "blog_notices" ? 'active': ''; ?>"><a href="<?= BASE_URL ?>blog_notices/all_blogs">Blogs</a></li> -->

											
											<li class="<?= $current_module == "product_items" ? 'active': ''; ?>"><a href="<?= BASE_URL ?>product_items">Shop</a></li>


											<li class="<?= $current_module == "enquiries" ? 'active': ''; ?>"><a href="<?= BASE_URL ?>enquiries">Contact Us</a></li>
										</ul>
									</nav>
								</div>
								<!--/ End Main Menu -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--/ End Header Inner -->
		</header>
		<!-- End Header Area -->
		



		<!-- MAIN CONTENT OF FRONTEND -->

		<?= Template::display($data) ?>

		<!-- END OF MAIN CONTENT FRONTEND -->


	
		<!-- Footer Area -->
		<footer id="footer" class="footer ">
			<!-- Footer Top -->
			<div class="footer-top">
				<div class="container">
					<div class="row">


						<div class="col-lg-4 col-md-6 col-12">
							<div class="single-footer">
								<h2>About Us</h2>
								<p>Visit our social media links below to interact with us and our followers who loves everything we do.</p>
								<!-- Social -->
								<ul class="social">
									<li><a href="#"><i class="icofont-facebook"></i></a></li>
									<li><a href="#"><i class="icofont-google-plus"></i></a></li>
									<li><a href="#"><i class="icofont-twitter"></i></a></li>
									<li><a href="#"><i class="icofont-vimeo"></i></a></li>
									<li><a href="#"><i class="icofont-pinterest"></i></a></li>
								</ul>
								<!-- End Social -->
							</div>
						</div>


						<div class="col-lg-4 col-md-6 col-12">
							<div class="single-footer f-link">
								<h2>Quick Links</h2>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-12">
										<ul>
											<li><a href="<?= BASE_URL ?>"><i class="fa fa-caret-right" aria-hidden="true"></i>Home</a></li>
											<li><a href="<?= BASE_URL ?>abouts"><i class="fa fa-caret-right" aria-hidden="true"></i>About Us</a></li>
											<li><a href="<?= BASE_URL ?>services"><i class="fa fa-caret-right" aria-hidden="true"></i>Services</a></li>
											<li><a href="<?= BASE_URL ?>projects"><i class="fa fa-caret-right" aria-hidden="true"></i>Our Projects</a></li>
											<li><a href="<?= BASE_URL ?>blog_notices"><i class="fa fa-caret-right" aria-hidden="true"></i>Blog</a></li>	
										</ul>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-4 col-md-6 col-12">
							<div class="single-footer">
								<h2>Open Hours</h2>
								<p>Our office is always opened for support, enquiries and consultations.</p>
								<ul class="time-sidual">
									<li class="day">Monday - Friday <span>8.00-18.00</span></li>
									<li class="day">Saturday <span>9.00-16.30</span></li>
								</ul>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<!--/ End Footer Top -->
			<!-- Copyright -->
			<div class="copyright">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-12">
							<div class="copyright-content">
								<p> &copy; Copyright 2021 - <?= date('Y') ?>  <b style="text-transform: uppercase;">archgeo limited</b></p>
								<!-- <p>Designed and Developed by<a href="#" rel="nofollow" target="_blank">evancewebguy@gmail.com</a></p> -->
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--/ End Copyright -->
		</footer>
		<!--/ End Footer Area -->

		<!-- jquery Min JS -->
        <script src="<?= BASE_URL ?>frontend/js/jquery.min.js"></script>
		<!-- jquery Migrate JS -->
		<script src="<?= BASE_URL ?>frontend/js/jquery-migra	te-3.0.0.js"></script>
		<!-- Easing JS -->
        <script src="<?= BASE_URL ?>frontend/js/easing.js"></script>
		<!-- Color JS -->
		<script src="<?= BASE_URL ?>frontend/js/colors.js"></script>
		<!-- Popper JS -->
		<script src="<?= BASE_URL ?>frontend/js/popper.min.js"></script>
		<!-- Bootstrap Datepicker JS -->
		<script src="<?= BASE_URL ?>frontend/js/bootstrap-datepicker.js"></script>
		<!-- Jquery Nav JS -->
        <script src="<?= BASE_URL ?>frontend/js/jquery.nav.js"></script>
		<!-- Slicknav JS -->
		<script src="<?= BASE_URL ?>frontend/js/slicknav.min.js"></script>
		<!-- ScrollUp JS -->
        <script src="<?= BASE_URL ?>frontend/js/jquery.scrollUp.min.js"></script>
		<!-- Niceselect JS -->
		<script src="<?= BASE_URL ?>frontend/js/niceselect.js"></script>
		<!-- Tilt Jquery JS -->
		<script src="<?= BASE_URL ?>frontend/js/tilt.jquery.min.js"></script>
		<!-- Owl Carousel JS -->
        <script src="<?= BASE_URL ?>frontend/js/owl-carousel.js"></script>
		<!-- counterup JS -->
		<script src="<?= BASE_URL ?>frontend/js/jquery.counterup.min.js"></script>
		<!-- Steller JS -->
		<script src="<?= BASE_URL ?>frontend/js/steller.js"></script>
		<!-- Wow JS -->
		<script src="<?= BASE_URL ?>frontend/js/wow.min.js"></script>
		<!-- Magnific Popup JS -->
		<script src="<?= BASE_URL ?>frontend/js/jquery.magnific-popup.min.js"></script>
		<!-- Counter Up CDN JS -->
		<script src="<?= BASE_URL ?>frontend/js/waypoints.min.js"></script>
		<!-- Bootstrap JS -->
		<script src="<?= BASE_URL ?>frontend/js/bootstrap.min.js"></script>
		<!-- Main JS -->
		<script src="<?= BASE_URL ?>frontend/js/main.js"></script>
		<?= $additional_public_includes_btm ?>
    </body>

</html>