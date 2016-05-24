<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_content extends CI_Controller {

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

	public function showAll() {
		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$response = array(
			'status'	=> "error"
		);

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN ) {
			$response["message"] 			= "No permission to execute this operation";
			print_r(json_encode($response));
			return false;
		}
		
		$this->load->model('web_contents/model_home_content');

		$getParams = array(
			"dataFor" => "all"
		);
		$newsData = $this->model_home_content->getNewsData();

		$viewParams = array(
			'news' => $newsData
		);
		
		$newsForm = $this->load->view("web_content/home_content/newsInputFom", $viewParams, true);

		$resourceData = $this->model_home_content->getResourceData();

		$viewParams = array(
			'resource' => $resourceData
		);
		
		$resourceForm = $this->load->view("web_content/home_content/resourceInputFom", $viewParams, true);

		$viewPage = array(
			'news_content' => $newsForm,
			'resource_content' => $resourceForm
		);

		echo $this->load->view("web_content/home_content/all_data", $viewPage, true);
	}

	public function addNews() {

		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$response = array(
			'status'	=> "error"
		);

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN ) {
			$response["message"] 			= "No permission to execute this operation";
			print_r(json_encode($response));
			return false;
		}

		$this->load->model('web_contents/model_home_content');

		$newNewsContent = $this->input->post('news_content');

		$deleteResponse = $this->deleteNews();

		if( $deleteResponse['status'] == "success") {
			$data = array(
			   'news_content' 	=> $newNewsContent,
			   'created_by'		=> $this->session->userdata('logged_in_user_id'),
			   'created_on' 	=> date("Y-m-d H:i:s")
			);

			//print_r( $data );

			$inserted = $this->model_home_content->insertNewsData($data);

			print_r(json_encode($inserted));
		} else {
			print_r(json_encode($deleteResponse));
		}
	}

	public function deleteNews() {
		$this->load->model('web_contents/model_home_content');
		$deleteResponse = $this->model_home_content->deleteNewsData();
		return $deleteResponse;
	}

	public function getNewsData() {
		$this->load->model('web_contents/model_home_content');

		$getParams = array(
			"dataFor" => "all"
		);
		$newsData = $this->model_home_content->getNewsData();

		return $newsData;
	}

	public function addResource() {

		if(!is_logged_in()) {
			print_r(json_encode(response_for_not_logged_in()));
			return false;
		}

		$response = array(
			'status'	=> "error"
		);

		if($this->session->userdata('logged_in_role_disp_name') != ROLE_ADMIN ) {
			$response["message"] 			= "No permission to execute this operation";
			print_r(json_encode($response));
			return false;
		}

		$this->load->model('web_contents/model_home_content');

		$newResourceContent = $this->input->post('resource_content');

		$deleteResponse = $this->deleteResource();

		if( $deleteResponse['status'] == "success") {
			$data = array(
			   'resource_content' 	=> $newResourceContent,
			   'created_by'		=> $this->session->userdata('logged_in_user_id'),
			   'created_on' 	=> date("Y-m-d H:i:s")
			);

			//print_r( $data );

			$inserted = $this->model_home_content->insertResourceData($data);

			print_r(json_encode($inserted));
		} else {
			print_r(json_encode($deleteResponse));
		}
	}

	public function deleteResource() {
		$this->load->model('web_contents/model_home_content');
		$deleteResponse = $this->model_home_content->deleteResourceData();
		return $deleteResponse;
	}
}
?>