<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Notice Source Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Source Name');
        echo form_input('source_name', $source_name, array("placeholder" => "Enter Source Name"));
        echo form_label('Author <span>(optional)</span>');
        echo form_input('author', $author, array("placeholder" => "Enter Author"));
        echo form_label('Source Link <span>(optional)</span>');
        echo form_input('source_link', $source_link, array("placeholder" => "Enter Source Link"));
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>