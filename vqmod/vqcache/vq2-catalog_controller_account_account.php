<?php
class ControllerAccountAccount extends Controller {
	
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
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/account');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		} 

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_my_account'] = $this->language->get('text_my_account');
		$data['text_my_orders'] = $this->language->get('text_my_orders');
		$data['text_my_newsletter'] = $this->language->get('text_my_newsletter');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_password'] = $this->language->get('text_password');
		$data['text_address'] = $this->language->get('text_address');
		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_wishlist'] = $this->language->get('text_wishlist');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_reward'] = $this->language->get('text_reward');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_recurring'] = $this->language->get('text_recurring');

		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['address'] = $this->url->link('account/address', '', true);
		
		$data['credit_cards'] = array();
		
		$files = glob(DIR_APPLICATION . 'controller/credit_card/*.php');
		
		foreach ($files as $file) {
			$code = basename($file, '.php');
			
			if ($this->config->get($code . '_status') && $this->config->get($code)) {
				$this->load->language('credit_card/' . $code);

				$data['credit_cards'][] = array(
					'name' => $this->language->get('heading_title'),
					'href' => $this->url->link('credit_card/' . $code, '', true)
				);
			}
		}
		
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['order'] = $this->url->link('account/order', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		
		if ($this->config->get('reward_status')) {
			$data['reward'] = $this->url->link('account/reward', '', true);
		} else {
			$data['reward'] = '';
		}		
		
		$data['return'] = $this->url->link('account/return', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		$data['recurring'] = $this->url->link('account/recurring', '', true);

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
		
		$data['column_left'] = $this->load->controller('common/column_left');

		if($this->config->get('module_account_dashboard_page_status')){
		
			$this->document->addStyle('catalog/view/javascript/account-page.css');
			$data = array_merge($data,$this->load->language('account/order'));
			$data = array_merge($data,$this->load->language('account/edit'));
			$this->load->model('account/order');
			$this->load->model('account/customer');
			$this->load->model('account/download');
			$this->load->model('account/wishlist');
			$data['download_total'] = $this->model_account_download->getTotalDownloads();
			$results = $this->model_account_wishlist->getWishlist();
			$data['total_wishlist'] = count($results);
			$dat = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer c
				LEFT JOIN " . DB_PREFIX . "address a ON (a.address_id=c.address_id)
				WHERE c.customer_id = '" . (int)$this->customer->getId() . "' AND c.status = '1'");
			$data['customer_info'] = $dat->row;
			$data['order_total'] = $this->model_account_order->getTotalOrders();
			$results = $this->model_account_order->getOrders(0, 5);
			$data['orders_info'] = array();
			$orderprice =$pro = 0;
			foreach ($results as $result) {
				$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
				$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);
				$pro += $product_total + $voucher_total;
				$orderprice += $this->currency->format($result['total'], $result['currency_code'], $result['currency_value'],false); 
				$data['orders_info'][] = array(
					'order_id'   => $result['order_id'],
					'name'       => $result['firstname'] . ' ' . $result['lastname'],
					'status'     => $result['status'],
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'products'   => ($product_total + $voucher_total),
					'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
					'href'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], 'SSL'),
				);
			}
			$data['orderprice'] = $this->currency->format($orderprice, $this->config->get('config_currency'));
			$data['orderproduct'] = $pro;

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('account/account_dashboard_page', $data));
			
			return ;
		}
		
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		$this->response->setOutput($this->load->view('account/account', $data));
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
