<?php
class Permissions_lib {

	private $CI;
	private $user_id;
	private $role_id;
	private $type;
	private $function_id;
	private $operation_id;
	private $data_filter;
	private $compDataByDB;
	private $compDataBySno;
	private $compDataById;

	public function __construct() {
        $this->CI =& get_instance();
	}

	private $compKeyMap = array(
        "users" 		=> "user_name",
        "roles" 		=> "role_id",
        "operations" 	=> "ope_id",
        "functions" 	=> "fn_id",
        "dataFilters" 	=> "data_filter_id"
    );

	private function convertCompDataBySno() {
		foreach ($this->compDataByDB as $key => $value) {
			$this->compDataBySno[$key] = array();
			$this->compDataById[$key] = array();
			for($i = 0; $i < count($value); $i++) {
                $this->compDataBySno[$key][$value[$i]->sno] = $value[$i];
             }
		};
	}

	private function getFunctionSnoByName( $function_name ) {
		foreach ($this->compDataBySno["functions"] as $key => $value) {
			if(strtolower($value->fn_name) == strtolower($function_name)) {
				return $value->sno;
				break;
			}
		}
	}

	private function getRoleIdByName( $role_name ) {
		$role_id = "";
		$this->CI->load->model("security/model_roles");
		$role_resp = $this->CI->model_roles->getRolesListByName($role_name);
		if($role_resp) {
			$role_id = $role_resp[0]->sno;
		}
		return $role_id;
	}

	private function _getPermissionOption( $modules = '') {
		$role_id = $this->CI->session->userdata('logged_in_role_id');
		
		/* Parameter For Project Permissions */
		$params = array(
			'type' 						=> 'default',
			'role_id' 					=> $role_id,
			'get_allowed_permissions' 	=> true,
			'function_name'				=> $modules
		);
		return $params;
	}

	private function _getPermissions() {
		$this->CI->load->model('security/model_permissions');

		$response = array(
			'status' => "success",
			'data' => []
		);
		/*echo $this->type."<br/>";
		echo $this->role_id."<br/>";*/

		if($this->type != "" && $this->role_id != "") {
			//echo "In Get Permission<br/>";
			$getPermissionsParam = array (
				'user_id' => '',
				'role_id' => $this->role_id,
				'function_id' => '',
				'type' => $this->type
			);
			//print_r($getPermissionsParam);

			$data = $this->CI->model_permissions->getUserPermission( $getPermissionsParam );

			if($data && count($data)) {
				$response["data"] = $data;
			}
		} else {
			$response["status"] = "error";
			$response["message"] = "Invalid Request, Argument missing";
		}

		return $response;
	}

	public function getRoleAndDisplayStr() {
		$this->CI->load->model('security/model_roles');

		$role_id = $this->CI->session->userdata('logged_in_role_id');
		if(!$role_id || $role_id == 0 || $role_id == "0") {
			$role_id = $this->getRoleIdByName( "Customer" );
			$this->CI->session->userdata('logged_in_role_id', $role_id);
		}
		$role_disp_name = $role_id ? strtolower($this->CI->model_roles->getRolesList($role_id)[0]->role_name) : "Customer";
		
		return array($role_id, $role_disp_name);
	}

