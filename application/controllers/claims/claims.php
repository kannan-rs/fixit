<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Claims extends CI_Controller {

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
		//Project > Permissions for logged in User by role_id
		$claimPermission = $this->permissions_lib->getPermissions('claim');
		$customer_names = array();

		/* If User dont have view permission load No permission page */
		if(!in_array('view', $claimPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Claims list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('claims/model_claims');
		$this->load->model('security/model_users');
		
		$getParams = array();

		$claimsResponse = $this->model_claims->getClaimsList($getParams);

		$claims = $claimsResponse["claims"];

		for($i = 0, $count = count($claims); $i < $count; $i++) {
			$customer_names[$claims[$i]->claim_customer_id] = $this->model_users->getUserDisplayName($claims[$i]->claim_customer_id);
		}

		$params = array(
			'claims'				=>$claimsResponse["claims"],
			'role_id' 				=> $this->session->userdata('role_id'),
			'claimPermission' 		=> $claimPermission,
			'customer_names'		=> $customer_names
		);
		
		echo $this->load->view("claims/claims/viewAll", $params, true);
	}

	public function createForm() {
		//Project > Permissions for logged in User by role_id
		$claimPermission = $this->permissions_lib->getPermissions('claim');

		/* If User dont have view permission load No permission page */
		if(!in_array('create', $claimPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "create claims"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('security/model_users');

		$addressParams = array(
			'forForm' 		=> "create_claim_form"
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);
		
		$customerPermission = $this->permissions_lib->getPermissions('customer');

		$params = array(
			'users' 				=> $this->model_users->getUsersList(),
			'userType' 				=> $this->session->userdata('role_id'),
			'addressFile' 			=> $addressFile,
			'customerPermission'	=> $customerPermission
		);

		echo $this->load->view("claims/claims/inputForm", $params, true);
	}

	public function add() {
		$this->load->model('claims/model_claims');
		$this->load->model('mail/model_mail');

		$data = array(
			'claim_customer_id' 	=> $this->input->post('customer_id'),
			'addr1' 				=> $this->input->post('addressLine1'),
			'addr2' 				=> $this->input->post('addressLine2'),
			'addr_city' 			=> $this->input->post('city'),
			'addr_country'			=> $this->input->post('country'),
			'addr_state' 			=> $this->input->post('state'),
			'addr_pin' 				=> $this->input->post('zipCode'),
			'customer_contact_no'	=> $this->input->post('contactPhoneNumber'),
			'customer_email_id' 	=> $this->input->post('emailId'),
			'claim_number' 			=> $this->input->post('claim_number'),
			'claim_description' 	=> $this->input->post('description'),
			'created_by'			=> $this->session->userdata('user_id'),
			'updated_by'			=> $this->session->userdata('user_id'),
			'created_on'			=> date("Y-m-d H:i:s"),
			'updated_on'			=> date("Y-m-d H:i:s")
		);

		$response = $this->model_claims->insert($data);

		/*$claimParamsFormMail = array(
			'response'			=> $response,
			'claimData'		=> $data
		);

		$mail_options = $this->model_mail->generateCreateClaimMailOptions( $claimParamsFormMail );
		
		$response['mail_content'] = $mail_options;
		$response["mail_error"] = $this->model_mail->sendMail( $mail_options );*/

		print_r(json_encode($response));
	}

	public function viewOne() {
		//Project > Permissions for logged in User by role_id
		$claimPermission = $this->permissions_lib->getPermissions('claim');

		/* If User dont have view permission load No permission page */
		if(!in_array('view', $claimPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "view claims"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('claims/model_claims');
		$this->load->model('security/model_users');
		$this->load->model('utils/model_form_utils');
		$this->load->model('projects/model_remainingbudget');

		$claim_id 				= $this->input->post('claim_id');

		$getParams 	= array(
			"record" 		=> $claim_id
		);
		$claimsResponse 		= $this->model_claims->getClaimsList( $getParams );

		$claims = $claimsResponse["claims"];
		
		for($i=0; $i < count($claims); $i++) {
			$claims[$i]->customer_name 		= $this->model_users->getUserDisplayName($claims[$i]->claim_customer_id);
			$claims[$i]->created_by_name	= $this->model_users->getUsersList($claims[$i]->created_by)[0]->user_name;
			$claims[$i]->updated_by_name 	= $this->model_users->getUsersList($claims[$i]->updated_by)[0]->user_name;
		}

		$stateText = !empty($claims[0]->addr_state) ? $this->model_form_utils->getCountryStatus($claims[0]->addr_state)[0]->name : "";

		$addressParams = array(
			'addressLine1' 		=> $claims[0]->addr1,
			'addressLine2' 		=> $claims[0]->addr2,
			'city' 				=> $claims[0]->addr_city,
			'country' 			=> $claims[0]->addr_country,
			'state'				=> $stateText,
			'zipCode' 			=> $claims[0]->addr_pin,
			'requestFrom' 		=> 'view'
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);

		/*
		Get Project with the Claim details
		*/
		$project_with_claim_response = $this->model_claims->getProjectClaim($claims[0]->claim_number);

		if($project_with_claim_response["status"] == "success") {
			$project_with_claim = $project_with_claim_response["projects"];
			for($i = 0, $count= count($project_with_claim); $i < $count; $i++) {
				$project_with_claim[$i]->remediation_payment = $this->model_remainingbudget->getPaidBudgetSum($project_with_claim[$i]->proj_id);
			}
		}

		$params = array(
			'claims'				=> $claims,
			'budget_details'		=> isset($project_with_claim) ? $project_with_claim : [],
			'userType' 				=> $this->session->userdata('role_id'),
			'claim_id' 				=> $claim_id,
			'addressFile' 			=> $addressFile,
			'claimPermission'		=> $claimPermission
		);
		
		echo $this->load->view("claims/claims/viewOne", $params, true);
	}

	public function editForm() {
		//Project > Permissions for logged in User by role_id
		$claimPermission = $this->permissions_lib->getPermissions('claim');

		/* If User dont have view permission load No permission page */
		if(!in_array('update', $claimPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "update claim"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('claims/model_claims');

		$claim_id = $this->input->post('claim_id');
		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$get_params = array(
			"record" 	=> $claim_id
		);
		$claimsResponse = $this->model_claims->getClaimsList( $get_params );
		$claims = $claimsResponse["claims"];

		$addressParams = array(
			'addressLine1' 		=> $claims[0]->addr1,
			'addressLine2' 		=> $claims[0]->addr2,
			'city' 				=> $claims[0]->addr_city,
			'country' 			=> $claims[0]->addr_country,
			'state'				=> $claims[0]->addr_state,
			'zipCode' 			=> $claims[0]->addr_pin,
			'forForm' 			=> "update_claim_form"
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);

		$customerPermission = $this->permissions_lib->getPermissions('customer');

		$params = array(
			'claims' 				=> $claims,
			'addressFile' 			=> $addressFile,
			'userType' 				=> $this->session->userdata('role_id'),
			'openAs' 				=> $openAs,
			'popupType' 			=> $popupType,
			'customerPermission'	=> $customerPermission
		);
		
		echo $this->load->view("claims/claims/inputForm", $params, true);
	}

	public function update() {
		$this->load->model('claims/model_claims');
		$this->load->model('mail/model_mail');

		$claim_id = $this->input->post("claim_id");

		$data = array(
			'claim_customer_id' 	=> $this->input->post('customer_id'),
			'addr1' 				=> $this->input->post('addressLine1'),
			'addr2' 				=> $this->input->post('addressLine2'),
			'addr_city' 			=> $this->input->post('city'),
			'addr_country'			=> $this->input->post('country'),
			'addr_state' 			=> $this->input->post('state'),
			'addr_pin' 				=> $this->input->post('zipCode'),
			'customer_contact_no'	=> $this->input->post('contactPhoneNumber'),
			'customer_email_id' 	=> $this->input->post('emailId'),
			'claim_number' 			=> $this->input->post('claim_number'),
			'claim_description' 	=> $this->input->post('description'),
			'updated_by'			=> $this->session->userdata('user_id'),
			'updated_on'			=> date("Y-m-d H:i:s")
		);

		$response = $this->model_claims->update($data, $claim_id);

		/*$claimParamsFormMail = array(
			'response'			=> $response,
			'claimData'			=> $data
		);

		$mail_options = $this->model_mail->generateUpdateClaimMailOptions( $claimParamsFormMail );
		
		$response['mail_content'] = $mail_options;
		$response["mail_error"] = $this->model_mail->sendMail( $mail_options );*/

		print_r(json_encode($response));
	}

	public function deleteRecord() {
		$this->load->model('claims/model_claims');
		$this->load->model('mail/model_mail');

		$claim_id = $this->input->post('claim_id');
		$delete_claim = $this->model_claims->deleteRecord($claim_id);

		print_r(json_encode($delete_claim));
	}
}