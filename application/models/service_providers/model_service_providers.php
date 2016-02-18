<?php

class Model_service_providers extends CI_Model {
	
	public function get_service_provider_list($record = "", $zip = "", $serviceZip = "") {
		$this->db->where('is_deleted', '0');
		
		if(isset($record) && !is_null($record)) {
			//echo "record =>"; print_r(array_filter($record)); echo "--count =>".count($record)."--imp--".implode(",", $record)."<br/>";
			if(is_array($record) && implode(",", $record) != "" ) {
				$this->db->where_in('id', $record);
			} else if($record != "") {
				$this->db->where('id', $record);
			}
		}

		if(isset($zip) && !is_null($zip) && $zip != "") {
			//echo "zip =>"; print_r($zip); echo "--count =>".count($zip)."<br/>";
			if(is_array($zip)) {
				for($i = 0; $i < count($zip); $i++) {
					if($zip[$i] != "") $this->db->or_like('pin_code', $zip[$i]);	
				}
			} else if($zip != "") {
				$this->db->or_like('pin_code', $zip);
			}
		}

		if(isset($serviceZip) && !is_null($serviceZip) && $serviceZip != "") {
			//echo "serviceZip =>"; print_r(array_filter($serviceZip)); echo "--count =>".count($serviceZip)."--imp--".implode(",", $serviceZip)."<br/>";
			if(is_array($serviceZip)) {
				for($i = 0; $i < count($serviceZip); $i++) {
					if($serviceZip[$i] != "") $this->db->or_like('service_area', $serviceZip[$i]);	
				}
			} else if($service_area != "") {
				$this->db->or_like('service_area', $serviceZip);
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

		//echo $this->db->last_query();
		
		$response = array();
		
		if($this->db->_error_number() == 0) {
			$response["status"] 		= "success";
			$response["contractors"] 	= $query->result();
		} else {
			$response["status"] 		= "error";
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}
		
		return $response;
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

			$data = array(
				'is_deleted' => 1
			);
			
			if($this->db->update('contractor', $data)) {
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