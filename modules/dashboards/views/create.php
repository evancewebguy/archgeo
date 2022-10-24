<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Dashboard Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('viewed time');
        $attr = array("class"=>"time-picker", "autocomplete"=>"off", "placeholder"=>"Select viewed time");
        echo form_input('viewed_time', $viewed_time, $attr);
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>