<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Insurancecompany extends CI_Controller {

	public function __construct()
   	{
        parent::__construct();
	}

	public function getList() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$this->load->model('ins_comp/model_ins_comp');

		//$zip 		= trim($this->input->post("zip")) ? trim($this->input->post("zip")) : null;
		$records 	= trim($this->input->post("records")) ? explode(",", trim($this->input->post("records"))) : null;
		
		$ins_comps_response = $this->model_ins_comp->get_ins_comp_list( $records );

		print_r(json_encode($ins_comps_response));
	}

	public function get_ins_comp_by_name() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed 	= $this->permissions_lib->is_allowed(FUNCTION_PARTNER, OPERATION_VIEW);

		if( !$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}
		
		$this->load->model('ins_comp/model_ins_comp');
		
		$companyName = explode(",", $this->input->post('companyName'));
		$record = "";

		$partnersResponse = $this->model_ins_comp->get_ins_comp_list($record, $companyName);

		print_r(json_encode($partnersResponse));	
	}

	public function viewAll() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$ins_comp_permission 	= $this->permissions_lib->getPermissions(FUNCTION_INSURANCE_COMPANY);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $ins_comp_permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Insurance Company List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('ins_comp/model_ins_comp');
		$this->load->model('security/model_users');
		
		$ins_comps_response = $this->model_ins_comp->get_ins_comp_list();

		$ins_comps = $ins_comps_response["ins_comps"];

		for( $i = 0; $i < count($ins_comps); $i++) {
			$ins_comps[$i]->default_contact_user_disp_str = $this->model_users->getUserDisplayNameWithEmail($ins_comps[$i]->default_contact_user_id);
		}

		$params = array(
			'ins_comps'			=> $ins_comps,
			'role_id' 			=> $this->session->userdata('logged_in_role_id'),
			'role_disp_name'	=> $this->session->userdata('logged_in_role_disp_name')
		);
		
		echo $this->load->view("ins_comp/ins_comp/viewAll", $params, true);
	}
	

	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$ins_comp_permission 	= $this->permissions_lib->getPermissions(FUNCTION_INSURANCE_COMPANY);
		
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $ins_comp_permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Create Insurance Company"
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
			'forForm' 			=> "create_ins_comp_form",
			'role_id' 			=> $role_id,
			'role_disp_name'	=> $role_disp_name,
			'requestFrom'		=> "input"
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);

		// Remove the already existing trades from All Trades
		$params = array(
			//'users' 				=> $this->model_users->getUsersList(),
			'role_id' 				=> $role_id,
			'role_disp_name'		=> $role_disp_name,
			'openAs' 				=> $openAs,
			'addressFile' 			=> $addressFile,
			'popupType' 			=> $popupType
		);

		echo $this->load->view("ins_comp/ins_comp/inputForm", $params, true);
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_INSURANCE_COMPANY, OPERATION_CREATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('ins_comp/model_ins_comp');
		$this->load->model('mail/model_mail');

		$data = array(
			'ins_comp_name' 			=> $this->input->post('company'),
			'email_id' 					=> $this->input->post('emailId'),
			'contact_no' 				=> $this->input->post('contactPhoneNumber'),
			'address1' 					=> $this->input->post('addressLine1'),
			'address2'					=> $this->input->post('addressLine2'),
			'city' 						=> $this->input->post('city'),
			'state' 					=> $this->input->post('state'),
			'country' 					=> $this->input->post('country'),
			'zip_code' 					=> $this->input->post('zipCode'),
			'default_contact_user_id'	=> $this->input->post('db_default_user_id'),
			'website_url' 				=> $this->input->post('websiteURL'),
			'created_by'				=> $this->session->userdata('logged_in_user_id'),
			'updated_by'				=> $this->session->userdata('logged_in_user_id'),
			'created_on'				=> date("Y-m-d H:i:s"),
			'updated_on'				=> date("Y-m-d H:i:s")
		);

		$response = $this->model_ins_comp->insert($data);

		/*$ins_comp_params_form_mail = array(
			'response'					=> $response,
			'ins_comp_data'	=> $data
		);

		$mail_options = $this->model_mail->generateCreateInsuranceCompanyMailOptions( $ins_comp_params_form_mail );
		
		$response['mail_content'] = $mail_options;

		$response["mail_error"] = $this->model_mail->sendMail( $mail_options );*/

		print_r(json_encode($response));
	}

	public function viewOne() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$ins_comp_permission 	= $this->permissions_lib->getPermissions(FUNCTION_INSURANCE_COMPANY);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $ins_comp_permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Service Provider details"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('ins_comp/model_ins_comp');
		$this->load->model('security/model_users');
		$this->load->model('utils/model_form_utils');

		$ins_comp_id 			= $this->input->post('ins_comp_id');
		$openAs		 			= $this->input->post('openAs');

		$ins_comps_response 	= $this->model_ins_comp->get_ins_comp_list($ins_comp_id);

		$ins_comps = $ins_comps_response["ins_comps"];

		
		for($i=0; $i < count($ins_comps); $i++) {
			$ins_comps[$i]->created_by_name = $this->model_users->getUsersList($ins_comps[$i]->created_by)[0]->user_name;
			$ins_comps[$i]->updated_by_name = $this->model_users->getUsersList($ins_comps[$i]->updated_by)[0]->user_name;
		}

		$stateFromDb = $this->model_form_utils->getCountryStatus($ins_comps[0]->state);
		
		$stateName = count($stateFromDb) ? $stateFromDb[0]->name: "";
		$stateText = !empty($ins_comps[0]->state) ? $stateName : "";

		$addressFile = $this->form_lib->getAddressFile(array("requestFrom" => "view", "address_data" => $ins_comps[0]));

		$ins_comps[0]->default_contact_user_disp_str = $this->model_users->getUserDisplayNameWithEmail($ins_comps[0]->default_contact_user_id);
		$ins_comps[0]->created_by = $this->model_users->getUserDisplayNameWithEmail($ins_comps[0]->created_by);
		$ins_comps[0]->updated_by = $this->model_users->getUserDisplayNameWithEmail($ins_comps[0]->updated_by);

		$params = array(
			'ins_comps'				=> $ins_comps,
			'userType' 				=> $this->session->userdata('logged_in_role_id'),
			'ins_comp_id' 			=> $ins_comp_id,
			'addressFile' 			=> $addressFile,
			'openAs' 				=> $openAs,
			'ins_comp_permission'	=> $ins_comp_permission,
			'role_id'				=> $this->session->userdata('logged_in_role_id'),
			'role_disp_name'		=> $this->session->userdata('logged_in_role_disp_name')
		);
		
		echo $this->load->view("ins_comp/ins_comp/viewOne", $params, true);
	}

	public function editForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$ins_comp_permission 	= $this->permissions_lib->getPermissions(FUNCTION_INSURANCE_COMPANY);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_UPDATE, $ins_comp_permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Update Insurance Company"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$role_id 		= $this->session->userdata('logged_in_role_id');
		$role_disp_name = $this->session->userdata('logged_in_role_disp_name');

		$this->load->model('ins_comp/model_ins_comp');
		$this->load->model('security/model_users');

		$ins_comp_id = $this->input->post('ins_comp_id');
		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$ins_comps_response = $this->model_ins_comp->get_ins_comp_list($ins_comp_id);
		$ins_comps = $ins_comps_response["ins_comps"];

		$addressFile = $this->form_lib->getAddressFile(array("view" => "update_ins_comp_form", "requestFrom" => "input", "address_data" => $ins_comps[0]));

		if(!empty($ins_comps[0]->default_contact_user_id)) {
			$ins_comps[0]->default_contact_user_disp_str = $this->model_users->getUserDisplayNameWithEmail($ins_comps[0]->default_contact_user_id);
		}

		$params = array(
			'ins_comps' 		=> $ins_comps,
			'addressFile' 		=> $addressFile,
			'userType' 			=> $this->session->userdata('logged_in_role_id'),
			'openAs' 			=> $openAs,
			'popupType' 		=> $popupType,
			'role_id'			=> $role_id,
			'role_disp_name'	=> $role_disp_name
		);
		
		echo $this->load->view("ins_comp/ins_comp/inputForm", $params, true);
	}

	public function update() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_INSURANCE_COMPANY, OPERATION_UPDATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('ins_comp/model_ins_comp');
		$this->load->model('mail/model_mail');

		$ins_comp_id 			= $this->input->post('ins_comp_id');

		$data = array(
			'ins_comp_name' 			=> $this->input->post('company'),
			'email_id' 					=> $this->input->post('emailId'),
			'contact_no' 				=> $this->input->post('contactPhoneNumber'),
			'address1' 					=> $this->input->post('addressLine1'),
			'address2'					=> $this->input->post('addressLine2'),
			'city' 						=> $this->input->post('city'),
			'state' 					=> $this->input->post('state'),
			'country' 					=> $this->input->post('country'),
			'zip_code' 					=> $this->input->post('zipCode'),
			'default_contact_user_id'	=> $this->input->post('db_default_user_id'),
			'website_url' 				=> $this->input->post('websiteURL'),
			'updated_by'				=> $this->session->userdata('logged_in_user_id'),
			'updated_on'				=> date("Y-m-d H:i:s")
		);

		$response = $this->model_ins_comp->update($data, $ins_comp_id);

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

		/*$contractorCompanyParamsFormMail = array(
			'response'				=> $response,
			'contractorData'		=> $data
		);

		$mail_options = $this->model_mail->generateUpdateContractorCompanyMailOptions( $contractorCompanyParamsFormMail );
		
		$response['mail_content'] = $mail_options;
		$response["mail_error"] = $this->model_mail->sendMail( $mail_options );*/

		print_r(json_encode($response));
	}

	public function deleteRecord() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}
		
		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_INSURANCE_COMPANY, OPERATION_DELETE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('ins_comp/model_ins_comp');
		$this->load->model('mail/model_mail');

		$ins_comp_id = $this->input->post('ins_comp_id');
		$delete_contractor = $this->model_ins_comp->deleteRecord($ins_comp_id);

		print_r(json_encode($delete_contractor));	
	}
}