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
			"js/utils/constants.js",
			"js/library/jquery-2.1.3.min.js",
			"js/library/jquery-ui.js",
			"js/library/jquery.validate.js",
			"js/library/plugin/searchSelect-Jquery.js",
			"js/library/menus.js",
			"js/themes/default/layouts.js",
			"js/submit.js",
			"js/utils/utils.js",
			"js/utils/messages.js",
			"js/security/users.js",
			"js/security/operations.js",
			"js/security/roles.js",
			"js/security/functions.js",
			"js/security/dataFilters.js",
			"js/security/permissions.js",
			"js/projects/issues.js",
			"js/projects/projects.js",
			"js/projects/tasks.js",
			"js/projects/notes.js",
			"js/projects/docs.js",
			"js/service_providers/contractors.js",
			"js/adjusters/partners.js",
			"js/projects/remainingbudget.js",
			'js/home/userInfo.js',
			"js/home.js"
		)
	);

	private $css_includes = array(
		"css/jquery-ui.css",
		"css/style.css",
		"css/foundation-icons.css"
	);

	private $includes = array();
	
	/*
		Set default left navigation selection and highlight if no left navigation is selected
	*/
	private $left_menus_default = array(
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

		//$this->layout_data['menus'] 		= $this->menus;
		$this->layout_data['left_menus_default'] = $this->left_menus_default;
		$this->layout_data['menu_title'] 	= $this->menu_title;
		$this->layout_data['initVar'] 		= $this->CI->session->userdata;
		$this->layout_data['baseUrl'] 		= base_url();
		$this->layout_data['params']  		= $params;
		$this->layout_data['is_logged_in']  = $this->CI->session->userdata("is_logged_in");

		if($this->layout_data['is_logged_in']) {
			list($role_id, $role_disp_name) = $this->CI->permissions_lib->getRoleAndDisplayStr();
			$this->layout_data['role_id'] 				= $role_id;
			$this->layout_data['role_disp_name'] 		= $role_disp_name;
			$this->layout_data['projectPermission'] 	= $this->CI->permissions_lib->getPermissions('projects');
			$this->layout_data['contractorPermission'] 	= $this->CI->permissions_lib->getPermissions('service provider');
			$this->layout_data['adjusterPermission'] 	= $this->CI->permissions_lib->getPermissions('adjuster');
		}
		//$this->layout_data['login_form'] 	= $this->CI->load->view("forms/login_form", $this->layout_data, true);

		$main_content_name = null;
		
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
				$this->layout_data["userType"] 			= $this->CI->session->userdata("role_id");

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