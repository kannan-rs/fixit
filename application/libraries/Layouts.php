<?php 
class Layouts {
  	// hold codeigniter instance
	private $CI;

	// hold layout title
	private $layout_data = array();

	//Default templates includes
	private $layout_template = "default";

	private $js_includes = array(
		'common' => array(
			"js/shared/utils/constants.js",
			"js/library/angular.min.js",
			"js/library/jquery-2.1.3.min.js",
			"js/library/jquery-ui.js",
			"js/library/nicEdit.js",
			"js/library/jquery.validate.js",
			"js/library/filedrag.js",
			"js/library/plugin/searchSelect-Jquery.js",
			//"js/library/jssor.slider.mini.js",
			"js/library/jquery.bxslider.js",
			//"js/library/menus.js",
			"js/shared/themes/default/layouts.js",
			"js/shared/apps/fixit_app.js",

			"js/shared/top_menu/top_menu_ctrl.js",
			"js/shared/top_menu/top_menu_directive.js",

			"js/shared/header/header_ctrl.js",
			//"js/shared/header/header_directive.js",

			"js/shared/fixitcontent/fixitcontent_ctrl.js",

			"js/shared/footer/footer_ctrl.js",

			"js/submit.js",
			"js/shared/utils/utils.js",
			"js/shared/utils/messages.js",
			"js/components/security/users/users.js",
			"js/components/security/operations/operations.js",
			"js/components/security/roles/roles.js",
			"js/components/security/functions/functions.js",
			"js/components/security/dataFilters/dataFilters.js",
			"js/components/security/permissions/permissions.js",
			"js/components/projects/issues/issues.js",
			"js/components/projects/projects/projects.js",
			"js/components/projects/tasks/tasks.js",
			"js/components/projects/notes/notes.js",
			"js/components/projects/docs/docs.js",
			"js/components/projects/remainingbudget/remainingbudget.js",
			"js/components/service_providers/contractors/contractors.js",
			"js/components/insurance_company/insurance_company/insurance_company.js",
			"js/components/service_providers/trades/trades.js",
			"js/components/service_providers/discounts/discounts.js",
			"js/components/service_providers/testimonial/testimonial.js",
			"js/components/adjusters/partners.js",
			"js/components/claims/claims/claims.js",
			"js/components/claims/notes/notes.js",
			"js/components/claims/dairy_updates/dairy_updates.js",
			"js/components/claims/subrogation/subrogation.js",
			"js/components/claims/docs/docs.js",
			'js/components/home/userInfo.js',
			'js/components/web_contents/home_content.js',
			"js/home.js"
		)
	);

	private $css_includes = array(
		"css/jquery-ui.css",
		"css/style.css",
		"css/foundation-icons.css",
		"css/jquery.bxslider.css"
	);

	private $includes = array();

	private $menus = TOP_MENUS;
	
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

