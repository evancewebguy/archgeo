<?php
class Faqs extends Trongate {

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

        // $data['services_options'] = $this->_get_services_options($data['services_id']);

        if (is_numeric($update_id)) {
            $data['headline'] = 'Update FAQ Record';
            $data['cancel_url'] = BASE_URL.'faqs/show/'.$update_id;
        } else {
            $data['headline'] = 'Create New FAQ Record';
            $data['cancel_url'] = BASE_URL.'faqs/manage';
        }

        $data['form_location'] = BASE_URL.'faqs/submit/'.$update_id;
        $data['view_file'] = 'create';
        $this->template('bootstrappy', $data);
    }

    function manage() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if (segment(4) !== '') {
            $data['headline'] = 'Search Results';
            $searchphrase = trim($_GET['searchphrase']);
            $params['question'] = '%'.$searchphrase.'%';
            $sql = 'select * from faqs
            WHERE question LIKE :question
            ORDER BY id';
            $all_rows = $this->model->query_bind($sql, $params, 'object');
        } else {
            $data['headline'] = 'Manage Faqs';
            $all_rows = $this->model->get('id');
        }

        $pagination_data['total_rows'] = count($all_rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->_get_limit();
        $pagination_data['pagination_root'] = 'faqs/manage';
        $pagination_data['record_name_plural'] = 'faqs';
        $pagination_data['include_showing_statement'] = true;
        $data['pagination_data'] = $pagination_data;

        $data['rows'] = $this->_reduce_rows($all_rows);
        $data['selected_per_page'] = $this->_get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;
        $data['view_module'] = 'faqs';
        $data['view_file'] = 'manage';
        $this->template('bootstrappy', $data);
    }

    function show() {
        $this->module('trongate_security');
        $token = $this->trongate_security->_make_sure_allowed();
        $update_id = segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('faqs/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('faqs/manage');
        } else {
            $data['update_id'] = $update_id;
            $data['headline'] = 'FAQ Information';
            $data['view_file'] = 'show';
            $this->template('bootstrappy', $data);
        }
    }

    function index(){
        $services = $this->model->get('id desc', 'services');

        $sql = "SELECT
                    faqs.id,
                    faqs.url_string,
                    faqs.question,
                    faqs.answer,
                    associated_faqs_and_services.faqs_id,
                    associated_faqs_and_services.services_id,
                    services.id,
                    services.url_string,
                    services.service_name 
                FROM
                    services
                INNER JOIN
                    associated_faqs_and_services
                ON
                    services.id = associated_faqs_and_services.services_id
                INNER JOIN
                    faqs
                ON
                    associated_faqs_and_services.faqs_id = faqs.id";

        $faqs = $this->model->query($sql,'array');

        $data['services'] = $services;
        $data['faqs'] = $faqs;

        $data['headline'] = 'Frequently Asked Questions';
        $data['view_module'] = 'faqs';
        $data['view_file'] = 'display_faqs';
        $this->template('public', $data);
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

            $this->validation_helper->set_rules('question', 'Question', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('answer', 'Answer', 'required|min_length[2]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = segment(3);
                $data = $this->_get_data_from_post();
                // $data['services_id'] = (is_numeric($data['services_id']) ? $data['services_id'] : 0);
                $data['url_string'] = strtolower(url_title($data['answer']));

                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'faqs');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'faqs');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('faqs/show/'.$update_id);

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
            $params['module'] = 'faqs';
            $this->model->query_bind($sql, $params);

            //delete the record
            $this->model->delete($params['update_id'], 'faqs');

            //set the flashdata
            $flash_msg = 'The record was successfully deleted';
            set_flashdata($flash_msg);

            //redirect to the manage page
            redirect('faqs/manage');
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
        redirect('faqs/manage');
    }

    function _get_data_from_db($update_id) {
        $record_obj = $this->model->get_where($update_id, 'faqs');

        if ($record_obj == false) {
            $this->template('error_404');
            die();
        } else {
            $data = (array) $record_obj;
            return $data;        
        }
    }

    function _get_data_from_post() {
        $data['question'] = post('question', true);
        $data['answer'] = post('answer', true);        
        // $data['services_id'] = post('services_id');
        return $data;
    }

    // function _get_services_options($selected_key) {
    //     $this->module('module_relations');
    //     $options = $this->module_relations->_fetch_options($selected_key, 'faqs', 'services');
    //     return $options;
    // }
}