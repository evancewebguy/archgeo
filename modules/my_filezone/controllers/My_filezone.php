<?php
class My_filezone extends Trongate {

    function _draw_summary_panel($update_id, $filezone_settings) {
        $this->module('trongate_security');
        $data['token'] = $this->trongate_security->_make_sure_allowed();
        $this->_make_sure_got_sub_folder($update_id, $filezone_settings);
        $data['update_id'] = $update_id;
        $data['target_module'] = $filezone_settings['targetModule'];
        $data['uploader_url'] = 'my_filezone/uploader/'.$data['target_module'].'/'.$update_id;
        $data['pictures'] = $this->_fetch_pictures($update_id, $filezone_settings);
        $data['target_directory'] = BASE_URL.$data['target_module'].'_pictures_thumb/'.$update_id.'/';
        $this->view('multi_summary_panel', $data);
    }

    function _make_sure_got_sub_folder($update_id, $filezone_settings) {
        $destination = $filezone_settings['destination'];
        $target_dir = APPPATH.'public/'.$destination.'/'.$update_id;
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $destination_thumb = $filezone_settings['destination_thumb'];
        $target_dir = APPPATH.'public/'.$destination_thumb.'/'.$update_id;
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
    }

    function _fetch_pictures($update_id, $filezone_settings) {
        $data = [];
        $pictures_directory = $this->_get_pictures_directory_thumb($filezone_settings);
        $picture_directory_path = str_replace(BASE_URL, './', $pictures_directory . '/' . $update_id);

        if (is_dir($picture_directory_path)) {
            $pictures = scandir($picture_directory_path);
            foreach ($pictures as $key => $value) {
                if (($value !== '.') && ($value !== '..') && ($value !== '.DS_Store')) {
                    $data[] = $value;
                }
            }

        }

        return $data;
    }

    function _get_pictures_directory($filezone_settings) {
        $target_module = $filezone_settings['targetModule'];
        $directory = $target_module . '_pictures';
        return $directory;
    }

    function _get_pictures_directory_small($uploader_settings) {

        $target_module = $uploader_settings['targetModule'];
        $directory = $target_module . '_pictures_small';
        return $directory;
    }

    function _get_pictures_directory_thumb($uploader_settings) {

        $target_module = $uploader_settings['targetModule'];
        $directory = $target_module . '_pictures_thumb';
        return $directory;
    }

    function _remove_flashdata() {
        if (isset($_SESSION['flashdata'])) {
            unset($_SESSION['flashdata']);
        }
    }

    function removeDirectory($path) {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        rmdir($path);
        return;
    }

    function _get_previously_uploaded_files($code) {
        $data = [];
        $pictures_directory = BASE_URL.'module_resources/'.$code.'/picture_gallery';
        $picture_directory_path = str_replace(BASE_URL, './', $pictures_directory);

        if (is_dir($picture_directory_path)) {
            $pictures = scandir($picture_directory_path);
            foreach ($pictures as $key => $value) {
                if (($value !== '.') && ($value !== '..') && ($value !== '.DS_Store')) {
                    $data[] = $value;
                }
            }
        }
        return $data;
    }

    function _make_sure_got_dir($target_dir) {
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
    }

    function uploader() {
        $this->module('trongate_security');
        $data['token'] = $this->trongate_security->_make_sure_allowed();
        $target_module = segment(3);
        $update_id = segment(4);

        //get all of the uploaded files
        $this->module($target_module);
        $settings = $this->$target_module->_init_filezone_settings();
        $destination = $settings['destination_thumb'];
        $dir = $destination.'/'.$update_id;
        $previously_uploaded_files = [];
        if (is_dir($dir)){
            if ($dh = opendir($dir)){
                while (($file = readdir($dh)) !== false) {
                    $first_char = substr($file, 0, 1);

                    if ($first_char !== '.') {
                        $row_data['directory'] = $dir;
                        $row_data['filename'] = $file;
                        $row_data['overlay_id'] = $this->_get_overlay_id($file);
                        $previously_uploaded_files[] = $row_data;
                    }

                }
                closedir($dh);
            }
        }

        $additional_includes_top[] = BASE_URL.'my_filezone_module/css/trongate-filezone.css';
        $data['additional_includes_top'] = $additional_includes_top;
        $additional_includes_btm[] = BASE_URL.'my_filezone_module/js/trongate-filezone.js';
        $data['additional_includes_btm'] = $additional_includes_btm;
        $data['target_module'] = $settings['targetModule'];
        $target_module_desc = str_replace("_", " ", $data['target_module']);
        $data['target_module_desc'] = ucwords($target_module_desc);
        $data['previous_url'] = BASE_URL . $target_module . '/show/' . $update_id;
        $data['update_id'] = $update_id;
        $data['headline'] = 'Upload Pictures';
        $data['upload_url'] = BASE_URL.'my_filezone/upload/'.$target_module.'/'.$update_id;
        $data['delete_url'] = BASE_URL.'my_filezone/ditch';
        $data['previously_uploaded_files'] = $previously_uploaded_files;
        $data['view_module'] = 'my_filezone';
        $data['view_file'] = 'uploader';
        $this->template('bootstrappy', $data);
    }

