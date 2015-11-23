<?php

class Model_notes extends CI_Model {
	public function getNotesList($projectId = "", $taskId = 0, $noteId, $startRecord = 0, $count) {
		$this->db->where('deleted', '0');

		$countWhereStr = " WHERE deleted = 0";

		if(isset($projectId) && !is_null($projectId) && $projectId != "") {
			$this->db->where('project_id', $projectId);
			$countWhereStr .= " AND project_id = ".$projectId;
		}

		if(isset($taskId) && !is_null($taskId) && $taskId != "") {
			$this->db->where('task_id', $taskId);
			$countWhereStr .= (count($countWhereStr) ? " AND " : " where")." task_id = ".$taskId;
		}

		if(isset($noteId) && !is_null($noteId) && $noteId != "" && $noteId != 0) {
			$this->db->where('notes_id', $noteId);
			$countWhereStr .= (count($countWhereStr) ? " AND " : " where")." notes_id = ".$noteId;
		}

		$this->db->select([
				"*", 
				"DATE_FORMAT(created_date, \"%d-%m-%y %H:%i:%S\") as created_date_for_view", 
				"DATE_FORMAT( updated_date, \"%d-%m-%y %H:%i:%S\") as updated_date_for_view",
				"created_date",
				"updated_date"
			]);
		$this->db->order_by("created_date", "desc");
		$query = $this->db->from('project_notes')->get();
		$notesResult = $query->result();

		// Count
		$countQueryStr 	= "SELECT COUNT(*) as count FROM `project_notes`".$countWhereStr;
		$countQuery 	= $this->db->query($countQueryStr);
		$countResult	= $countQuery->result();

		$response = array();
		
		$response["count"] 			= $countResult;
		if($this->db->_error_number() == 0) {
			$response["status"] 		= "success";
			$response["notes"] 			= $notesResult;
		} else {
			$response["status"] 		= "error";
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}
		
		return $response;
	}

	public function insert($data) {
		$response = array();
		if($this->db->insert('project_notes', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']		= $this->db->insert_id();
			$response['message']		= "Note Inserted Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	public function count( $project_id = 0, $task_id = 0) {
		if($project_id > 0) {
			$this->db->where('project_id', $project_id);
			if($task_id > 0) {
				$this->db->where('task_id', $task_id);
			} else {
				$this->db->where('task_id', 0);
			}
			$this->db->select(["count(*) as count"]);

			$query = $this->db->from('project_notes')->get();

			$notes_count = $query->result();

			if(count($notes_count)) {
				return $notes_count[0]->count;
			}
		} else {
			return 0;
		}
	}

	public function deleteRecord($noteId) {
		$response = array(
			'status' => 'error'
		);
		if($noteId && $noteId != "") {
			$this->db->where('notes_id', $noteId);

			$data = array(
				'deleted' 				=> 1
			);
			
			if($this->db->update('project_notes', $data)) {
				$response['status']		= "success";
				$response['message']	= "Note Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the records";
			}
		} else {
			$response['message']	= 'Invalid Note, Please try again';
		}
		return $response;
	}
}