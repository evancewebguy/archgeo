<?php
class Blog_notices extends Trongate {

    private $default_limit = 20;
    private $per_page_options = array(10, 20, 50, 100);

    function category() {

        $category_url = segment(3);
        $data['category_obj'] = $this->model->get_one_where('url_string', $category_url, 'blog_categories');
    
        if ($data['category_obj'] == false) {
            redirect('blog_notices');
        } else {

        $categories = $this->model->get('id', 'blog_categories');

        $data['categories'] = $categories;

        $category_id = $data['category_obj']->id;

        $total_rows = $this->_get_blog_notices_from_category($category_id, false);
        $data['category_id'] = $data['category_obj']->id;
        $data['category_name'] = $data['category_obj']->category_name;
        $data['total_rows'] = $total_rows;

        if($total_rows>0) {
            $rows = $this->_get_blog_notices_from_category($category_id, true);
            $data['news'] =  $rows;

            $data['template'] = 'default';
            $data['pagination_root'] = 'blog_notices/category/'.$category_url;
            $data['total_rows'] = $total_rows ;
            $data['offset'] = $this->get_offset_blog_category();
            $data['limit'] = $this->get_limit();
            $data['include_showing_statement'] = false;
            $data['page_num_segment'] = 4;
            $data['num_links_per_page'] = 3;
        } else {
            $data['no_news'] = 'No Notices yet'; 
        }

        $data['blog_notices'] =  $rows;

        $data['headline'] = ucfirst($category_url);
        $data['headline'].= ' Publications';

        $data['class'] = 'news';
        $data['view_module'] = 'blog_notices';
        $this->view('category', $data);
    }  
   }

    
    function all() {
        $categories = $this->model->get('id', 'blog_categories');

        $total_rows = $this->_get_blog_notices(false);

        $data['categories'] = $categories;
        $data['total_rows'] = $total_rows;

        if($total_rows>0) {
            $rows = $this->_get_blog_notices(true);
            $data['blog_notices'] =  $rows;
            $data['template'] = 'default';
            
            $data['target_base_url'] = $this->get_target_pagination_base_url();
            $data['total_rows'] = $total_rows ;
            $data['offset'] = $this->get_offset();
            $data['limit'] = $this->get_limit();
            $data['include_showing_statement'] = false;
            $data['num_links_per_page'] = 3;
        } else {
            $data['no_blog_notices'] = 'No Blog Notices yet'; 
        }
        $this->view('display', $data);
    }

    function _get_blog_notices($limit_results = NULL) {
        if($limit_results == false) {
            $sql = 'SELECT
            blog_notices.id 
            FROM
            blog_notices
            WHERE 
            blog_notices.published = 1';
            $rows = $this->model->query($sql, 'object');
            if (empty($rows)) {
             $results = 0;   
            } else {
             $results = count($rows);
            }          

        } else {
            $pagination_data['offset'] = $this->get_offset();
            $pagination_data['limit'] = $this->get_limit();

            $sql = 'SELECT
            blog_notices.id, 
            blog_notices.blog_title,
            blog_notices.notice,
            blog_notices.blog_sub_title,
            blog_notices.published_date,
            blog_notices.url_string,
            blog_notices.picture,
            blog_notices.notice_sources_id
            FROM
            blog_notices
            WHERE 
            blog_notices.published = 1
            ORDER BY 
            blog_notices.published_date desc
            LIMIT [offset], [limit] 
            ';
            $sql = str_replace('[offset]',$pagination_data['offset'], $sql);
            $sql = str_replace('[limit]',$pagination_data['limit'], $sql);

            $rows = $this->model->query($sql, 'object');
            foreach ($rows as $blog_notices) {
                $row_data['blog_notices_id'] = $blog_notices->id;
                $row_data['blog_title'] = $blog_notices->blog_title;
                $row_data['notice'] = $blog_notices->notice;
                $row_data['blog_sub_title'] = $blog_notices->blog_sub_title;
                $row_data['published_date'] = $blog_notices->published_date;
                $row_data['url_string'] = $blog_notices->url_string;
                $row_data['picture'] = $blog_notices->picture;
                $row_data['notice_sources_id'] = $blog_notices->notice_sources_id;
                $row_data['notice_source_name'] = $this->_get_notice_source_name($blog_notices->notice_sources_id);
                $row_data['categories'] = $this->_get_categories($blog_notices->id);
    
                $data[] = (object) $row_data;
            }
            $results =  $data;    
        }
        return $results;
    }

