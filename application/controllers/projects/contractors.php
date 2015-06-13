<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contractors extends CI_Controller {

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

	public function getList() {
		$this->load->model('projects/model_contractors');
		$contractors = $this->model_contractors->getContractorsList();
		$response = array();
		if($contractors) {
			$response['status'] 		= 'success';
			$response['contractors']	= $contractors;
		} else {
			$response['status'] 		= 'error';
			$response["message"] 		= "Error retriving contractors details";
		}
		print_r(json_encode($response));
	}

	public function viewAll() {
		$this->load->model('projects/model_contractors');

		
		$contractors = $this->model_contractors->getContractorsList();

		$params = array(
			'contractors'=>$contractors
		);
		
		echo $this->load->view("projects/contractors/viewAll", $params, true);
	}
	
	public function createForm() {
		$this->load->model('security/model_users');

		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";
		
		$params = array(
			'users' 		=> $this->model_users->getUsersList(),
			'userType' 		=> $this->session->userdata('account_type'),
			'openAs' 		=> $openAs,
			'popupType' 	=> $popupType
		);

		echo $this->load->view("projects/contractors/createForm", $params, true);
	}

	public function add() {
		$this->load->model('projects/model_contractors');

		$data = array(
			'name' 				=> $this->input->post('name'),
			'company' 			=> $this->input->post('company'),
			'type' 				=> $this->input->post('type'),
			'license' 			=> $this->input->post('license'),
			'bbb' 				=> $this->input->post('bbb'),
			'status' 			=> $this->input->post('status'),
			'address1' 			=> $this->input->post('addressLine1'),
			'address2'			=> $this->input->post('addressLine2'),
			'address3' 			=> $this->input->post('addressLine3'),
			'address4' 			=> $this->input->post('addressLine4'),
			'city' 				=> $this->input->post('city'),
			'state' 			=> $this->input->post('state'),
			'country' 			=> $this->input->post('country'),
			'pin_code' 			=> $this->input->post('pinCode'),
			'office_email' 		=> $this->input->post('emailId'),
			'office_ph' 		=> $this->input->post('contactPhoneNumber'),
			'mobile_ph' 		=> $this->input->post('mobileNumber'),
			'prefer' 			=> $this->input->post('prefContact'),
			'website_url' 		=> $this->input->post('websiteURL'),
			'created_by'		=> $this->session->userdata('user_id'),
			'updated_by'		=> $this->session->userdata('user_id'),
			'created_on'		=> date("Y-m-d H:i:s"),
			'updated_on'		=> date("Y-m-d H:i:s")
		);

		$insert_contractor = $this->model_contractors->insert($data);
		print_r(json_encode($insert_contractor));
	}

	public function viewOne() {
		$this->load->model('projects/model_contractors');
		$this->load->model('security/model_users');

		$contractorId 		= $this->input->post('contractorId');
		$openAs		 		= $this->input->post('openAs');
		$contractors 		= $this->model_contractors->getContractorsList($contractorId);
		
		for($i=0; $i < count($contractors); $i++) {
			$contractors[$i]->created_by_name = $this->model_users->getUsersList($contractors[$i]->created_by)[0]->user_name;
			$contractors[$i]->updated_by_name = $this->model_users->getUsersList($contractors[$i]->updated_by)[0]->user_name;
		}

		$params = array(
			'contractors'	=> $contractors,
			'userType' 		=> $this->session->userdata('account_type'),
			'contractorId' 	=> $contractorId,
			'openAs' 		=> $openAs
		);
		
		echo $this->load->view("projects/contractors/viewOne", $params, true);
	}

	public function editForm() {
		$this->load->model('projects/model_contractors');

		$contractorId = $this->input->post('contractorId');

		$contractors = $this->model_contractors->getContractorsList($contractorId);

		$params = array(
			'contractors'=>$contractors,
			'userType' 		=> $this->session->userdata('account_type')
		);
		
		echo $this->load->view("projects/contractors/editForm", $params, true);
	}

	public function update() {
		$this->load->model('projects/model_contractors');

		$contractorId 			= $this->input->post('contractorId');

		$data = array(
			'name' 				=> $this->input->post('name'),
			'company' 			=> $this->input->post('company'),
			'type' 				=> $this->input->post('type'),
			'license' 			=> $this->input->post('license'),
			'bbb' 				=> $this->input->post('bbb'),
			'status' 			=> $this->input->post('status'),
			'address1' 			=> $this->input->post('addressLine1'),
			'address2'			=> $this->input->post('addressLine2'),
			'address3' 			=> $this->input->post('addressLine3'),
			'address4' 			=> $this->input->post('addressLine4'),
			'city' 				=> $this->input->post('city'),
			'state' 			=> $this->input->post('state'),
			'country' 			=> $this->input->post('country'),
			'pin_code' 			=> $this->input->post('pinCode'),
			'office_email' 		=> $this->input->post('emailId'),
			'office_ph' 		=> $this->input->post('contactPhoneNumber'),
			'mobile_ph' 		=> $this->input->post('mobileNumber'),
			'prefer' 			=> $this->input->post('prefContact'),
			'website_url' 		=> $this->input->post('websiteURL'),
			'updated_by'		=> $this->session->userdata('user_id'),
			'updated_on'		=> date("Y-m-d H:i:s")
		);

		$update_contractor = $this->model_contractors->update($data, $contractorId);

		print_r(json_encode($update_contractor));
	}

	public function delete() {
		$this->load->model('projects/model_contractors');

		$contractorId = $this->input->post('contractorId');
		$delete_contractor = $this->model_contractors->delete($contractorId);

		print_r(json_encode($delete_contractor));	
	}
}