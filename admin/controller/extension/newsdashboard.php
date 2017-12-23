<?php
class ControllerExtensionNewsdashboard extends Controller {
	public function index() {
		$this->load->language('extension/newsdashboard');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_send'] = $this->language->get('text_send');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('extension/newsdashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/newsdashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		// Check install directory exists
		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];
		
		$data['dashmenu'] = $this->load->controller('extension/dashmenu');
		
		$data['templatelist'] =array();
		
		$this->load->model('extension/tmdnewsletter');
		$filter_data = array();
		$template_info = $this->model_extension_tmdnewsletter->getNewletterdes($filter_data);
		
		foreach($template_info as $info) {
			$data['templatelist'][] = array(
			'newstemplate_id' => $info['newstemplate_id'],
			'name' => $info['name'],
			);
		}
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['subscribers'] = $this->load->controller('extension/totalsubscribers');
		
		$data['unsubscribers'] = $this->load->controller('extension/totalemailtemplate');
		
		$data['subscriblist'] = $this->load->controller('extension/newssubscribers_list');
		$data['footer'] = $this->load->controller('common/footer');

		// Run currency update
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');

			$this->model_localisation_currency->refresh();
		}
			
		$this->response->setOutput($this->load->view('extension/newsdashboard', $data));
	}
	
	
	public function sendtemplatemail() {			
			$json=array();
			$this->load->language('extension/newsdashboard');
			$this->load->model('extension/newssubscribers');
			$this->load->model('extension/tmdnewsletter');
			if(empty($this->request->post['templatename']))
			{
				$json['error']='Please Select template';
			}
			else
			{	
			$tmdnewsletter_info = $this->model_extension_tmdnewsletter->getNewletter($this->request->post['templatename']);
				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else {
					$page = 1;
				}
				$subject=html_entity_decode($tmdnewsletter_info['subject']);		
				$message=html_entity_decode($tmdnewsletter_info['description']);	
				$data = array(
							'start'             => ($page - 1) * 10,
							'limit'             => 10
						);	
			
							
								$store_name = $this->config->get('config_name');
												
				$emails = $this->model_extension_newssubscribers->getEmails($data);
				
				$email_total = $this->model_extension_newssubscribers->getTotalEmails($data);
				if ($emails) {
					 $start = ($page - 1) * 10;
					$end = $start + 10;

					if ($end < $email_total) {
						$json['success'] = sprintf($this->language->get('text_sent'), $start, $email_total);
					} else {
						$json['success'] = $this->language->get('text_success');
					}

					if ($end < $email_total) {
						$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/newsdashboard/sendtemplatemail', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true));
					} else {
						$json['next'] = '';
					}

					
					foreach ($emails as $email) {
						
						if (preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email['email_id'])) {
							$mail = new Mail();
							$mail->protocol = $this->config->get('config_mail_protocol');
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
							$mail->smtp_port = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
							$mail->setTo($email['email_id']);
							$mail->setFrom($this->config->get('config_email'));
							$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
							$mail->setSubject($subject);
							$mail->setHtml($message);
							$mail->send();
						}
					}
				}
				
			}
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			
		}
}
