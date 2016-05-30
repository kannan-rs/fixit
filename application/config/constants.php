<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|---------------------------------------------------------
| PERMISSIONS MODULE CONSTANTS
|---------------------------------------------------------
*/
/*
| Operations Constants
*/
define('OPERATION_CREATE',		'create');
define('OPERATION_UPDATE',		'update');
define('OPERATION_DELETE',		'delete');
define('OPERATION_VIEW',		'view');
define('OPERATION_DEACTIVATE',	'deactivate');
define('OPERATION_ACTIVATE',	'activate');
define('OPERATION_EXPORT',		'export');
define('OPERATION_CHOOSE',		'select / choose');

/*
| Module Constants
*/
define('FUNCTION_ALL',							'all');
define('FUNCTION_CUSTOMER',						'customer');
define('FUNCTION_PARTNER',						'partner');
define('FUNCTION_CLAIM',						'claim');
define('FUNCTION_CLAIM_DOCS',					'Claim Docs');
define('FUNCTION_CLAIM_DAIRY_UPDATES',			'Claim Dairy Updates');
define('FUNCTION_CLAIM_NOTES',					'Claim Notes');
define('FUNCTION_CLAIM_SUBROGATION',			'claim subrogation');
define('FUNCTION_SERVICE_PROVIDER',				'service provider');
define('FUNCTION_SERVICE_PROVIDER_ADS',			'service provider ads');
define('FUNCTION_SERVICE_PROVIDER_DISCOUNT',	'service provider discounts');
define('FUNCTION_SERVICE_PROVIDER_TESTIMONIAL',	'service provider testimonial');
define('FUNCTION_SERVICE_PROVIDER_TRADE',		'service provider trade');
define('FUNCTION_INSURANCE_COMPANY',			'insurance company');
define('FUNCTION_TASKS',						'tasks');
define('FUNCTION_ISSUES',						'issues');
define('FUNCTION_PROJECTS',						'projects');
define('FUNCTION_USERS',						'users');
define('FUNCTION_DOCS',							'docs');
define('FUNCTION_NOTES',						'notes');
define('FUNCTION_BUDGET',						'budget');

/*
| Role Constants
*/
define('ROLE_ADMIN', 							'admin');
define('ROLE_SUB_ADMIN', 						'sub admin');
define('ROLE_INSURANCECO_CALL_CENTER_AGENT', 	'insuranceco call center agent');
define('ROLE_INSURANCECO_ADMIN', 				'insuranceco admin');
define('ROLE_INSURANCECO_USER', 				'insuranceco user');
define('ROLE_CUSTOMER', 						'customer');
define('ROLE_SERVICE_PROVIDER_ADMIN', 			'service provider admin');
define('ROLE_SERVICE_PROVIDER_USER',			'service provider user');
define('ROLE_PARTNER_ADMIN', 					'partner admin');

/*
| Data Filter constant
*/
define('DATA_FILTER_ALL', 						'all');
define('DATA_FILTER_ASSIGNED_INSURANCE_CO', 	'assigned insurance co');
define('DATA_FILTER_ASSIGNED_PROJECTS', 		'assigned projects');
define('DATA_FILTER_INSURANCE_CO_USERS', 		'insuranceco users');
define('DATA_FILTER_SELF', 						'self');
define('DATA_FILTER_SERVICE_PROVIDER', 			'service provider');
define('DATA_FILTER_SERVICE_PROVIDER_CLAIMS',	'service provider claims');
define('DATA_FILTER_SERVICE_PROVIDER_USERS', 	'service provider users');
define('DATA_FILTER_PARTNER_USERS', 			'partner users');

/*
| Role to page mapping
*/
define('ADMIN_PAGE', 							'security');
define('SUB_ADMIN_PAGE', 						'projects');
define('INSURANCECO_CALL_CENTER_AGENT_PAGE', 	'partners');
define('INSURANCECO_ADMIN_PAGE', 				'partners');
define('CUSTOMER_PAGE', 						'projects');
define('SERVICE_PROVIDER_ADMIN_PAGE', 			'service_providers');
define('SERVICE_PROVIDER_USER_PAGE', 			'service_providers');

