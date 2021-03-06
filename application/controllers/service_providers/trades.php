<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trades extends CI_Controller {

	public function __construct()
   	{
        parent::__construct();
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

	private function _mix_sub_trade_to_main_trade( $main_trades = array(), $sub_trades = array()) {
		foreach ($sub_trades as $sub_trade_id => $sub_trade) {
			if( isset($main_trades[$sub_trade->parent_trade_id])) {
				if( !isset($main_trades[$sub_trade->parent_trade_id]->sub_trades )) {
					$main_trades[$sub_trade->parent_trade_id]->sub_trades = array();
				}
				array_push($main_trades[$sub_trade->parent_trade_id]->sub_trades, $sub_trade);
			}
			
		}
		return $main_trades;
	}

	public function getTradesList() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER_TRADE, OPERATION_VIEW);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');

		$contractor_id = $this->input->post("contractorId");

		// Get All Trades
		$main_trades_response = $this->model_trades->getMainTradeList("all");
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
		$sub_trades_response = $this->model_trades->getSubTradeList($options);

		$sub_trades_list = [];
		if($sub_trades_response["status"] == "success") {
			$sub_trades = $sub_trades_response["subTradesList"];
			$sub_trades = $this->_mapSubTradeToId( $sub_trades );
		}

		$params = array(
			"contractor_id" => $contractor_id
		);

		$tradesList = $this->model_trades->getTradesList( $params );

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
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$permission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER_TRADE);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Create trade"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');

		$contractor_id = $this->input->post("contractorId");

		// Get All Trades
		$main_trades_response = $this->model_trades->getMainTradeList("all");
		$main_trades = null;
		if($main_trades_response["status"] == "success") {
			$main_trades = $main_trades_response["mainTradesList"];
		}

		// Get Already assigned trade ID's
		$options = array( 
			"contractor_id" => $contractor_id,
			"trade_for"		=> "main"
		);

		$available_main_trades_response = $this->model_trades->getAvailableTrades( $options );

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

		echo $this->load->view("service_providers/trades/inputFormMainTrade", $params, true);
	}

	public function create_form_main_trade_master() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN ) {
			$no_permission_options = array(
				'page_disp_string' => "Trades management list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$params = array();

		echo $this->load->view("service_providers/trades/input_form_main_trade_for_master", $params, true);
	}

	public function edit_form_main_trade_master() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$main_trade_id = $this->input->post("master_main_trade_id");
		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN || empty($main_trade_id)) {
			$no_permission_options = array(
				'page_disp_string' => "Trades management list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_trades');

		$response = array("status" => "error");
		
		$trade_list_response = $this->model_trades->getMainTradeList( $main_trade_id );

		if($trade_list_response["status"] == "error") {
			$response = $trade_list_response;
		} else {
			$params = array(
				"master_main_trade_list" 	=> $trade_list_response["mainTradesList"],
				"main_trade_id"				=> $main_trade_id
			);
			echo $this->load->view("service_providers/trades/input_form_main_trade_for_master", $params, true);
		}
	}

	public function create_form_sub_trade_master() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$main_trade_id = $this->input->post("master_main_trade_id");

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN || empty($main_trade_id)) {
			$no_permission_options = array(
				'page_disp_string' => "Trades management list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$params = array(
			"main_trade_id" => $main_trade_id
		);

		echo $this->load->view("service_providers/trades/input_form_sub_trade_for_master", $params, true);
	}

	public function edit_form_sub_trade_master() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$main_trade_id = $this->input->post("master_main_trade_id");
		$sub_trade_id = $this->input->post("master_sub_trade_id");
		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN || empty($main_trade_id) || empty($sub_trade_id)) {
			$no_permission_options = array(
				'page_disp_string' => "Trades management list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_trades');

		$response = array("status" => "error");

		$params = array(
			"sub_trade_id"		=> $sub_trade_id,
			"parent_trade_id"	=> $main_trade_id
		);
		
		$trade_list_response = $this->model_trades->getSubTradeList( $params );
		
		if($trade_list_response["status"] == "error") {
			$response = $trade_list_response;
		} else {
			$params = array(
				"master_sub_trade_list" 	=> $trade_list_response["subTradesList"],
				"main_trade_id"				=> $main_trade_id,
				"sub_trade_id"				=> $sub_trade_id
			);
			echo $this->load->view("service_providers/trades/input_form_sub_trade_for_master", $params, true);
		}
	}

	public function addTrades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER_TRADE, OPERATION_CREATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');

		$contractor_id = $this->input->post("contractor_id");
		$main_trade_id = $this->input->post("main_trade_id");
		$sub_trades_id = $this->input->post("sub_trades_id");

		$data = array(
			'trade_belongs_to_contractor_id'	=> $contractor_id,
			'trade_id_from_master_list' 		=> $main_trade_id,
			'created_by'						=> $this->session->userdata('logged_in_user_id'),
			'updated_by'						=> $this->session->userdata('logged_in_user_id'),
			'created_on'						=> date("Y-m-d H:i:s"),
			'updated_on'						=> date("Y-m-d H:i:s")
		);

		$response = $this->model_trades->insertTrade($data);

		if($response["status"] == "success") {
			$sub_trades_id_arr = explode(",", $sub_trades_id);
			for($i = 0, $count = count($sub_trades_id_arr); $i < $count; $i++) {
				if($response["status"] == "success") {
					$data = array (
						'trade_belongs_to_contractor_id'	=> $contractor_id,
						'trade_id_from_master_list' 		=> $sub_trades_id_arr[$i],
						'trade_parent'						=> $main_trade_id,
						'created_by'						=> $this->session->userdata('logged_in_user_id'),
						'updated_by'						=> $this->session->userdata('logged_in_user_id'),
						'created_on'						=> date("Y-m-d H:i:s"),
						'updated_on'						=> date("Y-m-d H:i:s")
					);
					$response = $this->model_trades->insertTrade($data);		
				} else {
					break;
				}
			}
		}

		print_r(json_encode($response));
	}

	public function master_list_add_main_trades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN ) {
			$no_permission_options = array(
				'page_disp_string' => "Trades management list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$response = array("status" => "error");

		$main_trade_name 	= $this->input->post("master_main_trade_name");
		$main_trade_descr 	= $this->input->post("master_main_trade_descr");

		$this->load->model('service_providers/model_trades');

		if(!empty($main_trade_name)) {
			$trade_data = array(
				'main_trade_name'			=> $main_trade_name,
				'main_trade_description'	=> $main_trade_descr,
				'is_deleted'				=> 0 
			);

			$response = $this->model_trades->add_master_main_trade( $trade_data );
		} else {
			$response["message"] 	= "Invalid request";
		}

		print_r(json_encode($response));
	}

		public function master_list_add_sub_trades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN ) {
			$no_permission_options = array(
				'page_disp_string' => "Trades management list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$response = array("status" => "error");

		$master_sub_trade_name		= $this->input->post("master_sub_trade_name");
		$master_sub_trade_descr		= $this->input->post("master_sub_trade_descr");
		$master_main_trade_id		= $this->input->post("master_main_trade_id");

		$this->load->model('service_providers/model_trades');

		if( !empty($master_sub_trade_name) && !empty($master_sub_trade_descr) && !empty($master_main_trade_id) ) {
			$trade_data = array(
				'sub_trade_name'		=> $master_sub_trade_name,
				'sub_trade_description'	=> $master_sub_trade_descr,
				'parent_trade_id'		=> $master_main_trade_id,
				'is_deleted'			=> 0 
			);

			$response = $this->model_trades->add_master_sub_trade( $trade_data );
		} else {
			$response["message"] 	= "Invalid request";
		}

		print_r(json_encode($response));
	}

	public function master_list_update_main_trades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN ) {
			$no_permission_options = array(
				'page_disp_string' => "Trades management list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$response = array("status" => "error");

		$main_trade_id 		= $this->input->post("master_main_trade_id");
		$main_trade_name 	= $this->input->post("master_main_trade_name");
		$main_trade_descr 	= $this->input->post("master_main_trade_descr");

		$this->load->model('service_providers/model_trades');

		if(!empty($main_trade_name)) {
			$trade_data = array(
				'main_trade_name'			=> $main_trade_name,
				'main_trade_description'	=> $main_trade_descr
			);
			$response = $this->model_trades->update_master_main_trade( $trade_data, $main_trade_id );
		} else {
			$response["message"] 	= "Invalid request";
		}

		print_r(json_encode($response));
	}

	public function master_list_update_sub_trades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN ) {
			$no_permission_options = array(
				'page_disp_string' => "Trades management list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$response = array("status" => "error");

		$main_trade_id 		= $this->input->post("master_main_trade_id");
		$sub_trade_id 		= $this->input->post("master_sub_trade_id");
		$sub_trade_name 	= $this->input->post("master_sub_trade_name");
		$sub_trade_descr 	= $this->input->post("master_sub_trade_descr");

		$this->load->model('service_providers/model_trades');

		if(!empty($main_trade_id) && !empty($sub_trade_id) && !empty($sub_trade_name) && !empty($sub_trade_descr)) {
			$trade_data = array(
				'sub_trade_name'			=> $sub_trade_name,
				'sub_trade_description'		=> $sub_trade_descr
			);
			$response = $this->model_trades->update_master_sub_trade( $trade_data, $main_trade_id, $sub_trade_id );
		} else {
			$response["message"] 	= "Invalid request";
		}

		print_r(json_encode($response));
	}

	public function master_list_delete_main_trades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN ) {
			$no_permission_options = array(
				'page_disp_string' => "Trades management list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$response = array("status" => "error");

		$main_trade_id 		= $this->input->post("master_main_trade_id");

		$this->load->model('service_providers/model_trades');

		$response = $this->model_trades->delete_master_main_trade( $main_trade_id );

		print_r(json_encode($response));
	}

	public function master_list_delete_sub_trades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN ) {
			$no_permission_options = array(
				'page_disp_string' => "Trades management list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$response = array("status" => "error");

		$main_trade_id 		= $this->input->post("master_main_trade_id");
		$sub_trade_id 		= $this->input->post("master_sub_trade_id");

		$this->load->model('service_providers/model_trades');

		$response = $this->model_trades->delete_master_sub_trade( $main_trade_id, $sub_trade_id );

		print_r(json_encode($response));
	}

	public function updateFormMainTrades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$permission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER_TRADE);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_UPDATE, $permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "update trade"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$trade_id 		= $this->input->post("trade_id");
		$contractor_id 	= $this->input->post("contractorId");
		$displayString 	= $this->input->post("displayString");

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');

		// Get All Trades
		$main_trades_response = $this->model_trades->getMainTradeList("all");
		$main_trades = null;
		if($main_trades_response["status"] == "success") {
			$main_trades = $main_trades_response["mainTradesList"];
		}

		// Get Already assigned trade ID's
		$options = array( 
			"contractor_id" => $contractor_id,
			"trade_for"		=> "main"
		);

		$available_main_trades_response = $this->model_trades->getAvailableTrades( $options );

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

		echo $this->load->view("service_providers/trades/inputFormMainTrade", $params, true);
	}

	public function updateTrades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER_TRADE, OPERATION_UPDATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');

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
						'created_by'						=> $this->session->userdata('logged_in_user_id'),
						'updated_by'						=> $this->session->userdata('logged_in_user_id'),
						'created_on'						=> date("Y-m-d H:i:s"),
						'updated_on'						=> date("Y-m-d H:i:s")
					);
					$response = $this->model_trades->insertTrade($data);		
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
					$response = $this->model_trades->deleteTradeRecordByParent($options);		
				} else {
					break;
				}
			}

		}

		print_r(json_encode($response));
	}

	public function deleteMainTrades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER_TRADE, OPERATION_DELETE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');

		$contractor_id = $this->input->post("contractor_id");
		$trade_id 		= $this->input->post("trade_id");

		$params = array(
			"contractor_id"	=> $contractor_id,
			"trade_id"		=> $trade_id
		);

		$response = $this->model_trades->deleteTradeRecord($params);

		if($response["status"] == "success") {
			$response_dependent = $this->model_trades->deleteTradeRecordByParent( $params );
		}

		if(isset($response_dependent)) {
			$response["status"] 	= $response_dependent["status"];
			$response["message"]	.= ". ".$response_dependent["message"];
		}

		print_r(json_encode($response));
	}

	public function createSubTradesForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$permission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER_TRADE);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Create trade"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');


		$contractor_id 		= $this->input->post("contractor_id");
		$main_trade_id		= $this->input->post("main_trade_id");
		
		// Get All Trades
		$options = array(
			"sub_trade_id" 		=> "all",
			"parent_trade_id"	=> $main_trade_id,
			"trade_for"			=> "sub",
			"contractor_id"		=> $contractor_id
		);


		$sub_trades_response = $this->model_trades->getSubTradeList($options);

		$sub_trades_list = [];

		if($sub_trades_response["status"] == "success") {
			$sub_trades_list = $sub_trades_response["subTradesList"];
		}

		$available_sub_trades_response = $this->model_trades->getAvailableTrades( $options );

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

		echo $this->load->view("service_providers/trades/inputFormSubTrade", $params, true);
	}

	public function addSubTrades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER_TRADE, OPERATION_CREATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');

		$contractor_id 		= $this->input->post("contractor_id");
		$sub_trade_name 	= $this->input->post("sub_trade_name");
		$main_trade_id 		= $this->input->post("main_trade_id");

		$data = array(
			'trade_parent'						=> $main_trade_id,
			'trade_belongs_to_contractor_id'	=> $contractor_id,
			'trade_name' 						=> $sub_trade_name,
			'created_by'						=> $this->session->userdata('logged_in_user_id'),
			'updated_by'						=> $this->session->userdata('logged_in_user_id'),
			'created_on'						=> date("Y-m-d H:i:s"),
			'updated_on'						=> date("Y-m-d H:i:s")
		);

		$response = $this->model_trades->insertTrade($data);

		print_r(json_encode($response));
	}

	public function updateFormSubTrades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$permission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER_TRADE);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_UPDATE, $permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "update trade"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');

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

		$response = $this->model_trades->getTradesList( $getParams );

		if($response["status"] == "success") {
			$params['tradesList'] = $response["tradesList"];
		}

		echo $this->load->view("service_providers/trades/inputFormSubTrade", $params, true);
	}

	public function updateSubTrades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER_TRADE, OPERATION_UPDATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');

		$sub_trade_name	= $this->input->post("sub_trade_name");
		$sub_trade_id	= $this->input->post("sub_trade_id");
		$main_trade_id	= $this->input->post("main_trade_id");
		$contractor_id	= $this->input->post("contractor_id");

		$data = array(
			'trade_name' 						=> $sub_trade_name,
			'created_by'						=> $this->session->userdata('logged_in_user_id'),
			'updated_by'						=> $this->session->userdata('logged_in_user_id'),
			'created_on'						=> date("Y-m-d H:i:s"),
			'updated_on'						=> date("Y-m-d H:i:s")
		);

		$params = array(
			'data'			=> $data,
			'trade_id'		=> $sub_trade_id,
			"contractor_id"	=> $contractor_id,
			"main_trade_id"	=> $main_trade_id
		);

		$response = $this->model_trades->updateTrade($params);

		print_r(json_encode($response));
	}

	public function deleteSubTrades() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}
		
		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER_TRADE, OPERATION_DELETE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');

		$sub_trade_id	= $this->input->post("sub_trade_id");
		$main_trade_id	= $this->input->post("main_trade_id");
		$contractor_id	= $this->input->post("contractor_id");

		$params = array(
			"contractor_id"	=> $contractor_id,
			"trade_id"		=> $sub_trade_id,
			"trade_parent" 	=> $main_trade_id
		);

		$response = $this->model_trades->deleteTradeRecord($params);
		print_r(json_encode($response));
	}

	public function list_all_trades_and_manage() {
		$response = array(
			"status" => "success"
		);
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN ) {
			$no_permission_options = array(
				'page_disp_string' => "Trades management list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_trades');

		// Get All Trades
		$main_trades_response = $this->model_trades->getMainTradeList("all");
		$main_trades = null;
		if($main_trades_response["status"] == "success") {
			$response["status"] = "success";
			$main_trades = $main_trades_response["mainTradesList"];
			$main_trades 	= $this->_mapMainTradeToId( $main_trades );
		} else {
			$response["status"] = "error";
		}

		if($response["status"] == "success") {
			// Get All Trades
			$options = array(
				"sub_trade_id" 		=> "all",
				"parent_trade_id"	=> "all",
				"trade_for"			=> "sub"
			);
			$sub_trades_response = $this->model_trades->getSubTradeList($options);

			$sub_trades_list = [];
			if($sub_trades_response["status"] == "success") {
				$sub_trades = $sub_trades_response["subTradesList"];
				$sub_trades = $this->_mapSubTradeToId( $sub_trades );
			} else {
				$response["status"] = "error";
			}
		}

		if($response["status"] == "success") {
			$mixed_master_trades = $this->_mix_sub_trade_to_main_trade($main_trades, $sub_trades);
			$response["trade_list"] = $mixed_master_trades;
		}

		print_r(json_encode($response));
	}
}