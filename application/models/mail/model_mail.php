<?php

class Model_mail extends CI_Model {
	public function generateCreateUserMailOptions( $options ) {
		$response 				= $options['response'];
		$user_details_record 	= $options["user_details_record"];
		$user_record 			= $options["user_record"];
		$activationKey 			= $options["activationKey"];
		$status 				= $options['status'];
		$responseType 			= $options['responseType'];
		
		if($response["status"] == "success") {
			
			$mail_options = array();
			//$mail_options["from"]		= $smtp_user;
			//$mail_options["fromName"]	= $this->lang->line('email_from_name_html');
			$mail_options["to"]			= $user_record[0]->user_name;
			$mail_options["cc"]			="";
			$mail_options["bcc"]		= "";
			$mail_options["subject"]	= $this->lang->line('email_'.$status.'_user_'.$responseType.'_subject');
			$mail_options["signature"] 	= $this->lang->line('email_signature_html');

			$patterns = array();
			$patterns[0] = '/#first_name#/';
			$patterns[1] = '/#last_name#/';
			$patterns[2] = '/#activation_link#/';

			$replacements = array();
			if(count($user_details_record)) {
				$replacements[2] = $user_details_record[0]->first_name;
				$replacements[1] = $user_details_record[0]->last_name;
				$replacements[0] = base_url()."main/activate_user/".$activationKey;
			}

			//email_success_user_add_subject
			//email_success_user_add_message_html
			$mail_options["message"] =  preg_replace($patterns, $replacements, $this->lang->line('email_'.$status.'_user_'.$responseType.'_message_html'));

			$mail_options["message"] 	.= $mail_options["signature"];

			return $mail_options;
		}
	}

	public function generateCreateuserReferredByMailOptions( $options ) {
		$response 				= $options['response'];
		$user_details_record 	= $options["user_details_record"];
		$user_record 			= $options["user_record"];
		$referredBy 			= $options["referredBy"];
		$referredByDetails 		= $options["referredByDetails"];

		$mailNmae 		= "";
		$mailToEmail 	= "";

		if(!empty($referredBy) && $referredBy == "contractor") {
			$mailNmae = $referredByDetails[0]->name." for Company ".$referredByDetails[0]->company;
			$mailToEmail = $referredByDetails[0]->office_email;
		} else if(!empty($referredBy) && $referredBy == "adjuster") {
			$mailNmae = $referredByDetails[0]->name." for Company ".$referredByDetails[0]->company_name;
			$mailToEmail = $referredByDetails[0]->work_email_id;
		}
		
		if($response["status"] == "success") {
			$mail_options = array();
			$mail_options["name"] 		= $mailNmae;
			//$mail_options["from"]		= "admin@thefixitnetwork.com";
			//$mail_options["fromName"]	= "Fixit Admin:";
			$mail_options["to"]			= $mailToEmail;
			$mail_options["cc"]			="";
			$mail_options["bcc"]		= "";
			$mail_options["subject"]	= "Referral Registration to Fixit Network successfully";
			$mail_options["signature"] 	= "<p>Thanks,<br/>Fixit Networks</p>";
			$mail_options["message"] 	= "<p>Dear ".$mail_options["name"]."</p>";
			$mail_options["message"] 	.= "<p>Your referral ".$user_details_record[0]->first_name." ".$user_details_record[0]->last_name."'s' account with fixit network was created successfully.</p>";
			$mail_options["message"] 	.= "<p>Thanks for referring. </p>";
			$mail_options["message"] 	.= $mail_options["signature"];

			return $mail_options;
		}
	}

	public function generateDeleteUserMailOptions ( $options ) {
		$response 		= $options['response'];
		$email_id 		= $options["email_id"];

		$mailNmae 		= "";
		$mailToEmail 	= "";
		
		if($response["status"] == "success") {
			$mail_options = array();
			$mail_options["name"] 		= "User";
			//$mail_options["from"]		= "admin@thefixitnetwork.com";
			//$mail_options["fromName"]	= "Fixit Admin";
			$mail_options["to"]			= $mailToEmail;
			$mail_options["cc"]			="";
			$mail_options["bcc"]		= "";
			$mail_options["subject"]	= "Deletion of account";
			$mail_options["signature"] 	= "<p>Thanks,<br/>Fixit Networks</p>";

			$mail_options["message"] 	= "<p>Dear ".$mail_options["name"].",</p>";
			$mail_options["message"] 	.= "<p>Your account is successfully deleted from our system.</p>";
			$mail_options["message"] 	.= $mail_options["signature"];

			return $mail_options;
		}	
	}

