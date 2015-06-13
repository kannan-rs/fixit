<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

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

		$data = array(
			'controller' => $controller,
			'page' => $page,
			'module'=> $module,
			'function' => $function,
			'record'=> $record
		);

		$this->session->set_userdata($data);
   }

	public function index()
	{
		// With View library
		$this->load->library("layouts");

		$this->layouts->setPage("index");
		
		if($this->session->userdata('is_logged_in')) {
			redirect(base_url()."main/personalDetails	");
		}
		//Render a view
		$this->layouts->view();
	}

	public function personalDetails()
	{
		// With View library
		$this->load->library("layouts");

		if(!$this->isLoggedIn()) {
			return false;
		}

		$this->layouts->setPage("personalDetails");
		
		//Render a view
		$this->layouts->view();
	}

	public function security()
	{
		// With View library
		$this->load->library("layouts");

		if(!$this->isLoggedIn()) {
			return false;
		}

		$this->layouts->setPage("security");
		//Render a view
		$this->layouts->view();
	}

	public function projects()
	{
		// With View library
		$this->load->library("layouts");

		if(!$this->isLoggedIn()) {
			return false;
		}
		$this->layouts->setPage("projects");
		//Render a view
		$this->layouts->view();
	}

	public function signup()
	{
		// With View library
		$this->load->library("layouts");

		$this->layouts->setPage("signup");
		//Render a view
		$this->layouts->view();
	}


	public function isLoggedIn() {
		if(!$this->session->userdata("is_logged_in")) {
			 redirect('/main/index', 'refresh');
		} else {
			return true;
		}
	}

	public function logout() {
		$this->session->sess_destroy();
		redirect("/");
	}
}
?>