    function _get_blog_notices_from_category($blog_category_id, $limit_results = NULL) {
        $params['blog_category_id'] = $blog_category_id;

        if($limit_results == false) {
            $sql = 'SELECT
            blog_notices.id as blog_notices_id
            FROM
            blog_categories
            JOIN associated_blog_notices_and_blog_categories
            ON associated_blog_notices_and_blog_categories.blog_categories_id = :blog_category_id
            JOIN
            blog_notices
            ON associated_blog_notices_and_blog_categories.blog_notices_id = blog_notices.id
            AND blog_notices.published = 1
            WHERE 
            blog_categories.id = :blog_category_id 
            ';
            $rows = $this->model->query_bind($sql, $params, 'object');
            if (empty($rows)) {
             $results = 0;   
            } else {
             $results = count($rows);
            }          

        } else {
            $pagination_data['offset'] = $this->get_offset_blog_category();
            $pagination_data['limit'] = $this->get_limit();

            $sql = 'SELECT
            blog_notices.id, 
            blog_notices.blog_title,
            blog_notices.blog_sub_title,
            blog_notices.published_date,
            blog_notices.url_string,
            blog_notices.picture,
            blog_notices.notice_sources_id
            FROM
            blog_categories
            JOIN associated_blog_notices_and_blog_categories
            ON associated_blog_notices_and_blog_categories.blog_categories_id = :blog_category_id
            JOIN
            blog_notices
            ON associated_blog_notices_and_blog_categories.blog_notices_id = blog_notices.id
            AND blog_notices.published = 1
            WHERE 
            blog_categories.id = :blog_category_id
            ORDER BY 
            blog_notices.published_date desc
            LIMIT [offset], [limit] 
            ';
            $sql = str_replace('[offset]',$pagination_data['offset'], $sql);
            $sql = str_replace('[limit]',$pagination_data['limit'], $sql);

            $rows = $this->model->query_bind($sql, $params, 'object');
            foreach ($rows as $blog_notices) {
                $row_data['blog_notices_id'] = $blog_notices->id;
                $row_data['blog_title'] = $blog_notices->blog_title;
                $row_data['blog_sub_title'] = $blog_notices->blog_sub_title;
                $row_data['published_date'] = $blog_notices->published_date;
                $row_data['url_string'] = $blog_notices->url_string;
                $row_data['picture'] = $blog_notices->picture;
                $row_data['notice_sources_id'] = $blog_notices->notice_sources_id;
                $row_data['notice_source_name'] = $this->_get_notice_source_name($blog_notices->notice_sources_id);
                $row_data['categories'] = $this->_get_categories($blog_notices->id);
    
    
                $data[] = (object) $row_data;
            }
            $results =  $data;    
        }
        return $results;
    }

    function get_target_pagination_base_url(){

        $first_bit = segment(1);  
        $second_bit = segment(2);  
        $target_base_url = BASE_URL.$first_bit."/".$second_bit;
        $target_base_url = BASE_URL.$first_bit;

         return $target_base_url;
    }

    function get_limit() {
    $limit =4;
    return $limit;
    }

    function get_offset_blog_category() {

            $page_num = segment(4);
    
            if(!is_numeric($page_num)) {
                $page_num = 0;
            } 
            if($page_num>1) {
              
                $offset = ($page_num-1)*$this->get_limit();
            } else {
                $offset = 0;
            }
            return $offset;
        }

    function get_offset() {
    
        $page_num = segment(3);

        if(!is_numeric($page_num)) {
            $page_num = 0;
        } 
        if($page_num>1) {
          
            $offset = ($page_num-1)*$this->get_limit();
        } else {
            $offset = 0;
        }
        return $offset;
    }

    function _get_current_page_num($segment_num) {
        $current_page = segment($segment_num);
        if(!is_numeric($current_page)) {
        $current_page = 0;
        }
        return $current_page;
    }

    function _get_nice_date($fecha_timestamp) {
        $fecha = date('m/d/Y', $fecha_timestamp);
        return $fecha;
    }

