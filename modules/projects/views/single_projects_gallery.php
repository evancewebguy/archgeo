<div id="owl-demo" class="owl-carousel">
    <?php 
		$news_title = $projects_obj->client_name;
		$count = 0; 
		foreach($gallery_pics as $gallery_pic) {
			$count++;
			$pic_path = $gallery_dir.$gallery_pic->picture;
			$pic_path_full = $gallery_dir_full.$gallery_pic->picture;
			$alt_text = $news_title.' - picture '.$count;
		?>
		<div class="item">
            <a href="<?= $pic_path_full ?>" 
				data-toggle="lightbox" 
				data-gallery="example-gallery" 
				data-title="<?= $news_title ?>" 
				data-footer="Nature of the Project: <?= $projects_obj->nature_of_the_project ?>">
                <img src="<?= $pic_path ?>" alt="<?= $alt_text ?>" style="height:300px" class="img-fluid">
            </a>
        </div>
    <?php } ?>               
</div>



<style>
	#owl-demo .item{
	margin: 3px;
	}
	#owl-demo .item img{
	display: block;
	width: 100%;
	height: auto;
	}
	.owl-carousel .item {
	position: relative;
	z-index: 100; 
	-webkit-backface-visibility: hidden; 
	}
	/* end fix */
	.owl-nav > div {
	margin-top: -26px;
	position: absolute;
	top: 50%;
	color: #cdcbcd;
	}
	.owl-nav i {
	font-size: 52px;
	}
	.owl-nav .owl-prev {
	left: -30px;
	}
	.owl-nav .owl-next {
	right: -30px;
	}
</style>

<script>
	$(document).ready(function() {
		$("#owl-demo").owlCarousel({
			loop: true,
			margin: 10,
			nav: true,
			navText: [
				"<i class='fa fa-caret-left'></i>",
				"<i class='fa fa-caret-right'></i>"
			],
			autoplay: true,
			autoplayHoverPause: true,
			items : 3,
			itemsDesktop : [1199,1],
			itemsDesktopSmall : [979,1],
			responsive: {
				0: {
				items: 1
				},
				600: {
				items: 2
				},
				1000: {
				items: 3
				}
			}
		});
	});


    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox(
        );
    });


</script>


