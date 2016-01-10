<?php
$menu_json_str = '[
	{
		"text" 			: "Home",
		"link" 			: "/main/index",
		"is_logged_in" 	: 0
	},
	{
		"text" 			: "Home",
		"link" 			: "/main/index",
		"is_logged_in" 	: 1,
		"sub_menus" 		: [
			{
				"text" 			: "View My Details", 
				"link"			: "/main/home/view_my_details", 
				"key" 			: "view_my_details",
				"is_logged_in" 	: 1
			},
			{
				"text" 			: "Change Password", 
				"link" 			: "/main/home/change_pass_form", 
				"key" 			: "change_pass_form",
				"is_logged_in" 	: 1
			}
		]
	},
	{
		"text" 			: "Overview",
		"link" 			: "/main/overview",
		"is_logged_in" 	: 0
	},
	{
		"text" 			: "FAQ",
		"link" 			: "/main/faq",
		"is_logged_in" 	: 0
	},
	{
		"text" 			: "About Us",
		"link" 			: "/main/about_us",
		"is_logged_in" 	: 0
	},
	{
		"text" 			: "Contact Us",
		"link" 			: "/main/contact_us",
		"is_logged_in" 	: 0
	},
	{
		"text" 			: "Security",
		"link" 			: "/main/security",
		"is_logged_in" 	: 1,
		"dependency"	: {
			"roles_by_name"	: ["admin"]
		},
		"sub_menus"		: [
			{
				"text"			: "Users", 
				"link"			: "/main/security/users", 
				"key"			: "users",
				"dependency"	: {
					"roles_by_name"	: ["admin"]
				},
				"sub_menus"	: [
					{
						"text"			: "View Users", 
						"link"			: "/main/security/users", 
						"key"			: "users",
						"dependency"	: {
							"roles_by_name"	: ["admin"]
						}
					},
					{
						"text"			: "Create User", 
						"link" 			: "/main/security/users/create_user",
						"key"			: "users",
						"dependency"	: {
							"roles_by_name"	: ["admin"]
						}
					}
				]
			},
			{
				"text"			: "Roles", 
				"link"			: "/main/security/roles", 
				"key"			: "roles",
				"dependency"	: {
					"roles_by_name"	: ["admin"]
				},
				"sub_menus"	: [
					{
						"text"			: "View Roles", 
						"link"			: "/main/security/roles", 
						"key"			: "roles",
						"dependency"	: {
							"roles_by_name"	: ["admin"]
						}
					},
					{
						"text"			: "Create Role", 
						"link" 			: "/main/security/roles/create_role",
						"key"			: "roles",
						"dependency"	: {
							"roles_by_name"	: ["admin"]
						}
					}
				]
			},
			{
				"text"			: "Operations", 
				"link"			: "/main/security/operations", 
				"key"			: "operations",
				"dependency"	: {
					"roles_by_name"	: ["admin"]
				},
				"sub_menus"	: [
					{
						"text"			: "View Operations", 
						"link"			: "/main/security/operations", 
						"key"			: "operations",
						"dependency"	: {
							"roles_by_name"	: ["admin"]
						}
					},
					{
						"text"			: "Create Operation", 
						"link"			: "/main/security/operations/create_operation", 
						"key"			: "operations",
						"dependency"	: {
							"roles_by_name"	: ["admin"]
						}
					}
				]
			},
			{
				"text"			: "Functions", 
				"link"			: "/main/security/functions", 
				"key"			: "functions",
				"dependency"	: {
					"roles_by_name"	: ["admin"]
				},
				"sub_menus"	: [
					{
						"text"			: "View Functions", 
						"link"			: "/main/security/functions", 
						"key"			: "functions",
						"dependency"	: {
							"roles_by_name"	: ["admin"]
						}
					},
					{
						"text"			: "Create Function", 
						"link"			: "/main/security/functions/create_function", 
						"key"			: "functions",
						"dependency"	: {
							"roles_by_name"	: ["admin"]
						}
					}
				]
			},
			{
				"text"			: "Data Filters", 
				"link"			: "/main/security/data_filters", 
				"key"			: "data_filters",
				"dependency"	: {
					"roles_by_name"	: ["admin"]
				},
				"sub_menus"	: [
					{
						"text"			: "View Data Filters", 
						"link"			: "/main/security/data_filters", 
						"key"			: "data_filters",
						"dependency"	: {
							"roles_by_name"	: ["admin"]
						}
					},
					{
						"text"			: "Create Data Filter", 
						"link"			: "/main/security/data_filters/create_data_filter", 
						"key"			: "data_filters",
						"dependency"	: {
							"roles_by_name"	: ["admin"]
						}
					}
				]
			},
			{
				"text"			: "Permissions", 
				"link"			: "/main/security/permissions", 
				"key"			: "permissions",
				"dependency"	: {
					"roles_by_name"	: ["admin"]
				}
			}
		]
	},
	{
		"text" 			: "Projects",
		"link" 			: "/main/projects",
		"is_logged_in" 	: 1,
		"dependency"	: {
			"permissions"	: "projectPermission",
			"operation"		: ["create"]
		},
		"sub_menus"		: [
			{
				"text"			: "View Projects", 
				"link"			: "/main/projects/projects", 
				"key" 			: "projects",
				"dependency"	: {
					"permissions"	: "projectPermission",
					"operation"		: ["view"]
				}
			},
			{
				"text"			: "Create Project", 
				"link"			: "/main/projects/create_project", 
				"key" 			: "create_project",
				"dependency"	: {
					"permissions"	: "projectPermission",
					"operation"		: ["create"]
				}
			}
		]
	},
	{
		"text" 			: "Service Providers",
		"link" 			: "/main/service_providers",
		"is_logged_in" 	: 1,
		"dependency"	: {
			"permissions"	: "contractorPermission",
			"operation"		: ["create"]
		},
		"sub_menus"		: [
			{
				"text"			: "Service Providers List", 
				"link"			: "/main/service_providers", 
				"key" 			: "projects",
				"dependency"	: {
					"permissions"	: "contractorPermission",
					"operation"		: ["view"]
				}
			},
			{
				"text"			: "Create Service Provider", 
				"link"			: "/main/service_providers/create_contractor", 
				"key" 			: "create_project",
				"dependency"	: {
					"permissions"	: "contractorPermission",
					"operation"		: ["create"]
				}
			}
		]
	},
	{
		"text" 			: "Adjusters",
		"link" 			: "/main/adjusters",
		"is_logged_in" 	: 1,
		"dependency"	: {
			"permissions"	: "adjusterPermission",
			"operation"		: ["view"]
		},
		"sub_menus"		: [
			{
				"text"			: "Adjusters List", 
				"link"			: "/main/adjusters", 
				"key" 			: "projects",
				"dependency"	: {
					"permissions"	: "adjusterPermission",
					"operation"		: ["view"]
				}
			},
			{
				"text"			: "Create Adjuster", 
				"link"			: "/main/adjusters/create_partner", 
				"key" 			: "create_project",
				"dependency"	: {
					"permissions"	: "adjusterPermission",
					"operation"		: ["create"]
				}
			}
		]
	},
	{
		"text" 			: "Claims",
		"link" 			: "/main/claims",
		"is_logged_in" 	: 1,
		"dependency"	: {
			"permissions"	: "claimPermission",
			"operation"		: ["view"]
		},
		"sub_menus"		: [
			{
				"text"			: "Claims List", 
				"link"			: "/main/claims", 
				"key" 			: "projects",
				"dependency"	: {
					"permissions"	: "claimPermission",
					"operation"		: ["view"]
				}
			},
			{
				"text"			: "Create Claim", 
				"link"			: "/main/claims/create_claim", 
				"key" 			: "create_claim",
				"dependency"	: {
					"permissions"	: "claimPermission",
					"operation"		: ["create"]
				}
			}
		]
	}
]';

