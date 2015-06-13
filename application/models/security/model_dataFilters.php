<?php

class Model_dataFilters extends CI_Model {
	public function getDataFiltersList($params = "") {
		if($params && $params != "" && $params != 0) {
			$this->db->where('sno', $params);			
		}
		//$this->db->where('status', '1');
		$query = $this->db->get('data_filters');
		$dataFilters = $query->result();
		return $dataFilters;
	}
}