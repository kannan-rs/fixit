<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notes extends CI_Controller {

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
		$notesPermission = $this->permissions_lib->getPermissions(FUNCTION_NOTES);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $notesPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Notes List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('projects/model_notes');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_projects');

		$projectId 			= $this->input->post('projectId');
		$taskId 			= $this->input->post('taskId');
		$startRecord 		= $this->input->post('startRecord');
		$count 				= $this->input->post('count');
		$noteId 			= $this->input->post('noteId');
		$viewFor 			= $this->input->post('viewFor');

		$viewFor 			= $viewFor ? $viewFor : "";

		$startRecord 		= $startRecord != "" ? $startRecord : 0;
		$count 				= $count != "" ? $count : 5;

		$projectNotesResponse 		= $this->model_notes->getNotesList($projectId, $taskId, $noteId, $startRecord, $count);

		$count 				= $this->model_notes->count($projectId, $taskId);

		for($i=0; $i < count($projectNotesResponse["notes"]); $i++) {
			$projectNotesResponse["notes"][$i]->created_by_name = $this->model_users->getUsersList($projectNotesResponse["notes"][$i]->created_by)[0]->user_name;
			$projectNotesResponse["notes"][$i]->updated_by_name = $this->model_users->getUsersList($projectNotesResponse["notes"][$i]->updated_by)[0]->user_name;
		}

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission' => $projectPermission
		);
		$project 			= $this->model_projects->getProjectsList($projectParams);

		$paramsNameDescr 	= array(
			'projectId' 		=> $projectId,
			'projectName' 		=> $project[0]->project_name,
			'projectDescr' 		=> $project[0]->project_descr
		);

		$internalLinkParams = array(
			"internalLinkArr" 		=> ["tasks", "documents"],
			"projectId" 			=> $projectId
		);

		$projectNameDescr 		= ($viewFor == "" || $viewFor != "projectViewOne") ? $this->load->view("projects/projectNameDescr", $paramsNameDescr, TRUE) : "";
		$internalLink 			= ($viewFor == "" || $viewFor != "projectViewOne") ? $this->load->view("projects/internalLinks", $internalLinkParams, TRUE) : "";	

		$params = array(
			'project_notes' 	=> isset($projectNotesResponse["notes"]) ? $projectNotesResponse["notes"] : [],
			'count' 			=> $projectNotesResponse["count"],
			'startRecord' 		=> $startRecord,
			'projectId' 		=> $projectId,
			'taskId' 			=> $taskId,
			'projectNameDescr' 	=> $projectNameDescr,
			'internalLink' 		=> $internalLink,
			'viewFor' 			=> $viewFor
		);
		
		echo $this->load->view("projects/notes/viewAll", $params, true);
	}
	
	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$notesPermission = $this->permissions_lib->getPermissions(FUNCTION_NOTES);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $notesPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Create Notes"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$projectId			= $this->input->post('projectId');
		$taskId				= $this->input->post('taskId');
		$viewFor 			= $this->input->post('viewFor');

		$params = array(
			'function'		=>"createFormNotes",
			'projectId' 	=> $projectId,
			'taskId' 		=> $taskId,
			'viewFor' 		=> $viewFor
		);

		echo $this->load->view("projects/notes/createForm", $params, true);
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_NOTES, OPERATION_CREATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('projects/model_notes');
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');
		$this->load->model('mail/model_mail');

		$projectId 	= $this->input->post('projectId');
		$taskId  	= $this->input->post('taskId');

		$data = array(
			'project_id'		=> $projectId,
			'task_id'			=> $taskId,
			'notes_name'		=> $this->input->post('noteName'),
			'notes_content'		=> $this->input->post('noteContent'),
			'created_by'		=> $this->session->userdata('logged_in_user_id'),
			'updated_by'		=> $this->session->userdata('logged_in_user_id'),
			'created_date'		=> date("Y-m-d H:i:s"),
			'updated_date'		=> date("Y-m-d H:i:s")
		);

		$response = $this->model_notes->insert($data);

		if($response["status"] == "success") {
			$response["projectId"] = $this->input->post('projectId');
			$response["taskId"] = $this->input->post('taskId');
		}

		//Project > Permissions for logged in User by role_id
		$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);

		$projectParams = array(
			'projectId' 		=> [$projectId],
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'projectPermission'	=> $projectPermission
		);
		$projects = $this->model_projects->getProjectsList($projectParams);
		$project 	= count($projects) ? $projects[0] : "";

		if($taskId) {
			$tasks = $this->model_tasks->getTask($taskId);
			$taskData = count($tasks) ? $tasks[0] : null;
		}

		$customerId 	= isset($project) && isset($project->customer_id) && !empty($project->customer_id) ? $project->customer_id :  null;
		$contractorId 	= isset($project) && isset($project->contractor_id) && !empty($project->contractor_id) ? $project->contractor_id :  null;
		$adjusterId 	= isset($project) && isset($project->adjuster_id) && !empty($project->adjuster_id) ? $project->adjuster_id :  null;

		if(isset($taskData)) {
			if($taskData->task_owner_id && strpos($taskData->task_owner_id, '-'))
				list($ownerType, $ownerTypeId) = explode('-',  $taskData->task_owner_id);

			if(isset($ownerType) && !empty($ownerType)) {
				$contractorId 	= $ownerType == "contractor" ? $ownerTypeId : null;
				$adjusterId 	= $ownerType == "adjuster" ? $ownerTypeId : null;
			}
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

		$notesParamsFormMail = array(
			'response'			=> $response,
			'projectData'		=> $project,
			'taskData'			=> isset( $taskData ) ? $taskData : null,
			'customerData' 		=> $customerData,
			'contractorsData' 	=> $contractorsData,
			'partnersData' 		=> $partnersData,
			'mail_type' 		=> "create"
		);

		$mail_options = $this->model_mail->generateNotesMailOptions( $notesParamsFormMail );
		
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
		
		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_NOTES, OPERATION_DELETE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('projects/model_notes');
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('service_providers/model_service_providers');
		$this->load->model('adjusters/model_partners');
		$this->load->model('mail/model_mail');

		$noteId = $this->input->post('noteId');

		$projectNotesResponse = $this->model_notes->getNotesList(null, null, $noteId, 0, 5);
		$note 		= isset($projectNotesResponse["notes"]) && count($projectNotesResponse["notes"]) ? $projectNotesResponse["notes"][0] : null;

		$response = $this->model_notes->deleteRecord($noteId);

		if(isset($note) && !empty($note)) {
			$projectId 	= !empty($note->project_id) ? $note->project_id : null;
			$taskId 	= !empty($note->task_id) ? $note->task_id : null;

			//Project > Permissions for logged in User by role_id
			$projectPermission = $this->permissions_lib->getPermissions(FUNCTION_PROJECTS);
		
			$projectParams = array(
				'projectId' 		=> [$projectId],
				'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
				'projectPermission'	=> $projectPermission
			);
			$projects = $this->model_projects->getProjectsList($projectParams);
			$project 	= count($projects) ? $projects[0] : "";

			if($taskId) {
				$tasks = $this->model_tasks->getTask($taskId);
				$taskData = count($tasks) ? $tasks[0] : null;
			}

			$customerId 	= isset($project) && isset($project->customer_id) && !empty($project->customer_id) ? $project->customer_id :  null;
			$contractorId 	= isset($project) && isset($project->contractor_id) && !empty($project->contractor_id) ? $project->contractor_id :  null;
			$adjusterId 	= isset($project) && isset($project->adjuster_id) && !empty($project->adjuster_id) ? $project->adjuster_id :  null;

			if(isset($taskData)) {
				if($taskData->task_owner_id && strpos($taskData->task_owner_id, '-'))
					list($ownerType, $ownerTypeId) = explode('-',  $taskData->task_owner_id);

				if(isset($ownerType) && !empty($ownerType)) {
					$contractorId 	= $ownerType == "contractor" ? $ownerTypeId : null;
					$adjusterId 	= $ownerType == "adjuster" ? $ownerTypeId : null;
				}
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

			$projectParamsFormMail = array(
				'response'			=> $response,
				'projectData'		=> $project,
				'taskData'			=> isset( $taskData ) ? $taskData : null,
				'customerData' 		=> $customerData,
				'contractorsData' 	=> $contractorsData,
				'partnersData' 		=> $partnersData,
				'mail_type' 		=> "delete"
			);

			$mail_options = $this->model_mail->generateNotesMailOptions( $projectParamsFormMail );
			
			$response['mail_content'] = $mail_options;
			for($i = 0; $i < count($mail_options); $i++) {
				$response["mail_error"] = $this->model_mail->sendMail( $mail_options[$i] );
			}
		}

		print_r(json_encode($response));
	}
}