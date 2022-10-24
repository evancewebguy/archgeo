<?php
class Spam_blocker_data extends Trongate {

    private $default_limit = 20;
    private $per_page_options = array(10, 20, 50, 100);   

    function __construct() {
        parent::__construct();
        $this->parent_module = 'spam_blocker';
        $this->child_module = 'spam_blocker_data';
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

        if (is_numeric($update_id)) {
            $data['headline'] = 'Update Spam Blocker Data Record';
            $data['cancel_url'] = BASE_URL.'spam_blocker-spam_blocker_data/show/'.$update_id;
        } else {
            $data['headline'] = 'Create New Spam Blocker Data Record';
            $data['cancel_url'] = BASE_URL.'spam_blocker-spam_blocker_data/manage';
        }

        $data['element_options'] = $this->_get_element_options($data);

        $data['form_location'] = BASE_URL.'spam_blocker-spam_blocker_data/submit/'.$update_id;
        $data['view_module'] = 'spam_blocker/spam_blocker_data';
        $data['view_file'] = 'create';
        $this->template('bootstrappy', $data);
    }

    function _get_element_options($data) {
        $element_options = [];

        if (!isset($data['element'])) {
            $data['element'] = '';
        } elseif($data['element'] == '') {
            $element_options[''] = 'Select element...';
        }

        $element_options[1] = 'sender name';
        $element_options[2] = 'sender email';
        $element_options[3] = 'message';
        return $element_options;
    }

    function manage() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if (segment(4) !== '') {
            $data['headline'] = 'Search Results';
            $searchphrase = trim($_GET['searchphrase']);
            $params['element'] = '%'.$searchphrase.'%';
            $params['target_string'] = '%'.$searchphrase.'%';
            $sql = 'select * from spam_blocker_data
            WHERE element LIKE :element
            OR target_string LIKE :target_string
            ORDER BY id';
            $all_rows = $this->model->query_bind($sql, $params, 'object');
        } else {
            $data['headline'] = 'Manage Spam Blocker Data';
            $all_rows = $this->model->get('id', 'spam_blocker_data');
        }

        $pagination_data['total_rows'] = count($all_rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->_get_limit();
        $pagination_data['pagination_root'] = 'spam_blocker-spam_blocker_data/manage';
        $pagination_data['record_name_plural'] = 'spam blocker data';
        $pagination_data['include_showing_statement'] = true;
        $data['pagination_data'] = $pagination_data;

        $data['rows'] = $this->_reduce_rows($all_rows);
        $data['selected_per_page'] = $this->_get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;

        $data['view_module'] = 'spam_blocker/spam_blocker_data';
        $data['view_file'] = 'manage';
        $this->template('bootstrappy', $data);
    }

    function show() {
        $this->module('trongate_security');
        $token = $this->trongate_security->_make_sure_allowed();
        $update_id = segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('spam_blocker_data/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('spam_blocker_data/manage');
        } else {
            $data['update_id'] = $update_id;
            $data['headline'] = 'Spam Blocker Data Information';
            $data['view_module'] = 'spam_blocker/spam_blocker_data';
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

            $this->validation_helper->set_rules('element', 'Element', 'required');
            $this->validation_helper->set_rules('target_string', 'Target String', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('score', 'Score', 'required|max_length[11]|numeric|greater_than[-10]|less_than[10]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = segment(3);
                $data = $this->_get_data_from_post();

                $data['element'] = $this->_get_element_as_word($data['element']);

                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'spam_blocker_data');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'spam_blocker_data');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('spam_blocker-spam_blocker_data/show/'.$update_id);

            } else {
                //form submission error
                $this->create();
            }

        }

    }

    function _get_element_as_word($element_id) {
        $element_options = $this->_get_element_options([]);
        if (isset($element_options[$element_id])) {
            $element_name = $element_options[$element_id];
        } else {
            $element_name = '';
        }
        return $element_name;
    }

    function submit_delete() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        $submit = post('submit');
        $params['update_id'] = segment(3);

        if (($submit == 'Yes - Delete Now') && (is_numeric($params['update_id']))) {
            //delete all of the comments associated with this record
            $sql = 'delete from trongate_comments where target_table = :module and update_id = :update_id';
            $params['module'] = 'spam_blocker_data';
            $this->model->query_bind($sql, $params);

            //delete the record
            $this->model->delete($params['update_id'], 'spam_blocker_data');

            //set the flashdata
            $flash_msg = 'The record was successfully deleted';
            set_flashdata($flash_msg);

            //redirect to the manage page
            redirect('spam_blocker-spam_blocker_data/manage');
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
        redirect('spam_blocker-spam_blocker_data/manage');
    }

    function _get_data_from_db($update_id) {
        $record_obj = $this->model->get_where($update_id, 'spam_blocker_data');

        if ($record_obj == false) {
            $this->template('error_404');
            die();
        } else {
            $data = (array) $record_obj;
            return $data;        
        }
    }

    function _get_data_from_post() {
        $data['element'] = post('element', true);
        $data['target_string'] = post('target_string', true);
        $data['score'] = post('score', true);        
        return $data;
    }

    function __destruct() {
        $this->parent_module = '';
        $this->child_module = '';
    }

}