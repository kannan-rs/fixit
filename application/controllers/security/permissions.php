<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions extends CI_Controller {

	public function __construct()
   {
        parent::__construct();
        $this->load->helper('url');
		$controller = $this->uri->segment(1);
		$page = $this->uri->segment(2);
		$module = $this->uri->segment(3) ? $this->uri->segment(3): "";
		$sub_module = $this->uri->segment(3) ? $this->uri->segment(3): "";
		$function = $this->uri->segment(4) ? $this->uri->segment(4): "";
		$record = $this->uri->segment(5) ? $this->uri->segment(5): "";
	}

	public function getComponentInfo() {
		$response = array(
			'status'	=> "error"
		);
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$response["message"] 			= "No permission to execute this operation";
			print_r(json_encode($response));
			return false;
		}
		
		$this->load->model('security/model_permissions');

		$getParams = array(
			"dataFor" => "all"
		);
		$data = $this->model_permissions->getAllList( $getParams );
		
		$response = array(
			'status' => "success",
			'data' => $data
		);
		print_r(json_encode($response));
	}

	public function inputForm() {
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$no_permission_options = array(
				'page_disp_string' => "create permission"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}
		$this->load->model('security/model_permissions');

		$permissionId = $this->input->post('permissionId');
		$type = $this->input->post('type');
	
		$getParams = array(
			'type' => $type,
			'permissionId' => $permissionId,
			'dataFor' => "all"
		);

		$data = $this->model_permissions->getAllList($getParams);

		$permission = $permissionId ? $this->model_permissions->getPermissionsById( $getParams ) : [];

		$viewParams = array(
			'function'=>"view",
			'record'=>"all",
			'type' => $type,
			'permissionId' => $permissionId,
			'data'=>$data,
			'permission' => $permission
		);
		
		echo $this->load->view("security/permissions/inputForm", $viewParams, true);
	}

	public function getPageForType() {
		$response = array(
			'status'	=> "error"
		);
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$response["message"] 			= "No permission to execute this operation";
			print_r(json_encode($response));
			return false;
		}

		$this->load->model('security/model_permissions');

		$type = $this->input->post('type');
	
		$getParams = array(
			'type' => $type,
			'dataFor' => "all"
		);

		$data = $this->model_permissions->getAllList($getParams);

		$viewParams = array(
			'function'=>"view",
			'record'=>"all",
			'type' => $type,
			'data'=>$data
		);
		
		echo $this->load->view("security/permissions/page_for_type", $viewParams, true);
	}

	/*public function getPermissionsById() {
		$this->load->model('security/model_permissions');

		$permissionId = $this->input->post('permissionId');

		$response = array(
			'status' => "success",
			'data' => []
		);

		if($permissionId != "") {
			$getPermissionsParam = array (
				'permissionId' => $permissionId
			);

			$data = $this->model_permissions->getUserPermission( $getPermissionsParam );

			if($data && count($data)) {
				$response["data"] = $data;
			}
		} else {
			$response["status"] = "error";
			$response["message"] = "Invalid Request, Argument missing";
		}

		return $response;
	}*/

	public function getPermissions() {
		$response = array(
			'status'	=> "error"
		);
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$response["message"] 			= "No permission to execute this operation";
			print_r(json_encode($response));
			return false;
		}

		$this->load->model('security/model_permissions');

		$user_id = $this->input->post('user_id');
		$role_id = $this->input->post('role_id');

		$function_id = $this->input->post('function_id');
		$type = $this->input->post('type');

		$response = array(
			'status' => "success",
			'data' => []
		);

		if($user_id != "" || ($type != "" && $role_id != "")) {
			$getPermissionsParam = array (
				'user_id' => $user_id,
				'role_id' => $role_id,
				'function_id' => $function_id,
				'type' => $type
			);

			$data = $this->model_permissions->getUserPermission( $getPermissionsParam );

			if($data && count($data)) {
				$response["data"] = $data;
			}
		} else {
			$response["status"] = "error";
			$response["message"] = "Invalid Request, Argument missing";
		}

		print_r(json_encode($response));
	}

	public function setPermissions() {
		$response = array(
			'status'	=> "error"
		);
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$response["message"] 			= "No permission to execute this operation";
			print_r(json_encode($response));
			return false;
		}

		$this->load->model('security/model_permissions');
		$response = $this->model_permissions->setUserPermission();

		print_r(json_encode($response));
	}

	public function deleteRecord() {
		$response = array(
			'status'	=> "error"
		);
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$response["message"] 			= "No permission to execute this operation";
			print_r(json_encode($response));
			return false;
		}

		$this->load->model('security/model_permissions');
		$response = $this->model_permissions->deleteRecord();

		print_r(json_encode($response));
	}
}
?>