	public function generateUpdateUserMailOptions( $options ) {
		$response 				= $options['response'];
		$user_details_record 	= $options["user_details_record"];
		$user_record 			= $options["user_record"];
		
		if($response["status"] == "success") {
			$mail_options = array();
			$mail_options["name"] 		= $user_details_record[0]->first_name." ".$user_details_record[0]->last_name;
			//$mail_options["from"]		= "admin@thefixitnetwork.com";
			//$mail_options["fromName"]	= "Fixit Admin:";
			$mail_options["to"]			= $user_record[0]->user_name;
			$mail_options["cc"]			="";
			$mail_options["bcc"]		= "";
			$mail_options["subject"]	= "Personal information updateed successfully";
			$mail_options["signature"] 	= "<p>Thanks,<br/>Fixit Networks</p>";
			$mail_options["message"] 	= "<p>Dear ".$mail_options["name"]."</p>";
			$mail_options["message"] 	.= "<p>Your personal information is updated successfully. Please login with you credential to see the latest update</p>";
			$mail_options["message"] 	.= $mail_options["signature"];

			return $mail_options;
		}
	}

	public function generateCreateContractorCompanyMailOptions( $options ) {
		$response 				= $options['response'];
		$contractorData 		= $options["contractorData"];

		if($response["status"] == "success") {
			$mail_options = array();
			$mail_options["name"] 		= $contractorData["company"];
			//$mail_options["from"]		= "admin@thefixitnetwork.com";
			//$mail_options["fromName"]	= "Fixit Admin:";
			$mail_options["to"]			= $contractorData["office_email"];
			$mail_options["cc"]			="";
			$mail_options["bcc"]		= "";
			$mail_options["subject"]	= "Registration Successful for Contractor Company";
			$mail_options["signature"] 	= "<p>Thanks,<br/>Fixit Networks</p>";
			$mail_options["message"] 	= "<p>Dear ".$mail_options["name"]."</p>";
			$mail_options["message"] 	.= "<p>Welcome Contractor company, Your account with fixit network was created successfully.</p>";
			$mail_options["message"] 	.= "<p>personal information is also added successfully. </p>";
			$mail_options["message"] 	.= $mail_options["signature"];

			return $mail_options;
		}
	}

	public function generateUpdateContractorCompanyMailOptions( $options ) {
		$response 				= $options['response'];
		$contractorData 		= $options["contractorData"];

		if($response["status"] == "success") {
			$mail_options = array();
			$mail_options["name"] 		= $contractorData["company"];
			//$mail_options["from"]		= "admin@thefixitnetwork.com";
			//$mail_options["fromName"]	= "Fixit Admin:";
			$mail_options["to"]			= $contractorData["office_email"];
			$mail_options["cc"]			="";
			$mail_options["bcc"]		= "";
			$mail_options["subject"]	= "Notice : Contractor Company information update";
			$mail_options["signature"] 	= "<p>Thanks,<br/>Fixit Networks</p>";
			$mail_options["message"] 	= "<p>Dear ".$mail_options["name"]."</p>";
			$mail_options["message"] 	.= "<p>Your Contractor company information is updated successfully.</p>";
			$mail_options["message"] 	.= "<p> </p>";
			$mail_options["message"] 	.= $mail_options["signature"];

			return $mail_options;
		}
	}


	public function generateCreatePartnerCompanyMailOptions( $options ) {
		$response 			= $options['response'];
		$partnerData 		= $options["partnerData"];

		if($response["status"] == "success") {
			$mail_options = array();
			$mail_options["name"] 		= $partnerData["company_name"];
			//$mail_options["from"]		= "admin@thefixitnetwork.com";
			//$mail_options["fromName"]	= "Fixit Admin:";
			$mail_options["to"]			= $partnerData["work_email_id"];
			$mail_options["cc"]			="";
			$mail_options["bcc"]		= "";
			$mail_options["subject"]	= "Registration Successful for Partner Company";
			$mail_options["signature"] 	= "<p>Thanks,<br/>Fixit Networks</p>";
			$mail_options["message"] 	= "<p>Dear ".$mail_options["name"]."</p>";
			$mail_options["message"] 	.= "<p>Welcome Partner company, Your account with fixit network was created successfully.</p>";
			$mail_options["message"] 	.= "<p>personal information is also added successfully. </p>";
			$mail_options["message"] 	.= $mail_options["signature"];

			return $mail_options;
		}
	}

