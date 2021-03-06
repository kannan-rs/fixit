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
		//print_r($data);

		$this->session->set_userdata($data);
   }

	public function index()
	{
		// With View library
		$this->load->library("layouts");

		$this->layouts->setPage("index");
		
		if(is_logged_in()) {
			redirect(base_url()."main/home");
		}
		//Render a view
		$this->layouts->view();
	}

	public function overview()
	{
		// With View library
		$this->load->library("layouts");

		$this->layouts->setPage("overview");
		
		//Render a view
		$this->layouts->view();
	}

	public function faq()
	{
		// With View library
		$this->load->library("layouts");

		$this->layouts->setPage("faq");
		
		//Render a view
		$this->layouts->view();
	}
	public function about_us()
	{
		// With View library
		$this->load->library("layouts");

		$this->layouts->setPage("about_us");
		
		//Render a view
		$this->layouts->view();
	}
	public function contact_us()
	{
		// With View library
		$this->load->library("layouts");

		$this->layouts->setPage("contact_us");
		
		//Render a view
		$this->layouts->view();
	}

	public function home()
	{
		// With View library
		/*echo "<br/>";
		echo "main > home";*/
		$this->load->library("layouts");

		if(!$this->isLoggedIn()) {
			return false;
		}

		$this->layouts->setPage("home");
		
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

		if($this->session->userdata('module') != "exportCSV") {
			$this->layouts->setPage("projects");
			//Render a view
			$this->layouts->view();
		} else {
			//$this->load->model('projects');
			//$this->Users_model->csv();
			$this->load->library('../controllers/projects/projects');
			$this->projects->exportCSV();
		}
	}

	public function service_providers()
	{
		// With View library
		$this->load->library("layouts");

		if(!$this->isLoggedIn()) {
			return false;
		}

		$this->layouts->setPage("service_providers");
		//Render a view
		$this->layouts->view();
	}

	public function adjusters()
	{
		// With View library
		$this->load->library("layouts");

		if(!$this->isLoggedIn()) {
			return false;
		}

		$this->layouts->setPage("adjusters");
		//Render a view
		$this->layouts->view();
	}

	public function insurance_company() {
		// With View library
		$this->load->library("layouts");

		if(!$this->isLoggedIn()) {
			return false;
		}

		$this->layouts->setPage("insurance_company");
		//Render a view
		$this->layouts->view();
	}

	public function claims()
	{
		// With View library
		$this->load->library("layouts");

		if(!$this->isLoggedIn()) {
			return false;
		}

		$this->layouts->setPage("claims");
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

	public function login()
	{
		// With View library
		$this->load->library("layouts");

		$this->layouts->setPage("login");
		//Render a view
		$this->layouts->view();
	}

	public function forgotpass()
	{
		// With View library
		$this->load->library("layouts");

		$this->layouts->setPage("forgotpass");
		//Render a view
		$this->layouts->view();
	}

	public function activate_user()
	{
		// With View library
		$this->load->library("layouts");
		$this->load->model('security/model_users');

		$this->layouts->setPage("activation_user");

		$response = $this->model_users->activate_user($this->session->userdata('module'));

		if($response && $response["activated_user"] && count($response["activated_user"])) {
			$email = $response["activated_user"][0]->user_name;
			if(!is_null($email) && !empty($email)) {
				$response["user_details"] = $this->model_users->getUserDetailsByEmail($email);
			}
		}
		$params = array(
			'response' => $response
		);

		$this->layouts->view( $params );
	}

	public function web_contents()
	{
		// With View library
		$this->load->library("layouts");

		if(!$this->isLoggedIn()) {
			return false;
		}

		$this->layouts->setPage("web_content");
		//Render a view
		$this->layouts->view();
	}

	public function isLoggedIn() {
		if(!is_logged_in()) {
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