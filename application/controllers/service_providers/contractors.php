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

	private function _mapTradeToId( $tradesList ) {
		$mappedTradeById = [];
		if(!empty($tradesList)) {
			for($i=0, $count = count($tradesList); $i < $count; $i++) {
				$mappedTradeById[$tradesList[$i]->trade_id_from_master_list] = $tradesList[$i];
			}
		}
		return $mappedTradeById;
	}

	private function _mapMainTradeToId( $tradesList ) {
		$mappedTradeById = [];
		if(!empty($tradesList)) {
			for($i=0, $count = count($tradesList); $i < $count; $i++) {
				$mappedTradeById[$tradesList[$i]->main_trade_id] = $tradesList[$i];
			}
		}
		return $mappedTradeById;
	}

	private function _mapSubTradeToId( $tradesList ) {
		$mappedTradeById = [];
		if(!empty($tradesList)) {
			for($i=0, $count = count($tradesList); $i < $count; $i++) {
				$mappedTradeById[$tradesList[$i]->sub_trade_id] = $tradesList[$i];
			}
		}
		return $mappedTradeById;
	}

	private function _removeDuplicatesFromMainTradesList( $options ) {
		$uniqueMainTrades 		= array ();
		$main_trades_list 		= $options["main_trades_list"];
		$available_main_trades 	= $options["available_main_trades"];

		for($i = 0, $count = count($main_trades_list); $i < $count; $i++) {
			if(!isset($available_main_trades[$main_trades_list[$i]->main_trade_id])) {
				array_push($uniqueMainTrades, $main_trades_list[$i]);
			}
		}

		return $uniqueMainTrades;
	}

	public function getList() {
		$this->load->model('service_providers/model_contractors');

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
				'page_disp_string' => "Service Provider List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		$this->load->model('service_providers/model_contractors');
		
		$contractorsResponse = $this->model_contractors->getContractorsList();

		$params = array(
			'contractors'		=> $contractorsResponse["contractors"],
			'role_id' 			=> $role_id,
			'role_disp_name'	=> $role_disp_name
		);
		
		echo $this->load->view("service_providers/contractors/viewAll", $params, true);
	}
	
	public function createForm() {
		//Contractor > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions('service provider');
		/* If User dont have view permission load No permission page */
		if(!in_array('create', $contractorPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Create Service Provider"
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
		$this->load->model('service_providers/model_contractors');
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
				'page_disp_string' => "Service Provider details"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}
		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		$this->load->model('service_providers/model_contractors');
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
		
		echo $this->load->view("service_providers/contractors/viewOne", $params, true);
	}

	public function editForm() {
		//Contractor > Permissions for logged in User by role_id
		$contractorPermission 	= $this->permissions_lib->getPermissions('service provider');
		/* If User dont have view permission load No permission page */
		if(!in_array('update', $contractorPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Update Service Provider"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();

		$this->load->model('service_providers/model_contractors');
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
			'role_disp_name'	=> $role_disp_name,
			'requestFrom'		=> "input"
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
		
		echo $this->load->view("service_providers/contractors/inputForm", $params, true);
	}

	public function update() {
		$this->load->model('service_providers/model_contractors');
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
		$this->load->model('service_providers/model_contractors');
		$this->load->model('mail/model_mail');

		$contractorId = $this->input->post('contractorId');
		$delete_contractor = $this->model_contractors->deleteRecord($contractorId);

		print_r(json_encode($delete_contractor));	
	}

	public function getTradesList() {

		$this->load->model('service_providers/model_contractors');
		$contractor_id = $this->input->post("contractorId");

		// Get All Trades
		$main_trades_response = $this->model_contractors->getMainTradeList("all");
		$main_trades = null;
		if($main_trades_response["status"] == "success") {
			$main_trades = $main_trades_response["mainTradesList"];
			$main_trades 	= $this->_mapMainTradeToId( $main_trades );
		}

		// Get All Trades
		$options = array(
			"sub_trade_id" 		=> "all",
			"parent_trade_id"	=> "all",
			"trade_for"			=> "sub",
			"contractor_id"		=> $contractor_id
		);
		$sub_trades_response = $this->model_contractors->getSubTradeList($options);

		$sub_trades_list = [];
		if($sub_trades_response["status"] == "success") {
			$sub_trades = $sub_trades_response["subTradesList"];
			$sub_trades = $this->_mapSubTradeToId( $sub_trades );
		}

		$params = array(
			"contractor_id" => $contractor_id
		);

		$tradesList = $this->model_contractors->getTradesList( $params );

		if($tradesList["status"] == "success") {
			for($i = 0, $count = count($tradesList["tradesList"]); $i < $count; $i++) {
				$nameKey = $tradesList["tradesList"][$i]->trade_id_from_master_list;
				if($tradesList["tradesList"][$i]->trade_parent == "0") {
					$tradesList["tradesList"][$i]->trade_name = $main_trades[$nameKey]->main_trade_name;
				} else {
					$tradesList["tradesList"][$i]->trade_name = $sub_trades[$nameKey]->sub_trade_name;
				}
			}
		}
		
		print_r(json_encode($tradesList));
	}

	public function createFormMainTrades() {
		$this->load->model('service_providers/model_contractors');
		$contractor_id = $this->input->post("contractorId");

		// Get All Trades
		$main_trades_response = $this->model_contractors->getMainTradeList("all");
		$main_trades = null;
		if($main_trades_response["status"] == "success") {
			$main_trades = $main_trades_response["mainTradesList"];
		}

		// Get Already assigned trade ID's
		$options = array( 
			"contractor_id" => $contractor_id,
			"trade_for"		=> "main"
		);

		$available_main_trades_response = $this->model_contractors->getAvailableTrades( $options );

		$available_main_trades = null;
		if($available_main_trades_response["status"] == "success") {
			$available_main_trades = $available_main_trades_response["availableTradesList"];
		}

		$mapped_available_main_trade_by_id = $this->_mapTradeToId($available_main_trades);

		$params = array(
			'contractorId' 			=> $contractor_id,
			'main_trades_list'		=> $main_trades,
			'available_main_trades'	=> $mapped_available_main_trade_by_id
		);

		$params["main_trades_list"] 	= $this->_removeDuplicatesFromMainTradesList( $params );

		echo $this->load->view("service_providers/contractors/inputFormMainTrade", $params, true);
	}

	public function addTrades() {
		$this->load->model('service_providers/model_contractors');

		$contractor_id = $this->input->post("contractor_id");
		$main_trade_id = $this->input->post("main_trade_id");
		$sub_trades_id = $this->input->post("sub_trades_id");

		$data = array(
			'trade_belongs_to_contractor_id'	=> $contractor_id,
			'trade_id_from_master_list' 		=> $main_trade_id,
			'created_by'						=> $this->session->userdata('user_id'),
			'updated_by'						=> $this->session->userdata('user_id'),
			'created_on'						=> date("Y-m-d H:i:s"),
			'updated_on'						=> date("Y-m-d H:i:s")
		);

		$response = $this->model_contractors->insertTrade($data);

		if($response["status"] == "success") {
			$sub_trades_id_arr = explode(",", $sub_trades_id);
			for($i = 0, $count = count($sub_trades_id_arr); $i < $count; $i++) {
				if($response["status"] == "success") {
					$data = array (
						'trade_belongs_to_contractor_id'	=> $contractor_id,
						'trade_id_from_master_list' 		=> $sub_trades_id_arr[$i],
						'trade_parent'						=> $main_trade_id,
						'created_by'						=> $this->session->userdata('user_id'),
						'updated_by'						=> $this->session->userdata('user_id'),
						'created_on'						=> date("Y-m-d H:i:s"),
						'updated_on'						=> date("Y-m-d H:i:s")
					);
					$response = $this->model_contractors->insertTrade($data);		
				} else {
					break;
				}
			}
		}

		print_r(json_encode($response));
	}

	public function updateFormMainTrades() {
		$trade_id 		= $this->input->post("trade_id");
		$contractor_id 	= $this->input->post("contractorId");
		$displayString 	= $this->input->post("displayString");

		$this->load->model('service_providers/model_contractors');

		// Get All Trades
		$main_trades_response = $this->model_contractors->getMainTradeList("all");
		$main_trades = null;
		if($main_trades_response["status"] == "success") {
			$main_trades = $main_trades_response["mainTradesList"];
		}

		// Get Already assigned trade ID's
		$options = array( 
			"contractor_id" => $contractor_id,
			"trade_for"		=> "main"
		);

		$available_main_trades_response = $this->model_contractors->getAvailableTrades( $options );

		$available_main_trades = null;
		if($available_main_trades_response["status"] == "success") {
			$available_main_trades = $available_main_trades_response["availableTradesList"];
		}

		$mapped_available_main_trade_by_id = $this->_mapTradeToId($available_main_trades);

		$params = array(
			'contractorId' 			=> $contractor_id,
			'main_trades_list'		=> $main_trades,
			'available_main_trades'	=> $mapped_available_main_trade_by_id,
			'main_trade_id' 		=> $trade_id,
			'displayString'			=> $displayString
		);

		$params["main_trades_list"] 	= $this->_removeDuplicatesFromMainTradesList( $params );

		echo $this->load->view("service_providers/contractors/inputFormMainTrade", $params, true);
	}

	public function updateTrades() {
		$this->load->model('service_providers/model_contractors');

		$response = array("status" => "success");

		$contractor_id 			= $this->input->post("contractor_id");
		$to_add_sub_trades_id 	= $this->input->post("to_add_sub_trades_id");
		$to_delete_sub_trade_id	= $this->input->post("to_delete_sub_trade_id");
		$main_trade_id 			= $this->input->post("main_trade_id");

		$to_add_sub_trades_id_arr = explode(",", $to_add_sub_trades_id);
		$to_delete_sub_trade_id_arr = explode(",", $to_delete_sub_trade_id);

		if(isset($to_add_sub_trades_id) && !empty($to_add_sub_trades_id)) {
			for($i = 0, $count = count($to_add_sub_trades_id_arr); $i < $count; $i++) {
				if($response["status"] == "success") {
					$data = array (
						'trade_belongs_to_contractor_id'	=> $contractor_id,
						'trade_id_from_master_list' 		=> $to_add_sub_trades_id_arr[$i],
						'trade_parent'						=> $main_trade_id,
						'created_by'						=> $this->session->userdata('user_id'),
						'updated_by'						=> $this->session->userdata('user_id'),
						'created_on'						=> date("Y-m-d H:i:s"),
						'updated_on'						=> date("Y-m-d H:i:s")
					);
					$response = $this->model_contractors->insertTrade($data);		
				} else {
					break;
				}
			}
		}

		if($response["status"] == "success" && isset($to_delete_sub_trade_id) && !empty($to_delete_sub_trade_id)) {
			
			for($i = 0, $count = count($to_delete_sub_trade_id_arr); $i < $count; $i++) {
				if($response["status"] == "success") {
					$options = array (
						'contractor_id'		=> $contractor_id,
						'sub_trade_id' 		=> $to_delete_sub_trade_id_arr[$i],
						'trade_id'			=> $main_trade_id
					);
					$response = $this->model_contractors->deleteTradeRecordByParent($options);		
				} else {
					break;
				}
			}

		}

		print_r(json_encode($response));
	}

	public function deleteMainTrades() {
		$this->load->model('service_providers/model_contractors');

		$contractor_id = $this->input->post("contractor_id");
		$trade_id 		= $this->input->post("trade_id");

		$params = array(
			"contractor_id"	=> $contractor_id,
			"trade_id"		=> $trade_id
		);

		$response = $this->model_contractors->deleteTradeRecord($params);

		if($response["status"] == "success") {
			$response_dependent = $this->model_contractors->deleteTradeRecordByParent( $params );
		}

		if(isset($response_dependent)) {
			$response["status"] 	= $response_dependent["status"];
			$response["message"]	.= ". ".$response_dependent["message"];
		}

		print_r(json_encode($response));
	}

	public function createSubTradesForm() {
		$this->load->model('service_providers/model_contractors');

		$contractor_id 		= $this->input->post("contractor_id");
		$main_trade_id		= $this->input->post("main_trade_id");
		
		// Get All Trades
		$options = array(
			"sub_trade_id" 		=> "all",
			"parent_trade_id"	=> $main_trade_id,
			"trade_for"			=> "sub",
			"contractor_id"		=> $contractor_id
		);


		$sub_trades_response = $this->model_contractors->getSubTradeList($options);

		$sub_trades_list = [];

		if($sub_trades_response["status"] == "success") {
			$sub_trades_list = $sub_trades_response["subTradesList"];
		}

		$available_sub_trades_response = $this->model_contractors->getAvailableTrades( $options );

		$available_sub_trades = null;
		if($available_sub_trades_response["status"] == "success") {
			$available_sub_trades = $available_sub_trades_response["availableTradesList"];
		}

		$mapped_available_sub_trade_by_id = $this->_mapTradeToId($available_sub_trades);

		$params = array(
			"sub_trades_list" 			=> $sub_trades_list,
			"contractor_id"				=> $contractor_id,
			"main_trade_id"				=> $main_trade_id,
			"selected_sub_trade_list" 	=> $mapped_available_sub_trade_by_id
		);

		echo $this->load->view("service_providers/contractors/inputFormSubTrade", $params, true);
	}

	public function addSubTrades() {
		$this->load->model('service_providers/model_contractors');

		$contractor_id 		= $this->input->post("contractor_id");
		$sub_trade_name 	= $this->input->post("sub_trade_name");
		$main_trade_id 		= $this->input->post("main_trade_id");

		$data = array(
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
		$this->load->model('service_providers/model_contractors');

		$params = array(
			"sub_trade_id"		=> $this->input->post("sub_trade_id"),
			"main_trade_id"		=> $this->input->post("main_trade_id"),
			"contractor_id"		=> $this->input->post("contractor_id"),
			"trade_name"		=> $this->input->post("trade_name"),
		);

		$getParams = array(
			"trade_id"			=> $this->input->post("sub_trade_id"),
			"trade_parent"		=> $this->input->post("main_trade_id"),
			"contractor_id"		=> $this->input->post("contractor_id")
		);

		$response = $this->model_contractors->getTradesList( $getParams );

		if($response["status"] == "success") {
			$params['tradesList'] = $response["tradesList"];
		}

		echo $this->load->view("service_providers/contractors/inputFormSubTrade", $params, true);
	}

	public function updateSubTrades() {
		$this->load->model('service_providers/model_contractors');

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
		$this->load->model('service_providers/model_contractors');

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
		$this->load->model('service_providers/model_contractors');
		$contractor_id	= $this->input->post("contractor_id");

		$getParams = array(
			"contractor_id" => $contractor_id
		);

		/* Get Trades */
		$tradesListResponse = $this->model_contractors->getTradesList( $getParams );
		$tradesList 		= $this->convertTradesDBToId($tradesListResponse["tradesList"]);

		/* Get Discount List */
		$response = $this->model_contractors->getDiscountList($getParams);

		$params = array(
			"sub_trade_id"		=> $this->input->post("sub_trade_id"),
			"main_trade_id"		=> $this->input->post("main_trade_id"),
			"contractor_id"		=> $contractor_id,
			"trade_name"		=> $this->input->post("trade_name"),
			"tradesList"		=> $tradesList
		);

		if($response["status"] == "success") {
			$params['discountList'] = $response["discountList"];
		}

		echo $this->load->view("service_providers/contractors/discountView", $params, true);
	}

	public function createDiscountForm() {
		$this->load->model('service_providers/model_contractors');

		$main_trade_id 	= $this->input->post("main_trade_id");
		$contractor_id 	= $this->input->post("contractor_id");
		$sub_trade_id 	= $this->input->post("sub_trade_id");

		$main_trade_id = $main_trade_id == "0" || $main_trade_id == 0 ? "" : $main_trade_id;
		$sub_trade_id = $sub_trade_id == "0" || $sub_trade_id == 0 ? "" : $sub_trade_id;

		$params = array(
			"main_trade_id"		=> $main_trade_id,
			"contractor_id"		=> $contractor_id,
			"sub_trade_id"		=> $sub_trade_id
		);

		/* Get Main Trade List */
		$getParams = array(
			"trade_id"			=> $main_trade_id,
			"contractor_id"		=> $contractor_id
		);

		$response = $this->model_contractors->getTradesList( $getParams );

		if($response["status"] == "success") {
			$params['mainTradesList'] = $response["tradesList"];
		}

		/* Get Sub Trade List */
		$getParams = array(
			"trade_id"			=> $sub_trade_id,
			"trade_parent"		=> $main_trade_id,
			"contractor_id"		=> $contractor_id
		);

		$response = $this->model_contractors->getTradesList( $getParams );

		if($response["status"] == "success") {
			$params['subTradesList'] = $response["tradesList"];
		}

		echo $this->load->view("service_providers/contractors/inputFormDiscount", $params, true);
	}

	public function addDiscount() {
		$this->load->model('service_providers/model_contractors');

		$main_trade_id 			= $this->input->post("main_trade_id");
		$sub_trade_id 			= $this->input->post("sub_trade_id");
		$discount_name 			= $this->input->post("discount_name");
		$discount_descr 		= $this->input->post("discount_descr");
		$discount_for_zip 		= $this->input->post("discount_for_zip");
		$discount_from_date 	= $this->input->post("discount_from_date");
		$discount_to_date 		= $this->input->post("discount_to_date");
		$contractor_id 			= $this->input->post("contractor_id");
		$discount_type			= $this->input->post("discount_type");
		$discount_value			= $this->input->post("discount_value");

		$data = array(
			'discount_name' 				=> $discount_name,
			'discount_descr'				=> $discount_descr,
			'discount_for_contractor_id'	=> $contractor_id,
			'discount_for_trade_id' 		=> $main_trade_id,
			'discount_for_sub_trade_id'		=> $sub_trade_id,
			'discount_for_zip'				=> $discount_for_zip,
			'discount_type'					=> $discount_type,
			'discount_value'				=> $discount_value,
			'discount_from_date'			=> $discount_from_date,
			'discount_to_date'				=> $discount_to_date,
			'created_by'					=> $this->session->userdata('user_id'),
			'updated_by'					=> $this->session->userdata('user_id'),
			'created_on'					=> date("Y-m-d H:i:s"),
			'updated_on'					=> date("Y-m-d H:i:s")
		);

		$response = $this->model_contractors->insertDiscount($data);

		print_r(json_encode($response));
	}

	public function convertTradesDBToId($tradeFromDb) {
		$tradesList = array();
		for($i = 0; $i < count($tradeFromDb); $i++) {
			$tradesList[$tradeFromDb[$i]->trade_id] = $tradeFromDb[$i];
		}
		return $tradesList;
	}

	public function editDiscountForm() {
		$this->load->model('service_providers/model_contractors');

		$contractor_id	= $this->input->post("contractor_id");
		$discount_id	= $this->input->post("discount_id");

		$getParams = array(
			"contractor_id" => $contractor_id,
			'discount_id'	=> $discount_id
		);

		/* Get Discount List */
		$response = $this->model_contractors->getDiscountList($getParams);

		if($response["status"] == "success") {
			$params['discountList'] = $response["discountList"];
		}

		echo $this->load->view("service_providers/contractors/inputFormDiscount", $params, true);
	}

	public function updateDiscount() {
		$this->load->model('service_providers/model_contractors');

		$discount_id 			= $this->input->post("discount_id");
		$main_trade_id 			= $this->input->post("main_trade_id");
		$sub_trade_id 			= $this->input->post("sub_trade_id");
		$discount_name 			= $this->input->post("discount_name");
		$discount_descr 		= $this->input->post("discount_descr");
		$discount_for_zip 		= $this->input->post("discount_for_zip");
		$discount_from_date 	= $this->input->post("discount_from_date");
		$discount_to_date 		= $this->input->post("discount_to_date");
		$contractor_id 			= $this->input->post("contractor_id");
		$discount_type			= $this->input->post("discount_type");
		$discount_value			= $this->input->post("discount_value");

		$data = array(
			'discount_name' 				=> $discount_name,
			'discount_descr'				=> $discount_descr,
			//'discount_for_contractor_id'	=> $contractor_id,
			'discount_for_trade_id' 		=> $main_trade_id,
			'discount_for_sub_trade_id'		=> $sub_trade_id,
			'discount_for_zip'				=> $discount_for_zip,
			'discount_type'					=> $discount_type,
			'discount_value'				=> $discount_value,
			'discount_from_date'			=> $discount_from_date,
			'discount_to_date'				=> $discount_to_date,
			'created_by'					=> $this->session->userdata('user_id'),
			'updated_by'					=> $this->session->userdata('user_id'),
			'created_on'					=> date("Y-m-d H:i:s"),
			'updated_on'					=> date("Y-m-d H:i:s")
		);

		$params = array(
			'data'				=> $data,
			'discount_id'		=> $discount_id,
			'contractor_id'		=> $contractor_id
		);

		$response = $this->model_contractors->updateDiscount($params);

		print_r(json_encode($response));
	}

	public function deleteDiscount() {
		$this->load->model('service_providers/model_contractors');

		$discount_id 			= $this->input->post("discount_id");
		$contractor_id 			= $this->input->post("contractor_id");

		$params = array(
			"contractor_id"		=> $contractor_id,
			"discount_id"		=> $discount_id
		);

		$response = $this->model_contractors->deleteDiscount($params);
		print_r(json_encode($response));
	}

	public function viewAllTestimonial() {
		$this->load->model('service_providers/model_contractors');

		$contractor_id 			= $this->input->post("contractor_id");

		$params = array(
			"contractor_id"		=> $contractor_id
		);

		$response = $this->model_contractors->getTestimonial($params);

		if($response["status"] == "success") {
			$params["testimonialList"] = $response["testimonialList"];
		}

		echo $this->load->view("service_providers/contractors/testimonialView", $params, true);
	}

	public function createTestimonialForm() {
		$this->load->model('service_providers/model_contractors');

		$contractor_id 	= $this->input->post("contractor_id");

		$params = array(
			"contractor_id"		=> $contractor_id
		);

		echo $this->load->view("service_providers/contractors/inputFormTestimonial", $params, true);
	}

	public function addTestimonial() {
		$this->load->model('service_providers/model_contractors');

		$contractor_id 				= $this->input->post("contractor_id");
		$testimonial_summary		= $this->input->post("testimonial_summary");
		$testimonial_descr			= $this->input->post("testimonial_descr");
		$testimonial_rating		= $this->input->post("testimonial_rating");
		$testimonial_customer_name	= $this->input->post("testimonial_customer_name");
		$testimonial_date			= $this->input->post("testimonial_date");

		$data = array(
			'testimonial_contractor_id'		=> $contractor_id,
			'testimonial_customer_id'		=> "",
			'testimonial_anonynomus_name'	=> $testimonial_customer_name,
			'testimonial_summary' 			=> $testimonial_summary,
			'testimonial_descr'				=> $testimonial_descr,
			'testimonial_rating'			=> $testimonial_rating,
			'testimonial_date'				=> $testimonial_date,
			'created_by'					=> $this->session->userdata('user_id'),
			'updated_by'					=> $this->session->userdata('user_id'),
			'created_on'					=> date("Y-m-d H:i:s"),
			'updated_on'					=> date("Y-m-d H:i:s")
		);

		$response = $this->model_contractors->insertTestimonial($data);

		print_r(json_encode($response));
	}

	public function editTestimonialForm() {
		$this->load->model('service_providers/model_contractors');

		$contractor_id 				= $this->input->post("contractor_id");
		$testimonial_id 			= $this->input->post("testimonial_id");

		$params = array(
			"contractor_id"		=> $contractor_id,
			"testimonial_id"	=> $testimonial_id
		);

		$response = $this->model_contractors->getTestimonial($params);

		if($response["status"] == "success") {
			$params["testimonialList"] = $response["testimonialList"];
		}

		echo $this->load->view("service_providers/contractors/inputFormTestimonial", $params, true);
	}

	public function updateTestimonial() {
		$this->load->model('service_providers/model_contractors');

		$testimonial_id 			= $this->input->post("testimonial_id");
		$contractor_id 				= $this->input->post("contractor_id");
		$testimonial_summary		= $this->input->post("testimonial_summary");
		$testimonial_descr			= $this->input->post("testimonial_descr");
		$testimonial_rating		= $this->input->post("testimonial_rating");
		$testimonial_customer_name	= $this->input->post("testimonial_customer_name");
		$testimonial_date			= $this->input->post("testimonial_date");

		$data = array(
			'testimonial_anonynomus_name'	=> $testimonial_customer_name,
			'testimonial_summary' 			=> $testimonial_summary,
			'testimonial_descr'				=> $testimonial_descr,
			'testimonial_rating'			=> $testimonial_rating,
			'testimonial_date'				=> $testimonial_date,
			'updated_by'					=> $this->session->userdata('user_id'),
			'updated_on'					=> date("Y-m-d H:i:s")
		);

		$params = array(
			'data'				=> $data,
			'testimonial_id'	=> $testimonial_id,
			'contractor_id'		=> $contractor_id
		);

		$response = $this->model_contractors->updateTestimonial($params);

		print_r(json_encode($response));
	}

	public function deleteTestimonial() {
		$this->load->model('service_providers/model_contractors');

		$testimonial_id 		= $this->input->post("testimonial_id");
		$contractor_id 			= $this->input->post("contractor_id");

		$params = array(
			"contractor_id"		=> $contractor_id,
			"testimonial_id"		=> $testimonial_id
		);

		$response = $this->model_contractors->deleteTestimonial($params);
		print_r(json_encode($response));
	}
}