    function _get_overlay_id($filename) {
        $bits = explode('.', $filename);
        $last_bit = $bits[count($bits)-1];
        $ditch = '.'.$last_bit;
        $replace = '-'.$last_bit;
        $overlay_id = str_replace($ditch, $replace, $filename);
        return $overlay_id;
    }

    function alt() {
        $data['view_module'] = 'my_filezone';
        $data['view_file'] = 'alt';
        $this->template('public_default', $data);
    }

    function _get_str_chuck($str, $target_length, $from_start=null) {
        $strlen = strlen($str);
        $start_pos = $strlen-$target_length;

        if (isset($from_start)) {
            $start_pos = 0;
        }

        $str_chunk = substr($str, $start_pos, $target_length);
        return $str_chunk;
    }

    function ditch() {
        api_auth();
        $post = file_get_contents('php://input');
        $posted_data = json_decode($post, true);
        $element_id = $posted_data['elId'];
        $update_id = $posted_data['update_id'];
        $target_module = $posted_data['target_module'];


        $this->module($target_module);
        $settings = $this->$target_module->_init_filezone_settings();
        $destination = $settings['destination'];
        $destination_thumb = $settings['destination_thumb'];

        $bits = explode('-', $element_id);
        $last_bit = '-'.$bits[count($bits)-1];
        $last_bit_len = strlen($last_bit);
        $target_len = strlen($element_id) - $last_bit_len;
        $first_chunk = $this->_get_str_chuck($element_id, $target_len, true);
        $correct_last_bit = str_replace('-', '.', $last_bit);
        $target_image_name = $first_chunk.$correct_last_bit;

        $this->_delete_picture_from_pictures($target_image_name,$destination_thumb,$target_module, $update_id);

        $target_file = $destination.'/'.$update_id.'/'.$target_image_name;

        if (file_exists($target_file)) {
            unlink($target_file);
            http_response_code(200);
            echo $element_id;            
        }

    }

    function _delete_picture_from_pictures($picture_name, $destination_thumb,$target_module, $update_id) {
        
        $picture_obj = $this->model->get_one_where('picture', $picture_name, 'pictures');

        if($picture_obj == true) {
            $this->model->delete($picture_obj->id, 'pictures');
        }

        $target_file_thumb = $destination_thumb.'/'.$update_id.'/'.$picture_name;

        if (file_exists($target_file_thumb)) {
            unlink($target_file_thumb);                     
        }

    }

    function upload() {
        api_auth();

        $request_type = $_SERVER['REQUEST_METHOD'];
        $target_module = segment(3);
        $update_id = segment(4);

        if ($request_type == 'DELETE') {
            $this->_remove_picture($target_module, $update_id);
        } else {
            $this->_do_upload($update_id, $target_module);
        }

    }

    function rotate() {
        api_auth();

        $request_type = $_SERVER['REQUEST_METHOD'];
        $target_module = segment(3);
        $update_id = segment(4);

        if ($request_type == 'POST') {
            /* $this->_rotate_picture($target_module, $update_id); */
            $post = file_get_contents('php://input');
            $decoded = json_decode($post, true);
            $picture_path = file_get_contents("php://input");

            $picture_name = $this->get_file_name($picture_path);

            $picture_path = str_replace(BASE_URL, './', $picture_path);

            $picture_obj = $this->model->get_one_where('picture', $picture_name, 'pictures'); 
            
            if($picture_obj == true) {

                $target_module = $picture_obj->target_module;
                $update_id= $picture_obj->target_module_id;

                $picture_path_ori = './' . $target_module . '_pictures/' . $update_id . '/' .$picture_name;
                if (file_exists($picture_path_ori)) {
                    unlink($picture_path_ori);
                    $this->_rotate_picture($target_module, $update_id,$picture_path_ori);
                    
                } 

               /*  $this->model->delete($picture_obj->id, 'pictures'); */
            }

            
            if (file_exists($picture_path)) {
                //delete the picture
                unlink($picture_path);
                $this->_rotate_picture($target_module, $update_id,$picture_path);
                echo $picture_path;
        
                header('Access-Control-Allow-Origin: *');
                header('Content-Type: application/json');
                $output['body'] = $picture_path;
                $output['code'] = 200;
                
                $code = $output['code'];
                http_response_code($code);
                echo $output['body'];

            } else {
                http_response_code(422);
                echo $picture_path;
            }
        }

    }

