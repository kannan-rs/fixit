<?php

class Model_tasks extends CI_Model {
	public function getTasksList($parentId = "") {
		$whereStr = "";
		if(isset($parentId) && !is_null($parentId) && $parentId != "") {
			$this->db->where('project_id', $parentId);
			$whereStr .= " where project_id = ".$parentId;
		}

		$this->db->select([
				"*", 
				"DATE_FORMAT(created_on, \"%m/%d/%y %H:%i:%S\") as created_on_for_view", 
				"DATE_FORMAT( updated_on, \"%m/%d/%y %H:%i:%S\") as updated_on_for_view",
				"DATE_FORMAT( task_start_date, \"%m/%d/%y\") as task_start_date_for_view",
				"DATE_FORMAT( task_end_date, \"%m/%d/%y\") as task_end_date_for_view",
				"created_on",
				"updated_on",
				"task_start_date",
				"task_end_date"
			]);
		$query = $this->db->from('project_details')->get();

		$tasksResult 	= $query->result();

		// Count
		$countQueryStr 	= "SELECT COUNT(*) as count FROM `project_details`".$whereStr;
		$countQuery 	= $this->db->query($countQueryStr);
		$countResult	= $countQuery->result();

		$response = array();

		$response["count"] 			= $countResult;

		if($tasksResult) {
			$response["status"] 		= "success";
			$response["tasks"] 			= $tasksResult;
		} else {
			$response["status"] 		= "error";
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}
		
		return $response;
	}

	public function getTask($record = "") {
		if(isset($record) && !is_null($record) && $record != "") {
			$this->db->where('task_id', $record);	
		}

		$this->db->select([
			"*", 
			"DATE_FORMAT(created_on, \"%m/%d/%y %H:%i:%S\") as created_on_for_view", 
			"DATE_FORMAT( updated_on, \"%m/%d/%y  %H:%i:%S\") as updated_on_for_view",
			"DATE_FORMAT( task_start_date, \"%m/%d/%y\") as task_start_date_for_view",
			"DATE_FORMAT( task_end_date, \"%m/%d/%y\") as task_end_date_for_view",
			"created_on",
			"updated_on",
			"task_start_date",
			"task_end_date"
		]);
		$query = $this->db->from('project_details')->get();

		$tasks = $query->result();
		return $tasks;
	}

	public function insert($data) {
		$response = array(
			'status' => 'error'
		);
		if($this->db->insert('project_details', $data)) {
			$record = $this->db->insert_id();
			$response['status'] 	= "success";
			$response['insertedId'] = $record;
			$response['message']	= "Task Created Successfully";
		} else {
			$response['message']	= "Error while creating task<br/>".$this->db->_error_message();
		}
		return $response;
	}

	public function update($data, $record) {
		$response = array(
			'status' => 'error'
		);
		if($data && $record) {
			$this->db->where('task_id', $record);
		
			if($this->db->update('project_details', $data)) {
				$response['status']		= "success";
				$response['message']	= "Task Updated Successfully";
				$response['updatedId']	= $record;
			} else {
				$response["message"] = "Error while updating the task<br/>".$this->db->_error_message();
			}
		} else {
			$response['message'] = "Invalid Query";
		}
		return $response;
	}

	public function deleteRecord($task_id, $project_id) {
		$response = array(
			'status' => 'error'
		);
		if($task_id && $task_id!= "") {
			$this->db->where('task_id', $task_id);
			
			if($this->db->delete('project_details')) {
				$response['status']		= "success";
				$response['message']	= "Task Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the records";
			}
		} else {
			$response['message']	= 'Invalid Task, Please try again';
		}
		return $response;
	}
}