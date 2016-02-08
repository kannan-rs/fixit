<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class home extends CI_Controller {

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

	public function changePassForm() {
		$this->load->model('security/model_users');
		
		$record = $this->session->userdata("user_id");
		$users = $this->model_users->getUsersList($record);

		$params = array(
			'function'=>"changePassForm",
			'record'=>"",
			'user_details'=>$users
		);

		echo $this->load->view("home/changePassForm", $params, true);
	}

	public function updatePassword() {
		/* Including Required Modules */
		$this->load->model('security/model_users');

		$response = array();

		$record 		= $this->input->post('sno'); 
		$password 		= $this->input->post('password'); 
		$passwordHint 	= $this->input->post('passwordHint');
		$email 			= $this->input->post('email');

		$update_data = array(
			'password' => md5($password),
			'password_hint' => $passwordHint,
			'updated_by' => $this->session->userdata("user_id"),
			'updated_date' => date("Y-m-d H:i:s")
		);


		$update = $this->model_users->updateUserTableByEmail($update_data, $email);

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