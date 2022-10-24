<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Project Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Client Name <span>(optional)</span>');
        echo form_input('client_name', $client_name, array("placeholder" => "Enter Client Name"));
        echo form_label('Nature of the project');
        echo form_input('nature_of_the_project', $nature_of_the_project, array("placeholder" => "Enter Nature of the project"));
        echo form_label('Year');
        $attr = array("class"=>"date-picker", "autocomplete"=>"off", "placeholder"=>"Select Year");
        echo form_input('year', $year, $attr);
        echo form_label('Status');
        echo form_input('status', $status, array("placeholder" => "Enter Status"));
        echo form_label('Project Description');
        echo form_textarea('project_description', $project_description, array("placeholder" => "Enter Project Description", "class" =>"cleditor", "id" => "textarea"));
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>