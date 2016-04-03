<?php

class Model_ins_comp extends CI_Model {
	
	public function get_ins_comp_id_by_user_id($user_id = "") {
		if(isset($user_id) && !empty($user_id)) {
			$this->db->where('is_deleted', '0');
			$this->db->where('default_contact_user_id', $user_id);

			$this->db->select([
				"ins_comp_id"
			]);
			$query = $this->db->from('insurance_company')->get();
			$ins_comps = $query->result();

			if(count($ins_comps)) {
				return $ins_comps[0]->ins_comp_id;
			}
		}
		return false;
	}

	public function get_ins_comp_list($record = "", $compName = "") {
		$this->db->where('is_deleted', '0');
		
		if(isset($record) && !is_null($record)) {
			//echo "record =>"; print_r(array_filter($record)); echo "--count =>".count($record)."--imp--".implode(",", $record)."<br/>";
			if(is_array($record) && implode(",", $record) != "" ) {
				$this->db->where_in('ins_comp_id', $record);
			} else if($record != "") {
				$this->db->where('ins_comp_id', $record);
			}
		}

		if(isset($companyNmae) && !is_null($companyNmae) && $companyNmae != "") {
			if(is_array($companyNmae)) {
				for($i = 0; $i < count($companyNmae); $i++) {
					if(!empty($companyNmae[$i])) {
						$this->db->or_like('ins_comp_name', $companyNmae[$i]);	
					}
				}
			} else if(!empty($companyNmae)) {
				$this->db->or_like('ins_comp_name', $companyNmae);
			}
		}

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN) {
			if($this->session->userdata('logged_in_role_disp_name') == ROLE_INSURANCECO_ADMIN) {
				$this->db->where('default_contact_user_id', $this->session->userdata('logged_in_user_id'));
			}
		}

		$this->db->select([
			"*", 
			"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
			"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
			"created_on",
			"updated_on"
		]);

		$query = $this->db->from('insurance_company')->get();

		//echo $this->db->last_query();
		
		$response = array();
		
		if($this->db->_error_number() == 0) {
			$response["status"] 		= "success";
			$response["ins_comps"] 	= $query->result();
		} else {
			$response["status"] 		= "error";
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}
		
		return $response;
	}

	public function insert($data) {
		$response = array();
		if($this->db->insert('insurance_company', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "Insurance Company Inserted Successfully";
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
			$this->db->where('ins_comp_id', $record);
		
			if($this->db->update('insurance_company', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'Insurance Company updated Successfully';
				$response['updatedId']	= $record;
			} else {
				$response["message"] = "Error while updating the records";
			}	
		} else {
			$response['message']	= 'Invalid Insurance Company, Please try again';
		}
		return $response;
	}

	public function deleteRecord($record) {
		$response = array(
			'status' => 'error'
		);
		if($record && $record!= "") {
			$this->db->where('ins_comp_id', $record);

			$data = array(
				'is_deleted' => 1
			);
			
			if($this->db->update('insurance_company', $data)) {
				$response['status']		= "success";
				$response['message']	= "Insurance Company Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the Insurance Company";
			}
		} else {
			$response['message']	= 'Invalid Insurance Company, Please try again';
		}
		return $response;
	}
}