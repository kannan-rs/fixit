<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class formUtils extends CI_Controller {

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

	public function getCountryStatus() {
		$this->load->model('utils/model_form_utils');

		$state = $this->model_form_utils->getCountryStatus();

		$response["status"] = "error";
		if($state) {
			$response["status"] = "success";
			$response["state"] = $state;
		} else {
			$response["message"] = "Error while fetching state details";
		}
		print_r(json_encode($response));
	}
}