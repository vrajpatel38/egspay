<?php
class ControllerModuleQuntityBased extends Controller {
    public function index(){
        $this->load->language('module/quntity_based');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('quantity_based', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		// $data['text_enabled'] = $this->language->get('text_enabled');
		// $data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');
		// $data['entry_public_key'] = $this->language->get('entry_public_key');
		// $data['entry_secret_key'] = $this->language->get('entry_secret_key');

		// $data['button_save'] = $this->language->get('button_save');
		// $data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		// $data['breadcrumbs'][] = array(
		// 	'text' => $this->language->get('text_home'),
		// 	'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		// );

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/terminal_shipping', 'token=' . $this->session->data['token'], true)
		);

		$data['token'] = 'token=' . $this->session->data['token'];

		// $data['action'] = $this->url->link('module/terminal_shipping', 'token=' . $this->session->data['token'], true);

		// $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], true);

		// if (isset($this->request->post['account_status'])) {
		// 	$data['account_status'] = $this->request->post['account_status'];
		// } else {
		// 	$data['account_status'] = $this->config->get('account_status');
		// }
        
		$this->response->setOutput($this->load->view('module/quntity_based', $data));

    }

    public function install(){
		$groupId = $this->user->getGroupId();
		foreach ($checks as $key){
			$this->model_user_user_group->addPermission($groupId, 'access', $key);
			$this->model_user_user_group->addPermission($groupId, 'modify', $key);
		}
	}
}