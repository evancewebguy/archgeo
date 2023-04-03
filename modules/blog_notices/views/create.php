<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Blog Notice Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Blog Title');
        echo form_input('blog_title', $blog_title, array("placeholder" => "Enter Blog Title"));
        echo form_label('Blog Sub Title <span>(optional)</span>');
        echo form_input('blog_sub_title', $blog_sub_title, array("placeholder" => "Enter Blog Sub Title"));
        echo form_label('Notice');
        echo form_textarea('notice', $notice, array("placeholder" => "Enter Notice", "class" =>"cleditor", "id" => "textarea"));
        echo form_label('Youtube Video ID <span>(optional)</span>');
        echo form_input('youtube', $youtube, array("placeholder" => "Enter Youtube Video ID"));
        echo form_label('Pubished Date');
        $attr = array("class"=>"date-picker", "autocomplete"=>"off", "placeholder"=>"Select Pubished Date");
        echo form_input('published_date', $published_date, $attr);
        echo '<div>';
        // echo form_label('Associated Notice_source');
        // echo form_dropdown('notice_sources_id', $notice_sources_options, $notice_sources_id);
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>