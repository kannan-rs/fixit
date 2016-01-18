<?php

class Model_dairy_updates extends CI_Model {
	public function getDairyUpdatesList($claim_id = "", $dairy_updates_id, $startRecord = 0, $count) {
		$this->db->where('is_deleted', '0');

		$countWhereStr = " WHERE is_deleted = 0";

		if(isset($claim_id) && !is_null($claim_id) && $claim_id != "") {
			$this->db->where('claim_id', $claim_id);
			$countWhereStr .= " AND claim_id = ".$claim_id;
		}


		if(isset($dairy_updates_id) && !is_null($dairy_updates_id) && $dairy_updates_id != "" && $dairy_updates_id != 0) {
			$this->db->where('dairy_updates_id', $dairy_updates_id);
			$countWhereStr .= (count($countWhereStr) ? " AND " : " where")." dairy_updates_id = ".$dairy_updates_id;
		}

		$this->db->select([
				"*", 
				"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
				"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
				"created_on",
				"updated_on"
			]);
		$this->db->order_by("created_on", "desc");
		$query = $this->db->from('claim_dairy_updates')->get();
		$dairyUpdatesResult = $query->result();

		// Count
		$countQueryStr 	= "SELECT COUNT(*) as count FROM `claim_dairy_updates`".$countWhereStr;
		$countQuery 	= $this->db->query($countQueryStr);
		$countResult	= $countQuery->result();

		$response = array();
		
		$response["count"] 			= $countResult;
		if($this->db->_error_number() == 0) {
			$response["status"] 		= "success";
			$response["dairy_updates"] 			= $dairyUpdatesResult;
		} else {
			$response["status"] 		= "error";
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}
		
		return $response;
	}

	public function insert($data) {
		$response = array();
		if($this->db->insert('claim_dairy_updates', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']		= $this->db->insert_id();
			$response['message']		= "Claim Dairy Update Inserted Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	public function count( $claim_id = 0) {
		if($claim_id > 0) {
			$this->db->where('claim_id', $claim_id);
			$this->db->select(["count(*) as count"]);

			$query = $this->db->from('claim_dairy_updates')->get();

			$dairy_updates_count = $query->result();

			if(count($dairy_updates_count)) {
				return $dairy_updates_count[0]->count;
			}
		} else {
			return 0;
		}
	}

	public function deleteRecord($dairy_updates_id) {
		$response = array(
			'status' => 'error'
		);
		if($dairy_updates_id && $dairy_updates_id != "") {
			$this->db->where('dairy_updates_id', $dairy_updates_id);

			$data = array(
				'is_deleted' 				=> 1
			);
			
			if($this->db->update('claim_dairy_updates', $data)) {
				$response['status']		= "success";
				$response['message']	= "Claim Dairy Update Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the records";
			}
		} else {
			$response['message']	= 'Invalid Dairy Update, Please try again';
		}
		return $response;
	}
}