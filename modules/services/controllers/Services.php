<?php
class Services extends Trongate {

    private $default_limit = 20;
    private $per_page_options = array(10, 20, 50, 100);    

    function _init_filezone_settings() {
        $data['targetModule'] = 'services';
        $data['destination'] = 'services_pictures';
        $data['destination_thumb'] = 'services_pictures_thumb';
        $data['max_file_size'] = 2000;
        $data['max_width'] = 1200;
        $data['max_height'] = 1200;
        $data['resized_max_width'] = 2560;
        $data['resized_max_height'] = 1200;
        $data['max_width_thumb'] = 1280;
        $data['max_height_thumb'] = 600;
        $data['upload_to_module'] = true;
        return $data;
    }

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

        $data['faqs_options'] = $this->_get_faqs_options($data['faqs_id']);

        if (is_numeric($update_id)) {
            $data['headline'] = 'Update Service Record';
            $data['cancel_url'] = BASE_URL.'services/show/'.$update_id;
        } else {
            $data['headline'] = 'Create New Service Record';
            $data['cancel_url'] = BASE_URL.'services/manage';
        }

        $additional_includes_top[] = BASE_URL.'services_module/cleditor/jquery.cleditor.css';
        $additional_includes_top[] = BASE_URL.'services_module/js/jquery-1.8.2.min.js';
        $additional_includes_top[] = BASE_URL.'services_module/cleditor/jquery.cleditor.min.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $additional_includes_btm[] = BASE_URL.'services_module/js/app.js';
        $data['additional_includes_btm'] = $additional_includes_btm;


