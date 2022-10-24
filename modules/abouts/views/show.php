<h1><?= $headline ?></h1>

<!-- <span class="smaller hide-sm">(Record ID: <?= $update_id ?>)</span> -->

<?= flashdata() ?>
<div class="card">
    <div class="card-heading">
        Options
    </div>
    <div class="card-body">
        <?php 
        echo anchor('abouts/manage', 'View All Abouts', array("class" => "button alt"));
        echo anchor('abouts/create/'.$update_id, 'Update Details', array("class" => "button"));
        $attr_delete = array( 
            "class" => "danger go-right",
            "id" => "btn-delete-modal",
            "onclick" => "openModal('delete-modal')"
        );
        echo form_button('delete', 'Delete', $attr_delete);
        ?>
    </div>
</div>

<div class="three-col">
    <div class="card">
        <div class="card-heading">
            About Details
        </div>
        <div class="card-body">
            <div class="record-details">
                <div class="row">
                    <div class="full-width">
                        <div><b>Company Information</b></div>
                        <div>
                            <?php 
                                $limit =100;
                                if (strlen($company_information) > $limit){
                                    $new_description = substr($company_information, 0, $limit) .'...';
                                    echo nl2br($new_description);
                                }else{
                                    echo nl2br($company_information);
                                }
                            ?>

                        </div>
                        <div class="text-right"><button class="alt smaller" onclick="openModal('article-preview-1')">READ ARTICLE</button></div>
                    </div>
                </div>
                <div class="row">
                    <div class="full-width">
                        <div><b>Mission</b></div>
                        <div>
                            <?php 
                                $limit =100;
                                if (strlen($mission) > $limit){
                                    $new_description = substr($mission, 0, $limit) .'...';
                                    echo nl2br($new_description);
                                }else{
                                    echo nl2br($mission);
                                }
                            ?>
                        </div>
                        <div class="text-right"><button class="alt smaller" onclick="openModal('article-preview-2')">READ ARTICLE</button></div>
                    </div>
                </div>
                <div class="row">
                    <div class="full-width">
                        <div><b>Vision</b></div>
                        <div>
                            <?php 
                                $limit =100;
                                if (strlen($vision) > $limit){
                                    $new_description = substr($vision, 0, $limit) .'...';
                                    echo nl2br($new_description);
                                }else{
                                    echo nl2br($vision);
                                }
                            ?>
                        </div>
                        <div class="text-right"><button class="alt smaller" onclick="openModal('article-preview-3')">READ ARTICLE</button></div>
                    </div>
                </div>
                <div class="row">
                    <div class="full-width">
                        <div><b>Core Values</b></div>
                        <div>
                            <?php 
                                $limit =100;
                                if (strlen($core_values) > $limit){
                                    $new_description = substr($core_values, 0, $limit) .'...';
                                    echo nl2br($new_description);
                                }else{
                                    echo nl2br($core_values);
                                }
                            ?>
                        </div>
                        <div class="text-right"><button class="alt smaller" onclick="openModal('article-preview-4')">READ ARTICLE</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-heading">
            Picture
        </div>
        <div class="card-body picture-preview">
            <?php
            if ($draw_picture_uploader == true) {
                echo form_open_upload(segment(1).'/submit_upload_picture/'.$update_id);
                echo validation_errors();
                echo '<p>Please choose a picture from your computer and then press \'Upload\'.</p>';
                echo form_file_select('picture');
                echo form_submit('submit', 'Upload');
                echo form_close();
            } else {
                $picture_path = BASE_URL.segment(1).'_pics/'.$update_id.'/'.$picture;
            ?>
                <p class="text-center">
                    <button class="danger" onclick="openModal('delete-picture-modal')"><i class="fa fa-trash"></i> Delete Picture</button>
                </p>
                <p class="text-center">
                    <img src="<?= $picture_path ?>" alt="picture preview">
                </p>

                <div class="modal" id="delete-picture-modal" style="display: none;">
                    <div class="modal-heading danger"><i class="fa fa-trash"></i> Delete Picture</div>
                    <div class="modal-body">
                        <?= form_open(segment(1).'/ditch_picture/'.$update_id) ?>
                            <p>Are you sure?</p>
                            <p>You are about to delete the picture.  This cannot be undone. Do you really want to do this?</p>
                            <p>
                                <button type="button" name="close" value="Cancel" class="alt" onclick="closeModal()">Cancel</button>
                                <button type="submit" name="submit" value="Yes - Delete Now" class="danger">Yes - Delete Now</button>
                            </p>
                        <?= form_close() ?>
                    </div>
                </div>

            <?php 
            }
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-heading">
            Comments
        </div>
        <div class="card-body">
            <div class="text-center">
                <p><button class="alt" onclick="openModal('comment-modal')">Add New Comment</button></p>
                <div id="comments-block"><table></table></div>
            </div>
        </div>
    </div>
</div>



<div class="modal" id="article-preview-1">
    <div class="modal-heading">Company Information Preview <span class="float-right close-preview" onclick="closeModal()">&#10005;</span></div>
    <div class="modal-body">
        <div class="text-left">
            <?= nl2br($company_information); ?>
        </div>
    </div>
</div>

<div class="modal" id="article-preview-2">
    <div class="modal-heading">Mission Preview <span class="float-right close-preview" onclick="closeModal()">&#10005;</span></div>
    <div class="modal-body">
        <div class="text-left">
            <?= nl2br($mission); ?>
        </div>
    </div>
</div>

<div class="modal" id="article-preview-3">
    <div class="modal-heading">Vision Preview <span class="float-right close-preview" onclick="closeModal()">&#10005;</span></div>
    <div class="modal-body">
        <div class="text-left">
            <?= nl2br($vision); ?>
        </div>
    </div>
</div>

<div class="modal" id="article-preview-4">
    <div class="modal-heading">Core Values Preview <span class="float-right close-preview" onclick="closeModal()">&#10005;</span></div>
    <div class="modal-body">
        <div class="text-left">
            <?= nl2br($core_values); ?>
        </div>
    </div>
</div>

<div class="modal" id="comment-modal" style="display: none;">
    <div class="modal-heading"><i class="fa fa-commenting-o"></i> Add New Comment</div>
    <div class="modal-body">
        <p><textarea placeholder="Enter comment here..."></textarea></p>
        <p><?php
            $attr_close = array( 
                "class" => "alt",
                "onclick" => "closeModal()"
            );
            echo form_button('close', 'Cancel', $attr_close);
            echo form_button('submit', 'Submit Comment', array("onclick" => "submitComment()"));
            ?>
        </p>
    </div>
</div>

<div class="modal" id="delete-modal" style="display: none;">
    <div class="modal-heading danger"><i class="fa fa-trash"></i> Delete Record</div>
    <div class="modal-body">
        <?= form_open('abouts/submit_delete/'.$update_id) ?>
        <p>Are you sure?</p>
        <p>You are about to delete an About record.  This cannot be undone.  Do you really want to do this?</p> 
        <?php 
        echo '<p>'.form_button('close', 'Cancel', $attr_close);
        echo form_submit('submit', 'Yes - Delete Now', array("class" => 'danger')).'</p>';
        echo form_close();
        ?>
    </div>
</div>

<script>
var token = '<?= $token ?>';
var baseUrl = '<?= BASE_URL ?>';
var segment1 = '<?= segment(1) ?>';
var updateId = '<?= $update_id ?>';
var drawComments = true;
</script>