<?php

class Model_form_utils extends CI_Model {
	public function getCountryStatus( $abbr = "") {
	
		if(isset($abbr) && !is_null($abbr) && $abbr != "") {
			$this->db->where('abbreviation', $abbr);	
		}
		
		$this->db->select(["*"]);

		$query = $this->db->from('state')->get();

		$state = $query->result();
		
		return $state;
	}

	public function getMatchCityList( $city = "") {
		if(isset($city) && !is_null($city) && $city != "") {
			$this->db->like('city', $city, "after");
		}
		
		$this->db->select(["zipcode", "city", "state_abbreviation"]);
		//$this->db->distinct();
		$this->db->order_by("city", "asc");

		$query = $this->db->from('postal_codes')->get();
		
		$state = $query->result();
		
		return $state;
	}

	public function getPostalDetailsByCity( $city = "") {
		if(isset($city) && !is_null($city) && $city != "") {
			$this->db->where('city', $city);	
		}
		
		$this->db->select(["zipcode", "state_abbreviation"]);
		$this->db->distinct();
		$this->db->order_by("zipcode", "asc");

		$query = $this->db->from('postal_codes')->get();
		
		$state = $query->result();
		
		return $state;
	}

	public function getFromUsersList( $params = array()) {
		$response = array("status" => "error");

		$queryStr 	= "SELECT users.sno, users.user_name, ";
		$queryStr	.= "user_details.email, user_details.first_name, user_details.last_name ";
		$queryStr 	.= "FROM `users` LEFT JOIN `user_details` ON users.user_name = user_details.email where users.is_deleted = 0 AND user_details.is_deleted = 0";


		if(isset($params) && is_array($params)) {
			$emailId				= isset($params["emailId"]) ? $params["emailId"] : "";
			$role_disp_name			= isset($params["role_disp_name"]) ? $params["role_disp_name"] : "";
			$assignment				= isset($params["assignment"]) ? $params["assignment"] : "";
			$role_id 				= "";
			$logged_in_user 		= isset($params["logged_in_user"]) ? $params["logged_in_user"] : "";
			$contractor_user_list 	= isset($params["contractor_user_list"]) ? $params["contractor_user_list"] : "";
			$company_id 			= isset($params["company_id"]) ? $params["company_id"] : "";

			if(isset($role_disp_name) && !empty($role_disp_name)) {
				$this->load->model('security/model_roles');
				$role_id = $this->model_roles->get_role_id_by_role_name(constant("ROLE_".$role_disp_name));
			}
			
			if(!empty($emailId)) {
				$this->db->like('email', $emailId);
				$queryStr .=" AND `email` LIKE '%".$emailId."%'";
			}
			
			if(!empty($role_id)) {
				$this->db->where_in('users.role_id', $role_id);
				$queryStr .=" AND `role_id` IN (".$role_id.")";
			}

			if(!empty($assignment)) {
				$assignment = $assignment == "not assigned" ? '0' : $assignment;
				$this->db->where('belongs_to_id', $assignment);
				$queryStr .= " AND `belongs_to_id` = '".$assignment."'";
			}

			if(!empty($company_id)) {
				$this->db->where('belongs_to_id', $company_id);
				$queryStr .= " AND belongs_to_id = '".$company_id."'";
			}
		} else {
			/*$this->db->where('belongs_to', "customer");
			$queryStr .=" AND `belongs_to` = \"customer\"";*/
		}

		//echo $queryStr;
		
		//$this->db->select(["*"]);
		//$query = $this->db->from('user_details')->get();

		$query = $this->db->query($queryStr);

		//echo $this->db->last_query();
		
		if($this->db->_error_number()) {
			$response['message'] = $this->db->_error_message();	
		} else {
			$response['status']		= "success";
			$response['customer'] 	= $query->result();
		}
		
		return $response;
	}

	public function getAdjusterList( $record = "") {
		if(isset($record) && !is_null($record) && $record != "") {
			$this->db->where('sno', $record);	
		}

		$this->db->where('belongs_to', "adjuster");
		
		$this->db->select(["*"]);

		$query = $this->db->from('user_details')->get();
		
		$adjuster = $query->result();
		
		return $adjuster;
	}
}