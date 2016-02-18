<?php
// Model Service Provider trade

class Model_trades extends CI_Model {
	public function getTradesList( $options = array()) {
		
		$contractorId 	= $options["contractor_id"];
		$trade_id 		= isset($options["trade_id"]) ? $options["trade_id"] : "";

		$response = array('status' => "error");

		if(empty($contractorId)) {
			$response["message"] = "Please provide contractor ID";
			return $response;
		}

		$this->db->where('trade_belongs_to_contractor_id', $contractorId);
		$this->db->where('is_deleted', "0");

		if(isset($trade_id) && !empty($trade_id)) {
			$this->db->where('trade_id', $trade_id);
		}

		if(isset($trade_parent) && !empty($trade_parent)) {
			$this->db->where('trade_parent', $trade_parent);
		}
		
		$this->db->select([
			"*", 
			"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
			"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
			"created_on",
			"updated_on"
		]);

		$query = $this->db->from('trades')->get();

		if($this->db->_error_number() == 0) {
			$response["status"] 		= "success";
			$response["tradesList"] 	= $query->result();
		} else {
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}

		return $response;
	}

	public function insertTrade($data) {
		$response = array();
		if($this->db->insert('trades', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "Trade Inserted Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	public function updateTrade( $options ) {
		$response = array(
			'status' => 'error'
		);
		
		$data 			= $options["data"];
		$trade_id 		= $options["trade_id"];
		$contractor_id 	= $options["contractor_id"];
		$main_trade_id 	= $options["main_trade_id"];

		if(isset($trade_id) && $trade_id != "" && isset($contractor_id) && $contractor_id != "") {
			$this->db->where('trade_id', $trade_id);
			$this->db->where('trade_belongs_to_contractor_id', $contractor_id);
		
			if($this->db->update('trades', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'Trade updated Successfully';
				$response['updatedId']	= $trade_id;
			} else {
				$response["message"] = "Error while updating the trade";
			}	
		} else {
			$response['message']	= 'Invalid trade update, Please try again';
		}
		return $response;
	}

	public function deleteTradeRecord( $options ) {
		$response = array(
			'status' => 'error'
		);

		$contractor_id	= $options["contractor_id"];
		$trade_id		= $options["trade_id"];

		if(isset($contractor_id) && $contractor_id != "" && isset($trade_id) && $trade_id != "") {
			$this->db->where('trade_id_from_master_list', $trade_id);
			$this->db->where('trade_belongs_to_contractor_id', $contractor_id);

			$data = array(
				'is_deleted' 				=> 1
			);
			
			if($this->db->update('trades', $data)) {
				$response['status']		= "success";
				$response['message']	= "Trade Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the trade";
			}
		} else {
			$response['message']	= 'Invalid contractor or trade, Please try again';
		}
		return $response;
	}

	public function deleteTradeRecordByParent( $options ) {
		$response = array(
			'status' => 'error'
		);

		$contractor_id	= $options["contractor_id"];
		$trade_id		= $options["trade_id"];
		$sub_trade_id 	= isset($options["sub_trade_id"]) ? $options["sub_trade_id"] : "";

		if(isset($contractor_id) && $contractor_id != "" && isset($trade_id) && $trade_id != "") {
			$this->db->where('trade_parent', $trade_id);
			$this->db->where('trade_belongs_to_contractor_id', $contractor_id);
			if(isset($sub_trade_id) && !empty($sub_trade_id)) {
				$this->db->where('trade_id_from_master_list', $sub_trade_id);
			}

			$data = array(
				'is_deleted' 				=> 1
			);
			
			if($this->db->update('trades', $data)) {
				$response['status']		= "success";
				$response['message']	= "Sub Trade Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the sub trade";
			}

		} else {
			$response['message']	= 'Invalid contractor or trade, Please try again';
		}
		return $response;
	}

	public function getMainTradeList( $tradeId = "" ) {
		$response = array('status' => "error");

		if(empty($tradeId)) {
			$response["message"] = "Invalid request";
		} else {
			if($tradeId != "all") {
				$this->db->where("main_trade_id", $tradeId);
			}
			$this->db->where('is_deleted', 0);
			$this->db->order_by("main_trade_name", "asc"); 
			$query = $this->db->from('trades_main')->get();
		
			if($this->db->_error_number() == 0) {
				$response["status"] 		= "success";
				$response["mainTradesList"] 	= $query->result();
			} else {
				$response["errorCode"] 		= $this->db->_error_number();
				$response["errorMessage"] 	= $this->db->_error_message();
			}
		}

		return $response;
	}

	public function getSubTradeList( $options ) {
		$sub_trade_id 	= isset($options["sub_trade_id"]) ? $options["sub_trade_id"] : null;
		$main_trade_id 	= isset($options["parent_trade_id"]) ? $options["parent_trade_id"] : null;

		$response = array('status' => "error");

		if(empty($sub_trade_id) || empty($main_trade_id)) {
			$response["message"] = "Invalid request";
		} else {
			if($sub_trade_id != "all") {
				$this->db->where("sub_trade_id", $sub_trade_id);
			}
			if($main_trade_id != "all") {
				$this->db->where("parent_trade_id", $main_trade_id);
			}
			$this->db->order_by("sub_trade_name", "asc"); 
			$this->db->where('is_deleted', 0);
			$query = $this->db->from('trades_sub')->get();
		
			if($this->db->_error_number() == 0) {
				$response["status"] 		= "success";
				$response["subTradesList"] 	= $query->result();
			} else {
				$response["errorCode"] 		= $this->db->_error_number();
				$response["errorMessage"] 	= $this->db->_error_message();
			}
		}

		return $response;
	}

	public function getAvailableTrades( $options = array()) {
		$contractor_id 		= $options["contractor_id"];
		$trade_for			= $options["trade_for"];
		$parent_trade_id 	= isset($options["parent_trade_id"]) ? $options["parent_trade_id"] : null;

		$response = array('status' => "error");

		if(empty($contractor_id) || empty($trade_for)) {
			$response["message"] = "Please provide contractor ID or trade for information";
			return $response;
		}

		if(($trade_for == "sub" || $trade_for != "main") && !$parent_trade_id) {
			$response["message"] = "Please provide trade type";
			return $response;
		}

		$this->db->where('trade_belongs_to_contractor_id', $contractor_id);
		$this->db->where('is_deleted', 0);


		if($trade_for == "main") {
			$this->db->where('trade_parent', 0);
		} else if( $trade_for == "sub") {
			$this->db->where('trade_parent', $parent_trade_id);
		}
		
		$this->db->select([
			"*", 
			"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
			"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
			"created_on",
			"updated_on"
		]);

		$query = $this->db->from('trades')->get();

		$response["query"] = $this->db->last_query();

		if($this->db->_error_number() == 0) {
			$response["status"] 		= "success";
			$response["availableTradesList"] 	= $query->result();
		} else {
			$response["errorCode"] 		= $this->db->_error_number();
			$response["message"] 	= $this->db->_error_message();
		}

		return $response;
	}

	public function add_master_main_trade( $data = array() ) {
		$response = array("status" => "error");
		if( !empty($data) ) {
			if($this->db->insert('trades_main', $data)) {
				$response["status"]		= "success";
				$response["insert_id"] 	= $this->db->insert_id();
				$response["message"]	=  "Master main trade inserted Successfully";
			} else {
				$response["message"]	= $this->db->_error_message();
				$response["errorCode"] 	= $this->db->_error_number();
			}
		} else {
			$response["message"] 		= "Invalid request";
		}

		return $response;
	}

	public function add_master_sub_trade( $data = array() ) {
		$response = array("status" => "error");
		if( !empty($data) ) {
			if($this->db->insert('trades_sub', $data)) {
				$response["status"]		= "success";
				$response["insert_id"] 	= $this->db->insert_id();
				$response["message"]	=  "Master sub trade inserted Successfully";
			} else {
				$response["message"]	= $this->db->_error_message();
				$response["errorCode"] 	= $this->db->_error_number();
			}
		} else {
			$response["message"] 		= "Invalid request";
		}

		return $response;
	}

	public function update_master_main_trade( $data = array() , $main_trade_id = "") {
		$response = array("status" => "error");
		
		if( !empty($data) && !empty($main_trade_id) ) {
			$this->db->where("main_trade_id", $main_trade_id);
			if($this->db->update('trades_main', $data)) {
				$response["status"]		= "success";
				$response["message"]	=  "Master main trade updated Successfully";
			} else {
				$response["message"]	= $this->db->_error_message();
				$response["errorCode"] 	= $this->db->_error_number();
			}
		} else {
			$response["message"] 		= "Invalid request";
		}

		return $response;
	}

	public function update_master_sub_trade( $data = array() , $main_trade_id = "", $sub_trade_id = "") {
		$response = array("status" => "error");
		
		if( !empty($data) && !empty($main_trade_id) && !empty($sub_trade_id) ) {
			$this->db->where("parent_trade_id", $main_trade_id);
			$this->db->where("sub_trade_id", $sub_trade_id);
			if($this->db->update('trades_sub', $data)) {
				$response["status"]		= "success";
				$response["message"]	=  "Master sub trade updated Successfully";
			} else {
				$response["message"]	= $this->db->_error_message();
				$response["errorCode"] 	= $this->db->_error_number();
			}
		} else {
			$response["message"] 		= "Invalid request";
		}

		return $response;
	}

	public function delete_master_main_trade( $main_trade_id = "" ) {
		$response = array("status" => "error");
		
		if( !empty($main_trade_id) ) {
			$this->db->where('main_trade_id', $main_trade_id);

			$data = array(
				'is_deleted' 				=> 1
			);
			
			if($this->db->update('trades_main', $data)) {
				$response['status']		= "success";
				$response['message']	= "Master main trade deleted successfully";
			} else {
				$response["message"] = "Error while deleting the records";
			}
		} else {
			$response["message"] 		= "Invalid request";
		}
		return $response;
	}

	public function delete_master_sub_trade( $main_trade_id = "", $sub_trade_id = "" ) {
		$response = array("status" => "error");
		
		if( !empty($main_trade_id) && !empty($sub_trade_id) ) {
			$this->db->where('parent_trade_id', $main_trade_id);
			$this->db->where('sub_trade_id', $sub_trade_id);

			$data = array(
				'is_deleted' 				=> 1
			);
			
			if($this->db->update('trades_sub', $data)) {
				$response['status']		= "success";
				$response['message']	= "Master sub trade deleted successfully";
			} else {
				$response["message"] = "Error while deleting the records";
			}
		} else {
			$response["message"] 		= "Invalid request";
		}
		return $response;
	}
}
