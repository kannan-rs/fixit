<?php

class Model_contractors extends CI_Model {
	
	public function getContractorsList($record = "", $zip = "") {
		if(isset($record) && !is_null($record) && $record != "") {
			if(is_array($record)) {
				for($i = 0; $i < count($record); $i++) {
					$this->db->or_where('id', $record[$i]);	
				}
			} else {
				$this->db->where('id', $record);
			}
		}

		if(isset($zip) && !is_null($zip) && $zip != "") {
			if(is_array($zip)) {
				for($i = 0; $i < count($zip); $i++) {
					$this->db->or_like('service_area', $zip[$i]);	
				}
			} else {
				$this->db->or_like('service_area', $zip);
			}
		}

		$this->db->select([
				"*", 
				"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
				"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
				"created_on",
				"updated_on"
			]);
		$query = $this->db->from('contractor')->get();
		
		$contractors = $query->result();
		return $contractors;
	}

	public function insert($data) {
		$response = array();
		if($this->db->insert('contractor', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "contractor Inserted Successfully";
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
			$this->db->where('id', $record);
		
			if($this->db->update('contractor', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'contractor updated Successfully';
				$response['updatedId']	= $record;
			} else {
				$response["message"] = "Error while updating the records";
			}	
		} else {
			$response['message']	= 'Invalid contractor, Please try again';
		}
		return $response;
	}

	public function deleteRecord($record) {
		$response = array(
			'status' => 'error'
		);
		if($record && $record!= "") {
			$this->db->where('id', $record);
			
			if($this->db->delete('contractor')) {
				$response['status']		= "success";
				$response['message']	= "contractor Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the contractor";
			}
		} else {
			$response['message']	= 'Invalid contractor, Please try again';
		}
		return $response;
	}
}