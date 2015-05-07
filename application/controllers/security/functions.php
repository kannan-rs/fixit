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
		$this->load->model('security/model_functions');
		$functions = $this->model_functions->get_functions_list();

		$params = array(
			'function'=>"view",
			'record'=>"all",
			'functions'=>$functions
		);
		
		echo $this->load->view("security/functions/viewAll", $params, true);
	}
	
	public function createForm() {
		$params = array(
			'function'=>"createfunctionform",
			'record'=>""
		);

		echo $this->load->view("security/functions/createForm", $params, true);
	}

	public function add() {
		$this->load->model('security/model_functions');
		$data = array(
		   'fn_id' =>  $this->input->post('function_id'),
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
		$this->load->model('security/model_functions');

		$record = $this->input->post('function_sno');

		$functions = $this->model_functions->get_functions_list($record);

		$params = array(
			'function'=>"edit",
			'record'=>$record,
			'functions'=>$functions
		);
		
		echo $this->load->view("security/functions/editForm", $params, true);
	}

	public function update() {
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

	public function delete() {
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
		$this->load->model('security/model_functions');

		$record = $this->input->post('function_sno');
		$functions = $this->model_functions->get_functions_list($record);

		$params = array(
			'function'=>"view",
			'record'=>$record,
			'functions'=>$functions
		);
		
		echo $this->load->view("security/functions/viewOne", $params, true);
	}
}