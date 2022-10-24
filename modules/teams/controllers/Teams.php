<?php
class Teams extends Trongate {

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
            $data['headline'] = 'Update Team Record';
            $data['cancel_url'] = BASE_URL.'teams/show/'.$update_id;
        } else {
            $data['headline'] = 'Create New Team Record';
            $data['cancel_url'] = BASE_URL.'teams/manage';
        }

        $data['form_location'] = BASE_URL.'teams/submit/'.$update_id;
        $data['view_file'] = 'create';
        $this->template('bootstrappy', $data);
    }

    function manage() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if (segment(4) !== '') {
            $data['headline'] = 'Search Results';
            $searchphrase = trim($_GET['searchphrase']);
            $params['full_name'] = '%'.$searchphrase.'%';
            $params['job_title'] = '%'.$searchphrase.'%';
            $params['email_address'] = '%'.$searchphrase.'%';
            $params['facebook'] = '%'.$searchphrase.'%';
            $params['twitter'] = '%'.$searchphrase.'%';
            $params['instagram'] = '%'.$searchphrase.'%';
            $sql = 'select * from teams
            WHERE full_name LIKE :full_name
            OR job_title LIKE :job_title
            OR email_address LIKE :email_address
            OR facebook LIKE :facebook
            OR twitter LIKE :twitter
            OR instagram LIKE :instagram
            ORDER BY id';
            $all_rows = $this->model->query_bind($sql, $params, 'object');
        } else {
            $data['headline'] = 'Manage Teams';
            $all_rows = $this->model->get('id');
        }

        $pagination_data['total_rows'] = count($all_rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->_get_limit();
        $pagination_data['pagination_root'] = 'teams/manage';
        $pagination_data['record_name_plural'] = 'teams';
        $pagination_data['include_showing_statement'] = true;
        $data['pagination_data'] = $pagination_data;

        $data['rows'] = $this->_reduce_rows($all_rows);
        $data['selected_per_page'] = $this->_get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;
        $data['view_module'] = 'teams';
        $data['view_file'] = 'manage';
        $this->template('bootstrappy', $data);
    }

    function show() {
        $this->module('trongate_security');
        $token = $this->trongate_security->_make_sure_allowed();
        $update_id = segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('teams/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('teams/manage');
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
            $data['headline'] = 'Team Information';
            $data['view_file'] = 'show';
            $this->template('bootstrappy', $data);
        }
    }
    

    // this will show the teams on the users/public side 
    function index() {
        $data['rows'] = $this->model->get('id desc');
        $data['headline'] = 'Our Team';
        $data['view_module'] = 'teams';
        $data['view_file'] = 'display_teams';
        $this->template('public', $data);
    }

    function view_staff(){
        $url_string = segment(3);
        $data['teams_obj'] = $this->model->get_one_where('url_string', $url_string, 'teams');
        if($data['teams_obj'] == false){
            redirect('teams');
        }else{
            $update_id = $data['teams_obj']->id;
            if($data['teams_obj']->picture != '') {
                $data['picture_path'] = BASE_URL.'teams_pics/'.$data['teams_obj']->id.'/'.$data['teams_obj']->picture;
            } else {
                $data['picture_path'] = BASE_URL.'teams_module/img/home-img.png';           
            }
            $data['headline'] = 'Staff Information';
            $data['view_module'] = 'teams';
            $data['view_file'] = 'display_single_staff';
            $this->template('public', $data);
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

            $this->validation_helper->set_rules('full_name', 'Full Name', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('job_title', 'Job Title', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('biography', 'Biography', 'required|min_length[2]');
            $this->validation_helper->set_rules('email_address', 'Email Address', 'required|min_length[7]|max_length[255]|valid_email_address|valid_email');
            $this->validation_helper->set_rules('facebook', 'Facebook', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('twitter', 'Twitter', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('instagram', 'Instagram', 'required|min_length[2]|max_length[255]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = segment(3);
                $data = $this->_get_data_from_post();
                $data['url_string'] = strtolower(url_title($data['full_name']));

                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'teams');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'teams');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('teams/show/'.$update_id);

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
            $params['module'] = 'teams';
            $this->model->query_bind($sql, $params);

            //delete the record
            $this->model->delete($params['update_id'], 'teams');

            //set the flashdata
            $flash_msg = 'The record was successfully deleted';
            set_flashdata($flash_msg);

            //redirect to the manage page
            redirect('teams/manage');
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
        redirect('teams/manage');
    }

    function _get_data_from_db($update_id) {
        $record_obj = $this->model->get_where($update_id, 'teams');

        if ($record_obj == false) {
            $this->template('error_404');
            die();
        } else {
            $data = (array) $record_obj;
            return $data;        
        }
    }

    function _get_data_from_post() {
        $data['full_name'] = post('full_name', true);
        $data['job_title'] = post('job_title', true);
        $data['biography'] = post('biography', true);
        $data['email_address'] = post('email_address', true);
        $data['facebook'] = post('facebook', true);
        $data['twitter'] = post('twitter', true);
        $data['instagram'] = post('instagram', true);        
        return $data;
    }

    function _init_picture_settings() { 
        $picture_settings['max_file_size'] = 2000;
        $picture_settings['max_width'] = 1200;
        $picture_settings['max_height'] = 1200;
        $picture_settings['resized_max_width'] = 558;
        $picture_settings['resized_max_height'] = 575;
        $picture_settings['destination'] = 'teams_pics';
        $picture_settings['target_column_name'] = 'picture';
        $picture_settings['thumbnail_dir'] = 'teams_pics_thumbnails';
        $picture_settings['thumbnail_max_width'] = 300;
        $picture_settings['thumbnail_max_height'] = 300;
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