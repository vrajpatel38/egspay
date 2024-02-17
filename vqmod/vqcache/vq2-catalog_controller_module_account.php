<?php
class ControllerModuleAccount extends Controller {
	
		protected function octlversion(){
	    	$varray = explode('.', VERSION);
	    	return (int)implode('', $varray);
		}
		protected function lg($keys = ''){
			$module_key = '';
			if($this->octlversion() >= 3000) $module_key = 'module_';

			$language_id = $this->config->get('config_language_id');
			$ls = $this->config->get($module_key.'e_wallet_language');

			if(!is_array($keys)) $keys = array($keys);
			$is_acco = array_keys($keys) !== range(0, count($keys) - 1);

			$return = array();
			foreach ($keys as $key => $value){
				if($is_acco) $new_value = $value;
				else $new_value = $key = $value;

			  	if(isset($ls[$language_id]) && !empty($ls[$language_id][$key])){
			  		$new_value = $ls[$language_id][$key];
			  	}
			  	$return[] = $new_value;
			}
			return $return;
		}
		  
	public function index() {
		$this->load->language('module/account');

		$data['heading_title'] = $this->language->get('heading_title');
	
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}		 
		$data['e_wallet_status'] = $this->config->get($module_key.'e_wallet_status');
		list($data['e_account_title']) = $this->lg('e_account_text');
		$data['e_wallet'] = $this->url->link('account/e_wallet', '', true);
		  

		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_forgotten'] = $this->language->get('text_forgotten');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_password'] = $this->language->get('text_password');
		$data['text_address'] = $this->language->get('text_address');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_reward'] = $this->language->get('text_reward');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_recurring'] = $this->language->get('text_recurring');

		$data['logged'] = $this->customer->isLogged();
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['forgotten'] = $this->url->link('account/forgotten', '', true);
		$data['account'] = $this->url->link('account/account', '', true);
		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['address'] = $this->url->link('account/address', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['order'] = $this->url->link('account/order', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['reward'] = $this->url->link('account/reward', '', true);
		$data['return'] = $this->url->link('account/return', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		$data['recurring'] = $this->url->link('account/recurring', '', true);

		return $this->load->view('module/account', $data);
	}
}