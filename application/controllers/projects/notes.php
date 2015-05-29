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

		$project_notes 		= $this->model_notes->get_notes_list($projectId, $taskId, $noteId, $startRecord, $count);

		$count 				= $this->model_notes->count($projectId, $taskId);

		for($i=0; $i < count($project_notes); $i++) {
			$project_notes[$i]->created_by_name = $this->model_users->get_users_list($project_notes[$i]->created_by)[0]->user_name;
			$project_notes[$i]->updated_by_name = $this->model_users->get_users_list($project_notes[$i]->updated_by)[0]->user_name;
		}

		$project 			= $this->model_projects->get_projects_list($projectId);

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
			'project_notes' 	=> $project_notes,
			'startRecord' 		=> $startRecord,
			'projectId' 		=> $projectId,
			'count' 			=> $count,
			'projectNameDescr' 	=> $projectNameDescr,
			'internalLink' 		=> $internalLink,
			'viewFor' 			=> $viewFor
		);
		
		echo $this->load->view("projects/notes/viewAll", $params, true);
	}
	
	public function createForm() {
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
		$this->load->model('projects/model_notes');

		$data = array(
			'project_id'		=> $this->input->post('projectId'),
			'task_id'			=> $this->input->post('taskId'),
			'notes_name'		=> $this->input->post('noteName'),
			'notes_content'		=> $this->input->post('noteContent'),
			'created_by'		=> $this->session->userdata('user_id'),
			'updated_by'		=> $this->session->userdata('user_id'),
			'created_date'		=> date("Y-m-d H:i:s"),
			'updated_date'		=> date("Y-m-d H:i:s")
		);

		$insert_notes = $this->model_notes->insert($data);

		if($insert_notes["status"] == "success") {
			$insert_notes["projectId"] = $this->input->post('projectId');
			$insert_notes["taskId"] = $this->input->post('taskId');
		}

		print_r(json_encode($insert_notes));
	}

	public function delete() {
		$this->load->model('projects/model_notes');

		$noteId = $this->input->post('noteId');

		$delete_task = $this->model_notes->delete($noteId);

		print_r(json_encode($delete_task));
	}
}