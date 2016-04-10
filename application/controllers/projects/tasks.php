<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends CI_Controller {

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
		$tasksPermission = $this->permissions_lib->getPermissions(FUNCTION_TASKS);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $tasksPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Task List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('projects/model_tasks');
		$this->load->model('projects/model_projects');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_issues');

		$contractors = array();
		$projectId 	= $this->input->post('projectId');
		$viewFor 	= $this->input->post('viewFor');
		$viewFor = $viewFor ? $viewFor : "";

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission'	=> $projectPermission
		);
		$project 			= $this->model_projects->getProjectsList($projectParams);
		$customerDetails 	= $this->model_users->getUserDetailsBySno($project[0]->customer_id);
		$customerName 		= isset($customerDetails) && count($customerDetails) ? $customerDetails[0]->first_name." ".$customerDetails[0]->last_name : "-NA-";


		$tasksResponse 	= $this->model_tasks->getTasksList($projectId);

		$contractorIds 			= $project[0]->contractor_id ? explode(",", $project[0]->contractor_id) : "";
		$contractorsResponse 	= $this->model_service_providers->get_service_provider_list($contractorIds);
		$contractorDB 			= $contractorsResponse["contractors"];

		if (isset($tasksResponse["tasks"])) {
			for($i = 0; $i < count($tasksResponse["tasks"]); $i++) {
				$issuesResponse = $this->model_issues->getIssuesList(array('records' => '', 'projectId' => $projectId, 'taskId' => $tasksResponse["tasks"][$i]->task_id, 'status' => 'open'));
				$issueCount 	= $issuesResponse && $issuesResponse["issues"] ? count($issuesResponse["issues"]) : 0;

				$tasksResponse["tasks"][$i]->issueCount = $issueCount;
			}
		}

		for($i = 0; $i < count($contractorDB); $i++) {
			$contractors[$contractorDB[$i]->id] = $contractorDB[$i];
		}

		$internalLinkParams = array(
			"internalLinkArr" 		=> ["project notes", "documents", "create task"],
			"projectId" 			=> $projectId
		);

		$paramsNameDescr 	= array(
			'projectId' 		=> $projectId,
			'projectName' 		=> $project[0]->project_name,
			'projectDescr' 		=> $project[0]->project_descr
		);

		$projectNameDescr 		= ($viewFor == "" || $viewFor != "projectViewOne") ? $this->load->view("projects/projectNameDescr", $paramsNameDescr, TRUE) : "";
		$internalLink 			= ($viewFor == "" || $viewFor != "projectViewOne") ? $this->load->view("projects/internalLinks", $internalLinkParams, TRUE) : "";

		//Issues > Permissions for logged in User by role_id
		$issuesPermission = $this->permissions_lib->getPermissions(FUNCTION_ISSUES);
		//Notes > Permissions for logged in User by role_id
		$notesPermission 		= $this->permissions_lib->getPermissions( 'notes');

		$params = array(
			'tasks' 			=> isset($tasksResponse["tasks"]) ? $tasksResponse["tasks"] : [],
			'count' 			=> $tasksResponse["count"],
			'projectId' 		=> $projectId,
			'viewFor' 			=> $viewFor,
			'projectNameDescr' 	=> $projectNameDescr,
			'internalLink' 		=> $internalLink,
			'contractors' 		=> $contractors,
			'project_details'	=> $project,
			'customerName' 		=> $customerName,
			'issuesPermission'	=> $issuesPermission,
			'notesPermission'	=> $notesPermission
		);
		
		echo $this->load->view("projects/tasks/viewAll", $params, true);
	}
	
	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$tasksPermission = $this->permissions_lib->getPermissions(FUNCTION_TASKS);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $tasksPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Create task"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('security/model_users');
		$this->load->model('projects/model_projects');

		$projectId = $this->input->post('projectId');
		$viewFor 	= $this->input->post('viewFor');

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission'	=> $projectPermission
		);
		$parent_record = $this->model_projects->getProjectsList($projectParams);

		$paramsNameDescr 	= array(
			'projectId' 		=> $projectId,
			'projectName' 		=> $parent_record[0]->project_name,
			'projectDescr' 		=> $parent_record[0]->project_descr,
		);

		$internalLinkParams = array(
			"internalLinkArr" 		=> ["tasks", "project notes", "documents"],
			"projectId" 			=> $projectId
		);

		$projectNameDescr 		= ($viewFor == "" || $viewFor != "projectViewOne") ? $this->load->view("projects/projectNameDescr", $paramsNameDescr, TRUE) : "";
		$internalLink 			= ($viewFor == "" || $viewFor != "projectViewOne") ? $this->load->view("projects/internalLinks", $internalLinkParams, TRUE) : "";
		
		$params = array(
			'projectId' 		=> $projectId,
			'projectNameDescr' 	=> $projectNameDescr,
			'internalLink' 		=> $internalLink,
			'viewFor' 			=> $viewFor,
			'tasksPermission'	=> $tasksPermission
		);

		echo $this->load->view("projects/tasks/inputForm", $params, true);
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_TASKS, OPERATION_CREATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');
		$this->load->model('mail/model_mail');

		$task_start_date 	= ($this->input->post('task_start_date') == "") ? date("Y-m-d") : $this->input->post('task_start_date');
		$task_end_date 		= ($this->input->post('task_end_date') == "" ) ?  $task_start_date : $this->input->post('task_end_date');

		$projectId 	= $this->input->post('parentId');
		$ownerId 	= $this->input->post('task_owner_id');

		if($ownerId && strpos($ownerId, '-'))
			list($ownerType, $ownerTypeId) = explode('-', $ownerId);

		$data = array(
		   'project_id' 				=> $projectId,
		   'task_name' 					=> $this->input->post('task_name'),
		   'task_desc' 					=> $this->input->post('task_desc'),
		   'task_start_date' 			=> $task_start_date,
		   'task_end_date' 				=> $task_end_date,
		   'task_status' 				=> $this->input->post('task_status'),
		   'task_owner_id' 				=> $ownerId,
		   'task_percent_complete' 		=> $this->input->post('task_percent_complete'),
		   'task_dependency' 			=> $this->input->post('task_dependency'),
		   'task_trade_type' 			=> $this->input->post('task_trade_type'),
		   'created_by' 				=> $this->session->userdata('logged_in_user_id'),
		   'created_on' 				=> date("Y-m-d H:i:s"),
		   'updated_by'					=> $this->session->userdata('logged_in_user_id'),
		   'updated_on' 				=> date("Y-m-d H:i:s")
		);

		$response = $this->model_tasks->insert($data);

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);
		
		/* Project Details */
		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission' => $projectPermission
		);

		$projects 	= $this->model_projects->getProjectsList($projectParams);
		$project 	= count($projects) ? $projects[0] : null;

		$customerId 	= isset($project) && isset($project->customer_id) && !empty($project->customer_id) ? $project->customer_id :  null;
		$contractorId 	= null;
		$adjusterId 	= null;

		if(isset($ownerType) && !empty($ownerType)) {
			$contractorId 	= $ownerType == "contractor" ? $ownerTypeId : null;
			$adjusterId 	= $ownerType == "adjuster" ? $ownerTypeId : null;
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
			'taskData'			=> $data,
			'projectData' 		=> $project,
			'customerData' 		=> $customerData,
			'contractorsData' 	=> $contractorsData,
			'partnersData' 		=> $partnersData,
			'mail_type' 		=> "create"
		);

		$mail_options = $this->model_mail->generateTaskMailOptions( $taskParamsFormMail );
		
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
		$tasksPermission = $this->permissions_lib->getPermissions(FUNCTION_TASKS);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_UPDATE, $tasksPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "update task"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_projects');


		$record 	= $this->input->post('taskId');
		$viewFor 	= $this->input->post('viewFor');
		$viewFor 	= $viewFor ? $viewFor : "";


		$tasks = $this->model_tasks->getTask($record);

		$projectId 		= $tasks[0]->project_id;

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name'	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission' => $projectPermission
		);
		$parent_record 	= $this->model_projects->getProjectsList($projectParams);

		$paramsNameDescr 	= array(
			'projectId' 		=> $projectId,
			'projectName' 		=> $parent_record[0]->project_name,
			'projectDescr' 		=> $parent_record[0]->project_descr,
		);

		$internalLinkParams = array(
			"internalLinkArr" 		=> ["tasks", "project notes", "documents"],
			"projectId" 			=> $projectId
		);

		$projectNameDescr 		= ($viewFor == "" || $viewFor != "projectViewOne") ? $this->load->view("projects/projectNameDescr", $paramsNameDescr, TRUE) : "";
		$internalLink 			= ($viewFor == "" || $viewFor != "projectViewOne") ? $this->load->view("projects/internalLinks", $internalLinkParams, TRUE) : "";

		$params = array(
			'tasks' 			=>$tasks,
			'users' 			=> $this->model_users->getUsersList(),
			'projectId' 		=> $projectId,
			'projectNameDescr' 	=> $projectNameDescr,
			'internalLink' 		=> $internalLink,
			'viewFor' 			=> $viewFor
		);
		
		echo $this->load->view("projects/tasks/inputForm", $params, true);
	}

	public function update() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_TASKS, OPERATION_UPDATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');
		$this->load->model('mail/model_mail');

		$record 	= 	$this->input->post('task_id');
		$ownerId 	= $this->input->post('task_owner_id');

		if($ownerId && strpos($ownerId, '-'))
			list($ownerType, $ownerTypeId) = explode('-', $ownerId);

		$task_start_date 			= ($this->input->post('task_start_date') == "") ? date("Y-m-d") : $this->input->post('task_start_date');
		$task_end_date 				= ($this->input->post('task_end_date') == "" ) ?  $task_start_date : $this->input->post('task_end_date');

		$data = array(
		   'task_name' 					=> $this->input->post('task_name'),
		   'task_desc' 					=> $this->input->post('task_desc'),
		   'task_start_date' 			=> $task_start_date,
		   'task_end_date' 				=> $task_end_date,
		   'task_status' 				=> $this->input->post('task_status'),
		   'task_owner_id' 				=> $ownerId,
		   'task_percent_complete'		=> $this->input->post('task_percent_complete'),
		   'task_dependency'			=> $this->input->post('task_dependency'),
		   'task_trade_type'			=> $this->input->post('task_trade_type'),
		   'updated_by'					=> $this->session->userdata('logged_in_user_id'),
		   'updated_on'					=> date("Y-m-d H:i:s")
		);

		$response = $this->model_tasks->update($data, $record);

		$tasks = $this->model_tasks->getTask($record);
		$taskData = count($tasks) ? $tasks[0] : null;

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$projectParams = array(
			'projectId' 		=> [$taskData->project_id],
			'role_disp_name'	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission'	=> $projectPermission
		);

		$projects 	= $this->model_projects->getProjectsList($projectParams);
		$project 	= count($projects) ? $projects[0] : null;

		$customerId 	= isset($project) && isset($project->customer_id) && !empty($project->customer_id) ? $project->customer_id :  null;
		$contractorId 	= null;
		$adjusterId 	= null;

		if(isset($ownerType) && !empty($ownerType)) {
			$contractorId 	= $ownerType == "contractor" ? $ownerTypeId : null;
			$adjusterId 	= $ownerType == "adjuster" ? $ownerTypeId : null;
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
			'mail_type' 		=> "update"
		);

		$mail_options = $this->model_mail->generateTaskMailOptions( $taskParamsFormMail );
		
		$response['mail_content'] = $mail_options;
		for($i = 0; $i < count($mail_options); $i++) {
			$response["mail_error"] = $this->model_mail->sendMail( $mail_options[$i] );
		}

		print_r(json_encode($response));
	}

	public function deleteRecord() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_TASKS, OPERATION_DELETE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');
		$this->load->model('mail/model_mail');

		$task_id = $this->input->post('task_id');
		$project_id = $this->input->post('project_id');

		$tasks = $this->model_tasks->getTask($task_id);
		$taskData = count($tasks) ? $tasks[0] : null;

		$ownerId = $taskData->task_owner_id;

		if($ownerId && strpos($ownerId, '-'))
			list($ownerType, $ownerTypeId) = explode('-', $ownerId);

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$projectParams = array(
			'projectId' 		=> [$taskData->project_id],
			'role_disp_name'	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission'	=> $projectPermission
		);

		$projects 	= $this->model_projects->getProjectsList($projectParams);
		$project 	= count($projects) ? $projects[0] : null;

		$customerId 	= isset($project) && isset($project->customer_id) && !empty($project->customer_id) ? $project->customer_id :  null;
		$contractorId 	= null;
		$adjusterId 	= null;

		if(isset($ownerType) && !empty($ownerType)) {
			$contractorId 	= $ownerType == "contractor" ? $ownerTypeId : null;
			$adjusterId 	= $ownerType == "adjuster" ? $ownerTypeId : null;
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

		$response = $this->model_tasks->deleteRecord($task_id, $project_id);

		$taskParamsFormMail = array(
			'response'			=> $response,
			'taskData'			=> $taskData,
			'projectData' 		=> $project,
			'customerData' 		=> $customerData,
			'contractorsData' 	=> $contractorsData,
			'partnersData' 		=> $partnersData,
			'mail_type' 		=> "delete"
		);

		$mail_options = $this->model_mail->generateTaskMailOptions( $taskParamsFormMail );
		
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
		
		//Project > Permissions for logged in User by role_id
		$tasksPermission = $this->permissions_lib->getPermissions(FUNCTION_TASKS);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $tasksPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Task List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_projects');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('projects/model_issues');


		$record 	= $this->input->post('taskId');
		$viewFor 	= $this->input->post('viewFor');
		$viewFor 	= $viewFor ? $viewFor : "";

		$tasks = $this->model_tasks->getTask($record);

		//Issues > Permissions for logged in User by role_id
		$issuesPermission = $this->permissions_lib->getPermissions(FUNCTION_ISSUES);

		if (in_array(OPERATION_VIEW, $issuesPermission['operation']) && isset($tasks)) {
			for($i = 0; $i < count($tasks); $i++) {
				$issuesResponse = $this->model_issues->getIssuesList(array('records' => '', 'projectId' => $tasks[$i]->project_id, 'taskId' => $tasks[$i]->task_id, 'status' => 'open'));
				$issueCount 	= $issuesResponse && $issuesResponse["issues"] ? count($issuesResponse["issues"]) : 0;

				$tasks[$i]->issueCount = $issueCount;
			}
		}

		$projectId 		= $tasks[0]->project_id;

		//Service Provider > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER);

		if(in_array(OPERATION_VIEW, $contractorPermission['operation'])) {
			$task_owner_id = explode("-", $tasks[0]->task_owner_id);
			if(count($task_owner_id) && count($task_owner_id) > 1 ) {
				$task_owner_id = $task_owner_id[1];
			} else {
				$task_owner_id = "";
			}
			$contractorIds 	= !empty($task_owner_id) && strlen ( $task_owner_id ) ? explode(",", $task_owner_id) : "";
			if($contractorIds) {
				$contractorsResponse 	= $this->model_service_providers->get_service_provider_list($contractorIds);
				$contractors 	= $contractorsResponse["contractors"];
			}
		}
		$created_by 	= $this->model_users->getUsersList($tasks[0]->created_by)[0]->user_name;
		$updated_by 	= $this->model_users->getUsersList($tasks[0]->updated_by)[0]->user_name;

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name'	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission'	=> $projectPermission
		);
		$parent_record 	= $this->model_projects->getProjectsList($projectParams);

		$paramsNameDescr 	= array(
			'projectId' 		=> $projectId,
			'projectName' 		=> $parent_record[0]->project_name,
			'projectDescr' 		=> $parent_record[0]->project_descr,
		);

		$internalLinkParams = array(
			"internalLinkArr" 		=> ["tasks", "task notes", "documents"],
			"projectId" 			=> $projectId,
			'taskId' 				=> $tasks[0]->task_id
		);

		$projectNameDescr 		= ($viewFor == "" || $viewFor != "projectViewOne") ? $this->load->view("projects/projectNameDescr", $paramsNameDescr, TRUE) : "";
		$internalLink 			= ($viewFor == "" || $viewFor != "projectViewOne") ? $this->load->view("projects/internalLinks", $internalLinkParams, TRUE) : "";

		$params = array(
			'tasks' 				=>$tasks,
			'created_by' 			=> $created_by,
			'updated_by' 			=> $updated_by,
			'projectId' 			=> $projectId,
			'projectNameDescr' 		=> $projectNameDescr,
			'internalLink' 			=> $internalLink,
			'viewFor' 				=> $viewFor,
			'contractors' 			=> isset($contractors) ? $contractors : [],
			'projectPermission'		=> $projectPermission,
			'issuesPermission'		=> $issuesPermission,
			'contractorPermission'	=> $contractorPermission,
			'tasksPermission'		=> $tasksPermission
		);
		
		echo $this->load->view("projects/tasks/viewOne", $params, true);
	}	
}