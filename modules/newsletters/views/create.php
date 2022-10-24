<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Newsletter Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Email Address');
        echo form_input('email_address', $email_address, array("placeholder" => "Enter Email Address"));
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>