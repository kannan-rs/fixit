<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contractors extends CI_Controller {

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
		$this->load->model('projects/model_contractors');

		$serviceZip = trim($this->input->post("serviceZip")) ? explode(",", trim($this->input->post("serviceZip"))) : null;
		$zip 		= trim($this->input->post("zip")) ? trim($this->input->post("zip")) : null;
		$records 	= trim($this->input->post("records")) ? explode(",", trim($this->input->post("records"))) : null;
		
		$contractorsResponse = $this->model_contractors->getContractorsList( $records, $zip, $serviceZip );

		print_r(json_encode($contractorsResponse));
	}

	public function viewAll() {
		//Contractor > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions('service provider');
		/* If User dont have view permission load No permission page */
		if(!in_array('view', $contractorPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Contractor List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		$this->load->model('projects/model_contractors');
		
		$contractorsResponse = $this->model_contractors->getContractorsList();

		$params = array(
			'contractors'		=> $contractorsResponse["contractors"],
			'role_id' 			=> $role_id,
			'role_disp_name'	=> $role_disp_name
		);
		
		echo $this->load->view("projects/contractors/viewAll", $params, true);
	}
	
	public function createForm() {
		//Contractor > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions('service provider');
		/* If User dont have view permission load No permission page */
		if(!in_array('create', $contractorPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Create Contractor"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		$this->load->model('security/model_users');

		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$addressParams = array(
			'forForm' 			=> "create_contractor_form",
			'role_id' 			=> $role_id,
			'role_disp_name'	=> $role_disp_name,
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);
		
		$params = array(
			'users' 			=> $this->model_users->getUsersList(),
			'role_id' 			=> $role_id,
			'role_disp_name'	=> $role_disp_name,
			'openAs' 			=> $openAs,
			'addressFile' 		=> $addressFile,
			'popupType' 		=> $popupType
		);

		echo $this->load->view("projects/contractors/inputForm", $params, true);
	}

	public function add() {
		$this->load->model('projects/model_contractors');
		$this->load->model('mail/model_mail');

		$data = array(
			//'name' 				=> $this->input->post('name'),
			'company' 			=> $this->input->post('company'),
			'type' 				=> $this->input->post('type'),
			'license' 			=> $this->input->post('license'),
			//'bbb' 				=> $this->input->post('bbb'),
			'status' 			=> $this->input->post('status'),
			'address1' 			=> $this->input->post('addressLine1'),
			'address2'			=> $this->input->post('addressLine2'),
			'city' 				=> $this->input->post('city'),
			'state' 			=> $this->input->post('state'),
			'country' 			=> $this->input->post('country'),
			'pin_code' 			=> $this->input->post('zipCode'),
			'office_email' 		=> $this->input->post('emailId'),
			'office_ph' 		=> $this->input->post('contactPhoneNumber'),
			'mobile_ph' 		=> $this->input->post('mobileNumber'),
			'prefer' 			=> $this->input->post('prefContact'),
			'default_contact_user_id'	=> $this->input->post('db_default_user_id'),
			'website_url' 		=> $this->input->post('websiteURL'),
			'service_area' 		=> $this->input->post('serviceZip'),
			'created_by'		=> $this->session->userdata('user_id'),
			'updated_by'		=> $this->session->userdata('user_id'),
			'created_on'		=> date("Y-m-d H:i:s"),
			'updated_on'		=> date("Y-m-d H:i:s")
		);

		$response = $this->model_contractors->insert($data);

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
		//Contractor > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions('service provider');
		/* If User dont have view permission load No permission page */
		if(!in_array('view', $contractorPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Contractor details"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		$this->load->model('projects/model_contractors');
		$this->load->model('security/model_users');
		$this->load->model('utils/model_form_utils');

		$contractorId 			= $this->input->post('contractorId');
		$openAs		 			= $this->input->post('openAs');

		$contractorsResponse 	= $this->model_contractors->getContractorsList($contractorId);

		$contractors = $contractorsResponse["contractors"];

		
		for($i=0; $i < count($contractors); $i++) {
			$contractors[$i]->created_by_name = $this->model_users->getUsersList($contractors[$i]->created_by)[0]->user_name;
			$contractors[$i]->updated_by_name = $this->model_users->getUsersList($contractors[$i]->updated_by)[0]->user_name;
		}

		$stateFromDb = $this->model_form_utils->getCountryStatus($contractors[0]->state);
		
		$stateName = count($stateFromDb) ? $stateFromDb[0]->name: "";
		$stateText = !empty($contractors[0]->state) ? $stateName : "";

		$addressParams = array(
			'addressLine1' 		=> $contractors[0]->address1,
			'addressLine2' 		=> $contractors[0]->address2,
			'city' 				=> $contractors[0]->city,
			'country' 			=> $contractors[0]->country,
			'state'				=> $stateText,
			'zipCode' 			=> $contractors[0]->pin_code,
			'requestFrom' 		=> 'view'
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);

		$contractors[0]->default_contact_user_disp_str = $this->model_users->getUserDisplayName($contractors[0]->default_contact_user_id);
		$contractors[0]->created_by = $this->model_users->getUserDisplayName($contractors[0]->created_by);
		$contractors[0]->updated_by = $this->model_users->getUserDisplayName($contractors[0]->updated_by);

		$params = array(
			'contractors'			=> $contractors,
			'userType' 				=> $this->session->userdata('role_id'),
			'contractorId' 			=> $contractorId,
			'addressFile' 			=> $addressFile,
			'openAs' 				=> $openAs,
			'contractorPermission'	=> $contractorPermission,
			'role_id'				=> $role_id,
			'role_disp_name'		=> $role_disp_name
		);
		
		echo $this->load->view("projects/contractors/viewOne", $params, true);
	}

	public function editForm() {
		//Contractor > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions('service provider');
		/* If User dont have view permission load No permission page */
		if(!in_array('update', $contractorPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Update Contractor"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		$this->load->model('projects/model_contractors');
		$this->load->model('security/model_users');

		$contractorId = $this->input->post('contractorId');
		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$contractorsResponse = $this->model_contractors->getContractorsList($contractorId);
		$contractors = $contractorsResponse["contractors"];

		$addressParams = array(
			'addressLine1' 		=> $contractors[0]->address1,
			'addressLine2' 		=> $contractors[0]->address2,
			'city' 				=> $contractors[0]->city,
			'country' 			=> $contractors[0]->country,
			'state'				=> $contractors[0]->state,
			'zipCode' 			=> $contractors[0]->pin_code,
			'forForm' 			=> "update_contractor_form",
			'role_id'			=> $role_id,
			'role_disp_name'	=> $role_disp_name
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);

		if(!empty($contractors[0]->default_contact_user_id)) {
			$contractors[0]->default_contact_user_disp_str = $this->model_users->getUserDisplayName($contractors[0]->default_contact_user_id);
		}

		$params = array(
			'contractors' 		=> $contractors,
			'addressFile' 		=> $addressFile,
			'userType' 			=> $this->session->userdata('role_id'),
			'openAs' 			=> $openAs,
			'popupType' 		=> $popupType,
			'role_id'			=> $role_id,
			'role_disp_name'	=> $role_disp_name
		);
		
		echo $this->load->view("projects/contractors/inputForm", $params, true);
	}

	public function update() {
		$this->load->model('projects/model_contractors');
		$this->load->model('mail/model_mail');

		$contractorId 			= $this->input->post('contractorId');

		$data = array(
			//'name' 				=> $this->input->post('name'),
			'company' 			=> $this->input->post('company'),
			'type' 				=> $this->input->post('type'),
			'license' 			=> $this->input->post('license'),
			//'bbb' 				=> $this->input->post('bbb'),
			'status' 			=> $this->input->post('status'),
			'address1' 			=> $this->input->post('addressLine1'),
			'address2'			=> $this->input->post('addressLine2'),
			'city' 				=> $this->input->post('city'),
			'state' 			=> $this->input->post('state'),
			'country' 			=> $this->input->post('country'),
			'pin_code' 			=> $this->input->post('zipCode'),
			'office_email' 		=> $this->input->post('emailId'),
			'office_ph' 		=> $this->input->post('contactPhoneNumber'),
			'mobile_ph' 		=> $this->input->post('mobileNumber'),
			'default_contact_user_id'	=> $this->input->post('db_default_user_id'),
			'prefer' 			=> $this->input->post('prefContact'),
			'website_url' 		=> $this->input->post('websiteURL'),
			'service_area' 		=> $this->input->post('serviceZip'),
			'updated_by'		=> $this->session->userdata('user_id'),
			'updated_on'		=> date("Y-m-d H:i:s")
		);

		$response = $this->model_contractors->update($data, $contractorId);

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
		$this->load->model('projects/model_contractors');
		$this->load->model('mail/model_mail');

		$contractorId = $this->input->post('contractorId');
		$delete_contractor = $this->model_contractors->deleteRecord($contractorId);

		print_r(json_encode($delete_contractor));	
	}

	public function getTradesList() {

		$this->load->model('projects/model_contractors');
		$contractorId = $this->input->post("contractorId");

		$params = array(
			"contractorId" => $contractorId
		);

		$tradesList = $this->model_contractors->getTradesList( $params );
		
		print_r(json_encode($tradesList));
	}

	public function createFormMainTrades() {
		$params = array(
		);

		echo $this->load->view("projects/contractors/inputFormMainTrade", $params, true);
	}

	public function addMainTrades() {
		$this->load->model('projects/model_contractors');

		$contractor_id = $this->input->post("contractor_id");
		$trade_name = $this->input->post("trade_name");

		$data = array(
			//'name' 				=> $this->input->post('name'),
			'trade_belongs_to_contractor_id'	=> $contractor_id,
			'trade_name' 						=> $trade_name,
			'created_by'						=> $this->session->userdata('user_id'),
			'updated_by'						=> $this->session->userdata('user_id'),
			'created_on'						=> date("Y-m-d H:i:s"),
			'updated_on'						=> date("Y-m-d H:i:s")
		);

		$response = $this->model_contractors->insertTrade($data);

		print_r(json_encode($response));
	}

	public function updateFormMainTrades() {
		$trade_id 		= $this->input->post("trade_id");
		$contractorId 	= $this->input->post("contractorId");

		$this->load->model('projects/model_contractors');

		$params = array(
			"trade_id" 		=> $trade_id,
			"contractorId" 	=> $contractorId

		);

		$response = $this->model_contractors->getTradesList( $params );

		if($response["status"] == "success") {
			$params['tradesList'] = $response["tradesList"];
		}

		echo $this->load->view("projects/contractors/inputFormMainTrade", $params, true);
	}

	public function updateMainTrades() {
		$this->load->model('projects/model_contractors');

		$contractor_id = $this->input->post("contractor_id");
		$trade_name 	= $this->input->post("trade_name");
		$trade_id 		= $this->input->post("trade_id");

		$data = array(
			'trade_name' 						=> $trade_name,
			'created_by'						=> $this->session->userdata('user_id'),
			'updated_by'						=> $this->session->userdata('user_id'),
			'created_on'						=> date("Y-m-d H:i:s"),
			'updated_on'						=> date("Y-m-d H:i:s")
		);

		$params = array(
			'data'			=> $data,
			'trade_id'		=> $trade_id,
			"contractor_id"	=> $contractor_id
		);

		$response = $this->model_contractors->updateTrade($params);

		print_r(json_encode($response));
	}

	public function deleteMainTrades() {
		$this->load->model('projects/model_contractors');

		$contractor_id = $this->input->post("contractor_id");
		$trade_id 		= $this->input->post("trade_id");

		$params = array(
			"contractor_id"	=> $contractor_id,
			"trade_id"		=> $trade_id
		);

		$response = $this->model_contractors->deleteTradeRecord($params);
		print_r(json_encode($response));
	}

	public function createSubTradesForm() {
		$params = array(
			"main_trade_id"		=> $this->input->post("main_trade_id"),
			"contractor_id"	=> $this->input->post("contractor_id"),
			"main_trade_name"	=> $this->input->post("main_trade_name")
		);

		echo $this->load->view("projects/contractors/inputFormSubTrade", $params, true);
	}

	public function addSubTrades() {
		$this->load->model('projects/model_contractors');

		$contractor_id 		= $this->input->post("contractor_id");
		$sub_trade_name 	= $this->input->post("sub_trade_name");
		$main_trade_id 		= $this->input->post("main_trade_id");

		$data = array(
			//'name' 				=> $this->input->post('name'),
			'trade_parent'						=> $main_trade_id,
			'trade_belongs_to_contractor_id'	=> $contractor_id,
			'trade_name' 						=> $sub_trade_name,
			'created_by'						=> $this->session->userdata('user_id'),
			'updated_by'						=> $this->session->userdata('user_id'),
			'created_on'						=> date("Y-m-d H:i:s"),
			'updated_on'						=> date("Y-m-d H:i:s")
		);

		$response = $this->model_contractors->insertTrade($data);

		print_r(json_encode($response));
	}

	public function updateFormSubTrades() {
		$this->load->model('projects/model_contractors');

		$params = array(
			"sub_trade_id"		=> $this->input->post("sub_trade_id"),
			"main_trade_id"		=> $this->input->post("main_trade_id"),
			"contractor_id"		=> $this->input->post("contractor_id"),
			"trade_name"		=> $this->input->post("trade_name"),
		);

		$getParams = array(
			"trade_id"			=> $this->input->post("sub_trade_id"),
			"trade_parent"		=> $this->input->post("main_trade_id"),
			"contractorId"		=> $this->input->post("contractor_id")
		);

		$response = $this->model_contractors->getTradesList( $getParams );

		if($response["status"] == "success") {
			$params['tradesList'] = $response["tradesList"];
		}

		echo $this->load->view("projects/contractors/inputFormSubTrade", $params, true);
	}

	public function updateSubTrades() {
		$this->load->model('projects/model_contractors');

		$sub_trade_name	= $this->input->post("sub_trade_name");
		$sub_trade_id	= $this->input->post("sub_trade_id");
		$main_trade_id	= $this->input->post("main_trade_id");
		$contractor_id	= $this->input->post("contractor_id");

		$data = array(
			'trade_name' 						=> $sub_trade_name,
			'created_by'						=> $this->session->userdata('user_id'),
			'updated_by'						=> $this->session->userdata('user_id'),
			'created_on'						=> date("Y-m-d H:i:s"),
			'updated_on'						=> date("Y-m-d H:i:s")
		);

		$params = array(
			'data'			=> $data,
			'trade_id'		=> $sub_trade_id,
			"contractor_id"	=> $contractor_id,
			"main_trade_id"	=> $main_trade_id
		);

		$response = $this->model_contractors->updateTrade($params);

		print_r(json_encode($response));
	}

	public function deleteSubTrades() {
		$this->load->model('projects/model_contractors');

		$sub_trade_id	= $this->input->post("sub_trade_id");
		$main_trade_id	= $this->input->post("main_trade_id");
		$contractor_id	= $this->input->post("contractor_id");

		$params = array(
			"contractor_id"	=> $contractor_id,
			"trade_id"		=> $sub_trade_id,
			"trade_parent" 	=> $main_trade_id
		);

		$response = $this->model_contractors->deleteTradeRecord($params);
		print_r(json_encode($response));
	}

	public function showDiscountList() {
		$this->load->model('projects/model_contractors');
		$contractor_id	= $this->input->post("contractor_id");

		$getParams = array(
			"contractor_id" => $contractor_id
		);

		$response = $this->model_contractors->getDiscountList($getParams);

		$params = array(
			"sub_trade_id"		=> $this->input->post("sub_trade_id"),
			"main_trade_id"		=> $this->input->post("main_trade_id"),
			"contractor_id"		=> $contractor_id,
			"trade_name"		=> $this->input->post("trade_name"),
		);

		if($response["status"] == "success") {
			$params['discountList'] = $response["discountList"];
		}

		echo $this->load->view("projects/contractors/discountView", $params, true);

	}
}