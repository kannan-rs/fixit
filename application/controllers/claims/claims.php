<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends CI_Controller {

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
		//Project > Permissions for logged in User by role_id
		$claimPermission = $this->permissions_lib->getPermissions('claims');

		/* If User dont have view permission load No permission page */
		if(!in_array('view', $claimPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Claims list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('claims/model_claims');
		
		$partnersResponse = $this->model_claims->getClaimsList();

		$params = array(
			'partners'=>$partnersResponse["partners"],
			'role_id' => $this->session->userdata('role_id')
		);
		
		echo $this->load->view("claims/claims/viewAll", $params, true);
	}

}