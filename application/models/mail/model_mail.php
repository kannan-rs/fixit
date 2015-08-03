<?php

class Model_mail extends CI_Model {

	public function generateUpdateUserMailOptions( $options ) {
		$response 				= $options['response'];
		$user_details_record 	= $options["user_details_record"];
		$user_record 			= $options["user_record"];
		
		if($response["status"] == "success") {
			$mail_options = array();
			$mail_options["name"] 		= $user_details_record[0]->first_name." ".$user_details_record[0]->last_name;
			$mail_options["from"]		= "admin@fixitnetworks.com";
			$mail_options["fromName"]	= "Fixit Admin";
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

	public function generateCreateUserMailOptions( $options ) {
		$response 				= $options['response'];
		$user_details_record 	= $options["user_details_record"];
		$user_record 			= $options["user_record"];
		
		if($response["status"] == "success") {
			$mail_options = array();
			$mail_options["name"] 		= $user_details_record[0]->first_name." ".$user_details_record[0]->last_name;
			$mail_options["from"]		= "admin@fixitnetworks.com";
			$mail_options["fromName"]	= "Fixit Admin";
			$mail_options["to"]			= $user_record[0]->user_name;
			$mail_options["cc"]			="";
			$mail_options["bcc"]		= "";
			$mail_options["subject"]	= "Registration to Fixit Network successfully";
			$mail_options["signature"] 	= "<p>Thanks,<br/>Fixit Networks</p>";
			$mail_options["message"] 	= "<p>Dear ".$mail_options["name"]."</p>";
			$mail_options["message"] 	.= "<p>Your account with fixit network was created successfully.</p>";
			$mail_options["message"] 	.= "<p>personal information is also added successfully. </p>";
			$mail_options["message"] 	.= "<p>Please login with you credential to see the latest update</p>";
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
			$mail_options["from"]		= "admin@fixitnetworks.com";
			$mail_options["fromName"]	= "Fixit Admin";
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

	public function generateCreateContractorCompanyMailOptions( $options ) {
		$response 				= $options['response'];
		$contractorData 		= $options["contractorData"];

		if($response["status"] == "success") {
			$mail_options = array();
			$mail_options["name"] 		= $contractorData["name"]." for Company ".$contractorData["company"];
			$mail_options["from"]		= "admin@fixitnetworks.com";
			$mail_options["fromName"]	= "Fixit Admin";
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

	public function generateCreatepartnerCompanyMailOptions( $options ) {
		$response 				= $options['response'];
		$partnerData 		= $options["partnerData"];

		if($response["status"] == "success") {
			$mail_options = array();
			$mail_options["name"] 		= $partnerData["name"]." for Company ".$partnerData["company_name"];
			$mail_options["from"]		= "admin@fixitnetworks.com";
			$mail_options["fromName"]	= "Fixit Admin";
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

	public function sendMail( $options = array() ) {
		$from 		= $options["from"];
		$fromName	= $options["fromName"];
		$to 		= $options["to"];
		$subject 	= $options["subject"];
		$message 	= $options["message"];

		$this->email->clear(TRUE);

	    $this->email->to($to);
	    $this->email->from($from, $fromName);
	    $this->email->subject($subject);
	    $this->email->message($message);
	    $this->email->set_mailtype('html');
	    $this->email->send();
	}
}