    function _get_categories($value) {
        $categories_result = $this->model->get_many_where('blog_notices_id', $value, 'associated_blog_notices_and_blog_categories');
        if ($categories_result == false){
            $categories_print = [];
        } else {
            $this->module('blog_categories');
            $categories_print = [];
            foreach ($categories_result as $key => $value) {
              $categories_print[$key] = $this->_get_category($categories_result[$key]->blog_categories_id);             
            }
        }  
        
        return $categories_print;
    }

    function _get_category($categories_id) {
        $category_c = $this->model->get_one_where('id', $categories_id, 'blog_categories');
        if ($category_c == true){
            $category = $category_c->category_name;
        } else {
            $category = "";
        }        
        return $category;
    }

    function _get_notice_source_name($notice_source_id) {
        $notice_obj = $this->model->get_one_where('id', $notice_source_id, 'notice_sources');
        if ($notice_obj == true){
            $source_name = $notice_obj->source_name;
        } else {
            $source_name = "";
        }        
        return $source_name;

    }

    function _get_notice_source($notice_source_id) {
        $notice_obj = $this->model->get_one_where('id', $notice_source_id, 'notice_sources');
        if ($notice_obj == true){
            $source = $notice_obj;
        } else {
            $source = "";
        }        
        return $source;

    }


    function display() {
        $url_string = segment(3);
        $data['blog_notices_obj'] = $this->model->get_one_where('url_string', $url_string, 'blog_notices');
        if ($data['blog_notices_obj'] == false) {
            $this->not_found();
        } else {
            if($data['blog_notices_obj']->youtube != '') {
                $data['html_video'] = $this->_get_blog_notices_video_html($data);
            } else {
                $data['html_video'] = '';
            }
            if($data['blog_notices_obj']->picture != '') {
                $data['picture_path'] = BASE_URL.'blog_notices_pics/'.$data['blog_notices_obj']->id.'/'.$data['blog_notices_obj']->picture;
            } else {
                $data['picture_path'] = BASE_URL.'blog_notices_module/img/home-img1.jpg';           
            }

            $data['blog_notice_categories'] = $this->_get_categories($data['blog_notices_obj']->id);
            $data['source'] = $this->_get_notice_source($data['blog_notices_obj']->notice_sources_id);

            $prev_next_projects = $this->_get_prev_next($data['blog_notices_obj']->id);

            $data['prev_link'] = $prev_next_projects['prev'];
            $data['next_link'] = $prev_next_projects['next'];
            $data['page_url'] = 'blog_notices/display/'.$url_string;
            $data['current_url'] = current_url();
            $data['html_pictures'] = $this->_get_blog_notices_pics_html($data);

            $data['class'] = 'class="news-example"';
            $this->view('notice', $data);
        }

    }


    function blog() {
        $url_string = segment(3);

        $data['blog_notices_obj'] = $this->model->get_one_where('url_string', $url_string, 'blog_notices');


        // var_dump($data['blog_notices_obj']->id); die();

        if($data['blog_notices_obj'] == false){
            redirect('blog_notices');
        }else{
            $update_id = $data['blog_notices_obj']->id;


            if($data['blog_notices_obj']->youtube != '') {
                $data['html_video'] = $this->_get_blog_notices_video_html($data);
            } else {
                $data['html_video'] = '';
            }
            if($data['blog_notices_obj']->picture != '') {
                $data['picture_path'] = BASE_URL.'blog_notices_pics/'.$data['blog_notices_obj']->id.'/'.$data['blog_notices_obj']->picture;
            } else {
                $data['picture_path'] = BASE_URL.'blog_notices_module/img/home-img1.jpg';           
            }

            $data['blog_notice_categories'] = $this->_get_categories($data['blog_notices_obj']->id);
            $data['source'] = $this->_get_notice_source($data['blog_notices_obj']->notice_sources_id);

            $prev_next_projects = $this->_get_prev_next($data['blog_notices_obj']->id);

            $data['prev_link'] = $prev_next_projects['prev'];
            $data['next_link'] = $prev_next_projects['next'];
            $data['page_url'] = 'blog_notices/blog/'.$url_string;
            $data['current_url'] = current_url();
            $data['html_pictures'] = $this->_get_blog_notices_pics_html($data);

            $data['class'] = 'class="news-example"';
 
            $data['headline'] = 'Blog Detail';
            $data['view_module'] = 'blog_notices';
            $data['view_file'] = 'display_single_blog';
            $this->template('public', $data);

        }
    }

