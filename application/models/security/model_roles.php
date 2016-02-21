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
		$roles =  null;
		if(!empty($params)) {
			$this->db->where('role_name', $params);
			$query = $this->db->get('roles');
			$roles = $query->result();
			//print_r($this->db->last_query());
		}
		return $roles;
	}

	public function get_role_id_by_role_name( $role_name = '') {
		$role_id = false;
		if(!empty($role_name)) {
			$this->db->where('role_name', $role_name);

			$query = $this->db->get('roles');

			if($query->num_rows() == 1) {
				if($role_row = $query->result()) {
					$role_id = $role_row[0]->sno;
				}
			}
		}
		return $role_id;
	}
}