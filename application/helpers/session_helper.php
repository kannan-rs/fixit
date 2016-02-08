<?php
function is_logged_in() {
    // Get current CodeIgniter instance
    $CI =& get_instance();
    // We need to use $CI->session instead of $this->session
    $logged_in 	= $CI->session->userdata('is_logged_in');
    //echo "logged in-".$logged_in;
    if (!isset($logged_in) || empty($logged_in)) { 
    	return false; 
   	} else { 
   		return true; 
   	}
}

function redirect_if_not_logged_in() {
	if(!is_logged_in()) {
		redirect('/');
	}
}

function response_for_not_logged_in() {
	$response = array(
		"status" => "not_logged_in",
		"message"	=> "Session expired. Please login in again"
	);
	return $response;
}