        $data['form_location'] = BASE_URL.'services/submit/'.$update_id;
        $data['view_file'] = 'create';
        $this->template('bootstrappy', $data);
    }

    function manage() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if (segment(4) !== '') {
            $data['headline'] = 'Search Results';
            $searchphrase = trim($_GET['searchphrase']);
            $params['service_name'] = '%'.$searchphrase.'%';
            $sql = 'select * from services
            WHERE service_name LIKE :service_name
            ORDER BY id desc';
            $all_rows = $this->model->query_bind($sql, $params, 'object');
        } else {
            $data['headline'] = 'Manage Services';
            $all_rows = $this->model->get('id desc');
        }

        $pagination_data['total_rows'] = count($all_rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->_get_limit();
        $pagination_data['pagination_root'] = 'services/manage';
        $pagination_data['record_name_plural'] = 'services';
        $pagination_data['include_showing_statement'] = true;
        $data['pagination_data'] = $pagination_data;

        $data['rows'] = $this->_reduce_rows($all_rows);
        $data['selected_per_page'] = $this->_get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;
        $data['view_module'] = 'services';
        $data['view_file'] = 'manage';
        $this->template('bootstrappy', $data);
    }


    // this will show the services on the users/public side 
    function index() {
        $data['rows'] = $this->model->get('id desc');

        $this->module('processes');
        $data['processes'] = $this->processes->homepage_processes();
        
        $data['headline'] = 'SERVICES';
        $data['view_module'] = 'services';
        $data['view_file'] = 'display_services';
        $this->template('public', $data);
    }

    function _draw_services_list($modulename) {
        $data['module'] = $modulename;
        $data['rows'] = $this->model->get('id desc');
        $data['headline'] = 'SERVICES';
        $this->view('display_services_links', $data);
    }

    function service() {
        $url_string = segment(3);

        $data['services_obj'] = $this->model->get_one_where('url_string', $url_string, 'services');
        if($data['services_obj'] == false){
            redirect('services');
        }else{
            $update_id = $data['services_obj']->id;

            if($data['services_obj']->picture != '') {
                $data['picture_path'] = BASE_URL.'services_pics/'.$data['services_obj']->id.'/'.$data['services_obj']->picture;
            } else {
                $data['picture_path'] = BASE_URL.'services_module/img/home-img1.jpg';           
            }


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
            
 
            $data['html_pictures'] = $this->_get_blog_notices_pics_html($data);
            $data['headline'] = 'Service Information';
            $data['view_module'] = 'services';
            $data['view_file'] = 'display_single_service';
            $this->template('public', $data);

        }
    }


    function homepage_services(){
        $services = $this->model->get('id desc');
        return $services;
    }

    function show() {
        $this->module('trongate_security');
        $token = $this->trongate_security->_make_sure_allowed();
        $update_id = segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('services/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('services/manage');
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
            $data['headline'] = 'Service Information';
            $data['filezone_settings'] = $this->_init_filezone_settings();
            $data['view_file'] = 'show';
            $this->template('bootstrappy', $data);
        }
    }
    

    function _get_blog_notices_pics_html($data) {

        $this->module('pictures');
        $update_id = $data['services_obj']->id;

        $data['gallery_pics'] = $this->pictures->_fetch_pictures('services',$update_id);
        // var_dump($data['gallery_pics']); die();

        $gal = count($data['gallery_pics']);

        if ($gal>0) {
            $data['gallery_dir'] = BASE_URL.'services_pictures_thumb/'.$update_id.'/';
            $data['gallery_dir_full'] = BASE_URL.'services_pictures/'.$update_id.'/';
            $blog_notices_pics_html = $this->view('single_service_gallery', $data, true);
        } else {
            $blog_notices_pics_html = '';
        }

        return $blog_notices_pics_html;
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

            $this->validation_helper->set_rules('service_name', 'Service Name', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('service_description', 'Service Description', 'required|min_length[2]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = segment(3);
                $data = $this->_get_data_from_post();
                $data['faqs_id'] = (is_numeric($data['faqs_id']) ? $data['faqs_id'] : 0);
                $data['faqs_id'] = (is_numeric($data['faqs_id']) ? $data['faqs_id'] : 0);
                $data['url_string'] = strtolower(url_title($data['service_name']));

                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'services');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'services');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('services/show/'.$update_id);

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
            $params['module'] = 'services';
            $this->model->query_bind($sql, $params);

            //delete the record
            $this->model->delete($params['update_id'], 'services');

            //set the flashdata
            $flash_msg = 'The record was successfully deleted';
            set_flashdata($flash_msg);

            //redirect to the manage page
            redirect('services/manage');
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
        redirect('services/manage');
    }

    function _get_data_from_db($update_id) {
        $record_obj = $this->model->get_where($update_id, 'services');

        if ($record_obj == false) {
            $this->template('error_404');
            die();
        } else {
            $data = (array) $record_obj;
            return $data;        
        }
    }

    function _get_data_from_post() {
        $data['service_name'] = post('service_name', true);
        $data['service_description'] = post('service_description', true);        
        $data['faqs_id'] = post('faqs_id');
        return $data;
    }

    function _init_picture_settings() { 
        $picture_settings['max_file_size'] = 2000;
        $picture_settings['max_width'] = 1200;
        $picture_settings['max_height'] = 1200;
        $picture_settings['resized_max_width'] = 450;
        $picture_settings['resized_max_height'] = 450;
        $picture_settings['destination'] = 'services_pics';
        $picture_settings['target_column_name'] = 'picture';
        $picture_settings['thumbnail_dir'] = 'services_pics_thumbnails';
        $picture_settings['thumbnail_max_width'] = 120;
        $picture_settings['thumbnail_max_height'] = 120;
        $picture_settings['upload_to_module'] = true;
        return $picture_settings;
    }

    // function _make_sure_got_destination_folders($update_id, $picture_settings) {
    //     $destination = $picture_settings['destination'];
    //     if ($picture_settings['upload_to_module'] == true) {
    //         $target_dir = APPPATH.'modules/'.segment(1).'/assets/'.$destination.'/'.$update_id;
    //     } else {
    //         $target_dir = APPPATH.'public/'.$destination.'/'.$update_id;
    //     }

    //     if (!file_exists($target_dir)) {
    //         //generate the image folder
    //         mkdir($target_dir, 0777, true);
    //     }

    //     //attempt to create thumbnail directory
    //     if (strlen($picture_settings['thumbnail_dir'])>0) {
    //         $ditch = $destination.'/'.$update_id;
    //         $replace = $picture_settings['thumbnail_dir'].'/'.$update_id;
    //         $thumbnail_dir = str_replace($ditch, $replace, $target_dir);
    //         if (!file_exists($thumbnail_dir)) {
    //             //generate the image folder
    //             mkdir($thumbnail_dir, 0777, true);
    //         }
    //     }
    // }


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

    function _get_faqs_options($selected_key) {
        $this->module('module_relations');
        $options = $this->module_relations->_fetch_options($selected_key, 'services', 'faqs');
        return $options;
    }
}