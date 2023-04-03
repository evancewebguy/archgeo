 <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Page Title </title>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <link rel="canonical" href="<?= current_url() ?>" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="  " />
    <meta property="og:description" content="" />
    <meta property="og:url" content="<?= current_url() ?>" />
    <meta property="og:site_name" content="" />
    <meta property="og:image" content="" />
    <meta name="robots" content="index, follow">

    <!-- font -->
   <link rel="stylesheet" href="https://use.typekit.net/sud0zuo.css">
      
    <link rel="stylesheet" href="<?= BASE_URL ?>css/trongate.css">
    <script src="https://kit.fontawesome.com/967841f81f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    
    <link rel="stylesheet" href="<?= BASE_URL ?>blog_notices_module/css/app.css">

</head>

<body>
<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return;
js = d.createElement(s); js.id = id;
js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

    <header>
        <div id="header-container">
        <span id="logo"> <a href="<?= BASE_URL ?>">  SITE NAME</a></span>
            <div id="menu-toggle" class="burger">
                <div id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div id="cross">
                    <span></span>
                    <span></span>
                </div>
            </div>

            <nav>
                <ul>
                    <li><a href="<?= BASE_URL ?>">Home </a></li>
                   <li><a href="<?= BASE_URL ?>blog_notices/all">Blog</a></li>
                </ul>
            </nav>
        </div>

      
    </header>
    
        <?php  
        if(isset($class)) {
            $print_class = $class;
        } else {
            $print_class = '';
        }
        ?>
        <div id="master-container" <?= $print_class ?> >
        <div  class="landing">
        <h1>Blog Notices & Publications</h1>
    </div>
            
    <section class="projects-nav border-bottom">

        <ul class="portfolio-filter">

            <li class="active"><a href="<?= BASE_URL.'blog_notices/all' ?>" > <p> All  </p></a></li>

        <?php 
            foreach ($categories as $category) {
                $category_url = BASE_URL.'blog_notices/category/'.$category->url_string;
        ?>                   
                <li><a href="<?= $category_url ?>" ><p><?= $category->category_name ?> </p></a></li>
            <?php } ?>  
                    
        </ul>
            
        
    </section>
            <?php 
    if ($total_rows>0) { 
        $i = 0; 
        ?>
        <section class="publications-container">

        <?php 
       
            foreach ($blog_notices as $blog_notice) {
                if($blog_notice->picture != '') {
                    $blog_notices_picture = BASE_URL.'blog_notices_pics/'.$blog_notice->blog_notices_id.'/'.$blog_notice->picture;
                } else {
                    $blog_notices_picture = BASE_URL.'blog_notices_module/img/home-img1.jpg';
                }
                
                $view_blog_notice = BASE_URL.'blog_notices/display/'.$blog_notice->url_string;
                $blog_categories = $blog_notice->categories;
            ?>
            <div class="publication">
                    <a href="<?= $view_blog_notice ?>" id="" >
                <div class="news-img">
                    <img src="<?= $blog_notices_picture ?>" alt="<?= $blog_notice->blog_title ?>"> 
                </div>
                <div class="news-title">
                    <p><?= date('M j \, Y',  strtotime($blog_notice->published_date)) ?></p>
                    <h4><?= $blog_notice->blog_title ?> </h4>
                    <p>BY <?= $blog_notice->notice_source_name ?></p>

                    <p > <b> <?= implode(', ', $blog_notice->categories) ?></b></p>                
                </div>
                </a>
            </div>
            <?php } ?> 
        </section>
        <section class=" border-top">
        <?php
        unset($data['include_showing_statement']);
        echo Pagination::display($data);
        } else { echo 'No blog notices yet';}
        ?>
        </section>
        
    </div>

    <footer >

       <ul>
           <li>Website Name</li>           
           <li>Address</li>
           <li> Phone</li>
           <li><a href="mailto:#">Email</a></li>
       </ul>
       <ul class="social-media">
            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
            <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
            <li><a href="#"><i class="fab fa-facebook"></i></a></li>
       </ul>

       <p>Website Credits</p>           
    </footer>

    <script src="<?= BASE_URL ?>blog_notices_module/js/jquery-3.6.0.min.js"></script>
    <!-- Include the Quill library -->
   <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script src="<?= BASE_URL ?>blog_notices_module/js/myjquery.js"></script>

</body>

</html>




