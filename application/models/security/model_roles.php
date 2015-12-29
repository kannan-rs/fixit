<?php

class Model_roles extends CI_Model {
	public function getRolesList($params = "") {
		if($params && $params != "" && $params != 0) {
			$this->db->where('sno', $params);			
		}
		$query = $this->db->get('roles');
		$roles = $query->result();
		return $roles;
	}

	public function getRolesListByName($params = "") {
		$this->db->where('role_name', $params);
		$query = $this->db->get('roles');
		$roles = $query->result();
		
		//print_r($this->db->last_query());
		return $roles;
	}
}