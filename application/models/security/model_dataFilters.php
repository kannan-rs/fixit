<?php

class Model_datafilters extends CI_Model {
	public function getDataFiltersList($params = "") {
		if($params && $params != "" && $params != 0) {
			$this->db->where('sno', $params);			
		}
		//$this->db->where('status', '1');
		$this->db->order_by("data_filter_name", "asc"); 
		$query = $this->db->get('data_filters');
		$dataFilters = $query->result();
		return $dataFilters;
	}
}