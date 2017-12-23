<?php
class ControllerExtensionTotalEmailtemplate extends Controller {
	public function index() {
		$this->load->language('extension/totalemailtemplate');
		$this->load->model('extension/tmdnewsletter');	
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = $this->language->get('text_view');

		$data['user_token'] = $this->session->data['user_token'];
		
		$data['templatetotal'] = $this->model_extension_tmdnewsletter->getTotalNewslaters();

		
		return $this->load->view('extension/totalemailtemplate', $data);
	}
}
