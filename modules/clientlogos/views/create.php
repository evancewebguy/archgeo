<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Clientlogo Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Client Name');
        echo form_input('client_name', $client_name, array("placeholder" => "Enter Client Name"));
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>