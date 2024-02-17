<?php

class ControllerAccountReseller extends Controller {

	private $error = [];
	private $is_reseller = 0;	

	public function index() {

		if (!$this->config->get('hpwd_reseller_management_status')) {
			$this->response->redirect($this->url->link('common/home', '', true));
		}

		$this->load->language('account/address');
		$this->load->language('account/reseller');
		$this->load->model('account/reseller');
		$this->load->model('account/customer');

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_instruction'] = $this->language->get('text_instruction');

		$data['upload_transfer_receipt'] = (int)$this->config->get('module_confirm_transfer_receipt');

		$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
		$this->document->addScript('admin/view/javascript/selectpicker/js/bootstrap-select.min.js');
		$this->document->addStyle('admin/view/javascript/selectpicker/css/bootstrap-select.min.css');
		$this->document->addStyle('catalog/view/javascript/hprm.css');

		$this->document->setTitle($data['heading_title']);

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false,
		];

		$data['breadcrumbs'][] = [
			'text' => $data['heading_title'],
			'href' => $this->url->link('account/reseller', '', 'SSL'),
			'separator' => $this->language->get('text_separator'),
		];

		$data['logged'] = $this->customer->isLogged() ? true : false;
		$data['text_form_confirm'] = $this->language->get('text_form_confirm');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['module_confirm_status'] = $this->config->get('module_confirm_transfer_receipt') ? true : false;
		$data['hpwd_reseller_management_group'] = $this->config->get('hpwd_reseller_management_group');
		$data['hpwd_reseller_management_toggle_socmed'] = $this->config->get('hpwd_reseller_management_toggle_socmed');
		$data['hpwd_reseller_management_whatsapp'] = $this->config->get('hpwd_reseller_management_whatsapp');
		$data['hpwd_reseller_management_messenger'] = $this->config->get('hpwd_reseller_management_messenger');
		$data['hpwd_reseller_management_telegram'] = $this->config->get('hpwd_reseller_management_telegram');

		if ($this->config->get('hpwd_reseller_management_whatsapp_country')) {
			$data['hpwd_reseller_management_whatsapp_country'] = $this->config->get('hpwd_reseller_management_whatsapp_country');
		} else {
			$data['hpwd_reseller_management_whatsapp_country'] = '';
		}

		$data['text_whatsapp'] = $this->config->get('hpwd_reseller_management_text_whatsapp_' . $this->config->get('config_language_id'));

		$data['text_default_heading_title'] = (!empty($this->config->get('hpwd_reseller_management_heading_title_' . $this->config->get('config_language_id')))) ? $this->config->get('hpwd_reseller_management_heading_title_' . $this->config->get('config_language_id')) : $this->language->get('text_default_heading_title');
		$data['text_default_instruction'] = (!empty($this->config->get('hpwd_reseller_management_text_instruction_' . $this->config->get('config_language_id')))) ? $this->config->get('hpwd_reseller_management_text_instruction_' . $this->config->get('config_language_id')) : $this->language->get('text_default_instruction');
		$data['text_default_button_register'] = (!empty($this->config->get('hpwd_reseller_management_btn_register_' . $this->config->get('config_language_id')))) ? $this->config->get('hpwd_reseller_management_btn_register_' . $this->config->get('config_language_id')) : $this->language->get('text_default_button_register');
		$data['text_default_text_attachment'] = (!empty($this->config->get('hpwd_reseller_management_text_attachment_' . $this->config->get('config_language_id')))) ? $this->config->get('hpwd_reseller_management_text_attachment_' . $this->config->get('config_language_id')) : $this->language->get('text_default_text_attachment');

		$data['countries'] = [];
		$countries_code = [];
		$this->load->model('localisation/country');
		// if exist HPSB
		if($this->config->get('module_bundle_city_id')) {
			
			$data['countries'] = $this->model_localisation_country->getCountries();
		} else {
			foreach ($this->config->get('hpwd_reseller_management_country') as $country) {

				$data_country = explode("--", $country);
				$countries_code[] = $data_country[1];
			}
			$countries = $this->model_localisation_country->getCountries();
			foreach ($countries as &$country) {
				if (in_array($country['iso_code_2'],$countries_code)) {
					$data['countries'][] = $country;
				}
			}
		}

		$availabel_countries = $this->config->get('hpwd_reseller_management_country');

		$data['availabel_countries'] = [];

		$data['availabel_countries_code'] = [];

		$this->load->model('account/reseller');

