<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Issues extends CI_Controller {

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

		//Issues > Permissions for logged in User by role_id
		$issuesPermission 		= $this->permissions_lib->getPermissions(FUNCTION_ISSUES);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $issuesPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Issues List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('projects/model_issues');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');

		$openAs		 			= $this->input->post('openAs');
		$popupType		 		= $openAs == "popup" ? "2" : "0";
		$projectId		 		= $this->input->post('projectId');
		$taskId		 			= $this->input->post('taskId');
		
		$issuesResponse = $this->model_issues->getIssuesList( array('records' => '', 'projectId' => $projectId, 'taskId' => $taskId, 'status' => 'all') );

		$issues = $issuesResponse["issues"];

		$assigneeDetails = array();
		
		for($i=0; $i < count($issues); $i++) {
			$issues[$i]->created_by_name = $this->model_users->getUsersList($issues[$i]->created_by)[0]->user_name;
			$issues[$i]->updated_by_name = $this->model_users->getUsersList($issues[$i]->updated_by)[0]->user_name;

			$assigneeDetails[$issues[$i]->issue_id] = array();

			if($issues[$i]->assigned_to_user_type == "customer") {
				if(!empty($issues[$i]->assigned_to_user_id)) {
					$userDetails = $this->model_users->getUsersList( $issues[$i]->assigned_to_user_id );

					if(count($userDetails)) {
						$customerDetails = $this->model_users->getUserDetailsByEmail( $userDetails[0]->user_name );
						if(count($customerDetails)) {
							$assigneeDetails[$issues[$i]->issue_id]["customerDetails"] = $customerDetails;
							$assigneeDetails[$issues[$i]->issue_id]["customerDetails"]["user_sno"] = $userDetails[0]->sno;
							$assigneeDetails[$issues[$i]->issue_id]["customerDetails"]["role_id"] = $userDetails[0]->role_id;
							$assigneeDetails[$issues[$i]->issue_id]["customerDetails"]["account_status"] = $userDetails[0]->status;
						}
					}
				}
			}
			else if($issues[$i]->assigned_to_user_type == "contractor" ) {
				if(!empty($issues[$i]->assigned_to_user_id)) {
					$contractorIdArr = explode(",", $issues[$i]->assigned_to_user_id);
					$contractorsResponse = $this->model_service_providers->get_service_provider_list($contractorIdArr,"","", 1);
					$assigneeDetails[$issues[$i]->issue_id]["contractorDetails"] = $contractorsResponse["contractors"];
				}
			}
			/* else if($issues[$i]->assigned_to_user_type == "adjuster") {
				if(!empty($issues[$i]->assigned_to_user_id)) {
					$adjusterIdArr = explode(",", $issues[$i]->assigned_to_user_id);
					$partnersResponse = $this->model_partners->getPartnersList($adjusterIdArr);
					 $assigneeDetails["adjusterDetails"] = $partnersResponse["partners"];
				}
			}*/
		}

		$params = array(
			'issues'			=> $issues,
			'openAs' 			=> $openAs,
			'popupType' 		=> $popupType,
			'projectId' 		=> $projectId,
			'taskId' 			=> $taskId,
			'issuesPermission'	=> $issuesPermission,
			'assigneeDetails' 	=> $assigneeDetails
		);
		
		echo $this->load->view("projects/issues/viewAll", $params, true);
	}
	
	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Issues > Permissions for logged in User by role_id
		$issuesPermission 		= $this->permissions_lib->getPermissions(FUNCTION_ISSUES);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $issuesPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Create Issues"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";
		$projectId 	= $this->input->post('projectId') ? $this->input->post('projectId') : "";
		$taskId 	= $this->input->post('taskId') ? $this->input->post('taskId') : "";
		
		$params = array(
			'userType' 		=> $this->session->userdata('logged_in_role_id'),
			'openAs' 		=> $openAs,
			'popupType' 	=> $popupType,
			'projectId' 	=> $projectId,
			'taskId' 		=> $taskId,
			'formType' 		=> "create"
		);

		echo $this->load->view("projects/issues/inputForm", $params, true);
	}


	public function editForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Issues > Permissions for logged in User by role_id
		$issuesPermission 		= $this->permissions_lib->getPermissions(FUNCTION_ISSUES);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_UPDATE, $issuesPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "update issue"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('projects/model_issues');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');

		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";
		$projectId 	= $this->input->post('projectId') ? $this->input->post('projectId') : "";
		$taskId 	= $this->input->post('taskId') ? $this->input->post('taskId') : "";
		$issueId 	= $this->input->post('issueId') ? $this->input->post('issueId') : "";

		$issuesResponse 	= $this->model_issues->getIssuesList(array('records' => $issueId, 'status' => 'open'));
		$issues 			= $issuesResponse["issues"];

		$assigneeDetails = array();
		
		for($i=0; $i < count($issues); $i++) {
			if($issues[$i]->assigned_to_user_type == "customer") {
				if(!empty($issues[$i]->assigned_to_user_id)) {
					$userDetails = $this->model_users->getUsersList( $issues[$i]->assigned_to_user_id );

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
			} else if($issues[$i]->assigned_to_user_type == "contractor" ) {
				if(!empty($issues[$i]->assigned_to_user_id)) {
					$contractorIdArr = explode(",", $issues[$i]->assigned_to_user_id);
					$contractorsResponse = $this->model_service_providers->get_service_provider_list($contractorIdArr);
					$assigneeDetails["contractorDetails"] = $contractorsResponse["contractors"];
				}
			} else if($issues[$i]->assigned_to_user_type == "adjuster") {
				if(!empty($issues[$i]->assigned_to_user_id)) {
					$adjusterIdArr = explode(",", $issues[$i]->assigned_to_user_id);
					$partnersResponse = $this->model_partners->getPartnersList($adjusterIdArr);
					 $assigneeDetails["adjusterDetails"] = $partnersResponse["partners"];
				}
			}
		}

		$params = array(
			'issues'			=> $issues,
			'userType' 			=> $this->session->userdata('logged_in_role_id'),
			'issueId' 			=> $issueId,
			'openAs' 			=> $openAs,
			'popupType' 		=> $popupType,
			'assigneeDetails' 	=> $assigneeDetails,
			'projectId' 		=> $projectId,
			'taskId' 			=> $taskId,
		);

		echo $this->load->view("projects/issues/inputForm", $params, true);
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_ISSUES, OPERATION_CREATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');
		$this->load->model('projects/model_issues');
		$this->load->model('mail/model_mail');
		
		$assigned_to_service_provider_id	= $this->input->post("assignedToContractorId");
		$assignedToAdjusterId 	= $this->input->post("assignedToAdjusterId");
		$assignedToCustomerId 	= $this->input->post("assignedToCustomerId");

		$projectId 			= $this->input->post("issueProjectId");
		$taskId 			= $this->input->post("issueTaskId");

		$assignedToUserType = $this->input->post("assignedToUserType");

		$assignedToUserId = ($assignedToUserType == "customer") ? $assignedToCustomerId : ($assignedToUserType == "contractor" ? $assigned_to_service_provider_id : ($assignedToUserType == "adjuster" ? $assignedToAdjusterId : ""));
		
		$data = array(
			'issue_name' 			=> $this->input->post("issueName"),
			'issue_desc' 			=> $this->input->post("issueDescr"),
			'project_id' 			=> $projectId,
			'task_id' 				=> $taskId,
			'assigned_to_user_type'	=> $assignedToUserType,
			'assigned_to_user_id' 	=> $assignedToUserId,
			'issue_from_date' 		=> $this->input->post("issueFromdate"),
			'assigned_date' 		=> date("Y-m-d"),
			'status' 				=> $this->input->post("issueStatus"),
			'notes' 				=> $this->input->post("issueNotes"),
			'created_by'			=> $this->session->userdata('logged_in_user_id'),
			'updated_by'			=> $this->session->userdata('logged_in_user_id'),
			'created_on'			=> date("Y-m-d H:i:s"),
			'updated_on'			=> date("Y-m-d H:i:s")
		);

		$response = $this->model_issues->insert($data);

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission'	=> $projectPermission
		);

		$projects = $this->model_projects->getProjectsList($projectParams);
		$project 	= count($projects) ? $projects[0] : "";

		$tasks = $this->model_tasks->getTask($taskId);
		$taskData = count($tasks) ? $tasks[0] : null;


		$customerId 	= isset($project) && isset($project->customer_id) && !empty($project->customer_id) ? $project->customer_id :  null;
		$contractorId 	= null;
		$adjusterId 	= null;

		if(isset($assignedToUserType) && !empty($assignedToUserType)) {
			$contractorId 	= $assignedToUserType == "contractor" ? $assignedToUserId : null;
			$adjusterId 	= $assignedToUserType == "adjuster" ? $assignedToUserId : null;
		}

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

		$issueParamsFormMail = array(
			'response'			=> $response,
			'taskData'			=> $taskData,
			'projectData' 		=> $project,
			'customerData' 		=> $customerData,
			'contractorsData' 	=> $contractorsData,
			'partnersData' 		=> $partnersData,
			'mail_type' 		=> "create"
		);

		$mail_options = $this->model_mail->generateIssueMailOptions( $issueParamsFormMail );

		$response['mail_content'] = $mail_options;
		for($i = 0; $i < count($mail_options); $i++) {
			$response["mail_error"] = $this->model_mail->sendMail( $mail_options[$i] );
		}

		print_r(json_encode($response));
	}

	public function viewOne() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Issues > Permissions for logged in User by role_id
		$issuesPermission 		= $this->permissions_lib->getPermissions(FUNCTION_ISSUES);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $issuesPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Issue details"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('projects/model_issues');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');


		$issueId 			= $this->input->post('issueId');
		$openAs		 		= $this->input->post('openAs');
		$popupType		 	= $this->input->post('popupType');
		$issuesResponse 	= $this->model_issues->getIssuesList(array('records' => $issueId, 'status' => 'open'));

		$issues = $issuesResponse["issues"];

		$assigneeDetails = array();
		
		for($i=0; $i < count($issues); $i++) {
			$issues[$i]->created_by_name = $this->model_users->getUsersList($issues[$i]->created_by)[0]->user_name;
			$issues[$i]->updated_by_name = $this->model_users->getUsersList($issues[$i]->updated_by)[0]->user_name;

			if($issues[$i]->assigned_to_user_type == "customer") {
				if(!empty($issues[$i]->assigned_to_user_id)) {
					$userDetails = $this->model_users->getUsersList( $issues[$i]->assigned_to_user_id );

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
			}
			else if($issues[$i]->assigned_to_user_type == "contractor" ) {
				if(!empty($issues[$i]->assigned_to_user_id)) {
					$contractorIdArr = explode(",", $issues[$i]->assigned_to_user_id);
					$contractorsResponse = $this->model_service_providers->get_service_provider_list($contractorIdArr);
					$assigneeDetails["contractorDetails"] = $contractorsResponse["contractors"];
				}
			}
			/* else if($issues[$i]->assigned_to_user_type == "adjuster") {
				if(!empty($issues[$i]->assigned_to_user_id)) {
					$adjusterIdArr = explode(",", $issues[$i]->assigned_to_user_id);
					$partnersResponse = $this->model_partners->getPartnersList($adjusterIdArr);
					 $assigneeDetails["adjusterDetails"] = $partnersResponse["partners"];
				}
			}*/
		}

		$params = array(
			'issues'			=> $issues,
			'userType' 			=> $this->session->userdata('logged_in_role_id'),
			'issueId' 			=> $issueId,
			'openAs' 			=> $openAs,
			'popupType' 		=> $popupType,
			'assigneeDetails' 	=> $assigneeDetails
		);
		
		echo $this->load->view("projects/issues/viewOne", $params, true);
	}

	public function update() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_ISSUES, OPERATION_UPDATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');
		$this->load->model('projects/model_issues');
		$this->load->model('mail/model_mail');

		$issueId 				= $this->input->post('issueId');
		$assigned_to_service_provider_id	= $this->input->post("assignedToContractorId");
		$assignedToAdjusterId 	= $this->input->post("assignedToAdjusterId");
		$assignedToCustomerId 	= $this->input->post("assignedToCustomerId");

		$assignedToUserType = $this->input->post("assignedToUserType");

		$assignedToUserId = ($assignedToUserType == "customer") ? $assignedToCustomerId : ($assignedToUserType == "contractor" ? $assigned_to_service_provider_id : ($assignedToUserType == "adjuster" ? $assignedToAdjusterId : ""));

		$data = array(
			'issue_name' 				=> $this->input->post('issueName'),
			'issue_desc' 				=> $this->input->post('issueDescr'),
			'assigned_to_user_type'		=> $this->input->post('assignedToUserType'),
			'assigned_to_user_id' 		=> $assignedToUserId,
			'issue_from_date' 			=> $this->input->post('issueFromdate'),
			'status' 					=> $this->input->post('issueStatus'),
			'notes' 					=> $this->input->post('issueNotes'),
			'updated_by'				=> $this->session->userdata('logged_in_user_id'),
			'updated_on'				=> date("Y-m-d H:i:s")
		);

		$assignedToUserTypeDB	= $this->input->post("assignedToUserTypeDB");
		$assignedToUserDB 		= $this->input->post("assignedToUserDB");

		if($assignedToUserTypeDB != $this->input->post('assignedToUserType') || $assignedToUserId != $assignedToUserDB) {
			$data["assigned_date"] = date("Y-m-d");
		}

		$response = $this->model_issues->update($data, $issueId);

		$issuesResponse = $this->model_issues->getIssuesList(array('records' => $issueId, 'status' => 'all'));
		$issue 			= isset($issuesResponse["issues"]) && is_array($issuesResponse["issues"]) && count($issuesResponse["issues"]) ? $issuesResponse["issues"][0] : null;


		if($issue) {
			//Project > Permissions for logged in User by role_id
			$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

			$projectParams = array(
				'projectId' 		=> [$issue->project_id],
				'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
				'projectPermission'	=> $projectPermission
			);

			$projects = $this->model_projects->getProjectsList($projectParams);
			$project 	= count($projects) ? $projects[0] : "";

			$tasks = $this->model_tasks->getTask($issue->task_id);
			$taskData = count($tasks) ? $tasks[0] : null;


			$customerId 	= isset($project) && isset($project->customer_id) && !empty($project->customer_id) ? $project->customer_id :  null;
			$contractorId 	= null;
			$adjusterId 	= null;

			if(isset($assignedToUserType) && !empty($assignedToUserType)) {
				$contractorId 	= $assignedToUserType == "contractor" ? $assignedToUserId : null;
				$adjusterId 	= $assignedToUserType == "adjuster" ? $assignedToUserId : null;
			}

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

			$issueParamsFormMail = array(
				'response'			=> $response,
				'taskData'			=> $taskData,
				'projectData' 		=> $project,
				'customerData' 		=> $customerData,
				'contractorsData' 	=> $contractorsData,
				'partnersData' 		=> $partnersData,
				'mail_type' 		=> "update"
			);

			$mail_options = $this->model_mail->generateIssueMailOptions( $issueParamsFormMail );

			$response['mail_content'] = $mail_options;
			for($i = 0; $i < count($mail_options); $i++) {
				$response["mail_error"] = $this->model_mail->sendMail( $mail_options[$i] );
			}
		}

		print_r(json_encode($response));
	}

	public function deleteRecord() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}
		
		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_ISSUES, OPERATION_DELETE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');
		$this->load->model('projects/model_issues');
		$this->load->model('mail/model_mail');

		$issueId = $this->input->post('issueId');

		$issuesResponse 	= $this->model_issues->getIssuesList(array('records' => $issueId, 'status' => 'open'));
		$issue = $issuesResponse["issues"][0];

		$response = $this->model_issues->deleteRecord($issueId);

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$projectParams = array(
			'projectId' 		=> [$issue->project_id],
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission'	=> $projectPermission
		);
		$projects = $this->model_projects->getProjectsList($projectParams);
		$project 	= count($projects) ? $projects[0] : "";

		$tasks = $this->model_tasks->getTask($issue->task_id);
		$taskData = count($tasks) ? $tasks[0] : null;


		$customerId 	= isset($project) && isset($project->customer_id) && !empty($project->customer_id) ? $project->customer_id :  null;
		$contractorId 	= null;
		$adjusterId 	= null;

		if(isset($assignedToUserType) && !empty($assignedToUserType)) {
			$contractorId 	= $assignedToUserType == "contractor" ? $assignedToUserId : null;
			$adjusterId 	= $assignedToUserType == "adjuster" ? $assignedToUserId : null;
		}

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

		$taskParamsFormMail = array(
			'response'			=> $response,
			'taskData'			=> $taskData,
			'projectData' 		=> $project,
			'customerData' 		=> $customerData,
			'contractorsData' 	=> $contractorsData,
			'partnersData' 		=> $partnersData,
			'mail_type' 		=> "delete"
		);

		$mail_options = $this->model_mail->generateIssueMailOptions( $taskParamsFormMail );

		$response['mail_content'] = $mail_options;
		for($i = 0; $i < count($mail_options); $i++) {
			$response["mail_error"] = $this->model_mail->sendMail( $mail_options[$i] );
		}

		print_r(json_encode($response));	
	}
}