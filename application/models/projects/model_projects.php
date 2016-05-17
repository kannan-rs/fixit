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
					$self_df_query = "`customer_id` = '".$projectParams["user_id"]."'";
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
				/*$self_df_query = "";
				if( in_array('self', $projectParams["projectPermission"]["data_filter"]) ) {
					$self_df_query = "`customer_id` = '".$projectParams["user_details_id"]."'";
				}

				$created_by_query =  "`created_by` = '".$projectParams["user_id"]."'";

				$or_query = $self_df_query;
				$or_query .= !empty($or_query) ? " OR " : "";
				$or_query .= $created_by_query;

				$or_query = "(".$or_query.")";

				$outer_and_query .= " AND (".$or_query.")";*/
				if(in_array('assigned projects', $projectParams["projectPermission"]["data_filter"])) {
					$this->load->model("adjusters/model_partners");
					
					$partner_id = $this->model_partners->get_partner_company_id_by_user_id($projectParams["user_id"]);

					$self_df_query = "";
					$partner_id_like_query = "";
					
					if( in_array('self', $projectParams["projectPermission"]["data_filter"]) ) {
						$self_df_query = "`customer_id` = '".$projectParams["user_details_id"]."'";
					}

					if( !empty($partner_id) ) {
						$partner_id_like_query .= "`adjuster_id` = '".$partner_id."' OR `adjuster_id` LIKE '%".$partner_id.",%' OR `adjuster_id` LIKE '%,".$partner_id."%' OR `adjuster_id` LIKE '%,".$partner_id.",%'";	
					}

					$created_by_query =  "`created_by` = '".$projectParams["user_id"]."'";

					$or_query = $self_df_query;
					$or_query .= !empty($or_query) ? " OR " : "";
					$or_query .= $created_by_query;

					if(!empty($partner_id_like_query)) {
						$or_query .= !empty($or_query) ? " OR " : "";
						$or_query .= $partner_id_like_query;
					}

					$or_query = "(".$or_query.")";

					$outer_and_query .= " AND (".$or_query.")";
				}

			}
		}

		$query = $query.$outer_and_query;

		$query 		= $this->db->query($query);
		$projects 	= $query->result();

		//print_r($this->db->last_query());
		return $projects;
	}

	public function get_project_ids_by_sp_user( $params ) {
		if( $params['role_disp_name'] == ROLE_SERVICE_PROVIDER_USER) {
			$query = "select project_id from `project_owners` WHERE is_deleted = 0 && user_id ='".$params['user_id']."' && role_id = '".$params['role_id']."'";
			$query 		= $this->db->query($query);
			$projects 	= $query->result();
			return $projects;
		}
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

	public function insert_project_owner( $data ) {
		$response = array();
		if($this->db->insert('project_owners', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "Contractor User Inserted/Updated Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	public function update_project_owner( $params )
	{
		$response = array(
			'status' => 'error'
		);

		$data 				= $params["data"];
		$project_id 		= $params["project_id"];
		$parent_company_id 	= $params["parent_company_id"];

		if(!empty($data) && !empty($project_id)) {
			$this->db->where('project_id', $project_id);
			
			if( is_array( $parent_company_id ) ) {
				$this->db->where_in('parent_company_id', $parent_company_id);	
			} else {
				$this->db->where('parent_company_id', $parent_company_id);
			}

			if($this->db->update('project_owners', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'Contractor Users deleted Successfully';
			} else {
				$response["message"] = "Error while updating the records";
			}	
		} else {
			$response['message']	= 'Invalid Project, Please try again';
		}
		return $response;
	}

	public function checking_existing_project_owner( $params ) {
		$response = array(
			'status' => 'error'
		);

		$user_id 			= $params["user_id"];
		$project_id 		= $params["project_id"];
		$parent_company_id 	= $params["parent_company_id"];

		$this->db->where('user_id', $user_id);
		$this->db->where('project_id', $project_id);
		$this->db->where('parent_company_id', $parent_company_id);
		$this->db->where('is_deleted', "0");

		$query = $this->db->get('project_owners');

		return $query->num_rows();

	}

	/*
		List Service provider owner for a given project
	*/
	public function get_existing_project_owner( $params ) {
		$response = array(
			'status' => 'error'
		);

		$project_id 		= $params["project_id"];
		$parent_company_id 	= $params["parent_company_id"];

		if( !empty($project_id) ) {
			$this->db->where('project_id', $project_id);
			
			if( !empty($parent_company_id) ) {
				$this->db->where_in('parent_company_id', $parent_company_id);
			}
			
			$this->db->where('is_deleted', "0");

			$query = $this->db->get('project_owners');

			//print_r( $this->db->last_query());

			$owner_list = array();
			if($query->num_rows() >= 1) {
				if($owner_list_db = $query->result()) {
					for($i = 0; $i < count($owner_list_db); $i++) {
						array_push($owner_list, $owner_list_db[$i]->user_id);	
					}
					$response["status"] = "success";
					$response["owner_list"]  = $owner_list;
				}
			} 
			else {
				$response["message"] = "No Service Provider User Assigned";
			}
		}
		else {
			$response["message"] = "Invalid paramaters";
		}

		return $response;
	}
}