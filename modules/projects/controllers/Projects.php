<?php
class Projects extends Trongate {

    private $default_limit = 20;
    private $per_page_options = array(10, 20, 50, 100);    

    function _init_filezone_settings() {
        $data['targetModule'] = 'projects';
        $data['destination'] = 'projects_pictures';
        $data['destination_thumb'] = 'projects_pictures_thumb';
        $data['max_file_size'] = 5000;
        $data['max_width'] = 5200;
        $data['max_height'] = 3200;
        $data['resized_max_width'] = 1000;
        $data['resized_max_height'] = 1000;
        $data['max_width_thumb'] = 420;
        $data['upload_to_module'] = true;
        return $data;
    }


    // function _init_picture_settings() { 
    //     $picture_settings['max_file_size'] = 5000;
    //     $picture_settings['max_width'] = 4000;
    //     $picture_settings['max_height'] = 4000;
    //     $picture_settings['resized_max_width'] = 1000;
    //     $picture_settings['resized_max_height'] = 1000;
    //     $picture_settings['destination'] = 'blog_notices_pics';
    //     $picture_settings['target_column_name'] = 'picture';
    //     $picture_settings['thumbnail_dir'] = 'blog_notices_pics_thumbnails';
    //     $picture_settings['thumbnail_max_width'] = 120;
    //     $picture_settings['thumbnail_max_height'] = 120;
    //     return $picture_settings;
    // }


    function create() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $update_id = (int)segment(3);
        $submit = post('submit');

        if (($submit == '') && (is_numeric($update_id))) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        if (is_numeric($update_id)) {
            $data['headline'] = 'Update Project Record';
            $data['cancel_url'] = BASE_URL.'projects/show/'.$update_id;
        } else {
            $data['headline'] = 'Create New Project Record';
            $data['cancel_url'] = BASE_URL.'projects/manage';
        }

        $additional_includes_top[] = BASE_URL.'projects_module/cleditor/jquery.cleditor.css';
        $additional_includes_top[] = BASE_URL.'projects_module/js/jquery-1.8.2.min.js';
        $additional_includes_top[] = BASE_URL.'projects_module/cleditor/jquery.cleditor.min.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $additional_includes_btm[] = BASE_URL.'projects_module/js/app.js';
        $data['additional_includes_btm'] = $additional_includes_btm;

