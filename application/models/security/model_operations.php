<?php

class Model_operations extends CI_Model {
	public function getOperationsList($params = "") {
		if($params && $params != "" && $params != 0) {
			$this->db->where('sno', $params);			
		}
		//$this->db->where('status', '1');
		$this->db->order_by("ope_name", "asc"); 
		$query = $this->db->get('operations');
		$operations = $query->result();
		return $operations;
	}
}