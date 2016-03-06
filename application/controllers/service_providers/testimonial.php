<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testimonial extends CI_Controller {

	public function __construct()
   	{
        parent::__construct();
	}

	public function viewAll() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$permission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER_TESTIMONIAL);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_VIEW, $permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Testimonial List"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_testimonials');


		$contractor_id 			= $this->input->post("contractor_id");

		$params = array(
			"contractor_id"		=> $contractor_id
		);

		$response = $this->model_testimonials->getTestimonial($params);

		if($response["status"] == "success") {
			$params["testimonialList"] = $response["testimonialList"];
		}

		echo $this->load->view("service_providers/testimonial/viewAll", $params, true);
	}

	public function createForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$permission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER_TESTIMONIAL);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_CREATE, $permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Create Testimonial"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_service_providers');

		$contractor_id 	= $this->input->post("contractor_id");

		$params = array(
			"contractor_id"		=> $contractor_id
		);

		echo $this->load->view("service_providers/testimonial/inputForm", $params, true);
	}

	public function add() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER_TESTIMONIAL, OPERATION_CREATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_testimonials');

		$contractor_id 				= $this->input->post("contractor_id");
		$testimonial_summary		= $this->input->post("testimonial_summary");
		$testimonial_descr			= $this->input->post("testimonial_descr");
		$testimonial_rating		= $this->input->post("testimonial_rating");
		$testimonial_customer_name	= $this->input->post("testimonial_customer_name");
		$testimonial_date			= $this->input->post("testimonial_date");

		$data = array(
			'testimonial_contractor_id'		=> $contractor_id,
			'testimonial_customer_id'		=> "",
			'testimonial_anonynomus_name'	=> $testimonial_customer_name,
			'testimonial_summary' 			=> $testimonial_summary,
			'testimonial_descr'				=> $testimonial_descr,
			'testimonial_rating'			=> $testimonial_rating,
			'testimonial_date'				=> $testimonial_date,
			'created_by'					=> $this->session->userdata('logged_in_user_id'),
			'updated_by'					=> $this->session->userdata('logged_in_user_id'),
			'created_on'					=> date("Y-m-d H:i:s"),
			'updated_on'					=> date("Y-m-d H:i:s")
		);

		$response = $this->model_testimonials->insertTestimonial($data);

		print_r(json_encode($response));
	}

	public function editForm() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		//Service Provider > Permissions for logged in User by role_id
		$permission 	= $this->permissions_lib->getPermissions(FUNCTION_SERVICE_PROVIDER_TESTIMONIAL);
		/* If User dont have view permission load No permission page */
		if(!in_array(OPERATION_UPDATE, $permission['operation'])) {
			$no_permission_options = array(
				'page_disp_string' => "Update Testimonial"
			);
			echo $this->load->view("pages/no_permission", $no_permission_options, true);
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_testimonials');

		$contractor_id 				= $this->input->post("contractor_id");
		$testimonial_id 			= $this->input->post("testimonial_id");

		$params = array(
			"contractor_id"		=> $contractor_id,
			"testimonial_id"	=> $testimonial_id
		);

		$response = $this->model_testimonials->getTestimonial($params);

		if($response["status"] == "success") {
			$params["testimonialList"] = $response["testimonialList"];
		}

		echo $this->load->view("service_providers/testimonial/inputForm", $params, true);
	}

	public function update() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER_TESTIMONIAL, OPERATION_UPDATE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_service_providers');
		$this->load->model('service_providers/model_testimonials');

		$testimonial_id 			= $this->input->post("testimonial_id");
		$contractor_id 				= $this->input->post("contractor_id");
		$testimonial_summary		= $this->input->post("testimonial_summary");
		$testimonial_descr			= $this->input->post("testimonial_descr");
		$testimonial_rating		= $this->input->post("testimonial_rating");
		$testimonial_customer_name	= $this->input->post("testimonial_customer_name");
		$testimonial_date			= $this->input->post("testimonial_date");

		$data = array(
			'testimonial_anonynomus_name'	=> $testimonial_customer_name,
			'testimonial_summary' 			=> $testimonial_summary,
			'testimonial_descr'				=> $testimonial_descr,
			'testimonial_rating'			=> $testimonial_rating,
			'testimonial_date'				=> $testimonial_date,
			'updated_by'					=> $this->session->userdata('logged_in_user_id'),
			'updated_on'					=> date("Y-m-d H:i:s")
		);

		$params = array(
			'data'				=> $data,
			'testimonial_id'	=> $testimonial_id,
			'contractor_id'		=> $contractor_id
		);

		$response = $this->model_testimonials->updateTestimonial($params);

		print_r(json_encode($response));
	}

	public function delete() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}
		
		$is_allowed = $this->permissions_lib->is_allowed(FUNCTION_SERVICE_PROVIDER_TESTIMONIAL, OPERATION_DELETE);

		if(!$is_allowed["status"] ) {
			print_r(json_encode($is_allowed));
			return false;
		}

		$this->load->model('service_providers/model_testimonials');

		$testimonial_id 		= $this->input->post("testimonial_id");
		$contractor_id 			= $this->input->post("contractor_id");

		$params = array(
			"contractor_id"		=> $contractor_id,
			"testimonial_id"		=> $testimonial_id
		);

		$response = $this->model_testimonials->deleteTestimonial($params);
		print_r(json_encode($response));
	}
}