    // this will show the blog on the users/public side 
    //this will show all the blog posts in users side 
    function all_blogs() {   
        $categories = $this->model->get('id', 'blog_categories');
        $total_rows = $this->_get_blog_notices(false);

        $data['categories'] = $categories;
        $data['total_rows'] = $total_rows;

        if($total_rows>0) {
            $rows = $this->_get_blog_notices(true);

            $data['blog_notices'] =  $rows;
            $data['template'] = 'custom';
            
            $data['target_base_url'] = $this->get_target_pagination_base_url();
            $data['total_rows'] = $total_rows ;
            $data['offset'] = $this->get_offset();
            $data['limit'] = $this->get_limit();
            $data['include_showing_statement'] = false;
            $data['num_links_per_page'] = 4;



            ///sample 
        
            // $pagination_data['total_rows'] = count($all_rows);
            // $pagination_data['page_num_segment'] = 3;
            // $pagination_data['limit'] = $this->_get_limit();
            // $pagination_data['pagination_root'] = 'books/manage';
            // $pagination_data['record_name_plural'] = 'books';
            // $pagination_data['include_showing_statement'] = true;
            // $data['pagination_data'] = $pagination_data;
        
            // $data['rows'] = $this->_reduce_rows($all_rows);
            // $data['view_module'] = 'books';
            // $data['view_file'] = 'manage';
            // $this->template('bootstrappy', $data);
            //end of sample

        } else {
            $data['no_blog_notices'] = 'No Blog Notices yet'; 
        }

        $data['headline'] = 'Our Blogs';
        $data['view_module'] = 'blog_notices';
        $data['view_file'] = 'display_blog';
        $this->template('public', $data);
    }


    function homepage_blog_notices(){
        $articles = $this->model->get('id desc','blog_notices', 3, 0);
        return $articles;
    }
    

    function _get_blog_notices_pics_html($data) {

        $this->module('pictures');
        $update_id = $data['blog_notices_obj']->id;

        $data['gallery_pics'] = $this->pictures->_fetch_pictures('blog_notices',$update_id);

        $gal = count($data['gallery_pics']);

        if ($gal>0) {
            $data['gallery_dir'] = BASE_URL.'blog_notices_pictures_thumb/'.$update_id.'/';
            
            $blog_notices_pics_html = $this->view('single_blog_notices_gallery', $data, true);
        } else {
            $blog_notices_pics_html = '';
        }

        return $blog_notices_pics_html;
    }
    

    function _get_blog_notices_video_html($data) {

        $blog_notices_video_html = $this->view('single_blog_notices_video', $data, true);

        return $blog_notices_video_html;
    }

    function _get_prev_next($blog_notices_id) {
        //get the prev link

        $params['id'] = $blog_notices_id;
        
        $sql1 = 'SELECT * FROM blog_notices WHERE id<:id 
                 AND published = 1   
                 ORDER BY id desc 
                 LIMIT 0,1';
        $result1 = $this->model->query_bind($sql1, $params, 'object');
    
        if ($result1 == false) {
            //no prev video found so link back to the sections home area
            $prev = BASE_URL.'blog_notices/all';
        } else {
            //get the code for the video
            $target_blog_notices_url = $result1[0]->url_string;
            $prev = BASE_URL.'blog_notices/display/'.$target_blog_notices_url;
        }
    
        //get the next link
        $sql2 = str_replace('id<:id', 'id>:id', $sql1);
        $sql2 = str_replace('id desc', 'id', $sql2);
        $result2 = $this->model->query_bind($sql2, $params, 'object');
    
        if ($result2 == false) {
            //no next video found so link back to the sections home area
            $next = $prev = BASE_URL.'blog_notices/all';
        } else {
            $target_blog_notices_url = $result2[0]->url_string;
            $next = BASE_URL.'blog_notices/display/'.$target_blog_notices_url;
        }
    
        $prev_next_links['prev'] = $prev;
        $prev_next_links['next'] = $next;
        return $prev_next_links;
    }


