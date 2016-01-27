<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subrogation extends CI_Controller {

	private function _get_claim_customer_name( $claim_id )
	{
		$customer_name = "-- Not Available --";
		if(!empty($claim_id)) {
			$this->load->model('security/model_users');
			$this->load->model('claims/model_claims');
			
			$getParams = array(
				"claim_id" => $claim_id
			);

			/* Get Client Name from claim table to populate in subrogation input form */
			$claimsResponse = $this->model_claims->getClaimsList($getParams);
			$claims = $claimsResponse["claims"];

			$claim_record_count = count($claims);

			if( $claim_record_count == 1 ) {
				$customer_name = $this->model_users->getUserDisplayName($claims[0]->claim_customer_id);
			}
		}
		return $customer_name;
	}

	private function _get_claim_customer_address( $claim_id, $forForm ) {
		$_customer_address_file = "-- Not Available --";
		if(!empty($claim_id)) {
			$this->load->model('claims/model_claims');
			$this->load->model('security/model_users');
			$this->load->model('utils/model_form_utils');
			
			$get_params = array(
				"claim_id" 	=> $claim_id
			);
			
			$claimsResponse = $this->model_claims->getClaimsList( $get_params );
			$claims = $claimsResponse["claims"];

			$customer_id 			= $claims[0]->claim_customer_id;
			
			if(!empty($customer_id)) {
				$get_params = array(
					"customer_id" 	=> $customer_id
				);
				$usersResponse = $this->model_users->get_user_details_address( $get_params );
				$users = $usersResponse["users"];

				$stateText = !empty($users[0]->addr_state) ? $this->model_form_utils->getCountryStatus($users[0]->addr_state)[0]->name : "";

				$_addressParams = array(
					'addressLine1' 		=> $users[0]->addr1,
					'addressLine2' 		=> $users[0]->addr2,
					'city' 				=> $users[0]->addr_city,
					'country' 			=> $users[0]->addr_country,
					'state'				=> $stateText,
					'zipCode' 			=> $users[0]->addr_pin,
					'requestFrom' 		=> "view", 	
					'id_prefix'			=> "",
				);
				$_customer_address_file = $this->load->view("forms/address", $_addressParams, true);
			}
		}

		return $_customer_address_file;
	}

	private function _get_claimant_address( $claim_id, $subrogation_id, $forForm ) {
		$_addressFile = "-- Not Available --";
		
		if(!empty($claim_id)) {
			$this->load->model('claims/model_subrogation');
			$this->load->model('utils/model_form_utils');
			
			if(!empty($subrogation_id)) {
				$get_params 	= array(
					"subrogation_id" 		=> $subrogation_id,
					"claim_id"				=> $claim_id
				);
				
				$response = $this->model_subrogation->getSubrogationsList( $get_params );
				$subrogation = $response["subrogation"];

				$stateText = !empty($subrogation[0]->addr_state) ? $this->model_form_utils->getCountryStatus($subrogation[0]->addr_state)[0]->name : "";
				$_addressParams = array(
					'addressLine1' 		=> $subrogation[0]->addressLine1,
					'addressLine2' 		=> $subrogation[0]->addressLine2,
					'city' 				=> $subrogation[0]->addr_city,
					'country' 			=> $subrogation[0]->addr_country,
					'state'				=> $stateText,
					'zipCode' 			=> $subrogation[0]->addr_pin,
					'requestFrom' 		=> $forForm,
					'hidden'			=> "",
					'id_prefix'			=> "property_"
				);
			} else {
				$_addressParams = array(
					'addressLine1' 		=> "",
					'addressLine2' 		=> "",
					'city' 				=> "",
					'country' 			=> "",
					'state'				=> "",
					'zipCode' 			=> "",
					'requestFrom' 		=> $forForm,
					'hidden'			=> "",
					'id_prefix'			=> "property_"
				);
			}
			$_addressFile = $this->load->view("forms/address", $_addressParams, true);
			
		}

		return $_addressFile;
	}

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
		$claimPermission 		= $this->permissions_lib->getPermissions('claim');
		$subrogationPermission 	= $this->permissions_lib->getPermissions('claim subrogation');
		$customer_names = array();

		/* If User dont have view permission load No permission page */
		if(!in_array('view', $claimPermission['operation']) || !in_array('view', $subrogationPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Subrogation list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$claim_id = $this->input->post("claim_id");

		$this->load->model('claims/model_subrogation');
		$this->load->model('security/model_users');
		
		$getParams = array(
			"claim_id"		=> $claim_id
		);

		$response = $this->model_subrogation->getSubrogationsList($getParams);

		$subrogation = $response["subrogation"];

		/*for($i = 0, $count = count($subrogation); $i < $count; $i++) {
			$customer_names[$subrogation[$i]->customer_id] = $this->model_users->getUserDisplayName($subrogation[$i]->customer_id);
		}*/

		$params = array(
			'subrogation'			=> $response["subrogation"],
			'role_id' 				=> $this->session->userdata('role_id'),
			'claimPermission' 		=> $claimPermission,
			'subrogationPermission'	=> $subrogationPermission
			//'customer_names'		=> $customer_names
		);
		
		echo $this->load->view("claims/subrogation/viewAll", $params, true);
	}

	public function createForm() {
		//Project > Permissions for logged in User by role_id
		$claimPermission = $this->permissions_lib->getPermissions('claim');
		$subrogationPermission 	= $this->permissions_lib->getPermissions('claim subrogation');

		$claim_id 	= $this->input->post("claim_id");

		/* If User dont have view permission load No permission page */
		if(!in_array('create', $claimPermission['operation']) || !in_array("create", $subrogationPermission["operation"])|| empty($claim_id)) {
			$no_permission_options = array(
				'page_disp_string' => "create subrogation"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$openAs 	= $this->input->post("openAs");

		$this->load->model('security/model_users');

		/* Get Client Name from claim table to populate in subrogation input form */
		$customer_name_from_claim_db = $this->_get_claim_customer_name( $claim_id );

		$customer_communication_address_file = $this->_get_claim_customer_address( $claim_id, "view");
		$claimant_address_file = $this->_get_claimant_address($claim_id, "", "input");

		$customerPermission = $this->permissions_lib->getPermissions('customer');

		$params = array(
			'users' 								=> $this->model_users->getUsersList(),
			'userType' 								=> $this->session->userdata('role_id'),
			'claimant_address_file' 				=> $claimant_address_file,
			'customerPermission'					=> $customerPermission,
			'customer_communication_address_file'	=> $customer_communication_address_file,
			'openAs'								=> $openAs,
			'customer_name_from_claim_db'			=> $customer_name_from_claim_db
		);

		echo $this->load->view("claims/subrogation/inputForm", $params, true);
	}

	public function add() {
		$this->load->model('claims/model_subrogation');
		$this->load->model('mail/model_mail');

		$data = array(
			"claim_id"				=> $this->input->post('claim_id'),
			"climant_name"			=> $this->input->post('climant_name'),
			"addressLine1"			=> $this->input->post('addressLine1'),
			"addressLine2"			=> $this->input->post('addressLine2'),
			"addr_city"				=> $this->input->post('city'),
			"addr_country"			=> $this->input->post('country'),
			"addr_state"			=> $this->input->post('state'),
			"addr_pin"				=> $this->input->post('zipCode'),
			"description"			=> $this->input->post('description'),
			"status"				=> $this->input->post("status"),
			'created_by'			=> $this->session->userdata('user_id'),
			'updated_by'			=> $this->session->userdata('user_id'),
			'created_on'			=> date("Y-m-d H:i:s"),
			'updated_on'			=> date("Y-m-d H:i:s")
		);

		$response = $this->model_subrogation->insert($data);

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
		$subrogationPermission 	= $this->permissions_lib->getPermissions('claim subrogation');

		/* If User dont have view permission load No permission page */
		if(!in_array('view', $claimPermission['operation']) || !in_array("view", $subrogationPermission["operation"])) {
			$no_permission_options = array(
				'page_disp_string' => "view claims"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('claims/model_subrogation');
		$this->load->model('security/model_users');

		$subrogation_id 		= $this->input->post('subrogation_id');
		$claim_id 				= $this->input->post('claim_id');

		$getParams 	= array(
			"subrogation_id" 		=> $subrogation_id,
			"claim_id"				=> $claim_id
		);
		$response 		= $this->model_subrogation->getSubrogationsList( $getParams );

		$subrogation = $response["subrogation"];
			
		$subrogation[0]->customer_name 		= $this->_get_claim_customer_name( $claim_id );
		$subrogation[0]->created_by_name	= $this->model_users->getUsersList($subrogation[0]->created_by)[0]->user_name;
		$subrogation[0]->updated_by_name 	= $this->model_users->getUsersList($subrogation[0]->updated_by)[0]->user_name;
		

		$claimant_address_file = $this->_get_claimant_address($claim_id, $subrogation_id, "view");
		$customer_communication_address_file = $this->_get_claim_customer_address( $claim_id, "view");

		$params = array(
			'subrogation'							=> $subrogation,
			'userType' 								=> $this->session->userdata('role_id'),
			'claim_id' 								=> $claim_id,
			'subrogation_id'						=> $subrogation_id,
			'claimant_address_file' 				=> $claimant_address_file,
			'customer_communication_address_file'	=> $customer_communication_address_file,
			'claimPermission'						=> $claimPermission
		);
		
		echo $this->load->view("claims/subrogation/viewOne", $params, true);
	}

	public function editForm() {
		//Project > Permissions for logged in User by role_id
		$claimPermission = $this->permissions_lib->getPermissions('claim');
		$subrogationPermission 	= $this->permissions_lib->getPermissions('claim subrogation');

		/* If User dont have view permission load No permission page */
		if(!in_array('update', $claimPermission['operation']) || !in_array('update', $subrogationPermission["operation"])) {
			$no_permission_options = array(
				'page_disp_string' => "update subrogation"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('claims/model_subrogation');
		$this->load->model('security/model_users');

		$subrogation_id 		= $this->input->post('subrogation_id');
		$claim_id 				= $this->input->post('claim_id');
		$openAs 				= $this->input->post('openAs');

		$getParams 	= array(
			"subrogation_id" 		=> $subrogation_id,
			"claim_id"				=> $claim_id
		);
		$response 		= $this->model_subrogation->getSubrogationsList( $getParams );

		/* Get Client Name from claim table to populate in subrogation input form */
		$customer_name_from_claim_db = $this->_get_claim_customer_name( $claim_id );

		$customer_communication_address_file = $this->_get_claim_customer_address( $claim_id, "view");
		$claimant_address_file = $this->_get_claimant_address($claim_id, $subrogation_id, "input");

		$subrogation = $response["subrogation"];
		
		$customerPermission = $this->permissions_lib->getPermissions('customer');

		$params = array(
			'users' 								=> $this->model_users->getUsersList(),
			'subrogation'							=> $subrogation,
			'userType' 								=> $this->session->userdata('role_id'),
			'claimant_address_file' 				=> $claimant_address_file,
			'customerPermission'					=> $customerPermission,
			'customer_communication_address_file'	=> $customer_communication_address_file,
			'openAs'								=> $openAs,
			'customer_name_from_claim_db'			=> $customer_name_from_claim_db
		);

		echo $this->load->view("claims/subrogation/inputForm", $params, true);
	}

	public function update() {
		$this->load->model('claims/model_subrogation');
		$this->load->model('mail/model_mail');

		$claim_id = $this->input->post("claim_id");
		$subrogation_id = $this->input->post("subrogation_id");

		$data = array(
			"climant_name"			=> $this->input->post('climant_name'),
			"addressLine1"			=> $this->input->post('addressLine1'),
			"addressLine2"			=> $this->input->post('addressLine2'),
			"addr_city"				=> $this->input->post('city'),
			"addr_country"			=> $this->input->post('country'),
			"addr_state"			=> $this->input->post('state'),
			"addr_pin"				=> $this->input->post('zipCode'),
			"description"			=> $this->input->post('description'),
			"status"				=> $this->input->post("status"),
			'updated_by'			=> $this->session->userdata('user_id'),
			'updated_on'			=> date("Y-m-d H:i:s")
		);

		$update_params = array(
			"data"				=> $data,
			"claim_id"			=> $claim_id,
			"subrogation_id"	=> $subrogation_id
		);

		$response = $this->model_subrogation->update($update_params);

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
		$this->load->model('claims/model_subrogation');
		$this->load->model('mail/model_mail');

		$claim_id = $this->input->post('claim_id');
		$delete_claim = $this->model_subrogation->deleteRecord($claim_id);

		print_r(json_encode($delete_claim));
	}
}