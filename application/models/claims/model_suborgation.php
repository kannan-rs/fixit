<?php

class Model_suborgation extends CI_Model {
	public function getSuborgationsList($params = array()) {
		$this->db->where('is_deleted', '0');

		$response = array(
			"status" 	=> "error",
			"message"	=> "Claim refrence is missing"
		);

		$claim_id = isset($params["claim_id"]) ? $params["claim_id"] : null;
		$suborgation_id = isset($params["suborgation_id"]) ? $params["suborgation_id"] : null;

		if(!empty($claim_id)) {
			if($suborgation_id) {
				if(is_array($suborgation_id) && implode(",", $suborgation_id) != "" ) {
					$this->db->where_in('id', $suborgation_id);
				} else if( !empty($suborgation_id) ) {
					$this->db->where('suborgation_id', $suborgation_id);
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

			$query = $this->db->from('claim_suborgation')->get();

			if($this->db->_error_number() == 0) {
				$response["status"] 		= "success";
				$response["suborgation"] 	= $query->result();
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
		if($this->db->insert('claim_suborgation', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "Suborgation Inserted Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	public function update($data, $record) {
		$response = array(
			'status' => 'error'
		);
		if($record) {
			$this->db->where('claim_id', $record);
		
			if($this->db->update('claim_suborgation', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'Claim updated Successfully';
				$response['updatedId']	= $record;
			} else {
				$response["message"] = "Error while updating the records";
			}	
		} else {
			$response['message']	= 'Invalid claim, Please try again';
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
			
			if($this->db->update('claim_suborgation', $data)) {
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