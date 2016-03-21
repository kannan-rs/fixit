<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class formUtils extends CI_Controller {

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

	public function getCountryStatus() {
		$this->load->model('utils/model_form_utils');

		$state = $this->model_form_utils->getCountryStatus();

		$response["status"] = "error";
		if($state) {
			$response["status"] = "success";
			$response["state"] = $state;
		} else {
			$response["message"] = "Error while fetching state details";
		}
		print_r(json_encode($response));
	}

	public function getFromUsersList() {
		$emailId 				= $this->input->post('emailId');
		$role_disp_name			= $this->input->post('role_disp_name');
		$assignment				= $this->input->post('assignment');
		//$logged_in_user 		= $this->input->post('logged_in_user');
		$contractor_user_list 	= $this->input->post('contractor_user_list');

		$company_id = "";

		$logged_in_role_disp_name = $this->session->userdata('logged_in_role_disp_name');

		
		if( $logged_in_role_disp_name == ROLE_SERVICE_PROVIDER_ADMIN && !empty($contractor_user_list) ) {
			// Get Contractor by Contractor admin
			$logged_in_user_id = $this->session->userdata('logged_in_user_id');
			
			$this->load->model("service_providers/model_service_providers");
			$company_id = $this->model_service_providers->get_contractor_company_id_by_user_id($logged_in_user_id);

			//$this->load->model("security/model_users");

			//$response = $this->model_users->getUsersList();
		}

		if(!isset($response)) {
			$requestData = array(
				"emailId"				=> $emailId,
				"role_disp_name"		=> $role_disp_name,
				"assignment"			=> $assignment,
				"contractor_user_list" 	=> $contractor_user_list,
				"company_id" 			=> $company_id
			);

			$this->load->model('utils/model_form_utils');

			$response = $this->model_form_utils->getFromUsersList( $requestData );
		}

		print_r(json_encode($response));
	}

	public function getAdjusterList() {
		$this->load->model('utils/model_form_utils');

		$adjuster = $this->model_form_utils->getAdjusterList();

		$response["status"] = "error";
		if($adjuster) {
			$response["status"] = "success";
			$response["adjuster"] = $adjuster;
		} else {
			$response["message"] = "Error while fetching adjuster details";
		}
		print_r(json_encode($response));
	}

	public function getMatchCityList() {
		$this->load->model('utils/model_form_utils');

		$cityStr 	= $this->input->post('cityStr');

		if(isset($cityStr) && $cityStr != "") {
			$state = $this->model_form_utils->getMatchCityList( $cityStr );
		} else {
			$state = array();
		}

		$response["status"] = "error";
		if(isset($state)) {
			$response["status"] = "success";
			$response["cityList"] = $state;
		} else {
			$response["message"] = "Error while fetching state details";
		}
		print_r(json_encode($response));
	}

	public function getPostalDetailsByCity() {
		$this->load->model('utils/model_form_utils');

		$cityStr 	= $this->input->post('cityStr');

		if(isset($cityStr) && $cityStr != "") {
			$state = $this->model_form_utils->getPostalDetailsByCity( $cityStr );
		} else {
			$state = array();
		}

		$response["status"] = "error";
		if(isset($state)) {
			$response["status"] = "success";
			$response["postalDetails"] = $state;
		} else {
			$response["message"] = "Error while fetching state details";
		}
		print_r(json_encode($response));
	}
}