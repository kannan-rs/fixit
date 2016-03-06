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

	public function get_role_name_by_role_id( $role_id = '') {
		$role_name = false;
		if(!empty($role_id)) {
			$this->db->where('sno', $role_id);

			$query = $this->db->get('roles');

			if($query->num_rows() == 1) {
				if($role_row = $query->result()) {
					$role_name = $role_row[0]->role_name;
				}
			}
		}
		return $role_name;
	}

	public function get_role_id_from_user_table_by_user_id ( $user_id = "") {
		if(!empty($user_id)) {
			$this->db->where('sno', $user_id);
			$this->db->where('is_deleted', '0');

			$query = $this->db->get('user_details');

			if($query->num_rows() == 1) {
				if($user_row = $query->result()) {
					return $user_row[0]->sno;
				}
			} else {
				return false;
			}	
		}
	}
}