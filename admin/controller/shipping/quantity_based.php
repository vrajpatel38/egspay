<?php
class ControllerShippingQuantitybased extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/quantity_based');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_setting_setting->editSetting('quantity_based', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');

		$data['text_extension_setting'] = $this->language->get('text_extension_setting');
		$data['text_charges'] = $this->language->get('text_charges');
		$data['text_testing_mode'] = $this->language->get('text_testing_mode');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/quantity_based', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('shipping/quantity_based', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], true);
		
		if (isset($this->request->post['quantity_based_check_for_updates'])) {
			$data['quantity_based_check_for_updates'] = $this->request->post['quantity_based_check_for_updates'];
		} else {
			$data['quantity_based_check_for_updates'] = $this->config->get('quantity_based_check_for_updates');
		}

		if(isset($this->request->post['quantity_based_heading'])){
			$data['quantity_based_heading'] = $this->request->post['quantity_based_heading'];
		} else {
			$data['quantity_based_heading'] = $this->config->get('quantity_based_heading');
		}


		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['quantity_based_tax_class_id'])) {
			$data['tax_class_id'] = $this->request->post['quantity_based_tax_class_id'];
		} elseif ($this->config->get('quantity_based_tax_class_id')) {
			$data['tax_class_id'] = $this->config->get('quantity_based_tax_class_id');
		} else {
			$data['tax_class_id'] = 0;
		}

		if (isset($this->request->post['quantity_based_status'])) {
			$data['quantity_based_status'] = $this->request->post['quantity_based_status'];
		} else {
			$data['quantity_based_status'] = $this->config->get('quantity_based_status');
		}
		
		if (isset($this->request->post['quantity_based_autosave'])) {
			$data['quantity_based_autosave'] = $this->request->post['quantity_based_autosave'];
		} else {
			$data['quantity_based_autosave'] = $this->config->get('quantity_based_autosave');
		}

		if (isset($this->request->post['quantity_based_display'])) {
			$data['quantity_based_display'] = $this->request->post['quantity_based_display'];
		} else {
			$data['quantity_based_display'] = $this->config->get('quantity_based_display');
		}
		
		if (isset($this->request->post['quantity_based_tooltips'])) {
			$data['quantity_based_tooltips'] = $this->request->post['quantity_based_tooltips'];
		} else {
			$data['quantity_based_tooltips'] = $this->config->get('quantity_based_tooltips');
		}

		if (isset($this->request->post['free_sort_order'])) {
			$data['quantity_based_sort_order'] = $this->request->post['quantity_based_sort_order'];
		} else {
			$data['quantity_based_sort_order'] = $this->config->get('quantity_based_sort_order');
		}

		if (isset($this->request->post['free_sort_order'])) {
			$data['quantity_based_sort_order'] = $this->request->post['quantity_based_sort_order'];
		} else {
			$data['quantity_based_sort_order'] = $this->config->get('quantity_based_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$json = '{ "min":20, "max":5, "price":10}';
		$data['quickshipping_datas'] = array();
		$data['quickshipping_datas'][] = [
			'sort_order' => '12',
			'admin_name' => 'admin1',
			'title' => 'title1',
			'charge' => json_decode($json),
		];		
		
		$this->response->setOutput($this->load->view('shipping/quantity_based', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/quantity_based')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}