<?php
class Controllermodulequickproduct extends Controller {
    private $error = array();
    public function index() {
        $this->load->language('module/quick_product');
        $data['heading_title'] = $this->language->get('heading_title');

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('quick_product', $this->request->post);
            
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

        $data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/quick_product', 'token=' . $this->session->data['token'], true)
		);

        $data['action'] = $this->url->link('module/quick_product', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], true);

        if (isset($this->request->post['quick_product_status'])) {
			$data['quick_product_status'] = $this->request->post['quick_product_status'];
		} else {
			$data['quick_product_status'] = $this->config->get('quick_product_status');
		}

        $data['header']				= $this->load->controller('common/header');
		$data['column_left']		= $this->load->controller('common/column_left');
		$data['footer']				= $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/quick_product.tpl', $data));
    }
    public function install(){
		$groupId = $this->user->getGroupId();
		foreach ($checks as $key){
			$this->model_user_user_group->addPermission($groupId, 'access', $key);
			$this->model_user_user_group->addPermission($groupId, 'modify', $key);
		}
	}
	public function uninstall(){
		// $thisvar = $this->octlversion();
		// if($thisvar >= 3000){
		// 	$payment_key = 'payment_';
		// 	$module_key  = 'module_';
		// 	$total_key   = 'total_';
		// }else{
		// 	$payment_key = $module_key = $total_key = '';
		// }

		// if($this->config->get("{$module_key}e_wallet_delete_on_uninstall")){
		// 	$per = DB_PREFIX;
		// 	$this->db->query("DROP TABLE `{$per}e_wallet_transaction`");
		// 	$this->db->query("DROP TABLE `{$per}cod_request`");
		// 	$this->db->query("DROP TABLE `{$per}e_wallet_bank`");
		// 	$this->db->query("DROP TABLE `{$per}withdraw_request`");
		// 	$this->db->query("DROP TABLE `{$per}e_wallet_vouchar_list`");
		// }
		// $this->load->model('user/user_group');
		// $checks = array(
		// 	"e_wallet/e_wallet",
		// 	"extension/module/e_wallet",
		// 	"extension/payment/e_wallet_payment",
		// 	"extension/total/e_wallet_total",
		// );
		$groupId = $this->user->getGroupId();
		foreach ($checks as $key){
			$this->model_user_user_group->removePermission($groupId, 'access', $key);
			$this->model_user_user_group->removePermission($groupId, 'modify', $key);
		}

		// if($thisvar >= 3000){
		// 	$this->load->model('setting/extension');
		// 	$this->model_setting_extension->uninstall('payment', $payment_key."e_wallet_payment");
		// 	$this->model_setting_extension->uninstall('total', $total_key."e_wallet_total");
		//     $this->load->controller('extension/payment/e_wallet_payment/uninstall');
		//     $this->load->controller('extension/total/e_wallet_total/uninstall');
		// }else{
		// 	$this->load->model('extension/extension');
		// 	$this->model_extension_extension->uninstall('payment', "e_wallet_payment");
		// 	$this->model_extension_extension->uninstall('total', "e_wallet_total");
		//     $this->load->controller('extension/payment/e_wallet_payment/uninstall');
		//     $this->load->controller('extension/total/e_wallet_total/uninstall');
		// }
	}
    public function validate(){
        if (!$this->user->hasPermission('modify', 'module/quick_product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
    } 
}