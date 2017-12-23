<?php
class ControllerExtensionDashmenu extends Controller {
	public function index() {
		$this->load->language('extension/dashmenu');

		$data['text_dash'] = $this->language->get('text_dash');
		$data['text_tempalte'] = $this->language->get('text_tempalte');
		$data['text_sett'] = $this->language->get('text_sett');
		$data['text_addmodule'] = $this->language->get('text_addmodule');
		$data['text_newsletter'] = $this->language->get('text_newsletter');

		$data['user_token'] = $this->session->data['user_token'];
		
		
		$data['dashboard'] = $this->url->link('extension/newsdashboard', 'user_token=' . $this->session->data['user_token'], true);
		$data['tmdnewssetting'] = $this->url->link('extension/newssetting', 'user_token=' . $this->session->data['user_token'], true);
		$data['template'] = $this->url->link('extension/newstemplate', 'user_token=' . $this->session->data['user_token'], true);
		$data['newsletter'] = $this->url->link('extension/newssubscribers', 'user_token=' . $this->session->data['user_token'], true);
		$data['addmodule'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['dashboard_menu']=false;
		$data['template_menu']=false;
		$data['setting_menu']=false;				
		$data['module_menu']=false;				
		$data['newsletter_menu']=false;				
		if(isset($this->request->get['route']) && $this->request->get['route']=='extension/newsdashboard')
		{
		 $data['dashboard_menu']=true;
		}
		
		if(!isset($this->request->get['route']))
		{
		 $data['dashboard_menu']=true;
		}
		
		if(isset($this->request->get['route']) && $this->request->get['route']=='extension/newssubscribers')
		{
		$data['newsletter_menu']=true;
		}
		
		if(isset($this->request->get['route']) && $this->request->get['route']=='extension/newstemplate')
		{
		$data['template_menu']=true;
		}
		
		if(isset($this->request->get['route']) && $this->request->get['route']=='extension/newssetting'){
		$data['setting_menu']=true;
		}
		
		if(isset($this->request->get['route']) && $this->request->get['route']=='extension/module'){
		$data['module_menu']=true;
		}
		
		return $this->load->view('extension/dashmenu', $data);
	}
}
