<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Service Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Service Name');
        echo form_input('service_name', $service_name, array("placeholder" => "Enter Service Name"));
        echo form_label('Service Description');
        echo form_textarea('service_description', $service_description, array("placeholder" => "Enter Service Description", "class" =>"cleditor", "id" => "textarea"));
        echo form_label('Associated Faq');
        echo form_dropdown('faqs_id', $faqs_options, $faqs_id);
        // echo form_label('Associated Faq');
        // echo form_dropdown('faqs_id', $faqs_options, $faqs_id);
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>