<?php
class ControllerShippingTerminalAfrica extends Controller {

	public function index() {
		$this->load->language('shipping/terminal_africa');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('terminal_africa', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');


		if (isset($this->error['warning']))  {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->error['pkey'])) {
			$data['error_public_key'] = $this->error['pkey'];
		} else {
			$data['error_public_key'] = '';
		}
		if (isset($this->error['skey'])) {
			$data['error_secret_key'] = $this->error['skey'];
		} else {
			$data['error_secret_key'] = '';
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
			'href' => $this->url->link('shipping/terminal_africa', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('shipping/terminal_africa', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], true);

		if (isset($this->request->post['terminal_africa_status'])) {
			$data['terminal_africa_status'] = $this->request->post['terminal_africa_status'];
		} else {
			$data['terminal_africa_status'] = $this->config->get('terminal_africa_status');
		}

		if (isset($this->request->post['terminal_africa_geo_zone_id'])) {
			$data['terminal_africa_geo_zone_id'] = $this->request->post['terminal_africa_geo_zone_id'];
		} else {
			$data['terminal_africa_geo_zone_id'] = $this->config->get('terminal_africa_geo_zone_id');
		}

		if (isset($this->request->post['terminal_africa_sort_order'])) {
			$data['terminal_africa_sort_order'] = $this->request->post['terminal_africa_sort_order'];
		} else {
			$data['terminal_africa_sort_order'] = $this->config->get('terminal_africa_sort_order');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/terminal_africa', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/terminal_africa')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}	

		return !$this->error;		
	}
}