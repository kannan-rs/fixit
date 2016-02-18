<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Functions extends CI_Controller {

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

		if($role_disp_name != ROLE_ADMIN ) {
			$no_permission_options = array(
				'page_disp_string' => "function list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('security/model_functions');
		$functions = $this->model_functions->getFunctionsList();

		$params = array(
			'function'=>"view",
			'record'=>"all",
			'functions'=>$functions
		);
		
		echo $this->load->view("security/functions/viewAll", $params, true);
	}
	
	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != ROLE_ADMIN ) {
			$no_permission_options = array(
				'page_disp_string' => "create function"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$params = array(
			'function'=>"createfunctionform",
			'record'=>""
		);

		echo $this->load->view("security/functions/inputForm", $params, true);
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

		if($role_disp_name != ROLE_ADMIN ) {
			$response["message"] 			= "No permission to execute this operation";
			print_r(json_encode($response));
			return false;
		}

		$this->load->model('security/model_functions');
		$data = array(
		   //'fn_id' =>  $this->input->post('function_id'),
		   'fn_name' => $this->input->post('function_name'),
		   'fn_descr' => $this->input->post('function_desc')
		);

		$response = array(
			'status' => 'error'
		);
		if($this->db->insert('functions', $data) && $this->db->insert_id()) {
			$record = $this->db->insert_id();

			$response["status"] 	= "success";
			$response["message"] 	= "Function Added Successfully";
			$response["insertedId"]	= $record;
		} else {
			$response["status"] 	= "error";
			$response["message"] 	= "Error while Adding Function";
		}
		print_r(json_encode($response));
	}

	public function editForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != ROLE_ADMIN ) {
			$no_permission_options = array(
				'page_disp_string' => "edit function"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('security/model_functions');

		$record = $this->input->post('function_sno');

		$functions = $this->model_functions->getFunctionsList($record);

		$params = array(
			'function'=>"edit",
			'record'=>$record,
			'functions'=>$functions
		);
		
		echo $this->load->view("security/functions/inputForm", $params, true);
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

		if($role_disp_name != ROLE_ADMIN ) {
			$response["message"] 			= "No permission to execute this operation";
			print_r(json_encode($response));
			return false;
		}

		$this->load->model('security/model_functions');
		$record = $this->input->post('function_sno');
		$fn_id = $this->input->post('function_id');
		$fn_name = $this->input->post('function_name');
		$fn_desc = $this->input->post('function_desc');

		$data = array(
		   'fn_id' => $fn_id,
		   'fn_name' => $fn_name,
		   'fn_descr' => $fn_desc
		);

		$this->db->where('sno', $record);
		
		$response = array(
			'status' => 'error'
		);

		if($this->db->update('functions', $data)) {
			$response["status"]		= "success";
			$response["message"] 	= "Function updated successfully";
			$response["updatedId"]	= $record;
		} else {
			$response["message"] = "Error while updating the records";
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

		if($role_disp_name != ROLE_ADMIN ) {
			$response["message"] 			= "No permission to execute this operation";
			print_r(json_encode($response));
			return false;
		}
		
		$this->load->model('security/model_functions');

		$record = $this->input->post('function_sno');

		$this->db->where('sno', $record);
		
		$response = array(
			'status' => 'error'
		);

		if($this->db->delete('functions')) {
			$response["status"]		= "success";
			$response["message"]	= "Function Deleted Successfully";		
		} else {
			$response["message"] = "Error while deleting the records";
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

		if($role_disp_name != ROLE_ADMIN ) {
			$no_permission_options = array(
				'page_disp_string' => "function details"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}
		$this->load->model('security/model_functions');

		$record = $this->input->post('function_sno');
		$functions = $this->model_functions->getFunctionsList($record);

		$params = array(
			'function'=>"view",
			'record'=>$record,
			'functions'=>$functions
		);
		
		echo $this->load->view("security/functions/viewOne", $params, true);
	}
}