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

	public function getList() {
		$this->load->model('projects/model_issues');

		$records = [];
		$records = explode(",", $this->input->post("records"));
		

		$issuesResponse = $this->model_issues->getIssuesList( $records );

		print_r(json_encode($issuesResponse));
	}

	public function viewAll() {
		$this->load->model('projects/model_issues');

		$openAs		 			= $this->input->post('openAs');
		$popupType		 		= $this->input->post('popupType');
		$projectId		 		= $this->input->post('projectId');
		$taskId		 			= $this->input->post('taskId');
		
		$issuesResponse = $this->model_issues->getIssuesList("", $projectId);

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
		$this->load->model('security/model_users');

		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";
		$projectId 	= $this->input->post('projectId') ? $this->input->post('projectId') : "";
		$taskId 	= $this->input->post('taskId') ? $this->input->post('taskId') : "";

		$addressParams = array(
			'forForm' 		=> "create_issue_form"
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);
		
		$params = array(
			'users' 		=> $this->model_users->getUsersList(),
			'userType' 		=> $this->session->userdata('account_type'),
			'openAs' 		=> $openAs,
			'addressFile' 	=> $addressFile,
			'popupType' 	=> $popupType,
			'projectId' 	=> $projectId,
			'taskId' 		=> $taskId
		);

		echo $this->load->view("projects/issues/createForm", $params, true);
	}

	public function add() {
		$this->load->model('projects/model_issues');
		$this->load->model('mail/model_mail');
		
		$assignedToContractorId	= $this->input->post("assignedToContractorId");
		$assignedToAdjusterId 	= $this->input->post("assignedToAdjusterId");
		$assignedToCustomerId 	= $this->input->post("assignedToCustomerId");

		$assignedToUserType = $this->input->post("assignedToUserType");


		$assignedToUserId = ($assignedToUserType == "customer") ? $assignedToCustomerId : ($assignedToUserType == "contractor" ? $assignedToContractorId : ($assignedToUserType == "adjuster" ? $assignedToAdjusterId : ""));
		

		$data = array(
			'issue_name' 			=> $this->input->post("issueName"),
			'issue_desc' 			=> $this->input->post("issueDescr"),
			'project_id' 			=> $this->input->post("issueProjectId"),
			'task_id' 				=> $this->input->post("issueTaskId"),
			'assigned_to_user_type'	=> $this->input->post("assignedToUserType"),
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

		$insert_issue = $this->model_issues->insert($data);

		/*$issueCompanyParamsFormMail = array(
			'response'				=> $insert_issue,
			'issueData'		=> $data
		);

		$mail_options = $this->model_mail->generateCreateIssueCompanyMailOptions( $issueCompanyParamsFormMail );
		
		$this->model_mail->sendMail( $mail_options );*/
		print_r(json_encode($insert_issue));
	}

	public function viewOne() {
		$this->load->model('projects/model_issues');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_partners');


		$issueId 			= $this->input->post('issueId');
		$openAs		 		= $this->input->post('openAs');
		$popupType		 	= $this->input->post('popupType');
		$issuesResponse 	= $this->model_issues->getIssuesList($issueId);

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

	public function editForm() {
		$this->load->model('projects/model_issues');

		$issueId = $this->input->post('issueId');
		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$issuesResponse = $this->model_issues->getIssuesList($issueId);
		$issues = $issuesResponse["issues"];

		$addressParams = array(
			'addressLine1' 		=> $issues[0]->address1,
			'addressLine2' 		=> $issues[0]->address2,
			'city' 				=> $issues[0]->city,
			'country' 			=> $issues[0]->country,
			'state'				=> $issues[0]->state,
			'zipCode' 			=> $issues[0]->pin_code,
			'forForm' 			=> "update_issue_form"
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);

		$params = array(
			'issues' 	=> $issues,
			'addressFile' 	=> $addressFile,
			'userType' 		=> $this->session->userdata('account_type'),
			'openAs' 		=> $openAs,
			'popupType' 	=> $popupType
		);
		
		echo $this->load->view("projects/issues/editForm", $params, true);
	}

	public function update() {
		$this->load->model('projects/model_issues');

		$issueId 			= $this->input->post('issueId');

		$data = array(
			'name' 				=> $this->input->post('name'),
			'company' 			=> $this->input->post('company'),
			'type' 				=> $this->input->post('type'),
			'license' 			=> $this->input->post('license'),
			'bbb' 				=> $this->input->post('bbb'),
			'status' 			=> $this->input->post('status'),
			'address1' 			=> $this->input->post('addressLine1'),
			'address2'			=> $this->input->post('addressLine2'),
			'city' 				=> $this->input->post('city'),
			'state' 			=> $this->input->post('state'),
			'country' 			=> $this->input->post('country'),
			'pin_code' 			=> $this->input->post('zipCode'),
			'office_email' 		=> $this->input->post('emailId'),
			'office_ph' 		=> $this->input->post('contactPhoneNumber'),
			'mobile_ph' 		=> $this->input->post('mobileNumber'),
			'prefer' 			=> $this->input->post('prefContact'),
			'website_url' 		=> $this->input->post('websiteURL'),
			'service_area' 		=> $this->input->post('serviceZip'),
			'updated_by'		=> $this->session->userdata('user_id'),
			'updated_on'		=> date("Y-m-d H:i:s")
		);

		$update_issue = $this->model_issues->update($data, $issueId);

		print_r(json_encode($update_issue));
	}

	public function deleteRecord() {
		$this->load->model('projects/model_issues');

		$issueId = $this->input->post('issueId');
		$delete_issue = $this->model_issues->deleteRecord($issueId);

		print_r(json_encode($delete_issue));	
	}
}