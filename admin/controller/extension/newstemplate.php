<?php
class ControllerExtensionNewsTemplate extends Controller {
	private $error = array();
	public function index()	{
		$this->load->language('extension/newstemplate');
		$this->document->setTitle($this->language->get('heading_title'));
		$data['dashmenu'] = $this->load->controller('extension/dashmenu');
		$this->load->model('extension/tmdnewsletter');
		$this->getList();
		}

	public function add() {
		$this->load->language('extension/newstemplate');
		$data['dashmenu'] = $this->load->controller('extension/dashmenu');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/tmdnewsletter');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
			{
			$this->model_extension_tmdnewsletter->addNewsletter($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['sort']))
				{
				$url.= '&sort=' . $this->request->get['sort'];
				}

			if (isset($this->request->get['order']))
				{
				$url.= '&order=' . $this->request->get['order'];
				}

			if (isset($this->request->get['page']))
				{
				$url.= '&page=' . $this->request->get['page'];
				}

			$this->response->redirect($this->url->link('extension/newstemplate', 'user_token=' . $this->session->data['user_token'] . $url, true));
			}

		$this->getForm();
		}

	public function delete() {
		$this->load->language('extension/newstemplate');
		$data['dashmenu'] = $this->load->controller('extension/dashmenu');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/tmdnewsletter');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach($this->request->post['selected'] as $newstemplate_id) {
				$this->model_extension_tmdnewsletter->deleteNewletter($newstemplate_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['sort']))
				{
				$url.= '&sort=' . $this->request->get['sort'];
				}

			if (isset($this->request->get['order']))
				{
				$url.= '&order=' . $this->request->get['order'];
				}

			if (isset($this->request->get['page']))
				{
				$url.= '&page=' . $this->request->get['page'];
				}

			$this->response->redirect($this->url->link('extension/newstemplate', 'user_token=' . $this->session->data['user_token'] . $url, true));
			}

		$this->getList();
		}

	public function edit() {
		$this->load->language('extension/newstemplate');
		$data['dashmenu'] = $this->load->controller('extension/dashmenu');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('extension/tmdnewsletter');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
			{
			$this->model_extension_tmdnewsletter->EditNewsleter($this->request->get['newstemplate_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['sort']))
				{
				$url.= '&sort=' . $this->request->get['sort'];
				}

			if (isset($this->request->get['order']))
				{
				$url.= '&order=' . $this->request->get['order'];
				}

			if (isset($this->request->get['page']))
				{
				$url.= '&page=' . $this->request->get['page'];
				}

			$this->response->redirect($this->url->link('extension/newstemplate', 'user_token=' . $this->session->data['user_token'] . $url, true));
			}

		$this->getForm();
		}

	private  function getList() {
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home') ,
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title') ,
			'href' => $this->url->link('extension/newstemplate', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);
		$data['add'] = $this->url->link('extension/newstemplate/add', 'user_token=' . $this->session->data['user_token'], true);
		$data['delete'] = $this->url->link('extension/newstemplate/delete', 'user_token=' . $this->session->data['user_token'], true);
		$data['export'] = $this->url->link('extension/newstemplate/export', 'user_token=' . $this->session->data['user_token'], true);
		$data['dashmenu'] = $this->load->controller('extension/dashmenu');
		$data['text_export'] = $this->language->get('text_export');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_subject'] = $this->language->get('column_subject');
		$data['column_sortorder'] = $this->language->get('column_sortorder');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_shortcut'] = $this->language->get('button_shortcut');
		$data['button_delete'] = $this->language->get('button_delete');
		if (isset($this->error['warning']))
			{
			$data['error_warning'] = $this->error['warning'];
			}
		  else
			{
			$data['error_warning'] = '';
			}

		if (isset($this->session->data['success']))
			{
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
			}
		  else
			{
			$data['success'] = '';
			}

		$data['newsletter'] = array();	
		
		$filter_data = array(
		'sort'  => $sort,
		'order' => $order,
		'start' => ($page - 1) * $this->config->get('config_limit_admin'),
		'limit' => $this->config->get('config_limit_admin')
		);
				
		$newsletter_total = $this->model_extension_tmdnewsletter->getTotalNewslaters();
		$results = $this->model_extension_tmdnewsletter->getNewletterdes($filter_data);
		
		if (isset($results)) {
			foreach($results as $result) {
				if ($result['status'])
					{
					$status = $this->language->get('text_enable');
					}
				  else
					{
					$status = $this->language->get('text_disable');
					}

				$data['newsletter'][] = array(
					'newstemplate_id' => $result['newstemplate_id'],
					'name' => $result['name'],
					'subject' => $result['subject'],
					'sortorder' => $result['sortorder'],
					'status' => $status,
					'edit' => $this->url->link('extension/newstemplate/edit', 'user_token=' . $this->session->data['user_token'] . '&newstemplate_id=' . $result['newstemplate_id'] . $url, true)
				);
				}
		}
		
		$pagination = new Pagination();
		$pagination->total = $newsletter_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/newstemplate', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($newsletter_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($newsletter_total - $this->config->get('config_limit_admin'))) ? $newsletter_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $newsletter_total, ceil($newsletter_total / $this->config->get('config_limit_admin')));

		
		$data['sort_name'] = $this->url->link('extension/newstemplate/', 'user_token=' . $this->session->data['user_token']);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/newstemplate_list', $data));
		}

	private  function getForm()	{
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_form'] = !isset($this->request->get['id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_subject'] = $this->language->get('entry_subject');
		$data['entry_sortorder'] = $this->language->get('entry_sortorder');
		$data['entry_Status'] = $this->language->get('entry_Status');
		$data['entry_code'] = $this->language->get('entry_code');
		$data['dashmenu'] = $this->load->controller('extension/dashmenu');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_shortcut'] = $this->language->get('button_shortcut');
		$data['text_enable'] = $this->language->get('text_enable');
		$data['text_disable'] = $this->language->get('text_disable');
		$data['tab_general'] = $this->language->get('tab_general');
		if (isset($this->error['warning']))
			{
			$data['error_warning'] = $this->error['warning'];
			}
		  else
			{
			$data['error_warning'] = '';
			}

		if (isset($this->error['warning']))
			{
			$data['error_warning'] = $this->error['warning'];
			}
		  else
			{
			$data['error_warning'] = '';
			}

		if (isset($this->error['name']))
			{
			$data['error_name'] = $this->error['name'];
			}
		  else
			{
			$data['error_name'] = '';
			}

		if (isset($this->error['subject']))
			{
			$data['error_subject'] = $this->error['subject'];
			}
		  else
			{
			$data['error_subject'] = '';
			}

		if (isset($this->error['description']))
			{
			$data['error_description'] = $this->error['description'];
			}
		  else
			{
			$data['error_description'] = '';
			}

		if (isset($this->error['sortorder']))
			{
			$data['error_sortorder'] = $this->error['sortorder'];
			}
		  else
			{
			$data['error_sortorder'] = '';
			}

		if (isset($this->error['status']))
			{
			$data['error_status'] = $this->error['status'];
			}
		  else
			{
			$data['error_status'] = '';
			}

		/* if (isset($this->request->get['newstemplate_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST'))
			{
			$tmdnewsletter_info = $this->model_tmdnewsletter_tmdnewsletter->getNewletter($this->request->get['newstemplate_id']);
			} */
			
		if (isset($this->request->get['newstemplate_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$tmdnewsletter_info = $this->model_extension_tmdnewsletter->getNewletter($this->request->get['newstemplate_id']);
		}	

		$data['user_token'] = $this->session->data['user_token'];
		
		if (isset($this->request->post['name']))
			{
			$data['name'] = $this->request->post['name'];
			}
		elseif (isset($tmdnewsletter_info['name']))
			{
			$data['name'] = $tmdnewsletter_info['name'];
			}
		  else
			{
			$data['name'] = '';
			}
			
		if (isset($this->request->post['status']))
			{
			$data['status'] = $this->request->post['status'];
			}
		elseif (isset($tmdnewsletter_info['status']))
			{
			$data['status'] = $tmdnewsletter_info['status'];
			}
		  else
			{
			$data['status'] = '';
			}

		if (isset($this->request->post['sortorder']))
			{
			$data['sortorder'] = $this->request->post['sortorder'];
			}
		elseif (isset($tmdnewsletter_info['sortorder']))
			{
			$data['sortorder'] = $tmdnewsletter_info['sortorder'];
			}
		  else
			{
			$data['sortorder'] = '';
			}

		
			
		if (isset($this->request->post['newsletter_template'])) {
			$data['newsletter_template'] = $this->request->post['newsletter_template'];
		} elseif (isset($this->request->get['newstemplate_id'])) {
			$data['newsletter_template'] = $this->model_extension_tmdnewsletter->getNewslaterDescriptions($this->request->get['newstemplate_id']);
		} else {
			$data['newsletter_template'] = array();
		}

		$url = '';
		if (isset($this->request->get['sort']))
			{
			$url.= '&sort=' . $this->request->get['sort'];
			}

		if (isset($this->request->get['order']))
			{
			$url.= '&order=' . $this->request->get['order'];
			}

		if (isset($this->request->get['page']))
			{
			$url.= '&page=' . $this->request->get['page'];
			}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true) ,
			'text' => $this->language->get('text_home') ,
			'separator' => FALSE
		);
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/newstemplate', 'user_token=' . $this->session->data['user_token'], true) ,
			'text' => $this->language->get('heading_title') ,
			'separator' => ' :: '
		);
		if (!isset($this->request->get['newstemplate_id']))
			{
			$data['action'] = $this->url->link('extension/newstemplate/add', 'user_token=' . $this->session->data['user_token'], true);
			}
		  else
			{
			$data['action'] = $this->url->link('extension/newstemplate/edit', 'user_token=' . $this->session->data['user_token'] . '&newstemplate_id=' . $this->request->get['newstemplate_id'], true);
			}

		$data['user_token'] = $this->session->data['user_token'];
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['cancel'] = $this->url->link('extension/newstemplate', 'user_token=' . $this->session->data['user_token'], true);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/newstemplate_form', $data));
		}

	private function validateForm()
		{
		$this->load->model('extension/tmdnewsletter');
		if (!$this->user->hasPermission('modify', 'extension/newstemplate'))
			{
			$this->error['warning'] = $this->language->get('error_permission');
			}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32))
			{
			$this->error['name'] = $this->language->get('error_name');
			}

		foreach($this->request->post['newsletter_template'] as $language_id => $value)
			{
			if ((utf8_strlen($value['subject']) < 3) || (utf8_strlen($value['subject']) > 50))
				{
				$this->error['subject'][$language_id] = $this->language->get('error_subject');
				}

			if ((utf8_strlen($value['description']) < 3) || (utf8_strlen($value['description']) > 1000))
				{
				$this->error['description'][$language_id] = $this->language->get('error_description');
				}
			}

		return !$this->error;
		}

	private function validateDelete()
		{
		if (!$this->user->hasPermission('modify', 'tmdnewsletter/newstemplate'))
			{
			$this->error['warning'] = $this->language->get('error_permission');
			}

		return !$this->error;
		}
	}

?>
