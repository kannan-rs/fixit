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
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_issues');
		$this->load->model('security/model_users');
		$projectList = "";

		//print_r($this->session->userdata);
		$account_type = $this->session->userdata('account_type');
		$user_id = $this->session->userdata('user_id');
		$email = $this->session->userdata('email');

		$projectParams = array(
			'account_type' => $account_type,
			'user_details_id' => $this->model_users->getUserDetailsSnoViaEmail($email),
			'user_id' => $user_id,
			'email' => $email
		);

		$projectListArr = array();
		//print_r($projectParams);
		if($account_type == "user") {
			$projectList = $this->model_projects->getProjectIds($projectParams);
			for($i = 0; $i < count($projectList); $i++) {
				array_push($projectListArr, $projectList[$i]->proj_id);
			}
		} else if($account_type != "admin") {
			//$projectList = 
		}

		//echo "projectListArr ->";
		//print_r($projectListArr); 

		$projectParams["projectId"] = $projectListArr;

		//print_r($projectParams);
		
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

			$issuesResponse = $this->model_issues->getIssuesList(array('records' => '', 'projectId' => $projects[$i]->proj_id, 'status' => 'open'));
			$issueCount 	= $issuesResponse && $issuesResponse["issues"] ? count($issuesResponse["issues"]) : 0;

			$projects[$i]->issueCount = $issueCount;

		}

		$params = array(
			'projects' => $projects,
			'account_type' => $this->session->userdata('account_type')
		);
		
		echo $this->load->view("projects/projects/viewAll", $params, true);
	}

	public function getAssignees() {
		$projectId 			= $this->input->post('projectId');

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

		$projectParams = array(
			'projectId' => [$projectId]
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
					$assigneeDetails["customerDetails"]["account_type"] = $userDetails[0]->account_type;
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
		$this->load->model('security/model_users');

		$addressParams = array(
			'forForm' 			=> "create_project_form"
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);
		
		$params = array(
			'users' 		=> $this->model_users->getUsersList(),
			'addressFile' 	=> $addressFile,
			'userType' 		=> $this->session->userdata('account_type')
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
		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');


		$record = $this->input->post('projectId');

		$projectParams = array(
			'projectId' => [$record]
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

		$params = array(
			'projects' 			=>$projects,
			'users' 			=> $this->model_users->getUsersList(),
			'internalLink' 		=> $this->load->view("projects/internalLinks", $internalLinkParams, true),
			'userType' 			=> $this->session->userdata('account_type'),
			'addressFile' 		=> $addressFile,
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
		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_partners');
		$this->load->model('mail/model_mail');

		$projectId = $this->input->post('projectId');

		// Get Porject details defore delete
		$projectParams = array(
			'projectId' => [$projectId]
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
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_remainingbudget');

		$projectId = $this->input->post('projectId');
		$projectParams = array(
			'projectId' => [$projectId]
		);
		$projects = $this->model_projects->getProjectsList($projectParams);

		$project 	= count($projects) ? $projects[0] : "";

		$project->paid_from_budget = $this->model_remainingbudget->getPaidBudgetSum($project->proj_id);

		$budgetParams = array(
			'project'		=> $project,
			'userType' 		=> $this->session->userdata('account_type'),
		);

		echo  $this->load->view("projects/projects/projectBudget", $budgetParams, true);
	}

	public function viewOne() {
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
		$projectParams = array(
			'projectId' => [$projectId]
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

		$internalLinkParams = array(
			"internalLinkArr" 		=> ["update project", "delete project"],
			"projectId" 			=> $projectId
		);

		/*
			Customer Output
		*/
		$customers 	= $this->model_users->getUserDetailsBySno($project->customer_id);
		$customer 	= count($customers) ? $customers[0] : "";
		
		if($customer) {
			$customerParams = array(
				"customer"			=> $customer
			);
			$customerFile 		= $this->load->view("projects/projects/customerDetailsView", $customerParams, true);
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
			'requestFrom' 		=> 'view'
		);
		//$addressFile = $this->load->view("forms/address", $addressParams, true);

		/*
			Budget List
		*/
		$budgetParams = array(
			'project'		=> $project,
			'userType' 		=> $this->session->userdata('account_type'),
		);

		/*
			Final Project ViewOnly template output
		*/
		$params = array(
			'project'			=> $project,
			'userType' 			=> $this->session->userdata('account_type'),
			'projectId' 		=> $projectId,
			'contractors' 		=> $contractors,
			'partners' 			=> $partners,
			'customerFile' 		=> $customerFile,
			'addressFile' 		=> $this->load->view("forms/address", $addressParams, true),
			'projectBudgetFile' =>  $this->load->view("projects/projects/projectBudget.php", $budgetParams, true)
		);
		echo $this->load->view("projects/projects/viewOne", $params, true);
	}

	public function exportCSV() {
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

		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_notes');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_issues');
		$this->load->model('projects/model_partners');
		$this->load->model('projects/model_remainingbudget');

		$projectParams = array(
			'projectId' => [$projectId]
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