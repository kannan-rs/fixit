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

	public function getCustomerList( $record = "") {
		if(isset($record) && !is_null($record) && $record != "") {
			$this->db->where('sno', $record);	
		}

		$this->db->where('belongs_to', "customer");
		
		$this->db->select(["*"]);

		$query = $this->db->from('user_details')->get();
		
		$customer = $query->result();
		
		return $customer;
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