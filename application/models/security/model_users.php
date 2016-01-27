<?php

class Model_users extends CI_Model {
	public function canLogin($email, $password) {

		$this->db->where('user_name', $email);
		$this->db->where('password', $password);
		$this->db->where('status', '1');
		$this->db->where('deleted', '0');
		$this->db->where('activation_key', 'active');

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
				return $user_row[0]->role_id;
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

	public function getUserDetailsSnoViaEmail($email = '') {
		if($email != '') {
			$this->db->where('email', $email);
			$this->db->where('deleted', '0');

			$query = $this->db->get('user_details');

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

	public function getUsersList($params = "", $from_db = "users") {
		$queryStr 	= "SELECT users.sno, users.user_name, users.password, users.password_hint, users.role_id, ";
		$queryStr	.= "users.status, users.updated_by, users.created_by, users.created_date, users.updated_date, user_details.belongs_to, user_details.first_name, user_details.last_name ";
		$queryStr 	.= "FROM `users` LEFT JOIN `user_details` ON users.user_name = user_details.email where users.deleted = 0 AND user_details.deleted = 0";

		if($params && $params != "" && $params != 0) {
			if($from_db == "users") {
				$queryStr .= " AND users.sno = ".$params;			
			} else if($from_db == "user_details") {
				$queryStr .= " AND user_details.sno = ".$params;
			}
		}
		$queryStr .= " ORDER BY users.user_name";

		$query = $this->db->query($queryStr);
		$users = $query->result();
		return $users;
	}

	public function getUserDisplayName($params = "") {
		if(!empty($params) && $params != 0) {
			$queryStr 	= "SELECT users.user_name, user_details.first_name, user_details.last_name ";
			$queryStr 	.= "FROM `users` LEFT JOIN `user_details` ON users.user_name = user_details.email where users.deleted = 0 AND user_details.deleted = 0";
			$queryStr .= " AND users.sno = ".$params;
			
			$query = $this->db->query($queryStr);

			//echo $this->db->last_query();
			$users = $query->result();

			if($users && count($users)) {
				return $users[0]->first_name." ".$users[0]->last_name." (".$users[0]->user_name.")";
			}
		}
		return "--No Name--";
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
		//$this->db->flush_cache();
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
			"DATE_FORMAT(active_start_date, \"%m/%d/%Y\") as active_start_date",
			"DATE_FORMAT(active_end_date, \"%m/%d/%Y\") as active_end_date",
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
			"DATE_FORMAT(created_dt, \"%m/%d/%Y %H:%i:%S\") as created_dt",
			"DATE_FORMAT(last_updated_dt, \"%m/%d/%Y %H:%i:%S\") as last_updated_dt",
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

	public function activate_user($activation_key) {
		$response = array(
			'status' => 'error'
		);
		if($activation_key && $activation_key!= "") {
			
			$this->db->start_cache();

			$selectQueryStr = "select * from `users` where `activation_key` = '".$activation_key."'";
			//echo $selectQueryStr;
			$selectQuery 	= $this->db->query($selectQueryStr);
			$selectResult 	= $selectQuery->result();

			$this->db->where('activation_key', $activation_key);
			$this->db->where('status', '0');
			
			$data = array(
				'activation_key' 	=> "active",
				'status' 			=> '1'
			);
			
			if($this->db->update('users', $data)) {
				$response['status']		= "success";
				$response['message']	= "User Activation Successfully";
				$response['activated_user'] = $selectResult;
			} else {
				$response["message"] = "Error while Activation the records";
			}
			$this->db->stop_cache();
			$this->db->flush_cache();
		} else {
			$response['message']	= 'Invalid activation key for user, Please try again';
		}
		return $response;
	}

	public function get_user_details_address( $params ) {
		$user_id = $params["customer_id"];

		$response = array( "status" => "error");

		if(empty($user_id)) {
			$response["message"]	= "Invalid request";
		} else {
			$queryStr 	= "SELECT user_details.addr1, user_details.addr2, user_details.addr_city, user_details.addr_state, user_details.addr_country, user_details.addr_pin ";
			$queryStr 	.= "FROM `users` LEFT JOIN `user_details` ON users.user_name = user_details.email where users.deleted = 0 AND user_details.deleted = 0 AND users.sno = ".$user_id;

			$query = $this->db->query($queryStr);
			$users = $query->result();

			$response["users"]	=	$users;
		}
		return $response;
	}
}