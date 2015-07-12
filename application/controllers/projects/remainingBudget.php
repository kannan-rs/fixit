<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RemainingBudget extends CI_Controller {

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

	public function getListWithForm() {
		
		$projectId 		= $this->input->post("projectId");
		$openAs 		= $this->input->post('openAs') ? $this->input->post('openAs') : "";
		$popupType 		= $this->input->post('popupType') ? $this->input->post('popupType') : "";

		$this->load->model('projects/model_remainingBudget');
		$rbResponse = $this->model_remainingBudget->getList( $projectId );

		$inputFormParams = array(
			'openAs' 		=> $openAs,
			'popupType' 	=> $popupType
		);

		$inputForm = $this->load->view("projects/remainingBudget/createForm", $inputFormParams, true);

		$listParams = array(
			'budgetList' 	=> $rbResponse['paidFromBudget']
		);

		$listData = $this->load->view("projects/remainingBudget/budgetList", $listParams, true);

		echo $listData.$inputForm;
	}

	public function add() {
		$this->load->model('projects/model_remainingBudget');

		$data = array(
			'project_id' 	=> $this->input->post('projectId'),
			'date' 			=> $this->input->post('date'),
			'descr' 		=> $this->input->post('descr'),
			'amount' 		=> $this->input->post('amount'),
			'created_by'	=> $this->session->userdata('user_id'),
			'updated_by'	=> $this->session->userdata('user_id'),
			'created_on'	=> date("Y-m-d H:i:s"),
			'updated_on'	=> date("Y-m-d H:i:s")
		);

		$insert_rb = $this->model_remainingBudget->insert($data);
		print_r(json_encode($insert_rb));
	}

	public function update() {
		$this->load->model('projects/model_remainingBudget');

		$remainingBudgetId 			= $this->input->post('remainingBudgetId');

		$data = array(
			'project_id' 	=> $this->input->post('projectId'),
			'date' 			=> $this->input->post('date'),
			'descr' 		=> $this->input->post('descr'),
			'amount' 		=> $this->input->post('amount'),
			'updated_by'	=> $this->session->userdata('user_id'),
			'updated_on'	=> date("Y-m-d H:i:s")
			
		);

		$update_rd = $this->model_remainingBudget->update($data, $remainingBudgetId);

		print_r(json_encode($update_rd));
	}

	public function deleteRecord() {
		$this->load->model('projects/model_remainingBudget');

		$remainingBudgetId = $this->input->post('remainingBudgetId');
		$delete_rB = $this->model_remainingBudget->deleteRecord($remainingBudgetId);

		print_r(json_encode($delete_rB));
	}
}