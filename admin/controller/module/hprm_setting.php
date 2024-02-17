<?php

class ControllerModuleHprmSetting extends Controller
{

	private $error              = [];
	private $version            = '1.3.1.4';
	private $v_d                = '';
	private $extension_code     = 'hprm';
	private $extension_type 	= 'io';
	private $domain             = '';

	public function __construct($args)
	{
		parent::__construct($args);

		$this->route = $this->request->get['route'];
		$this->error = [];
		$this->v_d = '';
	}

	public function index()
	{
		/*VALIDATOR*************************************************************************/
		$this->checkEvent();
		/*VALIDATOR*************************************************************************/

		/*SYSTEM LOADER*********************************************************************/
		// Language
		$this->load->language($this->route);
		$this->document->addScript('view/javascript/selectpicker/js/bootstrap-select.min.js');
		$this->document->addStyle('view/javascript/selectpicker/css/bootstrap-select.min.css');
		$this->document->addStyle('view/javascript/desktop_theme.css');
		// Model
		$this->load->model($this->route);
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');
		$this->load->model('customer/customer_group');

		/*SYSTEM LOADER*********************************************************************/

		/*SETTER****************************************************************************/
		// Version
		$data['version'] = $this->version;

		// Variable
		$data = [];
		$data['languages'] = $this->model_localisation_language->getLanguages();
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['customer_group_id'] = $this->config->get('config_customer_group_id');

		// Page Configuration
		$this->document->setTitle($this->language->get('heading_title2'));
		$this->document->addScript('view/javascript/bootstrap/js/bootstrap-checkbox.min.js');

		// Breadcrumbs
		$data['breadcrumbs'] = [];
		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
		];
		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title2'),
			'href' => $this->url->link('module/hprm_setting', 'token=' . $this->session->data['token'], true),
		];

		$data['text_btn_save'] = $this->language->get('text_btn_save');
        $data['heading_title2'] = $this->language->get('heading_title2');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['text_hide'] = $this->language->get('text_hide');
        $data['text_show'] = $this->language->get('text_show');
        $data['text_required'] = $this->language->get('text_required');
        $data['text_reseller_page'] = $this->language->get('text_reseller_page');
        $data['text_reseller_list_page'] = $this->language->get('text_reseller_list_page');
        $data['entry_attachment_required'] = $this->language->get('entry_attachment_required');
        $data['text_uninstal_table'] = $this->language->get('text_uninstal_table');
        $data['text_uninstal_table_sub'] = $this->language->get('text_uninstal_table_sub');
        $data['text_bottom_link'] = $this->language->get('text_bottom_link');
        $data['help_bottom_link'] = $this->language->get('help_bottom_link');
        $data['text_toplink'] = $this->language->get('text_toplink');
        $data['help_toplink'] = $this->language->get('help_toplink');
        $data['text_reseller_affiliate'] = $this->language->get('text_reseller_affiliate');
        $data['text_sms_setting'] = $this->language->get('text_sms_setting');
        $data['text_sms_option'] = $this->language->get('text_sms_option');
        $data['text_sms_gateway'] = $this->language->get('text_sms_gateway');
        $data['text_sms_masking'] = $this->language->get('text_sms_masking');
        $data['text_sms_reguler'] = $this->language->get('text_sms_reguler');
        $data['text_sms_center'] = $this->language->get('text_sms_center');
        $data['text_eligible_api_usage'] = $this->language->get('text_eligible_api_usage');
        $data['text_active'] = $this->language->get('text_active');
        $data['text_inactive'] = $this->language->get('text_inactive');
        $data['text_auto_increase'] = $this->language->get('text_auto_increase');
        $data['text_limit_usage_warning'] = $this->language->get('text_limit_usage_warning');
        $data['text_zenziva'] = $this->language->get('text_zenziva');
        $data['entry_text_whatsapp_register'] = $this->language->get('entry_text_whatsapp_register');
        $data['entry_text_whatsapp_register_sub'] = $this->language->get('entry_text_whatsapp_register_sub');
        $data['entry_heading_resellerlist'] = $this->language->get('entry_heading_resellerlist');
        $data['entry_heading_resellerlist_sub'] = $this->language->get('entry_heading_resellerlist_sub');
        $data['entry_instruction_resellerlist'] = $this->language->get('entry_instruction_resellerlist');
        $data['entry_instruction_resellerlist_sub'] = $this->language->get('entry_instruction_resellerlist_sub');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['error_invalid_api'] = $this->language->get('error_invalid_api');
        $data['error_warning'] = $this->language->get('error_warning');
        $data['error_install'] = $this->language->get('error_install');
        $data['error_uninstall'] = $this->language->get('error_uninstall');
        $data['error_permission'] = $this->language->get('error_permission');
        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_sms'] = $this->language->get('tab_sms');
        $data['tab_translation'] = $this->language->get('tab_translation');
        $data['tab_advance'] = $this->language->get('tab_advance');
        $data['tab_help'] = $this->language->get('tab_help');
        $data['tab_group'] = $this->language->get('tab_group');
        $data['tooltip_btn_save'] = $this->language->get('tooltip_btn_save');
        $data['entry_reseller_group'] = $this->language->get('entry_reseller_group');
        $data['entry_reseller_group_sub'] = $this->language->get('entry_reseller_group_sub');
        $data['entry_instan_msg'] = $this->language->get('entry_instan_msg');
        $data['entry_instan_msg_sub'] = $this->language->get('entry_instan_msg_sub');
        $data['entry_instan_msg_entry_whatsapp'] = $this->language->get('entry_instan_msg_entry_whatsapp');
        $data['entry_instan_msg_entry_messenger'] = $this->language->get('entry_instan_msg_entry_messenger');
        $data['entry_instan_msg_entry_telegram'] = $this->language->get('entry_instan_msg_entry_telegram');
        $data['entry_level'] = $this->language->get('entry_level');
        $data['entry_total_checkout'] = $this->language->get('entry_total_checkout');
        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_default_country'] = $this->language->get('entry_default_country');
        $data['entry_id_card'] = $this->language->get('entry_id_card');
        $data['entry_social_media_profile'] = $this->language->get('entry_social_media_profile');
        $data['entry_patch_upgrade'] = $this->language->get('entry_patch_upgrade');
        $data['help_country'] = $this->language->get('help_country');
        $data['help_default_country'] = $this->language->get('help_default_country');
        $data['help_auto_increase'] = $this->language->get('help_auto_increase');
        $data['help_status'] = $this->language->get('help_status');
        $data['help_id_card'] = $this->language->get('help_id_card');
        $data['help_social_media_profile'] = $this->language->get('help_social_media_profile');
        $data['help_patch_upgrade'] = $this->language->get('help_patch_upgrade');
        $data['text_support_content'] = $this->language->get('text_support_content');
        $data['text_success_install_table'] = $this->language->get('text_success_install_table');
        $data['text_success_changes_saved'] = $this->language->get('text_success_changes_saved');
        $data['text_upgrade_database'] = $this->language->get('text_upgrade_database');
        $data['entry_sms_userkey'] = $this->language->get('entry_sms_userkey');
        $data['entry_sms_passkey'] = $this->language->get('entry_sms_passkey');
        $data['entry_sms_receipt'] = $this->language->get('entry_sms_receipt');
        $data['entry_sms_receipt_template'] = $this->language->get('entry_sms_receipt_template');
        $data['entry_sms_gateway'] = $this->language->get('entry_sms_gateway');
        $data['help_reseller_level_tab'] = $this->language->get('help_reseller_level_tab');
        $data['help_attachment_required'] = $this->language->get('help_attachment_required');

		// Layout
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		// Page Url
		$data['action'] = $this->url->link('module/hprm_setting', 'token=' . $this->session->data['token'], true);
		$data['actioninstall'] = (!empty($this->request->get['install'])) ? $this->request->get['install'] : '';
		$data['uninstall'] = $this->url->link('module/hprm_setting/uninstallDatabase', 'token=' . $this->session->data['token'], true);

		$data['upgrade'] = $this->url->link('module/hprm_setting/patchDatabase', 'token=' . $this->session->data['token'], true);

		$data['changes'] = (!empty($this->request->get['changes'])) ? $this->request->get['changes'] : '';

		// Variable - Input
		$data['social_media'] = ['messenger', 'telegram'];
		$data['social_ico'] = ['https://m.me/', 'https://t.me/'];
		$data['sms'] = [
			'sms_gateway', 'sms_userkey', 'sms_passkey', 'sms_send_receipt', 'sms_receipt_template'];

		$data['translation'] = [
			[
				'code' => 'heading_title',
				'title' => 'Heading Title',
				'title_sub' => 'Title for reseler registration page',
				'type' => 'input',
				'default' => 'text_default_heading_title',
			],
			[
				'code' => 'text_instruction',
				'title' => 'Text Instruction',
				'title_sub' => 'Registration instruction that displayed right after heading title',
				'type' => 'textarea',
				'default' => 'text_default_instruction',
			],
			[
				'code' => 'btn_register',
				'title' => 'Button Register Title',
				'title_sub' => 'Title for reseller registration button',
				'type' => 'input',
				'default' => 'text_default_button_register',
			],
			[
				'code' => 'text_attachment',
				'title' => 'Text Help Attachment',
				'title_sub' => 'Help text that will be displayed in attachment field',
				'type' => 'textarea',
				'default' => 'text_default_text_attachment',
			],
			[
				'code' => 'text_whatsapp',
				'title' => 'Text Whatsapp',
				'title_sub' => 'Text for registration using whatsapp',
				'type' => 'textarea',
				'default' => 'text_default_text_whatsapp_register',
			],
			[
				'code' => 'heading_resellerlist',
				'title' => 'Heading Title',
				'title_sub' => 'Title fo reseller list page',
				'type' => 'input',
				'default' => 'text_default_heading_resellerlist',
				'group' => 'Reseller List'
			],
			[
				'code' => 'instruction_resellerlist',
				'title' => 'Text Instruction',
				'title_sub' => 'Instruction that displayed right after heading title',
				'type' => 'textarea',
				'default' => 'text_default_instruction_resellerlist',
			]
		];

		$data['reseller_page'] = HTTPS_CATALOG . 'index.php?route=account/reseller';
		$data['reseller_list_page'] = HTTPS_CATALOG . 'index.php?route=account/reseller_list';

		/*SETTER****************************************************************************/

		/*POST METHOD **********************************************************************/
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			// print_r($this->request->post);die;
			// Get event reseller_order
			// $this->load->model('setting/event');
			// $event = $this->model_setting_event->getEventByCode('reseller_order');
			// echo '<pre>';print_r($event);echo '</pre>';die();
			// if (isset($this->request->post['hpwd_reseller_management_auto_increase']) && $this->request->post['hpwd_reseller_management_auto_increase']) {
			// 	$this->model_setting_event->enableEvent($event['event_id']);
			// } else {
			// 	$this->model_setting_event->disableEvent($event['event_id']);
			// }

			// Saving Setting Variable :: Table Setting
			$this->model_setting_setting->editSetting('hpwd_reseller_management', $this->request->post);
			$this->model_setting_setting->editSetting('module_hprm_setting', ['module_hprm_setting_status' => $this->request->post['hpwd_reseller_management_status']]);
			$this->session->data['success'] = $this->language->get('text_success');

			// Saving Dynamic Variable
			foreach ($data['customer_groups'] as $key => $value) {
				$data['hpwd_reseller_management_group'] = $this->request->post['hpwd_reseller_management_group'];
			}

			foreach ($data['translation'] as $key => $value) {
				foreach ($data['languages'] as $keys => $values) {
					$data['hpwd_reseller_management_' . $value['code'] . '_' . $values['language_id']] = $this->request->post['hpwd_reseller_management_' . $value['code'] . '_' . $values['language_id']];
				}
			}
			// Saving Dynamic Variable
			$this->response->redirect($this->url->link('module/hprm_setting', 'token=' . $this->session->data['token'] . '&changes=saved', true));

		}
		/*POST METHOD **********************************************************************/

		/*Setter Variable ******************************************************************/
		$data['hpwd_reseller_management_id_card_entry'] = $this->config->get('hpwd_reseller_management_id_card_entry');
		$data['hpwd_reseller_management_social_media_profile'] = $this->config->get('hpwd_reseller_management_social_media_profile');
		$data['hpwd_reseller_management_status'] = $this->config->get('hpwd_reseller_management_status');
		$data['hpwd_reseller_management_total_checkout'] = $this->config->get('hpwd_reseller_management_total_checkout');
		$data['hpwd_reseller_management_top_link'] = $this->config->get('hpwd_reseller_management_top_link');
		$data['hpwd_reseller_management_bottom_link'] = $this->config->get('hpwd_reseller_management_bottom_link');
		$data['hpwd_reseller_management_auto_increase'] = $this->config->get('hpwd_reseller_management_auto_increase');
		$data['hpwd_reseller_management_attachment_required'] = $this->config->get('hpwd_reseller_management_attachment_required');
		$data['hpwd_reseller_management_group'] = array(1);
		$data['hpwd_reseller_management_toggle_socmed'] = $this->config->get('hpwd_reseller_management_toggle_socmed');
		$data['hpwd_reseller_management_reseller_level'] = $this->config->get('hpwd_reseller_management_reseller_level');


		$data['thousand_point'] = $this->language->get('thousand_point');
		$data['currency_symbol'] = $this->currency->getSymbolLeft($this->config->get('config_currency'));

		$data['help_reseller_level_tab'] = sprintf($this->language->get('help_reseller_level_tab'), $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'], true));

		foreach ($data['social_media'] as $key => $value) {
			if ($this->config->get('hpwd_reseller_management_check_entry_' . $value)) {
				$data['hpwd_reseller_management_check_entry_' . $value] = $this->config->get('hpwd_reseller_management_check_entry_' . $value);
			} else {
				$data['hpwd_reseller_management_check_entry_' . $value] = '';
			}
			if ($this->config->get('hpwd_reseller_management_' . $value)) {
				$data['hpwd_reseller_management_' . $value] = $this->config->get('hpwd_reseller_management_' . $value);
			} else {
				$data['hpwd_reseller_management_' . $value] = '';
			}
		}


		if ($this->config->get('hpwd_reseller_management_check_entry_whatsapp')) {
			$data['hpwd_reseller_management_check_entry_whatsapp'] = $this->config->get('hpwd_reseller_management_check_entry_whatsapp');
		} else {
			$data['hpwd_reseller_management_check_entry_whatsapp'] = '';
		}

		if ($this->config->get('hpwd_reseller_management_whatsapp_country')) {
			$data['hpwd_reseller_management_whatsapp_country'] = $this->config->get('hpwd_reseller_management_whatsapp_country');
		} else {
			$data['hpwd_reseller_management_whatsapp_country'] = '';
		}

		if ($this->config->get('hpwd_reseller_management_whatsapp')) {
			$data['hpwd_reseller_management_whatsapp'] = $this->config->get('hpwd_reseller_management_whatsapp');
		} else {
			$data['hpwd_reseller_management_whatsapp'] = '';
		}

		// default language
		$data['countries'] = $this->model_module_hprm_setting->getCountries();
		
		if ($this->config->get('hpwd_reseller_management_default_country')) {
			$data['hpwd_reseller_management_default_country'] = $this->config->get('hpwd_reseller_management_default_country');
		} else {
			$data['hpwd_reseller_management_default_country'] = "ID";
		}

		if (isset($this->request->post['hpwd_reseller_management_country'])) {
			$data['hpwd_reseller_management_country'] = $this->request->post['hpwd_reseller_management_country'];
		} else if ($this->config->get('hpwd_reseller_management_country')) {
			$data['hpwd_reseller_management_country'] = $this->config->get('hpwd_reseller_management_country');
		} else {
			$data['hpwd_reseller_management_country'] = [];
		}

		$data['hpwd_reseller_management_country'] = json_encode($data['hpwd_reseller_management_country']);

		
		$availabel_countries = json_decode($data['hpwd_reseller_management_country']);
		$data['availabel_countries'] = [];

		foreach ($availabel_countries as $availabel_country) {
			$availabel_country = explode("--", $availabel_country);
			$data['availabel_countries'][] = $availabel_country[1];
		}

		$data['availabel_countries'] = json_encode($data['availabel_countries']);

		foreach ($data['sms'] as $key => $value) {
			if ($this->config->get('hpwd_reseller_management_' . $value)) {
				$data['hpwd_reseller_management_' . $value] = $this->config->get('hpwd_reseller_management_' . $value);
			} else {
				$data['hpwd_reseller_management_' . $value] = '';
			}
		}

		foreach ($data['translation'] as $key => $value) {
			foreach ($data['languages'] as $keys => $values) {
				if (!empty($this->config->get('hpwd_reseller_management_' . $value['code'] . '_' . $values['language_id']))) {
					$data['hpwd_reseller_management_' . $value['code'] . '_' . $values['language_id']] = $this->config->get('hpwd_reseller_management_' . $value['code'] . '_' . $values['language_id']);
				} else {
					$data['hpwd_reseller_management_' . $value['code'] . '_' . $values['language_id']] = $this->language->get($value['default']);
				}
			}
		}

		foreach ($data['languages'] as $language) {
			if (!empty($this->config->get('hpwd_reseller_management_instruction_' . $language['language_id']))) {
				$data['hpwd_reseller_management_instruction_' . $language['language_id']] = $this->config->get('hpwd_reseller_management_instruction_' . $language['language_id']);
			} else {
				$data['hpwd_reseller_management_instruction_' . $language['language_id']] = $this->language->get('placeholder_attachment_instruction');
			}
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}

		if (isset($this->error['customer_group'])) {
			$data['error_customer_group'] = $this->error['customer_group'];
		} else {
			$data['error_customer_group'] = '';
		}

		$this->domain	 = str_replace("www.","",$_SERVER['SERVER_NAME']);

		$this->houseKeeping();

		$this->rightman();


		/*Setter Variable ******************************************************************/

		/*RUN HPRM ISTALLATION, VALIDATION AND VIEW*/
		if (!$this->validateTable()) {
			$this->viewInstallTable();
		} else {
			/*RUN VALIDATION*/
			if ($this->domain != $this->v_d) {
				$this->storeAuth();
			} else {
				$data['version'] 			= $this->version;
				$data['extension_code'] 	= $this->extension_code;
				$data['extension_type'] 	= $this->extension_type;
				$data['token'] = $this->session->data['token'];
				$this->response->setOutput($this->load->view($this->route, $data));
			}

		}
		/*RUN HPRM ISTALLATION, VALIDATION AND VIEW*/
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'module/hprm_setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		// if (!isset($this->request->post['hpwd_reseller_management_country']) || !count($this->request->post['hpwd_reseller_management_country'])) {
		// 	$this->error['country'] = $this->language->get('error_empty_country');
		// }

		// if (!isset($this->request->post['hpwd_reseller_management_group']) || !count($this->request->post['hpwd_reseller_management_group'])) {
		// 	$this->error['customer_group'] = $this->language->get('error_empty_customer_group');
		// }

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function checkEvent()
    {
        $isExist = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "event` WHERE `code` = 'reseller_order' LIMIT 1");
        if (!$isExist->row) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'reseller_order', `trigger` = 'catalog/model/checkout/order/addOrderHistory/before', `action` = 'account/reseller/addOrderHistory'");
        }
    }

	public function viewInstallTable()
	{
		$this->load->language('module/hprm_setting');

		if ($_SERVER['SERVER_NAME'] != $this->v_d) {
			$this->storeAuth();
		} else {
			$this->document->setTitle($this->language->get('error_database'));
			$data['install_database'] = $this->url->link('module/hprm_setting/installDatabase', 'token=' . $this->session->data['token'], true);
			$data['text_install_message'] = $this->language->get('text_install_message');
			$data['text_upgrade'] = $this->language->get('text_upgrade');
			$data['error_database'] = $this->language->get('error_database');
			$data['breadcrumbs'] = [];
			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], true),
				'separator' => false,
			];
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('module/hpwd_notification', $data));
		}
	}


	public function installDatabase()
	{
		$this->load->language('module/hprm_setting');
		$this->load->model('module/hprm_setting');
		$error = 0;

		if (!$this->model_module_hprm_setting->installTable()) {
			$error++;
		}

		if ($error < 1) {
			$this->response->redirect($this->url->link('module/hprm_setting', 'token=' . $this->session->data['token'] . "&install=true", true));
		} else {
			print_r($error);
			die();
			$this->response->redirect($this->url->link('module/hprm_setting', 'token=' . $this->session->data['token'] . "&install=false", true));
			$this->model_module_hprm_setting->installTable();
			$this->session->data['success'] = $this->language->get('text_success_installed');
			$route = $this->request->get['url'];
			$this->response->redirect($this->url->link($route, 'token=' . $this->session->data['token'], true));
		}
	}

	public function uninstallDatabase()
	{
		$this->load->model('module/hprm_setting');
		if ($this->model_module_hprm_setting->uninstallTable()) {
			$this->response->redirect($this->url->link('module/hprm_setting', 'token=' . $this->session->data['token'] . "&uninstall=true", true));
		}
	}

	public function uninstall()
	{
		$this->load->language('module/hprm_setting');

		if (!$this->checkDatabase()) {

			$this->document->setTitle($this->language->get('error_database'));

			$data['install_database'] = $this->url->link('module/hprm_setting/uninstallDatabase', 'token=' . $this->session->data['token'], true);

			$data['text_install_message'] = $this->language->get('text_uninstall_message');
			$data['text_upgrade'] = $this->language->get('text_downgrade');
			$data['error_database'] = $this->language->get('text_found_database');
			$data['breadcrumbs'] = [];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], true),
				'separator' => false,
			];

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('module/hpwd_notification', $data));
		}
	}

	public function checkDatabase()
	{
		$database_not_found = $this->validateTable();

		if (!$database_not_found) {
			return true;
		}

		return false;
	}

	public function patchDatabase()
	{
		$sqls = [];

		$table_column['customer_to_reseller'] = [
			"country_code" 	=> "VARCHAR(12)",
			"zone_id" 		=> "INT(11)",
			"country_id" 	=> "INT(11)"
		];
		$indexes = [
			'customer_to_reseller' => 'customer_id'
		];

		foreach ($table_column as $table => $columns) {
			foreach ($columns as $column => $type) {
				$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` LIKE '" . $column . "';");
				if (!$query->num_rows) {
					$sqls[] = "ALTER TABLE `" . DB_PREFIX . $table . "` ADD " . $column . " " . $type . " NOT NULL AFTER " . $indexes[$table] . "";
				}
			}
		}

		$error = 0;

		foreach ($sqls as $sql) {

			if (!$this->db->query($sql)) {
				$error++;
			}
			sleep(0.2);
		}





		if(!$error){
			$this->session->data['success'] = "Success install database. Now continue your life.";
		}else{
			$this->session->data['success'] = 'Already installed before. Don\'t you remember?';
		}

		$this->response->redirect($this->url->link('module/hprm_setting', 'token=' . $this->session->data['token'], true));
	}

	public function validateTable()
	{
		$sqls[] = "SHOW TABLES LIKE '" . DB_PREFIX . "customer_to_reseller'";
		$sqls[] = "SHOW TABLES LIKE '" . DB_PREFIX . "reseller_notif'";

		$status = true;
		foreach ($sqls as $sql) {
			$query = $this->db->query($sql);

			if (!$query->num_rows) {
				$status = false;
			}
		}

		return $status;
	}

	public function curlcheck()
	{
		return in_array('curl', get_loaded_extensions()) ? true : false;
	}

	private function internetAccess()
	{
		return true;
	}


	public function storeAuth() {
		$data['curl_status'] = $this->curlcheck();
		$data['extension_code'] = $this->extension_code;
		$data['extension_type'] = $this->extension_type;
		$data['token'] = $this->session->data['token'];
		$this->flushdata();

		$this->document->setTitle($this->language->get('text_validation'));

		$data['text_curl']                  = $this->language->get('text_curl');
		$data['text_disabled_curl']         = $this->language->get('text_disabled_curl');

		$data['text_validation']            = $this->language->get('text_validation');
		$data['text_validate_store']        = $this->language->get('text_validate_store');
		$data['text_information_provide']   = $this->language->get('text_information_provide');
		$data['domain_name'] = str_replace("www.","",$_SERVER['SERVER_NAME']);

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], true),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title2'),
			'href'      => $this->url->link('module/hprm_setting', 'token=' . $this->session->data['token'], true),
			'separator' => false
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/validation', $data));
	}

	private function rightman() {
		if($this->internetAccess()) {
			$this->load->model('module/system_startup');

			$license = $this->model_module_system_startup->checkLicenseKey($this->extension_code);

			if ($license) {
					if (isset($this->model_module_system_startup->licensewalker)) {
						$url = $this->model_module_system_startup->licensewalker($license['license_key'],$this->extension_code,$this->domain);
						$data = $url;
						$domain = isset($data['domain']) ? $data['domain'] : '';

						if($domain == $this->domain) {
							$this->v_d = $domain;
						} else {
							$this->flushdata();
						}
					 }
				}

		} else {
			$this->error['warning'] = $this->language->get('error_no_internet_access');
		}
	}

	private function houseKeeping() {
		$file = 'https://api.hpwebdesign.io/validate.zip';
		$newfile = DIR_APPLICATION.'validate.zip';

		if (!file_exists(DIR_APPLICATION.'controller/common/hp_validate.php') || !file_exists(DIR_APPLICATION.'model/module/system_startup.php') || !file_exists(DIR_APPLICATION.'view/template/module/validation.tpl')) {

		if ($this->download_file($file, $newfile)) {
			$zip = new ZipArchive();
			$res = $zip->open($newfile);
				if ($res === TRUE) {
				  $zip->extractTo(DIR_APPLICATION);
				  $zip->close();
				  unlink($newfile);
				}
			}
		}

		$this->load->model('module/system_startup');
		if (!isset($this->model_module_system_startup->checkLicenseKey) || !isset($this->model_module_system_startup->licensewalker)) {

			if ($this->download_file($file, $newfile)) {
			$zip = new ZipArchive();
			$res = $zip->open($newfile);
				if ($res === TRUE) {
				  $zip->extractTo(DIR_APPLICATION);
				  $zip->close();
				  unlink($newfile);
				}
			}
		}

		if(!file_exists(DIR_SYSTEM.'system.ocmod.xml')) {
			$str = $this->url_get_contents('https://api.hpwebdesign.io/system.ocmod.txt');

			file_put_contents(dirname(getcwd()) . '/system/system.ocmod.xml', $str);
		}
		$sql = "CREATE TABLE IF NOT EXISTS `hpwd_license`(
						`hpwd_license_id` INT(11) NOT NULL AUTO_INCREMENT,
						`license_key` VARCHAR(64) NOT NULL,
						`code` VARCHAR(32) NOT NULL,
						`support_expiry` date DEFAULT NULL,
						 PRIMARY KEY(`hpwd_license_id`)
					) ENGINE = InnoDB;";
		 $this->db->query($sql);
	}

	private function url_get_contents($Url) {
		if (!function_exists('curl_init')){
			die('CURL is not installed!');
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $Url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}

	public function install() {
		$this->houseKeeping();
	}

	public function flushdata() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` LIKE '%hpwd_reseller_management_status%'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` LIKE '%module_hprm_setting_status%'");
	}

	private function download_file($url, $path) {

	  $newfilename = $path;
	  $file = fopen ($url, "rb");
	  if ($file) {
		$newfile = fopen ($newfilename, "wb");

		if ($newfile)
		while(!feof($file)) {
		  fwrite($newfile, fread($file, 1024 * 8 ), 1024 * 8 );
		}
	  }

	  if ($file) {
		fclose($file);
	  }
	  if ($newfile) {
		fclose($newfile);
	  }
	 }


}
