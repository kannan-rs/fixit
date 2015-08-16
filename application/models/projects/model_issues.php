<?php

class Model_issues extends CI_Model {
	
	public function getIssuesList($record = "", $projectId = "", $serviceZip = "") {
		
		if(isset($record) && !is_null($record) && $record != "") {
			if(is_array($record)) {
				for($i = 0; $i < count($record); $i++) {
					if($record[$i]) $this->db->or_where('issue_id', $record[$i]);	
				}
			} else if($record != "") {
				$this->db->where('issue_id', $record);
			}
		}

		if(isset($projectId) && !is_null($projectId) && $projectId != "") {
			if(is_array($projectId)) {
				for($i = 0; $i < count($projectId); $i++) {
					if($projectId[$i]) $this->db->or_where('project_id', $projectId[$i]);	
				}
			} else if($projectId != "") {
				$this->db->where('project_id', $projectId);
			}
		}

		$this->db->select([
				"issue_id",
				"issue_name",
				"issue_desc",
				"project_id",
				"task_id",
				"assigned_to_user_type",
				"assigned_to_user_id",
				"DATE_FORMAT(issue_from_date, \"%m/%d/%y\") as issue_from_date",
				"DATE_FORMAT(issue_from_date, \"%d-%m-%y\") as issue_from_date_for_view",
				"assigned_date",
				"DATE_FORMAT(assigned_date, \"%d-%m-%y\") as assigned_date_for_view",
				"status",
				"notes",
				"deleted", 
				"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
				"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
				"created_on",
				"updated_on",
				"created_by",
				"updated_by"
			]);

		$query = $this->db->from('issue')->get();
		
		$response = array();
		
		if($this->db->_error_number() == 0) {
			$response["status"] 		= "success";
			$response["issues"] 	= $query->result();
		} else {
			$response["status"] 		= "error";
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}
		
		return $response;
	}

	public function insert($data) {
		$response = array();
		if($this->db->insert('issue', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "issue Inserted Successfully";
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
		
			if($this->db->update('issue', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'issue updated Successfully';
				$response['updatedId']	= $record;
			} else {
				$response["message"] = "Error while updating the records";
			}	
		} else {
			$response['message']	= 'Invalid issue, Please try again';
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
				'deleted' 				=> 1
			);
			
			if($this->db->update('issue', $data)) {
				$response['status']		= "success";
				$response['message']	= "issue Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the issue";
			}
		} else {
			$response['message']	= 'Invalid issue, Please try again';
		}
		return $response;
	}
}