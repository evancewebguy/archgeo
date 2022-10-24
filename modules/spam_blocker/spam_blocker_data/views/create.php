<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Spam Blocker Data Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Element');
        echo form_dropdown('element', $element_options, $data['element']);
        echo form_label('Target String');
        echo form_input('target_string', $target_string, array("placeholder" => "Enter Target String", "autocomplete" => "off"));
        echo form_label('Score');
        echo form_number('score', $score, array("placeholder" => "Enter Score", "autocomplete" => "off"));
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>