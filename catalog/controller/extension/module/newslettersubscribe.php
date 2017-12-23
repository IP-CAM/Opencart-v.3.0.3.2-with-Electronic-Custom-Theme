<?php  
class ControllerExtensionModuleNewslettersubscribe extends Controller {
  	private $error = array();
	
	
	public function index($setting){
	 
		if(!empty($setting)){
			if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
			} else {
			$server = $this->config->get('config_url');
			}
			
			$data['fburl']=$server;
			$data['google']=$server;
			$data['twet']=$server;
			$data['in']=$server;
			
			$data['newslaterstatus']=$setting['status'];
			if($data['newslaterstatus']){
			$data['conatnerbg']= $setting['containerbg'];
			$data['pupbutoncolor']= $setting['pupbutoncolor'];
			$data['butontextcolor']= $setting['butontextcolor'];
			$data['newstext_color']= $setting['newstext_color'];
			$data['buttonbg']= $setting['buttonbg'];
			$data['newsbutonhover']= $setting['newsbutonhover'];
			$data['newslatertext']= $setting['newslatertext'];
			$data['newstextnopop_color']= $setting['newstextnopop_color'];
			
			}
				
			$data['popup'] =$setting['newslettersubscribe_popup'];
			
			$this->session->data['newslettersubscribe']=$setting;	
			$this->load->model('extension/newslettersubscribe');
			$this->load->language('extension/module/newslettersubscribe');
			$this->model_extension_newslettersubscribe->check_db();
			
			$this->document->addStyle('catalog/view/theme/default/stylesheet/newsletter.css');
			
			return $this->load->view('extension/module/newslettersubscribe', $data);		
		}	  
	}	
	
	public function subscribe(){

		if(isset($this->session->data['newslettersubscribe'])){
				$setting=$this->session->data['newslettersubscribe'];
			}else{$setting='';}
			$prefix_eval="";
	
		$this->language->load('extension/module/newslettersubscribe');
	  
		$this->load->model('extension/newslettersubscribe');
	
		if(isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'],FILTER_VALIDATE_EMAIL)){
           
		if($this->config->get('newslettersubscribe_registered') and $this->model_extension_newslettersubscribe->checkRegisteredUser($this->request->post)){
			   
			   
		$this->model_extension_newslettersubscribe->UpdateRegisterUsers($this->request->post,1);
				
			echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('subscribe').'");$("'.$prefix_eval.' #subscribe")[0].reset();');
			   
		    
		   }else if(!$this->model_extension_newslettersubscribe->checkmailid($this->request->post)){
			 
			 $this->model_extension_newslettersubscribe->subscribe($this->request->post);
		     echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('subscribe').'");$("'.$prefix_eval.' #subscribe")[0].reset();');
			 
			 
				 if($setting['newslettersubscribe_mail_status']){
			   
			    $subject = $this->language->get('mail_subject');	
				
				$message = '<table width="60%" cellpadding="2"  cellspacing="1" border="0"> 
				  	         <tr>
							   <td> Email Id </td>
							   <td> '.$this->request->post['subscribe_email'].' </td>
							 </tr>';
				if(isset($this->request->post['option1'])) {
				   $message .= '<tr> <td>'.$this->config->get('newslettersubscribe_option_field1').'</td> <td>'.$this->request->post['option1'].'</td> </tr>';  
				}
				if(isset($this->request->post['option2'])) {
				   $message .= '<tr> <td>'.$this->config->get('newslettersubscribe_option_field2').'</td> <td>'.$this->request->post['option2'].'</td> </tr>';  
				}
				if(isset($this->request->post['option3'])) {
				   $message .= '<tr> <td>'.$this->config->get('newslettersubscribe_option_field3').'</td> <td>'.$this->request->post['option3'].'</td> </tr>';  
				}
				if(isset($this->request->post['option4'])) {
				   $message .= '<tr> <td>'.$this->config->get('newslettersubscribe_option_field4').'</td> <td>'.$this->request->post['option4'].'</td> </tr>';  
				}
				if(isset($this->request->post['option5'])) {
				   $message .= '<tr> <td>'.$this->config->get('newslettersubscribe_option_field5').'</td> <td>'.$this->request->post['option5'].'</td> </tr>';  
				}
				if(isset($this->request->post['option6'])) {
				   $message .= '<tr> <td>'.$this->config->get('newslettersubscribe_option_field6').'</td> <td>'.$this->request->post['option6'].'</td> </tr>';  
				} 
				 $message .= '</table>';
	 
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');				
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->request->post['subscribe_email']);
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject($subject);
				$mail->setHtml($message);
				$mail->send();
				 
			}
			 
		   }else{
				  echo('$("'.$prefix_eval.' #subscribe_result").html("<div class=\"alert alert-danger\">'.$this->language->get('alreadyexist').'</div>");$("'.$prefix_eval.' #subscribe")[0].reset();');	 
		   }
		   $data['success'] ='email go successfully';
		   
	  }else{
	   $data['login'] = $this->url->link('account/login');
	    echo('$("'.$prefix_eval.' #subscribe_result").html("<div class=\"alert alert-danger\">'.$this->language->get('error_invalid').'</div>")');
		
		   $data['error'] = $this->language->get('error_invalid');
	  }
	}

	
	public function unsubscribe(){

		if(isset($this->session->data['newslettersubscribe'])){
				$setting=$this->session->data['newslettersubscribe'];
			}else{$setting='';}
			$prefix_eval="";	
		
	  $prefix_eval="";
	  
	  $this->language->load('extension/module/newslettersubscribe');
	  
	  $this->load->model('extension/newslettersubscribe');
	   
	  if(isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'],FILTER_VALIDATE_EMAIL)){
            
		    if($this->config->get('newslettersubscribe_registered') and $this->model_extension_newslettersubscribe->checkRegisteredUser($this->request->post)){
			   
			    $this->model_extension_newslettersubscribe->UpdateRegisterUsers($this->request->post,0);
				
			echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('unsubscribe').'");$("'.$prefix_eval.' #subscribe")[0].reset();');
			   
		    
		   }else if(!$this->model_extension_newslettersubscribe->checkmailid($this->request->post)){
			 
		     echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('notexist').'");$("'.$prefix_eval.' #subscribe")[0].reset();');
			 
		   } else if($setting['option_unsubscribe']) {
				 $this->model_extension_newslettersubscribe->unsubscribe($this->request->post);
				 echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('unsubscribe').'");$("'.$prefix_eval.' #subscribe")[0].reset();');
		   }
		   
	  }else{
	    echo('$("'.$prefix_eval.' #subscribe_result").html("<div class=\"alert alert-danger\">'.$this->language->get('error_invalid').'</div>")');
	  }
	}

	protected function loadmodule($setting) {
			
		
		
		
		$this->language->load('extension/module/newslettersubscribe');

      	$data['heading_title'] = $this->language->get('heading_title');	
		
      	$data['entry_name'] = $this->language->get('entry_name');	
		
      	$data['entry_email'] = $this->language->get('entry_email');	
		
      	$data['entry_button'] = $this->language->get('entry_button');	
		
      	$data['entry_unbutton'] = $this->language->get('entry_unbutton');	
      	$data['button_signup'] = $this->language->get('button_signup');	
      	$data['text_signup'] = $this->language->get('text_signup');	
      	$data['text_nemail'] = $this->language->get('text_nemail');	
      	$data['text_sub'] = $this->language->get('text_sub');	
      	$data['text_tosee'] = $this->language->get('text_tosee');	
      	//xml
		
		
      	 $data['option_unsubscribe'] = $setting['option_unsubscribe'];	
		
      	$data['option_fields'] = $this->config->get('newslettersubscribe_option_field');	
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		//xml
		
		//xml
		$data['option_fields1'] = $this->config->get('$newslettersubscribe_option_field1');	
		$data['option_fields2'] = $this->config->get('$newslettersubscribe_option_field2');	
		$data['option_fields3'] = $this->config->get('$newslettersubscribe_option_field3');	
		$data['option_fields4'] = $this->config->get('$newslettersubscribe_option_field4');	
		$data['option_fields5'] = $this->config->get('$newslettersubscribe_option_field5');	
		$data['option_fields6'] = $this->config->get('$newslettersubscribe_option_field6');	
		//$data['thickbox'] = $setting['newslettersubscribe_thickbox'];
			
		$data['text_subscribe'] = $this->language->get('text_subscribe');	
		
		
		$this->id = 'newslettersubscribe';
		
		
		return $data;
	}
}
?>
