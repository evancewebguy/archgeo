<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Slider Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Title');
        echo form_textarea('title', $title, array("placeholder" => "Enter Title", "class" =>"cleditor", "id" => "textarea"));
        echo form_label('Description');
        echo form_textarea('description', $description, array("placeholder" => "Enter Description", "class" =>"cleditor", "id" => "textarea"));
        echo form_label('Link <span>(optional)</span>');
        echo form_input('link', $link, array("placeholder" => "Enter Link"));
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>