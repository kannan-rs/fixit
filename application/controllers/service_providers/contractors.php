<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contractors extends CI_Controller {

	public function __construct()
   	{
        parent::__construct();
	}

	public function getList() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');

		$serviceZip = trim($this->input->post("serviceZip")) ? explode(",", trim($this->input->post("serviceZip"))) : null;
		$zip 		= trim($this->input->post("zip")) ? trim($this->input->post("zip")) : null;
		$records 	= trim($this->input->post("records")) ? explode(",", trim($this->input->post("records"))) : null;
		
		$contractorsResponse = $this->model_service_providers->get_service_provider_list( $records, $zip, $serviceZip );

		print_r(json_encode($contractorsResponse));
	}

	public function viewAll() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $contractorPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Service Provider List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('security/model_users');
		
		$contractorsResponse = $this->model_service_providers->get_service_provider_list();

		$contractors = $contractorsResponse["contractors"];

		for( $i = 0; $i < count($contractors); $i++) {
			$contractors[$i]->default_contact_user_disp_str = $this->model_users->getUserDisplayNameWithEmail($contractors[$i]->default_contact_user_id);
		}

		$params = array(
			'contractors'		=> $contractors,
			'role_id' 			=> $this->session->userdata('logged_in_role_id'),
			'role_disp_name'	=> $this->session->userdata('logged_in_role_disp_name')
		);
		
		echo $this->load->view("service_providers/contractors/viewAll", $params, true);
	}
	

	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $contractorPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Create Service Provider"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Get Role ID and Role Display String*/
		$role_id 		= $this->session->userdata('logged_in_role_id'); 
		$role_disp_name = $this->session->userdata('logged_in_role_disp_name');

		$this->load->model('security/model_users');

		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$addressParams = array(
			'forForm' 			=> "create_contractor_form",
			'role_id' 			=> $role_id,
			'role_disp_name'	=> $role_disp_name,
			'requestFrom'		=> "input"
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);

		// Remove the already existing trades from All Trades
		$params = array(
			'users' 				=> $this->model_users->getUsersList(),
			'role_id' 				=> $role_id,
			'role_disp_name'		=> $role_disp_name,
			'openAs' 				=> $openAs,
			'addressFile' 			=> $addressFile,
			'popupType' 			=> $popupType
		);

		echo $this->load->view("service_providers/contractors/inputForm", $params, true);
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER, OPERATION_CREATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('mail/model_mail');

		$data = array(
			//'name' 					=> $this->input->post('name'),
			'company' 					=> $this->input->post('company'),
			'type' 						=> $this->input->post('type'),
			'license' 					=> $this->input->post('license'),
			//'bbb' 					=> $this->input->post('bbb'),
			'status' 					=> $this->input->post('status'),
			'address1' 					=> $this->input->post('addressLine1'),
			'address2'					=> $this->input->post('addressLine2'),
			'city' 						=> $this->input->post('city'),
			'state' 					=> $this->input->post('state'),
			'country' 					=> $this->input->post('country'),
			'zip_code' 					=> $this->input->post('zipCode'),
			'office_email' 				=> $this->input->post('emailId'),
			'office_ph' 				=> $this->input->post('contactPhoneNumber'),
			'mobile_ph' 				=> $this->input->post('mobileNumber'),
			'prefer' 					=> $this->input->post('prefContact'),
			'default_contact_user_id'	=> $this->input->post('db_default_user_id'),
			'website_url' 				=> $this->input->post('websiteURL'),
			'service_area' 				=> $this->input->post('serviceZip'),
			'created_by'				=> $this->session->userdata('logged_in_user_id'),
			'updated_by'				=> $this->session->userdata('logged_in_user_id'),
			'created_on'				=> date("Y-m-d H:i:s"),
			'updated_on'				=> date("Y-m-d H:i:s")
		);

		$response = $this->model_service_providers->insert($data);

		$contractorCompanyParamsFormMail = array(
			'response'				=> $response,
			'contractorData'		=> $data
		);

		$mail_options = $this->model_mail->generateCreateContractorCompanyMailOptions( $contractorCompanyParamsFormMail );
		
		$response['mail_content'] = $mail_options;

		$response["mail_error"] = $this->model_mail->sendMail( $mail_options );

		print_r(json_encode($response));
	}

	public function viewOne() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $contractorPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Service Provider details"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('security/model_users');
		$this->load->model('utils/model_form_utils');

		$contractorId 			= $this->input->post('contractorId');
		$openAs		 			= $this->input->post('openAs');

		$contractorsResponse 	= $this->model_service_providers->get_service_provider_list($contractorId);

		$contractors = $contractorsResponse["contractors"];

		
		for($i=0; $i < count($contractors); $i++) {
			$contractors[$i]->created_by_name = $this->model_users->getUsersList($contractors[$i]->created_by)[0]->user_name;
			$contractors[$i]->updated_by_name = $this->model_users->getUsersList($contractors[$i]->updated_by)[0]->user_name;
		}

		$stateFromDb = $this->model_form_utils->getCountryStatus($contractors[0]->state);
		
		$stateName = count($stateFromDb) ? $stateFromDb[0]->name: "";
		$stateText = !empty($contractors[0]->state) ? $stateName : "";

		$addressFile = $this->form_lib->getAddressFile(array("requestFrom" => "view", "address_data" => $contractors[0]));

		$contractors[0]->default_contact_user_disp_str = $this->model_users->getUserDisplayNameWithEmail($contractors[0]->default_contact_user_id);
		$contractors[0]->created_by = $this->model_users->getUserDisplayNameWithEmail($contractors[0]->created_by);
		$contractors[0]->updated_by = $this->model_users->getUserDisplayNameWithEmail($contractors[0]->updated_by);

		$params = array(
			'contractors'			=> $contractors,
			'userType' 				=> $this->session->userdata('logged_in_role_id'),
			'contractorId' 			=> $contractorId,
			'addressFile' 			=> $addressFile,
			'openAs' 				=> $openAs,
			'contractorPermission'	=> $contractorPermission,
			'role_id'				=> $this->session->userdata('logged_in_role_id'),
			'role_disp_name'		=> $this->session->userdata('logged_in_role_disp_name')
		);
		
		echo $this->load->view("service_providers/contractors/viewOne", $params, true);
	}

	public function editForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_UPDATE, $contractorPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Update Service Provider"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$role_id 		= $this->session->userdata('logged_in_role_id');
		$role_disp_name = $this->session->userdata('logged_in_role_disp_name');

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('security/model_users');

		$contractorId = $this->input->post('contractorId');
		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$contractorsResponse = $this->model_service_providers->get_service_provider_list($contractorId);
		$contractors = $contractorsResponse["contractors"];

		$addressFile = $this->form_lib->getAddressFile(array("view" => "update_contractor_form", "requestFrom" => "input", "address_data" => $contractors[0]));

		if(!empty($contractors[0]->default_contact_user_id)) {
			$contractors[0]->default_contact_user_disp_str = $this->model_users->getUserDisplayNameWithEmail($contractors[0]->default_contact_user_id);
		}

		$params = array(
			'contractors' 		=> $contractors,
			'addressFile' 		=> $addressFile,
			'userType' 			=> $this->session->userdata('logged_in_role_id'),
			'openAs' 			=> $openAs,
			'popupType' 		=> $popupType,
			'role_id'			=> $role_id,
			'role_disp_name'	=> $role_disp_name
		);
		
		echo $this->load->view("service_providers/contractors/inputForm", $params, true);
	}

	public function update() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER, OPERATION_UPDATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('mail/model_mail');

		$contractorId 			= $this->input->post('contractorId');

		$data = array(
			//'name' 					=> $this->input->post('name'),
			'company' 					=> $this->input->post('company'),
			'type' 						=> $this->input->post('type'),
			'license' 					=> $this->input->post('license'),
			//'bbb' 					=> $this->input->post('bbb'),
			'status' 					=> $this->input->post('status'),
			'address1' 					=> $this->input->post('addressLine1'),
			'address2'					=> $this->input->post('addressLine2'),
			'city' 						=> $this->input->post('city'),
			'state' 					=> $this->input->post('state'),
			'country' 					=> $this->input->post('country'),
			'zip_code' 					=> $this->input->post('zipCode'),
			'office_email' 				=> $this->input->post('emailId'),
			'office_ph' 				=> $this->input->post('contactPhoneNumber'),
			'mobile_ph' 				=> $this->input->post('mobileNumber'),
			'default_contact_user_id'	=> $this->input->post('db_default_user_id'),
			'prefer' 					=> $this->input->post('prefContact'),
			'website_url' 				=> $this->input->post('websiteURL'),
			'service_area' 				=> $this->input->post('serviceZip'),
			'updated_by'				=> $this->session->userdata('logged_in_user_id'),
			'updated_on'				=> date("Y-m-d H:i:s")
		);

		$response = $this->model_service_providers->update($data, $contractorId);

		/*if($response["status"] == "success") {
			// Update belongs_to_id in user
			$this->load->model('security/model_users');
			$this->load->model('security/model_roles');
			$params = array(
				"belongs_to_id" => $this->model_roles->get_role_id_from_user_table_by_user_id($default_user_details_id)
			);

			$default_user_details_id 	= $thi->model_users->get_user_details_id_from_user_id($this->input->post('db_default_user_id'));
			if($default_user_email) {
				$user_update_response 	= $this->model_users->updateDetailsTable( $params, $default_user_details_id);

				if($user_update_response["status"] != "failed") {
					$response["status"] 	= $user_update_response["status"];
					$response["message"] 	= $user_update_response["message"];
				}
			}
		}*/

		$contractorCompanyParamsFormMail = array(
			'response'				=> $response,
			'contractorData'		=> $data
		);

		$mail_options = $this->model_mail->generateUpdateContractorCompanyMailOptions( $contractorCompanyParamsFormMail );
		
		$response['mail_content'] = $mail_options;
		$response["mail_error"] = $this->model_mail->sendMail( $mail_options );

		print_r(json_encode($response));
	}

	public function deleteRecord() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}
		
		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER, OPERATION_DELETE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('mail/model_mail');

		$contractorId = $this->input->post('contractorId');
		$delete_contractor = $this->model_service_providers->deleteRecord($contractorId);

		print_r(json_encode($delete_contractor));	
	}
}