/*
| Menus
*/
define('TOP_MENUS', '[
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
		"text" 			: "Admin",
		"link" 			: "/main/security",
		"is_logged_in" 	: 1,
		"sub_menus"		: [
			{
				"text"			: "Users", 
				"link"			: "/main/security/users/viewall", 
				"key"			: "users",
				"sub_menus"	: [
					{
						"text"			: "View Users", 
						"link"			: "/main/security/users/viewall", 
						"key"			: "users",
						"dependency"	: {
							"permissions"	: "userPermission",
							"operation"		: ["view"]
						}
					},
					{
						"text"			: "Create User", 
						"link" 			: "/main/security/users/create_user",
						"key"			: "users",
						"dependency"	: {
							"permissions"	: "userPermission",
							"operation"		: ["create"]
						}
					}
				]
			},
			{
				"text"			: "Roles", 
				"link"			: "/main/security/roles/viewall", 
				"key"			: "roles",
				"dependency"	: {
					"roles_by_name"	: ["'.ROLE_ADMIN.'"]
				},
				"sub_menus"	: [
					{
						"text"			: "View Roles", 
						"link"			: "/main/security/roles/viewall", 
						"key"			: "roles",
						"dependency"	: {
							"roles_by_name"	: ["'.ROLE_ADMIN.'"]
						}
					},
					{
						"text"			: "Create Role", 
						"link" 			: "/main/security/roles/create_role",
						"key"			: "roles",
						"dependency"	: {
							"roles_by_name"	: ["'.ROLE_ADMIN.'"]
						}
					}
				]
			},
			{
				"text"			: "Functions", 
				"link"			: "/main/security/functions/viewall", 
				"key"			: "functions",
				"dependency"	: {
					"roles_by_name"	: ["'.ROLE_ADMIN.'"]
				},
				"sub_menus"	: [
					{
						"text"			: "View Functions", 
						"link"			: "/main/security/functions/viewall", 
						"key"			: "functions",
						"dependency"	: {
							"roles_by_name"	: ["'.ROLE_ADMIN.'"]
						}
					},
					{
						"text"			: "Create Function", 
						"link"			: "/main/security/functions/create_function", 
						"key"			: "functions",
						"dependency"	: {
							"roles_by_name"	: ["'.ROLE_ADMIN.'"]
						}
					}
				]
			},
			{
				"text"			: "Operations", 
				"link"			: "/main/security/operations/viewall", 
				"key"			: "operations",
				"dependency"	: {
					"roles_by_name"	: ["'.ROLE_ADMIN.'"]
				},
				"sub_menus"	: [
					{
						"text"			: "View Operations", 
						"link"			: "/main/security/operations/viewall", 
						"key"			: "operations",
						"dependency"	: {
							"roles_by_name"	: ["'.ROLE_ADMIN.'"]
						}
					},
					{
						"text"			: "Create Operation", 
						"link"			: "/main/security/operations/create_operation", 
						"key"			: "operations",
						"dependency"	: {
							"roles_by_name"	: ["'.ROLE_ADMIN.'"]
						}
					}
				]
			},
			{
				"text"			: "Data Filters", 
				"link"			: "/main/security/data_filters/viewall", 
				"key"			: "data_filters",
				"dependency"	: {
					"roles_by_name"	: ["'.ROLE_ADMIN.'"]
				},
				"sub_menus"	: [
					{
						"text"			: "View Data Filters", 
						"link"			: "/main/security/data_filters/viewall", 
						"key"			: "data_filters",
						"dependency"	: {
							"roles_by_name"	: ["'.ROLE_ADMIN.'"]
						}
					},
					{
						"text"			: "Create Data Filter", 
						"link"			: "/main/security/data_filters/create_data_filter", 
						"key"			: "data_filters",
						"dependency"	: {
							"roles_by_name"	: ["'.ROLE_ADMIN.'"]
						}
					}
				]
			},
			{
				"text"			: "Permissions", 
				"link"			: "/main/security/permissions", 
				"key"			: "permissions",
				"dependency"	: {
					"roles_by_name"	: ["'.ROLE_ADMIN.'"]
				}
			},
			{
				"text"			: "Home Page Content", 
				"link"			: "/main/web_contents/home_page", 
				"key"			: "permissions",
				"dependency"	: {
					"roles_by_name"	: ["'.ROLE_ADMIN.'"]
				}
			}
		]
	},
	{
		"text" 			: "Projects",
		"link" 			: "/main/projects/projects",
		"is_logged_in" 	: 1,
		"dependency"	: {
			"permissions"	: "projectPermission",
			"operation"		: ["view","create"]
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
		"text" 			: "Insurance Company",
		"link" 			: "/main/insurance_company",
		"is_logged_in" 	: 1,
		"dependency"	: {
			"permissions"	: "insCompPermission",
			"operation"		: ["create", "view"]
		},
		"sub_menus"		: [
			{
				"text"			: "Insurance Company List", 
				"link"			: "/main/insurance_company", 
				"key" 			: "projects",
				"dependency"	: {
					"permissions"	: "insCompPermission",
					"operation"		: ["view"]
				}
			},
			{
				"text"			: "Create Insurance Company", 
				"link"			: "/main/insurance_company/create_insurance_company", 
				"key" 			: "create_project",
				"dependency"	: {
					"permissions"	: "insCompPermission",
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
]');

define("DEFAULT_PAGE_IF_NOT_LOGGED_IN", "index");
define("DEFAULT_PAGE_IF_LOGGED_IN", "projects");
define("PAGE_INDEX", "index");
define("PAGE_SIGNUP", "signup");

/* Location: ./application/config/constants.php */

/* // Partner Menu next to insurance company
	{
		"text" 			: "Partners",
		"link" 			: "/main/adjusters",
		"is_logged_in" 	: 1,
		"dependency"	: {
			"permissions"	: "adjusterPermission",
			"operation"		: ["view"]
		},
		"sub_menus"		: [
			{
				"text"			: "Partners List", 
				"link"			: "/main/adjusters", 
				"key" 			: "projects",
				"dependency"	: {
					"permissions"	: "adjusterPermission",
					"operation"		: ["view"]
				}
			},
			{
				"text"			: "Create Partner", 
				"link"			: "/main/adjusters/create_partner", 
				"key" 			: "create_project",
				"dependency"	: {
					"permissions"	: "adjusterPermission",
					"operation"		: ["create"]
				}
			}
		]
	},
*/

/* End of file constants.php */
