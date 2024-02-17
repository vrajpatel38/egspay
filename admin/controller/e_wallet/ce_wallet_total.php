<?php
class Controllerextensiontotalewallettotal extends Controller {
	private $error = array();
	public function index() {
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
			$data['module_key'] = $module_key;
			$token = 'user_token=' . $this->session->data['user_token'];
			$this->load->language('extension/total/e_wallet_total');
		}else{
			$payment_key = $module_key = $total_key = '';
			$token = 'token=' . $this->session->data['token'];
			$this->load->language('extension/total/e_wallet_total');
		}
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
				$this->model_setting_setting->editSetting($total_key.'e_wallet_total', $this->request->post);
				$this->session->data['success'] = $this->language->get('text_success');
				$this->response->redirect($this->url->link('extension/total/e_wallet_total',$token, 'SSL'));
		}
		$data['heading_title']    = $this->language->get('heading_title');
		$data['text_edit']        = $this->language->get('text_edit');
		$data['text_enabled']     = $this->language->get('text_enabled');
		$data['text_disabled']    = $this->language->get('text_disabled');
		$data['entry_status']     = $this->language->get('entry_status');
		$data['entry_title']      = $this->language->get('entry_title');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['button_save']      = $this->language->get('button_save');
		$data['button_cancel']    = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if($thisvar >= 3000) {
			$total_key = 'total_';
			$token = 'user_token=' . $this->session->data['user_token'];
		}else{
			$total_key = '';
			$token = 'token=' . $this->session->data['token'];
		}

			$data['breadcrumbs'] = array();
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', $token, 'SSL')
			);
			
			if($thisvar >= 3000) {
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_total'),
					'href' => $this->url->link('marketplace/extension', $token, 'SSL')
				);
			}else{
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_total'),
					'href' => $this->url->link('extension/extension', $token, 'SSL')
				);
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/total/e_wallet_total', $token, 'SSL')
			);

			$data['action'] = $this->url->link('extension/total/e_wallet_total', $token, 'SSL');
			$data['cancel'] = $this->url->link('extension/extension', $token, 'SSL');

			if (isset($this->request->post[$total_key.'e_wallet_total_status'])) {
				$data[$total_key.'e_wallet_total_status'] = $this->request->post[$total_key.'e_wallet_total_status'];
			} else {
				$data[$total_key.'e_wallet_total_status'] = $this->config->get($total_key.'e_wallet_total_status');
			}

			if (isset($this->request->post[$total_key.'e_wallet_total_sort_order'])){
				$data[$total_key.'e_wallet_total_sort_order'] = $this->request->post[$total_key.'e_wallet_total_sort_order'];
			} else {
				$data[$total_key.'e_wallet_total_sort_order'] = $this->config->get($total_key.'e_wallet_total_sort_order');
			}
		
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			if($thisvar >= 3000) {
				$this->response->setOutput($this->load->view('extension/total/e_wallet_total', $data));
			}else{
				$this->response->setOutput($this->load->view('extension/total/e_wallet_total.tpl', $data));
			}
	}
	protected function octlversion(){
    	$varray = explode('.', VERSION);
    	return (int)implode('', $varray);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/total/e_wallet_total')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
}

class Controllertotalewallettotal extends Controllerextensiontotalewallettotal {
		
		function __construct($registry)
		{
			parent::__construct($registry);
		}
	
}