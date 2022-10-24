
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
								<li class="active">Faq</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Breadcrumbs -->



		 <!-- Start Faq -->
         <section class="faq-area section">
            <div class="container">

            <?php 
            foreach($services as $service){
            ?>
                <div class="row faq-wrap">
                    <div class="col-lg-12">
                        <div class="faq-head">
                            <h2><?= $service->service_name ?></h2>
                        </div>
                        <div class="faq-item">
                            <ul class="accordion">
                                <?php 
                                    foreach($faqs as $faq){

                                        // var_dump($faqs);
                                        if($service->id === $faq['services_id'] ){
                                ?>

                                    <li class="wow fadeInUp" data-wow-delay=".3s">
                                        <a><?= $faq['question'] ?></a>
                                        <p><?= $faq['answer'] ?></p>
                                    </li>
                                <?php }else{ ?>
                                    <li class="wow fadeInUp" data-wow-delay=".3s">
                                        <p>FAQs under this service will be available soon</p>
                                    </li>
                                <?php }} ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php } ?>

            </div>
        </section>
        <!-- End Faq -->