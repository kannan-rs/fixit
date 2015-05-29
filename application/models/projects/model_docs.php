<?php
class Model_docs extends CI_Model {
	public function get_docs_list($projectId = "", $startRecord = 0, $count= 10) {
		if(isset($projectId) && !is_null($projectId) && $projectId != "") {
			$this->db->where('project_id', $projectId);	
		} else {
			return [];
		}

		//$this->db->limit($count, $startRecord);

		$this->db->select([
				"*", 
				"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_date_for_view", 
				"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_date_for_view",
				"created_on",
				"updated_on"
			]);
		$this->db->order_by("created_on", "asc");

		$query = $this->db->from('project_docs')->get();
		
		$project_docs = $query->result();

		return $project_docs;	
	}

	public function get_doc_by_id($docId = "") {
		if(isset($docId) && !is_null($docId) && $docId != "") {
			$this->db->where('doc_id', $docId);	
		} else {
			return [];
		}

		$this->db->select(["*"]);
		
		$query = $this->db->from('project_docs')->get();
		
		$project_docs = $query->result();

		return $project_docs;	
	}

	public function insert($data) {
		$response = array();

		if($this->db->insert('project_docs', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']		= $this->db->insert_id();
			$response['message']		= "Attachment Inserted Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	public function delete($record) {
		$response = array(
			'status' => 'error'
		);
		if($record && $record!= "") {
			$this->db->where('doc_id', $record);
			
			if($this->db->delete('project_docs')) {
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