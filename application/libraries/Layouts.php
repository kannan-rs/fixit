<?php 
class Layouts
{
  	// hold codeigniter instance
	private $CI;

	// hold layout title
	private $layout_data = array();

	//Default templates includes
	private $layout_template = "default";

	private $js_includes = array(
		'common' => array(
			"js/library/jquery-2.1.3.min.js",
			"js/library/jquery-ui.js",
			"js/library/jquery.validate.js",
			"js/library/plugin/searchSelect-Jquery.js",
			"js/themes/default/layouts.js",
			"js/submit.js",
			"js/utils/utils.js",
			"js/security/users.js",
			"js/security/operations.js",
			"js/security/roles.js",
			"js/security/functions.js",
			"js/security/dataFilters.js",
			"js/security/permissions.js",
			"js/security.js",
			"js/projects/issues.js",
			"js/projects/projects.js",
			"js/projects/tasks.js",
			"js/projects/notes.js",
			"js/projects/docs.js",
			"js/projects/contractors.js",
			"js/projects/partners.js",
			"js/projects/remainingbudget.js",
			"js/projects.js",
			'js/home/userInfo.js',
			"js/home.js",
			"js/home.js",
		)
	);

	private $css_includes = array(
		"css/jquery-ui.css",
		"css/style.css",
		"css/foundation-icons.css"
	); 

	private $includes = array();

	private $menus = array(
		'security'=> array(
			array('text'=> 'Users', 'link'=> '/main/security/users', 'key'=> 'users'),
			array('text'=> 'Roles', 'link'=> '/main/security/roles', 'key'=> 'roles'),
			array('text'=> 'Operations', 'link'=> '/main/security/operations', 'key'=> 'operations'),
			array('text'=> 'Functions', 'link'=> '/main/security/functions', 'key'=> 'functions'),
			array('text'=> 'Data Filters', 'link'=> '/main/security/data_filters', 'key'=> 'data_filters'),
			array('text'=> 'Permissions', 'link'=> '/main/security/permissions', 'key'=> 'permissions'),
		),
		'home' => array(
			array('text' => 'View My Details', 'link'=> '/main/home/view_my_details', 'key' => 'view_my_details'),
			array('text' => 'Change Password', 'link'=> '/main/home/change_pass_form', 'key' => 'change_pass_form')
		),
		'projects' => array(
			//array('text' => 'Issues', 'link'=> '/main/projects/issues', 'key' => 'issues'),
			//array('text' => 'Create Issue', 'link'=> '/main/projects/create_issue', 'key' => 'create_issue'),
			array('text' => 'Projects', 'link'=> '/main/projects/projects', 'key' => 'projects'),
			array('text' => 'Create Project', 'link'=> '/main/projects/create_project', 'key' => 'create_project'),
			array('text' => 'Contractors', 'link'=> '/main/projects/contractors', 'key' => 'contractors'),
			array('text' => 'Create Contractor', 'link'=> '/main/projects/create_contractor', 'key' => 'create_contractor'),
			array('text' => 'Partners', 'link'=> '/main/projects/partners', 'key' => 'partners'),
			array('text' => 'Create Partner', 'link'=> '/main/projects/create_partner', 'key' => 'create_partners')
		)
	);

	/*
		Set default left navigation selection and highlight if no left navigation is selected
	*/
	private $menus_default = array(
		'security'=>"users",
		'home' => "view_my_details",
		'projects' => 'projects'
	);

	private $menu_title = array(
		'security' 	=> "Security Management",
		'home' 		=> "Personal Details",
		'projects' 	=> "Project Management"
	);

	public function __construct() {
		$this->CI =& get_instance();
	}

	// set layout Page
	public function setPage($page) {
		$this->layout_data['page'] = $page;
	}

	// add includes like css and js
	public function addInclude($path, $prepend_base_url = true) {
		if($prepend_base_url) {
			$this->CI->load->helper('url'); // just in case
			$this->includes[] = base_url() . $path;
		} else {
			$this->includes[] = $path;
		}
		return $this;
	}

	// print the includes
	public function printIncludes() {
		$final_includes = '';

		foreach($this->includes as $include) {
			if (preg_match('/js$/', $include)) {
				$final_includes .= '<script src="' . $include . '"></script>' . "\n\r";
			} elseif (preg_match('/css$/', $include)) {
				$final_includes .= '<link href="' . $include . '" rel="stylesheet"/>' . "\n\r";
			}
		}

		return $final_includes;
	}

