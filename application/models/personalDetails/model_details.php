<?php

class Model_details extends CI_Model {
	public function get_users_list($params = "") {
		if($params && $params != "") {
			$this->db->where('email', $params);			
		}
		$query = $this->db->get('user_details');
		$users = $query->result();
		return $users;
	}

	public function update($params, $record) {
		$response = array();
		$this->db->where('sno', $record);

		if($this->db->update('user_details', $params)) {
			$response['status'] = "success";
			$response['record'] = $record;
		} else {
			$response['status'] = "failed";
			$response['message'] = $this->db->_error_message();
		}
		return $response;
	}
}