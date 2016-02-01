<?php

class Model_remainingbudget extends CI_Model {
	
	public function getList($projectId = "") {
		if(isset($projectId) && !is_null($projectId) && $projectId != "") {			
			$this->db->where('project_id', $projectId);
		}
		$this->db->where('is_deleted', "0");

		$this->db->select([
				"*", 
				"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
				"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
				"created_on",
				"updated_on"
		]);

		$query = $this->db->from('paid_from_budget')->get();

		
		$response = array();
		
		if($this->db->_error_number() == 0) {
			$response["status"] 			= "success";
			$response["paidFromBudget"] 	= $query->result();
		} else {
			$response["status"] 		= "error";
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}
		
		return $response;
	}

	public function getBudgetById($budgetId = "") {
		if(isset($budgetId) && !is_null($budgetId) && $budgetId != "") {			
			$this->db->where('sno', $budgetId);
		}
		$this->db->where('is_deleted', "0");

		$this->db->select([
				"*", 
				"DATE_FORMAT(created_on, \"%m/%d/%Y %H:%i:%S\") as created_on_for_view", 
				"DATE_FORMAT( updated_on, \"%m/%d/%Y %H:%i:%S\") as updated_on_for_view",
				"DATE_FORMAT( date, \"%m/%d/%Y\") as date",
				"created_on",
				"updated_on"
		]);

		$query = $this->db->from('paid_from_budget')->get();

		
		$response = array();
		
		if($this->db->_error_number() == 0) {
			$response["status"] 			= "success";
			$response["paidFromBudget"] 	= $query->result();
		} else {
			$response["status"] 		= "error";
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}
		
		return $response;
	}

	public function getPaidBudgetSum($projectId = "") {
		if(isset($projectId) && !is_null($projectId) && $projectId != "") {			
			$this->db->where('project_id', $projectId);
		}
		$this->db->where('is_deleted', "0");

		$this->db->select([
				 "SUM(amount) as amount"
		]);
		
		$query = $this->db->from('paid_from_budget')->get();
		
		return $query->result()[0]->amount;

	}

	public function insert($data) {
		$response = array();
		if($this->db->insert('paid_from_budget', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "Paid From Budget Inserted Successfully";
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
			$this->db->where('sno', $record);
		
			if($this->db->update('paid_from_budget', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'Paid From Budget updated Successfully';
				$response['updatedId']	= $record;
			} else {
				$response["message"] = "Error while updating the Paid From Budget";
			}	
		} else {
			$response['message']	= 'Invalid Paid From Budget, Please try again';
		}
		return $response;
	}

	public function deleteRecord($record) {
		$response = array(
			'status' => 'error'
		);
		
		if($record && $record!= "") {
			$this->db->where('sno', $record);

			$data = array(
				'is_deleted' => 1
			);
			
			if($this->db->update('paid_from_budget', $data)) {
				$response['status']		= "success";
				$response['message']	= "Paid From Budget Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the Paid From Budget";
			}
		} else {
			$response['message']	= 'Invalid Paid From Budget, Please try again';
		}
		return $response;
	}
}