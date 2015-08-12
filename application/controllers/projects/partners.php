<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partners extends CI_Controller {

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
		$this->load->model('projects/model_partners');

		$companyName 	= explode(",", $this->input->post("companyName"));
		$name 			= explode(",", $this->input->post("name"));

		$records = [];
		if($this->input->post("records")) {
			$records = explode(",", $this->input->post("records"));
		}

		$partnersResponse = $this->model_partners->getPartnersList( $records, $companyName, $name);

		print_r(json_encode($partnersResponse));
	}

	public function getPartnerByCompanyName() {
		$this->load->model('projects/model_partners');
		
		$companyName = explode(",", $this->input->post('companyName'));
		$record = "";

		$partnersResponse = $this->model_partners->getPartnersList($record, $companyName);

		print_r(json_encode($partnersResponse));	
	}

	public function viewAll() {
		$this->load->model('projects/model_partners');

		
		$partnersResponse = $this->model_partners->getPartnersList();

		$params = array(
			'partners'=>$partnersResponse["partners"]
		);
		
		echo $this->load->view("projects/partners/viewAll", $params, true);
	}
	
	public function createForm() {
		$this->load->model('security/model_users');

		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$addressParams = array(
			'forForm' 		=> "create_partner_form"
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);
		
		$params = array(
			'users' 		=> $this->model_users->getUsersList(),
			'userType' 		=> $this->session->userdata('account_type'),
			'openAs' 		=> $openAs,
			'addressFile' 	=> $addressFile,
			'popupType' 	=> $popupType
		);

		echo $this->load->view("projects/partners/createForm", $params, true);
	}

	public function add() {
		$this->load->model('projects/model_partners');
		$this->load->model('mail/model_mail');

		$data = array(
			'name' 				=> $this->input->post('name'),
			'company_name' 		=> $this->input->post('company'),
			'type' 				=> $this->input->post('type'),
			'license' 			=> $this->input->post('license'),
			'status' 			=> $this->input->post('status'),
			'address1' 			=> $this->input->post('addressLine1'),
			'address2'			=> $this->input->post('addressLine2'),
			'city' 				=> $this->input->post('city'),
			'state' 			=> $this->input->post('state'),
			'country' 			=> $this->input->post('country'),
			'zip_code' 			=> $this->input->post('zipCode'),
			'work_email_id' 	=> $this->input->post('wEmailId'),
			'work_phone' 		=> $this->input->post('wNumber'),
			'mobile_no' 		=> $this->input->post('pNumber'),
			'personal_email_id' => $this->input->post('pEmailId'),
			'contact_pref' 		=> $this->input->post('prefContact'),
			'website_url' 		=> $this->input->post('websiteURL'),
			'created_by'		=> $this->session->userdata('user_id'),
			'updated_by'		=> $this->session->userdata('user_id'),
			'created_on'		=> date("Y-m-d H:i:s"),
			'updated_on'		=> date("Y-m-d H:i:s")
		);

		$insert_partner = $this->model_partners->insert($data);

		$partnerCompanyParamsFormMail = array(
			'response'				=> $insert_partner,
			'partnerData'		=> $data
		);

		$mail_options = $this->model_mail->generateCreatepartnerCompanyMailOptions( $partnerCompanyParamsFormMail );
		
		$this->model_mail->sendMail( $mail_options );

		print_r(json_encode($insert_partner));
	}

	public function viewOne() {
		$this->load->model('projects/model_partners');
		$this->load->model('security/model_users');

		$partnerId 			= $this->input->post('partnerId');
		$openAs		 			= $this->input->post('openAs');
		$partnersResponse 		= $this->model_partners->getPartnersList($partnerId);

		$partners = $partnersResponse["partners"];
		
		for($i=0; $i < count($partners); $i++) {
			$partners[$i]->created_by_name = $this->model_users->getUsersList($partners[$i]->created_by)[0]->user_name;
			$partners[$i]->updated_by_name = $this->model_users->getUsersList($partners[$i]->updated_by)[0]->user_name;
		}

		$addressParams = array(
			'addressLine1' 		=> $partners[0]->address1,
			'addressLine2' 		=> $partners[0]->address2,
			'city' 				=> $partners[0]->city,
			'country' 			=> $partners[0]->country,
			'state'				=> $partners[0]->state,
			'zipCode' 			=> $partners[0]->zip_code,
			'requestFrom' 		=> 'view'
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);

		$params = array(
			'partners'		=> $partners,
			'userType' 		=> $this->session->userdata('account_type'),
			'partnerId' 	=> $partnerId,
			'addressFile' 	=> $addressFile,
			'openAs' 		=> $openAs
		);
		
		echo $this->load->view("projects/partners/viewOne", $params, true);
	}

	public function editForm() {
		$this->load->model('projects/model_partners');

		$partnerId = $this->input->post('partnerId');
		$openAs 	= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 	= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$partnersResponse = $this->model_partners->getPartnersList($partnerId);
		$partners = $partnersResponse["partners"];

		$addressParams = array(
			'addressLine1' 		=> $partners[0]->address1,
			'addressLine2' 		=> $partners[0]->address2,
			'city' 				=> $partners[0]->city,
			'country' 			=> $partners[0]->country,
			'state'				=> $partners[0]->state,
			'zipCode' 			=> $partners[0]->zip_code,
			'forForm' 			=> "update_partner_form"
		);

		$addressFile = $this->load->view("forms/address", $addressParams, true);

		$params = array(
			'partners' 	=> $partners,
			'addressFile' 	=> $addressFile,
			'userType' 		=> $this->session->userdata('account_type'),
			'openAs' 		=> $openAs,
			'popupType' 	=> $popupType
		);
		
		echo $this->load->view("projects/partners/editForm", $params, true);
	}

	public function update() {
		$this->load->model('projects/model_partners');

		$partnerId 			= $this->input->post('partnerId');

		$data = array(
			'name' 				=> $this->input->post('name'),
			'company_name' 		=> $this->input->post('company'),
			'type' 				=> $this->input->post('type'),
			'license' 			=> $this->input->post('license'),
			'status' 			=> $this->input->post('status'),
			'address1' 			=> $this->input->post('addressLine1'),
			'address2'			=> $this->input->post('addressLine2'),
			'city' 				=> $this->input->post('city'),
			'state' 			=> $this->input->post('state'),
			'country' 			=> $this->input->post('country'),
			'zip_code' 			=> $this->input->post('zipCode'),
			'work_email_id' 	=> $this->input->post('wEmailId'),
			'work_phone' 		=> $this->input->post('wNumber'),
			'mobile_no' 		=> $this->input->post('pNumber'),
			'personal_email_id' => $this->input->post('pEmailId'),
			'contact_pref' 		=> $this->input->post('prefContact'),
			'website_url' 		=> $this->input->post('websiteURL'),
			'updated_by'		=> $this->session->userdata('user_id'),
			'updated_on'		=> date("Y-m-d H:i:s")
		);

		$update_partner = $this->model_partners->update($data, $partnerId);

		print_r(json_encode($update_partner));
	}

	public function deleteRecord() {
		$this->load->model('projects/model_partners');

		$partnerId = $this->input->post('partnerId');
		$delete_partner = $this->model_partners->deleteRecord($partnerId);

		print_r(json_encode($delete_partner));	
	}
}