<?php
class ControllerExtensionNewsSetting extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/newssetting');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_setting->editSetting('newssetting', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/newssetting', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		
		$data['entry_text_color'] = $this->language->get('entry_text_color');
		$data['entry_text_color1'] = $this->language->get('entry_text_color1');
		$data['entry_bgtop_color'] = $this->language->get('entry_bgtop_color');
		$data['entry_bgbottom_color'] = $this->language->get('entry_bgbottom_color');
		$data['entry_border_color'] = $this->language->get('entry_border_color');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_hover_color'] = $this->language->get('entry_hover_color');
		$data['entry_container_color'] = $this->language->get('entry_container_color');
		
		$data['help_text_color'] = $this->language->get('help_text_color');		
		$data['help_text_color1'] = $this->language->get('help_text_color1');		
		$data['help_bgtop'] = $this->language->get('help_bgtop');		
		$data['help_bgbottom'] = $this->language->get('help_bgbottom');		
		$data['help_border'] = $this->language->get('help_border');		
		$data['help_hover'] = $this->language->get('help_hover');		
		$data['help_container'] = $this->language->get('help_container');
	
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['dashmenu'] = $this->load->controller('extension/dashmenu');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('extension/newsdashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/newssetting', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		
		$data['action'] = $this->url->link('extension/newssetting', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('extension/newssetting', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['user_token'] = $this->session->data['user_token'];
		
		if (isset($this->request->post['pupbutoncolor'])) {
			$data['pupbutoncolor'] = $this->request->post['pupbutoncolor'];
		} elseif (isset($module_info['pupbutoncolor'])) {
			$data['pupbutoncolor'] = $module_info['pupbutoncolor'];
		} else {
			$data['pupbutoncolor'] = '';
		}
		if (isset($this->request->post['newstext_color'])) {
			$data['newstext_color'] = $this->request->post['newstext_color'];
		} elseif (isset($module_info['newstext_color'])) {
			$data['newstext_color'] = $module_info['newstext_color'];
		} else {
			$data['newstext_color'] = '';
		}
		if (isset($this->request->post['newstextnopop_color'])) {
			$data['newstextnopop_color'] = $this->request->post['newstextnopop_color'];
		} elseif (isset($module_info['newstextnopop_color'])) {
			$data['newstextnopop_color'] = $module_info['newstextnopop_color'];
		} else {
			$data['newstextnopop_color'] = '';
		}
		if (isset($this->request->post['butontextcolor'])) {
			$data['butontextcolor'] = $this->request->post['butontextcolor'];
		} elseif (isset($module_info['butontextcolor'])) {
			$data['butontextcolor'] = $module_info['butontextcolor'];
		} else {
			$data['butontextcolor'] = '';
		}
		if (isset($this->request->post['buttonbg'])) {
			$data['buttonbg'] = $this->request->post['buttonbg'];
		} elseif (isset($module_info['buttonbg'])) {
			$data['buttonbg'] = $module_info['buttonbg'];
		} else {
			$data['buttonbg'] = '';
		}
		if (isset($this->request->post['newslatertext'])) {
			$data['newslatertext'] = $this->request->post['newslatertext'];
		} elseif (isset($module_info['newslatertext'])) {
			$data['newslatertext'] = $module_info['newslatertext'];
		} else {
			$data['newslatertext'] = '';
		}
		if (isset($this->request->post['newsbutonhover'])) {
			$data['newsbutonhover'] = $this->request->post['newsbutonhover'];
		} elseif (isset($module_info['newsbutonhover'])) {
			$data['newsbutonhover'] = $module_info['newsbutonhover'];
		} else {
			$data['newsbutonhover'] = '';
		}
		if (isset($this->request->post['containerbg'])) {
			$data['containerbg'] = $this->request->post['containerbg'];
		} elseif (isset($module_info['containerbg'])) {
			$data['containerbg'] = $module_info['containerbg'];
		} else {
			$data['containerbg'] = '';
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/newssetting', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/newssetting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['newssetting_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 2) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}
		
		

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/newssetting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}