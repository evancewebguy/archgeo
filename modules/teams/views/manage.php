<h1><?= $headline ?></h1>
<?php
flashdata();
echo '<p>'.anchor('teams/create', 'Create New Team Record', array("class" => "button")).'</p>'; 
echo Pagination::display($pagination_data);
if (count($rows)>0) { ?>
    <table id="results-tbl">
        <thead>
            <tr>
                <th colspan="7">
                    <div>
                        <div><?php
                        echo form_open('teams/manage/1/', array("method" => "get"));
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
                <th>Full Name</th>
                <th>Job Title</th>
                <th>Email Address</th>
                <th>Facebook</th>
                <th>Twitter</th>
                <th>Instagram</th>
                <th style="width: 20px;">Action</th>            
            </tr>
        </thead>
        <tbody>
            <?php 
            $attr['class'] = 'button alt';
            foreach($rows as $row) { ?>
            <tr>
                <td><?= $row->full_name ?></td>
                <td><?= $row->job_title ?></td>
                <td><?= $row->email_address ?></td>
                <td><?= $row->facebook ?></td>
                <td><?= $row->twitter ?></td>
                <td><?= $row->instagram ?></td>
                <td><?= anchor('teams/show/'.$row->id, 'View', $attr) ?></td>        
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