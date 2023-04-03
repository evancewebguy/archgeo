<!-- <ul>
    <li><?= anchor('faqs/manage', '<i class="fa fa-question-circle-o"></i> Manage Faqs') ?></li>
    <li><?= anchor('projects/manage', '<i class="fa fa-list-ol"></i> Manage Projects') ?></li>
    <li><?= anchor('blog_notices/manage', '<i class="fa fa-list-ol"></i> Blog Notices') ?></li>
    <li><?= anchor('blog_categories/manage', '<i class="fa fa-list-ol"></i> Blog Categories') ?></li>

    
    <li><?= anchor('enquiries/manage', '<i class="fa fa-gears"></i> Manage Messages') ?></li>

    <li><?= anchor('services/manage', '<i class="fa fa-gears"></i> Manage Services') ?></li>
    <li><?= anchor('teams/manage', '<i class="fa fa-group"></i> Manage Teams') ?></li>
    <li><?= anchor('abouts/manage', '<i class="fa fa-file-o"></i> Manage About Us') ?></li>
    <li><?= anchor('testimonys/manage', '<i class="fa fa-thumbs-up"></i> Manage Testimonies') ?></li>
    <li><?= anchor('processes/manage', '<i class="fa fa-tree"></i> Manage Processes') ?></li>
    <li><?= anchor('clientlogos/manage', '<i class="fa fa-signing"></i> Manage Clientlogos') ?></li>
    <li><?= anchor('newsletters/manage', '<i class="fa fa-envelope-o"></i> Manage Newsletters') ?></li>
    <li><?= anchor('sliders/manage', '<i class="fa fa-exchange"></i> Manage Sliders') ?></li>
    <li><?= anchor('dashboards/manage', '<i class="fa fa-dashboard"></i> Dashboard') ?></li>
</ul> -->



<nav id="left-nav">
	<ul>
		<li><?= anchor('dashboards/manage', '<i class=\'fa fa-tachometer\'></i> Dashboard') ?></li>
		<li><?= anchor('dashboards/manage', '<i class=\'fa fa-shopping-cart\'></i> Orders') ?></li>
        <li><?= anchor('newsletters/manage', '<i class="fa fa-envelope-o"></i> Manage Newsletters') ?></li>
        <li><?= anchor('services/manage', '<i class="fa fa-gears"></i> Manage Services') ?></li>

        <!-- Project dropdown -->
        <li class="dropdown"><div><i class="fa fa-list-ol"></i> Manage Projects</div><div><i class="fa fa-caret-right"></i></div></li>
        <li class="dropdown-area">
			<ul>
                <li><?= anchor('projects/manage', 'Projects') ?></li>
                <li><?= anchor('project_categorys/manage', 'Project Categories') ?></li>
	  	    </ul>				
		</li>

        <!-- Shop Management dropdown -->
		<li class="dropdown"><div><i class="fa fa-file-text-o"></i> Shop Management</div><div><i class="fa fa-caret-right"></i></div></li>
		<li class="dropdown-area">
			<ul>
		  		<li><?= anchor('categories/manage', 'Product Categories') ?></li>
                <li><?= anchor('product_items/manage', 'Manage Products') ?></li>
	  	    </ul>				
		</li>

        <!-- Blog dropdown -->
        <li class="dropdown"><div><i class="fa fa-list-ol"></i> Manage News</div><div><i class="fa fa-caret-right"></i></div></li>
        <li class="dropdown-area">
			<ul>
                <li><?= anchor('blog_notices/manage', 'Blog Notices') ?></li>
                <li><?= anchor('blog_categories/manage', 'Blog Categories') ?></li>
	  	    </ul>				
		</li>

        <!-- Messages Dropdown -->
		<li class="dropdown"><div><i class="fa fa-envelope"></i> Enquiries</div><div><i class="fa fa-caret-right"></i></div></li>
        <li class="dropdown-area">
			<ul>
                <li><?= anchor('enquiries/manage', 'Inbox') ?></li>
		  		<li><?= anchor('dashboards/manage', 'Junk') ?></li>
		  		<li><?= anchor('dashboards/manage', 'Archives') ?></li>
	  	    </ul>				
		</li>

        <!-- Content Management dropdown -->
		<li class="dropdown"><div><i class="fa fa-file-text-o"></i> Content Management</div><div><i class="fa fa-caret-right"></i></div></li>
		<li class="dropdown-area">
			<ul>
		  		<li><?= anchor('abouts/manage', 'About Us') ?></li>
                <li><?= anchor('sliders/manage', 'Manage Sliders') ?></li>
                <li><?= anchor('clientlogos/manage', 'Client Logos') ?></li>
                <li><?= anchor('processes/manage', 'Manage Processes') ?></li>
                <li><?= anchor('faqs/manage', 'Manage Faqs') ?></li>
		  		<li><?= anchor('dashboards/manage', 'Refund Policy') ?></li>
		  		<li><?= anchor('dashboards/manage', 'Terms &amp; Conditions') ?></li>
	  	    </ul>				
		</li>

        <!-- Setting dropdown -->
		<li class="dropdown"><div><i class="fa fa-gears"></i> Settings</div><div><i class="fa fa-caret-right"></i></div></li>
		<li class="dropdown-area">
			<ul>
                <li><?= anchor('teams/manage', 'Manage Teams') ?></li>
                <li><?= anchor('testimonys/manage', 'Manage Testimonies') ?></li>
		  		<!-- <li><?= anchor('#', 'Second Link') ?></li>
		  		<li><?= anchor('#', 'Third Link') ?></li> -->
	  	    </ul>				
		</li>
    </ul>
</nav>