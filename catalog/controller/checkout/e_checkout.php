<?php
class ControllerCheckoutecheckout extends Controller {
	public function index() {
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}
		if(!isset($this->session->data['vouchers_key']) OR $this->session->data['vouchers_key']!='e_wallet_vouchers'){
			$this->response->redirect($this->url->link('checkout/checkout', '', true));
		}
		 $thisvar = $this->octlversion();
		 if(isset($this->session->data['use_e_wallet'])){
		 	unset($this->session->data['use_e_wallet']);
		 }
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$this->response->redirect($this->url->link('checkout/cart'));
		}
		list($data['add_money_text']) = $this->lg('add_money_text');
		
		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$this->response->redirect($this->url->link('checkout/cart'));
			}
		}
		$this->load->model('account/address');
		$this->session->data['payment_address']=$this->model_account_address->getAddress($this->customer->getAddressId());

		$this->load->language('checkout/checkout');

		$this->document->setTitle($this->language->get('text_e_wallet_payment'));
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		// Required by klarna
		if ($this->config->get('klarna_account') || $this->config->get('klarna_invoice')) {
			$this->document->addScript('http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js');
		}
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_e_account'),
			'href' => $this->url->link('account/account')
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['add_money_text'],
			'href' => $this->url->link('checkout/e_checkout', '', true)
		);
		
		$data['heading_title'] = $data['add_money_text'];	
		$data['text_checkout_payment_method'] = $this->language->get('text_payment');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

		$data['logged'] = $this->customer->isLogged();

		if (isset($this->session->data['account'])) {
			$data['account'] = $this->session->data['account'];
		} else {
			$data['account'] = '';
		}
		$other_theme = (int)defined("JOURNAL_VERSION");
		if($other_theme == 1){
			$data['journal_style'] = $this->document->addStyle('catalog/view/javascript/bootstrap/css/bootstrap.minupdate.css');
		}

		$data['shipping_required'] = $this->cart->hasShipping();
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if($thisvar < 2200){
			$file = '/template/e_wallet/e_checkout.tpl';
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template').$file)) {
				$this->response->setOutput($this->load->view($this->config->get('config_template').$file, $data));
			} else {
				$this->response->setOutput($this->load->view('default'.$file, $data));
			}
		}else{
			$this->response->setOutput($this->load->view('e_wallet/e_checkout', $data));
		}

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
	protected function octlversion(){
    	$varray = explode('.', VERSION);
    	return (int)implode('', $varray);
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

	public function customfield() {
		$json = array();

		$this->load->model('account/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => $custom_field['required']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function confirm() {

	}
}
