<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Team Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Full Name');
        echo form_input('full_name', $full_name, array("placeholder" => "Enter Full Name"));
        echo form_label('Job Title');
        echo form_input('job_title', $job_title, array("placeholder" => "Enter Job Title"));
        echo form_label('Biography');
        echo form_textarea('biography', $biography, array("placeholder" => "Enter Biography"));
        echo form_label('Email Address');
        echo form_input('email_address', $email_address, array("placeholder" => "Enter Email Address"));
        echo form_label('Facebook');
        echo form_input('facebook', $facebook, array("placeholder" => "Enter Facebook"));
        echo form_label('Twitter');
        echo form_input('twitter', $twitter, array("placeholder" => "Enter Twitter"));
        echo form_label('Instagram');
        echo form_input('instagram', $instagram, array("placeholder" => "Enter Instagram"));
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>