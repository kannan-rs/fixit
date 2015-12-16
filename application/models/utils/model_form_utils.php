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

	public function getCustomerList( $params = array()) {
		//print_r($params);
		$response = array("status" => "error");

		$queryStr 	= "SELECT users.sno, users.user_name, ";
		$queryStr	.= "user_details.email, user_details.first_name, user_details.last_name ";
		$queryStr 	.= "FROM `users` LEFT JOIN `user_details` ON users.user_name = user_details.email where users.deleted = 0 AND user_details.deleted = 0";

		/*
		if($params && $params != "" && $params != 0) {
			if($from_db == "users") {
				$queryStr .= " AND users.sno = ".$params;			
			} else if($from_db == "user_details") {
				$queryStr .= " AND user_details.sno = ".$params;
			}
		}*/
		if(isset($params) && is_array($params)) {
			$emailId	= isset($params["emailId"]) ? $params["emailId"] : "";
			$belongsTo	= isset($params["belongsTo"]) ? $params["belongsTo"] : "";
			$assignment	= isset($params["assignment"]) ? $params["assignment"] : "";
			
			if(!empty($emailId)) {
				$this->db->like('email', $emailId);
				$queryStr .=" AND `email` LIKE '%".$emailId."%'";
			}
			if(!empty($belongsTo)) {
				$belongsToArr = explode("|", $belongsTo);
				$belongsToStr = "";
				for($i = 0; $i < count($belongsToArr); $i++) {
					$belongsToArr[$i] = $belongsToArr[$i] == "empty" ? "" : $belongsToArr[$i];
					$belongsToStr .= $i > 0 ? "," : "";
					$belongsToStr .= "'".$belongsToArr[$i]."'";
				}
				$this->db->where_in('belongs_to', $belongsToArr);
				$queryStr .=" AND `belongs_to` IN (".$belongsToStr.")";
			} else {
				$this->db->where('belongs_to', "customer");
				$queryStr .=" AND `belongs_to` = \"customer\"";
			}

			if(!empty($assignment)) {
				$assignment = $assignment == "not assigned" ? '0' : $assignment;
				$this->db->where('belongs_to_id', $assignment);
				$queryStr .= " AND `belongs_to_id` = '".$assignment."'";
			}
		} else {
			$this->db->where('belongs_to', "customer");
			$queryStr .=" AND `belongs_to` = \"customer\"";
		}
		
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