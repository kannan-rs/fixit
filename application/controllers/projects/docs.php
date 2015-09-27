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
		
		$projectDocsResponse 	= $this->model_docs->getDocsList($projectId);

		if(isset($projectDocsResponse["docs"])) {
			for($i=0; $i < count($projectDocsResponse["docs"]); $i++) {
				$projectDocsResponse["docs"][$i]->created_by_name = $this->model_users->getUsersList($projectDocsResponse["docs"][$i]->created_by)[0]->user_name;
				$projectDocsResponse["docs"][$i]->updated_by_name = $this->model_users->getUsersList($projectDocsResponse["docs"][$i]->updated_by)[0]->user_name;
			}
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
			'project_docs' 		=> isset($projectDocsResponse["docs"]) ? $projectDocsResponse["docs"] : [],
			"count"				=> $projectDocsResponse["count"],
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
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_partners');
		$this->load->model('mail/model_mail');

		$response = array();

		if(count($_FILES) > 0) {
			if(is_uploaded_file($_FILES['docAttachment']['tmp_name'])) {

				$projectId = $this->input->post("projectId");

				$data = array(
					'project_id' 			=> $projectId,
					'document_name '		=> $this->input->post("docName"),
					'document_content' 		=> addslashes(file_get_contents($_FILES['docAttachment']['tmp_name'])),
					'att_name' 				=> $_FILES["docAttachment"]["name"],
					'att_type'				=> $_FILES["docAttachment"]["type"],
					'created_by'			=> $this->session->userdata('user_id'),
					'created_on' 			=> date("Y-m-d H:i:s"),
					'updated_by' 			=> $this->session->userdata('user_id'),
					'updated_on' 			=> date("Y-m-d H:i:s")
				);

				$response = $this->model_docs->insert($data);

				$projects = $this->model_projects->getProjectsList($projectId);
				$project 	= count($projects) ? $projects[0] : "";

				$customerId 	= isset($project) && isset($project->customer_id) && !empty($project->customer_id) ? $project->customer_id :  null;
				$contractorId 	= isset($project) && isset($project->contractor_id) && !empty($project->contractor_id) ? $project->contractor_id :  null;
				$adjusterId 	= isset($project) && isset($project->adjuster_id) && !empty($project->adjuster_id) ? $project->adjuster_id :  null;

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

				$docsParamsFormMail = array(
					'response'			=> $response,
					'projectData'		=> $project,
					'taskData'			=> isset( $taskData ) ? $taskData : null,
					'customerData' 		=> $customerData,
					'contractorsData' 	=> $contractorsData,
					'partnersData' 		=> $partnersData,
					'mail_type' 		=> "create"
				);

				$mail_options = $this->model_mail->generateDocsMailOptions( $docsParamsFormMail );
				
				if($this->config->item('development_mode')) {
					$response['mail_content'] = $mail_options;
				} else {
					for($i = 0; $i < count($mail_options); $i++) {
						$response["mail_error"] = $this->model_mail->sendMail( $mail_options[$i] );
					}
				}
			}
		} else {
			$response["status"] 	= "error";
			$response["message"] 	= "File missing.. Try again";
		}

		print_r(json_encode($response));
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
		$this->load->model('projects/model_projects');
		$this->load->model('projects/model_tasks');
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('projects/model_partners');
		$this->load->model('mail/model_mail');

		$docId = $this->input->post('docId');
		
		$docsResponse 	= $this->model_docs->getDocsList("", $docId);

		$response = $this->model_docs->deleteRecord($docId);

		if(isset($docsResponse["docs"])) {
			$docs = $docsResponse['docs'][0];

			$projectId = $docs->project_id;

			$projects = $this->model_projects->getProjectsList($projectId);
			$project 	= count($projects) ? $projects[0] : "";

			$customerId 	= isset($project) && isset($project->customer_id) && !empty($project->customer_id) ? $project->customer_id :  null;
			$contractorId 	= isset($project) && isset($project->contractor_id) && !empty($project->contractor_id) ? $project->contractor_id :  null;
			$adjusterId 	= isset($project) && isset($project->adjuster_id) && !empty($project->adjuster_id) ? $project->adjuster_id :  null;

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

			$docsParamsFormMail = array(
				'response'			=> $response,
				'projectData'		=> $project,
				'taskData'			=> isset( $taskData ) ? $taskData : null,
				'customerData' 		=> $customerData,
				'contractorsData' 	=> $contractorsData,
				'partnersData' 		=> $partnersData,
				'mail_type' 		=> "delete"
			);

			$mail_options = $this->model_mail->generateDocsMailOptions( $docsParamsFormMail );
			
			if($this->config->item('development_mode')) {
				$response['mail_content'] = $mail_options;
			} else {
				for($i = 0; $i < count($mail_options); $i++) {
					$response["mail_error"] = $this->model_mail->sendMail( $mail_options[$i] );
				}
			}
		}


		if($response["status"] == "success") {
			$response["docId"] = $docId;
		}

		print_r(json_encode($response));	
	}
}