<?php
class ControllerExtensionModuleewallet extends Controller {
	private $error = array();
	
	public function index(){
		if(isset($this->request->get['route'], $this->request->get['extension']) && $this->request->get['extension'] == 'e_wallet' && substr($this->request->get['route'], -8) == '/install'){
			$this->install();
			return;
		}
		$thisvar = $this->octlversion();
		if($thisvar >= 3000){
			$payment_key = 'payment_';
			$module_key  = 'module_';
			$total_key   = 'total_';
			$token       = 'user_token=' . $this->session->data['user_token'];
		}else{
			$payment_key = $module_key = $total_key = '';
			$token = 'token=' . $this->session->data['token'];
		}
		
		$data = array_merge($this->load->language('e_wallet/e_wallet'),array());
		$this->load->model('setting/setting');
		$payment = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'payment'");
		$payments = array();
		if($payment->num_rows){
			foreach ($payment->rows as $p){
				if($thisvar > 2200){
					$this->load->language('extension/payment/'.$p['code']);
				}else{
					$this->load->language('payment/'.$p['code']);
				}
				$payments[$p['code']] = $this->language->get('heading_title');
			}
		}
		if($thisvar >= 2200){
			$data['image_flag'] = 'language/';
		}else{
			$data['image_flag'] = 'view/image/flags/';
		}
		$data['thisvar'] = $thisvar;
		$data['payments'] = $payments;
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()){
			$this->model_setting_setting->editSetting($module_key.'e_wallet', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/module/e_wallet', $token , 'SSL'));
		}
		$data['breadcrumbs'] = array(
			array('text' => $data['text_home'],'href' => $this->url->link('common/dashboard', $token, 'SSL')),
			array('text' => $data['text_module'],'href' => $this->url->link('extension/module', $token, 'SSL')),
			array('text' => $data['heading_title'],'href' => $this->url->link('extension/module/e_wallet', $token, 'SSL')),
		);
		$data['cancel'] = $this->url->link('extension/extension', $token, 'SSL');
		$data['action'] = $this->url->link('extension/module/e_wallet', $token, 'SSL');
		$option = array(
			'e_wallet_title',
			'e_wallet_e_account_text',
			'e_wallet_status',
			'e_wallet_delete_voucher_order',
			'e_wallet_language',
			'e_wallet_max_add',
			'e_wallet_min_add',
			'e_wallet_image',
			'e_wallet_icon',
			'e_wallet_payments',
			'e_wallet_add_money',
			'e_wallet_send_money',
			'e_wallet_bank_detail',
			'e_wallet_voucher_status',
			'e_wallet_withdraw_requests',
			'e_wallet_checkout_payments',
			'e_wallet_max_send',
			'e_wallet_min_send',
			'e_wallet_min_withdraw',
			'e_wallet_max_withdraw',
			'e_wallet_refund_order_id',
			'e_wallet_processing_status',
			'e_wallet_complete_status',
			'e_wallet_delete_on_uninstall',
			'e_wallet_setting_mail',
			'e_wallet_feild_data',
		);
		foreach ($option as $key){
			$key_new = $module_key.$key;
			if (isset($this->request->post[$key_new])) $data[$key] = $this->request->post[$key_new];
			else $data[$key] = $this->config->get($key_new);
		}
		$error_key = array(
			"warning",
			"e_account_text",
			"payment_method",
			"title",
			"payment_method_title",
			"half_payment_line",
			"full_payment_line",
			"add_money_text",
			"send_money_text",
			"withdraw_money_text",
			"bank_detail_text",
			"total_title",
			"voucher_text",
			"max_add",
			"min_add",
			"max_send",
			"min_send",
			"setting_mail",
			"max_withdraw",
			"min_withdraw",
			"refund_order_id",
			"processing_status",
			"complete_status",
			"add_money_string_text",
			"send_money_string_text",
			"withdraw_string_text",
			"add_confirm_string_text",
			"order_id_text",
			"refund_string_text",
			"approve_request_text",
			"receive_money_text",
			"add_transaction_email_sub",
			"transaction_mail_msg",
			"receive_money_email_sub",
			"receive_email_msg",
			"send_data_sub",
			"send_money_msg",
			"sufficient_msg",
			"invalid_method",
			"bank_success_msg",
			"add_setting_sub",
			"add_setting_msg",
			"withdraw_setting_sub",
			"withdraw_setting_msg",
			"add_user_sub",
			"add_user_msg",
			"withdraw_user_sub",
			"withdraw_user_msg",
			"notify_email_sub",
			"notify_email_msg",
			"add_money_req_sub",
			"add_money_req_msg",
			"req_aproove_text",
			"req_reject_text",
			"add_voucher_text",
			"email_header_text",
			"email_footer_text",
			"feild_data",
		);
		foreach ($error_key as $key){
			if (isset($this->error[$key])) $data['error_'.$key] = $this->error[$key];
		}
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['confirm_msg']    = $this->language->get('confirm_msg');
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->language->get('success');
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$this->load->model('tool/image');
		if($data['e_wallet_image']) $data['image_thumb'] = $this->model_tool_image->resize($data['e_wallet_image'], 100, 100);
		else $data['image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if($data['e_wallet_icon']) $data['icon_thumb'] = $this->model_tool_image->resize($data['e_wallet_icon'], 100, 100);
		else $data['icon_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$data['module_key'] = $module_key;
		
		$this->document->addStyle("view/javascript/summernote/summernote.css");
		$this->document->addScript("view/javascript/summernote/summernote.js");
		$this->document->addScript("view/javascript/summernote/summernote-image-attributes.js");
		
		$data['voucher']			= $this->url->link('e_wallet/e_wallet/vouchar_list', $token, true);
		$data['transaction']		= $this->url->link('e_wallet/e_wallet', $token, true);
		$data['add_money_request']	= $this->url->link('e_wallet/e_wallet/add_request', $token, true);
		$data['customer_balance']	= $this->url->link('e_wallet/e_wallet/customers', $token, true);
		$data['header']				= $this->load->controller('common/header');
		$data['column_left']		= $this->load->controller('common/column_left');
		$data['footer']				= $this->load->controller('common/footer');
		
		if($thisvar >= 3000){
			$this->response->setOutput($this->load->view('extension/module/e_wallet', $data));
		}else{
			$this->response->setOutput($this->load->view('extension/module/e_wallet.tpl', $data));
		}
		
	}
	protected function validate(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000){
			$payment_key = 'payment_';
			$module_key  = 'module_';
			$total_key   = 'total_';
			$token       = 'user_token=' . $this->session->data['user_token'];
		}else{
			$payment_key = $module_key = $total_key = '';
			$token = 'token=' . $this->session->data['token'];
		}
		
		if (!$this->user->hasPermission('modify', 'extension/module/e_wallet')){
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$checks = array(
			'setting_mail',
			'max_add',
			'min_add',
			'max_send',
			'min_send',
			'max_withdraw',
			'min_withdraw',
		);
		foreach ($checks as $key){
			if ((utf8_strlen($this->request->post["{$module_key}e_wallet_{$key}"]) < 2) || (utf8_strlen($this->request->post["{$module_key}e_wallet_{$key}"]) > 255)){
				$this->error[$key] = $this->language->get("error_text_{$key}");
			}

		}
		$keys_array = array();
		foreach($this->request->post["{$module_key}e_wallet_feild_data"] as $key => $feild){
			if((!preg_match("/^[a-z0-9\_]+$/",$feild['key'])) || in_array($feild['key'],$keys_array)){
				$this->error['feild_data'][$key] = $this->language->get("error_text_feild_data");
		   	}
			$keys_array[] = $feild['key'];
		}

		$checks = array(
			'refund_order_id',
			'processing_status',
			'complete_status',
		);
		foreach($checks as $key){
			if(!isset($this->request->post["{$module_key}e_wallet_{$key}"]) || !$this->request->post["{$module_key}e_wallet_{$key}"]) $this->error[$key] = $this->language->get("error_text_{$key}");
		}
		$checks = array(
			'e_account_text',
			'title',
			'add_money_text',
			'send_money_text',
			'bank_detail_text',
			'withdraw_money_text',
			'voucher_text',
			'payment_method_title',
			'total_title',
			'add_money_string_text',
			'send_money_string_text',
			'withdraw_string_text',
			'add_confirm_string_text',
			'order_id_text',
			'refund_string_text',
			'approve_request_text',
			'receive_money_text',
			'add_transaction_email_sub',
			'transaction_mail_msg',
			'receive_money_email_sub',
			'receive_email_msg',
			'send_data_sub',
			'send_money_msg',
			'sufficient_msg',
			'invalid_method',
			'add_setting_sub',
			'add_setting_msg',
			'withdraw_setting_sub',
			'withdraw_setting_msg',
			'add_user_sub',
			'add_user_msg',
			'withdraw_user_sub',
			'withdraw_user_msg',
			'notify_email_sub',
			'notify_email_msg',
			'add_money_req_sub',
			'add_money_req_msg',
			'req_aproove_text',
			'req_reject_text',
			'add_voucher_text',
			'email_header_text',
			'email_footer_text',
		);
		$checks_html = array(
			'half_payment_line',
			'full_payment_line',
		);
		foreach ($this->request->post[$module_key.'e_wallet_language'] as $language_id => $value){
			foreach ($checks as $key){
				if ((utf8_strlen($value[$key]) < 2) || (utf8_strlen($value[$key]) > 255)){
					$this->error[$key][$language_id] = $this->language->get("error_text_{$key}");
				}
			}
			foreach($checks_html as $key){
				$text = isset($value[$key]) ? $value[$key] : '';
				$text = trim(strip_tags(html_entity_decode($text, ENT_QUOTES, 'UTF-8')));
				if ((utf8_strlen($text) < 3) || (utf8_strlen($text) > 255)){
					$this->error[$key][$language_id] = $this->language->get("error_text_{$key}");
				}
			}
		}
		if ($this->error && !isset($this->error['warning'])){
			$this->error['warning'] = $this->language->get('error_text_warning');
		}
		return !$this->error;
	}
	protected function octlversion(){
    	$varray = explode('.', VERSION);
    	return (int)implode('', $varray);
	}
	public function install(){
		$per = DB_PREFIX;
		$thisvar = $this->octlversion();
		if($thisvar >= 3000){
			$payment_key = 'payment_';
			$module_key  = 'module_';
			$total_key   = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}

		$sqls = array(
			"CREATE TABLE IF NOT EXISTS `{$per}e_wallet_transaction`(
			  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
			  `customer_id` int(11) NOT NULL,
			  `price` double NOT NULL,
			  `description` text NOT NULL,
			  `date_added` datetime NOT NULL,
			  `balance` VARCHAR( 50 ) NOT NULL,
			  PRIMARY KEY (`transaction_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1",

			"CREATE TABLE IF NOT EXISTS `{$per}e_wallet_vouchar_list`(
			  `vouchar_id` int(11) NOT NULL AUTO_INCREMENT,
			  `vouchar_name` varchar(255) NOT NULL,
			  `vouchar_code` varchar(255) NOT NULL,
			  `vouchar_amount` int(11) NOT NULL,
			  `user_limit` int(11) NOT NULL,
			  `used_by` text NOT NULL,
			  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `status` int(11) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`vouchar_id`)
			  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1",

			"CREATE TABLE IF NOT EXISTS `{$per}cod_request`(
			  `request_id` int(11) NOT NULL AUTO_INCREMENT,
			  `customer_id` int(11) NOT NULL,
			  `amount` double NOT NULL,
			  `description` text NOT NULL,
			  `date_added` datetime NOT NULL,
			  `status` SET(  '0',  '1',  '2' ) NOT NULL DEFAULT  '0',
			  `transaction_id` int(11) NOT NULL,
			  PRIMARY KEY (`request_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1",

			"CREATE TABLE IF NOT EXISTS `{$per}withdraw_request`(
			  `request_id` int(11) NOT NULL AUTO_INCREMENT,
			  `customer_id` int(11) NOT NULL,
			  `amount` double NOT NULL,
			  `description` text NOT NULL,
			  `date_added` datetime NOT NULL,
			  `status` SET(  '0',  '1',  '2' ) NOT NULL DEFAULT  '0',
			  `transaction_id` int(11) NOT NULL,
			  PRIMARY KEY (`request_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1",

			"CREATE TABLE IF NOT EXISTS `{$per}e_wallet_bank` (
			  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
			  `customer_id` int(11) NOT NULL,
			  `data` text NOT NULL,
			  `update_date` datetime NOT NULL,
			  PRIMARY KEY (`bank_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1"
		);
		foreach ($sqls as $sql) $this->db->query($sql);
		$checks = array(
			"e_wallet/e_wallet",
			"extension/module/e_wallet",
			"extension/payment/e_wallet_payment",
			"extension/total/e_wallet_total",
		);
		$groupId = $this->user->getGroupId();
		foreach ($checks as $key){
			$this->model_user_user_group->addPermission($groupId, 'access', $key);
			$this->model_user_user_group->addPermission($groupId, 'modify', $key);
		}
	
		if($thisvar >= 3000){
			$this->load->model('setting/extension');
			$this->model_setting_extension->install('payment', "e_wallet_payment");
			$this->model_setting_extension->install('total', "e_wallet_total");
			$this->load->controller('extension/payment/e_wallet_payment/install');
			$this->load->controller('extension/total/e_wallet_total/install');
		}else{
			$this->load->model('extension/extension');
			$this->model_extension_extension->install('payment', 'e_wallet_payment');
			$this->model_extension_extension->install('total', 'e_wallet_total');
			$this->load->controller('extension/payment/e_wallet_payment/install');
			$this->load->controller('extension/total/e_wallet_total/install');
		}
		$this->load->model('setting/setting');
		$module_setting = $this->db->query("SELECT code FROM ".DB_PREFIX."setting WHERE code='{$module_key}e_wallet'");
		if(!$module_setting->num_rows){
			$this->load->model('localisation/language');
			$languages = $this->model_localisation_language->getLanguages();
			$tmp = array();
			foreach ($languages as $l){
				$tmp[$l['language_id']] = array(
					'payment_method_title'		=> 'E Wallet',
					'e_account_text'			=> 'E Wallet',
					'title'						=> 'E Wallet',
					'total_title'				=> 'E Wallet',
					'add_money_text'			=> 'Add Money',
					'send_money_text'			=> 'Send Money',
					'withdraw_money_text'		=> 'Withdraw Money',
					'voucher_text'				=> 'Redeem Voucher',
					'bank_detail_text'			=> 'Bank Detail',
					'half_payment_line'			=> '<span>Payment to be made <b>{OT}</b> - wallet: <b>{WT}</b> Select an Option to pay balance <b>{RT}</b>. </span>',
					'full_payment_line'			=> '<span>Awesome! You have Sufficient balance in your Wallet. </span>',
					'email_header_text'			=> 'Thanks for the transaction in the wallet, the transaction information is as follows.',
					'email_footer_text'			=> 'If you have any questions regarding this transaction, please reply to this email. We are here to help you.',
					'add_confirm_string_text'	=> '{AT} in {WT}.',
					'sufficient_msg'			=> 'You have Sufficient Balance in Your {WT}',
					'invalid_method'			=> 'Invalid Payment GetWay Method...!',
					'add_money_string_text'		=> ' {AT} In {WT}.',
					'send_money_string_text'	=> 'You {ST} to {NT}, Email: {ET}.',
					'withdraw_string_text'		=> 'You {WT} from {ET}, Amount {AT}.',
					'order_id_text'				=> 'Paid for Order, Order Id is: # {OI}.',
					'refund_string_text'		=> 'Refund Amount for Order, Order Id is: # {OI}.',
					'approve_request_text'		=> '{AT} Amount: {BT} By Payment Method {PM}.',
					'receive_money_text'		=> 'You Receive Money From {FT} {LT}, Email: {ET}.',
					'req_aproove_text'			=> 'Request Approved: {DC}. ',
					'req_reject_text'			=> 'Request Rejected: {DC}.',
					'add_voucher_text'			=> 'Added By {VT}.',
					'add_transaction_email_sub'	=> '{WT} Money Transaction.',
					'transaction_mail_msg'		=> "Description: {DC},\namount: {AT},\nDate: {DT}.",
					'receive_money_email_sub'	=> 'Receive Money in {ET} Transaction.',
					'receive_email_msg'			=> "You Receive Money From {FT}, \nAmount: {AT}, \nEmail: {ET}, \nDate: {DT}.",
					'add_user_sub'				=> '{EN} {AT} request {ST}.',
					'add_user_msg'				=> "Your Request {ST} from admin,\ndescription: {DC},\namount: {AD},\nDate: {DT}.",
					'withdraw_user_sub'			=> '{EN} {WT} request {ST}.',
					'withdraw_user_msg'			=> "Your Request {ST} from admin,\ndescription: {DC},\nAmount : {AD},\nDate: {DT}.", 
					'send_data_sub'				=> 'You {ST} in {ET} Transaction.',
					'send_money_msg'			=> "You {ST} To {NT},\nAmount: {AT},\nEmail: {ET},\nDate: {DT}.",
					'notify_email_sub'			=> 'Admin added transaction in {EN}.',
					'notify_email_msg'			=> "Your Transaction {ET} From Admin,\nDescription : {DC},\nAmount : {AT},\nDate : {DT}.",
					'add_money_req_sub'			=> 'Your added money request sent to admin.',
					'add_money_req_msg'			=> "Your added money request has been sent to admin,\nAmount: {AT},\nDescription : {DC},\nDate: {DT}.",
					'add_setting_sub'			=> '{EN} {AT} request {ST}.',
					'add_setting_msg'			=> "You changed {AT} Request to {ST},\nDescription : {DC},\nAmount : {AT},\nDate : {DT}.",
					'withdraw_setting_sub'		=> '{EN} {WT} request {ST}.',
					'withdraw_setting_msg'		=> "You changed {WT} request to {ST},\ndescription : {DC},\nAmount : {AD},\nDate: {DT}.",
				);
			}
			$filds_name = array_column($languages, 'name', 'language_id');
			$complete_data = $this->config->get('config_complete_status');
			$feild_data = array(
				array(
					'name'       => array_map(function($l){return 'Account Name';}, $filds_name),
					'key'        => 'account_name',
					'type'       => 'text',
					'sort_order' => 1,
					'status'     => 1
				),array(
					'name'       => array_map(function($l){return 'Account Number';}, $filds_name),
					'key'        => 'account_number',
					'type'       => 'text',
					'sort_order' => 2,
					'status'     => 1
				),array(
					'name'       => array_map(function($l){return 'Bank Name';}, $filds_name),
					'key'        => 'bank_name',
					'type'       => 'text',
					'sort_order' => 3,
				),array(
					'name'       => array_map(function($l){return 'Branch Number';}, $filds_name),
					'key'        => 'branch_name',
					'type'       => 'text',
					'sort_order' => 4,
				),array(
					'name'       => array_map(function($l){return 'IFSC Code';}, $filds_name),
					'key'        => 'ifsc_code',
					'type'       => 'text',
					'sort_order' => 5,
				),array(
					'name'       => array_map(function($l){return 'Swift Code';}, $filds_name),
					'key'        => 'swift_code',
					'type'       => 'text',
					'sort_order' => 6,
				),array(
					'name'       => array_map(function($l){return 'Note';}, $filds_name),
					'key'        => 'note',
					'type'       => 'textarea',
					'sort_order' => 7,
				)
			);
			$array = array(
				"{$module_key}e_wallet_complete_status"		=> array(),
				"{$module_key}e_wallet_processing_status"	=> array(),
				"{$module_key}e_wallet_refund_order_id"		=> array(),
				"{$module_key}e_wallet_language"			=> $tmp,
				"{$module_key}e_wallet_feild_data"			=> $feild_data,
				"{$module_key}e_wallet_complete_status"		=> $complete_data,
				"{$module_key}e_wallet_setting_mail"		=> $this->config->get('config_email'),
			);
			$this->model_setting_setting->editSetting("{$module_key}e_wallet", $array);
		}
		$check_payment=$this->db->query("SELECT code FROM ".DB_PREFIX."setting WHERE code='{$payment_key}e_wallet_payment'");
		if(!$check_payment->num_rows){
			$array = array(
				"{$payment_key}e_wallet_payment_status"          => 1,
				"{$payment_key}e_wallet_payment_geo_zone_id"     => 0,
				"{$payment_key}e_wallet_payment_order_status_id" => $this->config->get('config_order_status_id'),
				"{$payment_key}e_wallet_payment_total"           => 1,
				"{$payment_key}e_wallet_payment_sort_order"      => 0,
			);
			$this->model_setting_setting->editSetting("{$payment_key}e_wallet_payment", $array);
		}
		$check_total = $this->db->query("SELECT code FROM ".DB_PREFIX."setting WHERE code='{$total_key}e_wallet_total'");
		if(!$check_total->num_rows){
			$array = array(
				"{$total_key}e_wallet_total_status"     => 1,
				"{$total_key}e_wallet_total_sort_order" => ((int)$this->config->get("{$total_key}total_sort_order")-1),
			);
			$this->model_setting_setting->editSetting("{$total_key}e_wallet_total", $array);
		}

	}
	public function uninstall(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000){
			$payment_key = 'payment_';
			$module_key  = 'module_';
			$total_key   = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}

		if($this->config->get("{$module_key}e_wallet_delete_on_uninstall")){
			$per = DB_PREFIX;
			$this->db->query("DROP TABLE `{$per}e_wallet_transaction`");
			$this->db->query("DROP TABLE `{$per}cod_request`");
			$this->db->query("DROP TABLE `{$per}e_wallet_bank`");
			$this->db->query("DROP TABLE `{$per}withdraw_request`");
			$this->db->query("DROP TABLE `{$per}e_wallet_vouchar_list`");
		}
		$this->load->model('user/user_group');
		$checks = array(
			"e_wallet/e_wallet",
			"extension/module/e_wallet",
			"extension/payment/e_wallet_payment",
			"extension/total/e_wallet_total",
		);
		$groupId = $this->user->getGroupId();
		foreach ($checks as $key){
			$this->model_user_user_group->removePermission($groupId, 'access', $key);
			$this->model_user_user_group->removePermission($groupId, 'modify', $key);
		}

		if($thisvar >= 3000){
			$this->load->model('setting/extension');
			$this->model_setting_extension->uninstall('payment', $payment_key."e_wallet_payment");
			$this->model_setting_extension->uninstall('total', $total_key."e_wallet_total");
		    $this->load->controller('extension/payment/e_wallet_payment/uninstall');
		    $this->load->controller('extension/total/e_wallet_total/uninstall');
		}else{
			$this->load->model('extension/extension');
			$this->model_extension_extension->uninstall('payment', "e_wallet_payment");
			$this->model_extension_extension->uninstall('total', "e_wallet_total");
		    $this->load->controller('extension/payment/e_wallet_payment/uninstall');
		    $this->load->controller('extension/total/e_wallet_total/uninstall');
		}
	}
}
class Controllermoduleewallet extends ControllerExtensionModuleewallet {
	function __construct($registry){
		parent::__construct($registry);
	}
}