    function _rotate_picture($target_module, $update_id, $picture_path) {

        $rotateFilename = $picture_path;
         // PATH
        $degrees = 90;

        $fileType = strtolower(substr($picture_name, strrpos($picture_name, '.') + 1));

        if($fileType == 'png'){
        header('Content-type: image/png');
        $source = imagecreatefrompng($rotateFilename);
        $bgColor = imagecolorallocatealpha($source, 255, 255, 255, 127);
        // Rotate
        $rotate = imagerotate($source, $degrees, $bgColor);
        /* $this->_do_upload($update_id, $target_module); */
        imagesavealpha($rotate, true);
        imagepng($rotate,$rotateFilename);
        
        }

        if($fileType == 'jpg' || $fileType == 'jpeg'){
        header('Content-type: image/jpeg');
        $source = imagecreatefromjpeg($rotateFilename);
        // Rotate
        $rotate = imagerotate($source, $degrees, 0);
        imagejpeg($rotate,$rotateFilename);
        /* $this->_do_upload($update_id, $target_module); */
        }

        // Free the memory
        imagedestroy($source);
        imagedestroy($rotate);
        

       
    }

    function get_file_name($picture_path) {
        $bits = explode('/', $picture_path);
        $last_bit = $bits[count($bits)-1];
        return $last_bit;
    }

    function _remove_picture($target_module, $update_id) {
        $post = file_get_contents('php://input');
        $decoded = json_decode($post, true);
        $picture_path = file_get_contents("php://input");

        $picture_name = $this->get_file_name($picture_path);

        $picture_path = str_replace(BASE_URL, './', $picture_path);

        $picture_obj = $this->model->get_one_where('picture', $picture_name, 'pictures'); 
        
        if($picture_obj == true) {

            $target_module = $picture_obj->target_module;
            $update_id= $picture_obj->target_module_id;

            $picture_path_ori = './' . $target_module . '_pictures/' . $update_id . '/' .$picture_name;
            if (file_exists($picture_path_ori)) {
                unlink($picture_path_ori);
            } 

            $this->model->delete($picture_obj->id, 'pictures');
        }

        
        if (file_exists($picture_path)) {
            //delete the picture
            unlink($picture_path);
            $this->_fetch();
        } else {
            http_response_code(422);
            echo $picture_path;
        }
    }

    function _fetch() {
        $target_module = segment(3);
        $update_id = segment(4);

        if (($target_module == '') || (!is_numeric($update_id))) {
            http_response_code(422);
            echo 'Invalid target module and/or update_id.';
            die();
        }

        //get the settings
        $this->module($target_module);
        $filezone_settings = $this->$target_module->_init_filezone_settings();
        $pictures = $this->_fetch_pictures($update_id, $filezone_settings);
        http_response_code(200);
        echo json_encode($pictures);
    }

    function _prep_file_name($file_name) {
        $bits = explode('.', $file_name);
        $last_bit = '.'.$bits[count($bits)-1];

        //remove last_bit from the file_name
        $file_name = str_replace($last_bit, '', $file_name);
        $safe_file_name = $file_name;
        $safe_file_name = url_title($file_name);

        //get the first 8 chars
        $safe_file_name = substr($safe_file_name, 0, 8);
        $safe_file_name.= make_rand_str(4);
        $safe_file_name.= $last_bit;
        return $safe_file_name;
    }

    function _make_sure_image($value) {
        $target_str = 'image/';
        $first_six = substr($value['type'], 0, 6);

        if ($first_six !== $target_str) {
            http_response_code(403);
            echo 'Not an image!';
            die();
        }

    }

