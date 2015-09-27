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
			redirect(base_url()."main/home");
		}
		//Render a view
		$this->layouts->view();
	}

	public function home()
	{
		// With View library
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

	public function signup()
	{
		// With View library
		$this->load->library("layouts");

		$this->layouts->setPage("signup");
		//Render a view
		$this->layouts->view();
	}

	public function activate_user()
	{
		// With View library
		$this->load->library("layouts");
		$this->load->model('security/model_users');

		$this->layouts->setPage("activation_user");
		
		/*echo "controller--".$this->session->userdata('controller');
		echo "<br/>";
		echo "page--".$this->session->userdata('page');
		echo "<br/>";
		echo "module--".$this->session->userdata('module');
		echo "<br/>";
		echo "function--".$this->session->userdata('function');
		echo "<br/>";
		echo "record--".$this->session->userdata('record');
		echo "<br/>";*/

		$response = $this->model_users->activate_user($this->session->userdata('module'));

		//($response);

		//print_r($response);
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