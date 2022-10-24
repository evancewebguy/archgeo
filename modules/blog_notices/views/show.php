<h1><?= $headline ?></h1> 
<!-- <span class="smaller hide-sm">(Record ID: <?= $update_id ?>)</span> -->
<?= flashdata() ?>
<div class="card">
    <div class="card-heading">
        Options
    </div>
    <div class="card-body">
        <?php 
        echo anchor('blog_notices/manage', 'View All Blog Notices', array("class" => "button alt"));
        echo anchor('blog_notices/create/'.$update_id, 'Update Details', array("class" => "button"));
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
    <div class="card record-details">
        <div class="card-heading">
            Blog Notice Details
        </div>
        <div class="card-body">
            <div><span>Blog Title</span><span><?= $blog_title ?></span></div>
            <div><span>Blog Sub Title</span><span><?= $blog_sub_title ?></span></div>
            <div><span>Notice</span><span><?= $notice ?></span></div>
            <div class="text-right"><button class="alt smaller" onclick="openModal('article-preview')">READ ARTICLE</button></div>

            <div><span>Youtube Video Id</span><span><?= $youtube ?></span></div>

            <div><span>Uploaded Date:</span> <span><?= date('l jS F Y',  strtotime($uploaded_date)) ?></span></div>
            <div><span>Pubished Date:</span> <span><?= date('l jS F Y',  strtotime($published_date)) ?></span></div>
            <div><span>Published</span><span> <?= $published ?></span></div>
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
        <div class="card-heading">
        Video en Youtube <span class="smaller">Id: <?= $youtube ?></span>
    </div>
    <div class="card-body picture-preview">
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?= $youtube ?>?rel=0" allowfullscreen></iframe>
        </div>
    </div>
    </div>
    <div>
    <?= Modules::run('module_relations/_draw_summary_panel', 'blog_categories', $token) ?>
    <?= Modules::run('my_filezone/_draw_summary_panel', $update_id, $filezone_settings); ?>
    
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



<div class="modal" id="article-preview">
    <div class="modal-heading">Blog Notice Preview <span class="float-right close-preview" onclick="closeModal()">&#10005;</span></div>
    <div class="modal-body">
        <div class="text-left">
            <h1><?= $blog_title ?></h1>
            <h4><?= $blog_sub_title ?></h4>
            <?= nl2br($notice ) ?>
        </div>
    </div>
</div>



<div class="modal" id="delete-modal" style="display: none;">
    <div class="modal-heading danger"><i class="fa fa-trash"></i> Delete Record</div>
    <div class="modal-body">
        <?= form_open('blog_notices/submit_delete/'.$update_id) ?>
        <p>Are you sure?</p>
        <p>You are about to delete a Blog Notice record.  This cannot be undone.  Do you really want to do this?</p> 
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
</script><script async src='/cdn-cgi/challenge-platform/h/g/scripts/invisible.js'></script><script type="text/javascript">(function(){window['__CF$cv$params']={r:'6d8f375f5af6d73d',m:'dL90lwGyan4Vr1OOuDCaq1wNao3rDGn6NWzoyL.sbbg-1644096379-0-AUmvZMDURUW4r7s8oTUgncQjOyriMWsLdyvvGRJGwVVbUKVAvkTyvp/QqM9V8Fr7SFejFEcbVnGcjNHSuJEZP62cCtGK1oVqRb+XMwnWklMao6tk5XoubVhJ2Q9ntfto8w==',s:[0x60ab50310e,0x3809391fcd],u:'/cdn-cgi/challenge-platform/h/g'}})();</script>