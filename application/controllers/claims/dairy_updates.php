<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dairy_updates extends CI_Controller {

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
		//Claim > Permissions for logged in User by role_id
		//$notesPermission = $this->permissions_lib->getPermissions('notes');

		/* If User dont have view permission load No permission page */
		/*if(!in_array('view', $notesPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Notes List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}*/

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();


		$this->load->model('claims/model_dairy_updates');
		$this->load->model('security/model_users');
		$this->load->model('claims/model_claims');

		$claim_id 			= $this->input->post('claim_id');
		$dairy_update_id 	= $this->input->post('dairy_update_id');
		$startRecord 		= $this->input->post('startRecord');
		$count 				= $this->input->post('count');

		$startRecord 		= $startRecord != "" ? $startRecord : 0;
		$count 				= $count != "" ? $count : 5;

		$dairy_updates_response = $this->model_dairy_updates->getDairyUpdatesList($claim_id, $dairy_update_id, $startRecord, $count);

		$count 				= $this->model_dairy_updates->count($claim_id);

		for($i=0; $i < count($dairy_updates_response["dairy_updates"]); $i++) {
			$dairy_updates_response["dairy_updates"][$i]->created_by_name = $this->model_users->getUsersList($dairy_updates_response["dairy_updates"][$i]->created_by)[0]->user_name;
			$dairy_updates_response["dairy_updates"][$i]->updated_by_name = $this->model_users->getUsersList($dairy_updates_response["dairy_updates"][$i]->updated_by)[0]->user_name;
		}

		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();
		//Claim > Permissions for logged in User by role_id

		$params = array(
			'claim_dairy_updates' 	=> isset($dairy_updates_response["dairy_updates"]) ? $dairy_updates_response["dairy_updates"] : [],
			'count' 				=> $dairy_updates_response["count"],
			'startRecord' 			=> $startRecord,
			'claim_id' 				=> $claim_id
		);
		
		echo $this->load->view("claims/dairy_updates/viewAll", $params, true);
	}
	
	public function createForm() {
		//Claim > Permissions for logged in User by role_id
		//$notesPermission = $this->permissions_lib->getPermissions('notes');

		/* If User dont have view permission load No permission page */
		/*if(!in_array('create', $notesPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Create Notes"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}*/

		$claim_id			= $this->input->post('claim_id');

		$params = array(
			'claim_id' 	=> $claim_id
		);

		echo $this->load->view("claims/dairy_updates/createForm", $params, true);
	}

	public function add() {
		$this->load->model('claims/model_dairy_updates');
		$this->load->model('mail/model_mail');

		$claim_id 	= $this->input->post('claim_id');

		$data = array(
			'claim_id'				=> $claim_id,
			'dairy_updates_content'	=> $this->input->post('daily_update_content'),
			'created_by'			=> $this->session->userdata('user_id'),
			'updated_by'			=> $this->session->userdata('user_id'),
			'created_on'			=> date("Y-m-d H:i:s"),
			'updated_on'			=> date("Y-m-d H:i:s")
		);

		$response = $this->model_dairy_updates->insert($data);

		if($response["status"] == "success") {
			$response["claim_id"] = $this->input->post('claim_id');
		}

		print_r(json_encode($response));
	}

	public function deleteRecord() {
		$this->load->model('claims/model_dairy_updates');
		$this->load->model('mail/model_mail');

		$dairy_update_id = $this->input->post('dairy_update_id');

		$dairy_updates_response = $this->model_dairy_updates->getDairyUpdatesList(null, $dairy_update_id, 0, 1);
		$dairy_update 		= isset($dairy_updates_response["dairy_updates"]) && count($dairy_updates_response["dairy_updates"]) ? $dairy_updates_response["dairy_updates"][0] : null;

		$response = $this->model_dairy_updates->deleteRecord($dairy_update_id);

		print_r(json_encode($response));
	}
}