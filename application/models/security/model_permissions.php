<?php

class Model_permissions extends CI_Model {
	public function getAllList( $params = array()) {
		$dataForArr 		= explode(",", $params["dataFor"]);

		$users_query 		= in_array("all", $dataForArr) || in_array("users", $dataForArr) ? $this->db->get('users') : null;
		$roles_query 		= in_array("all", $dataForArr) || in_array("roles", $dataForArr) ? $this->db->get('roles') : null;
		$operations_query 	= in_array("all", $dataForArr) || in_array("operations", $dataForArr) ? $this->db->get('operations') : null;
		$functions_query 	= in_array("all", $dataForArr) || in_array("functions", $dataForArr) ? $this->db->get('functions') : null;
		$dataFilters_query 	= in_array("all", $dataForArr) || in_array("datafilters", $dataForArr) ? $this->db->get('data_filters') : null;

		$users 			= $users_query ? $users_query->result() : null;
		$roles 			= $roles_query ? $roles_query->result() : null;
		$operations 	= $operations_query ? $operations_query->result() : null;
		$functions 		= $functions_query ? $functions_query->result() : null;
		$dataFilters 	= $dataFilters_query ? $dataFilters_query->result() : null;

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