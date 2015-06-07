<?php 
class Layouts
{
  	// hold codeigniter instance
	private $CI;

	// hold layout title
	private $layout_data = array();

	//Default templates includes
	private $layout_template = "default";

	// hold includes like css and js files
	private $js_includes = array(
			'common' => array(
				"js/library/jquery-2.1.3.min.js",
				"js/library/jquery-ui.js",
				"js/library/jquery.validate.js",
				"js/themes/default/layouts.js",
				"js/validation.js",
				"js/submit.js",	
			),
			'security' => array(
				"js/security/users.js",
				"js/security/operations.js",
				"js/security/roles.js",
				"js/security/functions.js",
				"js/security/dataFilters.js",
				"js/security/permissions.js",
				"js/security.js",
			),
			'projects' => array(
				"js/projects/projects.js",
				"js/projects/tasks.js",
				"js/projects/notes.js",
				"js/projects/docs.js",
				"js/projects/contractors.js",
				"js/projects.js"
			),
			'personalDetails' => array(
				'js/personalDetails/userInfo.js',
				"js/personalDetails.js",
			)
		);

	private $css_includes = array(
		"css/themes/default/styles.css", 
		"css/themes/default/others.css", 
		"css/jquery-ui.css",
		"css/container.css",
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
			array('text'=> 'Permissions', 'link'=> '/main/security/permissions', 'key'=> 'promissions'),
		),
		'personalDetails' => array(
			array('text' => 'View My Details', 'link'=> '/main/personalDetails/view_my_details', 'key' => 'view_my_details'),
			array('text' => 'Change Password', 'link'=> '/main/personalDetails/changePass_form', 'key' => 'change_pass_form')
		),
		'projects' => array(
			array('text' => 'Projects', 'link'=> '/main/projects/projects', 'key' => 'projects'),
			array('text' => 'Create Project', 'link'=> '/main/projects/create_project', 'key' => 'create_project'),
			array('text' => 'Contractors', 'link'=> '/main/projects/contractors', 'key' => 'contractors'),
			array('text' => 'Create Contractor', 'link'=> '/main/projects/create_contractor', 'key' => 'create_contractors'),
		)
	);

	private $menus_default = array(
		'security'=>"users",
		'personalDetails' => "my_details",
		'projects' => 'projects'
	);

	public function __construct() {
		$this->CI =& get_instance();
	}

	// set layout Page
	public function set_page($page) {
		$this->layout_data['page'] = $page;
	}

	// set layout description
	public function set_description($description) {
		$this->layout_description = $description;
	}

	//Set one User Details
	public function set_user_details($user_details) {
		$this->layout_data['user_details'] = $user_details;
	}

	// add includes like css and js
	public function add_include($path, $prepend_base_url = true) {
		if($prepend_base_url) {
			$this->CI->load->helper('url'); // just in case
			$this->includes[] = base_url() . $path;
		} else {
			$this->includes[] = $path;
		}
		return $this;
	}

	// print the includes
	public function print_includes() {
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

		$this->layout_data['menus'] = $this->menus;
		$this->layout_data['menus_default'] = $this->menus_default;

		$this->layout_data['initVar'] = $this->CI->session->userdata;
		$this->layout_data['baseUrl'] = base_url();

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
		$this->set_layout($params);
		//Set JS and CSS Includes
		$this->set_includes($params);
		// render view
		$this->CI->load->view("themes/".$this->layout_template."/template", $this->layout_data);
	}

	public function set_layout($params) {
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
			$this->layout_data['main_content']		= $this->CI->load->view("pages/".$this->layout_data['main_content_name'], $this->layout_data, true);
		}
		
		
		//set layout's Right Hand side portions
		$this->layout_data['right_side_bar']	= $this->CI->load->view("themes/".$this->layout_template."/right_side_bar", $this->layout_data, true);
		// set layout's Footers
		$this->layout_data['footer']			= $this->CI->load->view("themes/".$this->layout_template."/footer", $this->layout_data, true);
	}

	public function set_includes($params) {
		// Set layout's JS files
		
		for($jsIdx = 0; $jsIdx < count($this->js_includes['common']); $jsIdx++){
			$this->add_include($this->js_includes['common'][$jsIdx]);
		}
		
		if(isset($this->layout_data['page']) && isset($this->js_includes[$this->layout_data['page']])) {
			for($jsIdx = 0; $jsIdx < count($this->js_includes[$this->layout_data['page']]); $jsIdx++){
				$this->add_include($this->js_includes[$this->layout_data['page']][$jsIdx]);
			}
		}
		
		// Set layout's CSS files
		for($cssIdx = 0; $cssIdx < count($this->css_includes); $cssIdx++){
			$this->add_include($this->css_includes[$cssIdx]);
		}
		$this->layout_data["includes"]			= $this->print_includes();
	}
}
?>