	private function set_required_permission() {
		if(is_logged_in()) {
			$this->layout_data['role_id'] 				= $this->CI->session->userdata('logged_in_role_id');
			$this->layout_data['role_disp_name'] 		= $this->CI->session->userdata('logged_in_role_disp_name');

			$this->layout_data['projectPermission'] 	= $this->CI->permissions_lib->getPermissions(FUNCTION_PROJECTS);
			$this->layout_data['contractorPermission'] 	= $this->CI->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER);
			$this->layout_data['adjusterPermission'] 	= $this->CI->permissions_lib->getPermissions(FUNCTION_PARTNER);
			$this->layout_data['claimPermission'] 		= $this->CI->permissions_lib->getPermissions(FUNCTION_CLAIM);
			$this->layout_data['insCompPermission'] 	= $this->CI->permissions_lib->getPermissions(FUNCTION_INSURANCE_COMPANY);
			$this->layout_data['userPermission'] 		= $this->CI->permissions_lib->getPermissions(FUNCTION_USERS);
			/*$this->layout_data['rolePermission'] 		= $this->CI->permissions_lib->getPermissions(FUNCTION_USERS);
			$this->layout_data['functionPermission'] 	= $this->CI->permissions_lib->getPermissions(FUNCTION_USERS);
			$this->layout_data['dataFilterPermission'] 	= $this->CI->permissions_lib->getPermissions(FUNCTION_USERS);
			$this->layout_data['operationPermission'] 	= $this->CI->permissions_lib->getPermissions(FUNCTION_USERS);
			$this->layout_data['permissionPermission'] 	= $this->CI->permissions_lib->getPermissions(FUNCTION_USERS);*/
		}
	}

	private function check_menu_dependency( $menu ) {
		/*if($menu['text'] == "Insurance Company") {
			echo ("ins-dep");
			print_r($menu['dependency']);
		}*/
		$dependency_ok = true;
		$role_disp_name = $this->CI->session->userdata('logged_in_role_disp_name');
		if($dependency_ok && isset($menu['dependency'])) {
			/*if($menu['text'] == "Insurance Company") {
				//echo ("frist ok");
			}*/
			$dependency = $menu['dependency'];
			if(isset($dependency['roles_by_name']) && isset($role_disp_name) && !in_array($role_disp_name, $dependency['roles_by_name'])) {
				$dependency_ok = false;
			}


			if($dependency_ok && isset($dependency['permissions']) && isset($dependency['operation'])) {
				$permissionKey 		= $dependency['permissions'];
				/*if($menu['text'] == "Insurance Company") {
					//print_r($this->layout_data[$permissionKey]);
				}*/
				
				$allowedPermission 	= isset($this->layout_data[$permissionKey]) ? $this->layout_data[$permissionKey] : null;
				$operation 			= $dependency['operation'];
				$showMenu 			= false;

				if(isset($allowedPermission)) {
					/*if($menu['text'] == "Insurance Company") {
						//print_r($allowedPermission);
						//print_r($operation);
					}*/
					for($opIx = 0; $opIx < count($operation); $opIx++) {
						if(in_array($operation[$opIx], $allowedPermission['operation'])) {
							$showMenu = true;
						}
					}
					if($menu['text'] == "Insurance Company") {
						//echo "showMenu->".$showMenu;
					}
				}
				if($menu['text'] == "Projects") {
						//echo "show menu-".$showMenu."-";
				}
				if(!$showMenu) {
					$dependency_ok =  false;
				}
			}
		}
		return $dependency_ok;
	}

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
		$this->layout_data['is_logged_in']  = is_logged_in();
		$this->layout_data['currentPage']	= $this->CI->session->userdata("page");

		$this->set_required_permission();

		$main_content_name = null;
		
		if($this->CI->session->userdata("page")) {
			$main_content_name = $this->CI->session->userdata("page");
		}

		$this->layout_data["main_content_name"] = $main_content_name;
		
		// Set Template and its required layouts
		$this->setLayout($params);
		//Set JS and CSS Includes
		$this->setIncludes($params);

		$discountResponse = $this->getDiscountData();
		// render view
		$this->CI->load->view("themes/".$this->layout_template."/template", $this->layout_data);
	}

	public function setLayout($params) {
		// set layout's side bar for left navigation
		$this->layout_data['left_side_bar']	= $this->CI->load->view("themes/".$this->layout_template."/left_side_bar", $this->layout_data, true);
		
		
		if(!is_logged_in() && $this->layout_data['page'] == "index") {
			//echo "<br/>Not Logged In";
			$this->layout_data['main_content'] = $this->CI->load->view("notLoggedIn/index", $this->layout_data, true);

			$this->layout_data['news_content'] = $this->getNews_content();
			$this->layout_data['resource_content'] = $this->getResource_content();
		} else {
			if($this->layout_data['main_content_name'] == "signup") {
				$this->layout_data["userType"] 			= $this->CI->session->userdata('logged_in_role_id');

				$this->layout_data['addressFile']		= $this->CI->form_lib->getAddressFile(array("requestFrom" => "input", "view" => "create_user_form"));
				$this->layout_data['main_content']		= $this->CI->load->view("security/users/inputForm", $this->layout_data, true);
			} else {
				$this->layout_data['main_content']		= $this->CI->load->view("pages/".$this->layout_data['main_content_name'], $this->layout_data, true);
			}
		}
		
		
		//set layout's Right Hand side portions
		$this->layout_data['right_side_bar']	= $this->CI->load->view("themes/".$this->layout_template."/right_side_bar", $this->layout_data, true);
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

	public function top_menu() {
		$menus 			= json_decode( $this->menus, true);
		$is_logged_in	= is_logged_in() ? 1 : 0;

		$this->set_required_permission();

		$menus_to_show 	= array();

		for($menuIdx = 0; $menuIdx < count($menus); $menuIdx++) {
			$selected = "";

			$menu = $menus[$menuIdx];
			
			/* Dependency Check from Main Menu */
			$dependency_pass = $this->check_menu_dependency( $menu );
			if(!$dependency_pass) {
				continue;
			}
			/* Dependency Check Ends */
			
			/* Top Menu Generation */
			if($menu['is_logged_in'] === 'all' || $menu['is_logged_in'] === $is_logged_in) {
				
				$sub_menus = isset($menu['sub_menus']) ? $menu['sub_menus'] : null;

				$temp_menu_to_show = array(
					'menu_text'		=> $menu["text"],
					'link'			=> isset($menu["link"]) ? $menu["link"] : "",
					'sub_menus'		=> array()
				);

				$show_main_menu = false;

				if($sub_menus) {
					for($subMenuIdx = 0, $subMenuCount = count($sub_menus); $subMenuIdx < $subMenuCount; $subMenuIdx++) {
						$sbm1	= $sub_menus[$subMenuIdx];

						/* Dependency check for sub menu */
						$dependency_pass = $this->check_menu_dependency( $sbm1 );
						if(!$dependency_pass)
							continue;
						/* Dependency Check ends */

						$sub_menus2 = isset($sbm1['sub_menus']) ? $sbm1['sub_menus'] : null;

						$temp_sub_menu_1 =  array(
							"link"			=> isset($sbm1["link"]) ? $sbm1["link"] : "",
							"menu_text" 	=> $sbm1["text"],
							"sub_menus"		=> array()
						);

						$show_sub_menu = false;

						$sub_menus2 = isset($sbm1['sub_menus']) ? $sbm1['sub_menus'] : null;

						if($sub_menus2) {
							$dropdownCSS = "dropdown-menu sub-menu-level1";
							for($sm2ix = 0, $sm2Count = count($sub_menus2); $sm2ix < $sm2Count; $sm2ix++) {
								$sbm2 = $sub_menus2[$sm2ix];
								
								/* Dependency Check from Main Menu */
								$dependency_pass = $this->check_menu_dependency( $sbm2 );
								if(!$dependency_pass)
									continue;
								/* Dependency Check Ends */

								$show_sub_menu 	= true;
								$show_main_menu = true;

								$temp_sub_menu_2 = array(
									'link'		=> $sbm2['link'],
									'clickfn'	=> isset($sbm2['clickfn']) ? $sbm2['clickfn'] : "",
									'menu_text'	=> $sbm2['text']
								);

								array_push($temp_sub_menu_1["sub_menus"], $temp_sub_menu_2);
							}
						} else {
							$show_sub_menu 	= true;
							$show_main_menu = true;
						}
						if($show_sub_menu && $show_main_menu) {
							array_push($temp_menu_to_show["sub_menus"], $temp_sub_menu_1);
						}
					}
				} else {
					$show_main_menu = true;
				}
				if( $show_main_menu ) {
					array_push($menus_to_show, $temp_menu_to_show);
				}
			}
		}
		return json_encode($menus_to_show);
	}

	public function getNews_content() {
		$this->CI->load->model('web_contents/model_home_content');

		$getParams = array(
			"dataFor" => "all"
		);
		$newsData = $this->CI->model_home_content->getNewsData();

		return $newsData;
	}

	public function getResource_content() {
		$this->CI->load->model('web_contents/model_home_content');

		$getParams = array(
			"dataFor" => "all"
		);
		$newsData = $this->CI->model_home_content->getResourceData();

		return $newsData;
	}

	public function getDiscountData() {

		/*$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_trades');
		$this->load->model('service_providers/model_discounts');
		
		$contractor_id	= "";
		$sub_trade_id 	= "";
		$main_trade_id 	= "";
		$trade_name 	= "";

		$getParams = array(
			"contractor_id" => $contractor_id
		);

		// * Get Trades * /
		//$tradesListResponse = $this->model_trades->getTradesList( $getParams );
		//$tradesList 		= $this->convertTradesDBToId($tradesListResponse["tradesList"]);

		// * Get Main Trade and Sub trade Values from databases * /
		$main_trades_response = $this->model_trades->getMainTradeList("all");
		$main_trades = null;
		if($main_trades_response["status"] == "success") {
			$main_trades = $main_trades_response["mainTradesList"];
			$main_trades 	= $this->_mapMainTradeToId( $main_trades );
		}

		// Get Sub trade list Trades
		$options = array(
			"sub_trade_id" 		=> "all",
			"parent_trade_id"	=> "all",
			"trade_for"			=> "sub",
			"contractor_id"		=> $contractor_id
		);
		$sub_trades_response = $this->model_trades->getSubTradeList($options);

		$sub_trades_list = [];
		if($sub_trades_response["status"] == "success") {
			$sub_trades = $sub_trades_response["subTradesList"];
			$sub_trades = $this->_mapSubTradeToId( $sub_trades );
		}

		// * Get Discount List * /
		$response = $this->model_discounts->getDiscountList($getParams);

		$params = array(
			"sub_trade_id"		=> $sub_trade_id,
			"main_trade_id"		=> $main_trade_id,
			"contractor_id"		=> $contractor_id,
			"trade_name"		=> $trade_name,
			"tradesList"		=> $tradesList,
			"main_trades"		=> $main_trades,
			"sub_trades"		=> $sub_trades
		);

		if($response["status"] == "success") {
			$params['discountList'] = $response["discountList"];
		}*/
	}
	
	/* Function Ends */
}
?>