		$countries = $this->model_account_reseller->getCountries();

		foreach ($availabel_countries as $availabel_country) {
			$availabel_country = explode("--", $availabel_country);
			$data['availabel_countries'][] = $availabel_country[1];
		}

		// foreach ($countries as $country) {
		// 	if (in_array($country['iso_code_2'], $data['availabel_countries'])) {
		// 		$data['countries'][] = $country;
		// 	}
		// }

		$data['availabel_countries'] = json_encode($data['availabel_countries']);


		$data['default_country'] = $this->config->get('hpwd_reseller_management_default_country');
		$data['entry_jml_bayar'] = $this->language->get('entry_jml_bayar');
		$data['entry_upload_bukti_transfer'] = $this->language->get('entry_upload_bukti_transfer');
		$data['entry_no_order'] = $this->language->get('entry_no_order');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_bank_transfer'] = $this->language->get('entry_bank_transfer');
		$data['help_bank_transfer'] = $this->language->get('help_bank_transfer');
		$data['entry_metode_pembayaran'] = $this->language->get('entry_metode_pembayaran');
		$data['entry_tgl_bayar'] = $this->language->get('entry_tgl_bayar');
		$data['entry_pengirim'] = $this->language->get('entry_pengirim');
		$data['entry_nama_bank_pengirim'] = $this->language->get('entry_nama_bank_pengirim');

		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_your_details'] = $this->language->get('text_your_details');

		$data['entry_enquiry'] = $this->language->get('entry_enquiry');

		$data['button_upload'] = $this->language->get('button_upload');
		$data['entry_captcha'] = $this->language->get('entry_captcha');

		$data['customer_group_id'] = isset($this->request->get['mid']) ? $this->request->get['mid'] : 0;

		if ($this->customer->isLogged()) {
			$data['customer_id'] = $this->customer->getId();

			$reseller_info = $this->model_account_reseller->getReseller($data['customer_id']);

			if(!empty($reseller_info)){
				$data['country_id'] = $reseller_info['country_id'];
				$data['zone_id'] = $reseller_info['zone_id'];
				$data['address'] = $reseller_info['address'];
				$data['is_reseller'] = true;
			} else {
				$data['country_id'] 	= $this->config->get('config_country_id');
				$data['zone_id'] 		= $this->config->get('config_zone_id');
			}

			$data['firstname'] 	= $this->customer->getFirstName();
			$data['lastname'] 	= $this->customer->getLastName();
			$data['telephone'] 	= $this->customer->getTelephone();

			$telephone = strpos($data['telephone']," ") !== false ? explode(" ",$data['telephone']) : explode("-",$data['telephone']);

			$data['default_country'] = count($telephone) > 1 ? $telephone[0] : '';
			$data['telephone'] 			 = count($telephone) > 1 ? $telephone[1] : $this->customer->getTelephone();

			$data['email'] = $this->customer->getEmail();
			$data['current_customer_group']  = $this->customer->getGroupId();
		} else {
			$data['country_id'] 	= $this->config->get('config_country_id');
			$data['zone_id'] 		= $this->config->get('config_zone_id');
			$data['customer_id'] 	= '';
			$data['firstname'] 		= '';
			$data['lastname'] 		= '';
			$data['telephone'] 		= '';
			$data['default_country'] = '';
			$data['email'] 			= '';
			$data['current_customer_group']  = 0;
		}

		$data['action'] = $this->url->link('account/reseller/save');

		$data['button_submit'] = $this->language->get('button_submit');

		if ($this->config->get('config_google_captcha_status')) {
			$this->document->addScript('https://www.google.com/recaptcha/api.js');

			$data['site_key'] = $this->config->get('config_google_captcha_public');
		} else {
			$data['site_key'] = '';
		}

		if (isset($this->request->post['captcha'])) {
			$data['captcha'] = $this->request->post['captcha'];
		} else {
			$data['captcha'] = '';
		}

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['help_email'] = $this->customer->isLogged() ? $this->language->get('help_email_logged') : $this->language->get('help_email');

		$data['customer_groups_active'] = $this->model_account_reseller->getCustomerGroupsActive();

		$data['hpwd_reseller_management_text_attachment'] = $this->config->get('hpwd_reseller_management_text_attachment_' . $this->config->get('config_language_id'));

		$data['id_card_entry'] = $this->config->get('hpwd_reseller_management_id_card_entry');

