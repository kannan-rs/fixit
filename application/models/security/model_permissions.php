<?php

class Model_permissions extends CI_Model {
	public function get_all_list( $params = "") {
		$users_query 		= $this->db->get('users');
		$roles_query 		= $this->db->get('roles');
		$operations_query 	= $this->db->get('operations');
		$functions_query 	= $this->db->get('functions');
		$dataFilters_query 	= $this->db->get('data_filters');

		$users 			= $users_query->result();
		$roles 			= $roles_query->result();
		$operations 	= $operations_query->result();
		$functions 		= $functions_query->result();
		$dataFilters 	= $dataFilters_query->result();

		$output = array( 
			'users' 		=> $users,
			'roles' 		=> $roles,
			'operations' 	=> $operations,
			'functions' 	=> $functions,
			'dataFilters' 	=> $dataFilters
		);
		return $output;
	}

	public function get_user_permission() {
		$this->db->where('user_id', $this->input->post("user_id"));
		$permissions_query 		= $this->db->get('permissions');

		$permissions 			= $permissions_query->result();
		return $permissions;
	}

	public function set_user_permission() {
		$this->db->where('user_id', $this->input->post("user_id"));
		$get_query 		= $this->db->get('permissions');

		$get 			= $get_query->result();

		$data = array(
			   'role_id' => $this->input->post('role_id'),
			   'op_id' => $this->input->post('op_id'),
			   'function_id' => $this->input->post('fn_id'),
			   'data_filter_id' => $this->input->post('df_id')
		);

		if(count($get)) {
			$this->db->where('user_id', $this->input->post("user_id"));
			if($this->db->update("permissions", $data)) {
				return "updated";
			} else {
				return $this->db->_error_message();
			}
		} else {
			$data["user_id"] = $this->input->post('user_id');
			if($this->db->insert("permissions", $data)) {
				return "inserted";
			} else {
				return $this->db->_error_message();
			}
		}
	}
}
?>