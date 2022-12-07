<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/trongate.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/admin-slide-nav.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>css/trongate-datetime.css">
    <link rel="stylesheet" href="<?= THEME_DIR ?>css/bootstrappy.css">

	<?= $additional_includes_top ?>

	<title>Archgeo Administrator</title>
</head>
<body>
<div class="top-gutter">
	<div class="logo"><?= anchor('dashboards/manage', OUR_NAME) ?></div>
	<div class="top-rhs">
		<div class="hide-sm"><?= anchor('enquiries/manage', '<i class=\'fa fa-envelope\'></i> Enquiries(!)', array('class' => 'highlight')) ?></div>
		<!-- <div class="hide-sm"><?= anchor('#', '<i class=\'fa fa-shopping-cart\'></i> Orders') ?></div> -->
		<div class="hide-sm language-selector">
		<?php
			$language_options['ar'] = 'Arabic';
			$language_options['en'] = 'English';
			$language_options['fr'] = 'French';
			echo form_dropdown('language', $language_options, 'en');
		?>
		</div>
		<div id="top-rhs-selector">
		<i class="fa fa-user"></i><span id="admin-down-arrow">▼</span></div>
		<div id="admin-settings-dropdown">
			<ul>
				<li><?= anchor('trongate_administrators/create/1', '<i class=\'fa fa-shield\'></i> Update Your Details ') ?></li>
				<li><?= anchor('trongate_administrators/manage', '<i class=\'fa fa-users\'></i> Manage Admin Users ') ?></li>
				<li class="top-border"><?= anchor('trongate_administrators/logout', '<i class=\'fa fa-sign-out\'></i> Logout ') ?></li>
			</ul>
		</div>
		<div id="hamburger" class="hide-lg" onclick="openSlideNav()">&#9776;</div>
	</div>
</div>
<div class="wrapper" style="opacity:0">

	<div id="sidebar">
		<?= Template::partial('partials/admin/dynamic_bootstrappy_nav') ?>
	</div>


	<div class="center-stage"><?= Template::display($data) ?></div>
</div>
<div class="footer">
	<h4>For Support Email me at: 
		<a href="mailto:evancewebguy@gmail.com">evancewebguy@gmail.com </a>
	</h4>
</div>

<div id="slide-nav">
    <div id="close-btn" onclick="closeSlideNav()">&times;</div>
    <ul auto-populate="true"></ul>
</div>

<script src="<?= BASE_URL ?>js/admin.js"></script>
<script src="<?= THEME_DIR ?>js/bootstrappy.js"></script>
<script src="<?= BASE_URL ?>js/trongate-datetime.js"></script>

<?= $additional_includes_btm ?>
</body>
</html>