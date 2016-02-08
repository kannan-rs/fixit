<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notes extends CI_Controller {

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
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$claimPermission 	= $this->permissions_lib->getPermissions(FUNCTION_CLAIM);
		$notesPermission 	= $this->permissions_lib->getPermissions(FUNCTION_CLAIM_NOTES);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $claimPermission['operation']) || !in_array(OPERATION_VIEW, $notesPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Notes List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		/* Get Role ID and Role Display String*/
		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();


		$this->load->model('claims/model_notes');
		$this->load->model('security/model_users');
		$this->load->model('claims/model_claims');

		$claim_id 			= $this->input->post('claim_id');
		$note_id 			= $this->input->post('note_id');
		$startRecord 		= $this->input->post('startRecord');
		$count 				= $this->input->post('count');

		$startRecord 		= $startRecord != "" ? $startRecord : 0;
		$count 				= $count != "" ? $count : 5;

		$notesResponse = $this->model_notes->getNotesList($claim_id, $note_id, $startRecord, $count);

		$count 				= $this->model_notes->count($claim_id);

		for($i=0; $i < count($notesResponse["notes"]); $i++) {
			$notesResponse["notes"][$i]->created_by_name = $this->model_users->getUsersList($notesResponse["notes"][$i]->created_by)[0]->user_name;
			$notesResponse["notes"][$i]->updated_by_name = $this->model_users->getUsersList($notesResponse["notes"][$i]->updated_by)[0]->user_name;
		}

		list($role_id, $role_disp_name) = $this->permissions_lib->getRoleAndDisplayStr();
		//Claim > Permissions for logged in User by role_id

		$params = array(
			'claim_notes' 	=> isset($notesResponse["notes"]) ? $notesResponse["notes"] : [],
			'count' 			=> $notesResponse["count"],
			'startRecord' 		=> $startRecord,
			'claim_id' 		=> $claim_id
		);
		
		echo $this->load->view("claims/notes/viewAll", $params, true);
	}
	
	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Claim > Permissions for logged in User by role_id
		$claimPermission 	= $this->permissions_lib->getPermissions(FUNCTION_CLAIM);
		$notesPermission 	= $this->permissions_lib->getPermissions(FUNCTION_CLAIM_NOTES);

		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $claimPermission['operation']) || !in_array(OPERATION_CREATE, $notesPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Create Notes"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$claim_id			= $this->input->post('claim_id');

		$params = array(
			'claim_id' 	=> $claim_id
		);

		echo $this->load->view("claims/notes/createForm", $params, true);
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}
		
		$is_allowed_claim 	= $this->permissions_lib->is_allowed(FUNCTION_CLAIM, OPERATION_CREATE);
		$is_allowed 		= $this->permissions_lib->is_allowed(FUNCTION_CLAIM_NOTES, OPERATION_CREATE);

		if(!$is_allowed_claim["status"] || !$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('claims/model_notes');
		$this->load->model('mail/model_mail');

		$claim_id 	= $this->input->post('claim_id');

		$data = array(
			'claim_id'			=> $claim_id,
			'notes_content'		=> $this->input->post('noteContent'),
			'created_by'		=> $this->session->userdata('user_id'),
			'updated_by'		=> $this->session->userdata('user_id'),
			'created_on'		=> date("Y-m-d H:i:s"),
			'updated_on'		=> date("Y-m-d H:i:s")
		);

		$response = $this->model_notes->insert($data);

		if($response["status"] == "success") {
			$response["claim_id"] = $this->input->post('claim_id');
		}

		print_r(json_encode($response));
	}

	public function deleteRecord() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed_claim 	= $this->permissions_lib->is_allowed(FUNCTION_CLAIM, OPERATION_DELETE);
		$is_allowed 		= $this->permissions_lib->is_allowed(FUNCTION_CLAIM_NOTES, OPERATION_DELETE);

		if(!$is_allowed_claim["status"] || !$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('claims/model_notes');
		$this->load->model('mail/model_mail');

		$note_id = $this->input->post('note_id');

		$notesResponse = $this->model_notes->getNotesList(null, $note_id, 0, 1);
		$note 		= isset($notesResponse["notes"]) && count($notesResponse["notes"]) ? $notesResponse["notes"][0] : null;

		$response = $this->model_notes->deleteRecord($note_id);

		print_r(json_encode($response));
	}
}