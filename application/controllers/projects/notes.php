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
		$startRecord 		= $this->input->post('startRecord');
		$count 				= $this->input->post('count');

		$startRecord 		= $startRecord != "" ? $startRecord : 0;
		$count 				= $count != "" ? $count : 5;

		$project_notes 		= $this->model_notes->get_notes_list($projectId, $startRecord, $count);

		for($i=0; $i < count($project_notes); $i++) {
			$project_notes[$i]->created_by_name = $this->model_users->get_users_list($project_notes[$i]->created_by)[0]->user_name;
			$project_notes[$i]->updated_by_name = $this->model_users->get_users_list($project_notes[$i]->updated_by)[0]->user_name;
		}

		$project 			= $this->model_projects->get_projects_list($projectId);

		$paramsNameDescr 	= array(
			'projectName' 		=> $project[0]->project_name,
			'projectDescr' 		=> $project[0]->project_descr
		);

		$internalLinkParams = array(
			"internalLinkArr" 		=> ["tasks", "documents"],
			"projectId" 			=> $projectId
		);

		$params = array(
			'project_notes' 	=> $project_notes,
			'startRecord' 		=> $startRecord,
			'projectId' 		=> $projectId,
			'projectNameDescr' 	=> $this->load->view("projects/projectNameDescr", $paramsNameDescr, TRUE),
			'internalLink' 		=> $this->load->view("projects/internalLinks", $internalLinkParams, TRUE)
		);
		
		echo $this->load->view("projects/notes/viewAll", $params, true);
	}
	
	public function createForm() {
		//$this->load->model('security/model_users');
		$projectId			= $this->input->post('projectId');
		$params = array(
			'function'		=>"createFormNotes",
			'record'		=>"",
			'projectId' 	=> $projectId
		);

		echo $this->load->view("projects/notes/createForm", $params, true);
	}

	public function add() {
		$this->load->model('projects/model_projects');

		$data = array(
			'project_id'		=> $this->input->post('projectId'),
			'notes_name'		=> $this->input->post('noteName'),
			'notes_content'		=> $this->input->post('noteContent'),
			'created_by'		=> $this->session->userdata('user_id'),
			'updated_by'		=> $this->session->userdata('user_id'),
			'created_date'		=> date("Y-m-d H:i:s"),
			'updated_date'		=> date("Y-m-d H:i:s")
		);

		$insert_project = $this->model_projects->insertNote($data);

		if($insert_project["status"] == "success") {
			$insert_project["projectId"] = $this->input->post('projectId');
		}

		print_r(json_encode($insert_project));
	}
}