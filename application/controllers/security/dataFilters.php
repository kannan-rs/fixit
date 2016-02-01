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
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$no_permission_options = array(
				'page_disp_string' => "data filter list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}
		$this->load->model('security/model_datafilters');
		$dataFilters = $this->model_datafilters->getDataFiltersList();

		$params = array(
			'function'=>"view",
			'record'=>"all",
			'dataFilters'=>$dataFilters
		);
		
		echo $this->load->view("security/dataFilters/viewAll", $params, true);
	}
	
	public function createForm() {
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$no_permission_options = array(
				'page_disp_string' => "create data filter"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}
		$params = array(
			'function'=>"createdataFilterform",
			'record'=>""
		);

		echo $this->load->view("security/dataFilters/inputForm", $params, true);
	}

	public function add() {
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

		$this->load->model('security/model_datafilters');
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
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$no_permission_options = array(
				'page_disp_string' => "edit data filter"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}
		$this->load->model('security/model_datafilters');

		$record = $this->input->post('dataFilter_sno');

		$dataFilters = $this->model_datafilters->getDataFiltersList($record);

		$params = array(
			'function'=>"edit",
			'record'=>$record,
			'dataFilters'=>$dataFilters
		);
		
		echo $this->load->view("security/dataFilters/inputForm", $params, true);
	}

	public function update() {
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

		$this->load->model('security/model_datafilters');
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
		
		$this->load->model('security/model_datafilters');

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
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		if($role_disp_name != "admin") {
			$no_permission_options = array(
				'page_disp_string' => "data filter details"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}
		$this->load->model('security/model_datafilters');

		$record = $this->input->post('dataFilter_sno');
		$dataFilters = $this->model_datafilters->getDataFiltersList($record);

		$params = array(
			'function'=>"view",
			'record'=>$record,
			'dataFilters'=>$dataFilters
		);
		
		echo $this->load->view("security/dataFilters/viewOne", $params, true);
	}
}