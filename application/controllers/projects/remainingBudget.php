<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class remainingbudget extends CI_Controller {

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

	public function getListWithForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Budget > Permissions for logged in User by role_id
		$budgetPermission 		= $this->permissions_lib->getPermissions(FUNCTION_BUDGET);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $budgetPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "budget list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$projectId 		= $this->input->post("projectId");
		$openAs 		= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 		= $this->input->post('popupType') ? $this->input->post('popupType') : "";
		$budgetId 		= $this->input->post('budgetId') ? $this->input->post('budgetId') : "";
		$updateBudget 	= "";

		$this->load->model('projects/model_remainingbudget');
		$rbResponse = $this->model_remainingbudget->getList( $projectId );

		if(in_array(OPERATION_UPDATE, $budgetPermission['operation']) && $budgetId != "") {
			$updateBudget = $this->model_remainingbudget->getBudgetById( $budgetId )["paidFromBudget"];
		} else if( $budgetId != "" ) {
			$no_permission_options = array(
				'page_disp_string' => "update Budget"
			);
			$inputForm = $this->load->view("pages/no_permission", $no_permission_options, true);
		}

		if(in_array(OPERATION_CREATE, $budgetPermission['operation']) || in_array(OPERATION_UPDATE, $budgetPermission['operation'])) {
			$inputFormParams = array(
				'openAs' 			=> $openAs,
				'popupType' 		=> $popupType,
				'budgetPermission'	=> $budgetPermission
			);

			if($budgetId != "" && $updateBudget) {
				$inputFormParams['updateBudget'] = $updateBudget;
			}
			if(!isset($inputForm)) {
				$inputForm = $this->load->view("projects/remainingbudget/createForm", $inputFormParams, true);
			}
		} else {
			$no_permission_options = array(
				'page_disp_string' => "create/update budget"
			);
			$inputForm = $this->load->view("pages/no_permission", $no_permission_options, true);
		}

		$listParams = array(
			'budgetList' 		=> $rbResponse['paidFromBudget'],
			'budgetPermission'	=> $budgetPermission
		);

		$listData = $this->load->view("projects/remainingbudget/budgetList", $listParams, true);

		echo $listData.$inputForm;
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_BUDGET, OPERATION_CREATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('projects/model_remainingbudget');
		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');
		$this->load->model('mail/model_mail');

		$projectId = $this->input->post('projectId');

		$data = array(
			'project_id' 	=> $projectId,
			'date' 			=> $this->input->post('date'),
			'descr' 		=> $this->input->post('descr'),
			'amount' 		=> $this->input->post('amount'),
			'created_by'	=> $this->session->userdata('logged_in_user_id'),
			'updated_by'	=> $this->session->userdata('logged_in_user_id'),
			'created_on'	=> date("Y-m-d H:i:s"),
			'updated_on'	=> date("Y-m-d H:i:s")
		);

		$response = $this->model_remainingbudget->insert($data);
		print_r(json_encode($response));
	}

	public function update() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_BUDGET, OPERATION_UPDATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('projects/model_remainingbudget');

		$budgetId 			= $this->input->post('budgetId');

		$data = array(
			'project_id' 	=> $this->input->post('projectId'),
			'date' 			=> $this->input->post('date'),
			'descr' 		=> $this->input->post('descr'),
			'amount' 		=> $this->input->post('amount'),
			'updated_by'	=> $this->session->userdata('logged_in_user_id'),
			'updated_on'	=> date("Y-m-d H:i:s")
			
		);

		$update_rd = $this->model_remainingbudget->update($data, $budgetId);

		print_r(json_encode($update_rd));
	}

	public function deleteRecord() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}
		
		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_BUDGET, OPERATION_DELETE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('projects/model_remainingbudget');

		$remainingbudgetId = $this->input->post('remainingbudgetId');
		$delete_rB = $this->model_remainingbudget->deleteRecord($remainingbudgetId);

		print_r(json_encode($delete_rB));
	}
}