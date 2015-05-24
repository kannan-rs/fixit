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

		
		$projects = $this->model_projects->get_projects_list();

		for($i = 0; $i < count($projects); $i++) {
			$start_date = "";
			$end_date	= "";
			$percentage = "";

			$ed_query = "select 
								AVG(task_percent_complete) as percentage, 
								DATE_FORMAT( MAX(task_end_date), '%d-%m-%y' ) as end_date, 
								DATE_FORMAT( MIN(task_start_date),  '%d-%m-%y') as start_date 
						from project_details where project_id = '".$projects[$i]->proj_id."'";

			$consolidate_data_query = $this->db->query($ed_query);
			$consolidate_data_result = $consolidate_data_query->result();
			$consolidate_data = $consolidate_data_result[0];

			$projects[$i]->percentage = ($consolidate_data->percentage > 0  ? round($consolidate_data->percentage,1) : 0);
			$projects[$i]->start_date = ($consolidate_data->start_date != "" ? $consolidate_data->start_date : "-NA-");
			$projects[$i]->end_date = ($consolidate_data->end_date != "" ? $consolidate_data->end_date : "-NA-");
		}

		$params = array(
			'projects'=>$projects
		);
		
		echo $this->load->view("projects/projects/viewAll", $params, true);
	}
	
	public function createForm() {
		$this->load->model('security/model_users');
		
		$params = array(
			'users' 		=> $this->model_users->get_users_list(),
			'userType' 		=> $this->session->userdata('account_type')
		);

		echo $this->load->view("projects/projects/createForm", $params, true);
	}

	public function add() {
		$this->load->model('projects/model_projects');

		$data = array(
			'project_name' 				=> $this->input->post('projectTitle'),
			'project_descr'				=> $this->input->post('description'),
			'associated_claim_num'		=> $this->input->post('associated_claim_num'),
			'project_type'				=> $this->input->post('project_type'),
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
			'referral_fee'				=> $this->input->post('referral_fee'),
			'project_lender'			=> $this->input->post('project_lender'),
			'lend_amount'				=> $this->input->post('lend_amount'),
			'created_by'				=> $this->session->userdata('user_id'),
			'updated_by'				=> $this->session->userdata('user_id'),
			'created_on'				=> date("Y-m-d H:i:s"),
			'updated_on'				=> date("Y-m-d H:i:s")
		);

		$insert_project = $this->model_projects->insert($data);

		print_r(json_encode($insert_project));
	}


	public function editForm() {
		$this->load->model('projects/model_projects');
		$this->load->model('security/model_users');


		$record = $this->input->post('projectId');

		$projects = $this->model_projects->get_projects_list($record);

		$internalLinkParams = array(
			"internalLinkArr" 		=> ["tasks", "project notes", "documents"],
			"projectId" 			=> $record
		);

		$params = array(
			'projects'=>$projects,
			'users' => $this->model_users->get_users_list(),
			'internalLink' 	=> $this->load->view("projects/internalLinks", $internalLinkParams, true),
			'userType' 		=> $this->session->userdata('account_type')
		);
		
		echo $this->load->view("projects/projects/editForm", $params, true);
	}

	public function update() {
		$this->load->model('projects/model_projects');

		$record 			= $this->input->post('project_sno');

		$data = array(
			'project_name' 				=> $this->input->post('projectTitle'),
			'project_descr'				=> $this->input->post('description'),
			'associated_claim_num'		=> $this->input->post('associated_claim_num'),
			'project_type'				=> $this->input->post('project_type'),
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
			'referral_fee'				=> $this->input->post('referral_fee'),
			'project_lender'			=> $this->input->post('project_lender'),
			'lend_amount'				=> $this->input->post('lend_amount'),
			'updated_by'				=> $this->session->userdata('user_id'),
			'updated_on'				=> date("Y-m-d H:i:s")
		);

		$update_project = $this->model_projects->update($data, $record);

		print_r(json_encode($update_project));
	}

	public function delete() {
		$this->load->model('projects/model_projects');

		$record = $this->input->post('projectId');
		$delete_project = $this->model_projects->delete($record);

		print_r(json_encode($delete_project));	
	}

	public function viewOne() {
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_notes');

		$projectId = $this->input->post('projectId');
		$projects = $this->model_projects->get_projects_list($projectId);

		$start_date		= "";
		$end_date		= "";
		$percentage 	= "";
		// Individual View
		
		for($i = 0; $i < count($projects); $i++) {
			$ed_query = "select 
								AVG(task_percent_complete) as percentage, 
								DATE_FORMAT( MIN(task_end_date),  '%d-%m-%y') as end_date, 
								DATE_FORMAT( MIN(task_start_date),  '%d-%m-%y') as start_date 
						from project_details where project_id = '".$projects[$i]->proj_id."'";

			$consolidate_data_query = $this->db->query($ed_query);
			$consolidate_data_result = $consolidate_data_query->result();
			$consolidate_data = $consolidate_data_result[0];

			$projects[$i]->percentage = ($consolidate_data->percentage > 0  ? round($consolidate_data->percentage,1) : 0);
			$projects[$i]->start_date = ($consolidate_data->start_date != "" ? $consolidate_data->start_date : "-NA-");
			$projects[$i]->end_date = ($consolidate_data->end_date != "" ? $consolidate_data->end_date : "-NA-");

			$projects[$i]->created_by_name = $this->model_users->get_users_list($projects[$i]->created_by)[0]->user_name;
			$projects[$i]->updated_by_name = $this->model_users->get_users_list($projects[$i]->updated_by)[0]->user_name;
		}

		$internalLinkParams = array(
			"internalLinkArr" 		=> ["tasks", "project notes", "documents", "update project", "delete project"],
			"projectId" 			=> $projectId
		);

		// Get List of tasks for the project ID
		$tasks = $this->model_tasks->get_tasks_list($projectId);

		//Get All Notes for Project
		$notes 		= $this->model_notes->get_notes_list($projectId, 0, "", 0, 'All');

		for($i=0; $i < count($notes); $i++) {
			$notes[$i]->created_by_name = $this->model_users->get_users_list($notes[$i]->created_by)[0]->user_name;
			$notes[$i]->updated_by_name = $this->model_users->get_users_list($notes[$i]->updated_by)[0]->user_name;
		}

		$params = array(
			'projects'		=> $projects,
			'internalLink' 	=> $this->load->view("projects/internalLinks", $internalLinkParams, true),
			'userType' 		=> $this->session->userdata('account_type'),
			'tasks' 		=>$tasks,
			'projectId' 	=> $projectId,
			'notes' 		=> $notes
		);
		
		echo $this->load->view("projects/projects/viewOne", $params, true);
	}
}