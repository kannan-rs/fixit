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
		$this->load->model('projects/model_issues');

		$openAs		 			= $this->input->post('openAs');
		$popupType		 		= $openAs == "popup" ? "2" : "0";
		$projectId		 		= $this->input->post('projectId');
		$taskId		 			= $this->input->post('taskId');
		
		$issuesResponse = $this->model_issues->getIssuesList( array('records' => '', 'projectId' => $projectId, 'taskId' => $taskId, 'status' => 'all') );

		$params = array(
			'issues'	=>$issuesResponse["issues"],
			'openAs' 	=> $openAs,
			'popupType' => $popupType,
			'projectId' => $projectId,
			"taskId" 	=> $taskId
		);
		
		echo $this->load->view("projects/issues/viewAll", $params, true);
	}
	
	public function createForm() {
		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";
		$projectId 	= $this->input->post('projectId') ? $this->input->post('projectId') : "";
		$taskId 	= $this->input->post('taskId') ? $this->input->post('taskId') : "";
		
		$params = array(
			'userType' 		=> $this->session->userdata('account_type'),
			'openAs' 		=> $openAs,
			'popupType' 	=> $popupType,
			'projectId' 	=> $projectId,
			'taskId' 		=> $taskId,
			'formType' 		=> "create"
		);

		echo $this->load->view("projects/issues/inputForm", $params, true);
	}


	public function editForm() {
		$this->load->model('projects/model_issues');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_partners');

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
							$assigneeDetails["customerDetails"]["account_type"] = $userDetails[0]->account_type;
							$assigneeDetails["customerDetails"]["account_status"] = $userDetails[0]->status;
						}
					}
				}
			} else if($issues[$i]->assigned_to_user_type == "contractor" ) {
				if(!empty($issues[$i]->assigned_to_user_id)) {
					$contractorIdArr = explode(",", $issues[$i]->assigned_to_user_id);
					$contractorsResponse = $this->model_contractors->getContractorsList($contractorIdArr);
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
			'userType' 			=> $this->session->userdata('account_type'),
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
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_partners');
		$this->load->model('projects/model_issues');
		$this->load->model('mail/model_mail');
		
		$assignedToContractorId	= $this->input->post("assignedToContractorId");
		$assignedToAdjusterId 	= $this->input->post("assignedToAdjusterId");
		$assignedToCustomerId 	= $this->input->post("assignedToCustomerId");

		$projectId 			= $this->input->post("issueProjectId");
		$taskId 			= $this->input->post("issueTaskId");

		$assignedToUserType = $this->input->post("assignedToUserType");

		$assignedToUserId = ($assignedToUserType == "customer") ? $assignedToCustomerId : ($assignedToUserType == "contractor" ? $assignedToContractorId : ($assignedToUserType == "adjuster" ? $assignedToAdjusterId : ""));
		
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
			'created_by'			=> $this->session->userdata('user_id'),
			'updated_by'			=> $this->session->userdata('user_id'),
			'created_on'			=> date("Y-m-d H:i:s"),
			'updated_on'			=> date("Y-m-d H:i:s")
		);

		$response = $this->model_issues->insert($data);

		$projects = $this->model_projects->getProjectsList($projectId);
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
		$this->load->model('projects/model_issues');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_partners');


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
							$assigneeDetails["customerDetails"]["account_type"] = $userDetails[0]->account_type;
							$assigneeDetails["customerDetails"]["account_status"] = $userDetails[0]->status;
						}
					}
				}
			} else if($issues[$i]->assigned_to_user_type == "contractor" ) {
				if(!empty($issues[$i]->assigned_to_user_id)) {
					$contractorIdArr = explode(",", $issues[$i]->assigned_to_user_id);
					$contractorsResponse = $this->model_contractors->getContractorsList($contractorIdArr);
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
			'userType' 			=> $this->session->userdata('account_type'),
			'issueId' 			=> $issueId,
			'openAs' 			=> $openAs,
			'popupType' 		=> $popupType,
			'assigneeDetails' 	=> $assigneeDetails
		);
		
		echo $this->load->view("projects/issues/viewOne", $params, true);
	}

	public function update() {
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_partners');
		$this->load->model('projects/model_issues');
		$this->load->model('mail/model_mail');

		$issueId 				= $this->input->post('issueId');
		$assignedToContractorId	= $this->input->post("assignedToContractorId");
		$assignedToAdjusterId 	= $this->input->post("assignedToAdjusterId");
		$assignedToCustomerId 	= $this->input->post("assignedToCustomerId");

		$assignedToUserType = $this->input->post("assignedToUserType");

		$assignedToUserId = ($assignedToUserType == "customer") ? $assignedToCustomerId : ($assignedToUserType == "contractor" ? $assignedToContractorId : ($assignedToUserType == "adjuster" ? $assignedToAdjusterId : ""));

		$data = array(
			'issue_name' 				=> $this->input->post('issueName'),
			'issue_desc' 				=> $this->input->post('issueDescr'),
			'assigned_to_user_type'		=> $this->input->post('assignedToUserType'),
			'assigned_to_user_id' 		=> $assignedToUserId,
			'issue_from_date' 			=> $this->input->post('issueFromdate'),
			'status' 					=> $this->input->post('issueStatus'),
			'notes' 					=> $this->input->post('issueNotes'),
			'updated_by'				=> $this->session->userdata('user_id'),
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
			$projects = $this->model_projects->getProjectsList($issue->project_id);
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
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_partners');
		$this->load->model('projects/model_issues');
		$this->load->model('mail/model_mail');

		$issueId = $this->input->post('issueId');

		$issuesResponse 	= $this->model_issues->getIssuesList(array('records' => $issueId, 'status' => 'open'));
		$issue = $issuesResponse["issues"][0];

		$response = $this->model_issues->deleteRecord($issueId);

		$projects = $this->model_projects->getProjectsList($issue->project_id);
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