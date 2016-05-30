<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends CI_Controller {

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

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $projectPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Project List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Including Required Modules */
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_issues');
		$this->load->model('security/model_users');

		//Issues > Permissions for logged in User by role_id
		$issuesPermission = $this->permissions_lib->getPermissions(FUNCTION_ISSUES);
		//print_r($issuesPermission);

		$projectList 	= "";

		$user_id 						= $this->session->userdata('logged_in_user_id'); /* Get user ID for logged in User from session */
		$email 							= $this->session->userdata('logged_in_email'); /* Get Email ID for logged in User from session */
		$logged_in_role_disp_name 		= $this->session->userdata('logged_in_role_disp_name');
		$logged_in_role_id 				= $this->session->userdata('logged_in_role_id');

		/* Project Params to get the list of project with permissions */
		$projectParams = array(
			'role_disp_name' 		=> $logged_in_role_disp_name,
			'role_id' 				=> $logged_in_role_id,
			'user_details_id' 		=> $this->model_users->getUserDetailsSnoViaEmail($email),
			'user_id' 				=> $user_id,
			'email' 				=> $email,
			'projectPermission' 	=> $projectPermission
		);

		$projectListArr = array();
		
		/* If logged in User dont have 'all' permission in data filter, then get the project ID's list that user has access */
		/* Get details from Projects Table */
		if( !in_array('all', $projectPermission['data_filter']) ) {
			$projectList = $this->model_projects->getProjectIds($projectParams);
			for($i = 0; $i < count($projectList); $i++) {
				array_push($projectListArr, $projectList[$i]->proj_id);
			}
		}

		/*
			Get Project ID's from Project_Owners table
		*/
		if( $logged_in_role_disp_name == ROLE_SERVICE_PROVIDER_USER ) {
			$projectList = $this->model_projects->get_project_ids_by_sp_user($projectParams);
			for($i = 0; $i < count($projectList); $i++) {
				if(! in_array($projectList[$i]->project_id, $projectListArr)) {
					array_push($projectListArr, $projectList[$i]->project_id);
				}
			}
		}


		/*echo "projectListArr ->";
		print_r($projectListArr);*/
		/* Set the list of project ID's to projectParams, that logged in user has access to */
		$projectParams["projectId"] = $projectListArr;
		//print_r($projectParams);
		
		/* Get the list of Projects that logged in user has access to, from Database > projects > table to display */
		if(in_array('all', $projectPermission['data_filter']) || !empty($projectParams["projectId"])) {
			$projects = $this->model_projects->getProjectsList( $projectParams );
		}

		if(isset($projects)) {
			for($i = 0; $i < count($projects); $i++) {
				$start_date = "";
				$end_date	= "";
				$percentage = "";

				$ed_query = "select 
									AVG(task_percent_complete) as percentage, 
									DATE_FORMAT( MAX(task_end_date), '%m/%d/%Y' ) as end_date, 
									DATE_FORMAT( MIN(task_start_date),  '%m/%d/%Y') as start_date 
							from project_details where project_id = '".$projects[$i]->proj_id."' and is_deleted = 0";

				$consolidate_data_query = $this->db->query($ed_query);
				$consolidate_data_result = $consolidate_data_query->result();
				$consolidate_data = $consolidate_data_result[0];

				$projects[$i]->percentage = ($consolidate_data->percentage > 0  ? round($consolidate_data->percentage,1) : 0);

				$start_date 	= $consolidate_data->start_date != "" ? ($projects[$i]->start_date != "" && $projects[$i]->start_date < $consolidate_data->start_date ? $projects[$i]->start_date : $consolidate_data->start_date) : ($projects[$i]->start_date != "" ? $projects[$i]->start_date : "-NA-");
				$end_date 		= $consolidate_data->end_date != "" ? ($projects[$i]->end_date != "" && $projects[$i]->end_date > $consolidate_data->end_date ? $projects[$i]->end_date : $consolidate_data->end_date) : ($projects[$i]->end_date != "" ? $projects[$i]->end_date : "-NA-");

				$projects[$i]->start_date 	= $start_date;
				$projects[$i]->end_date 		= $end_date;

				if(in_array(OPERATION_VIEW, $issuesPermission['operation'])) {
					$issuesResponse = $this->model_issues->getIssuesList(array('records' => '', 'projectId' => $projects[$i]->proj_id, 'status' => 'open'));
					$issueCount 	= $issuesResponse && $issuesResponse["issues"] ? count($issuesResponse["issues"]) : 0;

					$projects[$i]->issueCount = $issueCount;
				}

			}
		}

		$params = array(
			'projects' 			=> isset($projects) ? $projects : null,
			'role_id' 			=> $this->session->userdata('logged_in_role_id'),
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission' => $projectPermission,
			'issuesPermission'	=> $issuesPermission
		);
		
		echo $this->load->view("projects/projects/viewAll", $params, true);
	}

	public function getAssignees() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');

		$customerId = "";
		$adjusterId = "";
		$contractorId = "";

		$assigneeDetails  	= array(
			"status" 	=> "error"
		);

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$projectId 			= $this->input->post('projectId');
		
		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=>$this->session->userdata('logged_in_role_disp_name'),
			'projectPermission'	=> $projectPermission
		);
		$projects = $this->model_projects->getProjectsList( $projectParams );

		if(count($projects)) {
			$customerId = $projects[0]->customer_id;
			$adjusterId = $projects[0]->adjuster_id;
			$contractorId = $projects[0]->contractor_id;
		}

		//echo "customerId--".$customerId."<br/>";
		if(!empty($customerId)) {
			$assigneeDetails["status"] = "success";
			$userDetails = $this->model_users->getUsersList( $customerId );

			if(count($userDetails)) {
				$customerDetails = $this->model_users->getUserDetailsByEmail( $userDetails[0]->user_name );
				if(count($customerDetails)) {
					$assigneeDetails["customerDetails"] = $customerDetails;
					$assigneeDetails["customerDetails"]["user_sno"] = $userDetails[0]->sno;
					$assigneeDetails["customerDetails"]["role_id"] = $userDetails[0]->role_id;
					$assigneeDetails["customerDetails"]["account_status"] = $userDetails[0]->status;
				}
			}
		}

		/*if(!empty($adjusterId)) {
			$assigneeDetails["status"] = "success";
			$adjusterIdArr = explode(",", $adjusterId);
			$partnersResponse = $this->model_partners->getPartnersList($adjusterIdArr);
			 $assigneeDetails["adjusterDetails"] = $partnersResponse["partners"];
		}*/

		//echo "ContractorId--".$contractorId;

		if(!empty($contractorId)) {
			$assigneeDetails["status"] = "success";
			$contractorIdArr = explode(",", $contractorId);
			$contractorsResponse = $this->model_service_providers->get_service_provider_list($contractorIdArr, "", "", 1);
			$assigneeDetails["contractorDetails"] = $contractorsResponse["contractors"];
		}

		//print_r($assigneeDetails);

		print_r(json_encode($assigneeDetails));
	}
	
	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$projectPermission 		= $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $projectPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Creating Projects"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('security/model_users');

		$addressFile = $this->form_lib->getAddressFile(array("requestFrom" => "input", "view" => "create_project_form"));

		//Service Provider > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER);
		//Adjuster > Permissions for logged in User by role_id
		$adjusterPermission 	= $this->permissions_lib->getPermissions(FUNCTION_PARTNER);
		//Customer > Permissions for logged in User by role_id
		$customerPermission 	= $this->permissions_lib->getPermissions(FUNCTION_CUSTOMER);
		//Budget > Permissions for logged in User by role_id
		$budgetPermission 		= $this->permissions_lib->getPermissions(FUNCTION_BUDGET);
		
		$params = array(
			'users' 				=> $this->model_users->getUsersList(),
			'addressFile' 			=> $addressFile,
			'userType' 				=> $this->session->userdata('logged_in_role_id'),
			'role_disp_name'		=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission'		=> $projectPermission,
			'contractorPermission'	=> $contractorPermission,
			'adjusterPermission'	=> $adjusterPermission,
			'budgetPermission'		=> $budgetPermission,
			'customerPermission'	=> $customerPermission
		);

		echo $this->load->view("projects/projects/inputForm", $params, true);
	}

	public function service_provider_choose_form() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CHOOSE, $contractorPermission['operation']) && !in_array(OPERATION_CREATE, $contractorPermission['operation']) && !in_array(OPERATION_UPDATE, $contractorPermission['operation']) ) {
			$no_permission_options = array(
				'page_disp_string' => "service provider selection"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$projectPermission 		= $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$record = $this->input->post('projectId');

		$this->load->model('projects/model_projects');

		$projectParams = array(
			'projectId' 		=> [$record],
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission' => $projectPermission
		);
		$projects = $this->model_projects->getProjectsList($projectParams);


		$params = array(
			'contractorPermission'	=> $contractorPermission,
			'projects'				=> $projects
		);

		echo $this->load->view("projects/projects/service_provider_choose_form", $params, true);
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_PROJECTS, OPERATION_CREATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');
		$this->load->model('mail/model_mail');

		$addressLine1 			= $this->input->post('addressLine1');
		$addressLine2 			= $this->input->post('addressLine2');
		$city 					= $this->input->post('city');
		$state 					= $this->input->post('state');
		$country 				= $this->input->post('country');
		$zipCode				= $this->input->post('zipCode');

		$contractorId 			= $this->input->post('contractor_id') ? $this->input->post('contractor_id') : null;
		$adjusterId 			= $this->input->post('adjuster_id') ? $this->input->post('adjuster_id') : null;
		$customerId 			= $this->input->post('customer_id') ? $this->input->post('customer_id') : null;

		$data = array(
			'project_name' 				=> $this->input->post('projectTitle'),
			'project_descr'				=> $this->input->post('description'),
			'associated_claim_num'		=> $this->input->post('associated_claim_num'),
			'project_type'				=> $this->input->post('project_type'),
			'start_date' 				=> $this->input->post('start_date'),
			'end_date' 					=> $this->input->post('end_date'),
			'project_status'			=> $this->input->post('project_status'),
			'project_budget'			=> $this->input->post('project_budget'),
			'property_owner_id'			=> $this->input->post('property_owner_id'),
			'contractor_id'				=> $contractorId,
			'adjuster_id'				=> $adjusterId,
			'broker_id'					=> $this->input->post('broker_id'),
			'banker_id'					=> $this->input->post('banker_id'),
			'customer_id'				=> $customerId,
			'paid_from_budget'			=> $this->input->post('paid_from_budget'),
			'remaining_budget'			=> $this->input->post('remaining_budget'),
			'deductible' 				=> $this->input->post('deductible'),
			'project_lender'			=> $this->input->post('project_lender'),
			'lend_amount'				=> $this->input->post('lend_amount'),
			'created_by'				=> $this->session->userdata('logged_in_user_id'),
			'updated_by'				=> $this->session->userdata('logged_in_user_id'),
			'created_on'				=> date("Y-m-d H:i:s"),
			'updated_on'				=> date("Y-m-d H:i:s"),
			'address1' 					=> $addressLine1,
			'address2' 					=> $addressLine2,
			'city' 						=> $city,
			'state' 					=> $state,
			'country' 					=> $country,
			'zip_code'					=> $zipCode
		);

		$response = $this->model_projects->insert($data);

		$customerData 		= null != $customerId ? $this->model_users->getUserDetailsBySno($customerId) : null;
		$contractorsData 	= null;
		$partnersData 		= null;

		//Service Provider Details
		if($contractorId != "") {
			$contractorIdArr = explode(",", $contractorId);
			$contractorsResponse = $this->model_service_providers->get_service_provider_list($contractorIdArr);
			 $contractorsData = $contractorsResponse["contractors"];
		}

		// Partners Name
		if($adjusterId != "") {
			$partnerIdArr = explode(",", $adjusterId);
			$partnersResponse = $this->model_partners->getPartnersList($partnerIdArr);
			 $partnersData = $partnersResponse["partners"];
		}

		$projectParamsFormMail = array(
			'response'			=> $response,
			'projectData'		=> $data,
			'customerData' 		=> $customerData,
			'contractorsData' 	=> $contractorsData,
			'partnersData' 		=> $partnersData,
			'mail_type' 		=> "create"
		);

		$mail_options = $this->model_mail->generateProjectMailOptions( $projectParamsFormMail );
		
		$response['mail_content'] = $mail_options;
		for($i = 0; $i < count($mail_options); $i++) {
			$response["mail_error"] = $this->model_mail->sendMail( $mail_options[$i] );
		}

		print_r(json_encode($response));
	}


	public function editForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);
		//print_r($projectPermission);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_UPDATE, $projectPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "update project"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');

		//Project > Permissions for logged in User by role_id
		$projectPermission 		= $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_UPDATE, $projectPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Update Project"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$record = $this->input->post('projectId');

		$projectParams = array(
			'projectId' 		=> [$record],
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission' => $projectPermission
		);
		$projects = $this->model_projects->getProjectsList($projectParams);

		$internalLinkParams = array(
			"internalLinkArr" 		=> ["tasks", "project notes", "documents"],
			"projectId" 			=> $record
		);

		$addressFile = $this->form_lib->getAddressFile(array("view" => "update_project_form", "requestFrom" => "input", "address_data" => $projects[0]));

		$contractorPermission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER);//Service Provider > Permissions for logged in User by role_id
		$adjusterPermission 	= $this->permissions_lib->getPermissions(FUNCTION_PARTNER);//Adjuster > Permissions for logged in User by role_id
		$customerPermission 	= $this->permissions_lib->getPermissions(FUNCTION_CUSTOMER);//Customer > Permissions for logged in User by role_id
		$budgetPermission 		= $this->permissions_lib->getPermissions(FUNCTION_BUDGET);//Budget > Permissions for logged in User by role_id

		$params = array(
			'projects' 				=>$projects,
			'users' 				=> $this->model_users->getUsersList(),
			'internalLink' 			=> $this->load->view("projects/internalLinks", $internalLinkParams, true),
			'userType' 				=> $this->session->userdata('logged_in_role_id'),
			'role_disp_name'		=> $this->session->userdata('logged_in_role_disp_name'),
			'addressFile' 			=> $addressFile,
			'projectPermission'		=> $projectPermission,
			'contractorPermission'	=> $contractorPermission,
			'adjusterPermission'	=> $adjusterPermission,
			'budgetPermission'		=> $budgetPermission,
			'customerPermission'	=> $customerPermission
		);
		
		echo $this->load->view("projects/projects/inputForm", $params, true);
	}

	public function update() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_PROJECTS, OPERATION_UPDATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');
		$this->load->model('mail/model_mail');

		$record 				= $this->input->post('project_sno');
		$addressLine1 			= $this->input->post('addressLine1');
		$addressLine2 			= $this->input->post('addressLine2');
		$city 					= $this->input->post('city');
		$state 					= $this->input->post('state');
		$country 				= $this->input->post('country');
		$zipCode 				= $this->input->post('zipCode');

		$contractorId 			= $this->input->post('contractor_id') ? $this->input->post('contractor_id') : null;
		$adjusterId 			= $this->input->post('adjuster_id') ? $this->input->post('adjuster_id') : null;
		$customerId 			= $this->input->post('customer_id') ? $this->input->post('customer_id') : null;

		$data = array(
			'project_name' 				=> $this->input->post('projectTitle'),
			'project_descr'				=> $this->input->post('description'),
			'associated_claim_num'		=> $this->input->post('associated_claim_num'),
			'project_type'				=> $this->input->post('project_type'),
			'start_date' 				=> $this->input->post('start_date'),
			'end_date' 					=> $this->input->post('end_date'),
			'project_status'			=> $this->input->post('project_status'),
			'project_budget'			=> $this->input->post('project_budget'),
			'property_owner_id'			=> $this->input->post('property_owner_id'),
			'contractor_id'				=> $contractorId,
			'adjuster_id'				=> $adjusterId,
			'broker_id'					=> $this->input->post('broker_id'),
			'banker_id'					=> $this->input->post('banker_id'),
			'customer_id'				=> $customerId,
			'paid_from_budget'			=> $this->input->post('paid_from_budget'),
			'remaining_budget'			=> $this->input->post('remaining_budget'),
			'deductible' 				=> $this->input->post('deductible'),
			'project_lender'			=> $this->input->post('project_lender'),
			'lend_amount'				=> $this->input->post('lend_amount'),
			'address1' 					=> $addressLine1,
			'address2' 					=> $addressLine2,
			'city' 						=> $city,
			'state' 					=> $state,
			'country' 					=> $country,
			'zip_code'					=> $zipCode,
			'updated_by'				=> $this->session->userdata('logged_in_user_id'),
			'updated_on'				=> date("Y-m-d H:i:s")
		);

		$response = $this->model_projects->update($data, $record);

		$customerData 		= null != $customerId ? $this->model_users->getUserDetailsBySno($customerId) : null;
		$contractorsData 	= null;
		$partnersData 		= null;

		//Service Provider Details
		if($contractorId != "") {
			$contractorIdArr = explode(",", $contractorId);
			$contractorsResponse = $this->model_service_providers->get_service_provider_list($contractorIdArr);
			 $contractorsData = $contractorsResponse["contractors"];
		}

		// Partners Name
		if($adjusterId != "") {
			$partnerIdArr = explode(",", $adjusterId);
			$partnersResponse = $this->model_partners->getPartnersList($partnerIdArr);
			 $partnersData = $partnersResponse["partners"];
		}

		$projectParamsFormMail = array(
			'response'			=> $response,
			'projectData'		=> $data,
			'customerData' 		=> $customerData,
			'contractorsData' 	=> $contractorsData,
			'partnersData' 		=> $partnersData,
			'mail_type' 		=> "update"
		);

		$mail_options = $this->model_mail->generateProjectMailOptions( $projectParamsFormMail );
		
		$response['mail_content'] = $mail_options;
		
		for($i = 0; $i < count($mail_options); $i++) {
			$response["mail_error"] = $this->model_mail->sendMail( $mail_options[$i] );
		}
		

		print_r(json_encode($response));
	}

	public function updateSP() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$this->load->model('projects/model_projects');

		$record 		= $this->input->post('project_sno');
		$contractorId 	= $this->input->post('contractor_id') ? $this->input->post('contractor_id') : null;

		$data = array(
			'contractor_id'	=> $contractorId
		);

		$response = $this->model_projects->update($data, $record);

		print_r(json_encode($response));

	}

	public function deleteRecord() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_PROJECTS, OPERATION_DELETE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}
		
		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');
		$this->load->model('mail/model_mail');

		$projectId = $this->input->post('projectId');

		// Get Porject details defore delete
		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission' => $projectPermission
		);
		$projects = $this->model_projects->getProjectsList($projectParams);
		$project 	= count($projects) ? $projects[0] : "";

		$customerId = isset($project->customer_id) && !empty($project->customer_id) ? $project->customer_id : null;
		$contractorId = isset($project->contractor_id) && !empty($project->contractor_id) ? $project->contractor_id : null;
		$adjusterId = isset($project->adjuster_id) && !empty($project->adjuster_id) ? $project->adjuster_id : null;

		$customerData 		= null != $customerId ? $this->model_users->getUserDetailsBySno($customerId) : null;
		$contractorsData 	= null;
		$partnersData 		= null;

		$response = $this->model_projects->deleteRecord($projectId);

		//Service Provider Details
		if($contractorId != "") {
			$contractorIdArr = explode(",", $contractorId);
			$contractorsResponse = $this->model_service_providers->get_service_provider_list($contractorIdArr);
			 $contractorsData = $contractorsResponse["contractors"];
		}

		// Partners Name
		if($adjusterId != "") {
			$partnerIdArr = explode(",", $adjusterId);
			$partnersResponse = $this->model_partners->getPartnersList($partnerIdArr);
			 $partnersData = $partnersResponse["partners"];
		}

		$projectParamsFormMail = array(
			'response'			=> $response,
			'projectData'		=> $project,
			'customerData' 		=> $customerData,
			'contractorsData' 	=> $contractorsData,
			'partnersData' 		=> $partnersData,
			'mail_type' 		=> "delete"
		);

		$mail_options = $this->model_mail->generateProjectMailOptions( $projectParamsFormMail );
		
		$response['mail_content'] = $mail_options;
		for($i = 0; $i < count($mail_options); $i++) {
			$response["mail_error"] = $this->model_mail->sendMail( $mail_options[$i] );
		}

		print_r(json_encode($response));	
	}

	public function viewOnlyBudget() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		/* Get Role ID and Role Display String*/
		$permission = $this->permissions_lib->getPermissions(FUNCTION_BUDGET);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Project Details"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}
		
		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_remainingbudget');

		$projectId = $this->input->post('projectId');

		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission'	=> $projectPermission
		);
		$projects = $this->model_projects->getProjectsList($projectParams);

		$project 	= count($projects) ? $projects[0] : "";

		$project->paid_from_budget = $this->model_remainingbudget->getPaidBudgetSum($project->proj_id);

		$budgetParams = array(
			'project'		=> $project,
			'userType' 		=> $this->session->userdata('logged_in_role_id'),
		);

		echo  $this->load->view("projects/projects/projectBudget", $budgetParams, true);
	}

	public function viewOne() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $projectPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Project Details"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Including Required Modules */
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_notes');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('projects/model_issues');
		$this->load->model('adjusters/model_partners');
		$this->load->model('projects/model_remainingbudget');
		$this->load->model('utils/model_form_utils');

		$projectId = $this->input->post('projectId');

		//Issues > Permissions for logged in User by role_id
		$issuesPermission 		= $this->permissions_lib->getPermissions(FUNCTION_ISSUES);
		//Tasks > Permissions for logged in User by role_id
		$tasksPermission		= $this->permissions_lib->getPermissions(FUNCTION_TASKS);
		//Docs > Permissions for logged in User by role_id
		$docsPermission 		= $this->permissions_lib->getPermissions(FUNCTION_DOCS);
		//Notes > Permissions for logged in User by role_id
		$notesPermission 		= $this->permissions_lib->getPermissions( 'notes');
		//Budget > Permissions for logged in User by role_id
		$budgetPermission 		= $this->permissions_lib->getPermissions(FUNCTION_BUDGET);
		//Customer > Permissions for logged in User by role_id
		$customerPermission 	= $this->permissions_lib->getPermissions(FUNCTION_CUSTOMER);
		//Service Provider > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER);
		//Adjuster > Permissions for logged in User by role_id
		$adjusterPermission 	= $this->permissions_lib->getPermissions(FUNCTION_PARTNER);

		$projectParams = array (
			'projectId'			=> [$projectId],
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission'	=> $projectPermission
		);
		$projects = $this->model_projects->getProjectsList($projectParams);

		$project 	= count($projects) ? $projects[0] : "";

		$start_date		= "";
		$end_date		= "";
		$percentage 	= "";
		$contractors 	= "";
		$partners 	= "";
		$customerFile 	= "";

		// Individual View
		/**
			Compute following data from task table.
			Start Date,
			End Date and 
			Percentage
		*/
		$ed_query = "select 
							AVG(task_percent_complete) as percentage, 
							DATE_FORMAT( MIN(task_end_date),  '%m/%d/%Y') as end_date, 
							DATE_FORMAT( MIN(task_start_date),  '%m/%d/%Y') as start_date 
					from project_details where project_id = '".$project->proj_id."' and is_deleted = 0";

		$consolidate_data_query = $this->db->query($ed_query);
		$consolidate_data_result = $consolidate_data_query->result();
		$consolidate_data = $consolidate_data_result[0];

		$project->percentage 	= $consolidate_data->percentage > 0  ? round($consolidate_data->percentage,1) : 0;
		$start_date 			= $consolidate_data->start_date != "" ? 
										($project->start_date != "" && $project->start_date < $consolidate_data->start_date ? $project->start_date : $consolidate_data->start_date) : 
										($project->start_date != "" ? $project->start_date : "-NA-");

		$end_date 				= $consolidate_data->end_date != "" ? 
										($project->end_date != "" && $project->end_date > $consolidate_data->end_date ? $project->end_date : $consolidate_data->end_date) : 
										($project->end_date != "" ? $project->end_date : "-NA-");

		$project->start_date 	= $start_date;
		$project->end_date 		= $end_date;

		// Created By and Updated By user Name
		$created_by_name_arr 		= $this->model_users->getUsersList($project->created_by);
		$project->created_by_name 	= count($created_by_name_arr) ? $created_by_name_arr[0]->user_name : "-NA-";
		$updated_by_name_arr 		= $this->model_users->getUsersList($project->updated_by);
		$project->updated_by_name 	= count($updated_by_name_arr) ? $updated_by_name_arr[0]->user_name : "-NA";

		//Service Provider Name
		$project->contractorName = "--";

		if(!in_array(OPERATION_VIEW, $contractorPermission['operation']) && !in_array(OPERATION_CHOOSE, $contractorPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Assigned Service Provider Details"
			);
			$contractorFile =  $this->load->view("pages/no_permission", $no_permission_options, true);
		} else {
			if($project->contractor_id != "") {
				$contractorIdArr = explode(",", $project->contractor_id);
				$contractorsResponse = $this->model_service_providers->get_service_provider_list($contractorIdArr);
				 $contractors = $contractorsResponse["contractors"];
			}
			$contractorParams = array(
				"contractors"				=> $contractors
			);
			$contractorFile 		= $this->load->view("projects/projects/contractorDetailsView", $contractorParams, true);
		}

		// Partners Name
		$project->partnerName = "--";

		if(!in_array(OPERATION_VIEW, $adjusterPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Assigned Adjuster Details"
			);
			$adjusterFile =  $this->load->view("pages/no_permission", $no_permission_options, true);
		} else {
			if($project->adjuster_id != "") {
				$partnerIdArr = explode(",", $project->adjuster_id);
				$partnersResponse = $this->model_partners->getPartnersList($partnerIdArr);
				$partners = $partnersResponse["partners"];
			}
			$adjusterParams = array(
				"partners"				=> $partners
			);
			$adjusterFile 		= $this->load->view("projects/projects/adjusterDetailsView", $adjusterParams, true);
		}
		

		//Paid From budget
		$project->paid_from_budget = $this->model_remainingbudget->getPaidBudgetSum($project->proj_id);

		$internalLinkParams = array(
			"internalLinkArr" 		=> ["update project", "delete project"],
			"projectId" 			=> $projectId,
			'projectPermission'		=> $projectPermission,
		);

		/*
			Customer Output
		*/
		if(!in_array(OPERATION_VIEW, $customerPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Assigned Customer Details"
			);
			$customerFile =  $this->load->view("pages/no_permission", $no_permission_options, true);
		} else {
			$customer_details_id = $this->model_users->get_user_details_id_from_user_id($project->customer_id);
			$customers 	= $this->model_users->getUserDetailsBySno($customer_details_id);
			$customer 	= count($customers) ? $customers[0] : "";
			
			if($customer) {
				$customerParams = array(
					"customer"				=> $customer
				);
				$customerFile 		= $this->load->view("projects/projects/customerDetailsView", $customerParams, true);
			}
		}

		/*
			Issues Count
		*/
		$issuesResponse = $this->model_issues->getIssuesList(array('records' => '', 'projectId' => $projectId, 'status' => 'open'));
		$issueCount 	= $issuesResponse && $issuesResponse["issues"] ? count($issuesResponse["issues"]) : 0;

		$project->issueCount = $issueCount;

		$addressFile = $this->form_lib->getAddressFile(array("requestFrom" => "view", "address_data" => $project));
		/*
			Budget List
		*/
		if(in_array(OPERATION_VIEW, $budgetPermission['operation'])) {
			$budgetParams = array(
				'project'		=> $project,
				'userType' 		=> $this->session->userdata('logged_in_role_id'),
			);
			$project_budget_file = $this->load->view("projects/projects/projectBudget.php", $budgetParams, true);
		} else {
			$no_permission_options = array(
				'page_disp_string' => "Budget List"
			);
			$project_budget_file = $this->load->view("pages/no_permission", $no_permission_options, true);
		}

		/*
			Final Project ViewOnly template output
		*/
		$params = array(
			'project'				=> $project,
			'role_id'				=> $this->session->userdata('logged_in_role_id'),
			'role_disp_name' 		=> $this->session->userdata('logged_in_role_disp_name'),
			'projectId' 			=> $projectId,
			'contractors' 			=> $contractors,
			'partners' 				=> $partners,
			'customerFile' 			=> $customerFile,
			'contractorFile' 		=> $contractorFile,
			'adjusterFile'			=> $adjusterFile,
			'addressFile' 			=> $addressFile,
			'projectBudgetFile' 	=> $project_budget_file,
			'projectPermission'		=> $projectPermission,
			'issuesPermission'		=> $issuesPermission,
			'tasksPermission'		=> $tasksPermission,
			'docsPermission'		=> $docsPermission,
			'notesPermission'		=> $notesPermission,
			'budgetPermission'		=> $budgetPermission,
			'customerPermission'	=> $customerPermission,
			'contractorPermission'	=> $contractorPermission,
			'adjusterPermission'	=> $adjusterPermission
		);
		echo $this->load->view("projects/projects/viewOne", $params, true);
	}

	public function add_project_owner() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER);

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_PROJECTS, DATA_FILTER_SERVICE_PROVIDER_USERS, 'data filter');

		if(!$is_allowed["status"] && !in_array(OPERATION_CHOOSE, $contractorPermission['operation'])) {
			print_r(json_encode($is_allowed));
			return false;
		}


		$project_id 			= $this->input->post('projectId');
		$sp_user_user_id 		= $this->input->post('user_id');
		$parent_company_id 		= $this->input->post('user_parent_id');

		if(!empty($sp_user_user_id)) {
			$sp_user_user_id = explode(",", $sp_user_user_id);			
		}

		if(!empty($parent_company_id)) {
			$parent_company_id = explode(",", $parent_company_id);			
		}

		$multiple_owner = true;

		if( count($sp_user_user_id) != count($parent_company_id)) {
			$multiple_owner = false;
		}

		$logged_in_role_id = $this->session->userdata('logged_in_role_id');

		$update_query 		= true;
		$this->load->model('projects/model_projects');

		/*
			Delete any record which are assigned earlier
		*/
		$data = array(
		"is_deleted"	=> 1,
		"updated_by"	=> $logged_in_role_id,
		"updated_on"	=> date("Y-m-d H:i:s")
		);

		$params = array(
			"data"					=> $data,
			"project_id"			=> $project_id,
			"parent_company_id" 	=> $parent_company_id
		);

		$response = $this->model_projects->update_project_owner($params);

		if(isset($sp_user_user_id) && !empty($sp_user_user_id)) {
			for($i = 0; $i < count($sp_user_user_id); $i++) {
				$this->load->model('security/model_users');
				$sp_user_role_id = $this->model_users->get_role_id_from_user_id( $sp_user_user_id[$i] );
				if(isset($sp_user_role_id) && !empty($sp_user_role_id)) {
					$data = array(
						"project_id"			=> $project_id,
						"role_id"				=> $sp_user_role_id,
						"user_id"				=> $sp_user_user_id[$i],
						"parent_company_id" 	=> $multiple_owner ? $parent_company_id[$i] : $parent_company_id,
						"is_deleted"			=> 0,
						"created_by"			=> $logged_in_role_id,
						"created_on"			=> date("Y-m-d H:i:s"),
						"updated_by"			=> $logged_in_role_id,
						"updated_on"			=> date("Y-m-d H:i:s")
					);
					$response = $this->model_projects->insert_project_owner($data);
				}
			}
		}

		print_r(json_encode($response));
	}

	function get_sp_assigned_user_for_current_project() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$response = array("status" => "error");

		$this->load->model('projects/model_projects');

		$user_parent_id 	= $this->input->post('user_parent_id');
		$project_id 		= $this->input->post('project_id');
		$details 			= $this->input->post('details');

		if( empty($user_parent_id) ) {
			//Project > Permissions for logged in User by role_id
			$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);
			
			$projectParams = array (
				'projectId'			=> [$project_id],
				'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
				'projectPermission'	=> $projectPermission
			);
			$projects = $this->model_projects->getProjectsList($projectParams);

			$project 	= count($projects) ? $projects[0] : "";

			$user_parent_id = explode(",", $project->contractor_id);
			//print_r($user_parent_id);
		}

		$params = array(
			"project_id"			=> $project_id,
			"parent_company_id" 	=> $user_parent_id
		);

		$existing_record_response = $this->model_projects->get_existing_project_owner($params);

		if( $existing_record_response["status"] == "success" ) {
			$existing_record = $existing_record_response["owner_list"];
			$existing_user_details = array();

			if( isset($details) && !empty($details) ) {
				$this->load->model('security/model_users');
				for( $i = 0; $i < count($existing_record); $i++ ) {
					array_push( $existing_user_details, $this->model_users->getUsersList($existing_record[$i])[0] );
				}
				$response = array('status' => "success", "assigned_user_id" => $existing_user_details);
			}
			else {
				$response = array('status' => "success", "assigned_user_id" => $existing_record);
			}
		}
		else {
			$response = $existing_record_response;
		}

		print_r(json_encode( $response ));

	}

	public function exportCSV() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}
		
		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_EXPORT, $projectPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "export project"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->helper('url');
		//$controller = $this->uri->segment(1);
		//$page = $this->uri->segment(2);
		//$module = $this->uri->segment(3) ? $this->uri->segment(3): "";
		//$sub_module = $this->uri->segment(3) ? $this->uri->segment(3): "";
		$function = $this->uri->segment(4) ? $this->uri->segment(4): "";
		//$record = $this->uri->segment(5) ? $this->uri->segment(5): "";

		//echo $controller.",".$page.",".$module .",".$sub_module.",".$function.",".$record;

		$projectId = $function;
		/*print_r($this->session->all_userdata());*/

		if(!isset($projectId) || empty($projectId)) {
			echo "Invalid Request";
			return;
		}
		
		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_notes');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('projects/model_issues');
		$this->load->model('adjusters/model_partners');
		$this->load->model('projects/model_remainingbudget');

		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission'	=> $projectPermission

		);
		$projects = $this->model_projects->getProjectsList($projectParams);

		$project 	= count($projects) ? $projects[0] : "";

		$start_date		= "";
		$end_date		= "";
		$percentage 	= "";
		$contractors 	= null;
		$partners 		= null;
		$customerFile 	= "";

		// Individual View
		$ed_query = "select 
							AVG(task_percent_complete) as percentage, 
							DATE_FORMAT( MIN(task_end_date),  '%m/%d/%Y') as end_date, 
							DATE_FORMAT( MIN(task_start_date),  '%m/%d/%Y') as start_date 
					from project_details where project_id = '".$project->proj_id."' and is_deleted = 0";

		$consolidate_data_query = $this->db->query($ed_query);
		$consolidate_data_result = $consolidate_data_query->result();
		$consolidate_data = $consolidate_data_result[0];

		$project->percentage 	= $consolidate_data->percentage > 0  ? round($consolidate_data->percentage,1) : 0;
		$start_date 			= $consolidate_data->start_date != "" ? ($project->start_date != "" && $project->start_date < $consolidate_data->start_date ? $project->start_date : $consolidate_data->start_date) : ($project->start_date != "" ? $project->start_date : "-NA-");
		$end_date 				= $consolidate_data->end_date != "" ? ($project->end_date != "" && $project->end_date > $consolidate_data->end_date ? $project->end_date : $consolidate_data->end_date) : ($project->end_date != "" ? $project->end_date : "-NA-");

		$project->start_date 	= $start_date;
		$project->end_date 		= $end_date;

		// Created By and Updated By user Name
		$project->created_by_name = $this->model_users->getUsersList($project->created_by)[0]->user_name;
		$project->updated_by_name = $this->model_users->getUsersList($project->updated_by)[0]->user_name;

		//Service Provider Name
		$project->contractorName = "--";
		if($project->contractor_id != "") {
			$contractorIdArr = explode(",", $project->contractor_id);
			$contractorsResponse = $this->model_service_providers->get_service_provider_list($contractorIdArr);
			 $contractors = $contractorsResponse["contractors"];
		}

		// Partners Name
		$project->partnerName = "--";
		if($project->adjuster_id != "") {
			$partnerIdArr = explode(",", $project->adjuster_id);
			$partnersResponse = $this->model_partners->getPartnersList($partnerIdArr);
			 $partners = $partnersResponse["partners"];
		}

		//Paid From budget
		$project->paid_from_budget = $this->model_remainingbudget->getPaidBudgetSum($project->proj_id);

		$this->load->helper('download');
	    
        $csvArray = array(
            //[""], 
            ["", "Project Title", $project->project_name], 
            ["", "Project Description", $project->project_descr],
            
            /* Address Details */
            ["Project Address", ""],
            ["", "Address Line 1", $project->address1],
            ["", "Address Line 2", $project->address2],
            ["", "City", $project->city],
            ["", "State", $project->state],
            ["", "Country", $project->country],
            ["", "Zip", $project->zip_code],
            
            /* Budget Details */
            ["Budget", ""],
            ["", "Project Budget", "$ ".number_format($project->project_budget, 2, '.', ',')],
            ["", "Paid From Budget", "$ ".number_format($project->paid_from_budget, 2, '.', ',')],
            ["", "Remaining Budget", "$ ".number_format(($project->project_budget - $project->paid_from_budget), 2, '.', ',')],
            ["", "Deductible", "$ ".number_format($project->deductible, 2, '.', ',')],
            ["", "Referral Fee", "$ ".number_format(((($project->project_budget - $project->deductible)/100) * 7), 2, '.', ',')],
            
            /* Project Dates */
            ["Project Schedule", ""],
            ["", "Start Date", $project->start_date],
            ["", "End Date", $project->end_date]
        );
        
        // Service Provider Details */
        $csvArray[] = array("Service Providers Assigned To The Project", "");
        $csvArray[] = array("", "Service Provider Name", "Service Provider Company", "Prefered Contact Mode", "Contact Office Email", "Contact Office Number", "Contact Mobile Number", "Address Line 1", "Address Line 2", "City", "State", "Country", "Zip Code");
        
        // Service Provider List */
        if( $contractors ) {
	        for($i = 0; $i < count($contractors); $i++) {
	            $csvArray[] = array(
	            					"", 
	            					$contractors[$i]->name, 
	            					$contractors[$i]->company, 
	            					$contractors[$i]->prefer, 
	            					$contractors[$i]->office_email, 
	            					$contractors[$i]->office_ph, 
	            					$contractors[$i]->mobile_ph, 
	            					$contractors[$i]->address1, 
	            					$contractors[$i]->address2, 
	            					$contractors[$i]->city, 
	            					$contractors[$i]->state, 
	            					$contractors[$i]->country, 
	            					$contractors[$i]->zip_code
	            				);
	        }
	    }
        
        /* Partner Details */
        $csvArray[] = array("Partner Details", "");
        $csvArray[] = array("", "Partner Name", "Partner Company", "Prefered Contact Mode", "Contact Office Email", "Contact Office Number", "Contact Personal Email", "Contact Mobile Number", "Address Line 1", "Address Line 2", "City", "State", "Country", "Zip Code");
        
        if($partners) {
	        for($i = 0; $i < count($partners); $i++) {
	            $csvArray[] = array(
	            					"", 
	            					$partners[$i]->name, 
	            					$partners[$i]->company_name, 
	            					$partners[$i]->contact_pref, 
	            					$partners[$i]->work_email_id, 
	            					$partners[$i]->work_phone, 
	            					$partners[$i]->personal_email_id, 
	            					$partners[$i]->mobile_no, 
	            					$partners[$i]->address1, 
	            					$partners[$i]->address2, 
	            					$partners[$i]->city, 
	            					$partners[$i]->state, 
	            					$partners[$i]->country, 
	            					$partners[$i]->zip_code
	            				);
	        }
    	}
        
        /* Task List */
        $contractors = array();
        
		$customerDetails 	= $this->model_users->getUserDetailsBySno($project->customer_id);
		$customerName 		= isset($customerDetails) && count($customerDetails) ? $customerDetails[0]->first_name." ".$customerDetails[0]->last_name : "-NA-";

		$tasksResponse 	= $this->model_tasks->getTasksList($projectId);

		$contractorIds 			= explode(",", $project->contractor_id);
		$contractorsResponse 	= $this->model_service_providers->get_service_provider_list($contractorIds);
		$contractorDB 			= $contractorsResponse["contractors"];

		for($i = 0; $i < count($contractorDB); $i++) {
			$contractors[$contractorDB[$i]->id] = $contractorDB[$i];
		}
			
        $tasks = isset($tasksResponse["tasks"]) ? $tasksResponse["tasks"] : [];
        
        $csvArray[] = array("Task Details");
        $csvArray[] = array("", "Task Name", "Description", "Owner", "% Complete", "Start Date", "End Date");
        
        for($i = 0; $i < count($tasks); $i++) { 
            $task_name 		= $tasks[$i]->task_name ? $tasks[$i]->task_name : "--";
            $descr 			= $tasks[$i]->task_desc != "" ? $tasks[$i]->task_desc : '--';
            $percent 		= $tasks[$i]->task_percent_complete;
            $stard_date 	= $tasks[$i]->task_start_date_for_view;
            $end_date 		= $tasks[$i]->task_end_date_for_view;

            $ownerName = $tasks[$i]->task_owner_id && $tasks[$i]->task_owner_id != "" && array_key_exists($tasks[$i]->task_owner_id, $contractors) ? $contractors[$tasks[$i]->task_owner_id]->name : $customerName;
            
            $csvArray[] = array("", $task_name, $descr, $ownerName, $percent, $stard_date, $end_date);
        }
        
        /* Issues List */
		$issuesResponse = $this->model_issues->getIssuesList(array('records' => '', 'projectId' => $projectId, 'status' => 'open'));
        $issues = isset($issuesResponse["issues"]) ? $issuesResponse["issues"] : [];
        
        $csvArray[] = array("Issues Details");
        $csvArray[] = array("", "Issue Name", "Issue Status", "Issue From Date");
        
        for($i = 0; $i < count($issues); $i++) {
             $csvArray[] = array("", $issues[$i]->issue_name, $issues[$i]->status, $issues[$i]->issue_from_date);
        }
        
        /* Notes List */
        /*$csvArray[] = array("Notes Details");
        $csvArray[] = array("", "Notes Content", "Created By", "Created On");
        
        $project_notes 		= $this->model_notes->getNotesList($projectId, "" , "", "", "");

		for($i=0; $i < count($project_notes["notes"]); $i++) {
			$project_notes["notes"][$i]->created_by_name = $this->model_users->getUsersList($project_notes["notes"][$i]->created_by)[0]->user_name;
        
            $csvArray[] = array("", strval($project_notes["notes"][$i]->notes_content), "", "");
		}*/
        
        
        /* Print Into XLS */
        /*$fp = fopen('php://output', 'w');
        for($i = 0; $i < count($csvArray); $i++) {
            fputcsv($fp, $csvArray[$i]);
        }

	    $data = file_get_contents('php://output'); */
	    $name = 'data.csv';

	    // Build the headers to push out the file properly.
	    header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="'.basename($name).'"');  // Add the file name
	    header('Pragma: public');     // required
	    header('Expires: 0');         // no cache
	    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	    header('Cache-Control: private',false);
	    header('Content-Transfer-Encoding: binary');
	    header('Connection: close');
	    header('Content-Description: File Transfer');
	    //header('Content-Length: ' . filesize($data));
	    $fp = fopen('php://output', 'w');
        for($i = 0; $i < count($csvArray); $i++) {
            fputcsv($fp, $csvArray[$i]);
        }
        fclose($fp);

	    //$data = file_get_contents('php://output'); 
	    exit();

	    //force_download($name, $data);
	    
	}
}