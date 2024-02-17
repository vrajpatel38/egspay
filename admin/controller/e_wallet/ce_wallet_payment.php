<?php
class Controllerextensionpaymentewalletpayment extends Controller {
	private $error = array();

	public function index() {
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
			$data['module_key'] = $module_key;
			$token = 'user_token=' . $this->session->data['user_token'];
		}else{
			$payment_key = $module_key = $total_key = '';
			$token = 'token=' . $this->session->data['token'];
		}
		$this->load->language('extension/payment/e_wallet_payment');
		$this->load->language('payment/e_wallet_payment');
		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($payment_key.'e_wallet_payment', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/payment/e_wallet_payment',$token, 'SSL'));
		}
		$data['error_warning'] = '';
		if (isset($this->error['warning'])) $data['error_warning'] = $this->error['warning'];
		if($thisvar >= 3000) {
			$data['breadcrumbs'] = array(
				array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/dashboard', $token, 'SSL')
				),array(
					'text' => $this->language->get('text_payment'),
					'href' => $this->url->link('marketplace/extension', $token, 'SSL')
				),array(
					'text' => $this->language->get('heading_title'),
					'href' => $this->url->link('extension/payment/e_wallet_payment', $token, 'SSL')
				),
			);
		}else{
				$data['breadcrumbs'] = array(
				array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/dashboard', $token, 'SSL')
				),array(
					'text' => $this->language->get('text_payment'),
					'href' => $this->url->link('extension/extension', $token, 'SSL')
				),array(
					'text' => $this->language->get('heading_title'),
					'href' => $this->url->link('extension/payment/e_wallet_payment', $token, 'SSL')
				),
			);
		}
		
		$language = array('heading_title','text_edit','text_enabled','text_disabled','text_all_zones','entry_order_status','entry_total','entry_geo_zone','entry_status','entry_sort_order','help_total','button_save','button_cancel');
		foreach ($language as $key) $data[$key] = $this->language->get($key);
		$this->document->setTitle($this->language->get('heading_title'));
		$data['action'] = $this->url->link('extension/payment/e_wallet_payment', $token, 'SSL');
		$data['cancel'] = $this->url->link('extension/extension', $token, 'SSL');
		$this->load->model('localisation/order_status');
		$this->load->model('localisation/geo_zone');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		$option = array(
			$payment_key.'e_wallet_payment_total',
			$payment_key.'e_wallet_payment_order_status_id',
			$payment_key.'e_wallet_payment_geo_zone_id',
			$payment_key.'e_wallet_payment_status',
			$payment_key.'e_wallet_payment_sort_order',
		);
		
		foreach ($option as $key) {
			if (isset($this->request->post[$key])) $data[$key] = $this->request->post[$key];
			else $data[$key] = $this->config->get($key);
		}
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		if($thisvar >= 3000) {
			$this->response->setOutput($this->load->view('extension/payment/e_wallet_payment', $data));
		}else{
			$this->response->setOutput($this->load->view('extension/payment/e_wallet_payment.tpl', $data));
		}
	}
	protected function octlversion(){
    	$varray = explode('.', VERSION);
    	return (int)implode('', $varray);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/e_wallet_payment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}

class Controllerpaymentewalletpayment extends Controllerextensionpaymentewalletpayment {
	function __construct($registry)
		{
			parent::__construct($registry);
		}
}