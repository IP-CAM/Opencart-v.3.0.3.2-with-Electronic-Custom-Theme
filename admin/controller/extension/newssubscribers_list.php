<?php
class ControllerExtensionNewssubscribersList extends Controller {
	public function index() {
		$this->load->language('extension/newssubscribers');
		$this->load->model('extension/newssubscribers');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['heading_title'] = $this->language->get('heading_title');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_email'] = $this->language->get('column_email');
		
		$data['column_action'] = $this->language->get('column_action');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		
		if(isset($this->request->get['page'])) {
	            $page = $this->request->get['page'];
		}else{
		        $page = 1;
		}
	
		$data['emails'] = array();
		
		$filter_data = "";
		
		$email_total = $this->model_extension_newssubscribers->getTotalEmails($data);
		
		$results = $this->model_extension_newssubscribers->getEmails($data,($page - 1) * $this->config->get('config_admin_limit'),$this->config->get('config_admin_limit'));

		foreach ($results as $result) {
			$data['emails'][] = array(
				'id' 		=> $result['id'],
				'email' 	=> $result['email_id'],
				'edit'    => $this->url->link('extension/newssubscribers/edit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $result['id'], true)
			);
		}

		$data['user_token'] = $this->session->data['user_token'];
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		return $this->load->view('extension/news_subscriberslist', $data);
	}
}