    function _do_upload($update_id, $target_module) {
        foreach ($_FILES as $key => $value) {
            $this->_make_sure_image($value);
            $file_name = $value['name'];
            $new_file_name = $this->_prep_file_name($file_name);
            $_FILES[$key]['name'] = $new_file_name;
        }

        //get picture settings
        $this->module($target_module);
        $filezone_settings = $this->$target_module->_init_filezone_settings();

        $config['targetModule']     = $target_module;
        $config['maxFileSize']      = $filezone_settings['max_file_size'];
        $config['maxWidth']         = 2500;
        $config['maxHeight']        = 1406;
        $config['max_width']  = $filezone_settings['resized_max_width'];
        $config['max_height'] = $filezone_settings['resized_max_height'];
        $config['destination']      = $filezone_settings['destination'] . '/' . $update_id;
   
        $config['destination_thumb']  = $filezone_settings['destination_thumb'] . '/' . $update_id;

        $config['max_width_thumb'] = $filezone_settings['max_width_thumb'];
        $config['update_id']      = $update_id;

        if (!is_dir($config['destination'])) {
            mkdir($config['destination'], 0755);
        }

        $destination_thumb = $config['destination_thumb'];
        if(strlen($destination_thumb)>0) {
            $target_dir = APPPATH.'public/'.$destination_thumb;
            if (!file_exists($target_dir)) {
                //generate the image folder
                mkdir($target_dir, 0777, true);
            }
        }

        $this->upload_pic($config);
       
        if (isset($_FILES['file1'])) {
            $picture_name = $_FILES['file1']['name'];
            $picture_name_ref = str_replace('.', '-', $picture_name);
            echo $picture_name_ref;
        }

        http_response_code(200);

    }

    public function upload_pic($data) {
        //check for valid image width and mime type
        $userfile = array_keys($_FILES)[0];
        $target_file = $_FILES[$userfile];

        $dimension_data = getimagesize($target_file['tmp_name']);
        $image_width = $dimension_data[0];

        if (!is_numeric($image_width)) {
            die('ERROR: non numeric image width');
        }

        $content_type = mime_content_type($target_file['tmp_name']);

        $str = substr($content_type, 0, 6);
        if ($str !== 'image/') {
            die('ERROR: not an image.');
        }

        $tmp_name = $target_file['tmp_name'];

        $data['image'] = new Image($tmp_name);
        $data['filename'] = '../public/'.$data['destination'].'/'.$target_file['name'];

        $data['picname'] = $target_file['name'];

        $data['tmp_file_width'] = $data['image']->getWidth();
        $data['tmp_file_height'] = $data['image']->getHeight();

        if (!isset($data['max_width'])) {
            $data['max_width'] = NULL;
        }

        if (!isset($data['max_height'])) {
            $data['max_height'] = NULL;
        }

        $this->save_the_pic($data);
       
        //rock the thumbnail
        if ((isset($data['thumbnail_max_width'])) && (isset($data['thumbnail_max_height'])) && (isset($data['thumbnail_dir']))) {
            $data['filename'] = '../public/'.$data['thumbnail_dir'].'/'.$target_file['name'];
            $data['max_width'] = $data['thumbnail_max_width'];
            $data['max_height'] = $data['thumbnail_max_height'];
            $this->save_the_pic($data);
        }
    }

    private function save_the_pic($data) {
        extract($data);
        $reduce_width = false;
        $reduce_height = false;

        if (!isset($data['compression'])) {
            $compression = 100;
        } else {
            $compression = $data['compression'];
        }

        if (!isset($data['permissions'])) {
            $permissions = 775;
        } else {
            $permissions = $data['permissions'];
        }

        //do we need to resize the picture?
        if ((isset($max_width)) && ($tmp_file_width>$max_width)) {
            $reduce_width = true;
        }

        if ((isset($max_height)) && ($tmp_file_width>$max_height)) {
            $reduce_height = true;
        }

        //resize rules figured out, let's rock...
        if (($reduce_width == true) && ($reduce_height == false)) {
            $image->resizeToWidth($max_width);
            $image->save($filename, $compression);
        }

        if (($reduce_width == false) && ($reduce_height == true)) {
            $image->resizeToHeight($max_height);
            $image->save($filename, $compression);
        }

        if (($reduce_width == false) && ($reduce_height == false)) {
            $image->save($filename, $compression);
        }

        if (($reduce_width == true) && ($reduce_height == true)) {
            $image->resizeToWidth($max_width);
            $image->resizeToHeight($max_height);
            $image->save($filename, $compression);
        }

        if(isset($data['targetModule'])) {
            $ditch = '../public/'.$data['targetModule'].'_pictures/'.$data['update_id'].'/';

            $picture_name = $data['picname'];
     
            $datap['picture'] = $picture_name;
            $datap['priority'] = 1;
            $datap['target_module'] = $data['targetModule'];
            $datap['target_module_id'] = $data['update_id'];
            $this->model->insert($datap, 'pictures');
            
            if (!is_dir($config['destination_thumb'])) {
                //Directory does not exist, so lets create it.
                mkdir($config['destination_thumb'], 0755);
            }

            $filename2 = str_replace('_pictures', '_pictures_thumb', $filename);
            $image->resizeToWidth($max_width_thumb);
            $image->save($filename2, $compression);


        }
    }


}