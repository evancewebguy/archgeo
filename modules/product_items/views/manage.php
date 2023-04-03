<h1><?= $headline ?></h1>
<?php
flashdata();
echo '<p>'.anchor('product_items/create', 'Create New Product Item Record', array("class" => "button")).'</p>'; 
echo Pagination::display($pagination_data);
if (count($rows)>0) { ?>
    <table id="results-tbl">
        <thead>
            <tr>
                <th colspan="6">
                    <div>
                        <div><?php
                        echo form_open('product_items/manage/1/', array("method" => "get"));
                        echo form_input('searchphrase', '', array("placeholder" => "Search records..."));
                        echo form_submit('submit', 'Search', array("class" => "alt"));
                        echo form_close();
                        ?></div>
                        <div>Records Per Page: <?php
                        $dropdown_attr['onchange'] = 'setPerPage()';
                        echo form_dropdown('per_page', $per_page_options, $selected_per_page, $dropdown_attr); 
                        ?></div>

                    </div>                    
                </th>
            </tr>
            <tr>
                <th>Product Name</th>
                <th>Product Price</th>
                <th>Product Availability</th>
                <th>Product Code</th>
                <!-- <th>Product Reviews</th> -->
                <th style="width: 20px;">Action</th>            
            </tr>
        </thead>
        <tbody>
            <?php 
            $attr['class'] = 'button alt';
            foreach($rows as $row) { ?>
            <tr>
                <td><?= $row->product_name ?></td>
                <td><?= $row->product_price ?></td>
                <td><?= $row->product_availability ?></td>
                <td><?= $row->product_code ?></td>
                <!-- <td><?= $row->product_reviews ?></td> -->
                <td><?= anchor('product_items/show/'.$row->id, 'View', $attr) ?></td>        
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
<?php 
    if(count($rows)>9) {
        unset($pagination_data['include_showing_statement']);
        echo Pagination::display($pagination_data);
    }
}
?>