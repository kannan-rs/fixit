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

		/*echo $controller."<br/>";
		echo $page."<br/>";
		echo $module."<br/>";
		echo $sub_module."<br/>";
		echo $function."<br/>";
		echo $record."<br/>";*/
	}

	public function viewAll() {
		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions("projects");
		//print_r($projectPermission);

		/* If User dont have view permission load No permission page */
		if(!in_array('view', $projectPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Project List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		/* Including Required Modules */
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_issues');
		$this->load->model('security/model_users');

		//Issues > Permissions for logged in User by role_id
		$issuesPermission = $this->permissions_lib->getPermissions('issues');
		//print_r($issuesPermission);

		$projectList 	= "";

		$user_id 		= $this->session->userdata('user_id'); /* Get user ID for logged in User from session */
		$email 			= $this->session->userdata('email'); /* Get Email ID for logged in User from session */

		/* Project Params to get the list of project with permissions */
		$projectParams = array(
			'role_disp_name' 		=> $role_disp_name,
			'user_details_id' 		=> $this->model_users->getUserDetailsSnoViaEmail($email),
			'user_id' 				=> $user_id,
			'email' 				=> $email,
			'projectPermission' 	=> $projectPermission
		);

		$projectListArr = array();
		//print_r($projectParams);
		
		/* If logged in User dont have 'all' permission in data filter, then get the project ID's list that user has access */
		if( !in_array('all', $projectPermission['data_filter']) ) {
			$projectList = $this->model_projects->getProjectIds($projectParams);
			for($i = 0; $i < count($projectList); $i++) {
				array_push($projectListArr, $projectList[$i]->proj_id);
			}
		}

		//echo "projectListArr ->";
		//print_r($projectListArr);
		/* Set the list of project ID's to projectParams, that logged in user has access to */
		$projectParams["projectId"] = $projectListArr;
		//print_r($projectParams);
		
		/* Get the list of Projects that logged in user has access to, from Database > projects > table to display */
		$projects = $this->model_projects->getProjectsList( $projectParams );

		for($i = 0; $i < count($projects); $i++) {
			$start_date = "";
			$end_date	= "";
			$percentage = "";

			$ed_query = "select 
								AVG(task_percent_complete) as percentage, 
								DATE_FORMAT( MAX(task_end_date), '%m/%d/%y' ) as end_date, 
								DATE_FORMAT( MIN(task_start_date),  '%m/%d/%y') as start_date 
						from project_details where project_id = '".$projects[$i]->proj_id."' and deleted = 0";

			$consolidate_data_query = $this->db->query($ed_query);
			$consolidate_data_result = $consolidate_data_query->result();
			$consolidate_data = $consolidate_data_result[0];

			$projects[$i]->percentage = ($consolidate_data->percentage > 0  ? round($consolidate_data->percentage,1) : 0);

			$start_date 	= $consolidate_data->start_date != "" ? ($projects[$i]->start_date != "" && $projects[$i]->start_date < $consolidate_data->start_date ? $projects[$i]->start_date : $consolidate_data->start_date) : ($projects[$i]->start_date != "" ? $projects[$i]->start_date : "-NA-");
			$end_date 		= $consolidate_data->end_date != "" ? ($projects[$i]->end_date != "" && $projects[$i]->end_date > $consolidate_data->end_date ? $projects[$i]->end_date : $consolidate_data->end_date) : ($projects[$i]->end_date != "" ? $projects[$i]->end_date : "-NA-");

			$projects[$i]->start_date 	= $start_date;
			$projects[$i]->end_date 		= $end_date;

			if(in_array('view', $issuesPermission['operation'])) {
				$issuesResponse = $this->model_issues->getIssuesList(array('records' => '', 'projectId' => $projects[$i]->proj_id, 'status' => 'open'));
				$issueCount 	= $issuesResponse && $issuesResponse["issues"] ? count($issuesResponse["issues"]) : 0;

				$projects[$i]->issueCount = $issueCount;
			}

		}

		$params = array(
			'projects' 			=> $projects,
			'role_id' 			=> $role_id,
			'role_disp_name' 	=> $role_disp_name,
			'projectPermission' => $projectPermission,
			'issuesPermission'	=> $issuesPermission
		);
		
		echo $this->load->view("projects/projects/viewAll", $params, true);
	}

	public function getAssignees() {
		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_partners');

		$customerId = "";
		$adjusterId = "";
		$contractorId = "";

		$assigneeDetails  	= array(
			"status" 	=> "error"
		);

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions('projects');

		$projectId 			= $this->input->post('projectId');
		
		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=> $role_disp_name,
			'projectPermission'	=> $projectPermission
		);
		$projects = $this->model_projects->getProjectsList( $projectParams );

		if(count($projects)) {
			$customerId = $projects[0]->customer_id;
			$adjusterId = $projects[0]->adjuster_id;
			$contractorId = $projects[0]->contractor_id;
		}

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

		if(!empty($adjusterId)) {
			$adjusterIdArr = explode(",", $adjusterId);
			$partnersResponse = $this->model_partners->getPartnersList($adjusterIdArr);
			 $assigneeDetails["adjusterDetails"] = $partnersResponse["partners"];
		}

		if(!empty($contractorId)) {
			$contractorIdArr = explode(",", $contractorId);
			$contractorsResponse = $this->model_contractors->getContractorsList($contractorIdArr);
			$assigneeDetails["contractorDetails"] = $contractorsResponse["contractors"];
		}

		print_r(json_encode($assigneeDetails));
	}
	
	public function createForm() {
		//Project > Permissions for logged in User by role_id
		$projectPermission 		= $this->permissions_lib->getPermissions('projects');

		/* If User dont have view permission load No permission page */
		if(!in_array('create', $projectPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Creating Projects"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('security/model_users');

		$addressParams = array(
			'forForm' 			=> "create_project_form"
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		//Contractor > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions('service provider');
		//Adjuster > Permissions for logged in User by role_id
		$adjusterPermission 	= $this->permissions_lib->getPermissions('adjuster');
		//Customer > Permissions for logged in User by role_id
		$customerPermission 	= $this->permissions_lib->getPermissions('customer');
		//Budget > Permissions for logged in User by role_id
		$budgetPermission 		= $this->permissions_lib->getPermissions('budget');
		
		$params = array(
			'users' 				=> $this->model_users->getUsersList(),
			'addressFile' 			=> $addressFile,
			'userType' 				=> $role_id,
			'role_disp_name'		=> $role_disp_name,
			'projectPermission'		=> $projectPermission,
			'contractorPermission'	=> $contractorPermission,
			'adjusterPermission'	=> $adjusterPermission,
			'budgetPermission'		=> $budgetPermission,
			'customerPermission'	=> $customerPermission
		);

		echo $this->load->view("projects/projects/inputForm", $params, true);
	}

	public function add() {
		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_partners');
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
			'created_by'				=> $this->session->userdata('user_id'),
			'updated_by'				=> $this->session->userdata('user_id'),
			'created_on'				=> date("Y-m-d H:i:s"),
			'updated_on'				=> date("Y-m-d H:i:s"),
			'addr1' 					=> $addressLine1,
			'addr2' 					=> $addressLine2,
			'addr_city' 				=> $city,
			'addr_state' 				=> $state,
			'addr_country' 				=> $country,
			'addr_pin'					=> $zipCode
		);

		$response = $this->model_projects->insert($data);

		$customerData 		= null != $customerId ? $this->model_users->getUserDetailsBySno($customerId) : null;
		$contractorsData 	= null;
		$partnersData 		= null;

		//Contractor Details
		if($contractorId != "") {
			$contractorIdArr = explode(",", $contractorId);
			$contractorsResponse = $this->model_contractors->getContractorsList($contractorIdArr);
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
		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions("projects");
		//print_r($projectPermission);

		/* If User dont have view permission load No permission page */
		if(!in_array('update', $projectPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "update project"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');

		//Project > Permissions for logged in User by role_id
		$projectPermission 		= $this->permissions_lib->getPermissions('projects');

		/* If User dont have view permission load No permission page */
		if(!in_array('update', $projectPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Update Project"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		$record = $this->input->post('projectId');

		$projectParams = array(
			'projectId' 		=> [$record],
			'role_disp_name' 	=> $role_disp_name,
			'projectPermission' => $projectPermission
		);
		$projects = $this->model_projects->getProjectsList($projectParams);

		$internalLinkParams = array(
			"internalLinkArr" 		=> ["tasks", "project notes", "documents"],
			"projectId" 			=> $record
		);

		$addressParams = array(
			'addressLine1' 		=> $projects[0]->addr1,
			'addressLine2' 		=> $projects[0]->addr2,
			'city' 				=> $projects[0]->addr_city,
			'country' 			=> $projects[0]->addr_country,
			'state'				=> $projects[0]->addr_state,
			'zipCode' 			=> $projects[0]->addr_pin,
			'forForm' 			=> "update_project_form"
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);

		//Contractor > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions('service provider');
		//Adjuster > Permissions for logged in User by role_id
		$adjusterPermission 	= $this->permissions_lib->getPermissions('adjuster');
		//Customer > Permissions for logged in User by role_id
		$customerPermission 	= $this->permissions_lib->getPermissions('customer');
		//Budget > Permissions for logged in User by role_id
		$budgetPermission 		= $this->permissions_lib->getPermissions('budget');

		$params = array(
			'projects' 				=>$projects,
			'users' 				=> $this->model_users->getUsersList(),
			'internalLink' 			=> $this->load->view("projects/internalLinks", $internalLinkParams, true),
			'userType' 				=> $role_id,
			'role_disp_name'		=> $role_disp_name,
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
		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_partners');
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
			'addr1' 					=> $addressLine1,
			'addr2' 					=> $addressLine2,
			'addr_city' 				=> $city,
			'addr_state' 				=> $state,
			'addr_country' 				=> $country,
			'addr_pin'					=> $zipCode,
			'updated_by'				=> $this->session->userdata('user_id'),
			'updated_on'				=> date("Y-m-d H:i:s")
		);

		$response = $this->model_projects->update($data, $record);

		$customerData 		= null != $customerId ? $this->model_users->getUserDetailsBySno($customerId) : null;
		$contractorsData 	= null;
		$partnersData 		= null;

		//Contractor Details
		if($contractorId != "") {
			$contractorIdArr = explode(",", $contractorId);
			$contractorsResponse = $this->model_contractors->getContractorsList($contractorIdArr);
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

	public function deleteRecord() {
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();
		
		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions('projects');

		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_partners');
		$this->load->model('mail/model_mail');

		$projectId = $this->input->post('projectId');

		// Get Porject details defore delete
		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=> $role_disp_name,
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

		//Contractor Details
		if($contractorId != "") {
			$contractorIdArr = explode(",", $contractorId);
			$contractorsResponse = $this->model_contractors->getContractorsList($contractorIdArr);
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
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();
		
		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions('projects');

		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_remainingbudget');

		$projectId = $this->input->post('projectId');

		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=> $role_disp_name,
			'projectPermission'	=> $projectPermission
		);
		$projects = $this->model_projects->getProjectsList($projectParams);

		$project 	= count($projects) ? $projects[0] : "";

		$project->paid_from_budget = $this->model_remainingbudget->getPaidBudgetSum($project->proj_id);

		$budgetParams = array(
			'project'		=> $project,
			'userType' 		=> $this->session->userdata('role_id'),
		);

		echo  $this->load->view("projects/projects/projectBudget", $budgetParams, true);
	}

	public function viewOne() {
		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions('projects');

		/* If User dont have view permission load No permission page */
		if(!in_array('view', $projectPermission['operation'])) {
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
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_issues');
		$this->load->model('projects/model_partners');
		$this->load->model('projects/model_remainingbudget');
		$this->load->model('utils/model_form_utils');

		$projectId = $this->input->post('projectId');

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		//Issues > Permissions for logged in User by role_id
		$issuesPermission 		= $this->permissions_lib->getPermissions('issues');
		//Tasks > Permissions for logged in User by role_id
		$tasksPermission		= $this->permissions_lib->getPermissions('tasks');
		//Docs > Permissions for logged in User by role_id
		$docsPermission 		= $this->permissions_lib->getPermissions('docs');
		//Notes > Permissions for logged in User by role_id
		$notesPermission 		= $this->permissions_lib->getPermissions( 'notes');
		//Budget > Permissions for logged in User by role_id
		$budgetPermission 		= $this->permissions_lib->getPermissions('budget');
		//Customer > Permissions for logged in User by role_id
		$customerPermission 	= $this->permissions_lib->getPermissions('customer');
		//Service Provider > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions('service provider');
		//Adjuster > Permissions for logged in User by role_id
		$adjusterPermission 	= $this->permissions_lib->getPermissions('adjuster');

		/*
		echo "<br/>Project Permissions ->";
		print_r($projectPermission);
		echo "<br/>Issues Permissions ->";
		print_r($issuesPermission);
		echo "<br/>Tasks Permissions ->";
		print_r($tasksPermission);
		echo "<br/>Docs Permissions ->";
		print_r($docsPermission);
		echo "<br/>Notes Permissions ->";
		print_r($notesPermission);
		echo "<br/>Budget Permissions ->";
		print_r($budgetPermission);
		echo "<br/>Customer Permissions ->";
		print_r($customerPermission);
		echo "<br/>Contractor / Service provider Permissions ->";
		print_r($contractorPermission);
		echo "<br/>Adjuster Permissions ->";
		print_r($adjusterPermission);
		*/

		$projectParams = array(
			'projectId'			=> [$projectId],
			'role_disp_name' 	=> $role_disp_name,
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
		$ed_query = "select 
							AVG(task_percent_complete) as percentage, 
							DATE_FORMAT( MIN(task_end_date),  '%m/%d/%y') as end_date, 
							DATE_FORMAT( MIN(task_start_date),  '%m/%d/%y') as start_date 
					from project_details where project_id = '".$project->proj_id."' and deleted = 0";

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

		// Contractor Name
		$project->contractorName = "-- Not Provided --";

		if(!in_array('view', $contractorPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Assigned Contractor Details"
			);
			$contractorFile =  $this->load->view("pages/no_permission", $no_permission_options, true);
		} else {
			if($project->contractor_id != "") {
				$contractorIdArr = explode(",", $project->contractor_id);
				$contractorsResponse = $this->model_contractors->getContractorsList($contractorIdArr);
				 $contractors = $contractorsResponse["contractors"];
			}
			$contractorParams = array(
				"contractors"				=> $contractors
			);
			$contractorFile 		= $this->load->view("projects/projects/contractorDetailsView", $contractorParams, true);
		}

		// Partners Name
		$project->partnerName = "-- Not Provided --";

		if(!in_array('view', $adjusterPermission['operation'])) {
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
		if(!in_array('view', $customerPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Assigned Customer Details"
			);
			$customerFile =  $this->load->view("pages/no_permission", $no_permission_options, true);
		} else {
			$customers 	= $this->model_users->getUserDetailsBySno($project->customer_id);
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

		/*
			Address Output
		*/
		$stateText = !empty($project->addr_state) ? $this->model_form_utils->getCountryStatus($project->addr_state)[0]->name : "";
		$addressParams = array(
			'addressLine1' 		=> $project->addr1,
			'addressLine2' 		=> $project->addr2,
			'city' 				=> $project->addr_city,
			'country' 			=> $project->addr_country,
			'state'				=> $stateText,
			'zipCode' 			=> $project->addr_pin,
			'requestFrom' 		=> 'view',
		);
		//$addressFile = $this->load->view("forms/address", $addressParams, true);

		/*
			Budget List
		*/
		if(in_array('view', $budgetPermission['operation'])) {
			$budgetParams = array(
				'project'		=> $project,
				'userType' 		=> $this->session->userdata('role_id'),
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
			'role_id'				=> $role_id,
			'userType' 				=> $role_disp_name,
			'projectId' 			=> $projectId,
			'contractors' 			=> $contractors,
			'partners' 				=> $partners,
			'customerFile' 			=> $customerFile,
			'contractorFile' 		=> $contractorFile,
			'adjusterFile'			=> $adjusterFile,
			'addressFile' 			=> $this->load->view("forms/address", $addressParams, true),
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

	public function exportCSV() {
		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions('projects');

		/* If User dont have view permission load No permission page */
		if(!in_array('export', $projectPermission['operation'])) {
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

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();
		
		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions('projects');

		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_notes');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_issues');
		$this->load->model('projects/model_partners');
		$this->load->model('projects/model_remainingbudget');

		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=> $role_disp_name,
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
		$ed_query = "select 
							AVG(task_percent_complete) as percentage, 
							DATE_FORMAT( MIN(task_end_date),  '%m/%d/%y') as end_date, 
							DATE_FORMAT( MIN(task_start_date),  '%m/%d/%y') as start_date 
					from project_details where project_id = '".$project->proj_id."' and deleted = 0";

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

		// Contractor Name
		$project->contractorName = "-- Not Provided --";
		if($project->contractor_id != "") {
			$contractorIdArr = explode(",", $project->contractor_id);
			$contractorsResponse = $this->model_contractors->getContractorsList($contractorIdArr);
			 $contractors = $contractorsResponse["contractors"];
		}

		// Partners Name
		$project->partnerName = "-- Not Provided --";
		if($project->adjuster_id != "") {
			$partnerIdArr = explode(",", $project->adjuster_id);
			$partnersResponse = $this->model_partners->getPartnersList($partnerIdArr);
			 $partners = $partnersResponse["partners"];
		}

		//Paid From budget
		$project->paid_from_budget = $this->model_remainingbudget->getPaidBudgetSum($project->proj_id);

		$this->load->helper('download');

	    $fp = fopen('php://output', 'w');
	    
        $csvArray = array(
            [""], 
            ["", "Project Title", $project->project_name], 
            ["", "Project Description", $project->project_descr],
            
            /* Address Details */
            ["Project Address", ""],
            ["", "Address Line 1", $project->addr1],
            ["", "Address Line 2", $project->addr2],
            ["", "City", $project->addr_city],
            ["", "State", $project->addr_state],
            ["", "Country", $project->addr_country],
            ["", "Zip", $project->addr_pin],
            
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
        
        /* Contractor Details */
        $csvArray[] = array("Contractors Assigned To The Project", "");
        $csvArray[] = array("", "Contractor Name", "Contractor Company", "Prefered Contact Mode", "Contact Office Email", "Contact Office Number", "Contact Mobile Number", "Address Line 1", "Address Line 2", "City", "State", "Country", "Zip Code");
        
        /* Contractor List */
        for($i = 0; $i < count($contractors); $i++) {
            $csvArray[] = array("", $contractors[$i]->name, $contractors[$i]->company, $contractors[$i]->prefer, $contractors[$i]->office_email, $contractors[$i]->office_ph, $contractors[$i]->mobile_ph, $contractors[$i]->address1, $contractors[$i]->address2, $contractors[$i]->city, $contractors[$i]->state, $contractors[$i]->country, $contractors[$i]->pin_code);
        }
        
        /* Partner Details */
        $csvArray[] = array("Partner Details", "");
        $csvArray[] = array("", "Partner Name", "Partner Company", "Prefered Contact Mode", "Contact Office Email", "Contact Office Number", "Contact Personal Email", "Contact Mobile Number", "Address Line 1", "Address Line 2", "City", "State", "Country", "Zip Code");
        for($i = 0; $i < count($partners); $i++) {
            $csvArray[] = array("", $partners[$i]->name, $partners[$i]->company_name, $partners[$i]->contact_pref, $partners[$i]->work_email_id, $partners[$i]->work_phone, $partners[$i]->personal_email_id, $partners[$i]->mobile_no, $partners[$i]->address1, $partners[$i]->address2, $partners[$i]->city, $partners[$i]->state, $partners[$i]->country, $partners[$i]->zip_code);
        }
        
        /* Task List */
        $contractors = array();
        
		$customerDetails 	= $this->model_users->getUserDetailsBySno($project->customer_id);
		$customerName 		= isset($customerDetails) && count($customerDetails) ? $customerDetails[0]->first_name." ".$customerDetails[0]->last_name : "-NA-";

		$tasksResponse 	= $this->model_tasks->getTasksList($projectId);

		$contractorIds 			= explode(",", $project->contractor_id);
		$contractorsResponse 	= $this->model_contractors->getContractorsList($contractorIds);
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
        for($i = 0; $i < count($csvArray); $i++) {
            fputcsv($fp, $csvArray[$i]);
        }

	    $data = file_get_contents('php://output'); 
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
	    header('Content-Length: ' . filesize($data));
	    exit();

	    force_download($name, $data);
	    fclose($fp);
	}
}