<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends CI_Controller {

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

	public function viewAll() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$no_permission_options = array(
				'page_disp_string' => "roles list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('security/model_roles');
		$roles = $this->model_roles->getRolesList();

		$params = array(
			'function'=>"view",
			'record'=>"all",
			'roles'=>$roles
		);
		
		echo $this->load->view("security/roles/viewAll", $params, true);
	}

	public function getRoleList() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

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
		
		$this->load->model('security/model_roles');
		$roles = $this->model_roles->getRolesList();

		$response = array(
			"status" => "error",
		);

		if($roles) {
			$response["status"] 	= "success";
			$response["roles"] 		= $roles;
		} else {
			$response["message"] 	= "Error while fetching roles data";
		}
		
		print(json_encode($response));
	}
	
	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$no_permission_options = array(
				'page_disp_string' => "create role"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$params = array(
			'function'=>"createroleform",
			'record'=>""
		);

		echo $this->load->view("security/roles/inputForm", $params, true);
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

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

		$this->load->model('security/model_roles');

		$data = array(
		   'role_id' =>  $this->input->post('role_id'),
		   'role_name' => $this->input->post('role_name'),
		   'role_desc' => $this->input->post('role_desc')
		);

		$response = array();
		if($this->db->insert('roles', $data) && $this->db->insert_id()) {
			$record = $this->db->insert_id();
			$response["status"]			= "success";
			$response["message"]		= "Roles Added Successfully";
			$response["insertedId"]		= $record;
		} else {
			$response["status"]			= "error";
			$response["message"]		= "Error while adding Role. Please try after some time";
		}
		print(json_encode($response));
	}

	public function editForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$no_permission_options = array(
				'page_disp_string' => "edit role"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('security/model_roles');

		$record = $this->input->post('role_sno');

		$roles = $this->model_roles->getRolesList($record);

		$params = array(
			'function'=>"edit",
			'record'=>$record,
			'roles'=>$roles
		);
		
		echo $this->load->view("security/roles/inputForm", $params, true);
	}

	public function update() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

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
		$this->load->model('security/model_roles');

		$record = $this->input->post('role_sno');
		$role_id = $this->input->post('role_id');
		$role_name = $this->input->post('role_name');
		$role_desc = $this->input->post('role_desc');

		$data = array(
		   'role_id' => $role_id,
		   'role_name' => $role_name,
		   'role_desc' => $role_desc
		);

		$this->db->where('sno', $record);
		
		$response = array();

		if($this->db->update('roles', $data)) {
			$response["status"]			= "success";
			$response["message"]		= "Role Updated Successfully";
			$response["updatedId"]		= $record;
		} else {
			$response["status"]			= "error";
			$response["message"] 		= "Error while updating the records";
		}
		print_r(json_encode($response));
	}

	public function deleteRecord() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

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

		$this->load->model('security/model_roles');

		$record = $this->input->post('role_sno');

		$this->db->where('sno', $record);
		
		$response = array();

		if($this->db->delete('roles')) {
			$response["status"]			= "success";
			$response["message"]		= "Role Deleted Successfully";
		} else {
			$response["status"]			= "error";
			$response["message"] 		= "Error while deleting the records";
		}
		print_r(json_encode($response));
	}

	public function viewOne() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}
		
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$no_permission_options = array(
				'page_disp_string' => "role details"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('security/model_roles');

		$record = $this->input->post('role_sno');
		$roles = $this->model_roles->getRolesList($record);

		$params = array(
			'function'=>"view",
			'record'=>$record,
			'roles'=>$roles
		);
		
		echo $this->load->view("security/roles/viewOne", $params, true);
	}
}