<?php
class Model_testimonials extends CI_Model {
	public function getTestimonial( $options ) {
		$response = array(
			'status' => 'error'
		);

		$contractor_id	= $options["contractor_id"];
		$testimonial_id	= isset($options["testimonial_id"]) ? $options["testimonial_id"] : "";

		if(isset($contractor_id) && $contractor_id != "") {
			$this->db->where('testimonial_contractor_id', $contractor_id);
			$this->db->where('is_deleted', "0");
			
			if(isset($testimonial_id) && $testimonial_id != "") {
				$this->db->where('testimonial_id', $testimonial_id);
			}

			$this->db->select([
				"*",
				"DATE_FORMAT(testimonial_date, \"%m/%d/%Y\") as testimonial_date_input_box",
				"DATE_FORMAT( testimonial_date, \"%d-%m-%y\") as testimonial_date_for_view",
				"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view",
				"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
				"created_on",
				"updated_on"
			]);

			$query = $this->db->from('testimonial')->get();

			if($this->db->_error_number() == 0) {
				$response["status"] 		= "success";
				$response["testimonialList"] 	= $query->result();
			} else {
				$response["errorCode"] 		= $this->db->_error_number();
				$response["message"] 	= $this->db->_error_message();
			}
		} else {
			$response["message"] = "Invalid request, please try again";
		}
		return $response;
	}

	public function insertTestimonial ( $data ) {
		$response = array();
		if($this->db->insert('testimonial', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']		= $this->db->insert_id();
			$response['message']		= "Testimonial Inserted Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	public function updateTestimonial ( $options ) {
		$response = array(
			'status' => 'error'
		);
		
		$data 					= $options["data"];
		$testimonial_id 		= $options["testimonial_id"];
		$contractor_id 			= $options["contractor_id"];

		if(isset($testimonial_id) && $testimonial_id != "" && isset($contractor_id) && $contractor_id != "") {
			$this->db->where('testimonial_id', $testimonial_id);
			$this->db->where('testimonial_contractor_id', $contractor_id);
		
			if($this->db->update('testimonial', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'Testimonial updated Successfully';
				$response['updatedId']	= $testimonial_id;
			} else {
				$response["message"] = "Error while updating the Testimonial";
			}	
		} else {
			$response['message']	= 'Invalid testimonial update, Please try again';
		}
		return $response;
	}

	public function deleteTestimonial( $options ) {
		$response = array(
			'status' => 'error'
		);

		$contractor_id	= $options["contractor_id"];
		$testimonial_id	= $options["testimonial_id"];

		if(isset($contractor_id) && $contractor_id != "" && isset($testimonial_id) && $testimonial_id != "") {
			$this->db->where('testimonial_id', $testimonial_id);
			$this->db->where('testimonial_contractor_id', $contractor_id);

			$data = array(
				'is_deleted' => 1
			);
			
			if($this->db->update('testimonial', $data)) {
				$response['status']		= "success";
				$response['message']	= "Testimonial Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the testimonial";
			}
		} else {
			$response['message']	= 'Invalid contractor or testimonial, Please try again';
		}
		return $response;
	}
}