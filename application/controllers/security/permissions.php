<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permissions extends CI_Controller {

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
		$this->load->model('security/model_permissions');
		$data = $this->model_permissions->getAllList();

		$params = array(
			'function'=>"view",
			'record'=>"all",
			'data'=>$data
		);
		
		echo $this->load->view("security/permissions/viewAll", $params, true);
	}

	public function getPermissions() {
		$this->load->model('security/model_permissions');
		$data = $this->model_permissions->getUserPermission();

		$response = array(
			'status' => "success"
		);
		if($data && count($data)) {
			$response["data"] = $data;
		}

		print_r(json_encode($response));
	}

	public function setPermissions() {
		$this->load->model('security/model_permissions');
		$data = $this->model_permissions->setUserPermission();

		$response = array(
			'status' => "success"
		);

		if($data && count($data)) {
			$response["data"] = $data;
		}

		print_r(json_encode($response));
	}
}
?>