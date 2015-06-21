<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Operations extends CI_Controller {

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
		$this->load->model('security/model_operations');
		$operations = $this->model_operations->getOperationsList();

		$params = array(
			'function'=>"view",
			'record'=>"all",
			'operations'=>$operations
		);
		
		echo $this->load->view("security/operations/viewAll", $params, true);
	}
	
	public function createForm() {
		$params = array(
			'function'=>"createoperationform",
			'record'=>""
		);

		echo $this->load->view("security/operations/createForm", $params, true);
	}

	public function add() {
		$this->load->model('security/model_operations');

		$data = array(
		   'ope_id' =>  $this->input->post('ope_id'),
		   'ope_name' => $this->input->post('ope_name'),
		   'ope_desc' => $this->input->post('ope_desc')
		);

		$response = array();
		if($this->db->insert('operations', $data) && $this->db->insert_id()) {
			$record = $this->db->insert_id();

			$response["status"]			= "success";
			$response["message"]		= "Operation Added Successfully";
			$response["insertedId"]		= $record;
		} else {
			$response["status"]			= "error";
			$response["message"]		= "Error while adding Operation. Please try after dome time.";
		}
		print_r(json_encode($response));
	}

	public function editForm() {
		$this->load->model('security/model_operations');

		$record = $this->input->post('ope_sno');

		$operations = $this->model_operations->getOperationsList($record);

		$params = array(
			'function'=>"edit",
			'record'=>$record,
			'operations'=>$operations
		);
		
		echo $this->load->view("security/operations/editForm", $params, true);
	}

	public function update() {
		$this->load->model('security/model_operations');

		$record = $this->input->post('ope_sno');
		$ope_id = $this->input->post('ope_id');
		$ope_name = $this->input->post('ope_name');
		$ope_desc = $this->input->post('ope_desc');

		$data = array(
		   'ope_id' => $ope_id,
		   'ope_name' => $ope_name,
		   'ope_desc' => $ope_desc
		);

		$this->db->where('sno', $record);
		
		$response = array();

		if($this->db->update('operations', $data)) {
			$response["status"]		= "success";
			$response["message"]	= "Operation Updated Successfully";
			$response["updatedId"]	= $record;
		} else {
			$response["status"]			= "error";
			$response["message"] = "Error while updating the records";
		}

		print_r(json_encode($response));
	}

	public function deleteRecord() {
		$this->load->model('security/model_operations');

		$record = $this->input->post('ope_sno');

		$this->db->where('sno', $record);
		
		$response = array();

		if($this->db->delete('operations')) {
			$response["status"]			= "success";
			$response["message"]		= "Operation Deleted Successfully";
		} else {
			$response["status"]			= "error";
			$response["message"] = "Error while deleting the records";
		}	
		print_r(json_encode($response));
	}

	public function viewOne() {
		$this->load->model('security/model_operations');

		$record = $this->input->post('ope_id');
		$operations = $this->model_operations->getOperationsList($record);

		$params = array(
			'function'=>"view",
			'record'=>$record,
			'operations'=>$operations
		);		
		echo $this->load->view("security/operations/viewOne", $params, true);
	}
}