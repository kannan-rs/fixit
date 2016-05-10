<?php
class Discounts extends CI_Controller {

	public function __construct()
   	{
        parent::__construct();
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

	private function convertTradesDBToId($tradeFromDb) {
		$tradesList = array();
		for($i = 0; $i < count($tradeFromDb); $i++) {
			$tradesList[$tradeFromDb[$i]->trade_id] = $tradeFromDb[$i];
		}
		return $tradesList;
	}

	public function viewAll() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$permission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER_DISCOUNT);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Service Provider Discount List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');
		$this->load->model('service_providers/model_discounts');
		
		$contractor_id	= $this->input->post("contractor_id");
		$sub_trade_id 	= !empty($this->input->post("sub_trade_id")) ? $this->input->post("sub_trade_id") : "";
		$main_trade_id 	= !empty($this->input->post("main_trade_id")) ? $this->input->post("main_trade_id") : "";
		$trade_name 	= !empty($this->input->post("trade_name")) ? $this->input->post("trade_name") : "";

		$getParams = array(
			"contractor_id" => $contractor_id
		);

		/* Get Trades */
		$tradesListResponse = $this->model_trades->getTradesList( $getParams );
		$tradesList 		= $this->convertTradesDBToId($tradesListResponse["tradesList"]);

		/* Get Main Trade and Sub trade Values from databases */
		$main_trades_response = $this->model_trades->getMainTradeList("all");
		$main_trades = null;
		if($main_trades_response["status"] == "success") {
			$main_trades = $main_trades_response["mainTradesList"];
			$main_trades 	= $this->_mapMainTradeToId( $main_trades );
		}

		// Get Sub trade list Trades
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

		/* Get Discount List */
		$response = $this->model_discounts->getDiscountList($getParams);

		$params = array(
			"sub_trade_id"		=> $sub_trade_id,
			"main_trade_id"		=> $main_trade_id,
			"contractor_id"		=> $contractor_id,
			"trade_name"		=> $trade_name,
			"tradesList"		=> $tradesList,
			"main_trades"		=> $main_trades,
			"sub_trades"		=> $sub_trades
		);

		if($response["status"] == "success") {
			$params['discountList'] = $response["discountList"];
		}

