
    <?php
    // validation_errors();
    // echo form_open($form_location);
    // echo form_label('Your Name');
    // $input_attr['placeholder'] = 'Enter your name here';
    // $input_attr['autocomplete'] = 'off';
    // echo form_input('name', $name, $input_attr);

    // echo form_label('Your Email Address');
    // $input_attr['placeholder'] = 'Enter your email address here';
    // echo form_email('email_address', $email_address, $input_attr);

    // echo form_label('Your Message');
    // $input_attr['placeholder'] = 'Use this space to enter your message';
    // $input_attr['rows'] = 5;
    // echo form_textarea('message', $message, $input_attr);

    // echo '<p>Prove you\'re human by answering the question below!</p>';
    // echo form_label($question);
    // echo form_dropdown('answer', $options, $answer);

    // echo form_submit('submit', 'Submit');
    // echo anchor(BASE_URL, 'Cancel', array('class' => 'button alt'));

    // echo form_close();
    ?>




		<!-- Breadcrumbs -->
		<div class="breadcrumbs overlay">
			<div class="container">
				<div class="bread-inner">
					<div class="row">
						<div class="col-12">
							<h2>Contact Us</h2>
							<ul class="bread-list">
								<li><a href="<?= BASE_URL ?>">Home</a></li>
								<li><i class="icofont-simple-right"></i></li>
								<li class="active">Contact Us</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Breadcrumbs -->
				
		<!-- Start Contact Us -->
		<section class="contact-us section">
			<div class="container">
				<div class="inner">
					<div class="row"> 
						<div class="col-lg-6">
							<div class="contact-us-left">
								<!--Start Google-map -->
								<div id="myMap"></div>
								<!--/End Google-map -->
							</div>
						</div>
						<div class="col-lg-6">
							<div class="contact-us-form">
								<h2>Contact Us</h2>
								<p>If you have any questions please fell free to contact with us.</p>
								<!-- Form -->
                                <?= form_open($form_location, array("class" => "form")); ?>
                                <?=  validation_errors(); ?>
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
                                                <?php
                                                    echo form_label('Your Name');
                                                    $input_attr['placeholder'] = 'Enter your name';
                                                    $input_attr['autocomplete'] = 'off';
                                                    $input_attr['required'] = '';
                                                    echo form_input('name', $name, $input_attr);
                                                ?>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
                                                <?php 
                                                    echo form_label('Your Email Address');
                                                    $input_attr['placeholder'] = 'Enter your email address';
                                                    $input_attr['required'] = '';
                                                    echo form_email('email_address', $email_address, $input_attr);
                                                ?>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
                                            <?php
                                                    echo form_label('Your Phone');
                                                    $input_attr['placeholder'] = 'Enter your phone no';
                                                    $input_attr['autocomplete'] = 'off';
                                                    $input_attr['required'] = '';
                                                    echo form_input('phone', $phone, $input_attr);
                                                ?>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group">
                                            <?php
                                                    echo form_label('Subject');
                                                    $input_attr['placeholder'] = 'Enter Subject';
                                                    $input_attr['autocomplete'] = 'off';
                                                    $input_attr['required'] = '';
                                                    echo form_input('subject', $subject, $input_attr);
                                                ?>
											</div>
										</div>


                                     


                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                
                                                <?php
                                                echo '<p>Prove you\'re human by answering the question below!</p>';
                                                    echo form_label($question);
                                                    echo '<br>';
                                                    echo form_dropdown('answer', $options, $answer, array("class" => "nice-select form-control wide"));
                                                ?>
                                            </div>
                                        </div>
										<div class="col-lg-12">
											<div class="form-group">
                                                <?php 
                                                    echo form_label('Your Message');
                                                    $input_attr['placeholder'] = 'Enter your message';
                                                    $input_attr['rows'] = 5;
                                                    $input_attr['required'] = '';
                                                    echo form_textarea('message', $message, $input_attr);
                                                ?>
											</div>
										</div>
										<div class="col-12">
											<div class="form-group login-btn">
                                                <?php 
                                                    echo form_submit('submit', 'Submit', array("class" => "btn"));
                                                ?>
											</div>
										</div>
									</div>
								<?= form_close(); ?>
								<!--/ End Form -->
							</div>
						</div>
					</div>
				</div>
				<div class="contact-info">
					<div class="row">
						<!-- single-info -->
						<div class="col-lg-4 col-12 ">
							<div class="single-info">
								<i class="icofont icofont-ui-call"></i>
								<div class="content">
									<h3><?= OUR_TELNUM ?></h3>
									<p><?= OUR_EMAIL_ADDRESS ?></p>
								</div>
							</div>
						</div>
						<!--/End single-info -->
						<!-- single-info -->
						<div class="col-lg-4 col-12 ">
							<div class="single-info">
								<i class="icofont-google-map"></i>
								<div class="content">
									<h3><?= OUR_ADDRESS ?></h3>
									<p><?= OUR_ADDRESS2 ?></p>
								</div>
							</div>
						</div>
						<!--/End single-info -->
						<!-- single-info -->
						<div class="col-lg-4 col-12 ">
							<div class="single-info">
								<i class="icofont icofont-wall-clock"></i>
								<div class="content">
									<h3>Mon - Fri: 8am - 5pm</h3>
									<p>Saturday and Sunday Closed</p>
								</div>
							</div>
						</div>
						<!--/End single-info -->
					</div>
				</div>
			</div>
		</section>
		<!--/ End Contact Us -->