<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contractors extends CI_Controller {

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
	}

	public function getList() {
		$this->load->model('projects/model_contractors');

		$serviceZip = trim($this->input->post("serviceZip")) ? explode(",", trim($this->input->post("serviceZip"))) : null;
		$zip 		= trim($this->input->post("zip")) ? trim($this->input->post("zip")) : null;
		$records 	= trim($this->input->post("records")) ? explode(",", trim($this->input->post("records"))) : null;
		
		$contractorsResponse = $this->model_contractors->getContractorsList( $records, $zip, $serviceZip );

		print_r(json_encode($contractorsResponse));
	}

	public function viewAll() {
		$this->load->model('projects/model_contractors');

		
		$contractorsResponse = $this->model_contractors->getContractorsList();

		$params = array(
			'contractors'=>$contractorsResponse["contractors"],
			'account_type' => $this->session->userdata('account_type')
		);
		
		echo $this->load->view("projects/contractors/viewAll", $params, true);
	}
	
	public function createForm() {
		$this->load->model('security/model_users');

		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$addressParams = array(
			'forForm' 		=> "create_contractor_form"
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);
		
		$params = array(
			'users' 		=> $this->model_users->getUsersList(),
			'userType' 		=> $this->session->userdata('account_type'),
			'openAs' 		=> $openAs,
			'addressFile' 	=> $addressFile,
			'popupType' 	=> $popupType
		);

		echo $this->load->view("projects/contractors/inputForm", $params, true);
	}

	public function add() {
		$this->load->model('projects/model_contractors');
		$this->load->model('mail/model_mail');

		$data = array(
			//'name' 				=> $this->input->post('name'),
			'company' 			=> $this->input->post('company'),
			'type' 				=> $this->input->post('type'),
			'license' 			=> $this->input->post('license'),
			'bbb' 				=> $this->input->post('bbb'),
			'status' 			=> $this->input->post('status'),
			'address1' 			=> $this->input->post('addressLine1'),
			'address2'			=> $this->input->post('addressLine2'),
			'city' 				=> $this->input->post('city'),
			'state' 			=> $this->input->post('state'),
			'country' 			=> $this->input->post('country'),
			'pin_code' 			=> $this->input->post('zipCode'),
			'office_email' 		=> $this->input->post('emailId'),
			'office_ph' 		=> $this->input->post('contactPhoneNumber'),
			'mobile_ph' 		=> $this->input->post('mobileNumber'),
			'prefer' 			=> $this->input->post('prefContact'),
			'website_url' 		=> $this->input->post('websiteURL'),
			'service_area' 		=> $this->input->post('serviceZip'),
			'created_by'		=> $this->session->userdata('user_id'),
			'updated_by'		=> $this->session->userdata('user_id'),
			'created_on'		=> date("Y-m-d H:i:s"),
			'updated_on'		=> date("Y-m-d H:i:s")
		);

		$response = $this->model_contractors->insert($data);

		$contractorCompanyParamsFormMail = array(
			'response'				=> $response,
			'contractorData'		=> $data
		);

		$mail_options = $this->model_mail->generateCreateContractorCompanyMailOptions( $contractorCompanyParamsFormMail );
		
		$response['mail_content'] = $mail_options;

		$response["mail_error"] = $this->model_mail->sendMail( $mail_options );

		print_r(json_encode($response));
	}

	public function viewOne() {
		$this->load->model('projects/model_contractors');
		$this->load->model('security/model_users');
		$this->load->model('utils/model_form_utils');

		$contractorId 			= $this->input->post('contractorId');
		$openAs		 			= $this->input->post('openAs');
		$contractorsResponse 	= $this->model_contractors->getContractorsList($contractorId);

		$contractors = $contractorsResponse["contractors"];
		
		for($i=0; $i < count($contractors); $i++) {
			$contractors[$i]->created_by_name = $this->model_users->getUsersList($contractors[$i]->created_by)[0]->user_name;
			$contractors[$i]->updated_by_name = $this->model_users->getUsersList($contractors[$i]->updated_by)[0]->user_name;
		}

		$stateText = !empty($contractors[0]->state) ? $this->model_form_utils->getCountryStatus($contractors[0]->state)[0]->name : "";

		$addressParams = array(
			'addressLine1' 		=> $contractors[0]->address1,
			'addressLine2' 		=> $contractors[0]->address2,
			'city' 				=> $contractors[0]->city,
			'country' 			=> $contractors[0]->country,
			'state'				=> $stateText,
			'zipCode' 			=> $contractors[0]->pin_code,
			'requestFrom' 		=> 'view'
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);

		$params = array(
			'contractors'	=> $contractors,
			'userType' 		=> $this->session->userdata('account_type'),
			'contractorId' 	=> $contractorId,
			'addressFile' 	=> $addressFile,
			'openAs' 		=> $openAs
		);
		
		echo $this->load->view("projects/contractors/viewOne", $params, true);
	}

	public function editForm() {
		$this->load->model('projects/model_contractors');

		$contractorId = $this->input->post('contractorId');
		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$contractorsResponse = $this->model_contractors->getContractorsList($contractorId);
		$contractors = $contractorsResponse["contractors"];

		$addressParams = array(
			'addressLine1' 		=> $contractors[0]->address1,
			'addressLine2' 		=> $contractors[0]->address2,
			'city' 				=> $contractors[0]->city,
			'country' 			=> $contractors[0]->country,
			'state'				=> $contractors[0]->state,
			'zipCode' 			=> $contractors[0]->pin_code,
			'forForm' 			=> "update_contractor_form"
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);

		$params = array(
			'contractors' 	=> $contractors,
			'addressFile' 	=> $addressFile,
			'userType' 		=> $this->session->userdata('account_type'),
			'openAs' 		=> $openAs,
			'popupType' 	=> $popupType
		);
		
		echo $this->load->view("projects/contractors/inputForm", $params, true);
	}

	public function update() {
		$this->load->model('projects/model_contractors');
		$this->load->model('mail/model_mail');

		$contractorId 			= $this->input->post('contractorId');

		$data = array(
			//'name' 				=> $this->input->post('name'),
			'company' 			=> $this->input->post('company'),
			'type' 				=> $this->input->post('type'),
			'license' 			=> $this->input->post('license'),
			'bbb' 				=> $this->input->post('bbb'),
			'status' 			=> $this->input->post('status'),
			'address1' 			=> $this->input->post('addressLine1'),
			'address2'			=> $this->input->post('addressLine2'),
			'city' 				=> $this->input->post('city'),
			'state' 			=> $this->input->post('state'),
			'country' 			=> $this->input->post('country'),
			'pin_code' 			=> $this->input->post('zipCode'),
			'office_email' 		=> $this->input->post('emailId'),
			'office_ph' 		=> $this->input->post('contactPhoneNumber'),
			'mobile_ph' 		=> $this->input->post('mobileNumber'),
			'prefer' 			=> $this->input->post('prefContact'),
			'website_url' 		=> $this->input->post('websiteURL'),
			'service_area' 		=> $this->input->post('serviceZip'),
			'updated_by'		=> $this->session->userdata('user_id'),
			'updated_on'		=> date("Y-m-d H:i:s")
		);

		$response = $this->model_contractors->update($data, $contractorId);

		$contractorCompanyParamsFormMail = array(
			'response'				=> $response,
			'contractorData'		=> $data
		);

		$mail_options = $this->model_mail->generateUpdateContractorCompanyMailOptions( $contractorCompanyParamsFormMail );
		
		$response['mail_content'] = $mail_options;
		$response["mail_error"] = $this->model_mail->sendMail( $mail_options );

		print_r(json_encode($response));
	}

	public function deleteRecord() {
		$this->load->model('projects/model_contractors');
		$this->load->model('mail/model_mail');

		$contractorId = $this->input->post('contractorId');
		$delete_contractor = $this->model_contractors->deleteRecord($contractorId);

		print_r(json_encode($delete_contractor));	
	}
}