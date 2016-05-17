<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class formUtils extends CI_Controller {

	private function sp_id_to_details_mapping( $sp_list = "" ) {
		$id_mapped_list = array();
		if( !empty($sp_list) ) {
			for( $i = 0; $i < count($sp_list); $i++ ) {
				$id_mapped_list[$sp_list[$i]->id] = $sp_list[$i];
			}
		}
		return $id_mapped_list;
	}

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
		
		$contractor_user_list 	= $this->input->post('contractor_user_list');

		$company_id = "";
		$sp_id_to_details_list = "";

		$logged_in_role_disp_name = $this->session->userdata('logged_in_role_disp_name');

		
		if( $logged_in_role_disp_name == ROLE_SERVICE_PROVIDER_ADMIN && !empty($contractor_user_list) ) {
			// Get Contractor by Contractor admin
			$logged_in_user_id = $this->session->userdata('logged_in_user_id');
			
			$this->load->model("service_providers/model_service_providers");
			$company_id = $this->model_service_providers->get_contractor_company_id_by_user_id($logged_in_user_id);
		} 
		else if( !empty($contractor_user_list)) {
			$project_id 			= $this->input->post('project_id');
			if($project_id) {
				$this->load->model("projects/model_projects");

				$projectParams = array(
					"role_disp_name" 	=> $logged_in_role_disp_name,
					"projectId"			=> $project_id
				);
				$projectsList = $this->model_projects->getProjectsList($projectParams);
				if(count($projectsList)) {
					$company_id = $projectsList[0]->contractor_id;
				}
			}
		}

		if( !empty($contractor_user_list) && !empty($company_id)) {
			$this->load->model("service_providers/model_service_providers");
			$sp_company_name_response = $this->model_service_providers->get_service_provider_list($company_id);
			if( $sp_company_name_response && $sp_company_name_response['status'] == "success" ) {
				$sp_id_to_details_list = $this->sp_id_to_details_mapping( $sp_company_name_response['contractors'] );
			}
			else {
				print_r(json_encode($sp_company_name_response));
				return false;
			}
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

			if( !empty($contractor_user_list) && !empty($sp_id_to_details_list)) {
				for( $i = 0; $i < count($response['customer']); $i++ ) {
					if( $response['customer'][$i]->belongs_to_id > 0 && $sp_id_to_details_list[$response['customer'][$i]->belongs_to_id] ) {
						$response['customer'][$i]->belongs_to_name = $sp_id_to_details_list[$response['customer'][$i]->belongs_to_id]->company;
					}
					else {
						$response['customer'][$i]->belongs_to_id = "--";
					}
				}
			}
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