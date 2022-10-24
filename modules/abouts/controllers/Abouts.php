<?php
class Abouts extends Trongate {

    private $default_limit = 20;
    private $per_page_options = array(10, 20, 50, 100);    

    function create() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $update_id = segment(3);
        $submit = post('submit');

        if (($submit == '') && (is_numeric($update_id))) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        if (is_numeric($update_id)) {
            $data['headline'] = 'Update About Record';
            $data['cancel_url'] = BASE_URL.'abouts/show/'.$update_id;
        } else {
            $data['headline'] = 'Create New About Record';
            $data['cancel_url'] = BASE_URL.'abouts/manage';
        }

        $additional_includes_top[] = BASE_URL.'abouts_module/cleditor/jquery.cleditor.css';
        $additional_includes_top[] = BASE_URL.'abouts_module/js/jquery-1.8.2.min.js';
        $additional_includes_top[] = BASE_URL.'abouts_module/cleditor/jquery.cleditor.min.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $additional_includes_btm[] = BASE_URL.'abouts_module/js/app.js';
        $data['additional_includes_btm'] = $additional_includes_btm;

        $data['form_location'] = BASE_URL.'abouts/submit/'.$update_id;
        $data['view_file'] = 'create';
        $this->template('bootstrappy', $data);
    }

    function manage() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if (segment(4) !== '') {
            $data['headline'] = 'Search Results';
            $searchphrase = trim($_GET['searchphrase']);
            $sql = 'select * from abouts ORDER BY id';
            $all_rows = $this->model->query_bind($sql, $params, 'object');
        } else {
            $data['headline'] = 'Manage Abouts';
            $all_rows = $this->model->get('id');
        }

        $pagination_data['total_rows'] = count($all_rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->_get_limit();
        $pagination_data['pagination_root'] = 'abouts/manage';
        $pagination_data['record_name_plural'] = 'abouts';
        $pagination_data['include_showing_statement'] = true;
        $data['pagination_data'] = $pagination_data;

        $data['rows'] = $this->_reduce_rows($all_rows);
        $data['selected_per_page'] = $this->_get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;
        $data['view_module'] = 'abouts';
        $data['view_file'] = 'manage';
        $this->template('bootstrappy', $data);
    }

    function show() {
        $this->module('trongate_security');
        $token = $this->trongate_security->_make_sure_allowed();
        $update_id = segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('abouts/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('abouts/manage');
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
            $data['headline'] = 'About Information';
            $data['view_file'] = 'show';
            $this->template('bootstrappy', $data);
        }
    }


    // this will show the aboutus on the users/public side 
    function index() {
        // $data['rows'] = $this->model->get('id desc');

        $url_string = segment(1);
        $data['abouts_obj'] = $this->model->get('url_string', $url_string, 'abouts');
        // var_dump($data['abouts_obj']); die();

        if($data['abouts_obj'] == false){
            redirect('abouts');
        }else{
            // $update_id = $data['abouts_obj']->id;

            // var_dump($update_id); die();

            /**
             * GET PICTURES TO SHOW ON ABOUT US PAGE
             * 
             * picture path shouldin modules asset 
             */


            // if($data['teams_obj']->picture != '') {
            //     $data['picture_path'] = BASE_URL.'teams_pics/'.$data['teams_obj']->id.'/'.$data['teams_obj']->picture;
            // } else {
            //     $data['picture_path'] = BASE_URL.'teams_module/img/home-img.png';           
            // }
            // $data['headline'] = 'Staff Information';
            // $data['view_module'] = 'teams';
            // $data['view_file'] = 'display_single_staff';
            // $this->template('public', $data);

            $this->module('clientlogos');
            $data['clientlogos'] = $this->clientlogos->homepage_clientlogos();

            $data['headline'] = 'About Us';
            $data['view_module'] = 'abouts';
            $data['view_file'] = 'display_aboutus';
            $this->template('public', $data);

        }
    }

    function homepage_abouts(){
         $abouts = $this->model->get('id desc', 'abouts', 1,0);
         return $abouts;
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

            $this->validation_helper->set_rules('company_information', 'Company Information', 'required|min_length[2]');
            $this->validation_helper->set_rules('mission', 'Mission', 'required|min_length[2]');
            $this->validation_helper->set_rules('vision', 'Vision', 'required|min_length[2]');
            $this->validation_helper->set_rules('core_values', 'Core Values', 'required|min_length[2]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = segment(3);
                $data = $this->_get_data_from_post();
                $data['url_string'] = strtolower(url_title($data['company_information']));

                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'abouts');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'abouts');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('abouts/show/'.$update_id);

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
        $params['update_id'] = segment(3);

        if (($submit == 'Yes - Delete Now') && (is_numeric($params['update_id']))) {
            //delete all of the comments associated with this record
            $sql = 'delete from trongate_comments where target_table = :module and update_id = :update_id';
            $params['module'] = 'abouts';
            $this->model->query_bind($sql, $params);

            //delete the record
            $this->model->delete($params['update_id'], 'abouts');

            //set the flashdata
            $flash_msg = 'The record was successfully deleted';
            set_flashdata($flash_msg);

            //redirect to the manage page
            redirect('abouts/manage');
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
        $page_num = segment(3);

        if (!is_numeric($page_num)) {
            $page_num = 0;
        }

        if ($page_num>1) {
            $offset = ($page_num-1)*$this->_get_limit();
        } else {
            $offset = 0;
        }

        return $offset;
    }

    function _get_selected_per_page() {
        if (!isset($_SESSION['selected_per_page'])) {
            $selected_per_page = $this->per_page_options[1];
        } else {
            $selected_per_page = $_SESSION['selected_per_page'];
        }

        return $selected_per_page;
    }

    function set_per_page($selected_index) {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if (!is_numeric($selected_index)) {
            $selected_index = $this->per_page_options[1];
        }

        $_SESSION['selected_per_page'] = $selected_index;
        redirect('abouts/manage');
    }

    function _get_data_from_db($update_id) {
        $record_obj = $this->model->get_where($update_id, 'abouts');

        if ($record_obj == false) {
            $this->template('error_404');
            die();
        } else {
            $data = (array) $record_obj;
            return $data;        
        }
    }

    function _get_data_from_post() {
        $data['company_information'] = post('company_information', true);
        $data['mission'] = post('mission', true);
        $data['vision'] = post('vision', true);
        $data['core_values'] = post('core_values', true);        
        return $data;
    }

    function _init_picture_settings() { 
        $picture_settings['max_file_size'] = 2000;
        $picture_settings['max_width'] = 1200;
        $picture_settings['max_height'] = 1200;
        $picture_settings['resized_max_width'] = 450;
        $picture_settings['resized_max_height'] = 450;
        $picture_settings['destination'] = 'abouts_pics';
        $picture_settings['target_column_name'] = 'picture';
        $picture_settings['thumbnail_dir'] = 'abouts_pics_thumbnails';
        $picture_settings['thumbnail_max_width'] = 120;
        $picture_settings['thumbnail_max_height'] = 120;
        $picture_settings['upload_to_module'] = true;
        return $picture_settings;
    }

    function _make_sure_got_destination_folders($update_id, $picture_settings) {
        $destination = $picture_settings['destination'];
        $target_dir = APPPATH.'public/'.$destination.'/'.$update_id;

        if (!file_exists($target_dir)) {
            //generate the image folder
            mkdir($target_dir, 0777, true);
        }

        //attempt to create thumbnail directory
        $thumbnail_dir = trim($picture_settings['thumbnail_dir']);

        if (strlen($thumbnail_dir)>0) {
            $target_dir = APPPATH.'public/'.$thumbnail_dir.'/'.$update_id;
            if (!file_exists($target_dir)) {
                //generate the image folder
                mkdir($target_dir, 0777, true);
            }
        }    
}

    function submit_upload_picture($update_id) {

        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if ($_FILES['picture']['name'] == '') {
            redirect($_SERVER['HTTP_REFERER']);
        }

        $submit = post('submit');

        if ($submit == 'Upload') {
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
                $this->upload_picture($config);

                //update the database
                $data[$target_column_name] = $_FILES['picture']['name'];
                $this->model->update($update_id, $data);

                $flash_msg = 'The picture was successfully uploaded';
                set_flashdata($flash_msg);
                redirect($_SERVER['HTTP_REFERER']);

            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
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
}