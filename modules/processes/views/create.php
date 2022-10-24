<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Process Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Process Name');
        echo form_input('process_name', $process_name, array("placeholder" => "Enter Process Name"));
        echo form_label('Process Description');
        echo form_textarea('process_description', $process_description, array("placeholder" => "Enter Process Description"));
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>