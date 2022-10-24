<section class="photos-project ">
    <div class="image-slider">
        <div class="pf-details-slider">

            <?php 
            $news_title = $blog_notices_obj->blog_title;
            $count = 0; 

            foreach($gallery_pics as $gallery_pic) {
                $count++;
                $pic_path = $gallery_dir.$gallery_pic->picture;
                $alt_text = $news_title.' - picture '.$count;

                 echo '<img src="'.$pic_path.'" alt="'.$alt_text.'">';
            }
            ?> 
        </div>
    </div>          
</section> 




