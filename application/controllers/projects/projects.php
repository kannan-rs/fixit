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
		$this->load->model('projects/model_projects');

		
		$projects = $this->model_projects->getProjectsList();

		for($i = 0; $i < count($projects); $i++) {
			$start_date = "";
			$end_date	= "";
			$percentage = "";

			$ed_query = "select 
								AVG(task_percent_complete) as percentage, 
								DATE_FORMAT( MAX(task_end_date), '%m/%d/%y' ) as end_date, 
								DATE_FORMAT( MIN(task_start_date),  '%m/%d/%y') as start_date 
						from project_details where project_id = '".$projects[$i]->proj_id."'";

			$consolidate_data_query = $this->db->query($ed_query);
			$consolidate_data_result = $consolidate_data_query->result();
			$consolidate_data = $consolidate_data_result[0];

			$projects[$i]->percentage = ($consolidate_data->percentage > 0  ? round($consolidate_data->percentage,1) : 0);

			$start_date 			= $consolidate_data->start_date != "" ? ($projects[$i]->start_date != "" && $projects[$i]->start_date < $consolidate_data->start_date ? $projects[$i]->start_date : $consolidate_data->start_date) : ($projects[$i]->start_date != "" ? $projects[$i]->start_date : "-NA-");
			$end_date 				= $consolidate_data->end_date != "" ? ($projects[$i]->end_date != "" && $projects[$i]->end_date > $consolidate_data->end_date ? $projects[$i]->end_date : $consolidate_data->end_date) : ($projects[$i]->end_date != "" ? $projects[$i]->end_date : "-NA-");

			$projects[$i]->start_date 	= $start_date;
			$projects[$i]->end_date 		= $end_date;
		}

		$params = array(
			'projects'=>$projects
		);
		
		echo $this->load->view("projects/projects/viewAll", $params, true);
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

		$addressLine1 			= $this->input->post('addressLine1');
		$addressLine2 			= $this->input->post('addressLine2');
		$city 					= $this->input->post('city');
		$state 					= $this->input->post('state');
		$country 				= $this->input->post('country');
		$zipCode				= $this->input->post('zipCode');

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
			'contractor_id'				=> $this->input->post('contractor_id'),
			'adjuster_id'				=> $this->input->post('adjuster_id'),
			'broker_id'					=> $this->input->post('broker_id'),
			'banker_id'					=> $this->input->post('banker_id'),
			'customer_id'				=> $this->input->post('customer_id'),
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

		$insert_project = $this->model_projects->insert($data);

		print_r(json_encode($insert_project));
	}


	public function editForm() {
		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');


		$record = $this->input->post('projectId');

		$projects = $this->model_projects->getProjectsList($record);

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

		$record 				= $this->input->post('project_sno');
		$addressLine1 			= $this->input->post('addressLine1');
		$addressLine2 			= $this->input->post('addressLine2');
		$city 					= $this->input->post('city');
		$state 					= $this->input->post('state');
		$country 				= $this->input->post('country');
		$zipCode 				= $this->input->post('zipCode');

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
			'contractor_id'				=> $this->input->post('contractor_id'),
			'adjuster_id'				=> $this->input->post('adjuster_id'),
			'broker_id'					=> $this->input->post('broker_id'),
			'banker_id'					=> $this->input->post('banker_id'),
			'customer_id'				=> $this->input->post('customer_id'),
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

		$update_project = $this->model_projects->update($data, $record);

		print_r(json_encode($update_project));
	}

	public function deleteRecord() {
		$this->load->model('projects/model_projects');

		$record = $this->input->post('projectId');
		$delete_project = $this->model_projects->deleteRecord($record);

		print_r(json_encode($delete_project));	
	}

	public function viewOnlyBudget() {
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_remainingbudget');

		$projectId = $this->input->post('projectId');
		$projects = $this->model_projects->getProjectsList($projectId);

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
		$this->load->model('projects/model_partners');
		$this->load->model('projects/model_remainingbudget');

		$projectId = $this->input->post('projectId');
		$projects = $this->model_projects->getProjectsList($projectId);

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
					from project_details where project_id = '".$project->proj_id."'";

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
			Address Output
		*/
		$addressParams = array(
			'addressLine1' 		=> $project->addr1,
			'addressLine2' 		=> $project->addr2,
			'city' 				=> $project->addr_city,
			'country' 			=> $project->addr_country,
			'state'				=> $project->addr_state,
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
}