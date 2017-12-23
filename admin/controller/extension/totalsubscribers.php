<?php
class ControllerExtensionTotalSubscribers extends Controller {
	public function index() {
		$this->load->language('extension/totalsubscribers');
		$this->load->model('extension/newssubscribers');	
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = $this->language->get('text_view');

		$data['user_token'] = $this->session->data['user_token'];
		$filter_data = array();	
		$data['newslatortotal'] = $this->model_extension_newssubscribers->getTotalEmails($filter_data);
		
		return $this->load->view('extension/totalsubscribers', $data);
	}
}
