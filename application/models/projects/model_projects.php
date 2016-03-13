<?php

class Model_projects extends CI_Model {
	
	public function getProjectsList( $projectParams ) {
		//print_r($projectParams);
		if($projectParams['role_disp_name'] != ROLE_ADMIN ) {
			$this->db->where('is_deleted', '0');
		}

		if(isset($projectParams['projectId']) && count($projectParams['projectId'])) {
			$this->db->where_in("proj_id", $projectParams["projectId"]);
		}

		$this->db->select([
				"*",
				"DATE_FORMAT(start_date, \"%m/%d/%Y\") as start_date",
				"DATE_FORMAT(end_date, \"%m/%d/%Y\") as end_date",  
				"DATE_FORMAT(created_on, \"%m-%d-%y %H:%i:%S\") as created_on_for_view", 
				"DATE_FORMAT( updated_on, \"%m-%d-%y %H:%i:%S\") as updated_on_for_view",
				"created_on",
				"updated_on"
			]);
		$query = $this->db->from('project')->get();
		
		$projects = $query->result();

		return $projects;
	}

	public function getProjectIds( $projectParams ) {
		$query  			= "SELECT `proj_id` FROM `project` WHERE ";
		$inner_or_query 	= "";
		$outer_and_query 	= "";

		if($projectParams['role_disp_name'] != ROLE_ADMIN ) {
			$outer_and_query .= "`is_deleted` = 0 ";
		}

		if( !in_array('all', $projectParams["projectPermission"]["data_filter"]) ) {
			// Data filter == "SELF" means project assigned to logged in User
			/*if( in_array('self', $projectParams["projectPermission"]["data_filter"]) ) {
				$inner_or_query .= "`customer_id` = '".$projectParams["user_details_id"]."'";
			}*/

			/* 
				Data filter == "SERVICE PROVIDER"
				Logged in user role need to be "SERVICE PROVIDER ADMIN", or "SERVICE PROVIDER USER"
				Project assigned to service provider company (Contractor) company, and logged in user belongs to that service provider company
			*/
			//else if( $projectParams['role_disp_name'] == ROLE_SERVICE_PROVIDER_ADMIN ) {
			if( $projectParams['role_disp_name'] == ROLE_SERVICE_PROVIDER_ADMIN || $projectParams['role_disp_name'] == ROLE_SUB_ADMIN ) {
				if(in_array('assigned projects', $projectParams["projectPermission"]["data_filter"]) || in_array('service provider', $projectParams["projectPermission"]["data_filter"]) ) {
					$this->load->model("service_providers/model_service_providers");
					
					$contractor_id = $this->model_service_providers->get_contractor_company_id_by_user_id($projectParams["user_id"]);
					
					$self_df_query = "";
					$contractor_id_like_query = "";
					
					if( in_array('self', $projectParams["projectPermission"]["data_filter"]) ) {
						$self_df_query = "`customer_id` = '".$projectParams["user_details_id"]."'";
					}

					if( !empty($contractor_id) ) {
						$contractor_id_like_query .= "`contractor_id` = '".$contractor_id."' OR `contractor_id` LIKE '%".$contractor_id.",%' OR `contractor_id` LIKE '%,".$contractor_id."%' OR `contractor_id` LIKE '%,".$contractor_id.",%'";	
					}

					$created_by_query =  "`created_by` = '".$projectParams["user_id"]."'";

					$or_query = $self_df_query;
					$or_query .= !empty($or_query) ? " OR " : "";
					$or_query .= $created_by_query;

					if(!empty($contractor_id_like_query)) {
						$or_query .= !empty($or_query) ? " OR " : "";
						$or_query .= $contractor_id_like_query;
					}

					$or_query = "(".$or_query.")";

					$outer_and_query .= " AND (".$or_query.")";
				}
			}
			/* 
				Data filter == ""
				Logged in user role need to be "SERVICE PROVIDER ADMIN", or "SERVICE PROVIDER USER"
				Project assigned to service provider company (Contractor) company, and logged in user belongs to that service provider company
			*/
			if( $projectParams['role_disp_name'] == ROLE_INSURANCECO_ADMIN || $projectParams['role_disp_name'] == ROLE_SUB_ADMIN ) {
				/*$this->db->or_where('adjuster_id', $projectParams["user_details_id"]);
				$this->db->or_like('adjuster_id', $projectParams["user_details_id"].",", "right");
				$this->db->or_like('adjuster_id', ",".$projectParams["user_details_id"], "left");
				$this->db->or_like('adjuster_id', ",".$projectParams["user_details_id"].",");*/
				
				$self_df_query = "";
				if( in_array('self', $projectParams["projectPermission"]["data_filter"]) ) {
					$self_df_query = "`customer_id` = '".$projectParams["user_details_id"]."'";
				}

				$created_by_query =  "`created_by` = '".$projectParams["user_id"]."'";

				$or_query = $self_df_query;
				$or_query .= !empty($or_query) ? " OR " : "";
				$or_query .= $created_by_query;

				/*if(!empty($contractor_id_like_query)) {
					$or_query .= !empty($or_query) ? " OR " : "";
					$or_query .= $contractor_id_like_query;
				}*/

				$or_query = "(".$or_query.")";

				$outer_and_query .= " AND (".$or_query.")";
			}

			if( $projectParams['role_disp_name'] == ROLE_SERVICE_PROVIDER_USER || $projectParams['role_disp_name'] == ROLE_SUB_ADMIN ) {
				$self_df_query = "";

				if( in_array('self', $projectParams["projectPermission"]["data_filter"]) ) {
					$self_df_query = "`customer_id` = '".$projectParams["user_details_id"]."'";
				}

				$created_by_query =  "`created_by` = '".$projectParams["user_id"]."'";

				$or_query = $self_df_query;
				$or_query .= !empty($or_query) ? " OR " : "";
				$or_query .= $created_by_query;

				/*if(!empty($contractor_id_like_query)) {
					$or_query .= !empty($or_query) ? " OR " : "";
					$or_query .= $contractor_id_like_query;
				}*/

				$or_query = "(".$or_query.")";

				$outer_and_query .= " AND (".$or_query.")";
			}

			if( $projectParams['role_disp_name'] == ROLE_INSURANCECO_CALL_CENTER_AGENT || $projectParams['role_disp_name'] == ROLE_SUB_ADMIN ) {
				$self_df_query = "";
				if( in_array('self', $projectParams["projectPermission"]["data_filter"]) ) {
					$self_df_query = "`customer_id` = '".$projectParams["user_details_id"]."'";
				}

				$created_by_query =  "`created_by` = '".$projectParams["user_id"]."'";

				$or_query = $self_df_query;
				$or_query .= !empty($or_query) ? " OR " : "";
				$or_query .= $created_by_query;

				/*if(!empty($contractor_id_like_query)) {
					$or_query .= !empty($or_query) ? " OR " : "";
					$or_query .= $contractor_id_like_query;
				}*/

				$or_query = "(".$or_query.")";

				$outer_and_query .= " AND (".$or_query.")";

			}

			if( $projectParams['role_disp_name'] == ROLE_CUSTOMER || $projectParams['role_disp_name'] == ROLE_SUB_ADMIN ) {
				$self_df_query = "";
				if( in_array('self', $projectParams["projectPermission"]["data_filter"]) ) {
					$self_df_query = "`customer_id` = '".$projectParams["user_details_id"]."'";
				}

				$created_by_query =  "`created_by` = '".$projectParams["user_id"]."'";

				$or_query = $self_df_query;
				$or_query .= !empty($or_query) ? " OR " : "";
				$or_query .= $created_by_query;

				/*if(!empty($contractor_id_like_query)) {
					$or_query .= !empty($or_query) ? " OR " : "";
					$or_query .= $contractor_id_like_query;
				}*/

				$or_query = "(".$or_query.")";

				$outer_and_query .= " AND (".$or_query.")";

			}

			if( $projectParams['role_disp_name'] == ROLE_PARTNER_ADMIN || $projectParams['role_disp_name'] == ROLE_SUB_ADMIN ) {
				$self_df_query = "";
				if( in_array('self', $projectParams["projectPermission"]["data_filter"]) ) {
					$self_df_query = "`customer_id` = '".$projectParams["user_details_id"]."'";
				}

				$created_by_query =  "`created_by` = '".$projectParams["user_id"]."'";

				$or_query = $self_df_query;
				$or_query .= !empty($or_query) ? " OR " : "";
				$or_query .= $created_by_query;

				/*if(!empty($contractor_id_like_query)) {
					$or_query .= !empty($or_query) ? " OR " : "";
					$or_query .= $contractor_id_like_query;
				}*/

				$or_query = "(".$or_query.")";

				$outer_and_query .= " AND (".$or_query.")";

			}
		}

		$query = $query.$outer_and_query;

		$query 		= $this->db->query($query);
		$projects 	= $query->result();

		//print_r($this->db->last_query());
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
				'is_deleted' => 1
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