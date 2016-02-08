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

/*
| Module Constants
*/
define('FUNCTION_ALL',							'all');
define('FUNCTION_CUSTOMER',						'customer');
define('FUNCTION_ADJUSTER',						'adjuster');
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
define('FUNCTION_INSURANCE_CO',					'insurance co');
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
define('ROLE_CUSTOMER', 						'customer');
define('ROLE_SERVICE_PROVIDER_ADMIN', 			'service provider admin');
define('ROLE_SERVICE_PROVIDER_USER', 			'service provider user');

/* End of file constants.php */
/* Location: ./application/config/constants.php */