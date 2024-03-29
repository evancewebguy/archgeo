<?php
class Product_items extends Trongate {

    private $default_limit = 20;
    private $per_page_options = array(10, 20, 50, 100);    

    function create() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $update_id = (int) segment(3);
        $submit = post('submit');

        if (($submit == '') && ($update_id>0)) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        $data['category_options'] = $this->_get_category_options();

        if ($update_id>0) {
            $data['headline'] = 'Update Product Item Record';
            $data['cancel_url'] = BASE_URL.'product_items/show/'.$update_id;
        } else {
            $data['headline'] = 'Create New Product Item Record';
            $data['cancel_url'] = BASE_URL.'product_items/manage';
        }

        $data['form_location'] = BASE_URL.'product_items/submit/'.$update_id;


        $additional_includes_top[] = BASE_URL.'product_items_module/cleditor/jquery.cleditor.css';
        $additional_includes_top[] = BASE_URL.'product_items_module/js/jquery-1.8.2.min.js';
        $additional_includes_top[] = BASE_URL.'product_items_module/cleditor/jquery.cleditor.min.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $additional_includes_btm[] = BASE_URL.'product_items_module/js/app.js';
        $data['additional_includes_btm'] = $additional_includes_btm;


        $data['view_file'] = 'create';
        $this->template('bootstrappy', $data);
    }


    // this will show the product items on the users/public side 
    function index() {
        
        $data['product_items'] = $this->model->get('id desc');
        $total_rows = count($data['product_items']);

        $data['total_rows'] = $total_rows;

        if($total_rows>0) {

            $data['product_items'] = $this->model->get('id desc');
            $data['template'] = 'custom';

            $data['target_base_url'] = $this->get_target_pagination_base_url();
            $data['total_rows'] = $total_rows ;

            $data['offset'] = $this->_get_offset();
            $data['limit'] = $this->_get_limit();
            $data['include_showing_statement'] = false;
            $data['num_links_per_page'] = 6; //change to 6

        } else {
            $data['no_product_item'] = 'No Product Item Have Been Posted Yet'; 
        }

        $data['headline'] = 'Some Of Our Product Item';
        $data['view_module'] = 'product_items';
        $data['view_file'] = 'display_item_show';
        $this->template('public', $data);
    }

    function get_target_pagination_base_url(){
        $first_bit = segment(1);  
        $second_bit = segment(2);  
        $target_base_url = BASE_URL.$first_bit."/".$second_bit;
        $target_base_url = BASE_URL.$first_bit;

         return $target_base_url;
    }
    

    function manage() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if (segment(4) !== '') {
            $data['headline'] = 'Search Results';
            $searchphrase = trim($_GET['searchphrase']);
            $params['product_name'] = '%'.$searchphrase.'%';
            $params['product_availability'] = '%'.$searchphrase.'%';
            $params['product_code'] = '%'.$searchphrase.'%';
            $params['product_reviews'] = '%'.$searchphrase.'%';
            $sql = 'select * from product_items
            WHERE product_name LIKE :product_name
            OR product_availability LIKE :product_availability
            OR product_code LIKE :product_code
            OR product_reviews LIKE :product_reviews
            ORDER BY id desc';
            $all_rows = $this->model->query_bind($sql, $params, 'object');
        } else {
            $data['headline'] = 'Manage Product Items';
            $all_rows = $this->model->get('id desc');
        }

        $pagination_data['total_rows'] = count($all_rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->_get_limit();
        $pagination_data['pagination_root'] = 'product_items/manage';
        $pagination_data['record_name_plural'] = 'product items';
        $pagination_data['include_showing_statement'] = true;
        $data['pagination_data'] = $pagination_data;

        $data['rows'] = $this->_reduce_rows($all_rows);
        $data['selected_per_page'] = $this->_get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;
        $data['view_module'] = 'product_items';
        $data['view_file'] = 'manage';
        $this->template('bootstrappy', $data);
    }

    function show() {
        $this->module('trongate_security');
        $token = $this->trongate_security->_make_sure_allowed();
        $update_id = (int) segment(3);

        if ($update_id == 0) {
            redirect('product_items/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('product_items/manage');
        } else {
            //generate picture folders, if required
            $picture_settings = $this->_init_picture_settings();
            $this->_make_sure_got_destination_folders($update_id, $picture_settings);

            //attempt to get the current picture
            $column_name = $picture_settings['target_column_name'];

            if ($data[$column_name] !== '') {
                //we have a picture - display picture preview
                $data['draw_picture_uploader'] = false;
                $picture = $data['picture'];

                if ($picture_settings['upload_to_module'] == true) {
                    $module_assets_dir = BASE_URL.segment(1).MODULE_ASSETS_TRIGGER;
                    $data['picture_path'] = $module_assets_dir.'/'.$picture_settings['destination'].'/'.$update_id.'/'.$picture;
                } else {
                    $data['picture_path'] = BASE_URL.$picture_settings['destination'].'/'.$update_id.'/'.$picture;
                }

            } else {
                //no picture - draw upload form
                $data['draw_picture_uploader'] = true;
            }
            $data['update_id'] = $update_id;
            $data['headline'] = 'Product Item Information';
            $data['view_file'] = 'show';
            $this->template('bootstrappy', $data);
        }
    }
    
    function _reduce_rows($all_rows) {
        $rows = [];
        $start_index = $this->_get_offset();
        $limit = $this->_get_limit();
        $end_index = $start_index + $limit;

        $count = -1;
        foreach ($all_rows as $row) {
            $count++;
            if (($count>=$start_index) && ($count<$end_index)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    function submit() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $submit = post('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('product_name', 'Product Name', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('product_description', 'Product Description', 'required|min_length[2]');
            $this->validation_helper->set_rules('product_price', 'Product Price', 'required|max_length[11]|numeric|greater_than[0]|integer');
            // $this->validation_helper->set_rules('product_availability', 'Product Availability', 'required|min_length[2]|max_length[255]');
            // $this->validation_helper->set_rules('product_code', 'Product Code', 'required|min_length[2]|max_length[255]');
            // $this->validation_helper->set_rules('product_reviews', 'Product Reviews', 'required|min_length[2]|max_length[255]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = (int) segment(3);
                $data = $this->_get_data_from_post();

                $data['url_string'] = strtolower(url_title($data['product_name']));
                
                if ($update_id>0) {
                    //update an existing record
                    if(empty($data['product_code'])){
                        $data['product_code'] = random_int(10000, 99999);
                    }
                    $this->model->update($update_id, $data, 'product_items');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $data['product_code'] = random_int(10000, 99999);
                    $update_id = $this->model->insert($data, 'product_items');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('product_items/show/'.$update_id);

            } else {
                //form submission error
                $this->create();
            }

        }

    }

    function submit_delete() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $submit = post('submit');
        $params['update_id'] = (int) segment(3);

        if (($submit == 'Yes - Delete Now') && ($params['update_id']>0)) {
            //delete all of the comments associated with this record
            $sql = 'delete from trongate_comments where target_table = :module and update_id = :update_id';
            $params['module'] = 'product_items';
            $this->model->query_bind($sql, $params);

            //delete the record
            $this->model->delete($params['update_id'], 'product_items');

            //set the flashdata
            $flash_msg = 'The record was successfully deleted';
            set_flashdata($flash_msg);

            //redirect to the manage page
            redirect('product_items/manage');
        }
    }

    function _get_limit() {
        if (isset($_SESSION['selected_per_page'])) {
            $limit = $this->per_page_options[$_SESSION['selected_per_page']];
        } else {
            $limit = $this->default_limit;
        }

        return $limit;
    }

    function _get_offset() {
        $page_num = (int) segment(3);

        if ($page_num>1) {
            $offset = ($page_num-1)*$this->_get_limit();
        } else {
            $offset = 0;
        }

        return $offset;
    }

    function _get_selected_per_page() {
        $selected_per_page = (isset($_SESSION['selected_per_page'])) ? $_SESSION['selected_per_page'] : 1;
        return $selected_per_page;
    }

    function set_per_page($selected_index) {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if (!is_numeric($selected_index)) {
            $selected_index = $this->per_page_options[1];
        }

        $_SESSION['selected_per_page'] = $selected_index;
        redirect('product_items/manage');
    }

    function _get_data_from_db($update_id) {
        $record_obj = $this->model->get_where($update_id, 'product_items');

        if ($record_obj == false) {
            $this->template('error_404');
            die();
        } else {
            $data = (array) $record_obj;
            return $data;        
        }
    }

    function _get_data_from_post() {
        $data['product_name'] = post('product_name', true);
        $data['product_description'] = post('product_description', true);
        $data['product_price'] = post('product_price', true);
        $data['product_availability'] = post('product_availability', true);
        $data['product_code'] = post('product_code', true);
        $data['category'] = post('category', true);
        $data['product_reviews'] = post('product_reviews', true);        
        return $data;
    }

    function _init_picture_settings() { 
        $picture_settings['max_file_size'] = 2000;
        $picture_settings['max_width'] = 1200;
        $picture_settings['max_height'] = 1200;
        $picture_settings['resized_max_width'] = 450;
        $picture_settings['resized_max_height'] = 450;
        $picture_settings['destination'] = 'product_items_pics';
        $picture_settings['target_column_name'] = 'picture';
        $picture_settings['thumbnail_dir'] = 'product_items_pics_thumbnails';
        $picture_settings['thumbnail_max_width'] = 120;
        $picture_settings['thumbnail_max_height'] = 120;
        $picture_settings['upload_to_module'] = true;
        $picture_settings['make_rand_name'] = false;
        return $picture_settings;
    }

    function _make_sure_got_destination_folders($update_id, $picture_settings) {

        $destination = $picture_settings['destination'];

        if ($picture_settings['upload_to_module'] == true) {
            $target_dir = APPPATH.'modules/'.segment(1).'/assets/'.$destination.'/'.$update_id;
        } else {
            $target_dir = APPPATH.'public/'.$destination.'/'.$update_id;
        }

        if (!file_exists($target_dir)) {
            //generate the image folder
            mkdir($target_dir, 0777, true);
        }

        //attempt to create thumbnail directory
        if (strlen($picture_settings['thumbnail_dir'])>0) {
            $ditch = $destination.'/'.$update_id;
            $replace = $picture_settings['thumbnail_dir'].'/'.$update_id;
            $thumbnail_dir = str_replace($ditch, $replace, $target_dir);
            if (!file_exists($thumbnail_dir)) {
                //generate the image folder
                mkdir($thumbnail_dir, 0777, true);
            }
        }

    }

    function submit_upload_picture($update_id) {

        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if ($_FILES['picture']['name'] == '') {
            redirect($_SERVER['HTTP_REFERER']);
        }

        $picture_settings = $this->_init_picture_settings();
        extract($picture_settings);

        $validation_str = 'allowed_types[gif,jpg,jpeg,png]|max_size['.$max_file_size.']|max_width['.$max_width.']|max_height['.$max_height.']';
        $this->validation_helper->set_rules('picture', 'item picture', $validation_str);

        $result = $this->validation_helper->run();

        if ($result == true) {

            $config['destination'] = $destination.'/'.$update_id;
            $config['max_width'] = $resized_max_width;
            $config['max_height'] = $resized_max_height;

            if ($thumbnail_dir !== '') {
                $config['thumbnail_dir'] = $thumbnail_dir.'/'.$update_id;
                $config['thumbnail_max_width'] = $thumbnail_max_width;
                $config['thumbnail_max_height'] = $thumbnail_max_height;
            }

            //upload the picture
            $config['upload_to_module'] = (!isset($picture_settings['upload_to_module']) ? false : $picture_settings['upload_to_module']);
            $config['make_rand_name'] = $picture_settings['make_rand_name'] ?? false;

            $file_info = $this->upload_picture($config);

            //update the database with the name of the uploaded file
            $data[$target_column_name] = $file_info['file_name'];
            $this->model->update($update_id, $data);

            $flash_msg = 'The picture was successfully uploaded';
            set_flashdata($flash_msg);
            redirect($_SERVER['HTTP_REFERER']);

        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
        
    }

    function ditch_picture($update_id) {

        if (!is_numeric($update_id)) {
            redirect($_SERVER['HTTP_REFERER']);
        }

        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $result = $this->model->get_where($update_id);

        if ($result == false) {
            redirect($_SERVER['HTTP_REFERER']);
        }

        $picture_settings = $this->_init_picture_settings();
        $target_column_name = $picture_settings['target_column_name'];
        $picture_name = $result->$target_column_name;

        if ($picture_settings['upload_to_module'] == true) {
            $picture_path = APPPATH.'modules/'.segment(1).'/assets/'.$picture_settings['destination'].'/'.$update_id.'/'.$picture_name;
        } else {
            $picture_path = APPPATH.'public/'.$picture_settings['destination'].'/'.$update_id.'/'.$picture_name;
        }

        $picture_path = str_replace('\\', '/', $picture_path);

        if (file_exists($picture_path)) {
            unlink($picture_path);
        }

        if (isset($picture_settings['thumbnail_dir'])) {
            $ditch = $picture_settings['destination'].'/'.$update_id;
            $replace = $picture_settings['thumbnail_dir'].'/'.$update_id;
            $thumbnail_path = str_replace($ditch, $replace, $picture_path);

            if (file_exists($thumbnail_path)) {
                unlink($thumbnail_path);
            }
        }

        $data[$target_column_name] = '';
        $this->model->update($update_id, $data);
        
        $flash_msg = 'The picture was successfully deleted';
        set_flashdata($flash_msg);
        redirect($_SERVER['HTTP_REFERER']);
    }

    function _get_category_options(){
        $rows = $this->model->get('category_title', 'categories');
        $options[''] = 'Select Category...';

        foreach($rows as $row) {
            $options[$row->id] = $row->category_title;
        }

        return $options;
    }
}