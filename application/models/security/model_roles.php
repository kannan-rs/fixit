<?php

class Model_roles extends CI_Model {
	public function get_roles_list($params = "") {
		if($params && $params != "" && $params != 0) {
			$this->db->where('sno', $params);			
		}
		$query = $this->db->get('roles');
		$roles = $query->result();
		return $roles;
	}
}