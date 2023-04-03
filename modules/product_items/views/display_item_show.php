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
						<li class="active">shop (- opening soon)</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Breadcrumbs -->


<?php // print_r($product_items); ?>


	<!-- Single News -->
	<section class="blog grid section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-12">
					<div class="row">
					<?php
						foreach ($product_items as $product_item) {
                            // var_dump($projects);    
							if($product_item->picture != '') {
								$product_picture = BASE_URL.'product_items_module/product_items_pics/'.$product_item->id.'/'.$product_item->picture;
							} else {
								$product_picture = BASE_URL.'projects_module/img/home-img1.jpg';
							}		
							$view_project = BASE_URL.'product_items/';
							// view_project/'.$product_item->url_string;
					?>
						<div class="col-lg-3 col-md-4 col-12">
							<!-- Single Blog -->
							<div class="single-news">
								<div class="news-head">
									<img src="<?= $product_picture ?>" alt="<?= $product_item->product_name ?>">
								</div>
                                <div class="news-body">
									<div class="news-content">
										<div><?= $product_item->product_name ?></div>
										<h2>ksh: <?= $product_item->product_price ?></h2>
										<p class="text"><?php                         
                                            // strip tags to avoid breaking any html
                                            $string = strip_tags($product_item->product_description );
                                            if (strlen($string) > 100) {
                                                // truncate string
                                                $stringCut = substr($string, 0, 100);
                                                $endPoint = strrpos($stringCut, ' ');

                                                //if the string doesn't contain any space then it will cut without word basis.
                                                $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                                $string .= '... <br><b><a style="color:#32B87D;" href='.$view_project.'>Read More</a></b>';
                                            }
                                            echo $string;
                                        
                                        ?></p>

                                        <button class="btn"><a href="<?= $view_project ?>">Add to cart</a></button>
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