<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_controller {

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

		$this->session->userdata("module", $module);
	}

	private function _getNotificationText( $status = '', $responseType = '', $user_details = array()) 
	{
		$patterns = array();
		$patterns[0] = '/#first_name#/';
		$patterns[1] = '/#last_name#/';

		$replacements = array();
		if(count($user_details)) {
			$replacements[1] = $user_details[0]->first_name;
			$replacements[0] = $user_details[0]->last_name;
		}

		$statusText =  preg_replace($patterns, $replacements, $this->lang->line($status.'_user_'.$responseType));

		$noticeParams = array(
			'status' 			=> $status,
			'statusText' 		=> $statusText
		);

		return $this->load->view("forms/notice", $noticeParams, true);
	}

	/**
	| Support function to list all users as table on clicking "security > Users > View Users"
	**/
	public function viewAll() 
	{
		/* Checking for logged in or not */
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		// User > Permissions for logged in User by role_id
		$userPermission = $this->permissions_lib->getPermissions(FUNCTION_USERS);


		/* Checking for page access permission */
		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN && !in_array(OPERATION_VIEW, $userPermission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "users list"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		include 'include_user_model.php';
		$this->load->model('security/model_permissions');
		$this->load->model('security/model_roles');

		$getParams = array(
			"dataFor" => "roles"
		);

		$record 		= $this->input->post('userId') ? $this->input->post('userId') : "";
		$status 		= $this->input->post('status');
		$responseType 	= $this->input->post('responseType');
		$noticeFile 	= '';

		$users = $this->model_users->getUsersList();

		if(!empty($record) && isset($status) && !empty($status) && isset($responseType) && !empty($responseType)) {
			$actionOnUsers 			= $this->model_users->getUsersList($record);
			$actionOnUser_details 	= $this->model_users->getUserDetailsByEmail($users[0]->user_name);
			$noticeFile = $this->_getNotificationText($status, $responseType, $actionOnUser_details);
		}

		// Add Role Display Name for every user
		for($i = 0; $i < count($users); $i++) {
			$users[$i]->role_disp_name = $this->model_roles->get_role_name_by_role_id($users[$i]->role_id);
		}

		$params = array(
			'function'		=> "view",
			'record' 		=> "all",
			'users'			=> $users,
			'noticeFile' 	=> $noticeFile,
			'admin_role_id'	=> $this->model_roles->get_role_id_by_role_name(ROLE_ADMIN)
		);
		
		echo $this->load->view("security/users/viewAll", $params, true);
	}

	public function createForm() 
	{
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$this->load->model('security/model_permissions');

		$getParams = array(
			"dataFor" => "roles"
		);
		$roles = $this->model_permissions->getAllList($getParams)["roles"];

		$addressFile = $this->form_lib->getAddressFile(array("requestFrom" => "input", "view" => "create_user_form"));

		$openAs 		= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 		= $this->input->post('popupType') ? $this->input->post('popupType') : "";
		$belongsTo 		= $this->input->post('belongsTo') ? $this->input->post('belongsTo') : "";
		
		$params = array(
			'role_id'			=> $this->session->userdata('logged_in_role_id'),
			'role_disp_name' 	=> $this->session->userdata('logged_in_role_disp_name'),
			'is_logged_in' 		=> is_logged_in(),
			'addressFile' 		=> $addressFile,
			'openAs' 			=> $openAs,
			'belongsTo' 		=> $belongsTo,
			'popupType' 		=> $popupType,
			'belongsToName' 	=> "-NA-",
			'referredByName'	=> "-NA-",
			'roles'				=> $roles
		);

		echo $this->load->view("security/users/inputForm", $params, true);
	}

	public function add() {
		/*if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}*/

		$response = array(
			'status'	=> "error"
		);

		$userPermission = $this->permissions_lib->getPermissions(FUNCTION_USERS);

		//print_r($userPermission);

		if(is_logged_in() && !in_array(OPERATION_CREATE, $userPermission['operation'])) {
			$response["message"] 			= "No permission to execute this operation";
			print_r(json_encode($response));
			return false;
		}

		include 'include_user_model.php';
		
		$emailId 		= $this->input->post('emailId');
		$userStatus 	= $this->input->post('userStatus');
		$referredBy 	= $this->input->post("referredBy");
		$referredById 	= $this->input->post("referredById");
		$password 		= $this->input->post('password');
		$tc 			= $this->input->post('tc');
		$activationKey 	= md5($emailId."-".$password);

		if(!$this->session->userdata('logged_in_user_id') && (!isset($tc) || empty($tc))) {
			$response["status"] 			= "error";
			$response["message"] 			= "Please accept terms and condition";
			print_r(json_encode($response));
			exit();
		}

		$belongsTo 			= "";
		$belongsToId 		= $this->input->post("belongsToId");
		$role_id 			= $this->input->post('privilege');
		$logged_in_user_id 	= $this->session->userdata('logged_in_user_id');

		$logged_in_role_disp_name = $this->session->userdata('logged_in_role_disp_name');
		$this->load->model('security/model_roles');
		if(is_logged_in() && $logged_in_role_disp_name == ROLE_SERVICE_PROVIDER_ADMIN) {
			
			$belongsToId	= $this->model_service_providers->get_contractor_company_id_by_user_id($logged_in_user_id);
			$role_id 	= $this->model_roles->get_role_id_by_role_name(ROLE_SERVICE_PROVIDER_USER);
		
		} else if(is_logged_in() && $logged_in_role_disp_name == ROLE_INSURANCECO_ADMIN) {
			
			$belongsToId	= $this->model_ins_comp->get_ins_comp_id_by_user_id($logged_in_user_id);
			$role_id 	= $this->model_roles->get_role_id_by_role_name(ROLE_INSURANCECO_USER);
		
		}  else if(is_logged_in() && $logged_in_role_disp_name == ROLE_PARTNER_ADMIN) {
			
			$belongsToId	= $this->model_partners->get_partner_company_id_by_user_id($logged_in_user_id);
			$role_id 	= $this->model_roles->get_role_id_by_role_name(ROLE_PARTNER_USER);
		
		}

		if($emailId && !$this->model_users->getUserSnoViaEmail($emailId)) {
			$createUser_data = array(
				'first_name' 			=> $this->input->post('firstName'),
				'last_name' 			=> $this->input->post('lastName'),
				'belongs_to' 			=> "",
				'belongs_to_id' 		=> $belongsToId,
				'referred_by' 			=> $referredBy,
				'referred_by_id' 		=> $referredById,
				'status' 				=> $userStatus,
				'active_start_date' 	=> date("Y-m-d H:i:s"),
				'email' 				=> $emailId,
				'contact_ph1' 			=> $this->input->post('contactPhoneNumber'),
				'contact_mobile' 		=> $this->input->post('mobileNumber'),
				'contact_alt_mobile'	=> $this->input->post('altNumber'),
				'primary_contact'		=> $this->input->post('primaryContact'),
				'address1' 				=> $this->input->post('addressLine1'),
				'address2' 				=> $this->input->post('addressLine2'),
				'city' 					=> $this->input->post('city'),
				'state' 				=> $this->input->post('state'),
				'country' 				=> $this->input->post('country'),
				'zip_code'				=> $this->input->post('zipCode'),
				'contact_pref' 			=> $this->input->post('prefContact'),
				'created_dt' 			=> date("Y-m-d H:i:s"),
				'last_updated_dt' 		=> date("Y-m-d H:i:s"),
				'created_by'			=> $this->session->userdata('logged_in_user_id'),
				'updated_by'			=> $this->session->userdata('logged_in_user_id')
			);

			$inserted = $this->model_users->insertUserDetails($createUser_data);
			if($inserted["status"] == "success") {
				$loginTableUser_data = array(
					'user_name' 			=> $emailId, 
					'password' 				=> md5($password),
					'password_hint' 		=> $this->input->post('passwordHint'),
					'role_id' 				=> $role_id,
					'activation_key' 		=> $activationKey,
					'status' 				=> $userStatus,
					'created_by'			=> $this->session->userdata('logged_in_user_id'),
					'updated_by'			=> $this->session->userdata('logged_in_user_id'),
					'created_date'			=> date("Y-m-d H:i:s"),
					'updated_date'			=> date("Y-m-d H:i:s")
				);

				$inserted_login = $this->model_users->insertUsers($loginTableUser_data);
				
				if($inserted_login['status'] == "success") {
					$record 				= $this->db->insert_id();

					$response["status"] 	= "success";
					$response["message"] 	= "User Added Successfully";
					$response["emailId"] 	= $emailId;
					$response["insertedId"]	= $record;


					$user_details_record 	= $this->model_users->getUserDetailsBySno($inserted['record']);
					$user_details 			= $this->model_users->getUsersList($inserted_login["record"]);
					
					$userParamsFormMail = array(
						'response'				=> $response,
						'user_details_record'	=> $user_details_record,
						'user_record' 			=> $user_details,
						'activationKey' 		=> $activationKey,
						'responseType' 			=> 'add',
						'status' 				=> $response["status"]
					);
					
					$mail_options = $this->model_mail->generateCreateUserMailOptions( $userParamsFormMail );

						$response['mail_content'] = $mail_options;

						file_put_contents($_SERVER['DOCUMENT_ROOT']."/email_log.html", "In Else--\n", FILE_APPEND | LOCK_EX);

						$response["mail_error"] = $this->model_mail->sendMail( $mail_options );

					/*
						Reference Email
					*/
					if(!empty($referredBy) && $referredBy != "customer") {
						$userReferredByParamsFormMail = array(
							'response'				=> $response,
							'user_details_record'	=> $user_details_record,
							'user_record' 			=> $user_details,
							'referredBy' 			=> $referredBy
						);
						if($referredBy == "contractor") {
							$userReferredByParamsFormMail['referredByDetails'] = $this->model_service_providers->get_service_provider_list($referredById)["contractors"];
						} else if( $referredBy == "adjuster") {
							$userReferredByParamsFormMail['referredByDetails'] = $this->model_partners->getPartnersList($referredById)["parrtners"];
						}
						$mail_options = $this->model_mail->generateCreateuserReferredByMailOptions( $userReferredByParamsFormMail );
						
						$response['mail_content_referred'] = $mail_options;
						
						$response["mail_error"] = $this->model_mail->sendMail( $mail_options );
						
					}

					if(!is_logged_in()) {
						$noticeFile = $this->_getNotificationText($response["status"], 'add', $user_details_record);
						$createConfirmPageParams = array(
							'user_details' 	=> $user_details_record,
							'noticeFile' 	=> $noticeFile
						);
						$response["createConfirmPage"] = $this->load->view("security/users/createConfirmationPage", $createConfirmPageParams, true);
					}

				} else {
					$response["status"] 	= "error";
					$response["message"] 	= $inserted_login["message"];		
				}
			} else {
				$response["status"] 		= "error";
				$response["message"] 		= $inserted["message"];	
			}
		} else {
			$response["status"] 			= "error";
			$response["message"] 			= "Email ID already exist";
		}

		print_r(json_encode($response));
	}

	public function editForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		/*print_r($this->session->userdata);
		echo "<br/>User ID-->".$this->input->post('userId');
		echo "<br/>Session ID-->".$this->session->userdata('logged_in_user_id');*/

		$userPermission = $this->permissions_lib->getPermissions(FUNCTION_USERS);//User > Permissions for logged in User by role_id

		/* Checking for page access permission */
		if( $this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN  && !in_array(OPERATION_UPDATE, $userPermission['operation'])) {
			if( $this->session->userdata('page') == 'home' && $this->input->post('userId') == $this->session->userdata('logged_in_user_id')) {
				// no Condition, this need to display edit screen
			} else {
				$no_permission_options = array(
					'page_disp_string' => "edit user"
				);
				echo $this->load->view("pages/no_permission", $no_permission_options, true);
				return false;
			}
		}

		include 'include_user_model.php';
		$this->load->model('security/model_permissions');
		$this->load->model('security/model_roles');

		$getParams = array(
			"dataFor" => "roles"
		);
		$roles = $this->model_permissions->getAllList($getParams)["roles"];

		$record = $this->input->post('userId') ? $this->input->post('userId') : $this->session->userdata('logged_in_user_id');

		$users 			= $this->model_users->getUsersList($record);
		$user_details 	= $this->model_users->getUserDetailsByEmail($users[0]->user_name);

		for($i = 0; $i < count($users); $i++) {
			$users[$i]->role_disp_name = $this->model_roles->get_role_name_by_role_id($users[$i]->role_id);
		}

		$belongsToName = "";

		$user_role_disp_str 	= strtolower($users[0]->role_disp_name);
		$is_service_provider 	= $user_role_disp_str == ROLE_SERVICE_PROVIDER_ADMIN || $user_role_disp_str == ROLE_SERVICE_PROVIDER_USER ? true : false;
		$is_partner 			= $user_role_disp_str == ROLE_PARTNER_ADMIN ? true : false;
		$is_ins_comp 			= $user_role_disp_str == ROLE_INSURANCECO_ADMIN  ? true : false;

		
		if($user_details[0]->belongs_to_id && $is_service_provider ) {
			$contractorsResponse = $this->model_service_providers->get_service_provider_list($user_details[0]->belongs_to_id);
			$contractors = $contractorsResponse["contractors"];
			$belongsToName = count($contractors) ? $contractors[0]->name." from ".$contractors[0]->company : "";
		} else if( $user_details[0]->belongs_to_id && $is_partner ) {
			$adjustersResponse = $this->model_partners->getPartnersList($user_details[0]->belongs_to_id);
			$adjusters 	= $adjustersResponse["partners"];
			$belongsToName = count($adjusters) ? $adjusters[0]->name." from ".$adjusters[0]->company_name : "";
		} 

		$referredByName = "";
		if(!empty($user_details[0]->belongs_to) && $user_details[0]->belongs_to == "contractor") {
			$contractorsResponse = $this->model_service_providers->get_service_provider_list($user_details[0]->referred_by_id);
			$contractors = $contractorsResponse["contractors"];
			$referredByName = count($contractors) ? $contractors[0]->company : "";
		} else if(!empty($user_details[0]->belongs_to) && $user_details[0]->belongs_to == "adjuster") {
			$adjustersResponse = $this->model_partners->getPartnersList($user_details[0]->referred_by_id);
			$adjusters 	= $adjustersResponse["partners"];
			$referredByName = count($adjusters) ? $adjusters[0]->company_name : "";
		}

		$addressFile = $this->form_lib->getAddressFile(array("view" => "update_user_form", "requestFrom" => "input", "address_data" => $user_details[0]));

		$params = array(
			'record' 				=> $record,
			'viewFrom' 				=> $this->input->post('userId') ? "security" : "home",
			'users' 				=> $users,
			'user_details' 			=> $user_details,
			'belongsToName' 		=> isset($belongsToName) && !empty($belongsToName) ? $belongsToName : "-NA-",
			'referredByName' 		=> isset($referredByName) && !empty($referredByName) ? $referredByName : "-NA-",
			'addressFile' 			=> $addressFile,
			'role_id' 				=> $this->session->userdata('logged_in_role_id'),
			'role_disp_name'		=> $this->session->userdata('logged_in_role_disp_name'),
			'is_logged_in' 			=> is_logged_in(),
			'roles'					=> $roles,
			"is_service_provider" 	=> $is_service_provider,
			"is_partner"			=> $is_partner
		);

		echo $this->load->view("security/users/inputForm", $params, true);
	}

	public function update() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$response = array(
			'status'	=> "error"
		);

		$userPermission = $this->permissions_lib->getPermissions(FUNCTION_USERS); //User > Permissions for logged in User by role_id

		/* Checking for page access permission */
		if(!in_array(OPERATION_UPDATE, $userPermission['operation'])) {
			if( $this->session->userdata('page') == 'home' && $this->input->post('userId') == $this->session->userdata('logged_in_user_id')) {
				// no Condition, this need to updateuser details
			} else {
				$response["message"] 	= "No permission to execute this operation";
				print_r(json_encode($response));
				return false;
			}
		}

		include 'include_user_model.php';

		$response = array();

		$active_start_date 		= $this->input->post("activeStartDate");
		$active_end_date 		= $this->input->post("activeEndDate");
		$user_record 			= $this->input->post('userId');
		$privilege 				= $this->input->post('privilege');
		$user_details_record 	= $this->input->post('sno'); 
		$firstName 				= $this->input->post('firstName');
		$lastName 				= $this->input->post('lastName'); 
		$belongsTo 				= $this->input->post('belongsTo');
		$belongsToId 			= $this->input->post("belongsToId");
		$userStatus 			= $this->input->post('userStatus');
		$contactPhoneNumber 	= $this->input->post('contactPhoneNumber');
		$mobileNumber 			= $this->input->post('mobileNumber');
		$altNumber 				= $this->input->post('altNumber');
		$primaryContact			= $this->input->post('primaryContact');
		$prefContact			= $this->input->post('prefContact');
		$addressLine1 			= $this->input->post('addressLine1');
		$addressLine2 			= $this->input->post('addressLine2');
		$city 					= $this->input->post('city');
		$state 					= $this->input->post('state');
		$country 				= $this->input->post('country');
		$zipCode 				= $this->input->post('zipCode');
		$referredBy 			= $this->input->post("referredBy");
		$referredById 			= $this->input->post("referredById");

		$logged_in_user_id 	= $this->session->userdata('logged_in_user_id');

		$logged_in_role_disp_name = $this->session->userdata('logged_in_role_disp_name');
		
		$this->load->model('security/model_roles');

		if(is_logged_in() && $logged_in_role_disp_name == ROLE_SERVICE_PROVIDER_ADMIN) {
		
			$belongsToId	= $this->model_service_providers->get_contractor_company_id_by_user_id($logged_in_user_id);
			$privilege 	= $this->model_roles->get_role_id_by_role_name(ROLE_SERVICE_PROVIDER_USER);
		
		} else if(is_logged_in() && $logged_in_role_disp_name == ROLE_INSURANCECO_ADMIN) {
			
			$belongsToId	= $this->model_ins_comp->get_ins_comp_id_by_user_id($logged_in_user_id);
			$role_id 	= $this->model_roles->get_role_id_by_role_name(ROLE_INSURANCECO_USER);
		
		} else if(is_logged_in() && $logged_in_role_disp_name == ROLE_PARTNER_ADMIN) {
			
			$belongsToId	= $this->model_partners->get_partner_company_id_by_user_id($logged_in_user_id);
			$role_id 	= $this->model_roles->get_role_id_by_role_name(ROLE_PARTNER_USER);
		
		}

		$update_details_data = array(
			'first_name' 			=> $firstName,
			'last_name' 			=> $lastName, 
			'belongs_to' 			=> $belongsTo,
			'belongs_to_id' 		=> $belongsToId,
			'referred_by' 			=> $referredBy,
			'referred_by_id' 		=> $referredById,
			//'status' 				=> $userStatus,
			'contact_ph1' 			=> $contactPhoneNumber,
			'contact_mobile' 		=> $mobileNumber,
			'contact_alt_mobile'	=> $altNumber,
			'primary_contact'		=> $primaryContact,
			'contact_pref'			=> $prefContact,
			'address1' 				=> $addressLine1,
			'address2' 				=> $addressLine2,
			'city' 					=> $city,
			'state' 				=> $state,
			'country' 				=> $country,
			'zip_code'				=> $zipCode,
			'last_updated_dt' 		=> date("Y-m-d H:i:s"),
			'updated_by'			=> $this->session->userdata('logged_in_user_id')
		);

		if( $userStatus ) {
			$update_details_data['status'] = $userStatus;
		}

		if( $active_start_date != "" )
			$update_details_data["active_start_date"] = $active_start_date;

		if( $active_end_date != "" )
			$update_details_data["active_end_date"] = $active_end_date;

		if($referredBy == "" || $referredBy == "customer") {
			$update_details_data["referred_by_id"] = "";
		}

		$update_details = $this->model_users->updateDetailsTable($update_details_data, $user_details_record);

		$update_data = array();

		if($privilege != "") {
			$update_data['role_id'] = $privilege;
			$update = $this->model_users->updateUserTable($update_data, $user_record);
		} else {
			$update["status"] = "success";
		}
		

		if($update["status"] == "success" && $update_details["status"] == "success") {
			$response["status"]		= "success";
			$response["message"] 	= "User details updated successfully";
		} else {
			$response["status"]		= "error";
			$response["message"] 	= "Error while updating the records<br/>".$update["message"]."<br/>".$update_details["message"];
		}

		$userParamsFormMail = array(
			'response'				=> $response,
			'user_details_record'	=> $this->model_users->getUserDetailsBySno($user_details_record),
			'user_record' 			=> $this->model_users->getUsersList($user_record)
		);

		$mail_options = $this->model_mail->generateUpdateUserMailOptions( $userParamsFormMail );

		$response['mail_content'] = $mail_options;
		$response["mail_error"] = $this->model_mail->sendMail( $mail_options );

		print_r(json_encode($response));
	}

	public function deleteRecord() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$response = array(
			'status'	=> "error"
		);

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN ) {
			$response["message"] 			= "No permission to execute this operation";
			print_r(json_encode($response));
			return false;
		}

		include 'include_user_model.php';

		$record = $this->input->post('userId');
		$emailId = $this->input->post('emailId');

		$response = $this->model_users->deleteUser($record);

		if($response["status"] == "success") {
			$response = $this->model_users->deleteUserDetails($emailId);
		}

		$userParamsFormMail = array(
			'response'		=> $response,
			'email_id'		=> $emailId
		);

		$mail_options = $this->model_mail->generateDeleteUserMailOptions( $userParamsFormMail );

		$response['mail_content'] = $mail_options;
		$response["mail_error"] = $this->model_mail->sendMail( $mail_options );

		print_r(json_encode($response));
	}

	public function viewOne() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$userPermission = $this->permissions_lib->getPermissions(FUNCTION_USERS); //User > Permissions for logged in User by role_id

		/*print_r($this->session->userdata);
		echo "<br/>User ID-->".$this->input->post('userId');
		echo "<br/>Session ID-->".$this->session->userdata('logged_in_user_id');*/
		
		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN  && !in_array(OPERATION_VIEW, $userPermission['operation']) && $this->session->userdata('page') != 'home') {
			$no_permission_options = array(
				'page_disp_string' => "user details"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}
		include 'include_user_model.php';
		$this->load->model('security/model_roles');

		$record 		= $this->input->post('userId') ? $this->input->post('userId') : $this->session->userdata('logged_in_user_id');
		$viewFrom 		= $this->input->post('viewFrom') ? $this->input->post('viewFrom') : "home";
		$status 		= $this->input->post('status');
		$responseType 	= $this->input->post('responseType');

		$users 			= $this->model_users->getUsersList($record);
		$user_details 	= $this->model_users->getUserDetailsByEmail($users[0]->user_name);
		$stateDetails 	= $this->model_form_utils->getCountryStatus($user_details[0]->state);

		for($i = 0; $i < count($users); $i++) {
			$users[$i]->role_disp_name = $this->model_roles->get_role_name_by_role_id($users[$i]->role_id);
		}

		$belongsToName = "";

		$user_role_disp_str 	= strtolower($users[0]->role_disp_name);
		$is_service_provider 	= $user_role_disp_str == ROLE_SERVICE_PROVIDER_ADMIN || $user_role_disp_str == ROLE_SERVICE_PROVIDER_USER ? true : false;
		$is_partner 			= $user_role_disp_str == ROLE_PARTNER_ADMIN ? true : false;
		$is_ins_comp 			= $user_role_disp_str == ROLE_INSURANCECO_ADMIN  ? true : false;

		
		if($user_details[0]->belongs_to_id && $is_service_provider ) {
			$contractorsResponse = $this->model_service_providers->get_service_provider_list($user_details[0]->belongs_to_id);
			$contractors = $contractorsResponse["contractors"];
			$belongsToName = count($contractors) ? $contractors[0]->name." from ".$contractors[0]->company : "";
		} else if( $user_details[0]->belongs_to_id && $is_partner ) {
			$adjustersResponse = $this->model_partners->getPartnersList($user_details[0]->belongs_to_id);
			$adjusters 	= $adjustersResponse["partners"];
			$belongsToName = count($adjusters) ? $adjusters[0]->name." from ".$adjusters[0]->company_name : "";
		}

		/* User Referred by */
		$referredByName = "";
		if(!empty($user_details[0]->referred_by_id) && !empty($user_details[0]->referred_by) && $user_details[0]->referred_by != "customer") {
			if($user_details[0]->referred_by == "contractor") {
				$contractorsResponse = $this->model_service_providers->get_service_provider_list($user_details[0]->referred_by_id);
				$contractors = $contractorsResponse["contractors"];
				$referredByName = count($contractors) ? $contractors[0]->name." from ".$contractors[0]->company : "";
			} else if($user_details[0]->referred_by == "adjuster") {
				$adjustersResponse = $this->model_partners->getPartnersList($user_details[0]->referred_by_id);
				$adjusters 	= $adjustersResponse["partners"];
				$referredByName = count($adjusters) ? $adjusters[0]->name." from ".$adjusters[0]->company_name : "";
			}
		}

		$addressFile = $this->form_lib->getAddressFile(array("view" => "view", "address_data" => $user_details[0]));
		
		if(isset($status) && !empty($status) && isset($responseType) && !empty($responseType)) {
			$noticeFile = $this->_getNotificationText($status, $responseType, $user_details);
		}

		// Add Role Display Name for every user
		for($i = 0; $i < count($users); $i++) {
			$users[$i]->role_disp_name = $this->model_roles->get_role_name_by_role_id($users[$i]->role_id);
		}

		$params = array(
			'viewFrom' 				=> $viewFrom,
			'record'				=> $record,
			'users'					=> $users,
			'user_details' 			=> $user_details,
			'state' 				=> $stateDetails,
			'userType' 				=> $this->session->userdata('logged_in_role_id'),
			'addressFile' 			=> $addressFile,
			'belongsToName' 		=> !empty($belongsToName) ? $belongsToName : "-NA-",
			'referredByName' 		=> !empty($referredByName) ? $referredByName : "-NA-",
			'noticeFile' 			=> isset($noticeFile) ? $noticeFile : "",
			"is_service_provider" 	=> $is_service_provider,
			"is_partner"			=> $is_partner
		);
		
		echo $this->load->view("security/users/viewOne", $params, true);
	}

	function _getFromUsersList() {
		$response = array("status" => "error");

		$queryStr 	= "SELECT users.sno, users.user_name, ";
		$queryStr	.= "user_details.email, user_details.first_name, user_details.last_name ";
		$queryStr 	.= "FROM `users` LEFT JOIN `user_details` ON users.user_name = user_details.email where users.is_deleted = 0 AND user_details.is_deleted = 0";


		if(isset($params) && is_array($params)) {
			$emailId	= isset($params["emailId"]) ? $params["emailId"] : "";
			$belongsTo	= isset($params["belongsTo"]) ? $params["belongsTo"] : "";
			$assignment	= isset($params["assignment"]) ? $params["assignment"] : "";
			
			if(!empty($emailId)) {
				$this->db->like('email', $emailId);
				$queryStr .=" AND `email` LIKE '%".$emailId."%'";
			}
			if(!empty($belongsTo)) {
				$belongsToArr = explode("|", $belongsTo);
				$belongsToStr = "";
				for($i = 0; $i < count($belongsToArr); $i++) {
					$belongsToArr[$i] = $belongsToArr[$i] == "empty" ? "" : $belongsToArr[$i];
					$belongsToStr .= $i > 0 ? "," : "";
					$belongsToStr .= "'".$belongsToArr[$i]."'";
				}
				$this->db->where_in('belongs_to', $belongsToArr);
				$queryStr .=" AND `belongs_to` IN (".$belongsToStr.")";
			} else {
				/*$this->db->where('belongs_to', "customer");
				$queryStr .=" AND `belongs_to` = \"customer\"";*/
			}

			if(!empty($assignment)) {
				$assignment = $assignment == "not assigned" ? '0' : $assignment;
				$this->db->where('belongs_to_id', $assignment);
				$queryStr .= " AND `belongs_to_id` = '".$assignment."'";
			}
		} else {
			/*$this->db->where('belongs_to', "customer");
			$queryStr .=" AND `belongs_to` = \"customer\"";*/
		}

		//echo $queryStr;
		
		//$this->db->select(["*"]);
		//$query = $this->db->from('user_details')->get();
		$query = $this->db->query($queryStr);

		//echo $this->db->last_query();
		
		if($this->db->_error_number()) {
			$response['message'] = $this->db->_error_message();	
		} else {
			$response['status']		= "success";
			$response['customer'] 	= $query->result();
		}
		
		return $response;
	}

	public function get_parent_for_user() {
		/* Checking for logged in or not */
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		include 'include_user_model.php';

		$user_id = $this->session->userdata('logged_in_user_id');
		$role_disp_name = $this->session->userdata('logged_in_role_disp_name');

		if($role_disp_name == ROLE_SERVICE_PROVIDER_ADMIN) {
			$parent_id = $this->model_users->get_contractor_company_id_by_user_id( $user_id );
		} else if ($role_disp_name == ROLE_PARTNER_ADMIN) {
			$parent_id = $this->model_users->get_partner_company_id_by_user_id( $user_id );
		}

		$response = array(
			'status' => "success",
			'parent_id' => isset($parent_id) ? $parent_id : ""
		);

		print_r(json_encode($response));
	}

	public function get_logged_in_user_details() {
		/* Checking for logged in or not */
		$response = array();
		if (is_logged_in()) {
			$response = array(
				"email_id" 			=> $this->session->userdata('logged_in_email'),
				"is_logged_in" 		=> $this->session->userdata('is_logged_in'),
				"role_id"			=> $this->session->userdata('logged_in_role_id'),
				"user_id"			=> $this->session->userdata('logged_in_user_id')
			);
		}

		print_r(json_encode($response));
	}
}