    function _init_filezone_settings() {
        $data['targetModule'] = 'blog_notices';
        $data['destination'] = 'blog_notices_pictures';
        $data['destination_thumb'] = 'blog_notices_pictures_thumb';
        $data['max_file_size'] = 1200;
        $data['max_width'] = 2500;
        $data['max_height'] = 1400;
        $data['resized_max_width'] = 2500;
        $data['resized_max_height'] = 1200;
        $data['max_width_thumb'] = 420;
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
        
        $data['notice_sources_options'] = $this->_get_notice_sources_options($data['notice_sources_id']);


        if (is_numeric($update_id)) {
            $data['headline'] = 'Update Blog Notice Record';
            $data['cancel_url'] = BASE_URL.'blog_notices/show/'.$update_id;
        } else {
            $data['headline'] = 'Create New Blog Notice Record';
            $data['cancel_url'] = BASE_URL.'blog_notices/manage';
        }

        $additional_includes_top[] = BASE_URL.'blog_notices_module/cleditor/jquery.cleditor.css';
        $additional_includes_top[] = BASE_URL.'blog_notices_module/js/jquery-1.8.2.min.js';
        $additional_includes_top[] = BASE_URL.'blog_notices_module/cleditor/jquery.cleditor.min.js';
        $data['additional_includes_top'] = $additional_includes_top;

        $additional_includes_btm[] = BASE_URL.'blog_notices_module/js/app.js';
        $data['additional_includes_btm'] = $additional_includes_btm;


        $data['form_location'] = BASE_URL.'blog_notices/submit/'.$update_id;
        $data['view_file'] = 'create';
        $this->template('bootstrappy', $data);
    }

