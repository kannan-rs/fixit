<?php

class Model_issues extends CI_Model {
	
	public function getIssuesList( $options) {
		
		if(!isset($options) && !is_array($options)) {
			return;
		}

		$record 	= isset($options["records"]) ? $options["records"] : "";
		$projectId 	= isset($options["projectId"]) ? $options["projectId"] : "";
		$taskId 	= isset($options["taskId"]) ? $options["taskId"] : "";
		$status 	= isset($options["status"]) ? $options["status"] : "";

		if(isset($record) && !is_null($record) && $record != "") {
			if(is_array($record)) {
				$this->db->where_in('issue_id', $record);
			} else if($record != "") {
				$this->db->where('issue_id', $record);
			}
		}

		if(isset($projectId) && !is_null($projectId) && $projectId != "") {
			if(is_array($projectId)) {
				$this->db->where_in('project_id', $projectId);
			} else if($projectId != "") {
				$this->db->where('project_id', $projectId);
			}
		}

		if(isset($taskId) && !is_null($taskId) && $taskId != "") {
			if(is_array($taskId)) {
				$this->db->where_in('task_id', $taskId);
			} else if($taskId != "") {
				$this->db->where('task_id', $taskId);
			}
		}

		if($status == "open") {
			$this->db->where_in("status", array("open", "inProgress"));
		} else if($status != "all") {
			$this->db->where_in("status", explode(',', $status));
		}

		$this->db->where("is_deleted", 0);

		$this->db->select([
				"issue_id",
				"issue_name",
				"issue_desc",
				"project_id",
				"task_id",
				"assigned_to_user_type",
				"assigned_to_user_id",
				"DATE_FORMAT(issue_from_date, \"%m/%d/%Y\") as issue_from_date",
				"DATE_FORMAT(issue_from_date, \"%d/%m/%y\") as issue_from_date_for_view",
				"assigned_date",
				"DATE_FORMAT(assigned_date, \"%m/%d/%Y\") as assigned_date_for_view",
				"status",
				"notes",
				"is_deleted", 
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
			$response["issues"] 		= $query->result();
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
			$response['message']		= "Issue Inserted Successfully";
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
			$this->db->where('issue_id', $record);
		
			if($this->db->update('issue', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'Issue updated Successfully';
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
				'is_deleted' 				=> 1
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