		$data['social_media_profile'] = $this->config->get('hpwd_reseller_management_social_media_profile');


		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/reseller', $data));
	}

	public function success() {
		$this->load->language('account/reseller');
		$this->load->model('account/reseller');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false,
		];

		$data['breadcrumbs'][] = [
			'text' => $data['heading_title'],
			'href' => $this->url->link('account/reseller'),
			'separator' => $this->language->get('text_separator'),
		];

		$data['heading_title'] = $data['heading_title'];
		$data['text_message'] = sprintf($this->language->get('text_message'), $this->model_account_confirm->getOrderStatus());
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['button_submit'] = $this->language->get('button_submit');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}

	private function validate() {

		$validatedata = [
			'firstname' => [
				'validate' => (isset($this->request->post['firstname']) && (utf8_strlen($this->request->post['firstname']) < 1)),
				'text_validate' => 'error_firstname',
			],
			'lastname' => [
				'validate' => (isset($this->request->post['lastname']) && (utf8_strlen($this->request->post['lastname']) < 1)),
				'text_validate' => 'error_lastname',
			],
			'email' => [
				'validate' => ((isset($this->request->post['email'])) && (!(filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)))),
				'text_validate' => 'error_email',
			],
			'address' => [
				'validate' => (isset($this->request->post['address']) && (utf8_strlen($this->request->post['address']) < 1)),
				'text_validate' => 'error_address',
			],
			'telephone' => [
				'validate' => (isset($this->request->post['telephone']) && (utf8_strlen($this->request->post['telephone']) < 1)),
				'text_validate' => 'error_telephone',
			],
			'password' => [
				'validate' => (isset($this->request->post['confirm']) && (utf8_strlen($this->request->post['password']) < 5)),
				'text_validate' => 'error_password',
			],
			'confirm' => [
				'validate' => (isset($this->request->post['confirm']) && ($this->request->post['confirm'] !== $this->request->post['password'])),
				'text_validate' => 'error_confirm',
			],
			'address' => [
				'validate' => (isset($this->request->post['address']) && (utf8_strlen($this->request->post['address']) < 1)),
				'text_validate' => 'error_input',
			],
			'customer_group_id' => [
				'validate' => (isset($this->request->post['customer_group_id']) && ($this->request->post['customer_group_id'] < 1)),
				'text_validate' => 'error_input',
			],
			'country_id' => [
				'validate' => (isset($this->request->post['country_id']) && ($this->request->post['country_id'] < 1)),
				'text_validate' => 'error_input',
			],
			'zone_id' => [
				'validate' => (isset($this->request->post['zone_id']) && ($this->request->post['zone_id'] < 1)),
				'text_validate' => 'error_input',
			]
		];

		if ($this->config->get('hpwd_reseller_management_id_card_entry') == 2) {
			$validatedata['no_nik'] = [
				'validate' => (isset($this->request->post['no_nik']) && (int)(utf8_strlen($this->request->post['no_nik']) < 10)),
				'text_validate' => 'error_nik',
			];
		}

		if(!$this->is_reseller) {
			if ($this->config->get('hpwd_reseller_management_attachment_required')) {
				$validatedata['lampiran'] = [
					'validate' => (isset($this->request->post['lampiran']) && (utf8_strlen($this->request->post['lampiran']) < 1)),
					'text_validate' => 'error_input',
				];
			}
		}

		foreach ($this->request->post as $key => $value) {
			if (isset($validatedata[$key])) {
				if ($validatedata[$key]['validate']) {
					$this->error[$key] = $this->language->get($validatedata[$key]['text_validate']);
				}
			}
		}


		if (!$this->customer->isLogged()) {
			$reseller_info = $this->model_account_reseller->getResellerByEmail($this->request->post['email']);

			if ($reseller_info && isset($this->request->post['email']) && (utf8_strlen($this->request->post['email']) > 0)) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}


		return !$this->error;
	}

	public function upload() {
		$this->load->language('reseller/reseller');

		$json = [];

		if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
			// Sanitize the filename
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

			// Validate the filename length
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// Allowed file extension types
			$allowed = [];

			$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));

			$filetypes = explode("\n", $extension_allowed);

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Allowed file mime types
			$allowed = [];

			$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

			$filetypes = explode("\n", $mime_allowed);

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array($this->request->files['file']['type'], $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Check to see if any PHP files are trying to be uploaded
			$content = file_get_contents($this->request->files['file']['tmp_name']);

			if (preg_match('/\<\?php/i', $content)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Return any upload error
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json) {
			$file = $filename . '.' . md5(mt_rand());

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);

			// Hide the uploaded file name so people can not link to it directly.
			$this->load->model('tool/upload');

			$json['code'] = $this->model_tool_upload->addUpload($filename, $file);

			$json['success'] = $filename;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function save() {
		$this->language->load('account/reseller');
		$this->load->model('account/reseller');
		$this->load->model('account/customer');
		$this->load->model('account/customer_group');

		$reseller_info = '';

		if($this->customer->getId()) {
			$reseller_info = $this->model_account_reseller->getReseller($this->customer->getId());
			if($reseller_info) {
				$this->is_reseller = 1;
			}
		}


		$json = [];

		// if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$customer_id = 0;

			if ($this->customer->isLogged()) {
				$this->request->post['firstname'] 	= $this->customer->getFirstName();
				$this->request->post['lastname'] 	= $this->customer->getLastName();
				$this->request->post['email'] 		= $this->customer->getEmail();

				$customer_id = $this->customer->getId();
			}

			//$customer_group_id = $this->getInitialGroupLevelId();
			//$this->request->post['customer_group_id'] = $customer_group_id;
			$customer_group_id = $this->request->post['customer_group_id'];
			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

			$reseller_info = $this->model_account_reseller->getReseller($customer_id);


			if ($this->customer->isLogged() && $reseller_info) {
				$this->model_account_reseller->editReseller($this->customer->getId(), $this->request->post);
				$json['logout_url'] = '';

			} else if($this->customer->isLogged() && !$reseller_info) {
				$this->model_account_reseller->addResellerFromCustomer($this->customer->getId(), $this->request->post);
				$json['logout_url'] = '';

			} else {
				$json['logout_url'] = $this->url->link('account/login');
				$this->model_account_reseller->addReseller($this->request->post);
			}



			if(!$this->is_reseller) {
				$mail = new Mail($this->config->get('config_mail_engine'));
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

				$mail->setTo($this->request->post['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setReplyTo($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->post['firstname'] . " " . $this->request->post['lastname'], $this->config->get('config_name')), ENT_QUOTES, 'UTF-8'));

				$data['text_mail_welcome'] = sprintf($this->language->get('text_mail_welcome'), $this->request->post['firstname']);
				$data['text_mail_thanks'] = sprintf($this->language->get('text_mail_thanks'), $this->config->get('config_name'));

				$mail->setHtml(html_entity_decode($this->load->view('account/mail_reseller', $data)));
				// print_r($mail);die();
				$mail->send();
			}


			$name = $this->request->post['firstname'] . " " . $this->request->post['lastname'];

			$json['success'] = sprintf($this->language->get('success_register'), $name, $customer_group_info['name']);

			$data['store'] 	 = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

			// $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
			// $this->customer->login($this->request->post['email'], $this->request->post['password']);
			// unset($this->session->data['guest']);

		}

		foreach ($this->error as $key => $value) {
			if (isset($this->error[$key])) {
				$json['error'][$key] = $this->error[$key];
			}
		}

		//  $json['success'] = 'ok';

		$this->response->setOutput(json_encode($json));
	}

	public function getInitialGroupLevelId() {
		$groupLevel = $this->config->get('hpwd_reseller_management_reseller_level');

		$customer_group_id = 0;

		$lowestLevel = 10000;

		foreach ($groupLevel as $id => $group) {
			if ($group['level'] < $lowestLevel) {
				$lowestLevel = $group['level'];
				$customer_group_id = $id;
			}
		}

		return $customer_group_id;
	}

	public function closeNotif() {
		$this->load->model('account/reseller');
		$this->model_account_reseller->deleteNotif($this->customer->getId());
	}

	// model/checkout/order/addOrderHistory/before
	public function addOrderHistory(&$route, &$args) {
		// If last order status id is 0 and new order status is not then record as new order
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($args[0]);

		if ($order_info && !$order_info['order_status_id'] && $args[1]) {
			$this->load->model('account/reseller');

			$reseller_id = $order_info['customer_id'];
			$order_amount = (int)$this->model_account_reseller->getOrderAmount($reseller_id);
			$groupLevels = $this->config->get('hpwd_reseller_management_reseller_level');

			$new_group = false;

			foreach ($groupLevels as $id => $group) {
				if ($group['total_checkout'] <= $order_amount) {
					$new_group = $id;
				}
			}

			if ($new_group) {
				$this->model_account_reseller->updateLevel($reseller_id, $new_group);
			}
		}
	}
}
