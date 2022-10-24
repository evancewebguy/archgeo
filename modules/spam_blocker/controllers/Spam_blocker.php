<?php
class Spam_blocker extends Trongate {

	private $allowed_user_levels = array('admin'); //which Trongate user levels are allowed to submit indicators?

	function _run_assumption_of_spam() {
		//messages that have been in the inbox for more than 7 days that do NOT have comments get moved to 'junk'
		$one_week = 604800;
		$one_week_back = time() - $one_week;
		$one_month = 2592000;
		$one_month_back = time() - $one_month;

		$params1['one_week_back'] = $one_week_back;
		$sql1 = 'SELECT
						    enquiries.id,
						    trongate_comments.id as comment_id 
						FROM
						    enquiries
						LEFT OUTER JOIN
						    trongate_comments
						ON
						    enquiries.id = trongate_comments.update_id 
						WHERE 
						    enquiries.date_created < :one_week_back';
		$rows1 = $this->model->query_bind($sql1, $params1, 'object');

		foreach($rows1 as $row) {
			if (!isset($row->comment_id)) {
				$update_id = $row->id;
				$data['message_folder_id'] = 2; //junk
				$this->model->update($update_id, $data, 'enquiries');
			}
		}
		
		//messages in junk that are older than 30 days get deleted
		$sql2 = 'select id from enquiries where message_folder_id = 2 and date_created <'.$one_month_back;
		$rows2 = $this->model->query($sql2, 'object');

		$args['target_table'] = 'enquiries';
		foreach($rows2 as $row) {
			//delete this message completely
			$this->model->delete($row->id, 'enquiries');
			//delete comments that are associated with this message
			$args['update_id'] = $row->id;
			$sql3 = 'delete from trongate_comments where target_table = :target_table and update_id = :update_id';
			$this->model->query_bind($sql3, $args);
		}

	}

	function _draw_modal_btns() {
		$this->module('trongate_tokens');
		$data['token'] = $this->trongate_tokens->_attempt_get_valid_token();
		$data['post_indicators_url'] = BASE_URL.'spam_blocker/submit_indicators';
		$data['view_module'] = 'spam_blocker';
		$this->view('modal_btns', $data);
	}

    function submit_indicators() {
    	$token = (isset($_SERVER['HTTP_TRONGATETOKEN']) ? $_SERVER['HTTP_TRONGATETOKEN'] : false);
    	if ($token == false) {
	    	http_response_code(401);
	    	die();
    	} else {
    		$this->module('trongate_tokens');
    		$user_obj = $this->trongate_tokens->_get_user_obj($token);

    		if ($user_obj == false) {
    			http_response_code(401);
    			die();
    		} else {
    			$allowed_user_levels = $this->allowed_user_levels;
    			if (!in_array($user_obj->user_level, $allowed_user_levels)) {
	    			http_response_code(401);
	    			die();
    			}
    		}
    	}

    	$posted_data = file_get_contents('php://input');
    	$args = (array) json_decode($posted_data);

    	if ((!isset($args['indicatorHTML'])) || (!isset($args['scoreToAllocate']))) {
    		http_response_code(400);
    		die();
    	}

    	if (isset($args['indicatorHTML'])) {
    		$indicators = explode('<div><b>', $args['indicatorHTML']);
    	}

    	if (isset($args['scoreToAllocate'])) {
    		$score = $args['scoreToAllocate'];
    	}

    	unset($indicators[0]);

    	foreach($indicators as $indicator) {
    		$data = $this->_extract_key_and_value($indicator);
    		$data['score'] = $score;
    		$this->_attempt_insert($data);
    	}

    }

    function _attempt_insert($params) {
    	$data['score'] = $params['score'];
    	unset($params['score']);
    	$sql = 'select * from spam_blocker_data where element = :key and target_string = :value';
    	$rows = $this->model->query_bind($sql, $params, 'object');
    	$num_rows = count($rows);
    	settype($num_rows, 'int');
    	if ($num_rows == 0) {
    		$data['element'] = $params['key'];
    		$data['target_string'] = $params['value'];
    		$this->model->insert($data, 'spam_blocker_data');
    	}
    }

    function _extract_key_and_value($indicator) {
    	$target_str = '</b> contains';
    	$strpos = strpos($indicator, $target_str);

    	$key = substr($indicator, 0, $strpos);
    	$data['key'] = strtolower($key);

    	$indicator = str_replace('span class="negative-text"', 'span', $indicator);
    	$indicator = str_replace('span class="positive-text"', 'span', $indicator);

    	$target_str1 = '</b> contains ';
    	$target_str1_len = strlen($target_str1);
    	$target_str1_len++;
    	$strpos1 = strpos($indicator, $target_str1);

    	$target_str2 = '</span>';
    	$strpos2 = strpos($indicator, $target_str2);

    	$diff = $strpos2 - $strpos1;
    	$value = substr($indicator, $strpos1+$target_str1_len, $diff-$target_str1_len);
    	$data['value'] = trim(str_replace('<span>', '', $value));
        return $data;
    }

	function _judge($data) {
		//data should contain 'sender_name', 'sender_email' and 'message'
		$score = 0;

		$rows = $this->model->get('id', 'spam_blocker_data');
		foreach($rows as $row) {

			if ((isset($data['sender_email'])) && ($row->element == 'sender email')) {
				$contains_str = $this->_contains_str($data['sender_email'], $row->target_string, true);
				if ($contains_str == true) {
					$point = $row->score;
					$score = $score+$point;
				}
			}

			if ((isset($data['sender_name'])) && ($row->element == 'sender name')) {
				$point = $this->_contains_str($data['sender_name'], $row->target_string, true);
				$score = $score+$point;
			}

			if ((isset($data['message'])) && ($row->element == 'message')) {
				$contains_str = $this->_contains_str($data['message'], $row->target_string, true);
				if ($contains_str == true) {
					$point = $row->score;
					$score = $score+$point;
				}
			}

		}

		$result = ($score>=0) ? 'not spam' : 'spam';
		return $result;
	}

	function _contains_str($haystack, $needle, $return_point=null) {

		if (is_numeric(strpos($haystack, $needle))) {
			$result = true;
			$point = -1;
		} else {
			$result = false;
			$point =0;
		}

		if (isset($return_point)) {
			return $point;
		} else {
			return $result;
		}

	}

}