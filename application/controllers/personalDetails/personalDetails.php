<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PersonalDetails extends CI_Controller {

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

	public function getUserDatas() {
		$this->load->model('personalDetails/model_details');
		
		$email = $this->session->userdata("email");
		$users = $this->model_details->get_users_list($email);
		
		$params = array(
			'function'=>"view",
			'record'=>"all",
			'user_details'=>$users
		);

		echo $this->load->view("personalDetails/view_my_details", $params, true);
	}

	
	public function editPage() {
		$this->load->model('personalDetails/model_details');
		
		$email = $this->session->userdata("email");
		$users = $this->model_details->get_users_list($email);
		
		$params = array(
			'function'=>"view",
			'record'=>"all",
			'user_details'=>$users
		);

		echo $this->load->view("personalDetails/edit_my_details", $params, true);
	}


	public function update() {
		$this->load->model('personalDetails/model_details');

		$response = array();

		$record 				= $this->input->post('sno'); 
		$firstName 				= $this->input->post('firstName');
		$lastName 				= $this->input->post('lastName'); 
		$belongsTo 				= $this->input->post('belongsTo');
		$userType 				= $this->input->post('userType');
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

		$this->load->model('security/model_users');

		$update_data = array(
			'first_name' 			=> $firstName,
			'last_name' 			=> $lastName, 
			'belongs_to' 			=> $belongsTo,
			'type' 					=> $userType,
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

		$updated = $this->model_details->update($update_data, $record);
		if($updated["status"] == "success") {
			$this->getUserDatas();
		} else {
			$this->editPage();
		}
	}

	function changePassForm() {
		$this->load->model('security/model_users');
		
		$record = $this->session->userdata("user_id");
		$users = $this->model_users->get_users_list($record);

		$params = array(
			'function'=>"changePassForm",
			'record'=>"",
			'user_details'=>$users
		);

		echo $this->load->view("personalDetails/changePassForm", $params, true);
	}

	public function updatePassword() {
		$response = array();

		$record 		= $this->input->post('sno'); 
		$password 		= $this->input->post('password'); 
		$passwordHint 	= $this->input->post('passwordHint');
		$email 			= $this->input->post('email');
		
		$this->load->model('security/model_users');

		$update_data = array(
			'password' => md5($password),
			'password_hint' => $passwordHint,
			'updated_by' => $this->session->userdata("user_id"),
			'updated_date' => date("Y-m-d H:i:s")
		);


		$update = $this->model_users->update_user_table_by_email($update_data, $email);

		if($update["status"] == "success") {
			$response["status"] = "Success";
			$response["message"] = "User Password Updated Successfully";
		} else {
			$response["status"] = "Error";
			$response["message"] = $update["message"];	
		}
		
		print_r(json_encode($response));	
	}
}