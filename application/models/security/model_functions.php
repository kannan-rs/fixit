<?php

class Model_functions extends CI_Model {
	public function get_functions_list($params = "") {
		if($params && $params != "" && $params != 0) {
			$this->db->where('sno', $params);			
		}
		//$this->db->where('status', '1');
		$query = $this->db->get('functions');
		$functions = $query->result();
		return $functions;
	}
}