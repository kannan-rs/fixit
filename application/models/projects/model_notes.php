<?php

class Model_notes extends CI_Model {
	public function get_notes_list($projectId = "", $startRecord = 0, $count= 5) {
		if(isset($projectId) && !is_null($projectId) && $projectId != "") {
			$this->db->where('project_id', $projectId);	
		} else {
			return [];
		}

		$this->db->limit($count, $startRecord);

		$this->db->select([
				"*", 
				"DATE_FORMAT(created_date, \"%d-%m-%y %H:%i:%S\") as created_date_for_view", 
				"DATE_FORMAT( updated_date, \"%d-%m-%y %H:%i:%S\") as updated_date_for_view",
				"created_date",
				"updated_date"
			]);
		$this->db->order_by("created_date", "asc");

		$query = $this->db->from('project_notes')->get();
		
		$project_notes = $query->result();

		return $project_notes;	
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
}