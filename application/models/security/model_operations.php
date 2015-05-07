<?php

class Model_operations extends CI_Model {
	public function get_operations_list($params = "") {
		if($params && $params != "" && $params != 0) {
			$this->db->where('sno', $params);			
		}
		//$this->db->where('status', '1');
		$query = $this->db->get('operations');
		$operations = $query->result();
		return $operations;
	}
}