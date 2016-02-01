<?php

class Model_partners extends CI_Model {
	
	public function getPartnersList($record = "", $companyNmae = "", $name = "") {
		$this->db->where('is_deleted', '0');

		if(isset($record) && !is_null($record)) {
			if(is_array($record) && implode(",", $record) != "" ) {
				$this->db->where_in('id', $record);
			} else if(!is_array($record) && !empty($record) ) {
				$this->db->where('id', $record);
			}
		}

		if(isset($companyNmae) && !is_null($companyNmae) && $companyNmae != "") {
			if(is_array($companyNmae)) {
				for($i = 0; $i < count($companyNmae); $i++) {
					if(!empty($companyNmae[$i])) {
						$this->db->or_like('company_name', $companyNmae[$i]);	
					}
				}
			} else if(!empty($companyNmae)) {
				$this->db->or_like('company_name', $companyNmae);
			}
		}

		if(isset($name) && !is_null($name) && $name != "") {
			if(is_array($name)) {
				for($i = 0; $i < count($name); $i++) {
					if(!empty($name[$i])) {
						$this->db->or_like('name', $name[$i]);
					}
				}
			} else if(!empty($name)) {
				$this->db->or_like('name', $name);
			}
		}

		$this->db->select([
				"*", 
				"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
				"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
				"created_on",
				"updated_on"
			]);

		$query = $this->db->from('partner')->get();

		//echo $this->db->last_query();
		
		$response = array();
		
		if($this->db->_error_number() == 0) {
			$response["status"] 		= "success";
			$response["partners"] 	= $query->result();
		} else {
			$response["status"] 		= "error";
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}
		
		return $response;
	}

	public function insert($data) {
		$response = array();
		if($this->db->insert('partner', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "Partner Inserted Successfully";
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
		
			if($this->db->update('partner', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'Partner updated Successfully';
				$response['updatedId']	= $record;
			} else {
				$response["message"] = "Error while updating the records";
			}	
		} else {
			$response['message']	= 'Invalid partner, Please try again';
		}
		return $response;
	}

	public function deleteRecord($record) {
		$response = array(
			'status' => 'error'
		);
		if($record && $record!= "") {
			$this->db->where('id', $record);

			$data = array(
				'is_deleted' => 1
			);
			
			if($this->db->update('partner', $data)) {
				$response['status']		= "success";
				$response['message']	= "Partner Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the partner";
			}
		} else {
			$response['message']	= 'Invalid partner, Please try again';
		}
		return $response;
	}
}