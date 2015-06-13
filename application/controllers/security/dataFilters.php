<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DataFilters extends CI_Controller {

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
		$this->load->model('security/model_dataFilters');
		$dataFilters = $this->model_dataFilters->getDataFiltersList();

		$params = array(
			'function'=>"view",
			'record'=>"all",
			'dataFilters'=>$dataFilters
		);
		
		echo $this->load->view("security/dataFilters/viewAll", $params, true);
	}
	
	public function createForm() {
		$params = array(
			'function'=>"createdataFilterform",
			'record'=>""
		);

		echo $this->load->view("security/dataFilters/createForm", $params, true);
	}

	public function add() {
		$this->load->model('security/model_dataFilters');
		$data = array(
		   'data_filter_id' =>  $this->input->post('dataFilter_id'),
		   'data_filter_name' => $this->input->post('dataFilter_name'),
		   'data_filter_descr' => $this->input->post('dataFilter_desc')
		);

		$response = array();

		if($this->db->insert('data_filters', $data) && $this->db->insert_id()) {
			$record = $this->db->insert_id();
			$response["status"] 	= "success";
			$response["message"] 	= "Data Filter Added Successfully";
			$response["insertedId"]	= $record;
		} else {
			$response["status"] 	= "error";
			$response["message"] 	= "Error while Adding Function";
		}
		print_r(json_encode($response));
	}

	public function editForm() {
		$this->load->model('security/model_dataFilters');

		$record = $this->input->post('dataFilter_sno');

		$dataFilters = $this->model_dataFilters->getDataFiltersList($record);

		$params = array(
			'function'=>"edit",
			'record'=>$record,
			'dataFilters'=>$dataFilters
		);
		
		echo $this->load->view("security/dataFilters/editForm", $params, true);
	}

	public function update() {
		$this->load->model('security/model_dataFilters');
		$record = $this->input->post('dataFilter_sno');
		$data_filter_id = $this->input->post('dataFilter_id');
		$data_filter_name = $this->input->post('dataFilter_name');
		$data_filter_desc = $this->input->post('dataFilter_desc');

		$data = array(
		   'data_filter_id' => $data_filter_id,
		   'data_filter_name' => $data_filter_name,
		   'data_filter_descr' => $data_filter_desc
		);

		$this->db->where('sno', $record);
		
		$response = array();

		if($this->db->update('data_filters', $data)) {
			$response["status"]		= "success";
			$response["message"] 	= "Data Filter updated successfully";
			$response["updatedId"]	= $record;
		} else {
			$response["status"]		= "error";
			$response["message"] 	= "Error while updating the records";	
		}
		print_r(json_encode($response));
	}

	public function delete() {
		$this->load->model('security/model_dataFilters');

		$record = $this->input->post('dataFilter_sno');

		$this->db->where('sno', $record);
		
		$response = array();

		if($this->db->delete('data_filters')) {
			$response["status"]		= "success";
			$response["message"]	= "Data Filter Deleted Successfully";
		} else {
			$response["status"]		= "error";
			$response["message"] = "Error while deleting the records";
		}
		print_r(json_encode($response));
	}

	public function viewOne() {
		$this->load->model('security/model_dataFilters');

		$record = $this->input->post('dataFilter_sno');
		$dataFilters = $this->model_dataFilters->getDataFiltersList($record);

		$params = array(
			'function'=>"view",
			'record'=>$record,
			'dataFilters'=>$dataFilters
		);
		
		echo $this->load->view("security/dataFilters/viewOne", $params, true);
	}
}