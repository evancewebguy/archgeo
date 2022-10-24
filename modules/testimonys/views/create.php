<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Testimony Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Testimony');
        echo form_textarea('testimony', $testimony, array("placeholder" => "Enter Testimony"));
        echo form_label('Platform');
        echo form_input('platform', $platform, array("placeholder" => "Enter Platform"));
        echo form_label('Name');
        echo form_input('name', $name, array("placeholder" => "Enter Name"));
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>