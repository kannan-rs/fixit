<?php

class Model_projects extends CI_Model {
	
	public function getProjectsList($record = "") {
		if(isset($record) && !is_null($record) && $record != "") {
			$this->db->where('proj_id', $record);	
		}
		/*
		if($this->session->userdata("account_type") != "admin") {
			$this->db->where('owner', $this->session->userdata('user_id'));
		}
		*/

		$this->db->select([
				"*", 
				"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
				"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
				"created_on",
				"updated_on"
			]);
		$query = $this->db->from('project')->get();
		
		$projects = $query->result();
		return $projects;
	}

	public function insert($data) {
		$response = array();
		if($this->db->insert('project', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "Project Inserted Successfully";
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
			$this->db->where('proj_id', $record);
		
			if($this->db->update('project', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'Project updated Successfully';
				$response['updatedId']	= $record;
			} else {
				$response["message"] = "Error while updating the records";
			}	
		} else {
			$response['message']	= 'Invalid Project, Please try again';
		}
		return $response;
	}

	public function deleteRecord($record) {
		$response = array(
			'status' => 'error'
		);
		if($record && $record!= "") {
			$this->db->where('proj_id', $record);
			
			if($this->db->delete('project')) {
				$response['status']		= "success";
				$response['message']	= "Project Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the records";
			}
		} else {
			$response['message']	= 'Invalid Project, Please try again';
		}
		return $response;
	}
}