    function manage() {
        $this->module('trongate_security');
        $this->trongate_security->_make_sure_allowed();

        if (segment(4) !== '') {
            $data['headline'] = 'Search Results';
            $searchphrase = trim($_GET['searchphrase']);
            $params['blog_title'] = '%'.$searchphrase.'%';
            $params['blog_sub_title'] = '%'.$searchphrase.'%';
            $sql = 'select * from blog_notices
            WHERE blog_title LIKE :blog_title
            OR blog_sub_title LIKE :blog_sub_title
            ORDER BY uploaded_date desc';
            $all_rows = $this->model->query_bind($sql, $params, 'object');
        } else {
            $data['headline'] = 'Manage Blog Notices';
            $all_rows = $this->model->get('uploaded_date desc');
        }

        $pagination_data['total_rows'] = count($all_rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->_get_limit();
        $pagination_data['pagination_root'] = 'blog_notices/manage';
        $pagination_data['record_name_plural'] = 'blog notices';
        $pagination_data['include_showing_statement'] = true;
        $data['pagination_data'] = $pagination_data;

        $data['rows'] = $this->_reduce_rows($all_rows);
        $data['selected_per_page'] = $this->_get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;
        $data['view_module'] = 'blog_notices';
        $data['view_file'] = 'manage';
        $this->template('bootstrappy', $data);
    }

    function show() {
        $this->module('trongate_security');
        $token = $this->trongate_security->_make_sure_allowed();
        $update_id = segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('blog_notices/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['published'] = ($data['published'] == 1 ? 'yes' : 'no');
        $data['token'] = $token;

        if ($data == false) {
            redirect('blog_notices/manage');
        } else {
            //generate picture folders, if required
            $picture_settings = $this->_init_picture_settings();
            $this->_make_sure_got_destination_folders($update_id, $picture_settings);

            //attempt to get the current picture
            $column_name = $picture_settings['target_column_name'];

            if ($data[$column_name] !== '') {
                //we have a picture - display picture preview
                $data['draw_picture_uploader'] = false;
            } else {
                //no picture - draw upload form
                $data['draw_picture_uploader'] = true;
            }
            $data['update_id'] = $update_id;
            $data['headline'] = 'Blog Notice Information';
            $data['filezone_settings'] = $this->_init_filezone_settings();
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
                $row->published = ($row->published == 1 ? 'yes' : 'no');
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

            $this->validation_helper->set_rules('blog_title', 'Blog Title', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('blog_sub_title', 'Blog Sub Title', 'min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('notice', 'Notice', 'required|min_length[2]');
            $this->validation_helper->set_rules('youtube', 'Youtube Code', 'min_length[5]');
            $this->validation_helper->set_rules('published_date', 'Pubished Date', 'required|valid_datepicker_us');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = segment(3);
                $data = $this->_get_data_from_post();
                $data['notice_sources_id'] = (is_numeric($data['notice_sources_id']) ? $data['notice_sources_id'] : 0);
                $data['url_string'] = strtolower(url_title($data['blog_title']));               
                $data['published_date'] = date('Y-m-d', strtotime($data['published_date']));
                $data['notice'] = str_replace('<script>', '',$data['notice']);
                $published_date = strtotime($data['published_date']);
                if($published_date <= time()) {
                    $data['published'] = 1;  
                } else {
                    $data['published'] = 0;
                }

                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'blog_notices');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $data['uploaded_date'] = date('Y-m-d', time());
                    $update_id = $this->model->insert($data, 'blog_notices');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('blog_notices/show/'.$update_id);

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
            //delete all of the blog categories associated with this record
            $sql = 'delete from associated_blog_notices_and_blog_categories where blog_notices_id = :update_id';
            $this->model->query_bind($sql, $params);

            //delete all of the comments associated with this record
            $sql = 'delete from trongate_comments where target_table = :module and update_id = :update_id';
            $params['module'] = 'blog_notices';
            $this->model->query_bind($sql, $params);

            $this->_delete_pictures_from_blog($params['update_id']);

            //delete the record
            $this->model->delete($params['update_id'], 'blog_notices');

            //set the flashdata
            $flash_msg = 'The record was successfully deleted';
            set_flashdata($flash_msg);

            //redirect to the manage page
            redirect('blog_notices/manage');
        }
    }

    function _delete_pictures_from_blog($update_id) {

        $sql = "SELECT id, picture FROM pictures WHERE target_module = 'blog_notices' AND target_module_id = ".$update_id." ";
        $pictures = $this->model->query($sql);

        if(!empty($pictures)) {
            foreach($pictures as $pic) {
                $target_file = 'blog_notices_pictures/'.$prop_id.'/'.$pic['picture'];
                if (file_exists($target_file)) {
                    unlink($target_file);                     
                }
    
                $target_file_thumb = 'blog_notices_pictures_thumb/'.$prop_id.'/'.$pic['picture'];
                if (file_exists($target_file_thumb)) {
                    unlink($target_file_thumb);                     
                }
    
                $this->model->delete($pic['id'], 'pictures');
                
            }
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
        redirect('blog_notices/manage');
    }

    function _get_data_from_db($update_id) {
        $record_obj = $this->model->get_where($update_id, 'blog_notices');

        if ($record_obj == false) {
            $this->template('error_404');
            die();
        } else {
            $data = (array) $record_obj;
            return $data;        
        }
    }

    function _get_data_from_post() {
        $data['blog_title'] = post('blog_title', true);
        $data['blog_sub_title'] = post('blog_sub_title', true);
        $data['notice'] = post('notice');
        $data['youtube'] = post('youtube', true);
        $data['published_date'] = post('published_date', true);        
        $data['notice_sources_id'] = post('notice_sources_id');
        return $data;
    }

    function _get_blog_categories_options($selected_key) {
        $this->module('module_relations');
        $options = $this->module_relations->_fetch_options($selected_key, 'blog_notices', 'blog_categories');
        return $options;
    }
    function _init_picture_settings() { 
        $picture_settings['max_file_size'] = 5000;
        $picture_settings['max_width'] = 4000;
        $picture_settings['max_height'] = 4000;
        $picture_settings['resized_max_width'] = 1000;
        $picture_settings['resized_max_height'] = 1000;
        $picture_settings['destination'] = 'blog_notices_pics';
        $picture_settings['target_column_name'] = 'picture';
        $picture_settings['thumbnail_dir'] = 'blog_notices_pics_thumbnails';
        $picture_settings['thumbnail_max_width'] = 120;
        $picture_settings['thumbnail_max_height'] = 120;
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

            $validation_str = 'allowed_types[gif,jpg,JPG,jpeg,png]|max_size['.$max_file_size.']|max_width['.$max_width.']|max_height['.$max_height.']';
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
        $picture_path = $picture_settings['destination'].'/'.$update_id.'/'.$picture_name;

        if (file_exists($picture_path)) {
            unlink($picture_path);
        }

        if (isset($picture_settings['thumbnail_dir'])) {
            $thumbnail_path = $picture_settings['thumbnail_dir'].'/'.$update_id.'/'.$picture_name;
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

    function _get_notice_sources_options($selected_key) {
        if(!empty($selected_key)){
            $this->module('module_relations');
            $options = $this->module_relations->_fetch_options($selected_key, 'blog_notices', 'notice_sources');
            return $options;
        }
        
    }
}