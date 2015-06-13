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
}