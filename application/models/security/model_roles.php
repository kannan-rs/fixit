<?php

class Model_roles extends CI_Model {
	public function getRolesList($params = "") {
		if($params && $params != "" && $params != 0) {
			$this->db->where('sno', $params);			
		}
		$this->db->order_by("role_name", "asc"); 
		$query = $this->db->get('roles');

		$roles = $query->result();
		return $roles;
	}

	public function getRolesListByName($params = "") {
		$roles;
		if(!empty($params)) {
			$this->db->where('role_name', $params);
			$query = $this->db->get('roles');
			$roles = $query->result();
			//print_r($this->db->last_query());
		}
		return $roles;
	}
}