<?php
class Model_docs extends CI_Model {
	public function getDocsList($claim_id = "", $docId = "") {
		$countWhereStr = " WHERE is_deleted = 0";

		$this->db->where('is_deleted', '0');

		if(isset($claim_id) && !is_null($claim_id) && $claim_id != "") {
			$this->db->where('claim_id', $claim_id);
			$countWhereStr .= "  AND claim_id = ".$claim_id;
		}

		if(isset($docId) && !is_null($docId) && $docId != "") {
			$this->db->where('doc_id', $docId);
			$countWhereStr .= "  AND doc_id = ".$docId;
		}

		$this->db->select([
				"*", 
				"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_date_for_view", 
				"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_date_for_view",
				"created_on",
				"updated_on"
			]);
		$this->db->order_by("created_on", "asc");
		$query = $this->db->from('claim_docs')->get();
		$docsResult = $query->result();

		// Count
		$countQueryStr 	= "SELECT COUNT(*) as count FROM `claim_docs`".$countWhereStr;
		$countQuery 	= $this->db->query($countQueryStr);
		$countResult	= $countQuery->result();

		//return $claim_docs;	
		$response = array();
		
		$response["count"] 			= $countResult;
		if($docsResult) {
			$response["status"] 		= "success";
			$response["docs"] 			= $docsResult;
		} else {
			$response["status"] 		= "error";
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}
		
		return $response;
	}

	public function getDocById($docId = "") {
		$this->db->where('is_deleted', '0');

		if(isset($docId) && !is_null($docId) && $docId != "") {
			$this->db->where('doc_id', $docId);	
		} else {
			return [];
		}

		$this->db->select(["*"]);
		
		$query = $this->db->from('claim_docs')->get();
		
		$claim_docs = $query->result();

		return $claim_docs;
	}

	public function insert($data) {
		$response = array();

		if($this->db->insert('claim_docs', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']		= $this->db->insert_id();
			$response['message']		= "Attachment Inserted Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	public function deleteRecord($record) {
		$response = array(
			'status' => 'error'
		);
		if($record && $record!= "") {
			$this->db->where('doc_id', $record);
			
			$data = array(
				'is_deleted' 				=> 1
			);
			
			if($this->db->update('claim_docs', $data)) {
				$response['status']		= "success";
				$response['message']	= "Document Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the Document";
			}
		} else {
			$response['message']	= 'Invalid Document, Please try again';
		}
		return $response;
	}
}