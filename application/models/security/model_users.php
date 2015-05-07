<?php

class Model_users extends CI_Model {
	public function can_login($email, $password) {

		//echo $email."-".$password;
		$this->db->where('user_name', $email);
		$this->db->where('password', $password);
		$this->db->where('status', '1');

		$query = $this->db->get('users');
		$result = $query->result();

		if($query->num_rows() == 1) {
			if($user_row = $query->result()) {
				return $user_row[0]->sno;
			}
		} else {
			return false;
		}
	}

	public function get_account_type($email, $password) {

		$this->db->where('user_name', $email);
		$this->db->where('password', $password);
		$this->db->where('status', '1');

		$query = $this->db->get('users');

		if($query->num_rows() == 1) {
			if($user_row = $query->result()) {
				return $user_row[0]->account_type;
			}
		}
	}

	public function get_user_id($email, $password) {

		$this->db->where('user_name', $email);
		$this->db->where('password', $password);
		$this->db->where('status', '1');

		$query = $this->db->get('users');

		if($query->num_rows() == 1) {
			if($user_row = $query->result()) {
				return $user_row[0]->sno;
			}
		}
	}

	public function get_user_sno_via_email($email = '') {
		if($email != '') {
			$this->db->where('user_name', $email);

			$query = $this->db->get('users');

			if($query->num_rows() == 1) {
				if($user_row = $query->result()) {
					return $user_row[0]->sno;
				}
			} else {
				return false;
			}	
		} else {
			return false;
		}
	}

	public function get_users_list($params = "") {
		if($params && $params != "" && $params != 0) {
			$this->db->where('sno', $params);			
		}
		$this->db->where('status', '1');
		$query = $this->db->get('users');
		$users = $query->result();
		return $users;
	}

	public function insert_user_details($params) {
		$response = array();
		if($this->db->insert('user_details', $params) && $this->db->insert_id()) {
			$record = $this->db->insert_id();
			$response['status'] = "success";
			$response['record'] = $record;
		} else {
			$response['status'] = "failed";
			$response['message'] = $this->db->_error_message();
		}

		return $response;
	}

	public function insert_users($params) {
		$response = array();
		if($this->db->insert('users', $params) && $this->db->insert_id()) {
			$record = $this->db->insert_id();
			$response['status'] = "success";
			$response['record'] = $record;
		} else {
			$response['status'] = "failed";
			$response['message'] = $this->db->_error_message();
		}

		return $response;
	}

	public function get_user_details_by_email($email) {
		$this->db->where('email', $email);
		$query = $this->db->get('user_details');
		$user_details = $query->result();
		return $user_details;
	}

	public function update_user_table($params, $sno) {
		$response = array();
		$this->db->where('sno', $sno);

		if($this->db->update('users', $params)) {
			$response['status'] = "success";
		} else {
			$response['status'] = "failed";
			$response['message'] = $this->db->_error_message();
		}
		return $response;	
	}

	public function update_user_table_by_email($params, $email) {
		$response = array();
		$this->db->where('user_name', $email);

		if($this->db->update('users', $params)) {
			$response['status'] = "success";
		} else {
			$response['status'] = "failed";
			$response['message'] = $this->db->_error_message();
		}
		return $response;	
	}

	public function update_details_table($params, $record) {
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