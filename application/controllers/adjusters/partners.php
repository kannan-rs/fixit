<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partners extends CI_Controller {

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

	public function getList() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed 	= $this->permissions_lib->is_allowed(FUNCTION_PARTNER, OPERATION_VIEW);

		if( !$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('adjusters/model_partners');

		$companyName 	= explode(",", $this->input->post("companyName"));
		$name 			= explode(",", $this->input->post("name"));

		$records = [];
		if($this->input->post("records")) {
			$records = explode(",", $this->input->post("records"));
		}

		$partnersResponse = $this->model_partners->getPartnersList( $records, $companyName, $name);

		print_r(json_encode($partnersResponse));
	}

	public function getPartnerByCompanyName() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed 	= $this->permissions_lib->is_allowed(FUNCTION_PARTNER, OPERATION_VIEW);

		if( !$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}
		
		$this->load->model('adjusters/model_partners');
		
		$companyName = explode(",", $this->input->post('companyName'));
		$record = "";

		$partnersResponse = $this->model_partners->getPartnersList($record, $companyName);

		print_r(json_encode($partnersResponse));	
	}

	public function viewAll() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$adjusterPermission = $this->permissions_lib->getPermissions(FUNCTION_PARTNER);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $adjusterPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "partner list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('adjusters/model_partners');
		$this->load->model('security/model_users');
		
		$partnersResponse = $this->model_partners->getPartnersList();

		$partners = $partnersResponse["partners"];

		for( $i = 0; $i < count($partners); $i++) {
			$partners[$i]->default_contact_user_disp_str = $this->model_users->getUserDisplayNameWithEmail($partners[$i]->default_contact_user_id);
		}

		$params = array(
			'partners'=> $partners,
			'role_id' => $this->session->userdata('logged_in_role_id')
		);
		
		echo $this->load->view("adjusters/partners/viewAll", $params, true);
	}
	
	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$adjusterPermission = $this->permissions_lib->getPermissions(FUNCTION_PARTNER);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $adjusterPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "create partner"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Get Role ID and Role Display String*/
		$role_id 		= $this->session->userdata('logged_in_role_id'); 
		$role_disp_name = $this->session->userdata('logged_in_role_disp_name');

		$this->load->model('security/model_users');

		$openAs 		= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 		= $this->input->post('popupType') ? $this->input->post('popupType') : "";
		$addressFile 	= $this->form_lib->getAddressFile(array("view" => "create_partner_form", "requestFrom"=> "input"));
		
		$params = array(
			//'users' 				=> $this->model_users->getUsersList(),
			//'userType' 				=> $this->session->userdata('logged_in_role_id'),
			'role_id' 				=> $role_id,
			'role_disp_name'		=> $role_disp_name,
			'openAs' 				=> $openAs,
			'addressFile' 			=> $addressFile,
			'popupType' 			=> $popupType
		);

		echo $this->load->view("adjusters/partners/inputForm", $params, true);
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed 	= $this->permissions_lib->is_allowed(FUNCTION_PARTNER, OPERATION_CREATE);

		if( !$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('adjusters/model_partners');
		$this->load->model('mail/model_mail');

		$data = array(
			//'name' 					=> $this->input->post('name'),
			'company_name' 				=> $this->input->post('company'),
			'type' 						=> $this->input->post('type'),
			'license' 					=> $this->input->post('license'),
			'status' 					=> $this->input->post('status'),
			'address1' 					=> $this->input->post('addressLine1'),
			'address2'					=> $this->input->post('addressLine2'),
			'city' 						=> $this->input->post('city'),
			'state' 					=> $this->input->post('state'),
			'country' 					=> $this->input->post('country'),
			'zip_code' 					=> $this->input->post('zipCode'),
			'work_email_id' 			=> $this->input->post('wEmailId'),
			'work_phone' 				=> $this->input->post('wNumber'),
			'mobile_no' 				=> $this->input->post('pNumber'),
			'personal_email_id' 		=> $this->input->post('pEmailId'),
			'contact_pref' 				=> $this->input->post('prefContact'),
			'default_contact_user_id'	=> $this->input->post('db_default_user_id'),
			'website_url' 				=> $this->input->post('websiteURL'),
			'created_by'				=> $this->session->userdata('logged_in_user_id'),
			'updated_by'				=> $this->session->userdata('logged_in_user_id'),
			'created_on'				=> date("Y-m-d H:i:s"),
			'updated_on'				=> date("Y-m-d H:i:s")
		);

		$response = $this->model_partners->insert($data);

		$partnerCompanyParamsFormMail = array(
			'response'			=> $response,
			'partnerData'		=> $data
		);

		$mail_options = $this->model_mail->generateCreatePartnerCompanyMailOptions( $partnerCompanyParamsFormMail );
		
		$response['mail_content'] = $mail_options;
		$response["mail_error"] = $this->model_mail->sendMail( $mail_options );

		print_r(json_encode($response));
	}

	public function viewOne() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$adjusterPermission = $this->permissions_lib->getPermissions(FUNCTION_PARTNER);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $adjusterPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "create partner"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('adjusters/model_partners');
		$this->load->model('security/model_users');
		$this->load->model('utils/model_form_utils');

		$partnerId 			= $this->input->post('partnerId');
		$openAs		 			= $this->input->post('openAs');
		$partnersResponse 		= $this->model_partners->getPartnersList($partnerId);

		$partners = $partnersResponse["partners"];
		
		for($i=0; $i < count($partners); $i++) {
			$partners[$i]->created_by_name = $this->model_users->getUsersList($partners[$i]->created_by)[0]->user_name;
			$partners[$i]->updated_by_name = $this->model_users->getUsersList($partners[$i]->updated_by)[0]->user_name;
		}

		$partners[0]->default_contact_user_disp_str = $this->model_users->getUserDisplayNameWithEmail($partners[0]->default_contact_user_id);

		$addressFile = $this->form_lib->getAddressFile(array("view" => "view", "address_data" => $partners[0]));

		$params = array(
			'partners'				=> $partners,
			'userType' 				=> $this->session->userdata('logged_in_role_id'),
			'partnerId' 			=> $partnerId,
			'addressFile' 			=> $addressFile,
			'openAs' 				=> $openAs,
			'adjusterPermission'	=> $adjusterPermission
		);
		
		echo $this->load->view("adjusters/partners/viewOne", $params, true);
	}

	public function editForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Project > Permissions for logged in User by role_id
		$adjusterPermission = $this->permissions_lib->getPermissions(FUNCTION_PARTNER);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_UPDATE, $adjusterPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "update partner"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Get Role ID and Role Display String*/
		$role_id 		= $this->session->userdata('logged_in_role_id'); 
		$role_disp_name = $this->session->userdata('logged_in_role_disp_name');

		$this->load->model('adjusters/model_partners');
		$this->load->model('security/model_users');

		$partnerId = $this->input->post('partnerId');
		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$partnersResponse = $this->model_partners->getPartnersList($partnerId);
		$partners = $partnersResponse["partners"];

		$addressFile = $this->form_lib->getAddressFile(array("view" => "update_partner_form", "requestFrom"=> "input", "address_data" => $partners[0]));

		if(!empty($partners[0]->default_contact_user_id)) {
			$partners[0]->default_contact_user_disp_str = $this->model_users->getUserDisplayNameWithEmail($partners[0]->default_contact_user_id);
		}

		$params = array(
			'role_id' 			=> $role_id,
			'role_disp_name'	=> $role_disp_name,
			'partners' 			=> $partners,
			'addressFile' 		=> $addressFile,
			//'userType' 			=> $this->session->userdata('logged_in_role_id'),
			'openAs' 			=> $openAs,
			'popupType' 		=> $popupType
		);
		
		echo $this->load->view("adjusters/partners/inputForm", $params, true);
	}

	public function update() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed 	= $this->permissions_lib->is_allowed(FUNCTION_PARTNER, OPERATION_UPDATE);

		if( !$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('adjusters/model_partners');
		$this->load->model('mail/model_mail');

		$partnerId 			= $this->input->post('partnerId');

		$data = array(
			//'name' 					=> $this->input->post('name'),
			'company_name' 				=> $this->input->post('company'),
			'type' 						=> $this->input->post('type'),
			'license' 					=> $this->input->post('license'),
			'status' 					=> $this->input->post('status'),
			'address1' 					=> $this->input->post('addressLine1'),
			'address2'					=> $this->input->post('addressLine2'),
			'city' 						=> $this->input->post('city'),
			'state' 					=> $this->input->post('state'),
			'country' 					=> $this->input->post('country'),
			'zip_code' 					=> $this->input->post('zipCode'),
			'work_email_id' 			=> $this->input->post('wEmailId'),
			'work_phone' 				=> $this->input->post('wNumber'),
			'mobile_no' 				=> $this->input->post('pNumber'),
			'personal_email_id' 		=> $this->input->post('pEmailId'),
			'contact_pref' 				=> $this->input->post('prefContact'),
			'website_url' 				=> $this->input->post('websiteURL'),
			'default_contact_user_id'	=> $this->input->post('db_default_user_id'),
			'updated_by'				=> $this->session->userdata('logged_in_user_id'),
			'updated_on'				=> date("Y-m-d H:i:s")
		);

		$response = $this->model_partners->update($data, $partnerId);

		$partnerCompanyParamsFormMail = array(
			'response'			=> $response,
			'partnerData'		=> $data
		);

		$mail_options = $this->model_mail->generateUpdatePartnerCompanyMailOptions( $partnerCompanyParamsFormMail );
		
		$response['mail_content'] = $mail_options;
		$response["mail_error"] = $this->model_mail->sendMail( $mail_options );

		print_r(json_encode($response));
	}

	public function deleteRecord() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}
		
		$is_allowed 	= $this->permissions_lib->is_allowed(FUNCTION_PARTNER, OPERATION_DELETE);

		if( !$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('adjusters/model_partners');
		$this->load->model('mail/model_mail');

		$partnerId = $this->input->post('partnerId');
		$delete_partner = $this->model_partners->deleteRecord($partnerId);

		print_r(json_encode($delete_partner));	
	}
}