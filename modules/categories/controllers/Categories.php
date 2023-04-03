<?php
class Categories extends Trongate {

    private $entity_name_singular = 'Category';
    private $entity_name_plural = 'Categories';
    private $max_allowed_levels = 3;

    function manage() {
        $this->module('trongate_security');
        $data['token'] = $this->trongate_security->_make_sure_allowed();
        $data['all_categories'] = $this->model->get('id', 'categories');
        $data['entity_name_singular'] = $this->entity_name_singular;
        $data['entity_name_plural'] = $this->entity_name_plural;
        $data['max_allowed_levels'] = $this->max_allowed_levels;
        $data['view_module'] = 'categories';
        $data['view_file'] = 'manage';
        $this->template('bootstrappy', $data);
    }

    function get_cat_name($id){
        $category = $this->model->get_one_where('id', $id);
        return $category;
    }

    function fix() {
        $all_categories = $this->model->get('id', 'categories');
        $this->_draw_category_nav($all_categories);
    }

    function _append_sub_categories($parent_category_id, $categories) {

        $sub_categories = [];
        foreach($categories as $category) {

            if ($category->parent_category_id == $parent_category_id) {
                $sub_categories[] = $category;
            }

        }

        return $sub_categories;
    }

    function _draw_category_nav($all_categories) {

        /*
            outputs HTML representation of the categories
            for example:

            <ul class="category-nav">
                <li><a href="http://localhost/app/categories/display/category-one">Category One</a></li>
                <ul>
                    <li><a href="http://localhost/app/categories/display/category-two">Category Two</a></li>
                </ul>
                <li><a href="http://localhost/app/categories/display/category-three">Category Three</a></li>
            </ul>
        */

        $num_categories = count($all_categories);
        $data['all_categories'] = $all_categories;
        $output = $this->view('category_nav', $data, true);
        $output = str_replace('<ul></ul>', '', $output);
        $num_categories = count($all_categories);

        if ($num_categories>0) {
            $output = '<ul class="category-nav">'.$output;
            $output = str_replace('<ul class="category-nav"><ul>', '<ul class="category-nav">', $output);
        }

        echo $output;
    }

    function _draw_dragzone_content($all_categories) {
        $data['all_categories'] = $all_categories;
        $this->view('dragzone_content', $data);
    }

    function remember_positions() {
        api_auth();
        $posted_data = file_get_contents('php://input');
        $child_nodes = json_decode($posted_data);

        $sql = '';
        foreach ($child_nodes as $child_node) {
            $id = $this->_prep_id($child_node->id);
            $parent_category_id = $this->_prep_id($child_node->parent_category_id);
            $priority = $child_node->priority;

            if ((!is_numeric($id)) || (!is_numeric($parent_category_id)) || (!is_numeric($priority))) {
                die();
            }

            $sql.= 'UPDATE categories set priority = '.$priority.', parent_category_id = '.$parent_category_id.' WHERE id = '.$id.';';

        }

        $this->model->query($sql);
        
    }   
    
    function _prep_id($id) {
        $id = str_replace('record-id-', '', $id);

        if ($id == 'dragzone') {
            $id = 0;
        }

        return $id;
    }

    //before hook (Create)
    function _get_url_string($input) {

        $category_title = trim($input['params']['category_title']);

        if ($category_title == '') {
            http_response_code(400);
            echo 'Invalid category title!';
            die();
        } else {
            $url_string = strtolower(url_title($category_title));
            $input['params']['url_string'] = $url_string;

            $update_id = segment(4);

            if (!is_numeric($update_id)) {
                $input['params']['parent_category_id'] = 0;
                $input['params']['priority'] = 0;
            }

            return $input;
        }

    }

    //before hook (Delete One)
    function _make_sure_delete_allowed($input) {

        //make sure no other categories have this as a parent
        $params['parent_category_id'] = $input['params']['id'];
        $sql = 'select * from categories where parent_category_id = :parent_category_id';
        $rows = $this->model->query_bind($sql, $params, 'array');

        if (count($rows)>0) {
            http_response_code(400);
            echo 'At least one category has this as a parent!';
            die();
        }

        return $input;

    } 

}