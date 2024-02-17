<?php
class ControllerExtensionModulequotemodule extends Controller {
	private $error = array();
	
	public function index() {
		$thisvar = $this->octlversion();
		if($thisvar >= 3000){
			$module_key  = 'module_';
			$token       = 'user_token=' . $this->session->data['user_token'];
		}else{
			$module_key = '';
			$token = 'token=' . $this->session->data['token'];
		}
		// $this->load->language('extension/module/quote_module');
		$data = array_merge($this->load->language('extension/module/quote_module'),array());
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting($module_key.'quote_module', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module/quote_module', $token, true));
		}
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard',$token, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', $token, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/quote_module',$token, true)
		);

		$data['action'] = $this->url->link('extension/module/quote_module',$token, true);
		$data['cancel'] = $this->url->link('marketplace/extension', $token, true);

		if($thisvar >= 3000){
			if (isset($this->request->post['module_quote_module_status'])) {
				$data['module_quote_module_status'] = $this->request->post['module_quote_module_status'];
			} else {
				$data['module_quote_module_status'] = $this->config->get('module_quote_module_status');
			}
		}else{
			if (isset($this->request->post['quote_module_status'])) {
				$data['quote_module_status'] = $this->request->post['quote_module_status'];
			} else {
				$data['quote_module_status'] = $this->config->get('quote_module_status');
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		if($thisvar >= 3000){
			$this->response->setOutput($this->load->view('extension/module/quote_module', $data));
		}else{
			$this->response->setOutput($this->load->view('extension/module/quote_module.tpl', $data));
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/quote_module')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	protected function octlversion(){
    	$varray = explode('.', VERSION);
    	return (int)implode('', $varray);
	}
	public function install() {
		$per = DB_PREFIX;
		$sqls = array(
			"ALTER TABLE `". DB_PREFIX ."product`
			 	ADD quote_status varchar(255) NOT NULL",

			"CREATE TABLE IF NOT EXISTS `{$per}p_quote_option`(
				`quote_option_id` int(11) NOT NULL AUTO_INCREMENT,
				`product_id` int(11) NOT NULL,
				`quote_name` varchar(255) NOT NULL,
				`type` varchar(255) NOT NULL,
				`value` text NOT NULL,
				PRIMARY KEY (`quote_option_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1",

			"CREATE TABLE IF NOT EXISTS `{$per}p_quote_option_save`(
				`p_quote_option_save_id` int(11) NOT NULL AUTO_INCREMENT,
				`p_q_o_value_id` int(11) NOT NULL,
				`product_id` int(11) NOT NULL,
				`product_name` varchar(255) NOT NULL,
				`image` text NOT NULL,
				`model` varchar(255) NOT NULL,
				`price` varchar(255) NOT NULL,
				`quantity` varchar(255) NOT NULL, 
				`quote_options` varchar(1024) NOT NULL,
			     PRIMARY KEY (`p_quote_option_save_id`)
			    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1",

			"CREATE TABLE IF NOT EXISTS `{$per}p_quote_option_save_value`(
				`p_q_o_value_id` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(255) NOT NULL,
				`city` text NOT NULL,
				`email` varchar(255) NOT NULL,
				`phone` varchar(255) NOT NULL,
				`file` text NOT NULL,
				`comment` text NOT NULL,
				`ip` varchar(255) NOT NULL,
  				`date_added` datetime NOT NULL,
				PRIMARY KEY (`p_q_o_value_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1",
			
		);
		       
		foreach ($sqls as $sql) $this->db->query($sql);
		$checks = array(
			"extension/module/quote_module",
			"quote/quote_module",
		);
		$groupId = $this->user->getGroupId();
		foreach ($checks as $key){
			$this->model_user_user_group->addPermission($groupId, 'access', $key);
			$this->model_user_user_group->addPermission($groupId, 'modify', $key);
		}
	}

	public function uninstall() {
		$this->db->query("ALTER TABLE `". DB_PREFIX ."product` DROP `quote_status`");
		$this->db->query("DROP TABLE `". DB_PREFIX ."p_quote_option`");
		$this->db->query("DROP TABLE `". DB_PREFIX ."p_quote_option_save`");
		$this->db->query("DROP TABLE `". DB_PREFIX ."p_quote_option_save_value`");
		$this->load->model('user/user_group');
		$checks = array(
			"quote/quote_module",
			"extension/module/quote_module",
		);
		$groupId = $this->user->getGroupId();
		foreach ($checks as $key){
			$this->model_user_user_group->removePermission($groupId, 'access', $key);
			$this->model_user_user_group->removePermission($groupId, 'modify', $key);
		}
	}
}
class Controllermodulequotemodule extends ControllerExtensionModulequotemodule {
	function __construct($registry){
		parent::__construct($registry);
	}
}