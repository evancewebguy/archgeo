<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        About Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Company Information');
        echo form_textarea('company_information', $company_information, array("placeholder" => "Enter Company Information", "class" =>"cleditor", "id" => "textarea"));
        echo form_label('Mission');
        echo form_textarea('mission', $mission, array("placeholder" => "Enter Mission", "class" =>"cleditor", "id" => "textarea"));
        echo form_label('Vision');
        echo form_textarea('vision', $vision, array("placeholder" => "Enter Vision", "class" =>"cleditor", "id" => "textarea"));
        echo form_label('Core Values');
        echo form_textarea('core_values', $core_values, array("placeholder" => "Enter Core Values", "class" =>"cleditor", "id" => "textarea"));
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>