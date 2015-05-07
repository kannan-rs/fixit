<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Validation extends CI_Controller {

	public function login()
	{
		$response = array();

		$email = $this->input->post('login_email');
		$password = md5($this->input->post('login_password'));

		$this->load->model('security/model_users');

		if(!$this->model_users->can_login($email, $password)) {
			$response["status"] = "error";
			$response["message"] = "Incorrect Username / Password";
		} else {

			$account_type = $this->model_users->get_account_type($email, $password);
			$user_id = $this->model_users->get_user_id($email, $password);
			
			$data = array(
				'email' => $email,
				'is_logged_in'=> 1,
				'account_type' => $account_type,
				'user_id' => $user_id
			);

			$this->session->set_userdata($data);

			$response["status"] = "Success";
			$response["message"] = "";
			$response["redirect"] = "/main/projects";
		}
		print_r(json_encode($response));
	}

	public function validate_login($email, $password) {
		$this->load->model('security/model_users');

		if($this->model_users->can_login($email, $password)) {
			return true;
		} else {	
			return false;
		}
	}

	public function signup() {
		$response = array();

		$firstName 				= $this->input->post('firstName');
		$lastName 				= $this->input->post('lastName'); 
		$password 				= $this->input->post('password');
		$passwordHint 			= $this->input->post('passwordHint');
		$belongsTo 				= $this->input->post('belongsTo');
		$userType 				= $this->input->post('userType');
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

		$this->load->model('security/model_users');

		if(!$this->model_users->get_user_sno_via_email($emailId)) {
			$signup_data = array(
				'first_name' 			=> $firstName,
				'last_name' 			=> $lastName, 
				'belongs_to' 			=> $belongsTo,
				'type' 					=> $userType,
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
				'created_by'			=> 0, 	// Self
				'updated_by'			=> 0	// Self
			);

			$inserted = $this->model_users->insert_user_details($signup_data);
			if($inserted["status"] == "success") {

				$users_data = array(
					'user_name' => $emailId, 
					'password' => md5($password),
					'password_hint' => $passwordHint,
					'account_type' => '',
					'status' => 1
				);

				$inserted_login = $this->model_users->insert_users($users_data);
				
				if($inserted_login['status'] == "success") {
					$response["status"] = "success";
					$response["message"] = "User Added Successfully";
					$response["redirect"] = base_url()."main/index";
				} else {
					$response["status"] = "Error";
					$response["message"] = $inserted_login["message"];		
				}
			} else {
				$response["status"] = "Error";
				$response["message"] = $inserted["message"];	
			}
		} else {
			$response["status"] = "error";
			$response["message"] = "Email ID already exist";
		}
		print_r(json_encode($response));
	}

	public function updateUserDetails() {
		$response = array();

		$record 				= $this->input->post('sno'); 
		$lastName 				= $this->input->post('lastName'); 
		$firstName 				= $this->input->post('firstName');
		$belongsTo 				= $this->input->post('belongsTo');
		$userType 				= $this->input->post('userType');
		$userStatus 			= $this->input->post('userStatus');
		$activeStartDate 		= $this->input->post('activeStartDate');
		$activeEndDate 			= $this->input->post('activeEndDate');
		$contactPhoneNumber 	= $this->input->post('contactPhoneNumber');
		$mobileNumber 			= $this->input->post('mobileNumber');
		$pagerNumber 			= $this->input->post('pagerNumber');
		$addressLine1 			= $this->input->post('addressLine1');
		$addressLine2 			= $this->input->post('addressLine2');
		$addressLine3 			= $this->input->post('addressLine3');
		$addressLine4 			= $this->input->post('addressLine4');
		$city 					= $this->input->post('city');
		$state 					= $this->input->post('state');
		$country 				= $this->input->post('country');
		$pref 					= $this->input->post('pref');

		$this->load->model('security/model_users');

		$update_data = array(
			'last_name' 			=> $lastName, 
			'first_name' 			=> $firstName,
			'belongs_to' 			=> $belongsTo,
			'type' 					=> $userType,
			'status' 				=> $userStatus,
			'active_start_date' 	=> $activeStartDate,
			'active_end_date' 		=> $activeEndDate,
			'contact_ph1' 			=> $contactPhoneNumber,
			'contact_mobile' 		=> $mobileNumber,
			'contact_pager' 		=> $pagerNumber,
			'addr1' 				=> $addressLine1,
			'addr2' 				=> $addressLine2,
			'addr3' 				=> $addressLine3,
			'addr4' 				=> $addressLine4,
			'addr_city' 			=> $city,
			'addr_state' 			=> $state,
			'addr_country' 			=> $country,
			'contact_pref' 			=> $pref,
			'last_updated_dt' 		=> date("Y-m-d H:i:s")
		);

		$updated = $this->model_users->update_user_details($update_data, $record);
		if($updated["status"] == "success") {
			$response["status"] = "success";
			$response["message"] = "User Updated Successfully";
			$response["redirect"] = base_url()."main/index";
		} else {
				$response["status"] = "Error";
				$response["message"] = $updated["message"];	
		}
		
		print_r(json_encode($response));
	}
}
?>