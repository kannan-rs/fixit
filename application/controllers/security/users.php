<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct()
   {
        parent::__construct();
        $this->load->helper('url');
		$controller = $this->uri->segment(1);
		$page = $this->uri->segment(2);
		$module = $this->uri->segment(3) ? $this->uri->segment(3): "";
		$sub_module = $this->uri->segment(3) ? $this->uri->segment(3): "";
		$function = $this->uri->segment(4) ? $this->uri->segment(4): "";
		$record = $this->uri->segment(5) ? $this->uri->segment(5): "";
	}

	public function viewAll() {
		$this->load->model('security/model_users');
		$users = $this->model_users->getUsersList();

		$params = array(
			'function'=>"view",
			'record'=>"all",
			'users'=>$users
		);
		
		echo $this->load->view("security/users/viewAll", $params, true);
	}

	public function createForm() {
		$params = array(
			'userType' 		=> $this->session->userdata("account_type")
		);

		echo $this->load->view("security/users/createForm", $params, true);
	}

	public function add() {
		$this->load->model('security/model_users');

		$privilege				= ($this->input->post('privilege') == 1 ? 'admin':'user');
		$firstName 				= $this->input->post('firstName');
		$lastName 				= $this->input->post('lastName'); 
		$password 				= $this->input->post('password');
		$passwordHint 			= $this->input->post('passwordHint');
		$belongsTo 				= $this->input->post('belongsTo');
		$contractorId 			= $this->input->post("contractorId");
		$userStatus 			= $this->input->post('userStatus');
		$emailId 				= $this->input->post('emailId');
		$contactPhoneNumber 	= $this->input->post('contactPhoneNumber');
		$mobileNumber 			= $this->input->post('mobileNumber');
		$altNumber 				= $this->input->post('altNumber');
		$primaryContact			= $this->input->post('primaryContact');
		$prefContact			= $this->input->post('prefContact');
		$addressLine1 			= $this->input->post('addressLine1');
		$addressLine2 			= $this->input->post('addressLine2');
		$addressLine3 			= $this->input->post('addressLine3');
		$addressLine4 			= $this->input->post('addressLine4');
		$city 					= $this->input->post('city');
		$state 					= $this->input->post('state');
		$country 				= $this->input->post('country');
		$pinCode				= $this->input->post('pinCode');

		if(!$this->model_users->getUserSnoViaEmail($emailId)) {
			$createUser_data = array(
				'first_name' 			=> $firstName,
				'last_name' 			=> $lastName, 
				'belongs_to' 			=> $belongsTo,
				'contractorId' 			=> $contractorId,
				'status' 				=> $userStatus,
				'active_start_date' 	=> date("Y-m-d H:i:s"),
				'email' 				=> $emailId,
				'contact_ph1' 			=> $contactPhoneNumber,
				'contact_mobile' 		=> $mobileNumber,
				'contact_alt_mobile'	=> $altNumber,
				'primary_contact'		=> $primaryContact,
				'addr1' 				=> $addressLine1,
				'addr2' 				=> $addressLine2,
				'addr3' 				=> $addressLine3,
				'addr4' 				=> $addressLine4,
				'addr_city' 			=> $city,
				'addr_state' 			=> $state,
				'addr_country' 			=> $country,
				'addr_pin'				=> $pinCode,
				'contact_pref' 			=> $prefContact,
				'created_dt' 			=> date("Y-m-d H:i:s"),
				'last_updated_dt' 		=> date("Y-m-d H:i:s"),
				'created_by'			=> $this->session->userdata("user_id"),
				'updated_by'			=> $this->session->userdata("user_id")
			);

			$inserted = $this->model_users->insertUserDetails($createUser_data);
			if($inserted["status"] == "success") {
				$loginTableUser_data = array(
					'user_name' 			=> $emailId, 
					'password' 				=> md5($password),
					'password_hint' 		=> $passwordHint,
					'account_type' 			=> $privilege,
					'status' 				=> $userStatus,
					'created_by'			=> $this->session->userdata("user_id"),
					'updated_by'			=> $this->session->userdata("user_id"),
					'created_date'			=> date("Y-m-d H:i:s"),
					'updated_date'			=> date("Y-m-d H:i:s")
				);

				$inserted_login = $this->model_users->insertUsers($loginTableUser_data);
				
				if($inserted_login['status'] == "success") {
					$record 				= $this->db->insert_id();

					$response["status"] 	= "success";
					$response["message"] 	= "User Added Successfully";
					$response["emailId"] 	= $emailId;
					$response["insertedId"]	= $record;
				} else {
					$response["status"] 	= "error";
					$response["message"] 	= $inserted_login["message"];		
				}
			} else {
				$response["status"] 		= "error";
				$response["message"] 		= $inserted["message"];	
			}
		} else {
			$response["status"] 			= "error";
			$response["message"] 			= "Email ID already exist";
		}
		print_r(json_encode($response));
	}

	public function editForm() {
		$this->load->model('security/model_users');
		$this->load->model('projects/model_contractors');
		$this->load->model('utils/model_form_utils');

		$record = $this->input->post('userId') ? $this->input->post('userId') : $this->session->userdata("user_id");

		$users 			= $this->model_users->getUsersList($record);
		$user_details 	= $this->model_users->getUserDetailsByEmail($users[0]->user_name);

		$contractorName = "";
		if(!empty($user_details[0]->contractorId)) {
			$contractors = $this->model_contractors->getContractorsList($user_details[0]->contractorId);
			$contractorName = count($contractors) ? $contractors[0]->name." from ".$contractors[0]->company : "";
		}

		$params = array(
			'record' 			=> $record,
			'viewFrom' 			=> $this->input->post('userId') ? "security" : "personalDetails",
			'users' 			=> $users,
			'user_details' 		=> $user_details,
			'contractorName' 	=> $contractorName,
			'userType' 			=> $this->session->userdata("account_type")
		);
		
		echo $this->load->view("security/users/editForm", $params, true);
	}

	public function update() {
		$response = array();

		$this->load->model('security/model_users');

		$active_start_date 		= $this->input->post("activeStartDate");
		$active_end_date 		= $this->input->post("activeEndDate");
		$user_record 			= $this->input->post('userId');
		$privilege 				= $this->input->post('privilege');
		$user_details_record 	= $this->input->post('sno'); 
		$firstName 				= $this->input->post('firstName');
		$lastName 				= $this->input->post('lastName'); 
		$belongsTo 				= $this->input->post('belongsTo');
		$contractorId 			= $this->input->post("contractorId");
		$userStatus 			= $this->input->post('userStatus');
		$contactPhoneNumber 	= $this->input->post('contactPhoneNumber');
		$mobileNumber 			= $this->input->post('mobileNumber');
		$altNumber 				= $this->input->post('altNumber');
		$primaryContact			= $this->input->post('primaryContact');
		$prefContact			= $this->input->post('prefContact');
		$addressLine1 			= $this->input->post('addressLine1');
		$addressLine2 			= $this->input->post('addressLine2');
		$addressLine3 			= $this->input->post('addressLine3');
		$addressLine4 			= $this->input->post('addressLine4');
		$city 					= $this->input->post('city');
		$state 					= $this->input->post('state');
		$country 				= $this->input->post('country');
		$pinCode 				= $this->input->post('pinCode');

		$update_details_data = array(
			'first_name' 			=> $firstName,
			'last_name' 			=> $lastName, 
			'belongs_to' 			=> $belongsTo,
			'status' 				=> $userStatus,
			'contact_ph1' 			=> $contactPhoneNumber,
			'contact_mobile' 		=> $mobileNumber,
			'contact_alt_mobile'	=> $altNumber,
			'primary_contact'		=> $primaryContact,
			'contact_pref'			=> $prefContact,
			'addr1' 				=> $addressLine1,
			'addr2' 				=> $addressLine2,
			'addr3' 				=> $addressLine3,
			'addr4' 				=> $addressLine4,
			'addr_city' 			=> $city,
			'addr_state' 			=> $state,
			'addr_country' 			=> $country,
			'addr_pin'				=> $pinCode,
			'last_updated_dt' 		=> date("Y-m-d H:i:s"),
			'updated_by'			=> $this->session->userdata('user_id')
		);

		if( $active_start_date != "" )
			$update_details_data["active_start_date"] = $active_start_date;

		if( $active_end_date != "" )
			$update_details_data["active_end_date"] = $active_end_date;

		if($contractorId != "") {
			$update_details_data["contractorId"] = $contractorId;
		}

		if($belongsTo != "contractor") {
			$update_details_data["contractorId"] = "";
		}

		$update_details = $this->model_users->updateDetailsTable($update_details_data, $user_details_record);

		$update_data = array();

		if($privilege != "") {
			$update_data['account_type'] = $privilege == 1 ? 'admin':'user';
			$update = $this->model_users->updateUserTable($update_data, $user_record);
		} else {
			$update["status"] = "success";
		}
		

		if($update["status"] == "success" && $update_details["status"] == "success") {
			$response["status"]		= "success";
			$response["message"] 	= "User details updated successfully";
		} else {
			$response["status"]		= "error";
			$response["message"] 	= "Error while updating the records<br/>".$update["message"]."<br/>".$update_details["message"];
		}
		print_r(json_encode($response));
	}

	public function deleteRecord() {
		$this->load->model('security/model_users');

		$record = $this->input->post('userId');

		$this->db->where('sno', $record);
		
		$response = array(
		);

		if($this->db->delete('users')) {
			$response["status"]			= "success";
			$response["message"]		= "User Deleted Successfully";
		} else {
			$response["status"]			= "error";
			$response["message"] 		= "Error while deleting the records";
		}
		print_r(json_encode($response));
	}

	public function viewOne() {
		$this->load->model('security/model_users');
		$this->load->model('utils/model_form_utils');
		$this->load->model('projects/model_contractors');

		$record 		= $this->input->post('userId') ? $this->input->post('userId') : $this->session->userdata("user_id");
		$viewFrom 		= $this->input->post('viewFrom') ? $this->input->post('viewFrom') : "personalDetails";

		$users 			= $this->model_users->getUsersList($record);
		$user_details 	= $this->model_users->getUserDetailsByEmail($users[0]->user_name);
		$stateDetails 	= $this->model_form_utils->getCountryStatus($user_details[0]->addr_state);

		$contractorName = "";
		if(!empty($user_details[0]->contractorId)) {
			$contractors = $this->model_contractors->getContractorsList($user_details[0]->contractorId);
			$contractorName = count($contractors) ? $contractors[0]->name." from ".$contractors[0]->company : "";
		}


		$params = array(
			'viewFrom' 			=> $viewFrom,
			'record'			=> $record,
			'users'				=> $users,
			'user_details' 		=> $user_details,
			'state' 			=> $stateDetails,
			'userType' 			=> $this->session->userdata("account_type"),
			'contractorName' 	=> $contractorName
		);
		
		echo $this->load->view("security/users/viewOne", $params, true);
	}
}