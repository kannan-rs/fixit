<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Docs extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
		
		$this->controller 		= $this->uri->segment(1);
		$this->page 			= $this->uri->segment(2);
		$this->module = $this->uri->segment(3) ? $this->uri->segment(3): "";
		$this->sub_module = $this->uri->segment(3) ? $this->uri->segment(3): "";
		$this->function = $this->uri->segment(4) ? $this->uri->segment(4): "";
		$this->record = $this->uri->segment(5) ? $this->uri->segment(5): "";
	}
	
	public function viewAll() {
		$this->load->model('projects/model_docs');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_projects');

		$projectId 			= $this->input->post('projectId');
		$startRecord 		= $this->input->post('startRecord');

		$startRecord 		= $startRecord != "" ? $startRecord : 0;
		
		$project_docs 		= $this->model_docs->getDocsList($projectId, $startRecord);

		for($i=0; $i < count($project_docs); $i++) {
			$project_docs[$i]->created_by_name = $this->model_users->getUsersList($project_docs[$i]->created_by)[0]->user_name;
			$project_docs[$i]->updated_by_name = $this->model_users->getUsersList($project_docs[$i]->updated_by)[0]->user_name;
		}

		$project 			= $this->model_projects->getProjectsList($projectId);

		$paramsNameDescr 	= array(
			'projectId' 		=> $projectId,
			'projectName' 		=> $project[0]->project_name,
			'projectDescr' 		=> $project[0]->project_descr,
			'accountType' 		=> $this->session->userdata('account_type')
		);

		$internalLinkParams = array(
			"internalLinkArr" 		=> ["tasks", "project notes"],
			"projectId" 			=> $projectId
		);

		$params = array(
			'project_docs' 		=> $project_docs,
			'startRecord'		=> $startRecord,
			'projectId' 		=> $projectId,
			'projectNameDescr' 	=> $this->load->view("projects/projectNameDescr", $paramsNameDescr, TRUE),
			'internalLink' 	=> $this->load->view("projects/internalLinks", $internalLinkParams, TRUE)
		);
		
		echo $this->load->view("projects/docs/viewAll", $params, true);
	}
	
	public function createForm() {
		$projectId			= $this->input->post('projectId');
		$params = array(
			'function'		=>"createFormNotes",
			'record'		=>"",
			'projectId' 	=> $projectId
		);

		echo $this->load->view("projects/docs/createForm", $params, true);
	}

	public function add() {
		$this->load->model('projects/model_docs');
		$insert_docs = array();

		if(count($_FILES) > 0) {
			if(is_uploaded_file($_FILES['docAttachment']['tmp_name'])) {

				$data = array(
					'project_id' 			=> $this->input->post("projectId"),
					'document_name '		=> $this->input->post("docName"),
					'document_content' 		=> addslashes(file_get_contents($_FILES['docAttachment']['tmp_name'])),
					'att_name' 				=> $_FILES["docAttachment"]["name"],
					'att_type'				=> $_FILES["docAttachment"]["type"],
					'created_by'			=> $this->session->userdata('user_id'),
					'created_on' 			=> date("Y-m-d H:i:s"),
					'updated_by' 			=> $this->session->userdata('user_id'),
					'updated_on' 			=> date("Y-m-d H:i:s")
				);

				$insert_docs = $this->model_docs->insert($data);
			}
		} else {
			$insert_docs["status"] 	= "error";
			$insert_docs["message"] 	= "File missing.. Try again";
		}

		print_r(json_encode($insert_docs));
	}

	public function downloadAttachment() {
		if(isset($this->function)) {
			$this->load->model('projects/model_docs');
			$one_doc = $this->model_docs->getDocById($this->function);
			
			for($i=0; $i < count($one_doc); $i++) {
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: private",false);
				header("Content-Disposition: attachment; filename=\"".$one_doc[$i]->att_name."\";" );
				header("Content-type: ". $one_doc[$i]->att_type);
				header("Content-length: ".strlen($one_doc[$i]->document_content));
				header("Content-Transfer-Encoding: binary");
				echo $one_doc[$i]->document_content;
			}
		}
	}

	public function deleteRecord() {
		$this->load->model('projects/model_docs');

		$docId = $this->input->post('docId');
		$delete_doc = $this->model_docs->deleteRecord($docId);

		if($delete_doc["status"] == "success") {
			$delete_doc["docId"] = $docId;
		}

		print_r(json_encode($delete_doc));	
	}
}