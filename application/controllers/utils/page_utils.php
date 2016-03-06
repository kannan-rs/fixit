<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class page_utils extends CI_Controller {

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

	public function get_page_menus() {
		echo $this->layouts->top_menu();
	}

	public function header_data() {
		$is_logged_in = is_logged_in();
		$response = array(
			"base_url"		=> base_url(),
			"is_logged_in"	=> $is_logged_in
		);

		if($is_logged_in) {
			$response["logged_in_user_email"]		= $this->session->userdata('logged_in_email');
			$response["role_disp_name"]				= $this->session->userdata('logged_in_role_disp_name');
		} else {
			$response["existing_user_sign_in"]		= $this->lang->line_arr('headers->existing_user');
			$response["new_user_sign_up"]			= $this->lang->line_arr('headers->new_user');
		}
		print_r(json_encode($response));
	}
}