<?php
class Model_home_content extends CI_Model {
	function getNewsData() {
		$this->db->where('is_deleted', '0');
		$query = $this->db->from('news_content')->get();
		$news = $query->result();

		if(count($news)) {
			return $news[0]->news_content;
		}

		return false;
	}

	function insertNewsData( $data ) {
		$response = array();
		if($this->db->insert('news_content', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "News Updated Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	function deleteNewsData( $record = "") {
		$response = array(
			'status' => 'error'
		);
		if($record && $record!= "") {
			$this->db->where('news_id', $record);
		}

		$data = array(
			'is_deleted' => 1
		);
		
		if($this->db->update('news_content', $data)) {
			$response['status']		= "success";
			$response['message']	= "News Deleted Successfully";
		} else {
			$response["message"] = "Error while deleting the news";
		}
		return $response;
	}

	function getResourceData() {
		$this->db->where('is_deleted', '0');
		$query = $this->db->from('resource_content')->get();
		$resource = $query->result();

		if(count($resource)) {
			return $resource[0]->resource_content;
		}

		return false;
	}

	function insertResourceData( $data ) {
		$response = array();
		if($this->db->insert('resource_content', $data)) {
			$response['status'] 		= 'success';
			$response['insertedId']	= $this->db->insert_id();
			$response['message']		= "Resource Updated Successfully";
		} else {
			$response['status'] 		= 'error';
			$response['message']		= $this->db->_error_message();
		}
		return $response;
	}

	function deleteResourceData( $record = "") {
		$response = array(
			'status' => 'error'
		);
		if($record && $record!= "") {
			$this->db->where('resource_id', $record);
		}

		$data = array(
			'is_deleted' => 1
		);
		
		if($this->db->update('resource_content', $data)) {
			$response['status']		= "success";
			$response['message']	= "Resource Deleted Successfully";
		} else {
			$response["message"] = "Error while deleting the resource";
		}
		return $response;
	}
}