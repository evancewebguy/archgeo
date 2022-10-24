<?php
class Pictures extends Trongate {

    private $default_limit = 20;
    private $per_page_options = array(10, 20, 50, 100); 
    
    function _get_pictures_module($target_module_id, $target_module) {
        $params['target_module'] = $target_module;
        $params['target_module_id'] = $target_module_id;
        
        $sql = 'SELECT picture_name FROM pictures WHERE target_module = :target_module AND target_module_id = :target_module_id ORDER BY priority';
        $data = $this->model->query_bind($sql, $params, 'object');
        /* var_dump($data); die(); */
        return $data;

    }
    
    function _fetch_pictures($target_module,$target_module_id) {
        $params['target_module'] = $target_module;
        $params['target_module_id'] = $target_module_id;
        
        $sql = 'SELECT picture FROM pictures WHERE target_module = :target_module AND target_module_id = :target_module_id ORDER BY priority';
        
        $data = $this->model->query_bind($sql, $params, 'object');
        return $data;
    }

    function order_pictures() {
        $target_module = segment(3);
        $target_module_id = segment(4);
        $params['target_module'] = $target_module;
        $params['target_module_id'] = $target_module_id;

        $this->module('trongate_security');
        $data['token'] = $this->trongate_security->_make_sure_allowed();

        $sql = 'SELECT * FROM pictures WHERE target_module = :target_module AND target_module_id = :target_module_id ORDER BY priority';
        $rows = $this->model->query_bind($sql, $params, 'object');

        $data['rows'] = $rows;

        $data['num_rows'] =  $this->model->count_where('target_module_id', $target_module_id, "=",  'priority','pictures');
        $data['target_module'] = $target_module;
        $data['target_module_id'] = $target_module_id;

        $data['target_directory'] = BASE_URL.$target_module.'_pictures_thumb/'.$target_module_id.'/';

        $data['upload_url'] = BASE_URL.'my_filezone/upload/'.$target_module.'/'.$target_module_id;
        $data['delete_url'] = BASE_URL.'my_filezone/ditch';

        $additional_includes_top[] = BASE_URL.'pictures_module/js/sort/jquery.min.js';
        $additional_includes_top[] = BASE_URL.'pictures_module/js/sort/jquery-ui.min.js';
        $additional_includes_top[] = BASE_URL.'pictures_module/js/sort/jquery.ui.touch-punch.min.js';
        $data['additional_includes_top'] = $additional_includes_top;
        $data['upload_url'] = BASE_URL.'my_filezone/upload/'.$target_module.'/'.$target_module_id;
        $data['headline'] = 'Order Pictures';
        $data['cancel_url'] = BASE_URL.$target_module.'/show/'.$target_module_id;
        $data['btn_text'] = 'GO BACK';
        $data['view_module'] = 'pictures';
        $data['view_file'] = 'order_pictures';
        $this->template('bootstrappy', $data);
    }
    
}