        $data['form_location'] = BASE_URL.'projects/submit/'.$update_id;
        $data['view_file'] = 'create';
        $this->template('bootstrappy', $data);
    }

    function manage() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if (segment(4) !== '') {
            $data['headline'] = 'Search Results';
            $searchphrase = trim($_GET['searchphrase']);
            $params['client_name'] = '%'.$searchphrase.'%';
            $params['nature_of_the_project'] = '%'.$searchphrase.'%';
            $params['status'] = '%'.$searchphrase.'%';
            $sql = 'select * from projects
            WHERE client_name LIKE :client_name
            OR nature_of_the_project LIKE :nature_of_the_project
            OR status LIKE :status
            ORDER BY id desc';
            $all_rows = $this->model->query_bind($sql, $params, 'object');
        } else {
            $data['headline'] = 'Manage Projects';
            $all_rows = $this->model->get('id desc');
        }

        $pagination_data['total_rows'] = count($all_rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->_get_limit();
        $pagination_data['pagination_root'] = 'projects/manage';
        $pagination_data['record_name_plural'] = 'projects';
        $pagination_data['include_showing_statement'] = true;
        $data['pagination_data'] = $pagination_data;

        $data['rows'] = $this->_reduce_rows($all_rows);
        $data['selected_per_page'] = $this->_get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;
        $data['view_module'] = 'projects';
        $data['view_file'] = 'manage';
        $this->template('bootstrappy', $data);
    }

    function show() {
        $this->module('trongate_security');
        $token = $this->trongate_security->_make_sure_allowed();
        $update_id = (int) segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('projects/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('projects/manage');
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
            $data['headline'] = 'Project Information';
            $data['filezone_settings'] = $this->_init_filezone_settings();
            $data['view_file'] = 'show';
            $this->template('bootstrappy', $data);
        }
    }
    
    // this will show the projects on the users/public side 
    function index() {
        $data['projects'] = $this->model->get('id desc');

        $total_rows = count($data['projects']);

        $data['total_rows'] = $total_rows;

        if($total_rows>0) {

            $data['projects'] = $this->model->get('id desc');
            $data['template'] = 'custom';

            $data['target_base_url'] = $this->get_target_pagination_base_url();
            $data['total_rows'] = $total_rows ;

            $data['offset'] = $this->_get_offset();
            $data['limit'] = $this->_get_limit();
            $data['include_showing_statement'] = false;
            $data['num_links_per_page'] = 2; //change to 6

        } else {
            $data['no_projects'] = 'No Projects Have Been Posted Yet'; 
        }

        $data['headline'] = 'Some Of Our Projects';
        $data['view_module'] = 'projects';
        $data['view_file'] = 'display_projects';
        $this->template('public', $data);
    }


    function get_target_pagination_base_url(){

        $first_bit = segment(1);  
        $second_bit = segment(2);  
        $target_base_url = BASE_URL.$first_bit."/".$second_bit;
        $target_base_url = BASE_URL.$first_bit;

         return $target_base_url;
    }

    function homepage_projects(){
        $projects = $this->model->get('id desc','projects', 4, 0);
        return $projects;
    }


    function view_project() {

        $url_string = segment(3);

        $data['projects_obj'] = $this->model->get_one_where('url_string', $url_string, 'projects');

        if($data['projects_obj'] == false){
            redirect('projects');
        }else{
            $update_id = $data['projects_obj']->id;

            if($data['projects_obj']->picture != '') {
                $data['picture_path'] = BASE_URL.'projects_pics/'.$data['projects_obj']->id.'/'.$data['projects_obj']->picture;
            } else {
                $data['picture_path'] = BASE_URL.'projects_module/img/home-img1.jpg';           
            }

            // $prev_next_projects = $this->_get_prev_next($data['projects_obj']->id);

            // $data['prev_link'] = $prev_next_projects['prev'];
            // $data['next_link'] = $prev_next_projects['next'];

            //additional footers
            $additional_public_includes_btm[] = 'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js';
            $additional_public_includes_btm[] = 'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js';
            $additional_public_includes_btm[] = 'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js.map';
            $additional_public_includes_btm[] = 'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js.map';
            $data['additional_public_includes_btm'] = $additional_public_includes_btm;

            //add additional headers
            $additional_public_includes_top[] = 'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css';
            $additional_public_includes_top[] = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js';
            $data['additional_public_includes_top'] = $additional_public_includes_top;

            $data['page_url'] = 'projects/view_project/'.$url_string;
            $data['current_url'] = current_url();
 
            $data['html_pictures'] = $this->_get_projects_pics_html($data);
            $data['headline'] = 'Project Information';
            $data['view_module'] = 'projects';
            $data['view_file'] = 'display_single_project';
            $this->template('public', $data);

        }
    }


    function _get_projects_pics_html($data) {
        $this->module('pictures');
        $update_id = $data['projects_obj']->id;

        $data['gallery_pics'] = $this->pictures->_fetch_pictures('projects',$update_id);
       
        $gal = count($data['gallery_pics']);
        if ($gal>0) {
            $data['gallery_dir'] = BASE_URL.'projects_pictures_thumb/'.$update_id.'/';
            $data['gallery_dir_full'] = BASE_URL.'projects_pictures/'.$update_id.'/';
            $projects_pics_html = $this->view('single_projects_gallery', $data, true);
        } else {
            $projects_pics_html = '';
        }
        return $projects_pics_html;
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

            $this->validation_helper->set_rules('client_name', 'Client Name', 'min_length[2]|max_length[255]');
            // $this->validation_helper->set_rules('nature_of_the_project', 'Nature of the project', 'min_length[2]|max_length[255]|required');
            $this->validation_helper->set_rules('year', 'Year', 'required|valid_datepicker_us');
            $this->validation_helper->set_rules('status', 'Status', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('project_description', 'Project Description', 'required|min_length[2]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = segment(3);
                $data = $this->_get_data_from_post();
                $data['url_string'] = strtolower(url_title($data['client_name']));
                $data['year'] = date('Y-m-d', strtotime($data['year']));

                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'projects');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'projects');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('projects/show/'.$update_id);

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
            $params['module'] = 'projects';
            $this->model->query_bind($sql, $params);

            //delete the record
            $this->model->delete($params['update_id'], 'projects');

            //set the flashdata
            $flash_msg = 'The record was successfully deleted';
            set_flashdata($flash_msg);

            //redirect to the manage page
            redirect('projects/manage');
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
        redirect('projects/manage');
    }

    function _get_data_from_db($update_id) {
        $record_obj = $this->model->get_where($update_id, 'projects');

        if ($record_obj == false) {
            $this->template('error_404');
            die();
        } else {
            $data = (array) $record_obj;
            return $data;        
        }
    }

    function _get_data_from_post() {
        $data['client_name'] = post('client_name', true);
        $data['nature_of_the_project'] = post('nature_of_the_project', true);
        $data['year'] = post('year', true);
        $data['status'] = post('status', true);
        $data['project_description'] = post('project_description', true);    
        return $data;
    }

    function _init_picture_settings() { 
        $picture_settings['max_file_size'] = 3000;
        $picture_settings['max_width'] = 1200;
        $picture_settings['max_height'] = 1200;
        $picture_settings['resized_max_width'] = 450;
        $picture_settings['resized_max_height'] = 450;
        $picture_settings['destination'] = 'projects_pics';
        $picture_settings['target_column_name'] = 'picture';
        $picture_settings['thumbnail_dir'] = 'projects_pics_thumbnails';
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