	public function getPermissions( $modules = "" ) {
		$options = $this->_getPermissionOption( $modules );

		$this->CI->load->model('security/model_permissions');
		/*
			Take required data and convert it into usable SNO array format
		*/
		$getAllParams = array(
			"dataFor" => "all"
		);
		$this->compDataByDB = $this->CI->model_permissions->getAllList( $getAllParams );
		$this->convertCompDataBySno();

		$this->user_id 						= isset($options['user_id']) ? $options["user_id"] : "";
		$this->role_id 						= isset($options["role_id"]) ? $options["role_id"] : "";
		$this->type 						= isset($options["type"]) ? $options["type"] : "";
		$this->function_name 				= isset($options['function_name']) ? $options['function_name'] : "";
		$this->operation 					= isset($options['operation']) ? $options['operation'] : "";
		$this->get_allowed_permissions 		= isset($options['get_allowed_permissions']) ? $options['get_allowed_permissions'] : "";
		
		$this->function_id 		= $this->getFunctionSnoByName($this->function_name);
		$this->role_id 			= empty($this->role_id) ? $this->getRoleIdByName("Customer") : $this->role_id;

		$final_permission = array(
			"operation" 	=> [],
			"data_filter" 	=> []
		);

		if($this->role_id == "" || $this->type == "" || $this->function_id == "") {
			return $final_permission;
		}

		$this->all_function_id 	=  $this->getFunctionSnoByName("all");
		
		/*
			Get Permission list from "database > permission > table" for selected user by role
		*/
		$permissionResp = $this->_getPermissions();

		if($permissionResp["status"] == "error") {
			return $final_permission;
		}
		
		$permission = isset($permissionResp["data"]) ? $permissionResp["data"] : null;
		if(!$permission) {
			return $final_permission;
		}

		$opIdFromPermDB 	= null;
		$dfIdFromPermDB 	= null;

		$opIdFromPermDBForAll 	= null;
		$dfIdFromPermDBForAll 	= null;
		
		foreach ($permission as $key => $value) {
			if ($value->function_id == $this->function_id) {
				$opIdFromPermDB = explode(",", $value->op_id);
				$dfIdFromPermDB = explode(",", $value->data_filter_id);
			} elseif ($value->function_id == $this->all_function_id) {
				$opIdFromPermDBForAll 	= explode(",", $value->op_id);
				$dfIdFromPermDBForAll 	= explode(",", $value->data_filter_id);
			}
		}

		$opIdFromPermDB = $opIdFromPermDB ? $opIdFromPermDB : $opIdFromPermDBForAll;
		$dfIdFromPermDB = $dfIdFromPermDB ? $dfIdFromPermDB : $dfIdFromPermDBForAll;

		$opNameFromDB = [];
		$dfNameFromDB = [];

		for( $i = 0; $i < count($opIdFromPermDB); $i++) {
			$opNameFromDB[$i] = strtolower($this->compDataBySno["operations"][$opIdFromPermDB[$i]]->ope_name);
		}

		if(is_array($dfIdFromPermDB)) {
			$dfIdFromPermDB = array_filter($dfIdFromPermDB);
		}

		for( $i = 0; $i < count($dfIdFromPermDB); $i++) {
			$dfNameFromDB[$i] = strtolower($this->compDataBySno["dataFilters"][$dfIdFromPermDB[$i]]->data_filter_name);
		}

		if(in_array('all', $opNameFromDB)) {
			$opNameFromDB = array();
			foreach($this->compDataBySno["operations"] as $key => $value) {
				array_push($opNameFromDB, strtolower($value->ope_name));
			}
		}

		if(in_array('all', $dfNameFromDB)) {
			$dfNameFromDB = array();
			foreach($this->compDataBySno["dataFilters"] as $key => $value) {
				array_push($dfNameFromDB, strtolower($value->data_filter_name));
			}
		}
		
		if(!empty($this->get_allowed_permissions)) {
			$final_permission = array(
				"operation" 	=> $opNameFromDB,
				"data_filter" 	=> $dfNameFromDB
			);
		} elseif( in_array($this->operation, $opNameFromDB) || in_array("all", $opNameFromDB)) {
			$final_permission = array(
				"operation" 	=> $opNameFromDB,
				"data_filter" 	=> $dfNameFromDB
			);
		}

		return $final_permission;
	}

	public function is_allowed( $permissionFor, $operation) {
		$response = array( "status" => true );
		//Project > Permissions for logged in User by role_id
		$permission = $this->getPermissions( $permissionFor );

		/* If User dont have view permission load No permission page */
		if(!in_array($operation, $permission['operation'])) {
			$response["message"] 	= "No permission to execute this operation";
			$response["status"] = false;
		}
		return $response;
	}
}
?>