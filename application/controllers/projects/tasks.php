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
		$this->load->model('projects/model_tasks');
		$this->load->model('projects/model_projects');

		$projectId 	= $this->input->post('projectId');
		$viewFor 	= $this->input->post('viewFor');

		$viewFor = $viewFor ? $viewFor : "";

		$project = $this->model_projects->get_projects_list($projectId);
		
		$tasks = $this->model_tasks->get_tasks_list($projectId);

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

		$params = array(
			'tasks' 			=>$tasks,
			'projectId' 		=> $projectId,
			'viewFor' 			=> $viewFor,
			'projectNameDescr' 	=> $projectNameDescr,
			'internalLink' 		=> $internalLink
		);
		
		echo $this->load->view("projects/tasks/viewAll", $params, true);
	}
	
	public function createForm() {
		$this->load->model('security/model_users');
		$this->load->model('projects/model_projects');

		$projectId = $this->input->post('projectId');
		$viewFor 	= $this->input->post('viewFor');

		$parent_record = $this->model_projects->get_projects_list($projectId);

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
		);

		echo $this->load->view("projects/tasks/createForm", $params, true);
	}

	public function add() {
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');

		$task_start_date 			= ($this->input->post('task_start_date') == "") ? date("Y-m-d") : $this->input->post('task_start_date');
		$task_end_date 				= ($this->input->post('task_end_date') == "" ) ?  $task_start_date : $this->input->post('task_end_date');

		$data = array(
		   'project_id' 				=> $this->input->post('parentId'),
		   'task_name' 					=>  $this->input->post('task_name'),
		   'task_desc' 					=> $this->input->post('task_desc'),
		   'task_start_date' 			=> $task_start_date,
		   'task_end_date' 				=> $task_end_date,
		   'task_status' 				=> $this->input->post('task_status'),
		   'task_owner_id' 				=> $this->input->post('task_owner_id'),
		   'task_percent_complete' 		=> $this->input->post('task_percent_complete'),
		   'task_dependency' 			=> $this->input->post('task_dependency'),
		   'task_trade_type' 			=> $this->input->post('task_trade_type'),
		   'created_by' 				=> $this->session->userdata('user_id'),
		   'created_on' 				=> date("Y-m-d H:i:s"),
		   'updated_by'					=> $this->session->userdata('user_id'),
		   'updated_on' 				=> date("Y-m-d H:i:s")
		);

		$insert_task = $this->model_tasks->insert($data);
		print_r(json_encode($insert_task));
	}


	public function editForm() {
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_projects');


		$record 	= $this->input->post('taskId');
		$viewFor 	= $this->input->post('viewFor');
		$viewFor 	= $viewFor ? $viewFor : "";


		$tasks = $this->model_tasks->get_task($record);

		$projectId 		= $tasks[0]->project_id;
		$parent_record 	= $this->model_projects->get_projects_list($projectId);

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
			'users' 			=> $this->model_users->get_users_list(),
			'projectId' 		=> $projectId,
			'projectNameDescr' 	=> $projectNameDescr,
			'internalLink' 		=> $internalLink,
			'viewFor' 			=> $viewFor
		);
		
		echo $this->load->view("projects/tasks/editForm", $params, true);
	}

	public function update() {
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');

		$record = 	$this->input->post('task_id');

		$task_start_date 			= ($this->input->post('task_start_date') == "") ? date("Y-m-d") : $this->input->post('task_start_date');
		$task_end_date 				= ($this->input->post('task_end_date') == "" ) ?  $task_start_date : $this->input->post('task_end_date');

		$data = array(
		   'task_name' 					=> $this->input->post('task_name'),
		   'task_desc' 					=> $this->input->post('task_desc'),
		   'task_start_date' 			=> $task_start_date,
		   'task_end_date' 				=> $task_end_date,
		   'task_status' 				=> $this->input->post('task_status'),
		   'task_owner_id' 				=> $this->input->post('task_owner_id'),
		   'task_percent_complete'		=> $this->input->post('task_percent_complete'),
		   'task_dependency'			=> $this->input->post('task_dependency'),
		   'task_trade_type'			=> $this->input->post('task_trade_type'),
		   'updated_by'					=> $this->session->userdata('user_id'),
		   'updated_on'					=> date("Y-m-d H:i:s")
		);

		$update_task = $this->model_tasks->update($data, $record);

		print_r(json_encode($update_task));
	}

	public function delete() {
		$this->load->model('projects/model_tasks');

		$task_id = $this->input->post('task_id');
		$project_id = $this->input->post('project_id');

		$delete_task = $this->model_tasks->delete($task_id, $project_id);

		print_r(json_encode($delete_task));	
	}

	public function viewOne() {
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_projects');

		$record 	= $this->input->post('taskId');
		$viewFor 	= $this->input->post('viewFor');
		$viewFor 	= $viewFor ? $viewFor : "";

		$tasks = $this->model_tasks->get_task($record);

		$projectId 		= $tasks[0]->project_id;
		$created_by 	= $this->model_users->get_users_list($tasks[0]->created_by)[0]->user_name;
		$updated_by 	= $this->model_users->get_users_list($tasks[0]->updated_by)[0]->user_name;

		$parent_record 	= $this->model_projects->get_projects_list($projectId);

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
			'tasks'=>$tasks,
			'created_by' => $created_by,
			'updated_by' => $updated_by,
			'projectId' => $projectId,
			'projectNameDescr' 	=> $projectNameDescr,
			'internalLink' 	=> $internalLink,
			'viewFor' 	=> $viewFor
		);
		
		echo $this->load->view("projects/tasks/viewOne", $params, true);
	}	
}