	public function generateUpdatePartnerCompanyMailOptions( $options ) {
		$response 			= $options['response'];
		$partnerData 		= $options["partnerData"];

		if($response["status"] == "success") {
			$mail_options = array();
			$mail_options["name"] 		= $partnerData["company_name"];
			//$mail_options["from"]		= "admin@thefixitnetwork.com";
			//$mail_options["fromName"]	= "Fixit Admin:";
			$mail_options["to"]			= $partnerData["work_email_id"];
			$mail_options["cc"]			="";
			$mail_options["bcc"]		= "";
			$mail_options["subject"]	= "Notice: Partner Company information update";
			$mail_options["signature"] 	= "<p>Thanks,<br/>Fixit Networks</p>";
			$mail_options["message"] 	= "<p>Dear ".$mail_options["name"]."</p>";
			$mail_options["message"] 	.= "<pYour Partner company information is updated successfully.</p>";
			$mail_options["message"] 	.= "<p> </p>";
			$mail_options["message"] 	.= $mail_options["signature"];

			return $mail_options;
		}
	}

	function generateProjectMailOptions( $options ) {
		$response 			=  $options['response'];
		$projectData 		=  $options['projectData'];
		$customerData 		=  $options['customerData'];
		$contractorsData 	=  $options['contractorsData'];
		$partnersData 		=  $options['partnersData'];
		$mail_type 			= $options['mail_type'];

		if($response["status"] == "success") {
			$mail_options = array();
			$mail_list = array();
			$mail_list["from"]		= "admin@thefixitnetwork.com";
			$mail_list["fromName"]	= "Fixit Admin:";
			$mail_list["signature"] = "<p>Thanks,<br/>Fixit Networks</p>";
			$mail_list["to"] 		= "";
			$mail_list["cc"]		= "";
			$mail_list["bcc"]		= "";

			if($customerData && is_array($customerData)) {
				//$mail_options["customerData"] = $customerData;

				for($i = 0; $i < count($customerData); $i++ ) {
					$mail_list["name"] 		= $customerData[$i]->first_name." ".$customerData[$i]->last_name;
					$mail_list["to"]		= $customerData[$i]->email;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";
					
					switch( $mail_type ) {
						case "create":
							$mail_list["subject"]	= "New Project Created";
							$mail_list["message"] 	.= "<pNew Project with your customer details is be created successfully.</p>";
							$mail_list["message"] 	.= "<p>Login to fixit networks to see the project</p>";
						break;
						case "update":
							$mail_list["subject"]	= "Notice: Project Updated";
							$mail_list["message"] 	.= "<p>Project with your customer details is be updated successfully.</p>";
							$mail_list["message"] 	.= "<p>Login to fixit networks to see the project</p>";
						break;
						case "delete":
							$mail_list["subject"]	= "Notice: Project Deleted";
							$mail_list["message"] 	.= "<p>Project with your customer details is be deleted successfully.</p>";
						break;
					}

					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			if($contractorsData && is_array($contractorsData)) {
				for($i = 0; $i < count($contractorsData); $i++ ) {
					$mail_list["name"] 		= $contractorsData[$i]->name." from ".$contractorsData[$i]->company;
					$mail_list["to"]		= $contractorsData[$i]->office_email;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";

					switch ( $mail_type ) {
						case 'create':
							$mail_list["subject"]	= "Contractor : New Project Created";
							$mail_list["message"] 	.= "<p>New Project with your contractor company as part of that was created successfully.</p>";
							$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
						break;
						case 'update':
							$mail_list["subject"]	= "Notice: Project Updated in your contractor list";
							$mail_list["message"] 	.= "<p>Project under your company as contractor company was updated successfully.</p>";
							$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
						break;
						case 'delete':
							$mail_list["subject"]	= "Notice: Project Deleted in your contractor list";
							$mail_list["message"] 	.= "<p>Project under your company as contractor company was deleted successfully.</p>";
						break;
					}
					
					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			if($partnersData && is_array($partnersData)) {
				for($i = 0; $i < count($partnersData); $i++ ) {
					$mail_list["name"] 		= $partnersData[$i]->name." from ".$partnersData[$i]->company_name;
					$mail_list["to"]		= $partnersData[$i]->work_email_id;
					
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";

					switch ( $mail_type ) {
						case 'create':
							$mail_list["subject"]	= "Contractor : New Project Created";
							$mail_list["message"] 	.= "<p>New Project with your Partner company as part of that was created successfully.</p>";
						break;
						case 'update':
							$mail_list["subject"]	= "Notice: Project Updated in your partner list";
							$mail_list["message"] 	.= "<p>Project under your company as partner company was updated successfully.</p>";
						break;
						case 'delete':
							$mail_list["subject"]	= "Notice: Project Deleted in your partner list";
							$mail_list["message"] 	.= "<p>Project under your company as partner company was Deleted successfully.</p>";
						break;
					}
					

					$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			return $mail_options;
		}
	}

	function generateTaskMailOptions( $options ) {
		$response 			=  $options['response'];
		$taskData 			=  $options['taskData'];
		$customerData 		=  $options['customerData'];
		$contractorsData 	=  $options['contractorsData'];
		$partnersData 		=  $options['partnersData'];
		$mail_type 			= $options['mail_type'];

		if($response["status"] == "success") {
			$mail_options = array();
			$mail_list = array();
			$mail_list["signature"] = "<p>Thanks,<br/>Fixit Networks</p>";
			$mail_list["from"]		= "admin@thefixitnetwork.com";
			$mail_list["to"]		= "";
			$mail_list["cc"]		= "";
			$mail_list["bcc"]		= "";

			if($customerData && is_array($customerData)) {
				for($i = 0; $i < count($customerData); $i++ ) {
					$mail_list["name"] 		= $customerData[$i]->first_name." ".$customerData[$i]->last_name;
					$mail_list["fromName"]	= "Fixit Admin:";
					$mail_list["to"]		= $customerData[$i]->email;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";
					
					switch ( $mail_type ) {
					 	case 'create':
					 		$mail_list["subject"]	= "New Task Created";
					 		$mail_list["message"] 	.= "<pNew Task with your customer details is be created successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see the project</p>";
					 	break;
					 	case 'update':
					 		$mail_list["subject"]	= "Notice: Task Updated";
					 		$mail_list["message"] 	.= "<p>Task with your customer details is be updated successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see the project</p>";
					 	break;
					 	case 'delete':
					 		$mail_list["subject"]	= "Notice: Task Deleted";
					 		$mail_list["message"] 	.= "<p>Task with your customer details is be deleted successfully.</p>";
					 	break;
					 	
					 }

					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			if($contractorsData && is_array($contractorsData)) {
				for($i = 0; $i < count($contractorsData); $i++ ) {
					$mail_list["name"] 		= $contractorsData[$i]->name." from ".$contractorsData[$i]->company;
					$mail_list["to"]		= $contractorsData[$i]->office_email;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";

					switch ( $mail_type ) {
					 	case 'create':
					 		$mail_list["subject"]	= "Contractor : New Task Created";
					 		$mail_list["message"] 	.= "<p>New Task with your contractor company as part of that was created successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
					 	break;
					 	case 'update':
					 		$mail_list["subject"]	= "Notice: Task Updated in your contractor list";
					 		$mail_list["message"] 	.= "<p>Task under your company as contractor company was updated successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
					 	break;
					 	case 'delete':
					 		$mail_list["subject"]	= "Notice: Task deleted in your contractor list";
					 		$mail_list["message"] 	.= "<p>Task under your company as contractor company was deleted successfully.</p>";
					 	break;
					}

					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			if($partnersData && is_array($partnersData)) {
				for($i = 0; $i < count($partnersData); $i++ ) {
					$mail_list["name"] 		= $partnersData[$i]->name." from ".$partnersData[$i]->company_name;
					$mail_list["to"]		= $partnersData[$i]->work_email_id;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";

					switch ( $mail_type ) {
					 	case 'create':
					 		$mail_list["subject"]	= "Contractor : New Task Created";
					 		$mail_list["message"] 	.= "<p>New Task with your Partner company as part of that was created successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
					 	break;
					 	case 'update':
					 		$mail_list["subject"]	= "Notice: Task Updated in your partner list";
					 		$mail_list["message"] 	.= "<p>Task under your company as partner company was updated successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
					 	break;
					 	case 'delete':
					 		$mail_list["subject"]	= "Notice: Task deleted in your partner list";
					 		$mail_list["message"] 	.= "<p>Task under your company as partner company was deleted successfully.</p>";
					 	break;
					}

					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			return $mail_options;
		}
	}

	function generateIssueMailOptions( $options ) {
		$response 			=  $options['response'];
		$taskData 			=  $options['taskData'];
		$customerData 		=  $options['customerData'];
		$contractorsData 	=  $options['contractorsData'];
		$partnersData 		=  $options['partnersData'];
		$mail_type 			= $options['mail_type'];

		if($response["status"] == "success") {
			$mail_options = array();
			$mail_list = array();
			$mail_list["signature"] = "<p>Thanks,<br/>Fixit Networks</p>";
			$mail_list["from"]		= "admin@thefixitnetwork.com";
			$mail_list["fromName"]	= "Fixit Admin:";

			$mail_list["to"]		= "";
			$mail_list["cc"]		= "";
			$mail_list["bcc"]		= "";

			if($customerData && is_array($customerData)) {
				for($i = 0; $i < count($customerData); $i++ ) {
					$mail_list["name"] 		= $customerData[$i]->first_name." ".$customerData[$i]->last_name;
					$mail_list["to"]		= $customerData[$i]->email;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";
					
					switch ( $mail_type ) {
					 	case 'create':
					 		$mail_list["subject"]	= "New Issue Created";
					 		$mail_list["message"] 	.= "<pNew Issue with your customer details is be created successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see the project</p>";
					 	break;
					 	case 'update':
					 		$mail_list["subject"]	= "Notice: Issue Updated";
					 		$mail_list["message"] 	.= "<p>Issue with your customer details is be updated successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see the project</p>";
					 	break;
					 	case 'delete':
					 		$mail_list["subject"]	= "Notice: Issue Deleted";
					 		$mail_list["message"] 	.= "<p>Issue with your customer details is be deleted successfully.</p>";
					 	break;
					 	
					 }

					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			if($contractorsData && is_array($contractorsData)) {
				for($i = 0; $i < count($contractorsData); $i++ ) {
					$mail_list["name"] 		= $contractorsData[$i]->name." from ".$contractorsData[$i]->company;
					$mail_list["to"]		= $contractorsData[$i]->office_email;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";

					switch ( $mail_type ) {
					 	case 'create':
					 		$mail_list["subject"]	= "Contractor : New Issue Created";
					 		$mail_list["message"] 	.= "<p>New Issue with your contractor company as part of that was created successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
					 	break;
					 	case 'update':
					 		$mail_list["subject"]	= "Notice: Issue Updated in your contractor list";
					 		$mail_list["message"] 	.= "<p>Issue under your company as contractor company was updated successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
					 	break;
					 	case 'delete':
					 		$mail_list["subject"]	= "Notice: Issue deleted in your contractor list";
					 		$mail_list["message"] 	.= "<p>Issue under your company as contractor company was deleted successfully.</p>";
					 	break;
					}

					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			if($partnersData && is_array($partnersData)) {
				for($i = 0; $i < count($partnersData); $i++ ) {
					$mail_list["name"] 		= $partnersData[$i]->name." from ".$partnersData[$i]->company_name;
					$mail_list["to"]		= $partnersData[$i]->work_email_id;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";

					switch ( $mail_type ) {
					 	case 'create':
					 		$mail_list["subject"]	= "Contractor : New Issue Created";
					 		$mail_list["message"] 	.= "<p>New Issue with your Partner company as part of that was created successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
					 	break;
					 	case 'update':
					 		$mail_list["subject"]	= "Notice: Issue Updated in your partner list";
					 		$mail_list["message"] 	.= "<p>Issue under your company as partner company was updated successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
					 	break;
					 	case 'delete':
					 		$mail_list["subject"]	= "Notice: Issue deleted in your partner list";
					 		$mail_list["message"] 	.= "<p>Issue under your company as partner company was deleted successfully.</p>";
					 	break;
					}

					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			return $mail_options;
		}
	}

	function generateNotesMailOptions( $options ) {
		$response 			=  $options['response'];
		$taskData 			=  $options['taskData'];
		$customerData 		=  $options['customerData'];
		$contractorsData 	=  $options['contractorsData'];
		$partnersData 		=  $options['partnersData'];
		$mail_type 			= $options['mail_type'];

		if($response["status"] == "success") {
			$mail_options = array();
			$mail_list = array();
			$mail_list["signature"] = "<p>Thanks,<br/>Fixit Networks</p>";
			$mail_list["from"]		= "admin@thefixitnetwork.com";
			$mail_list["to"]		= "";
			$mail_list["cc"]		= "";
			$mail_list["bcc"]		= "";

			if($customerData && is_array($customerData)) {
				for($i = 0; $i < count($customerData); $i++ ) {
					$mail_list["name"] 		= $customerData[$i]->first_name." ".$customerData[$i]->last_name;
					$mail_list["fromName"]	= "Fixit Admin:";
					$mail_list["to"]		= $customerData[$i]->email;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";
					
					switch ( $mail_type ) {
					 	case 'create':
					 		$mail_list["subject"]	= "New Notes Created";
					 		$mail_list["message"] 	.= "<pNew Notes with your customer details is be created successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see the project</p>";
					 	break;
					 	case 'delete':
					 		$mail_list["subject"]	= "Notice: Notes Deleted";
					 		$mail_list["message"] 	.= "<p>Notes with your customer details is be deleted successfully.</p>";
					 	break;
					 	
					 }

					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			if($contractorsData && is_array($contractorsData)) {
				for($i = 0; $i < count($contractorsData); $i++ ) {
					$mail_list["name"] 		= $contractorsData[$i]->name." from ".$contractorsData[$i]->company;
					$mail_list["to"]		= $contractorsData[$i]->office_email;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";

					switch ( $mail_type ) {
					 	case 'create':
					 		$mail_list["subject"]	= "Contractor : New Notes Created";
					 		$mail_list["message"] 	.= "<p>New Notes with your contractor company as part of that was created successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
					 	break;
					 	case 'delete':
					 		$mail_list["subject"]	= "Notice: Notes deleted in your contractor list";
					 		$mail_list["message"] 	.= "<p>Notes under your company as contractor company was deleted successfully.</p>";
					 	break;
					}

					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			if($partnersData && is_array($partnersData)) {
				for($i = 0; $i < count($partnersData); $i++ ) {
					$mail_list["name"] 		= $partnersData[$i]->name." from ".$partnersData[$i]->company_name;
					$mail_list["to"]		= $partnersData[$i]->work_email_id;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";

					switch ( $mail_type ) {
					 	case 'create':
					 		$mail_list["subject"]	= "Contractor : New Notes Created";
					 		$mail_list["message"] 	.= "<p>New Notes with your Partner company as part of that was created successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
					 	break;
					 	case 'delete':
					 		$mail_list["subject"]	= "Notice: Notes deleted in your partner list";
					 		$mail_list["message"] 	.= "<p>Notes under your company as partner company was deleted successfully.</p>";
					 	break;
					}

					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			return $mail_options;
		}
	}

	function generateDocsMailOptions( $options ) {
		$response 			=  $options['response'];
		$taskData 			=  $options['taskData'];
		$customerData 		=  $options['customerData'];
		$contractorsData 	=  $options['contractorsData'];
		$partnersData 		=  $options['partnersData'];
		$mail_type 			= $options['mail_type'];

		if($response["status"] == "success") {
			$mail_options = array();
			$mail_list = array();
			$mail_list["signature"] = "<p>Thanks,<br/>Fixit Networks</p>";
			$mail_list["from"]		= "admin@thefixitnetwork.com";
			$mail_list["to"]		= "";
			$mail_list["cc"]		= "";
			$mail_list["bcc"]		= "";

			if($customerData && is_array($customerData)) {
				for($i = 0; $i < count($customerData); $i++ ) {
					$mail_list["name"] 		= $customerData[$i]->first_name." ".$customerData[$i]->last_name;
					$mail_list["fromName"]	= "Fixit Admin:";
					$mail_list["to"]		= $customerData[$i]->email;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";
					
					switch ( $mail_type ) {
					 	case 'create':
					 		$mail_list["subject"]	= "New Document Created";
					 		$mail_list["message"] 	.= "<pNew Document with your customer details is be created successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see the project</p>";
					 	break;
					 	case 'delete':
					 		$mail_list["subject"]	= "Notice: Document Deleted";
					 		$mail_list["message"] 	.= "<p>Document with your customer details is be deleted successfully.</p>";
					 	break;
					 	
					 }

					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			if($contractorsData && is_array($contractorsData)) {
				for($i = 0; $i < count($contractorsData); $i++ ) {
					$mail_list["name"] 		= $contractorsData[$i]->name." from ".$contractorsData[$i]->company;
					$mail_list["to"]		= $contractorsData[$i]->office_email;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";

					switch ( $mail_type ) {
					 	case 'create':
					 		$mail_list["subject"]	= "Contractor : New Document Created";
					 		$mail_list["message"] 	.= "<p>New Document with your contractor company as part of that was created successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
					 	break;
					 	case 'delete':
					 		$mail_list["subject"]	= "Notice: Document deleted in your contractor list";
					 		$mail_list["message"] 	.= "<p>Document under your company as contractor company was deleted successfully.</p>";
					 	break;
					}

					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			if($partnersData && is_array($partnersData)) {
				for($i = 0; $i < count($partnersData); $i++ ) {
					$mail_list["name"] 		= $partnersData[$i]->name." from ".$partnersData[$i]->company_name;
					$mail_list["to"]		= $partnersData[$i]->work_email_id;
					$mail_list["message"] 	= "<p>Dear ".$mail_list["name"]."</p>";

					switch ( $mail_type ) {
					 	case 'create':
					 		$mail_list["subject"]	= "Contractor : New Document Created";
					 		$mail_list["message"] 	.= "<p>New Document with your Partner company as part of that was created successfully.</p>";
					 		$mail_list["message"] 	.= "<p>Login to fixit networks to see more details of the project</p>";
					 	break;
					 	case 'delete':
					 		$mail_list["subject"]	= "Notice: Document deleted in your partner list";
					 		$mail_list["message"] 	.= "<p>Document under your company as partner company was deleted successfully.</p>";
					 	break;
					}

					$mail_list["message"] 	.= $mail_list["signature"];

					array_push($mail_options, $mail_list);
				}
			}

			return $mail_options;
		}
	}

	public function sendMail( $options = array() ) {
		file_put_contents($_SERVER['DOCUMENT_ROOT']."/email_log.html", "in send mail--\n", FILE_APPEND | LOCK_EX);

		if(strpos(base_url(),"kannansriram.netau.net")) {
			$config = Array(
			    'protocol' => 'mail',
			    'smtp_host' => '',
			    'smtp_port' => '',
			    'smtp_user' => '',
			    'smtp_pass' => ''
			);
		} elseif (strpos(base_url(),"thefixitnetwork.net")) {
			$config = Array(
			    'protocol' => 'smtp',
			    'smtp_host' => 'ssl://secure179.inmotionhosting.com',
			    'smtp_port' => 465,
			    'smtp_user' => 'admin@thefixitnetwork.net',
			    'smtp_pass' => 'Adm1n@f1x1t'
			);
		} elseif (strpos(base_url(),"thefixitnetwork.com")) {
			$config = Array(
			    'protocol' => 'smtp',
			    'smtp_host' => 'ssl://dallas112.arvixeshared.com',
			    'smtp_port' => 465,
			    'smtp_user' => 'admin@thefixitnetwork.com',
			    'smtp_pass' => 'east2west'
			);
		}

		if(isset($config)) {
			
			$this->email->initialize( $config );

			$from 		= $config["smtp_user"];
			$fromName	= $this->lang->line('email_from_name_html');
			$to 		= $options["to"];
			$subject 	= $options["subject"];
			$message 	= $options["message"];

			$this->email->clear(TRUE);

		    $this->email->from($from, $fromName);
		    $this->email->to($to);
		    $this->email->reply_to($from, $fromName);
		    $this->email->subject($subject);
		    $this->email->message($message);
		    $this->email->set_mailtype('html');

		   	if ( ! $this->email->send()) {
				return "Email not sent \n".$this->email->print_debugger();
			} else {
				return "success";
			}
		}
	}
}