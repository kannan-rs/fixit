<?php
class Form_lib {

	private $CI;
	
	public function __construct() {
        $this->CI =& get_instance();
	}

	/*
	| Generic place to build address UI
	*/
	public function getAddressFile( $data = array() ) {
		$view 			= isset($data["view"]) && $data["view"] != "" ? $data["view"] : '';
		$address_data 	= isset($data["address_data"]) ? $data["address_data"] : array();
		$requestFrom 	= isset($data["requestFrom"]) ? $data["requestFrom"] : "view";
		$hidden 		= isset($data["hidden"]) ? $data["hidden"] : "";
		$id_prefix 		= isset($data["id_prefix"]) ? $data["id_prefix"] : "";

		$this->CI->load->model('utils/model_form_utils');
		
		$addressParams = array();

		$addressParams['addressLine1'] 	= "";
		$addressParams['addressLine2'] 	= "";
		$addressParams['city'] 			= "";
		$addressParams['country'] 		= "";
		$addressParams['state'] 		= "";
		$addressParams['zipCode'] 		= "";
		$addressParams['hidden'] 		= "";
		
		if(isset($address_data) && count($address_data)) {
				$stateText = !empty($address_data->state) ? $this->CI->model_form_utils->getCountryStatus($address_data->state)[0]->name : "";

				$addressParams['addressLine1'] 		= $address_data->address1;
				$addressParams['addressLine2'] 		= $address_data->address2;
				$addressParams['city'] 				= $address_data->city;
				$addressParams['country'] 			= $address_data->country;
				$addressParams['state']				=  $requestFrom == 'view' ? $stateText : $address_data->state;
				$addressParams['zipCode'] 			= $address_data->zip_code;
		}

		$addressParams['requestFrom'] 		= $requestFrom;
		$addressParams['id_prefix'] 		= $id_prefix;

		if(!empty($hidden)) {
			$addressParams['hidden'] 			= $hidden;
		}

		if(isset($view) && !empty($view) && $view != 'view') {
			$addressParams['forForm'] 			= $view;
		}

		return $this->CI->load->view("forms/address", $addressParams, true);
	}
	
}
?>