<?php

class Model_contractors extends CI_Model {
	
	public function getContractorsList($record = "", $zip = "", $serviceZip = "") {
		//$this->db->where('deleted', '0');
		
		if(isset($record) && !is_null($record)) {
			//echo "record =>"; print_r(array_filter($record)); echo "--count =>".count($record)."--imp--".implode(",", $record)."<br/>";
			if(is_array($record) && implode(",", $record) != "" ) {
				$this->db->where_in('id', $record);
			} else if($record != "") {
				$this->db->where('id', $record);
			}
		}

		if(isset($zip) && !is_null($zip) && $zip != "") {
			//echo "zip =>"; print_r($zip); echo "--count =>".count($zip)."<br/>";
			if(is_array($zip)) {
				for($i = 0; $i < count($zip); $i++) {
					if($zip[$i] != "") $this->db->or_like('pin_code', $zip[$i]);	
				}
			} else if($zip != "") {
				$this->db->or_like('pin_code', $zip);
			}
		}

		if(isset($serviceZip) && !is_null($serviceZip) && $serviceZip != "") {
			//echo "serviceZip =>"; print_r(array_filter($serviceZip)); echo "--count =>".count($serviceZip)."--imp--".implode(",", $serviceZip)."<br/>";
			if(is_array($serviceZip)) {
				for($i = 0; $i < count($serviceZip); $i++) {
					if($serviceZip[$i] != "") $this->db->or_like('service_area', $serviceZip[$i]);	
				}
			} else if($service_area != "") {
				$this->db->or_like('service_area', $serviceZip);
			}
		}


		$this->db->select([
			"*", 
			"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
			"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
			"created_on",
			"updated_on"
		]);

		$query = $this->db->from('contractor')->get();

		//echo $this->db->last_query();
		
		$response = array();
		
		if($this->db->_error_number() == 0) {
			$response["status"] 		= "success";
			$response["contractors"] 	= $query->result();
		} else {
			$response["status"] 		= "error";
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}
		
		return $response;
	}

	public function insert($data) {
		$response = array();
		if($this->db->insert('contractor', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "contractor Inserted Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	public function update($data, $record) {
		$response = array(
			'status' => 'error'
		);
		if($record) {
			$this->db->where('id', $record);
		
			if($this->db->update('contractor', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'contractor updated Successfully';
				$response['updatedId']	= $record;
			} else {
				$response["message"] = "Error while updating the records";
			}	
		} else {
			$response['message']	= 'Invalid contractor, Please try again';
		}
		return $response;
	}

	public function deleteRecord($record) {
		$response = array(
			'status' => 'error'
		);
		if($record && $record!= "") {
			$this->db->where('id', $record);

			$data = array(
				'deleted' 				=> 1
			);
			
			if($this->db->update('contractor', $data)) {
				$response['status']		= "success";
				$response['message']	= "contractor Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the contractor";
			}
		} else {
			$response['message']	= 'Invalid contractor, Please try again';
		}
		return $response;
	}

	public function getTradesList( $options = array()) {
		
		$contractorId 	= $options["contractor_id"];
		$trade_id 		= isset($options["trade_id"]) ? $options["trade_id"] : "";

		$response = array('status' => "error");

		if(empty($contractorId)) {
			$response["message"] = "Please provide contractor ID";
			return $response;
		}

		$this->db->where('trade_belongs_to_contractor_id', $contractorId);
		$this->db->where('is_deleted', 0);

		if(isset($trade_id) && !empty($trade_id)) {
			$this->db->where('trade_id', $trade_id);
		}

		if(isset($trade_parent) && !empty($trade_parent)) {
			$this->db->where('trade_parent', $trade_parent);
		}
		
		$this->db->select([
			"*", 
			"DATE_FORMAT(created_on, \"%d-%m-%y %H:%i:%S\") as created_on_for_view", 
			"DATE_FORMAT( updated_on, \"%d-%m-%y %H:%i:%S\") as updated_on_for_view",
			"created_on",
			"updated_on"
		]);

		$query = $this->db->from('trades')->get();

		if($this->db->_error_number() == 0) {
			$response["status"] 		= "success";
			$response["tradesList"] 	= $query->result();
		} else {
			$response["errorCode"] 		= $this->db->_error_number();
			$response["errorMessage"] 	= $this->db->_error_message();
		}

		return $response;
	}

	public function insertTrade($data) {
		$response = array();
		if($this->db->insert('trades', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "Trade Inserted Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	public function updateTrade( $options ) {
		$response = array(
			'status' => 'error'
		);
		
		$data 			= $options["data"];
		$trade_id 		= $options["trade_id"];
		$contractor_id 	= $options["contractor_id"];
		$main_trade_id 	= $options["main_trade_id"];

		if(isset($trade_id) && $trade_id != "" && isset($contractor_id) && $contractor_id != "") {
			$this->db->where('trade_id', $trade_id);
			$this->db->where('trade_belongs_to_contractor_id', $contractor_id);
		
			if($this->db->update('trades', $data)) {
				$response['status']		= 'success';
				$response['message']	= 'Trade updated Successfully';
				$response['updatedId']	= $trade_id;
			} else {
				$response["message"] = "Error while updating the trade";
			}	
		} else {
			$response['message']	= 'Invalid trade update, Please try again';
		}
		return $response;
	}

	public function deleteTradeRecord( $options ) {
		$response = array(
			'status' => 'error'
		);

		$contractor_id	= $options["contractor_id"];
		$trade_id		= $options["trade_id"];

		if(isset($contractor_id) && $contractor_id != "" && isset($trade_id) && $trade_id != "") {
			$this->db->where('trade_id', $trade_id);
			$this->db->where('trade_belongs_to_contractor_id', $contractor_id);

			$data = array(
				'is_deleted' 				=> 1
			);
			
			if($this->db->update('trades', $data)) {
				$response['status']		= "success";
				$response['message']	= "Trade Deleted Successfully";
			} else {
				$response["message"] = "Error while deleting the trade";
			}
		} else {
			$response['message']	= 'Invalid contractor or trade, Please try again';
		}
		return $response;
	}

	public function getDiscountList( $options ) {
		$response = array(
			'status' => 'error'
		);
		$contractor_id	= $options["contractor_id"];
		$discount_id	= isset($options["discount_id"]) ? $options["discount_id"] : "";

		if(isset($contractor_id) && $contractor_id != "") {
			$this->db->where('discount_for_contractor_id', $contractor_id);

			if(isset($discount_id) && $discount_id != "") {
				$this->db->where('discount_id', $discount_id);
			}

			$this->db->where('is_deleted', "0");

			$this->db->select([
				"*",
				"DATE_FORMAT(discount_from_date, \"%m/%d/%y\") as discount_from_date_input_box",
				"DATE_FORMAT(discount_to_date, \"%m/%d/%y\") as discount_to_date_input_box", 
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
				"DATE_FORMAT(testimonial_date, \"%m/%d/%y\") as testimonial_date_input_box",
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