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
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$claimPermission 	= $this->permissions_lib->getPermissions(FUNCTION_CLAIM);
		$docsPermission 	= $this->permissions_lib->getPermissions(FUNCTION_CLAIM_DOCS);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $claimPermission['operation']) || !in_array(OPERATION_VIEW, $docsPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "document list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		$this->load->model('claims/model_docs');
		$this->load->model('security/model_users');

		$claim_id 			= $this->input->post('claim_id');
		$startRecord 		= $this->input->post('startRecord');

		$startRecord 		= $startRecord != "" ? $startRecord : 0;
		
		$claimDocsResponse 	= $this->model_docs->getDocsList($claim_id);

		if(isset($claimDocsResponse["docs"])) {
			for($i=0; $i < count($claimDocsResponse["docs"]); $i++) {
				$claimDocsResponse["docs"][$i]->created_by_name = $this->model_users->getUsersList($claimDocsResponse["docs"][$i]->created_by)[0]->user_name;
				$claimDocsResponse["docs"][$i]->updated_by_name = $this->model_users->getUsersList($claimDocsResponse["docs"][$i]->updated_by)[0]->user_name;
			}
		}

		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();
		//Claim > Permissions for logged in User by role_id
		$claimPermission = $this->permissions_lib->getPermissions(FUNCTION_CLAIM);

		$params = array(
			'claim_docs' 		=> isset($claimDocsResponse["docs"]) ? $claimDocsResponse["docs"] : [],
			"count"				=> $claimDocsResponse["count"],
			'startRecord'		=> $startRecord,
			'claim_id' 			=> $claim_id,
			'role_disp_name'	=> $role_disp_name
		);
		
		echo $this->load->view("claims/docs/viewAll", $params, true);
	}
	
	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$claimPermission 	= $this->permissions_lib->getPermissions(FUNCTION_CLAIM);
		$docsPermission 	= $this->permissions_lib->getPermissions(FUNCTION_CLAIM_DOCS);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $claimPermission['operation']) || !in_array(OPERATION_CREATE, $docsPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "document list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$claim_id			= $this->input->post('claim_id');
		$params = array(
			'record'		=>"",
			'claim_id' 	=> $claim_id
		);

		echo $this->load->view("claims/docs/createForm", $params, true);
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed_claim 	= $this->permissions_lib->is_allowed(FUNCTION_CLAIM, OPERATION_CREATE);
		$is_allowed 		= $this->permissions_lib->is_allowed(FUNCTION_CLAIM_DOCS, OPERATION_CREATE);

		if(!$is_allowed_claim["status"] || !$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('claims/model_docs');
		$this->load->model('claims/model_claims');
		$this->load->model('security/model_users');
		$this->load->model('mail/model_mail');

		$response = array();

		if(count($_FILES) > 0) {
			if(is_uploaded_file($_FILES['docAttachment']['tmp_name'])) {

				$claim_id = $this->input->post("claim_id");

				$data = array(
					'claim_id' 				=> $claim_id,
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

				/*list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();
				//Claim > Permissions for logged in User by role_id
				$claimPermission = $this->permissions_lib->getPermissions(FUNCTION_CLAIM);

				$claimParams = array(
					'claim_id' 		=> [$claim_id],
					'role_disp_name' 	=> $role_disp_name,
					'claimPermission'	=> $claimPermission
				);

				$claims = $this->model_claims->getClaimsList($claimParams);
				$claim 	= count($claims) ? $claims[0] : "";

				$customerId 	= isset($claim) && isset($claim->customer_id) && !empty($claim->customer_id) ? $claim->customer_id :  null;
				$contractorId 	= isset($claim) && isset($claim->contractor_id) && !empty($claim->contractor_id) ? $claim->contractor_id :  null;
				$adjusterId 	= isset($claim) && isset($claim->adjuster_id) && !empty($claim->adjuster_id) ? $claim->adjuster_id :  null;

				$customerData 		= null != $customerId ? $this->model_users->getUserDetailsBySno($customerId) : null;
				$contractorsData 	= null;
				$partnersData 		= null;

				//Service Provider Details
				if($contractorId != "") {
					$contractorIdArr = explode(",", $contractorId);
					$contractorsResponse = $this->model_service_providers->get_service_provider_list($contractorIdArr);
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
					'claimData'		=> $claim,
					'taskData'			=> isset( $taskData ) ? $taskData : null,
					'customerData' 		=> $customerData,
					'contractorsData' 	=> $contractorsData,
					'partnersData' 		=> $partnersData,
					'mail_type' 		=> "create"
				);

				$mail_options = $this->model_mail->generateDocsMailOptions( $docsParamsFormMail );
				
				$response['mail_content'] = $mail_options;
				for($i = 0; $i < count($mail_options); $i++) {
					$response["mail_error"] = $this->model_mail->sendMail( $mail_options[$i] );
				}*/
			}
		} else {
			$response["status"] 	= "error";
			$response["message"] 	= "File missing.. Try again";
		}

		print_r(json_encode($response));
	}

	public function downloadAttachment() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$claimPermission 	= $this->permissions_lib->getPermissions(FUNCTION_CLAIM);
		$docsPermission 	= $this->permissions_lib->getPermissions(FUNCTION_CLAIM_DOCS);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $claimPermission['operation']) || !in_array(OPERATION_VIEW, $docsPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "document list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}
		
		if(isset($this->function)) {
			$this->load->model('claims/model_docs');
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
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}
		
		$is_allowed_claim 	= $this->permissions_lib->is_allowed(FUNCTION_CLAIM, OPERATION_DELETE);
		$is_allowed 		= $this->permissions_lib->is_allowed(FUNCTION_CLAIM_DAIRY_UPDATES, OPERATION_DELETE);

		if(!$is_allowed_claim["status"] || !$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('claims/model_docs');
		//$this->load->model('claims/model_claims');
		//$this->load->model('security/model_users');
		//$this->load->model('service_providers/model_service_providers');
		//$this->load->model('adjusters/model_partners');
		//$this->load->model('mail/model_mail');

		$docId = $this->input->post('doc_id');
		
		$docsResponse 	= $this->model_docs->getDocsList("", $docId);

		$response = $this->model_docs->deleteRecord($docId);

		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();
		//Claim > Permissions for logged in User by role_id
		$claimPermission = $this->permissions_lib->getPermissions(FUNCTION_CLAIM);

		/*if(isset($docsResponse["docs"])) {
			$docs = $docsResponse['docs'][0];

			$claim_id = $docs->claim_id;

			$claimParams = array(
				'claim_id' 		=> [$claim_id],
				'role_disp_name' 	=> $role_disp_name,
				'claimPermission'	=> $claimPermission
			);

			$claims = $this->model_claims->getClaimsList($claimParams);
			$claim 	= count($claims) ? $claims[0] : "";

			$customerId 	= isset($claim) && isset($claim->customer_id) && !empty($claim->customer_id) ? $claim->customer_id :  null;
			$contractorId 	= isset($claim) && isset($claim->contractor_id) && !empty($claim->contractor_id) ? $claim->contractor_id :  null;
			$adjusterId 	= isset($claim) && isset($claim->adjuster_id) && !empty($claim->adjuster_id) ? $claim->adjuster_id :  null;

			$customerData 		= null != $customerId ? $this->model_users->getUserDetailsBySno($customerId) : null;
			$contractorsData 	= null;
			$partnersData 		= null;

			//Service Provider Details
			if($contractorId != "") {
				$contractorIdArr = explode(",", $contractorId);
				$contractorsResponse = $this->model_service_providers->get_service_provider_list($contractorIdArr);
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
				'claimData'		=> $claim,
				'taskData'			=> isset( $taskData ) ? $taskData : null,
				'customerData' 		=> $customerData,
				'contractorsData' 	=> $contractorsData,
				'partnersData' 		=> $partnersData,
				'mail_type' 		=> "delete"
			);

			$mail_options = $this->model_mail->generateDocsMailOptions( $docsParamsFormMail );
			
			$response['mail_content'] = $mail_options;
			for($i = 0; $i < count($mail_options); $i++) {
				$response["mail_error"] = $this->model_mail->sendMail( $mail_options[$i] );
			}
		}*/


		if($response["status"] == "success") {
			$response["docId"] = $docId;
		}

		print_r(json_encode($response));	
	}
}