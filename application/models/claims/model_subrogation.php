<?php

class Model_subrogation extends CI_Model {
	public function getSubrogationsList($params = array()) {
		$this->db->where('is_deleted', '0');

		$response = array(
			"status" 	=> "error",
			"message"	=> "Claim refrence is missing"
		);

		$claim_id = isset($params["claim_id"]) ? $params["claim_id"] : null;
		$subrogation_id = isset($params["subrogation_id"]) ? $params["subrogation_id"] : null;

		if(!empty($claim_id)) {
			if($subrogation_id) {
				if(is_array($subrogation_id) && implode(",", $subrogation_id) != "" ) {
					$this->db->where_in('id', $subrogation_id);
				} else if( !empty($subrogation_id) ) {
					$this->db->where('subrogation_id', $subrogation_id);
				}
			}

			if( !empty($claim_id) ) {
				$this->db->where('claim_id', $claim_id);
			}

			$this->db->select([
				"*", 
				"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
				"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
				"created_on",
				"updated_on"
			]);

			$query = $this->db->from('claim_subrogation')->get();

			if($this->db->_error_number() == 0) {
				$response["status"] 		= "success";
				$response["subrogation"] 	= $query->result();
			} else {
				$response["errorCode"] 		= $this->db->_error_number();
				$response["message"] 	= $this->db->_error_message();
			}
		}

		//echo $this->db->last_query();
		
		return $response;
	}

	public function insert($data) {
		$response = array();
		if($this->db->insert('claim_subrogation', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "Subrogation Inserted Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	public function update( $update_params ) {
		$response = array(
			'status' => 'error'
		);

		$data 			= $update_params["data"];
		$claim_id		= $update_params["claim_id"];
		$subrogation_id	= $update_params["subrogation_id"];


		if(!empty($claim_id) && !empty($subrogation_id)) {
			$this->db->where('claim_id', $claim_id);
			$this->db->where('subrogation_id', $subrogation_id);
		
			if($this->db->update('claim_subrogation', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'Subrogation updated Successfully';
			} else {
				$response["message"] = "Error while updating the subrogation";
			}	
		} else {
			$response['message']	= 'Invalid claim or subrogation, Please try again';
		}
		return $response;
	}

	public function deleteRecord($record) {
		$response = array(
			'status' => 'error'
		);
		if($record && $record!= "") {
			$this->db->where('claim_id', $record);

			$data = array(
				'is_deleted' 				=> 1
			);
			
			if($this->db->update('claim_subrogation', $data)) {
				$response['status']		= "success";
				$response['message']	= "Claim Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the claim";
			}
		} else {
			$response['message']	= 'Invalid claim, Please try again';
		}
		return $response;
	}
}