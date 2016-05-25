<?php
// Service privider discount model functionality

class Model_discounts extends CI_Model {
	public function getDiscountList( $options ) {
		$response = array(
			'status' => 'error'
		);
		$contractor_id	= isset($options["contractor_id"]) ? $options["contractor_id"] : "";
		$discount_id	= isset($options["discount_id"]) ? $options["discount_id"] : "";
		$from_lib		= isset($options["from_lib"]) ? $options["from_lib"] : "";

		if((isset($contractor_id) && $contractor_id != "") || (isset($from_lib) && $from_lib == 1 )) {
			
			if(isset($contractor_id) && $contractor_id != "") {
				$this->db->where('discount_for_contractor_id', $contractor_id);
			}

			if(isset($discount_id) && $discount_id != "") {
				$this->db->where('discount_id', $discount_id);
			}

			$this->db->where('is_deleted', "0");

			$this->db->select([
				"*",
				"DATE_FORMAT(discount_from_date, \"%m/%d/%Y\") as discount_from_date_input_box",
				"DATE_FORMAT(discount_to_date, \"%m/%d/%Y\") as discount_to_date_input_box", 
				"DATE_FORMAT(discount_from_date, \"%d-%m-%y\") as discount_from_date_for_view", 
				"DATE_FORMAT( discount_to_date, \"%d-%m-%y\") as discount_to_date_for_view",
				"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
				"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
				"created_on",
				"updated_on"
			]);

			$query = $this->db->from('contractor_discount')->get();

			if($this->db->_error_number() == 0) {
				$response["status"] 		= "success";
				$response["discountList"] 	= $query->result();
			} else {
				$response["errorCode"] 		= $this->db->_error_number();
				$response["errorMessage"] 	= $this->db->_error_message();
			}
		} else {
			$response['message']	= 'Invalid contractor, Please try again';
		}
		return $response;
	}

	public function insertDiscount( $data ) {
		$response = array();
		if($this->db->insert('contractor_discount', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']		= $this->db->insert_id();
			$response['message']		= "Discount Inserted Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	public function updateDiscount( $options ) {
		$response = array(
			'status' => 'error'
		);
		
		$data 				= $options["data"];
		$discount_id 		= $options["discount_id"];
		$contractor_id 		= $options["contractor_id"];

		if(isset($discount_id) && $discount_id != "" && isset($contractor_id) && $contractor_id != "") {
			$this->db->where('discount_id', $discount_id);
			$this->db->where('discount_for_contractor_id', $contractor_id);
		
			if($this->db->update('contractor_discount', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'Discount updated Successfully';
				$response['updatedId']	= $discount_id;
			} else {
				$response["message"] = "Error while updating the discount";
			}	
		} else {
			$response['message']	= 'Invalid discount update, Please try again';
		}
		return $response;
	}

	public function deleteDiscount( $options ) {
		$response = array(
			'status' => 'error'
		);

		$contractor_id	= $options["contractor_id"];
		$discount_id		= $options["discount_id"];

		if(isset($contractor_id) && $contractor_id != "" && isset($discount_id) && $discount_id != "") {
			$this->db->where('discount_id', $discount_id);
			$this->db->where('discount_for_contractor_id', $contractor_id);

			$data = array(
				'is_deleted' 				=> 1
			);
			
			if($this->db->update('contractor_discount', $data)) {
				$response['status']		= "success";
				$response['message']	= "Discount Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the Discount";
			}
		} else {
			$response['message']	= 'Invalid contractor or discount, Please try again';
		}
		return $response;
	}
}