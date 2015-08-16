<?php

class Model_users extends CI_Model {
	public function canLogin($email, $password) {

		$this->db->where('user_name', $email);
		$this->db->where('password', $password);
		$this->db->where('status', '1');
		$this->db->where('deleted', '0');

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

	public function getAccountType($email, $password) {

		$this->db->where('user_name', $email);
		$this->db->where('password', $password);
		$this->db->where('deleted', '0');

		$query = $this->db->get('users');

		if($query->num_rows() == 1) {
			if($user_row = $query->result()) {
				return $user_row[0]->account_type;
			}
		}
	}

	public function getUserId($email, $password) {

		$this->db->where('user_name', $email);
		$this->db->where('password', $password);
		$this->db->where('deleted', '0');

		$query = $this->db->get('users');

		if($query->num_rows() == 1) {
			if($user_row = $query->result()) {
				return $user_row[0]->sno;
			}
		}
	}

	public function getUserSnoViaEmail($email = '') {
		if($email != '') {
			$this->db->where('user_name', $email);
			$this->db->where('deleted', '0');

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

	public function getUsersList($params = "") {
		$queryStr 	= "SELECT users.sno, users.user_name, users.password, users.password_hint, users.account_type, ";
		$queryStr	.= "users.status, users.updated_by, users.created_by, users.created_date, users.updated_date, user_details.belongs_to ";
		$queryStr 	.= "FROM `users` LEFT JOIN `user_details` ON users.user_name = user_details.email where users.deleted = 0 AND user_details.deleted = 0";

		 if($params && $params != "" && $params != 0) {
			$queryStr .= " AND users.sno = ".$params;			
		}

		$query = $this->db->query($queryStr);
		$users = $query->result();
		return $users;
	}

	public function insertUserDetails($params) {
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

	public function insertUsers($params) {
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

	public function getUserDetailsByEmail($email) {
		$this->db->where('email', $email);
		$this->db->where('deleted', '0');

		$this->db->select([
			"sno",
			"last_name",
			"first_name",
			"login_id",
			"belongs_to",
			"referred_by",
			"referred_by_id",
			"type",
			"belongs_to_id",
			"status",
			"DATE_FORMAT(active_start_date, \"%m/%d/%y\") as active_start_date",
			"DATE_FORMAT(active_end_date, \"%m/%d/%y\") as active_end_date",
			"email",
			"contact_ph1",
			"contact_mobile",
			"contact_alt_mobile",
			"primary_contact",
			"addr1",
			"addr2",
			"addr_city",
			"addr_state",
			"addr_country",
			"addr_pin",
			"contact_pref",
			"DATE_FORMAT(created_dt, \"%m/%d/%y %H:%i:%S\") as created_dt",
			"DATE_FORMAT(last_updated_dt, \"%m/%d/%y %H:%i:%S\") as last_updated_dt",
			"created_by",
			"updated_by"
		]);

		$query = $this->db->get('user_details');
		$user_details = $query->result();
		return $user_details;
	}

	public function getUserDetailsBySno($sno) {
		if(!isset($sno) || empty($sno)) {
			return [];
		}
		
		$this->db->where('sno', $sno);
		$this->db->where('deleted', '0');

		$query = $this->db->get('user_details');
		$user_details = $query->result();
		return $user_details;
	}

	public function updateUserTable($params, $sno) {
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

	public function updateUserTableByEmail($params, $email) {
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

	public function updateDetailsTable($params, $record) {
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

	public function deleteUser($record) {
		$response = array(
			'status' => 'error'
		);
		if($record && $record!= "") {
			$this->db->where('sno', $record);

			$data = array(
				'deleted' 				=> 1
			);
			
			if($this->db->update('users', $data)) {
				$response['status']		= "success";
				$response['message']	= "User Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the records";
			}
		} else {
			$response['message']	= 'Invalid User, Please try again';
		}
		return $response;
	}

	public function deleteUserDetails($email) {
		$response = array(
			'status' => 'error'
		);
		if($email && $email!= "") {
			$this->db->where('email', $email);

			$data = array(
				'deleted' 				=> 1
			);
			
			if($this->db->update('user_details', $data)) {
				$response['status']		= "success";
				$response['message']	= "User Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the records";
			}
		} else {
			$response['message']	= 'Invalid User, Please try again';
		}
		return $response;
	}
}