$menus = json_decode($menu_json_str, true);

$is_logged_in = (isset($is_logged_in) && $is_logged_in === 1) ? 1 : 0;

$controller 	= $this->session->userdata("controller");
$page 			= $this->session->userdata("page");
$module			= $this->session->userdata("module");
$role_id 		= $this->session->userdata("role_id");

?>

<div class="nav_top">
	<div class="container">
		<div class="navbar navbar-static-top">
			<div class="navigation">
				<nav>
					<ul class="nav topnav">
						<!-- MENU -->
						<?php

						for($menuIdx = 0; $menuIdx < count($menus); $menuIdx++) {
							$selected = "";

							$menu = $menus[$menuIdx];
							if(isset($menu['dependency'])) {
								$dependency = $menu['dependency'];
								if(isset($dependency['roles_by_name']) && isset($role_disp_name) && !in_array($role_disp_name, $dependency['roles_by_name'])) {
									continue;
								}

								if(isset($dependency['permissions']) && isset($dependency['operation'])) {
									$permissionKey 		= $dependency['permissions'];
									$allowedPermission 	= isset($$permissionKey) ? $$permissionKey : null;
									$operation 			= $dependency['operation'];
									$showMenu 			= false;

									if(isset($allowedPermission)) {
										for($opIx = 0; $opIx < count($operation); $opIx++) {
											if(in_array($operation[$opIx], $allowedPermission['operation'])) {
												$showMenu = true;
												break;
											}
										}
									}
									if(!$showMenu) {
										continue;
									}
								}
							}
							if( strpos(preg_replace('/\s+/', '', strtolower($menu["link"])), $page) ) {
								$selected = 'active';
							}
							if( ($page == "home" || $page == "signup")  && strpos(preg_replace('/\s+/', '', strtolower($menu["link"])), "index")) {
								$selected = 'active';	
							}
							
							if($menu['is_logged_in'] === 'all' || $menu['is_logged_in'] === $is_logged_in) {
								$sub_menus = isset($menu['sub_menus']) ? $menu['sub_menus'] : null;
								$dropdownCSS 	= $sub_menus ? " dropdown" : "";
								$angleIconItag 	= $sub_menus ? "<i class='icon-angle-down'></i>" : "";
								echo '<li class="'.$selected.$dropdownCSS.'">';
								echo '<a href="'. (!$sub_menus && isset($menu["link"])? $menu["link"] : "javascript:void(0);") .'">';
								echo $menu["text"] .$angleIconItag;
								echo '</a>';

								if($sub_menus) {
									echo "<ul class='dropdown-menu'>";
									for($subMenuIdx = 0, $subMenuCount = count($sub_menus); $subMenuIdx < $subMenuCount; $subMenuIdx++) {
										$sbm1	= $sub_menus[$subMenuIdx];
										if(isset($sbm1['dependency'])) {
											$dependency = $sbm1['dependency'];
											if(isset($dependency['roles_by_name']) && isset($role_disp_name) && !in_array($role_disp_name, $dependency['roles_by_name'])) {
												continue;
											}

											if(isset($dependency['permissions']) && isset($dependency['operation'])) {
												$permissionKey 		= $dependency['permissions'];
												$allowedPermission 	= $$permissionKey;
												$operation 			= $dependency['operation'];
												$showMenu 			= false;

												for($opIx = 0; $opIx < count($operation); $opIx++) {
													if(in_array($operation[$opIx], $allowedPermission['operation'])) {
														$showMenu = true;
														break;
													}
												}
												if(!$showMenu) {
													continue;
												}
											}
										}

										$sub_menus2 = isset($sbm1['sub_menus']) ? $sbm1['sub_menus'] : null;

										echo '<li class="'.($sub_menus2 ? 'dropdown' : '').'">';
										echo '<a href="'. (!$sub_menus2 && isset($sbm1["link"]) ? $sbm1["link"] : "javascript:void(0);") .'">';
										echo $sbm1["text"].($sub_menus2 ? "<i class=\"icon-angle-right\"></i>" : "");
										echo '</a>';

										$sub_menus2 = isset($sbm1['sub_menus']) ? $sbm1['sub_menus'] : null;

										if($sub_menus2) {
											$dropdownCSS = "dropdown-menu sub-menu-level1";
											echo "<ul class='".$dropdownCSS."'>";
											for($sm2ix = 0, $sm2Count = count($sub_menus2); $sm2ix < $sm2Count; $sm2ix++) {
												$sbm2 = $sub_menus2[$sm2ix];
												if(isset($sbm2['dependency'])) {
													$dependency = $sbm2['dependency'];
													if(isset($dependency['roles_by_name']) && isset($role_disp_name) && !in_array($role_disp_name, $dependency['roles_by_name'])) {
														continue;
													}

													if(isset($dependency['permissions']) && isset($dependency['operation'])) {
														$permissionKey 		= $dependency['permissions'];
														$allowedPermission 	= $$permissionKey;
														$operation 			= $dependency['operation'];
														$showMenu 			= false;

														for($opIx = 0; $opIx < count($operation); $opIx++) {
															if(in_array($operation[$opIx], $allowedPermission['operation'])) {
																$showMenu = true;
																break;
															}
														}
														if(!$showMenu) {
															continue;
														}
													}
												}
												echo "<li><a href='".(isset($sbm2['link']) ? $sbm2['link'] : "javascript:void(0)")."'";
												echo isset($sbm2['clickfn']) ? " onclick = '".$sbm2['clickfn']."' " : "";
												echo ">".$sbm2['text']."</a></li>";
											}
											echo "</ul>";
										}
										echo '</li>';
									}
									echo "</ul>";
								}

								echo '</li>';
							}
						}
						?>
						<!-- END MENU -->
					</ul>
				</nav>
			</div>
		</div>
	</div>
</div>