<?php

class Model_projects extends CI_Model {
	
	public function getProjectsList( $projectParams ) {
		if($this->session->userdata('account_type') != "admin") {
			$this->db->where('deleted', '0');
			//$projectIds = implode(",", $projectParams["projectId"]);
			//$this->db->where_in("proj_id", $projectIds);
			$this->db->where_in("proj_id", $projectParams["projectId"]);
		}
		
		/*if(isset($record) && !is_null($record) && $record != "") {
			$this->db->where('proj_id', $record);	
		}*/
		/*
		if($this->session->userdata("account_type") != "admin") {
			$this->db->where('owner', $this->session->userdata('user_id'));
		}
		*/

		$this->db->select([
				"*",
				"DATE_FORMAT(start_date, \"%m/%d/%y\") as start_date",
				"DATE_FORMAT(end_date, \"%m/%d/%y\") as end_date",  
				"DATE_FORMAT(created_on, \"%m-%d-%y %H:%i:%S\") as created_on_for_view", 
				"DATE_FORMAT( updated_on, \"%m-%d-%y %H:%i:%S\") as updated_on_for_view",
				"created_on",
				"updated_on"
			]);
		$query = $this->db->from('project')->get();
		
		$projects = $query->result();

		//print_r($this->db);
		return $projects;
	}

	public function getProjectIds( $projectParams ) {
		if($this->session->userdata('account_type') != "admin") {
			//$this->db->where('deleted', '0');
		} else {
			return "";
		}

		if( $projectParams["account_type"] == "user" ) {
			$this->db->where('customer_id', $projectParams["user_details_id"]);
		} else if( $projectParams["account_type"] == "contractor" ) {
			$this->db->or_where('contractor_id', $projectParams["user_details_id"]);
			$this->db->or_like('contractor_id', $projectParams["user_details_id"].",", "right");
			$this->db->or_like('contractor_id', ",".$projectParams["user_details_id"], "left");
			$this->db->or_like('contractor_id', ",".$projectParams["user_details_id"].",");
			$this->db->or_like('created_by', $projectParams["user_id"]);
		} else if( $projectParams["account_type"] == "adjuster" ) {
			$this->db->or_where('adjuster_id', $projectParams["user_details_id"]);
			$this->db->or_like('adjuster_id', $projectParams["user_details_id"].",", "right");
			$this->db->or_like('adjuster_id', ",".$projectParams["user_details_id"], "left");
			$this->db->or_like('adjuster_id', ",".$projectParams["user_details_id"].",");
		}
		$this->db->or_where('created_by', $projectParams["user_id"]);

		$this->db->select([
				'proj_id'
			]);
		$query = $this->db->from('project')->get();

		//print_r($this->db);
		
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

			$data = array(
				'deleted' 				=> 1
			);
			
			if($this->db->update('project', $data)) {
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