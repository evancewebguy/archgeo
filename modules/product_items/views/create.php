<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Product Item Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Product Name');
        echo form_input('product_name', $product_name, array("placeholder" => "Enter Product Name"));
        echo form_label('Product Description');
        echo form_textarea('product_description', $product_description, array("placeholder" => "Enter Product Description"));
        echo form_label('Product Price');
        echo form_number('product_price', $product_price, array("placeholder" => "Enter Product Price"));
        echo form_label('Product Availability');
        echo form_dropdown('product_availability', array("In Stock" => 'In Stock', "Out Of Stock" => 'Out Of Stock'), 'In Stock');

        echo form_label('Categories');
        echo form_dropdown('category', $category_options, '');
        // echo form_label('Product Code');
        // echo form_input('product_code', $product_code, array("placeholder" => "Enter Product Code"));
        // echo form_label('Product Reviews');
        // echo form_input('product_reviews', $product_reviews, array("placeholder" => "Enter Product Reviews"));
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>