		echo $this->load->view("service_providers/discounts/viewAll", $params, true);
	}

	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$permission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER_DISCOUNT);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "create discount"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');

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

		$response = $this->model_trades->getTradesList( $getParams );

		if($response["status"] == "success") {
			$params['mainTradesList'] = $response["tradesList"];
		}

		/* Get Sub Trade List */
		$getParams = array(
			"trade_id"			=> $sub_trade_id,
			"trade_parent"		=> $main_trade_id,
			"contractor_id"		=> $contractor_id
		);

		$response = $this->model_trades->getTradesList( $getParams );

		if($response["status"] == "success") {
			$params['subTradesList'] = $response["tradesList"];
		}

		echo $this->load->view("service_providers/discounts/inputForm", $params, true);
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER_DISCOUNT, OPERATION_CREATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_discounts');

		/*$main_trade_id 			= $this->input->post("main_trade_id");
		$sub_trade_id 			= $this->input->post("sub_trade_id");
		$discount_name 			= $this->input->post("discount_name");
		$discount_descr 		= $this->input->post("discount_descr");
		$discount_for_zip 		= $this->input->post("discount_for_zip");
		$discount_from_date 	= $this->input->post("discount_from_date");
		$discount_to_date 		= $this->input->post("discount_to_date");
		$contractor_id 			= $this->input->post("contractor_id");
		$discount_type			= $this->input->post("discount_type");
		$original_value			= $this->input->post("original_value");
		$discount_value			= $this->input->post("discount_value");*/

		$main_trade_id 			= $this->input->post("input_discount_for_main_trade");
		$sub_trade_id 			= $this->input->post("input_discount_for_sub_trade");
		$discount_name 			= $this->input->post("discount_name");
		$discount_descr 		= $this->input->post("discount_descr");
		$discount_for_zip 		= $this->input->post("discount_for_zip");
		$discount_from_date 	= $this->input->post("discount_from_date_to_db");
		$discount_to_date 		= $this->input->post("discount_to_date_to_db");
		$contractor_id 			= $this->input->post("selected_contractor_id");
		$discount_type			= $this->input->post("discount_type_to_db");
		$original_value			= $this->input->post("original_value");
		$discount_value			= $this->input->post("discount_value");

		$data = array(
			'discount_name' 				=> $discount_name,
			'discount_descr'				=> $discount_descr,
			'discount_for_contractor_id'	=> $contractor_id,
			'discount_for_trade_id' 		=> $main_trade_id,
			'discount_for_sub_trade_id'		=> $sub_trade_id,
			'discount_for_zip'				=> $discount_for_zip,
			'discount_type'					=> $discount_type,
			'original_value'				=> $original_value,
			'discount_value'				=> $discount_value,
			'discount_from_date'			=> $discount_from_date,
			'discount_to_date'				=> $discount_to_date,
			'created_by'					=> $this->session->userdata('logged_in_user_id'),
			'updated_by'					=> $this->session->userdata('logged_in_user_id'),
			'created_on'					=> date("Y-m-d H:i:s"),
			'updated_on'					=> date("Y-m-d H:i:s")
		);

		if(count($_FILES) > 0) {
			if(is_uploaded_file($_FILES['discountImage']['tmp_name'])) {
				$data["discount_image"] =  addslashes(file_get_contents($_FILES['discountImage']['tmp_name']));
			}
		}

		//print_r($data);
		$response = $this->model_discounts->insertDiscount($data);

		print_r(json_encode($response));
	}

	public function editForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$permission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER_DISCOUNT);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_UPDATE, $permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "update discount"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_discounts');

		$contractor_id	= $this->input->post("contractor_id");
		$discount_id	= $this->input->post("discount_id");

		$getParams = array(
			"contractor_id" => $contractor_id,
			'discount_id'	=> $discount_id
		);

		/* Get Discount List */
		$response = $this->model_discounts->getDiscountList($getParams);

		if($response["status"] == "success") {
			$params['discountList'] = $response["discountList"];
		}

		echo $this->load->view("service_providers/discounts/inputForm", $params, true);
	}

	public function update() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER_DISCOUNT, OPERATION_UPDATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_discounts');

		/*$discount_id 			= $this->input->post("discount_id");
		$main_trade_id 			= $this->input->post("main_trade_id");
		$sub_trade_id 			= $this->input->post("sub_trade_id");
		$discount_name 			= $this->input->post("discount_name");
		$discount_descr 		= $this->input->post("discount_descr");
		$discount_for_zip 		= $this->input->post("discount_for_zip");
		$discount_from_date 	= $this->input->post("discount_from_date");
		$discount_to_date 		= $this->input->post("discount_to_date");
		$contractor_id 			= $this->input->post("contractor_id");
		$discount_type			= $this->input->post("discount_type");
		$original_value			= $this->input->post("original_value");
		$discount_value			= $this->input->post("discount_value");*/

		$discount_id 			= $this->input->post("dbDiscountId");
		$main_trade_id 			= $this->input->post("input_discount_for_main_trade");
		$sub_trade_id 			= $this->input->post("input_discount_for_sub_trade");
		$discount_name 			= $this->input->post("discount_name");
		$discount_descr 		= $this->input->post("discount_descr");
		$discount_for_zip 		= $this->input->post("discount_for_zip");
		$discount_from_date 	= $this->input->post("discount_from_date_to_db");
		$discount_to_date 		= $this->input->post("discount_to_date_to_db");
		$contractor_id 			= $this->input->post("selected_contractor_id");
		$discount_type			= $this->input->post("discount_type_to_db");
		$original_value			= $this->input->post("original_value");
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
			'original_value'				=> $original_value,
			'discount_from_date'			=> $discount_from_date,
			'discount_to_date'				=> $discount_to_date,
			'created_by'					=> $this->session->userdata('logged_in_user_id'),
			'updated_by'					=> $this->session->userdata('logged_in_user_id'),
			'created_on'					=> date("Y-m-d H:i:s"),
			'updated_on'					=> date("Y-m-d H:i:s")
		);

		if(count($_FILES) > 0) {
			if(is_uploaded_file($_FILES['discountImage']['tmp_name'])) {
				$data["discount_image"] =  addslashes(file_get_contents($_FILES['discountImage']['tmp_name']));
			}
		}

		$params = array(
			'data'				=> $data,
			'discount_id'		=> $discount_id,
			'contractor_id'		=> $contractor_id
		);

		$response = $this->model_discounts->updateDiscount($params);

		print_r(json_encode($response));
	}

	public function delete() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}
		
		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER_DISCOUNT, OPERATION_DELETE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_discounts');

		$discount_id 			= $this->input->post("discount_id");
		$contractor_id 			= $this->input->post("contractor_id");

		$params = array(
			"contractor_id"		=> $contractor_id,
			"discount_id"		=> $discount_id
		);

		$response = $this->model_discounts->deleteDiscount($params);
		print_r(json_encode($response));
	}

	public function show_discount() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$permission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER_DISCOUNT);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Service Provider Discount List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_discounts');
		
		$discount_id	= $this->input->post("discount_id");
		$contractor_id	= $this->input->post("contractor_id");

		/* Get Discount List */
		$getParams = array(
			"contractor_id" => $contractor_id,
			'discount_id'	=> $discount_id
		);

		$response = $this->model_discounts->getDiscountList($getParams);

		if($response["status"] == "success") {
			$params['discountList'] = $response["discountList"];
		}

		echo $this->load->view("service_providers/discounts/view_image", $params, true);
	}
}