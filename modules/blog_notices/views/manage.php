<h1><?= $headline ?></h1>
<?php
flashdata();
echo '<p>'.anchor('blog_notices/create', 'Create New Blog Notice Record', array("class" => "button")).'</p>'; 
echo Pagination::display($pagination_data);
if (count($rows)>0) { ?>
    <table id="results-tbl">
        <thead>
            <tr>
                <th colspan="7">
                    <div>
                        <div><?php
                        echo form_open('blog_notices/manage/1/', array("method" => "get"));
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
                <th>Blog Title</th>
                <th>Blog Sub Title</th>
                <th>Youtube Video ID</th>
                <th>Uploaded Date</th>
                <th>Pubished Date</th>
                <th>Published</th>
                <th style="width: 20px;">Action</th>            
            </tr>
        </thead>
        <tbody>
            <?php 
            $attr['class'] = 'button alt';
            foreach($rows as $row) { ?>
            <tr>
                <td><?= $row->blog_title ?></td>
                <td><?= $row->blog_sub_title ?></td>
                <td><?= $row->youtube ?></td>
                <td><?= date('l jS F Y',  strtotime($row->uploaded_date)) ?></td>
                <td><?= date('l jS F Y',  strtotime($row->published_date)) ?></td>
                <td><?= $row->published ?></td>
                <td><?= anchor('blog_notices/show/'.$row->id, 'View', $attr) ?></td>        
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