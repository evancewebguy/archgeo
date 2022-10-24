<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        FAQ Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Question');
        echo form_input('question', $question, array("placeholder" => "Enter Question"));
        echo form_label('Answer');
        echo form_textarea('answer', $answer, array("placeholder" => "Enter Answer"));
        // echo form_label('Associated Service');
        // echo form_dropdown('services_id', $services_options, $services_id);
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>