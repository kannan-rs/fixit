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
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$claimPermission = $this->permissions_lib->getPermissions(FUNCTION_CLAIM);
		$customer_names = array();

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $claimPermission['operation'])) {
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
			'role_id' 				=> $this->session->userdata('logged_in_role_id'),
			'claimPermission' 		=> $claimPermission,
			'customer_names'		=> $customer_names
		);
		
		echo $this->load->view("claims/claims/viewAll", $params, true);
	}

	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$claimPermission = $this->permissions_lib->getPermissions(FUNCTION_CLAIM);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $claimPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "create claims"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('security/model_users');

		$_property_address_params = array(
			'forForm' 		=> "create_claim_form",
			'id_prefix'		=> "property_",
			"requestFrom"	=> "input"
		);

		$_property_address_file = $this->load->view("forms/address", $_property_address_params, true);
		
		$customerPermission = $this->permissions_lib->getPermissions(FUNCTION_CUSTOMER);

		$params = array(
			'users' 				=> $this->model_users->getUsersList(),
			'customer_address_file' => "",
			'userType' 				=> $this->session->userdata('logged_in_role_id'),
			'property_address_file' => $_property_address_file,
			'customerPermission'	=> $customerPermission
		);

		echo $this->load->view("claims/claims/inputForm", $params, true);
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed 	= $this->permissions_lib->is_allowed(FUNCTION_CLAIM, OPERATION_CREATE);

		if( !$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('claims/model_claims');
		$this->load->model('mail/model_mail');

		$is_property_address_same = $this->input->post("is_property_address_same") == "on" ? 1 : 0;

		$data = array(
			'claim_customer_id' 	=> $this->input->post('customer_id'),
			
			"is_property_address_same"	=> $is_property_address_same,
			"property_address1" 		=> $this->input->post("property_address1"),
			"property_address2" 		=> $this->input->post("property_address2"),
			"property_city" 			=> $this->input->post("property_city"),
			"property_country" 			=> $this->input->post("property_country"),
			"property_state" 			=> $this->input->post("property_state"),
			"property_zip_code" 		=> $this->input->post("property_zip_code"),

			'customer_contact_no'	=> $this->input->post('contactPhoneNumber'),
			'customer_email_id' 	=> $this->input->post('emailId'),
			'claim_number' 			=> $this->input->post('claim_number'),
			'claim_description' 	=> $this->input->post('description'),
			'created_by'			=> $this->session->userdata('logged_in_user_id'),
			'updated_by'			=> $this->session->userdata('logged_in_user_id'),
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
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$claimPermission = $this->permissions_lib->getPermissions(FUNCTION_CLAIM);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $claimPermission['operation'])) {
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
			"claim_id" 		=> $claim_id
		);
		$claimsResponse 		= $this->model_claims->getClaimsList( $getParams );

		$claims = $claimsResponse["claims"];

		$customer_id 			= $claims[0]->claim_customer_id;
		$customer_address_file 	= "-- Not Available --";
		if(!empty($customer_id)) {
			$get_params = array(
				"customer_id" 	=> $customer_id
			);
			$usersResponse = $this->model_users->get_user_details_address( $get_params );
			$users = $usersResponse["users"];

			$_customer_address_file = $this->form_lib->getAddressFile(array("view" => "view", "address_data" => $users[0]));
		}
		
		$claims[0]->customer_name 		= $this->model_users->getUserDisplayNameWithEmail($claims[0]->claim_customer_id);
		$claims[0]->created_by_name		= $this->model_users->getUsersList($claims[0]->created_by)[0]->user_name;
		$claims[0]->updated_by_name 	= $this->model_users->getUsersList($claims[0]->updated_by)[0]->user_name;

		$stateText = !empty($claims[0]->property_state) ? $this->model_form_utils->getCountryStatus($claims[0]->property_state)[0]->name : "";

		$_addressParams = array(
			'addressLine1' 		=> $claims[0]->property_address1,
			'addressLine2' 		=> $claims[0]->property_address2,
			'city' 				=> $claims[0]->property_city,
			'country' 			=> $claims[0]->property_country,
			'state'				=> $stateText,
			'zipCode' 			=> $claims[0]->property_zip_code,
			'requestFrom' 		=> 'view'
		);

		$_property_address_file = $this->load->view("forms/address", $_addressParams, true);

		//$addressFile = $this->_get_claim_customer_address( $claim_id, "view" );

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
			'claims'					=> $claims,
			'budget_details'			=> isset($project_with_claim) ? $project_with_claim : [],
			'userType' 					=> $this->session->userdata('logged_in_role_id'),
			'claim_id' 					=> $claim_id,
			'property_address_file' 	=> $_property_address_file,
			'customer_address_file' 	=> $_customer_address_file,
			'claimPermission'			=> $claimPermission
		);
		
		echo $this->load->view("claims/claims/viewOne", $params, true);
	}

	public function editForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$claimPermission = $this->permissions_lib->getPermissions(FUNCTION_CLAIM);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_UPDATE, $claimPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "update claim"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('claims/model_claims');
		$this->load->model('security/model_users');
		$this->load->model('utils/model_form_utils');

		$claim_id = $this->input->post('claim_id');
		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$get_params = array(
			"claim_id" 	=> $claim_id
		);
		$claimsResponse = $this->model_claims->getClaimsList( $get_params );
		$claims = $claimsResponse["claims"];

		$customer_id 			= $claims[0]->claim_customer_id;
		$customer_address_file 	= "-- Not Available --";
		if(!empty($customer_id)) {
			$get_params = array(
				"customer_id" 	=> $customer_id
			);
			$usersResponse = $this->model_users->get_user_details_address( $get_params );
			$users = $usersResponse["users"];

			$_customer_address_file = $this->form_lib->getAddressFile(array("view" => "view", "address_data" => $users[0], "requestFrom" => "both", "hidden" => "hidden"));

			$customer_address_file = "<table><tbody>".$_customer_address_file."</tbody></table>";
		}

		$_property_address_params = array(
			'addressLine1' 		=> $claims[0]->property_address1,
			'addressLine2' 		=> $claims[0]->property_address2,
			'city' 				=> $claims[0]->property_city,
			'country' 			=> $claims[0]->property_country,
			'state'				=> $claims[0]->property_state,
			'zipCode' 			=> $claims[0]->property_zip_code,
			'forForm' 			=> "update_claim_form",
			'requestFrom'		=> "input",
			"hidden"			=> "",
			'id_prefix'		=> "property_",
		);

		$_property_address_file = $this->load->view("forms/address", $_property_address_params, true);

		$customerPermission = $this->permissions_lib->getPermissions(FUNCTION_CUSTOMER);

		$params = array(
			'claims' 				=> $claims,
			'customer_address_file'	=> $customer_address_file,
			'property_address_file' => $_property_address_file,
			'userType' 				=> $this->session->userdata('logged_in_role_id'),
			'openAs' 				=> $openAs,
			'popupType' 			=> $popupType,
			'customerPermission'	=> $customerPermission
		);
		
		echo $this->load->view("claims/claims/inputForm", $params, true);
	}

	public function update() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed 	= $this->permissions_lib->is_allowed(FUNCTION_CLAIM, OPERATION_UPDATE);

		if( !$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('claims/model_claims');
		$this->load->model('mail/model_mail');

		$is_property_address_same = $this->input->post("is_property_address_same") == "on" ? 1 : 0;
		$claim_id = $this->input->post("claim_id");

		$data = array(
			'claim_customer_id' 	=> $this->input->post('customer_id'),
			
			"is_property_address_same"	=> $is_property_address_same,
			"property_address1" 		=> $this->input->post("property_addr1"),
			"property_address2" 		=> $this->input->post("property_addr2"),
			"property_city" 			=> $this->input->post("property_addr_city"),
			"property_country" 			=> $this->input->post("property_addr_country"),
			"property_state" 			=> $this->input->post("property_addr_state"),
			"property_zip_code" 		=> $this->input->post("property_addr_pin"),

			'customer_contact_no'	=> $this->input->post('contactPhoneNumber'),
			'customer_email_id' 	=> $this->input->post('emailId'),
			'claim_number' 			=> $this->input->post('claim_number'),
			'claim_description' 	=> $this->input->post('description'),
			'updated_by'			=> $this->session->userdata('logged_in_user_id'),
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
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed 	= $this->permissions_lib->is_allowed(FUNCTION_CLAIM, OPERATION_DELETE);

		if( !$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('claims/model_claims');
		$this->load->model('mail/model_mail');

		$claim_id = $this->input->post('claim_id');
		$delete_claim = $this->model_claims->deleteRecord($claim_id);

		print_r(json_encode($delete_claim));
	}

	public function customer_address() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}
		
		$is_allowed 	= $this->permissions_lib->is_allowed(FUNCTION_CLAIM, OPERATION_VIEW);

		if( !$is_view_allowed["status"]) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$customer_id 	= $this->input->post('customer_id');
		$claim_id 		= $this->input->post("claim_id");

		$_addressFile = "-- Not Available --";
		if(!empty($customer_id)) {
			$this->load->model('security/model_users');
			$get_params = array(
				"customer_id" 	=> $customer_id
			);
			$usersResponse = $this->model_users->get_user_details_address( $get_params );
			$users = $usersResponse["users"];

			$_addressFile = $this->form_lib->getAddressFile(array("requestFrom" => "both", "hidden" => "hidden", "address_data" => $users[0]));
		}

		echo $_addressFile;
	}
}