<?php
class Controlleraccountewallet extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/e_wallet', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}
		$this->load->language('account/e_wallet');
		$e_title = $this->config->get($module_key.'e_wallet_language');
		list($data['wallet_title'],$data['add_money_text'],$data['send_money_text'],$data['withdraw_money_text'],$data['voucher_text'],$data['bank_detail_text']) = $this->lg(array('title','add_money_text','send_money_text','withdraw_money_text','voucher_text','bank_detail_text'));
		$heading_title = sprintf($data['wallet_title']);
		$this->document->setTitle($heading_title);
		$data['heading_title'] = $heading_title;
		if($thisvar == 3036) {
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');	
		}else{
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');		
		}	
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->load->model('localisation/currency');

		if(isset($this->session->data['currency'])){
			$config_currency =$this->session->data['currency'];
			$data['config_currency'] =$this->session->data['currency'];
		}else{
			$config_currency =$this->config->get('config_currency');
			$data['config_currency'] =$this->config->get('config_currency');
		}
		$data['symbol_left'] = $this->currency->getSymbolLeft($config_currency);
		$data['symbol_right'] = $this->currency->getSymbolRight($config_currency);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'] = array(array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		),array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		),array(
			'text' => $data['wallet_title'],
			'href' => $this->url->link('account/e_wallet', '', 'SSL')
		));
		$data['success'] = $data['error'] = '';
		if(isset($this->session->data['success'])){
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}
		if(isset($this->session->data['error'])){
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}
		
		$data['e_wallet_add_money']         = $this->config->get($module_key.'e_wallet_add_money');
		$data['e_wallet_send_money']        = $this->config->get($module_key.'e_wallet_send_money');
		$data['e_wallet_bank_detail']       = $this->config->get($module_key.'e_wallet_bank_detail');
		$data['e_wallet_withdraw_requests'] = $this->config->get($module_key.'e_wallet_withdraw_requests');
		$data['voucher_status']             = $this->config->get($module_key.'e_wallet_voucher_status');
		$data['add_money_form']             = $this->url->link('account/e_wallet/get_form');
		$data['text_balance']               = $this->language->get('text_balance');
		$data['text_amount']                = $this->language->get('text_amount');
		$data['text_transaction_id']        = $this->language->get('text_transaction_id');
		$data['text_desc']                  = $this->language->get('text_desc');
		$data['text_date']                  = $this->language->get('text_date');
		$data['text_credit']                = $this->language->get('text_credit');
		$data['text_debit']                 = $this->language->get('text_debit');
		$data['column_balance']             = $this->language->get('column_balance');
		$data['text_send_money']            = sprintf($data['send_money_text']);
		$data['text_withdrawreq']           = sprintf($data['withdraw_money_text']);
		$data['text_add_bank']              = sprintf($data['bank_detail_text']);
		$data['send_money']                 = $this->url->link('account/e_wallet/send_money');
		$data['withdrawreq']                = $this->url->link('account/e_wallet/withdrawreq');
		$data['add_bank']                   = $this->url->link('account/e_wallet/add_bank');
		$data['text_add_money']             = sprintf($data['add_money_text']." To ".$data['wallet_title']);
		$data['add_money']                  = $this->url->link('account/e_wallet/add_money');
		$data['formurl']                    = $this->url->link('account/e_wallet');
		$data['redeem_voucher']             = $this->url->link('account/e_wallet/redeem_voucher');
		$data['entry_from_date']            = $this->language->get('entry_from_date');
		$data['entry_to']                   = $this->language->get('entry_to');
		$data['entry_generate']             = $this->language->get('entry_generate');
		$data['entry_bank_detail']          = $this->language->get('entry_bank_detail');	
		$data['entry_branch_number']        = $this->language->get('entry_branch_number');
		$data['entry_bank_name']            = $this->language->get('entry_bank_name');
		$data['entry_swift_code']           = $this->language->get('entry_swift_code');
		$data['entry_ifsc_code']            = $this->language->get('entry_ifsc_code');
		$data['entry_account_name']         = $this->language->get('entry_account_name');
		$data['entry_account_number']       = $this->language->get('entry_account_number');
		$data['entry_save']                 = $this->language->get('entry_save');
		$data['entry_close']                = $this->language->get('entry_close');
		$data['text_redeem_voucher']        =  sprintf($data['voucher_text']);
		$data['voucher_redeem_title']       = $this->language->get('voucher_redeem_title');
		$data['entry_voucher_code']         = $this->language->get('entry_voucher_code'); 
	
		if($data['e_wallet_add_money']){
			$data['add_money'] = $this->url->link('account/e_wallet/add_money','','SSL');
		}
		
		$this->load->model('account/e_wallet');
		$page = 1;
		$limit = 20;
		$url ='';
		$data['ccurrency'] = $this->currency;
		if(isset($this->request->get['page'])) $page = (int)$this->request->get['page'];
		if(isset($this->request->get['limit'])) $limit = (int)$this->request->get['limit'];
		$filter = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		$filter['datefrom'] = $data['datefrom'] = date('m/d/Y');
		$filter['dateto'] = $data['dateto'] = date('m/d/Y');
		if(isset($this->request->request['datefrom'])){
			$filter['datefrom'] = $this->request->request['datefrom'];
			$url .='&datefrom='.$this->request->request['datefrom'];
			$data['datefrom'] = date('m/d/Y',strtotime($this->request->request['datefrom']));
		}

		if(isset($this->request->request['dateto'])){
			$filter['dateto'] = $this->request->request['dateto'];
			$url .= '&dateto='.$this->request->request['dateto'];
			$data['dateto'] = date('m/d/Y',strtotime($this->request->request['dateto']));
		}
		$this->load->model('tool/image');
		$data['e_wallet_icon_url'] = $this->model_tool_image->resize($this->config->get($module_key.'e_wallet_icon'), 30,30);
		if(isset($this->session->data['currency'])){
			$config_currency =$this->session->data['currency'];
			$data['config_currency'] =$this->session->data['currency'];
		}else{
			$config_currency =$this->config->get('config_currency');
			$data['config_currency'] =$this->config->get('config_currency');
		}

		/*$bank = $this->model_account_e_wallet->getbank();
		if($bank){
			$data['bank'] = array(
				'name' => $bank['bank_name'],
				'branch_number' => $bank['branch_code'],
				'swift_code' => $bank['swift'],
				'ifsc_code' => $bank['ifsc'],
				'account_name' => $bank['ac_name'],
				'account_number' => $bank['ac_no'],
			);
		}else{
			$data['bank'] = array('name'=>'','branch_number'=>'','swift_code'=>'','ifsc_code'=>'','account_name'=>'','account_number'=>'');
		}*/

		$other_theme = (int)defined("JOURNAL_VERSION");
		if($other_theme == 1){
			$data['journal_style'] = $this->document->addStyle('catalog/view/javascript/bootstrap/css/bootstrap.minupdate.css');
		}

		$data['balance'] = $this->currency->format($this->model_account_e_wallet->getBalance(),$config_currency);
		$e_wallet_list = $this->model_account_e_wallet->gettransaction($filter);
		$totaltrasaction = $this->model_account_e_wallet->gettransactiontotal($filter);
		$data['openningbalance'] = $this->model_account_e_wallet->getopenningbalance($filter);
		$data['e_wallet_list'] = array();
		foreach ($e_wallet_list as $v){
			$data['e_wallet_list'][] = array(
				'transaction_id' => $v['transaction_id'],
				'description' => $v['description'],
				'credit' => ($v['price'] >= 0 ? $this->currency->format($v['price'],$config_currency) : ''),
				'debit' => ($v['price'] < 0 ? $this->currency->format(abs($v['price']),$config_currency) : ''),
				'balance' => $this->currency->format($v['balance'],$config_currency),
				'o_credit' => ($v['price'] > 0 ? $v['price'] : 0),
				'o_debit' => ($v['price'] < 0 ? abs($v['price']) :0),
				'o_balance' => $v['balance'],
				'date' => date('d-m-Y h:i A',strtotime($v['date_added']))
			); 
		}

		$post_bank = $this->model_account_e_wallet->getbank();
		$post_bank = isset($post_bank['data']) ? json_decode($post_bank['data'], 1) : array();

		$bank_data = $this->config->get($module_key.'e_wallet_feild_data');
		$data['language_id'] = $this->config->get('config_language_id');
		$data['bank_data'] = array();
		if(isset($bank_data) && !empty($bank_data)) {
			foreach ($bank_data as $key => $feild){
				$data['bank_data'][] = array(
					'display_name' => $feild['name'][$data['language_id']],
					'sort_order'   => $feild['sort_order'],
					'key'          => $feild['key'],
					'type'         => $feild['type'],
					'status'       => isset($feild['status']) ? $feild['status'] : 0,
					'value'        => isset($post_bank[$feild['key']]) ? $post_bank[$feild['key']] : ''
				);
			}
			$sort_order = array_column($data['bank_data'], 'sort_order');
			array_multisort($sort_order, SORT_ASC, $data['bank_data']);
		}

		$pagination = new Pagination();
		$pagination->total = $totaltrasaction;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('account/e_wallet','&page={page}'.$url);
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($totaltrasaction) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($totaltrasaction - $limit)) ? $totaltrasaction : ((($page - 1) * $limit) + $limit), $totaltrasaction, ceil($totaltrasaction / $limit));
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if($thisvar < 2200){
			$file = '/template/e_wallet/e_wallet.tpl';
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template').$file)) {
				$this->response->setOutput($this->load->view($this->config->get('config_template').$file, $data));
			} else {
				$this->response->setOutput($this->load->view('default'.$file, $data));
			}
		}else{
			$this->response->setOutput($this->load->view('e_wallet/e_wallet', $data));
		}
		
	}
	protected function lg($keys = ''){
		$module_key = '';
		if($this->octlversion() >= 3000) $module_key = 'module_';

		$language_id = $this->config->get('config_language_id');
		$ls = $this->config->get($module_key.'e_wallet_language');

		if(!is_array($keys)) $keys = array($keys);
		$is_acco = array_keys($keys) !== range(0, count($keys) - 1);

		$return = array();
		foreach ($keys as $key => $value){
			if($is_acco) $new_value = $value;
			else $new_value = $key = $value;

		  	if(isset($ls[$language_id]) && !empty($ls[$language_id][$key])){
		  		$new_value = $ls[$language_id][$key];
		  	}
		  	$return[] = $new_value;
		}
		return $return;
	}

	public function send_money(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}
		$other_theme = (int)defined("JOURNAL_VERSION");
		if($other_theme == 1){
			$data['journal_style'] = $this->document->addStyle('catalog/view/javascript/bootstrap/css/bootstrap.minupdate.css');
		}
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/send_money', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		if(!$this->config->get($module_key.'e_wallet_send_money')){
			$this->response->redirect($this->url->link('error/not_found', '', 'SSL'));
		}
	
		$this->document->addStyle('catalog/view/javascript/e_wallet.css');	
		$data = $this->load->language('account/e_wallet');
		$entry_invalid_amount= $this->language->get('entry_invalid_amount');
		$entry_enter_below =  $this->language->get('entry_enter_below');
		$entry_enter_above = $this->language->get('entry_enter_above');
		$entry_amount = $this->language->get('entry_amount');
		$entry_email_error = $this->language->get('entry_email_error');
		$entry_invalid_amount_error = $this->language->get('entry_invalid_amount_error');
		$entry_insufficent_balance = $this->language->get('entry_insufficent_balance');
		$entry_mobile_error = $this->language->get('entry_mobile_error');

		$this->load->model('account/e_wallet');
		$balance = $this->model_account_e_wallet->getBalance();

		if(isset($this->session->data['currency'])){
			$config_currency =$this->session->data['currency'];				
		}else{
			$config_currency =$this->config->get('config_currency');				
		}
		$data['symbol_left'] = $this->currency->getSymbolLeft($config_currency);
		$data['symbol_right'] = $this->currency->getSymbolRight($config_currency);

		$data['balance'] = $this->currency->format($balance,$config_currency);
		$data['error_warning'] = '';
		if($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['email'])){
			$this->load->model('account/customer');
			if(!isset($this->request->post['amount']) || (int)$this->request->post['amount'] <= 0){
				$data['error_warning'] = $entry_invalid_amount_error;
			}
			if(!$data['error_warning']){
				$s_currency = $this->session->data['currency'];
				$c_currency = $this->config->get('config_currency');
				$amount = $this->request->post['amount'];
				if($s_currency != $c_currency){
					$amount = $this->currency->convert($this->request->post['amount'], $s_currency, $c_currency);
				}
				$amountmax = $this->currency->format((float)$this->config->get($module_key.'e_wallet_max_send'),$s_currency);
				$amountmin = $this->currency->format((float)$this->config->get($module_key.'e_wallet_min_send'),$s_currency);

				if((int)$amount > $this->config->get($module_key.'e_wallet_max_send')){
					$data['error_warning'] = $entry_enter_below.$amountmax." ".$entry_amount;
				}else if((int)$amount < $this->config->get($module_key.'e_wallet_min_send')){
					$data['error_warning'] = $entry_enter_above.$amountmin." ".$entry_amount;
				}else if((float)$balance < (float)$amount){
					$data['error_warning'] = $entry_insufficent_balance;
				}else{
					$email = $this->request->post['email'];
					$per = DB_PREFIX;
					$c_info = $this->db->query("SELECT * FROM  `{$per}customer` WHERE  (`email` LIKE '{$email}' or `telephone` = '{$email}')
						AND (`email` NOT LIKE '{$this->customer->getEmail()}' AND `telephone` != '{$this->customer->getTelephone()}')");
					if($c_info->num_rows > 1){
						$data['error_warning'] = $entry_mobile_error;
					}elseif($c_info->num_rows < 1){
						$data['error_warning'] = $entry_email_error;
					}
					$c_info = $c_info->row;
				}
			}
			if(!$data['error_warning']){
				$d = array(
					'customer_id' => $c_info['customer_id'],
					'name' => $c_info['firstname'].' '.$c_info['lastname'],
					'amount' => $amount,
					'email' => $c_info['email'],
				);
				$this->model_account_e_wallet->sendmoney($d);
				$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
				die;
			}
		}
		$data['send_money'] = $this->url->link('account/e_wallet/send_money');
		$add_money_title = $this->config->get($module_key.'e_wallet_language');
		list($data['send_money_text'],$data['wallet_title']) = $this->lg(array('send_money_text','title'));
		$heading_title = sprintf($data['send_money_text']);
		$this->document->setTitle($heading_title);
		$data['heading_title'] = $heading_title;
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'] = array(array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		),array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		),array(
			'text' => $data['wallet_title'],
			'href' => $this->url->link('account/e_wallet', '', 'SSL')
		),array(
			'text' => $data['send_money_text'],
			'href' => $this->url->link('account/e_wallet/send_money', '', 'SSL')
		));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if($thisvar < 2200){
			$file = '/template/e_wallet/send_money.tpl';
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template').$file)) {
				$this->response->setOutput($this->load->view($this->config->get('config_template').$file, $data));
			} else {
				$this->response->setOutput($this->load->view('default'.$file, $data));
			}
		}else{
			$this->response->setOutput($this->load->view('e_wallet/send_money', $data));
		}
	}
	public function withdrawreq(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}
		$other_theme = (int)defined("JOURNAL_VERSION");
		if($other_theme == 1){
			$data['journal_style'] = $this->document->addStyle('catalog/view/javascript/bootstrap/css/bootstrap.minupdate.css');
		}
		$this->load->language('account/e_wallet');
		$entry_bank_error = $this->language->get('entry_bank_error');
		$entry_invalid_amount = $this->language->get('entry_invalid_amount');
		$entry_enter_below = $this->language->get('entry_enter_below');
		$entry_enter_above = $this->language->get('entry_enter_above');
		$entry_insufficent_balance = $this->language->get('entry_insufficent_balance');
		$entry_amount = $this->language->get('entry_amount');
		$confirm_withdraw_msg = $this->language->get('confirm_withdraw_msg');

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/withdrawreq', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->document->addStyle('catalog/view/javascript/e_wallet.css');	
		$thisvar = $this->octlversion();
		if(!$this->config->get($module_key.'e_wallet_withdraw_requests')){
			$this->response->redirect($this->url->link('error/not_found', '', 'SSL'));
		}
		$this->load->model('account/e_wallet');
		$post_bank = $this->model_account_e_wallet->getbank();
		$post_bank = isset($post_bank['data']) ? json_decode($post_bank['data'], 1) : array();
		$bank_data = $this->config->get($module_key.'e_wallet_feild_data');
		$data = $this->load->language('account/e_wallet');
		$this->load->model('account/e_wallet');
		$balance = $this->model_account_e_wallet->getBalance();
		if(isset($this->session->data['currency'])){
			$config_currency = $this->session->data['currency'];				
		}else{
			$config_currency = $this->config->get('config_currency');				
		}
		$data['symbol_left'] = $this->currency->getSymbolLeft($config_currency);
		$data['symbol_right'] = $this->currency->getSymbolRight($config_currency);
		$data['balance'] = $this->currency->format($balance,$config_currency);
		$data['error_warning'] = '';
		if($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['amount'])){
			$this->load->model('account/customer');
			$str = $this->db->query("SELECT data from oc_e_wallet_bank");
			if($str->num_rows)
			$test = $str->row;
			foreach($test as $key => $value){
				$data['bank_detail'] = json_decode($value,true);
			}
			$bank_setting = array();
			foreach($bank_data as $bank){
				$bank_setting[$bank['key']] = isset($bank['status']) ? $bank['status'] : 0;
			}
			if(isset($data['bank_detail'])){
				foreach($data['bank_detail'] as $key1 => $value1) {
					if($bank_setting[$key1] == 1 && empty($value1)){
						$data['error_warning'] = $entry_bank_error;
					}
				}
			}
			if(!$data['error_warning']){
				$per = DB_PREFIX;
				$withdraw_req = $this->db->query("SELECT * FROM  `{$per}withdraw_request` WHERE `status` = '0' AND `customer_id` = '{$this->customer->getId()}'");
				if($withdraw_req->num_rows){
					$data['error_warning'] = $error_pending_req;
				}
				if(!isset($this->request->post['amount']) || (int)$this->request->post['amount'] <= 0){
					$data['error_warning'] = $entry_invalid_amount;
				}
			}

			if(!$data['error_warning']){
				$s_currency = $this->session->data['currency'];
				$c_currency = $this->config->get('config_currency');
				$amount = $this->currency->convert($this->request->post['amount'], $s_currency, $c_currency);
				$amountmax = $this->currency->format((float)$this->config->get($module_key.'e_wallet_max_send'),$s_currency);
				$amountmin = $this->currency->format((float)$this->config->get($module_key.'e_wallet_min_send'),$s_currency);
				if((int)$amount > $this->config->get($module_key.'e_wallet_max_withdraw')){
					$data['error_warning'] = $entry_enter_below.$amountmax." ".$entry_amount;
				}else if((int)$amount < $this->config->get($module_key.'e_wallet_min_withdraw')){
					$data['error_warning'] = $entry_enter_above.$amountmin." ".$entry_amount;
				}else if((float)$balance < (float)$amount){
					$data['error_warning'] = $entry_insufficent_balance;
				}
			}

			if(!$data['error_warning']){
				$d =array(					
					'amount' => $amount,
				);
				$this->model_account_e_wallet->withdrawmoney($d);
				$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
				die;
			}
		}
		$data['withdrawreq'] = $this->url->link('account/e_wallet/withdrawreq');
		$add_money_title = $this->config->get($module_key.'e_wallet_language');
		list($data['withdraw_money_text'],$data['wallet_title']) = $this->lg(array('withdraw_money_text','title'));
		$heading_title = sprintf($data['withdraw_money_text']);
		$this->document->setTitle($heading_title);
		$data['heading_title'] = $heading_title;
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'] = array(array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		),array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		),array(
			'text' => $data['wallet_title'],
			'href' => $this->url->link('account/e_wallet', '', 'SSL')
		),array(
			'text' => $data['withdraw_money_text'],
			'href' => $this->url->link('account/e_wallet/withdrawreq', '', 'SSL')
		));
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if($thisvar < 2200){
			$file = '/template/e_wallet/withdrawreq.tpl';
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template').$file)) {
				$this->response->setOutput($this->load->view($this->config->get('config_template').$file, $data));
			} else {
				$this->response->setOutput($this->load->view('default'.$file, $data));
			}
		}else{
			$this->response->setOutput($this->load->view('e_wallet/withdrawreq', $data));
		}
	}
	
	public function add_money(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}

		list($add_confirm_msg,$add_money_text,$wallet_title) = $this->lg(array('add_confirm_string_text','add_money_text','title')); 
		$find = array('{AT}', '{WT}');
		$replace = array(
			'{AT}' => $add_money_text,
			'{WT}' => $wallet_title,
		);
		$other_theme = (int)defined("JOURNAL_VERSION");
		$add_money_confirm_msg = str_replace($find, $replace,$add_confirm_msg);
		$this->language->load('account/e_wallet');
		$default_address_error = $this->language->get('default_address_error');
		$entry_amount_error = $this->language->get('entry_amount_error');
		$entry_enter_below = $this->language->get('entry_enter_below');
		$entry_enter_above = $this->language->get('entry_enter_above');
		$entry_amount = $this->language->get('entry_amount');
		$confirm_send_msg = $this->language->get('confirm_send_msg');

		$entry_add_money_msg = $add_money_confirm_msg;
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/add_money', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		if (!$this->config->get($module_key.'e_wallet_add_money')) {
			$this->response->redirect($this->url->link('error/not_found', '', 'SSL'));
		}
		if(!$this->customer->getAddressId()){
			$this->session->data['error'] = $default_address_error;
			$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
		}
		$this->load->model('account/address');
		if(!isset($this->request->post['amount']) || (int)$this->request->post['amount'] == 0){
			$this->session->data['error'] = $entry_amount_error;
			$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
		}
		$s_currency = $this->session->data['currency'];
		if(isset($this->session->data['currency'])){
			$config_currency = $this->session->data['currency'];				
		}else{
			$config_currency = $this->config->get('config_currency');				
		}
		
		$amount = $this->currency->convert($this->request->post['amount'], $s_currency, $config_currency);
		$amountmax = $this->currency->format((float)$this->config->get($module_key.'e_wallet_max_add'),$s_currency);
		$amountmin = $this->currency->format((float)$this->config->get($module_key.'e_wallet_min_add'),$s_currency);
		if((int)$amount > $this->config->get($module_key.'e_wallet_max_add')){
			$this->session->data['error'] = $entry_enter_below .$amountmax." ".$entry_amount;
			$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
		}else if((int)$amount < $this->config->get($module_key.'e_wallet_min_add')){
			$this->session->data['error'] = $entry_enter_above.$amountmin." ".$entry_amount;
			$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
		}
		$this->load->model('tool/image');
		$this->cart->clear();
		$this->session->data['vouchers'] = array();
		$vouchers_key = 'e_wallet_vouchers';
		$this->session->data['vouchers_key'] = 'e_wallet_vouchers';
		$vimage = 'no_image.png';
		if($this->config->get($module_key.'e_wallet_image')){
			$vimage = $this->config->get($module_key.'e_wallet_image');
		}
		$vimage = $this->model_tool_image->resize($vimage, $this->config->get('theme_'.$this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_'.$this->config->get('config_theme') . '_image_cart_height'));
		$this->session->data['vouchers'][$vouchers_key] = array(
			'description'      => $entry_add_money_msg,
			'to_name'          => $vouchers_key,
			'to_email'         => $this->customer->getEmail(),
			'from_name'        => $this->customer->getFirstName(),
			'from_email'       => $this->customer->getEmail(),
			'voucher_theme_id' => -1,
			'message'          => $entry_add_money_msg,
			'image'            => $vimage,
			'amount'           => $amount,
		);
		$this->response->redirect($this->url->link('checkout/e_checkout', '', 'SSL'));
	}
	
	protected function octlversion(){
    	$varray = explode('.', VERSION);
    	return (int)implode('', $varray);
	}
	public function add_bank(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}
		$json = array();
		$this->load->language('account/e_wallet');
		$this->load->model('account/e_wallet');
		$add_money_title = $this->config->get($module_key.'e_wallet_language');
		list($bank_success_msg,$wallet_title) = $this->lg(array('bank_success_msg','title'));
		$find = array('{WT}');
		$replace = array(
			'{WT}' => $wallet_title,
		);
		$text_success_msg = str_replace($find,$replace,$bank_success_msg);
		if(!$this->config->get($module_key.'e_wallet_bank_detail')){
			$json['redirect'] = $this->url->link('error/not_found', '', 'SSL');
		}
		$bank_data = $this->config->get($module_key.'e_wallet_feild_data');
		$post_bank = isset($this->request->post['bank']) ? $this->request->post['bank'] : array();
		if(empty($json) && !empty($bank_data) && !empty($post_bank)){
			$data['data'] = array();
			$language_id = $this->config->get('config_language_id');
			foreach ($bank_data as $bank){
				if(isset($bank['status']) && empty($post_bank[$bank['key']])){
					$json['error'][$bank['key']] = $bank['name'][$language_id] . " is required";
				}else if(isset($post_bank[$bank['key']])){
					$data['data'][$bank['key']] = $post_bank[$bank['key']];
				} 
			}

			if(empty($json['error'])){
				$this->load->model('account/e_wallet');
				$this->model_account_e_wallet->setbank($data);
				$json['success'] = $text_success_msg;
			}
		}
		header("Content-Type: application/json; charset=UTF-8");
		echo json_encode($json); die;
	}
	public function redeem_voucher(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}
		$per = DB_PREFIX;
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/redeem_voucher', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		if(!$this->config->get($module_key.'e_wallet_voucher_status')){
			$this->response->redirect($this->url->link('error/not_found', '', 'SSL'));
		}
		$customer_id = $this->customer->getId();
		$this->load->language('account/e_wallet');
		$other_theme = (int)defined("JOURNAL_VERSION");
		if($other_theme == 1){
			$data['journal_style'] = $this->document->addStyle('catalog/view/javascript/bootstrap/css/bootstrap.minupdate.css');
		}
		list($added_voucher_text,$voucher_title) = $this->lg(array('add_voucher_text','voucher_text'));
		$find = array('{VT}');
		$replace = array(
			'{VT}' => $voucher_title,
		);
		$added_voucher = str_replace($find,$replace,$added_voucher_text);
		$entry_invalid_voucher = $this->language->get('entry_invalid_voucher');
		$data['voucher_redeem_title'] = $this->language->get('voucher_redeem_title');
		$data['confirm_voucher_msg'] = $this->language->get('confirm_voucher_msg');
		$this->document->addStyle('catalog/view/javascript/e_wallet.css');	
		
		$data = $this->load->language('account/e_wallet');
		$this->load->model('account/e_wallet');
		$balance = $this->model_account_e_wallet->getBalance();
		if(isset($this->session->data['currency'])){
			$config_currency = $this->session->data['currency'];				
		}else{
			$config_currency = $this->config->get('config_currency');				
		}
		$data['balance'] = $this->currency->format($balance,$config_currency);
		$data['error_warning'] = '';
		
		if($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['vouchar_code'])){
			$str = "SELECT vouchar_id,used_by,user_limit,vouchar_amount FROM `{$per}e_wallet_vouchar_list` WHERE vouchar_code= '". $this->request->post['vouchar_code'] ."' AND `status`='1' ";
			$vouchar_found = $this->db->query($str);
			$used_by = '';
			$data['error_warning'] = '';
			if (isset($vouchar_found->row['vouchar_id']) && $vouchar_found->row['vouchar_id']) {
				$vouchar_id = $vouchar_found->row['vouchar_id'];
				$user_limit = $vouchar_found->row['user_limit'];
				$vouchar_amount = $vouchar_found->row['vouchar_amount'];
				$used_by_count = 0;
				$used_by_array = array();

				if (!empty($vouchar_found->row['used_by'])) {
					$used_by_array = explode("','", $vouchar_found->row['used_by']);
				}
				// TESTC
				if (!in_array($customer_id , $used_by_array)) {

					if (empty($vouchar_found->row['used_by'])) {
						$used_by_array[] = $customer_id;
					}

					if (count($used_by_array) <= $user_limit) {
						$used_by = implode("','",$used_by_array);

						$str = "UPDATE `{$per}e_wallet_vouchar_list` set used_by='". $used_by ."' WHERE vouchar_id= '". $vouchar_id ."' ";

						$this->db->query($str);

						$transaction_data = array(
							'customer_id' => $customer_id,
							'amount' => $vouchar_amount,
							'desc' => $added_voucher
						);

						$this->load->model('account/e_wallet');
						$this->model_account_e_wallet->addtransaction($transaction_data);
					}else{
						$data['error_warning'] = $entry_invalid_voucher;
					}

				}else{
					$data['error_warning'] = $entry_invalid_voucher;
				}
			}else{
				$data['error_warning'] = $entry_invalid_voucher;
			}
			 
			if(empty($data['error_warning'])){
				$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
				die;
			}
		}


		$data['redeem_voucher'] = $this->url->link('account/e_wallet/redeem_voucher');
		$add_money_title = $this->config->get($module_key.'e_wallet_language');
		list($data['voucher_text'],$data['wallet_title']) = $this->lg(array('voucher_text','title'));
		$heading_title = sprintf($data['voucher_text']);
		$this->document->setTitle($heading_title);
		$data['heading_title'] = $heading_title;

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'] = array(array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		),array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		),array(
			'text' => $data['wallet_title'],
			'href' => $this->url->link('account/e_wallet', '', 'SSL')
		),array(
			'text' => $data['voucher_text'],
			'href' => $this->url->link('account/e_wallet/redeem_voucher', '', 'SSL')
		));
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if($thisvar < 2200){
			$file = '/template/e_wallet/redeem_voucher.tpl';
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template').$file)) {
				$this->response->setOutput($this->load->view($this->config->get('config_template').$file, $data));
			} else {
				$this->response->setOutput($this->load->view('default'.$file, $data));
			}
		}else{
			$this->response->setOutput($this->load->view('e_wallet/redeem_voucher', $data));
		}
	}
}