	// call the layouts view from the controller
	/*
	 $theme_name		=	Name of the view to render
	 $params 			= 	Array of parameters to be passed
	*/
	public function view($params = array(), $layouts = array()) {

		$this->layout_data['menus'] 		= $this->menus;
		$this->layout_data['menus_default'] = $this->menus_default;
		$this->layout_data['menu_title'] 	= $this->menu_title;

		$this->layout_data['initVar'] = $this->CI->session->userdata;
		$this->layout_data['baseUrl'] = base_url();
		$this->layout_data['params']  = $params;

		$main_content_name = null;
		$this->layout_data['login_form'] = $this->CI->load->view("forms/login_form", $this->layout_data, true);

		//echo "<br/>Page --> ".$this->CI->session->userdata("page");
		//echo "<br/>Module --> ".$this->CI->session->userdata("module");
		//echo "<br/>Logged IN --> ".$this->CI->session->userdata('is_logged_in');
		
		if($this->CI->session->userdata("page")) {
			$main_content_name = $this->CI->session->userdata("page");
		}

		$this->layout_data["main_content_name"] = $main_content_name;
		
		// Set Template and its required layouts
		$this->setLayout($params);
		//Set JS and CSS Includes
		$this->setIncludes($params);
		// render view
		$this->CI->load->view("themes/".$this->layout_template."/template", $this->layout_data);
	}

	public function setLayout($params) {
		// set layout's Header
		$this->layout_data['header'] 			= $this->CI->load->view("themes/".$this->layout_template."/header", $this->layout_data, true);
		// set layout's top menu Links
		$this->layout_data['top_menu'] 			= $this->CI->load->view("themes/".$this->layout_template."/top_menu", $this->layout_data, true);
		// set layout's side bar for left navigation
		$this->layout_data['left_side_bar']	= $this->CI->load->view("themes/".$this->layout_template."/left_side_bar", $this->layout_data, true);
		
		
		if(!$this->CI->session->userdata("is_logged_in") && $this->layout_data['page'] == "index") {
			//echo "<br/>Not Logged In";
			$this->layout_data['main_content'] = $this->CI->load->view("notLoggedIn/index", $this->layout_data, true);
		} else {
			//echo "<br/>Logged In";
			//echo "<br/>"."pages/".$this->layout_data['main_content_name'];
			//echo "---".$this->CI->session->userdata("userType")."---<br/>";
			//print_r($this->layout_data);
			if($this->layout_data['main_content_name'] == "signup") {
				$this->layout_data["userType"] 			= $this->CI->session->userdata("account_type");

				$addressParams = array(
					'forForm' 		=> "create_user_form"
				);
				$addressFile 							= $this->CI->load->view("forms/address", $addressParams, true);
				$this->layout_data['addressFile']		= $addressFile;
				$this->layout_data['main_content']		= $this->CI->load->view("security/users/inputForm", $this->layout_data, true);
			} else {
				$this->layout_data['main_content']		= $this->CI->load->view("pages/".$this->layout_data['main_content_name'], $this->layout_data, true);
			}
		}
		
		
		//set layout's Right Hand side portions
		$this->layout_data['right_side_bar']	= $this->CI->load->view("themes/".$this->layout_template."/right_side_bar", $this->layout_data, true);
		// set layout's Footers
		$this->layout_data['footer']			= $this->CI->load->view("themes/".$this->layout_template."/footer", $this->layout_data, true);
	}

	public function setIncludes($params) {
		// Set layout's JS files
		
		for($jsIdx = 0; $jsIdx < count($this->js_includes['common']); $jsIdx++){
			$this->addInclude($this->js_includes['common'][$jsIdx]);
		}
		
		if(isset($this->layout_data['page']) && isset($this->js_includes[$this->layout_data['page']])) {
			for($jsIdx = 0; $jsIdx < count($this->js_includes[$this->layout_data['page']]); $jsIdx++){
				$this->addInclude($this->js_includes[$this->layout_data['page']][$jsIdx]);
			}
		}
		
		// Set layout's CSS files
		for($cssIdx = 0; $cssIdx < count($this->css_includes); $cssIdx++){
			$this->addInclude($this->css_includes[$cssIdx]);
		}
		$this->layout_data["includes"]			= $this->printIncludes();
	}
}
?>