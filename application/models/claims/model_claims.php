<?php

class Model_claims extends CI_Model {
	public function getClaimsList($params = array()) {
		$this->db->where('is_deleted', '0');

		$claim_id = isset($params["claim_id"]) ? $params["claim_id"] : null;

		if($claim_id) {
			if(is_array($claim_id) && implode(",", $claim_id) != "" ) {
				$this->db->where_in('id', $claim_id);
			} else if( !empty($claim_id) ) {
				$this->db->where('claim_id', $claim_id);
			}
		}

		$this->db->select([
			"*", 
			"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
			"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
			"created_on",
			"updated_on"
		]);

		$query = $this->db->from('claim')->get();

		//echo $this->db->last_query();
		
		$response = array();
		
		if($this->db->_error_number() == 0) {
			$response["status"] 		= "success";
			$response["claims"] 	= $query->result();
		} else {
			$response["status"] 		= "error";
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}
		
		return $response;
	}

	public function insert($data) {
		$response = array();
		if($this->db->insert('claim', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "Claim Inserted Successfully";
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
		
			if($this->db->update('claim', $data)) {
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
			
			if($this->db->update('claim', $data)) {
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

	public function getProjectClaim( $claim_number ) {
		$response = array(
			"status"	=> "error"
		);

		if(!isset($claim_number) || empty($claim_number)) {
			$response["message"] = "Provide vaild claim number";
		} else {
			$this->db->where('is_deleted', '0');
			$this->db->where('associated_claim_num', $claim_number);

			$this->db->select([
					"proj_id",
					"project_name",
					"associated_claim_num",
					"project_budget"
				]);
			$query = $this->db->from('project')->get();
			
			$projects = $query->result();

			if($projects) {
				$response["status"] 	= "success";
				$response["projects"]	= $projects;
			}
		}

		return $response;
	}
}