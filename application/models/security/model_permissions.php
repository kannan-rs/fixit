<?php

class Model_permissions extends CI_Model {
	public function getAllList( $params = "") {
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

	public function getDefaultPermission( $options ) {

		$user_id = $options["user_id"];
		$role_id = $options["role_id"];
		$type = $options["type"];

		$this->db->where('user_id', "");
		
		$permissions_query 		= $this->db->get('permissions');

		$permissions 			= $permissions_query->result();
		return $permissions;
	}

	public function getPermissionsById( $options ) {
		if($options["permissionId"] && $options["permissionId"] != "") {
			$this->db->where('sno', $options["permissionId"]);
			$permissions_query 		= $this->db->get('permissions');
			$permissions 			= $permissions_query->result();
			return $permissions;
		} else {
			return [];
		}
	}

	public function getUserPermission( $options) {

		$user_id = $options["user_id"];
		$role_id = $options["role_id"];
		$function_id = $options["function_id"];
		$type = $options["type"];

		if($type != 'default' && $user_id != "") {
			$this->db->where('user_id', $user_id);	
		} else {
			$this->db->where('user_id', '');
		}

		if($type == 'default' && $role_id != "") {
			$this->db->where('role_id', $role_id);	
		}

		if($function_id != "") {
			$this->db->where('function_id', $function_id);	
		}
		
		$permissions_query 		= $this->db->get('permissions');

		$permissions 			= $permissions_query->result();
		return $permissions;
	}

	public function setUserPermission() {
		$this->db->where('user_id', $this->input->post("user_id"));
		$type = $this->input->post('type');
		$user_id = $this->input->post('user_id');
		$role_id = $this->input->post('role_id');
		$op_id = $this->input->post('op_sno');
		$function_id = $this->input->post('fn_id');
		$data_filter_id = $this->input->post('df_sno');
		$permission_id = $this->input->post("permissionId");

		$data = array(
			'user_id' => $this->input->post('user_id'),
			'role_id' => $this->input->post('role_id'),
			'op_id' => $this->input->post('op_sno'),
			'function_id' => $this->input->post('fn_id'),
			'data_filter_id' => $this->input->post('df_sno')
		);

		if($permission_id != "") {
			$this->db->where('sno', $permission_id);
		} else if($type == "default" && $user_id == "") {
			$this->db->where('role_id', $role_id);
			$this->db->where('user_id', "");
		} else if($type != "default" && $user_id != "") {
			$this->db->where('role_id', $role_id);
			$this->db->where('user_id', $user_id);
		}

		if($function_id != "") {
			$this->db->where("function_id", $function_id);
		}

		$get_query 		= $this->db->get('permissions');
		$get 			= $get_query->result();
		$count = count($get);

		$response = array(
			"status" => "success"
		);
		if( $count > 1) {
			$response["status"] = "error";
			$response["message"] = "Something is wrong while updating permission, please try proper combination";
		} else if( $count == 1 ) {
			$sno = $get[0]->sno;
			$this->db->where('sno', $sno);
			if($this->db->update("permissions", $data)) {
				$response["action"] = "updated";
			} else {
				$response["status"] = "error";
				$response["message"] = $this->db->_error_message();
			}
		} else {
			if($this->db->insert("permissions", $data)) {
				$response["action"] = "inserted";
			} else {
				$response["status"] = "error";
				$response["message"] = $this->db->_error_message();
			}
		}

		return $response;
	}

	public function deleteRecord() {
		$permission_id = $this->input->post("permissionId");

		$response = array(
			"status" => "error"
		);

		if($permission_id && $permission_id != "") {
			$this->db->where('sno', $permission_id);
			if($this->db->delete("permissions")) {
				$response["status"] = "success";
				$response["action"] = "deleteed";
			} 
		}

		return $response;
	}
}
?>