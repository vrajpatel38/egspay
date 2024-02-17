<?php
class Controllerewalletewallet extends Controller {
	private $error = array();
	
	public function index(){
		ini_set('display_errors', '1');
    	ini_set('display_startup_errors', '1');
    	error_reporting(E_ALL);
		$thisvar = $this->octlversion();
		$this->transaction();
	}
	public function add(){
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
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		$entry_add_transaction = $this->language->get('entry_add_transaction');
		$your_transaction      = $this->language->get('your_transaction');
		$entry_from_admin      = $this->language->get('entry_from_admin');
		$entry_desciption      = $this->language->get('entry_desciption');
		$entry_amount          = $this->language->get('entry_amount');
		$entry_date_text       = $this->language->get('entry_date_text');
		$entry_desciption_text = $this->language->get('entry_desciption_text');
		$notify_note           = $this->language->get('notify_note');
		$entry_date_add_text  = $this->language->get('entry_date_add_text');

		$thisvar = $this->octlversion();
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validation()){
			$this->addtransaction($this->request->post);
			if(isset($this->request->post['notify_customer'])){
				$this->load->model("customer/customer");
				$cs_id = $this->model_customer_customer->getCustomer($this->request->post['customer_id']);
				list($notify_customer_sub,$e_title) = $this->lg(array('notify_email_sub','title'));
				$find = array('{EN}');
				$replace = array(
					'{EN}' => $e_title,
				);
				$notify_customer_subject = str_replace($find, $replace, $notify_customer_sub);
				list($notify_customer_msg,$e_title) = $this->lg(array('notify_email_msg','title'));
				$find = array('{ET}','{DC}','{AT}','{DT}');
				$replace = array(
					'{ET}' => $e_title,
					'{DC}' => $this->request->post['description'],
					'{AT}' => $this->currency->format($this->request->post['amount'],$this->config->get('config_currency')),
					'{DT}' => date('d-m-Y h:i:s A'),
				);
				$notify_data = str_replace($find, $replace,$notify_customer_msg);
				$admin_email = $this->config->get($module_key.'e_wallet_setting_mail');
				list($email_header_text,$email_footer_text) = $this->lg(array('email_header_text','email_footer_text'));
				$per = DB_PREFIX;
				if($cs_id){
					$mail = new Mail();
					$mail->protocol      = $this->config->get('config_mail_protocol');
					$mail->parameter     = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_port     = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout  = $this->config->get('config_mail_smtp_timeout');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->setFrom($admin_email);
					$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				    $subject = $notify_customer_subject;
				    $message = $email_header_text."\n\n".$notify_data."\n\n".$email_footer_text;
					$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
					$mail->setText($message);
					$mail->setTo($cs_id['email']);
					$mail->send();
				}
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['page'])) $url .= '&page=' . $this->request->get['page'];
			if($thisvar >= 3000) $token = 'user_token=' . $this->session->data['user_token'];
			else $token = 'token=' . $this->session->data['token'];
			$this->response->redirect($this->url->link('e_wallet/e_wallet', $token, 'SSL'));
		}
		$this->addtransaction_form();
	}
	public function autocomplete(){
		$json = array();

		$filter_name = $filter_email = $filter_affiliate = '';
		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])){
			if (isset($this->request->get['filter_name'])) $filter_name = $this->request->get['filter_name'];
			if (isset($this->request->get['filter_email'])) $filter_email = $this->request->get['filter_email'];
			if (isset($this->request->get['filter_affiliate'])) $filter_affiliate = $this->request->get['filter_affiliate'];

			$this->load->model('customer/customer');
			$filter_data = array(
				'filter_name'      => $filter_name,
				'filter_email'     => $filter_email,
				'filter_affiliate' => $filter_affiliate,
				'start'            => 0,
				'limit'            => 5
			);
			$results = $this->model_customer_customer->getCustomers($filter_data);
			foreach ($results as $result){
				$json[] = array(
					'customer_id'       => $result['customer_id'],
					'customer_group'    => $result['customer_group'],
					'customer_group_id' => $result['customer_group_id'],
					'email'             => $result['email'],
					'firstname'         => $result['firstname'],
					'lastname'          => $result['lastname'],
					'telephone'         => $result['telephone'],
					'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'custom_field'      => json_decode($result['custom_field'], true),
					'address'           => $this->model_customer_customer->getAddresses($result['customer_id'])
				);
			}
		}

		$sort_order = array();
		foreach ($json as $key => $value) $sort_order[$key] = $value['name'];
		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function delete(){
		$thisvar = $this->octlversion();
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()){
			foreach ($this->request->post['selected'] as $transaction_id){
				$this->deletetransaction($transaction_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}
			if($thisvar >= 3000){
				$token = 'user_token=' . $this->session->data['user_token'];
			}else{
				$token = 'token=' . $this->session->data['token'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet',$token, 'SSL'));
		}
		$this->transaction();
	}
	public function deleterequest(){
		$this->load->language('e_wallet/e_wallet');
		$thisvar = $this->octlversion();
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()){
			foreach ($this->request->post['selected'] as $request_id){
				$this->deleterequests($request_id);
			}
			$this->session->data['success'] = $this->language->get('text_success_request');
			$url = '';
			if (isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}
			if($thisvar >= 3000){
				$token = 'user_token=' . $this->session->data['user_token'];
			}else{
				$token = 'token=' . $this->session->data['token'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet/add_request',$token, 'SSL'));
		}
		$this->add_request();
	}
	public function addtransaction_form(){
		$thisvar = $this->octlversion();
		$this->load->language('e_wallet/e_wallet');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_form'] = !isset($this->request->get['transaction_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['button_save']           = $this->language->get('button_save');
		$data['button_cancel']         = $this->language->get('button_cancel');
		$data['button_remove']         = $this->language->get('button_remove');
		$data['entry_customer_id']     = $this->language->get('entry_customer_id');
		$data['entry_amount']          = $this->language->get('entry_amount');
		$data['entry_description']     = $this->language->get('entry_description');
		$data['n_customer']            = $this->language->get('n_customer');
		$data['entry_refund_order_id'] = $this->language->get('entry_refund_order_id');
		$data['notify_note']           = $this->language->get('notify_note');

		if (isset($this->error['warning'])){
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
			$data['error'] = "";

		if (isset($this->error['description'])){
			$data['error'] = $this->error['description'];
		}  if (isset($this->error['amount'])){
			$data['error'] = $this->error['amount'];
		} if (isset($this->error['name'])){
			$data['error'] = $this->error['name'];
		}

		$url = '';
		if (isset($this->request->get['page'])){
			$url .= '&page=' . $this->request->get['page'];
		}
		if($thisvar >= 3000){
			$token = 'user_token=' . $this->session->data['user_token'];
			$data['user_token']  = $this->session->data['user_token'];
		}else{
			$token = 'token=' . $this->session->data['token'];
			$data['token'] = $this->session->data['token'];
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard',$token, 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('e_wallet/e_wallet',$token, 'SSL')
		);

		$data['action'] = $this->url->link('e_wallet/e_wallet/add', $token, 'SSL');
		$data['cancel'] = $this->url->link('e_wallet/e_wallet', $token, 'SSL');

		$json=array();
		if(isset($this->request->get['order_id'])){
			$this->load->model("sale/order");
			$order_info=$this->model_sale_order->getOrder($this->request->get['order_id']);
			$e_total = $this->db->query("SELECT order_id,value FROM  `".DB_PREFIX."order_total` WHERE  `order_id` = '{$this->request->get['order_id']}' AND code = 'e_wallet_total'");
			$amount=0;
			if($order_info['payment_code'] == 'e_wallet_payment'){
				$amount=abs($order_info['total']);
			}elseif($e_total->num_rows){
				$amount=abs($e_total->row['value']);
			}
		 	if($amount){
		 		$json = array(
		 			"amount" => $amount,
		 			"msg" => "Refund Amount For Order, Order Id is: #".$this->request->get['order_id'],
		 			"customer_id" => $order_info['customer_id'],
		 			"name" => $order_info['firstname'].' '.$order_info['lastname'].' - '.$order_info['email'],
		 		);
		 	}
		}

		if (isset($this->request->post['description'])){
			$data['description'] = $this->request->post['description'];
		} elseif ($json){
			$data['description'] = $json['msg'];
		} else {
			$data['description'] = '';
		}

		if (isset($this->request->post['customer_id'])){
			$data['customer_id'] = $this->request->post['customer_id'];
		} elseif ($json){
			$data['customer_id'] = $json['customer_id'];
		} else {
			$data['customer_id'] = '';
		}

		if (isset($this->request->post['customer'])){
			$data['customer'] = $this->request->post['customer'];
		} elseif ($json){
			$data['customer'] = $json['name'];
		} else {
			$data['customer'] = '';
		}

		if (isset($this->request->post['amount'])){
			$data['amount'] = $this->request->post['amount'];
		}elseif ($json){
			$data['amount'] = $json['amount'];
		}  else {
			$data['amount'] = '';
		}

		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE status = 1");
		$customer = array();
		foreach ($query->rows as $value){
			$customer[] = array(
				'customer_id' => $value['customer_id'],
				'name' => $value['firstname'].' '.$value['lastname'],
				'email' => $value['email'],
			);
		}
		$data['customers']=json_encode($customer);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if ($thisvar >= 3000){
			$this->response->setOutput($this->load->view('e_wallet/e_wallet_form', $data));
		}else{
			$this->response->setOutput($this->load->view('e_wallet/e_wallet_form.tpl', $data));
		}

	}
	public function customers(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000){
			$token = 'user_token=' . $this->session->data['user_token'];
		}else{
			$token = 'token=' . $this->session->data['token'];
		}
		$this->load->language('e_wallet/e_wallet');
		$data['customer_email']         = $this->language->get('customer_email');
		$data['entry_filter']           = $this->language->get('entry_filter');
		$data['entry_percentage']       = $this->language->get('entry_percentage');
		$data['entry_customer_balance'] = $this->language->get('entry_customer_balance');
		$data['entry_bank_name']        = $this->language->get('entry_bank_name');
		$data['entry_swift_code']       = $this->language->get('entry_swift_code');
		$data['entry_ifsc_code']        = $this->language->get('entry_ifsc_code');
		$data['entry_account_name']     = $this->language->get('entry_account_name');
		$data['entry_account_number']   = $this->language->get('entry_account_number');
		$data['entry_bank_detail']      = $this->language->get('entry_bank_detail');
		$data['entry_close']            = $this->language->get('entry_close');

		$data = array_merge($this->load->language('e_wallet/e_wallet'),array());
		$page = 1;
		$limit = $this->config->get('config_limit_admin');
		$url = $data['email'] = '';
		$where2 = $where = ' WHERE 1 ';
		$data['datefrom'] = '';
		$data['dateto'] = '';
		$per = DB_PREFIX;
		if(isset($this->request->get['page'])) $page = (int)$this->request->get['page'];
		if(isset($this->request->get['limit'])) $limit = (int)$this->request->get['limit'];
		$filter = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		if(isset($this->request->request['email']) && $this->request->request['email']){
			$url ='&email='.$this->request->request['email'];
			$data['email'] = $this->request->request['email'];
			$where .= " AND (customer_id IN (SELECT customer_id FROM  `{$per}customer` WHERE `email` like '%{$data['email']}%' or `telephone` like '%{$data['email']}%' OR firstname like '%{$data['email']}%' OR lastname like '%{$data['email']}%') OR customer_id like '%{$data['email']}%') ";
			$where2 .= " AND (c.email like '%{$data['email']}%' or c.telephone like '%{$data['email']}%' OR c.customer_id like '%{$data['email']}%' OR c.firstname like '%{$data['email']}%' OR c.lastname like '%{$data['email']}%') ";
		}
		$str = "SELECT SUM(price) AS balance FROM  `{$per}e_wallet_transaction`";
		$data['totalbalance'] = $this->db->query($str)->row['balance'];
		$data['totallabance_format'] = $this->currency->format($data['totalbalance'],$this->config->get('config_currency'));
		if( !$data['totalbalance']){
			$data['totalbalance']=1;
		}
		$str = "SELECT COUNT(*) AS total FROM (SELECT customer_id FROM {$per}e_wallet_bank
		UNION
		SELECT customer_id FROM {$per}e_wallet_transaction ) T
		{$where} ";
		$totalcustomers = $this->db->query($str)->row['total'];
		$str = "SELECT c.firstname,c.lastname,c.email,b.*,c.customer_id,
			(SELECT SUM(t.price) FROM {$per}e_wallet_transaction t WHERE c.customer_id = t.customer_id) AS balance
			FROM {$per}customer c
			LEFT JOIN {$per}e_wallet_bank b ON (b.customer_id = c.customer_id)
			{$where2}
			ORDER BY balance DESC
			LIMIT ".$filter['start'] .' , '.$filter['limit'];
		// echo "<pre>"; print_r($str); echo "</pre>";die();
		$results = $this->db->query($str)->rows;
		$data['customers'] = array();
		$data['bank_data'] = array();
		$this->load->model('customer/customer');
		foreach ($results as $result){
			$customer_info = $this->model_customer_customer->getCustomer($result['customer_id']);
			$data['customers'][] = array(
				'customer_id'  => $result['customer_id'],
				'customer' => $customer_info['firstname']." ".$customer_info['lastname'],
				'email' => $customer_info['email'],
				'data' => json_decode($result['data'],true),
				'balance' => $this->currency->format((float)$result['balance'],$this->config->get('config_currency')),
				'per' => round(((float)$result['balance'] * 100) / $data['totalbalance'],2),
				'c_link' => $this->url->link('customer/customer/edit', $token . '&customer_id=' . $result['customer_id'], 'SSL')
			);
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $token, 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('e_wallet/e_wallet/customers', $token, 'SSL')
		);
		$data['formurl'] = $this->url->link('e_wallet/e_wallet/customers', $token, 'SSL');


		if (isset($this->error['warning'])){
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])){
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->request->post['selected'])){
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$data['sort_name'] = $this->url->link('e_wallet/e_wallet/customers', $token, 'SSL');
		$data['sort_sort_order'] = $this->url->link('e_wallet/e_wallet/customers', $token, 'SSL');


		$pagination = new Pagination();
		$pagination->total = $totalcustomers;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('e_wallet/e_wallet/customers', $token . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($totalcustomers) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($totalcustomers - $this->config->get('config_limit_admin'))) ? $totalcustomers : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $totalcustomers, ceil($totalcustomers / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		if ($thisvar >= 3000){
			$this->response->setOutput($this->load->view('e_wallet/customers_list', $data));
		}else{
			$this->response->setOutput($this->load->view('e_wallet/customers_list.tpl', $data));
		}
	}
	public function transaction(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000){
			$token = 'user_token=' . $this->session->data['user_token'];
		}else{
			$token = 'token=' . $this->session->data['token'];
		}
		$this->load->language('e_wallet/e_wallet');
		$data['entry_from_date']   = $this->language->get('entry_from_date');
		$data['entry_to']          = $this->language->get('entry_to');
		$data['entry_email_phone'] = $this->language->get('entry_email_phone');
		$data['entry_filter']      = $this->language->get('entry_filter');
		$data['entry_total']       = $this->language->get('entry_total');
		$data['column_balance']    = $this->language->get('column_balance');
		$page = 1;
		$limit = $this->config->get('config_limit_admin');
		$url = $data['email'] = '';
		$where = ' WHERE 1 ';
		$data['datefrom'] = $data['dateto'] = '';
		$per = DB_PREFIX;
		if(isset($this->request->get['page'])) $page = (int)$this->request->get['page'];
		if(isset($this->request->get['limit'])) $limit = (int)$this->request->get['limit'];
		$filter = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		if(isset($this->request->request['datefrom']) && $this->request->request['datefrom']){
			$url ='&datefrom='.$this->request->request['datefrom'];
			$datefrom = date('Y-m-d',strtotime($this->request->request['datefrom']));
			$where .= " AND date_added >= '".date('Y-m-d',strtotime($datefrom))."' ";
			$data['datefrom'] = $this->request->request['datefrom'];
		}
		if(isset($this->request->request['dateto']) && $this->request->request['dateto']){
			$url ='&dateto='.$this->request->request['dateto'];
			$dateto = date('Y-m-d',strtotime($this->request->request['dateto']));
			$where .= " AND date_added <= '".date('Y-m-d',strtotime($dateto.' +1 days'))."' ";
			$data['dateto'] = $this->request->request['dateto'];
		}

		if(isset($this->request->request['email']) && $this->request->request['email']){
			$url ='&email='.$this->request->request['email'];
			$data['email'] = $this->request->request['email'];
			$where .= " AND customer_id IN (SELECT customer_id FROM  `{$per}customer` WHERE `email` like '%{$data['email']}%' or `telephone` like '%{$data['email']}%') ";
		}
		$str = "SELECT COUNT(*) AS total FROM `{$per}e_wallet_transaction` {$where}";
		$totaltransaction = $this->db->query($str)->row['total'];
		$str = "SELECT SUM(price) AS balance FROM  `{$per}e_wallet_transaction`";
		$data['t_balance'] = $this->db->query($str)->row['balance'];
		$data['t_balance_format'] = $this->currency->format($data['t_balance'],$this->config->get('config_currency'));
		$str = "SELECT * FROM `{$per}e_wallet_transaction` {$where} ORDER BY date_added DESC LIMIT ".$filter['start'].",".$filter['limit']." ";
		$results = $this->db->query($str)->rows;
		$data['transactions'] = array();
		$this->load->model('customer/customer');
		foreach ($results as $result){
			$customer_info = $this->model_customer_customer->getCustomer($result['customer_id']);
			$data['transactions'][] = array(
				'transaction_id' => $result['transaction_id'],
				'customer_id'    => $result['customer_id'],
				'description'    => $result['description'],
				'customer_name'  => @$customer_info['firstname'].' '.@$customer_info['lastname'],
				'customer'       => @$customer_info['email'].' - '.@$customer_info['telephone'],
				'price'          => $this->currency->format($result['price'],$this->config->get('config_currency')),
				'balance'        => $this->currency->format($result['balance'],$this->config->get('config_currency')),
				'o_price'        => $result['price'],
				'date'           => date('d-m-Y h:i:s A',strtotime($result['date_added'])),
				'c_link'         => $this->url->link('customer/customer/edit', $token . '&customer_id=' . $result['customer_id'], 'SSL')
			);
		}

		if (isset($this->request->get['order'])){
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['sort'])){
			$sort = $this->request->get['sort'];
		} else {
			$sort = '';
		}

		if ($order == 'ASC'){
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if ($sort == 'customer_name'){

			if ($order == 'DESC'){
				$customer_name = array_column($data['transactions'], 'customer_name');
				array_multisort($customer_name, SORT_DESC, $data['transactions']);
			}else{
				$customer_name = array_column($data['transactions'], 'customer_name');
				array_multisort($customer_name, SORT_ASC, $data['transactions']);
			}
		}elseif ($sort == 'customer_email'){

			if ($order == 'DESC'){
				$customer = array_column($data['transactions'], 'customer');
				array_multisort($customer, SORT_DESC, $data['transactions']);
			}else{
				$customer = array_column($data['transactions'], 'customer');
				array_multisort($customer, SORT_ASC, $data['transactions']);
			}
		}


		$data['sort_customer_name'] = $this->url->link('e_wallet/e_wallet/transaction', $token . '&sort=customer_name' . $url, 'SSL');
		$data['sort_customer_email'] = $this->url->link('e_wallet/e_wallet/transaction', $token . '&sort=customer_email' . $url, 'SSL');

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $token, 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('e_wallet/e_wallet', $token, 'SSL')
		);
		$data['add'] = $this->url->link('e_wallet/e_wallet/add', $token, 'SSL');
		$data['delete'] = $this->url->link('e_wallet/e_wallet/delete', $token, 'SSL');
		$data['formurl'] = $this->url->link('e_wallet/e_wallet/transaction', $token, 'SSL');


		$data['heading_title']        = $this->language->get('heading_title');
		$data['text_list']            = $this->language->get('text_list');
		$data['text_no_results']      = $this->language->get('text_no_results');
		$data['text_confirm']         = $this->language->get('text_confirm');
		$data['column_name']          = $this->language->get('column_name');
		$data['column_sort_order']    = $this->language->get('column_sort_order');
		$data['column_action']        = $this->language->get('column_action');
		$data['button_add']           = $this->language->get('button_add');
		$data['button_edit']          = $this->language->get('button_edit');
		$data['button_delete']        = $this->language->get('button_delete');
		$data['column_customer_name'] = $this->language->get('column_customer_name');
		$data['column_customer']      = $this->language->get('column_customer');
		$data['column_description']   = $this->language->get('column_description');
		$data['column_price']         = $this->language->get('column_price');
		$data['column_date']          = $this->language->get('column_date');
		if (isset($this->error['warning'])){
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])){
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->request->post['selected'])){
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$data['sort_name'] = $this->url->link('e_wallet/e_wallet', $token, 'SSL');
		$data['sort_sort_order'] = $this->url->link('e_wallet/e_wallet', $token, 'SSL');


		$pagination = new Pagination();
		$pagination->total = $totaltransaction;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('e_wallet/e_wallet', $token . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($totaltransaction) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($totaltransaction - $this->config->get('config_limit_admin'))) ? $totaltransaction : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $totaltransaction, ceil($totaltransaction / $this->config->get('config_limit_admin')));
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		if ($thisvar >= 3000){
			$this->response->setOutput($this->load->view('e_wallet/e_wallet_list', $data));
		}else{
			$this->response->setOutput($this->load->view('e_wallet/e_wallet_list.tpl', $data));
		}
	}
	public function approverequest(){
		$thisvar = $this->octlversion();
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()){
			foreach ($this->request->post['selected'] as $request_id){
				$this->approverequests($request_id);
			}
			$this->session->data['success'] = $this->language->get('text_success_request');
			$url = '';
			if (isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}
			if($thisvar >= 3000){
				$token = 'user_token=' . $this->session->data['user_token'];
			}else{
				$token = 'token=' . $this->session->data['token'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet/add_request', $token .$url ,'SSL'));
		}
		$this->add_request();
	}
	public function rejectrequest(){
		$thisvar = $this->octlversion();
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()){
			foreach ($this->request->post['selected'] as $request_id){
				$this->rejectrequests($request_id);
			}
			$this->session->data['success'] = $this->language->get('text_success_request');
			$url = '';
			if (isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}
			if($thisvar >= 3000){
				$token = 'user_token=' . $this->session->data['user_token'];
			}else{
				$token = 'token=' . $this->session->data['token'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet/add_request', $token.$url, 'SSL'));
		}
		$this->add_request();
	}
	public function add_request(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000){
			$token = 'user_token=' . $this->session->data['user_token'];
		}else{
			$token = 'token=' . $this->session->data['token'];
		}
		$page = 1;
		$data['status'] = '';
		$limit = $this->config->get('config_limit_admin');
		$url = $data['email'] = '';
		$where = ' WHERE 1 ';
		$data['datefrom'] = '';
		$data['dateto'] = '';
		$per = DB_PREFIX;
		if(isset($this->request->get['page'])) $page = (int)$this->request->get['page'];
		if(isset($this->request->get['limit'])) $limit = (int)$this->request->get['limit'];
		$filter = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		if(isset($this->request->request['datefrom']) && $this->request->request['datefrom']){
			$url ='&datefrom='.$this->request->request['datefrom'];
			$data['datefrom'] = $this->request->request['datefrom'];
			$where .= " AND date_added >= '".date('Y-m-d',strtotime(date('Y-m-d',strtotime($this->request->request['datefrom']))))."' ";
		}
		if(isset($this->request->request['dateto']) && $this->request->request['dateto']){
			$url ='&dateto='.$this->request->request['dateto'];
			$data['dateto'] = $this->request->request['dateto'];
			$where .= " AND date_added <= '".date('Y-m-d',strtotime(date('Y-m-d',strtotime($this->request->request['dateto'])).' +1 days'))."' ";
		}

		if(isset($this->request->request['status']) && trim($this->request->request['status']) != ''){
			$url ='&status='.$this->request->request['status'];
			$data['status'] = $this->request->request['status'];
			$where .= " AND status = '".(int)$data['status']."' ";
		}

		if(isset($this->request->request['email']) && $this->request->request['email']){
			$url ='&email='.$this->request->request['email'];
			$data['email'] = $this->request->request['email'];
			$where .= " AND customer_id IN (SELECT customer_id FROM  `{$per}customer` WHERE `email` like '%{$data['email']}%' or `telephone` like '%{$data['email']}%')";
		}
		$totaltransaction = $this->db->query("SELECT COUNT(*) AS total FROM {$per}cod_request {$where}");
		$totaltransaction = $totaltransaction->row['total'];
		$results = $this->db->query("SELECT * FROM {$per}cod_request {$where} ORDER BY date_added DESC LIMIT ".$filter['start'].",".$filter['limit']." ");
		$results = $results->rows;
		$data['requests'] = array();
		$this->load->model('customer/customer');
			foreach ($results as $result){
				$customer_info = $this->model_customer_customer->getCustomer($result['customer_id']);
				$data['requests'][] = array(
					'request_id'  => $result['request_id'],
					'customer_id' => $result['customer_id'],
					'description' => $result['description'],
					'status' => $result['status'],
					'customer' => $customer_info['email'].' - '.$customer_info['telephone'],
					'price' => $this->currency->format($result['amount'],$this->config->get('config_currency')),
					'date' => date('d-m-Y h:i:s A',strtotime($result['date_added'])),
					'c_link'       => $this->url->link('customer/customer/edit', $token . '&customer_id=' . $result['customer_id'], 'SSL')
				);
			}
			$this->load->language('e_wallet/e_wallet');
			$data['breadcrumbs'] = array(array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/dashboard', $token, 'SSL')
				), array(
					'text' => $this->language->get('request_heading_title'),
					'href' => $this->url->link('e_wallet/e_wallet/add_request', $token, 'SSL')
				)
			);
			$data['delete'] = $this->url->link('e_wallet/e_wallet/deleterequest', $token, 'SSL');
			$data['approveurl'] = $this->url->link('e_wallet/e_wallet/approverequest', $token, 'SSL');
			$data['rejecturl'] = $this->url->link('e_wallet/e_wallet/rejectrequest', $token, 'SSL');
			$data['formurl'] = $this->url->link('e_wallet/e_wallet/add_request', $token, 'SSL');
			$data['dis_edit'] = $this->url->link('e_wallet/e_wallet/dis_edit', $token, 'SSL');
			$data['heading_title']        = $this->language->get('request_heading_title');
			$data['text_list']            = $this->language->get('request_text_list');
			$data['text_no_results']      = $this->language->get('text_no_results');
			$data['text_confirm']         = $this->language->get('text_confirm');
			$data['column_name']          = $this->language->get('column_name');
			$data['column_sort_order']    = $this->language->get('column_sort_order');
			$data['column_action']        = $this->language->get('column_action');
			$data['button_add']           = $this->language->get('button_add');
			$data['button_edit']          = $this->language->get('button_edit');
			$data['button_delete']        = $this->language->get('button_delete');
			$data['column_customer']      = $this->language->get('column_customer');
			$data['column_customer_name'] = $this->language->get('column_customer_name');
			$data['column_description']   = $this->language->get('column_description');
			$data['column_price']         = $this->language->get('column_price');
			$data['column_date']          = $this->language->get('column_date');
			$data['entry_from_date']      = $this->language->get('entry_from_date');
			$data['entry_to']             = $this->language->get('entry_to');
			$data['entry_email_phone']    = $this->language->get('entry_email_phone');
			$data['entry_status']         = $this->language->get('entry_status');
			$data['entry_filter']         = $this->language->get('entry_filter');
			$data['status_pending']       = $this->language->get('status_pending');
			$data['status_approve']       = $this->language->get('status_approve');
			$data['status_reject']        = $this->language->get('status_reject');


		if (isset($this->error['warning'])){
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])){
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])){
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		// echo "<pre>"; print_r($data); echo "</pre>";die();

		$data['sort_name'] = $this->url->link('e_wallet/e_wallet/add_request', $token, 'SSL');
		$data['sort_sort_order'] = $this->url->link('e_wallet/e_wallet/add_request', $token, 'SSL');

		$pagination = new Pagination();
		$pagination->total = $totaltransaction;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('e_wallet/e_wallet/add_request', $token . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($totaltransaction) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($totaltransaction - $this->config->get('config_limit_admin'))) ? $totaltransaction : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $totaltransaction, ceil($totaltransaction / $this->config->get('config_limit_admin')));
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if ($thisvar >= 3000){
			$this->response->setOutput($this->load->view('e_wallet/cod_request_list', $data));
		}else{
			$this->response->setOutput($this->load->view('e_wallet/cod_request_list.tpl', $data));
		}
	}
	public function deletetransaction($id){
		$str = "DELETE FROM `" . DB_PREFIX . "e_wallet_transaction` WHERE transaction_id = '".(int)$id."'";
		return $this->db->query($str);
	}
	public function deleterequests($id){
		$str = "DELETE FROM `" . DB_PREFIX . "cod_request` WHERE request_id = '".(int)$id."'";
		return $this->db->query($str);
	}
	public function approverequests($id){
		$this->load->language('e_wallet/e_wallet');
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
		$entry_req_approve  = $this->load->language('entry_req_approve');
		$entry_your_request = $this->load->language('entry_your_request');
		$entry_desciption   = $this->load->language('entry_desciption');
		$entry_amount       = $this->load->language('entry_amount');
		$per = DB_PREFIX;
		$sql = "SELECT * FROM `{$per}cod_request` WHERE request_id = '".(int)$id."' AND status = '0'";
		$request = $this->db->query($sql)->row;
		if($request){
			$ststus = 'Approved';
			$adddata = array(
				'customer_id' => $request['customer_id'],
				'amount' => $request['amount'],
				'description' => 'Request Approved : '.$request['description'].'.'
			);
			list($req_approve_text_data) = $this->lg('req_aproove_text');
			$find = array('{DC}');
			$replace = array(
				'{DC}' => $request['description'],
			);
			$desc = str_replace($find,$replace,$req_approve_text_data);
			// $desc="Request Approved : ".$request['description'];

			$transaction_id = $this->addtransaction($adddata);
			$str = "UPDATE `" . DB_PREFIX . "cod_request` SET description='".$this->db->escape($desc)."', status = '1',transaction_id = '{$transaction_id}' WHERE request_id = '".(int)$id."'";
			$this->db->query($str);
			$c_info = $this->db->query("SELECT * FROM  `{$per}customer` WHERE  customer_id = {$request['customer_id']}")->row;
			//-----------------For Admin email message------------------------
			list($e_title,$add_money_title,$add_money_sub) = $this->lg(array('title','add_money_text','add_setting_sub'));
			$find = array('{EN}','{AT}','{ST}');
			$replace = array(
				'{EN}' => $e_title,
				'{AT}' => $add_money_title,
				'{ST}' => $ststus,
			);
			$add_money_sub_data = str_replace($find, $replace, $add_money_sub);
			list($add_setting_data,$add_money_title) = $this->lg(array('add_setting_msg','add_money_text'));
			$find = array('{AT}','{ST}','{DC}','{AD}','{DT}');
			$replace = array(
				'{AT}' => $add_money_title,
				'{ST}' => $ststus,
				'{DC}' => $request['description'],
				'{AD}' => $this->currency->format($request['amount'],$this->config->get('config_currency')),
				'{DT}' => date('d-m-Y h:i:s A'), 
			);
		    $add_setting_data_sub = str_replace($find, $replace, $add_setting_data);
		    //-----------------For USer email message------------------------
		    list($e_title,$add_money_title,$add_money_sub_user) = $this->lg(array('title','add_money_text','add_user_sub'));
			$find = array('{EN}','{AT}','{ST}');
			$replace = array(
				'{EN}' => $e_title,
				'{AT}' => $add_money_title,
				'{ST}' => $ststus,
			);
			$add_money_sub_data_user = str_replace($find, $replace, $add_money_sub_user);
			list($add_user_data) = $this->lg(array('add_user_msg'));
			$find = array('{ST}','{DC}','{AD}','{DT}');
			$replace = array(
				'{ST}' => $ststus,
				'{DC}' => $request['description'],
				'{AD}' => $this->currency->format($request['amount'],$this->config->get('config_currency')),
				'{DT}' => date('d-m-Y h:i:s A'), 
			);
		    $add_user_data_message = str_replace($find, $replace, $add_user_data);
		    $admin_email = $this->config->get($module_key.'e_wallet_setting_mail');
		    list($email_header_text,$email_footer_text) = $this->lg(array('email_header_text','email_footer_text'));
			if($c_info){
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setFrom($admin_email);
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$admin_subject = $add_money_sub_data;
				$admin_message = $add_setting_data_sub;
				$mail->setSubject(html_entity_decode($admin_subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($admin_message);
				$mail->setTo($admin_email);
				$mail->send();
				$user_subject = $add_money_sub_data_user;
				$user_message = $email_header_text."\n\n".$add_user_data_message."\n\n".$email_footer_text;
				$mail->setTo($c_info['email']);
				$mail->setSubject(html_entity_decode($user_subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($user_message);
				$mail->send();
			}
			return true;
		}
		return false;
	}
	public function rejectrequests($id){
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

		$this->load->language('e_wallet/e_wallet');
		$entry_your_request     = $this->language->get('entry_your_request');
		$entry_from_admin       = $this->language->get('entry_from_admin');
		$entry_amount           = $this->language->get('entry_amount');
		$entry_date_text        = $this->language->get('entry_date_text');
		$entry_reject_requested = $this->language->get('entry_reject_requested');
		$per = DB_PREFIX;
		$sql = "SELECT * FROM `{$per}cod_request` WHERE request_id = '".(int)$id."' AND status = '0'";
		$request = $this->db->query($sql)->row;
		list($e_title) = $this->lg('title');
		if($request){
			$adddata = array(
				'customer_id' => $request['customer_id'],
				'amount' => 0,
				'description' => 'Request Rejected : '.$request['description'].'.'
			);
			$transaction_id =$this->addtransaction($adddata);
			list($req_rejected_text_data) = $this->lg('req_reject_text');
			$find = array('{DC}');
			$replace = array(
				'{DC}' => $request['description'],
			);
			$desc = str_replace($find,$replace,$req_rejected_text_data);
			// $desc="Request Rejected : ".$request['description'];
			$str = "UPDATE `" . DB_PREFIX . "cod_request` SET description='".$this->db->escape($desc)."', status = '2',transaction_id = '{$transaction_id}' WHERE request_id = '".(int)$id."'";
			$this->db->query($str);
			$ststus = 'Rejected';
			$c_info = $this->db->query("SELECT * FROM  `{$per}customer` WHERE customer_id = {$request['customer_id']}")->row;
			$admin_email = $this->config->get($module_key.'e_wallet_setting_mail');
			//----------------------for Admin email-----------------------
			list($e_title,$add_money_title,$add_money_sub) = $this->lg(array('title','add_money_text','add_setting_sub'));
			$find = array('{EN}','{AT}','{ST}');
			$replace = array(
				'{EN}' => $e_title,
				'{AT}' => $add_money_title,
				'{ST}' => $ststus,
			);
			$add_money_sub_data = str_replace($find, $replace, $add_money_sub);
			list($add_setting_data,$add_money_title) = $this->lg(array('add_setting_msg','add_money_text'));
			$find = array('{AT}','{ST}','{DC}','{AD}','{DT}');
			$replace = array(
				'{AT}' => $add_money_title,
				'{ST}' => $ststus,
				'{DC}' => $request['description'],
				'{AD}' => $this->currency->format($request['amount'],$this->config->get('config_currency')),
				'{DT}' => date('d-m-Y h:i:s A'), 
			);
		    $add_setting_data_sub = str_replace($find, $replace, $add_setting_data);
		    //------------------------For User Email------------------------
		    list($e_title,$add_money_title,$add_money_sub_user) = $this->lg(array('title','add_money_text','add_user_sub'));
			$find = array('{EN}','{AT}','{ST}');
			$replace = array(
				'{EN}' => $e_title,
				'{AT}' => $add_money_title,
				'{ST}' => $ststus,
			);
			$add_money_sub_data_user = str_replace($find, $replace, $add_money_sub_user);
			list($add_user_data) = $this->lg('add_user_msg');
			$find = array('{ST}','{DC}','{AD}','{DT}');
			$replace = array(
				'{ST}' => $ststus,
				'{DC}' => $request['description'],
				'{AD}' => $this->currency->format($request['amount'],$this->config->get('config_currency')),
				'{DT}' => date('d-m-Y h:i:s A'), 
			);
		    $add_user_data_message = str_replace($find, $replace, $add_user_data);
		    list($email_header_text,$email_footer_text) = $this->lg(array('email_header_text','email_footer_text'));
			if($c_info){
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$admin_subject = $add_money_sub_data;
				$admin_message = $add_setting_data_sub;
				$mail->setFrom($admin_email);
				$mail->setSubject(html_entity_decode($admin_subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($admin_message);
				$mail->setTo($admin_email);
				$mail->send();
				$user_subject = $add_money_sub_data_user;
			    $user_message = $email_header_text."\n\n".$add_user_data_message."\n\n".$email_footer_text;
				$mail->setSubject(html_entity_decode($user_subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($user_message);
				$mail->setTo($c_info['email']);
				$mail->send();
			}
			return true;
		}
		return false;
	}
	public function dis_edit(){
		$str = "UPDATE `" . DB_PREFIX . "cod_request` SET description = '".$this->db->escape($this->request->post['description'])."' WHERE request_id = '".(int)$this->request->post['request_id']."'";
		$this->db->query($str);
		$str = "SELECT * FROM  `".DB_PREFIX."cod_request` WHERE request_id = '".(int)$this->request->post['request_id']."'";
		$request = $this->db->query($str)->row;
		$text = $this->db->escape($this->request->post['description']);
		$str = "UPDATE  `".DB_PREFIX."e_wallet_transaction` SET description =  '{$text}' WHERE transaction_id = '".$request['transaction_id']."'";

		$this->db->query($str);
	}
	public function addtransaction($data = array()){

		$customer_id = (int)$data['customer_id'];
		$str = "INSERT INTO `" . DB_PREFIX . "e_wallet_transaction` SET
			`customer_id` = '".(int)$customer_id."',
			`price` = '".(float)$data['amount']."',
			`description` = '".$this->db->escape($data['description'])."',
			`date_added` = NOW()";
		$this->db->query($str);
		$transaction_id = $this->db->getLastId();
		$balance = (float)$this->getBalance($data);
		$str = "UPDATE `".DB_PREFIX."e_wallet_transaction` SET
			balance = {$balance}
			WHERE customer_id = ".$customer_id." AND transaction_id = ".(int)$transaction_id;
		$this->db->query($str);
		return $transaction_id;
	}
	public function getBalance($data = array()){
		if(isset($data['customer_id'])) $customer_id = (int)$data['customer_id'];
		$str = "SELECT SUM(price) as total FROM `".DB_PREFIX."e_wallet_transaction` WHERE customer_id = ".$customer_id;
		$data = $this->db->query($str);
		return $data->row['total'];
	}
	public function checkrefund(){
		$json=array();
		if(isset($this->request->get['order_id'])){
			$this->load->model("sale/order");
			$order_info=$this->model_sale_order->getOrder($this->request->get['order_id']);
			if($this->config->get('e_wallet_status') && $this->request->get['o_his'] == $this->config->get('e_wallet_refund_order_id') && $order_info['payment_code'] != 'e_wallet_payment'){
				$order_total=$this->model_sale_order->getOrderTotals($this->request->get['order_id']);
				$temp=0;
				$amount=0;
				foreach ($order_total as $key => $value){
					if($value['code'] == "e_wallet_total"){
						$temp++;
						$amount=abs($value['value']);
					}
				}
			 	if($temp && $amount){
			 		$json['success']=array(
			 			"amount" => $amount,
			 			"msg" => "Refund Amount For Order, Order Id is: #".$this->request->get['order_id'],
			 			"customer_id" => $order_info['customer_id'],
			 			"firstname" => $order_info['firstname'],
			 			"lastname" => $order_info['lastname'],
			 			"email" => $order_info['email'],
			 			"link" => html_entity_decode($this->url->link('e_wallet/e_wallet/add', 'token=' . $this->session->data['token'].'&order_id='.$this->request->get['order_id'], 'SSL'), ENT_QUOTES, 'UTF-8'),
			 		);
			 	}
			}
		}
		$this->response->addHeader('Content-Type: application/json');
   		$this->response->setOutput(json_encode($json));
	}
	public function wdis_edit(){
		$str = "UPDATE `" . DB_PREFIX . "withdraw_request` SET description = '".$this->db->escape($this->request->post['description'])."' WHERE request_id = '".(int)$this->request->post['request_id']."'";
		$this->db->query($str);
		$str = "SELECT * FROM  `".DB_PREFIX."withdraw_request` WHERE request_id = '".(int)$this->request->post['request_id']."'";
		$request = $this->db->query($str)->row;
		$text = $this->db->escape($this->request->post['description']);
		$str = "UPDATE  `".DB_PREFIX."e_wallet_transaction` SET description =  '{$text}' WHERE transaction_id = '".$request['transaction_id']."'";

		$this->db->query($str);
	}
	public function withdraw_request(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000){
			$token = 'user_token=' . $this->session->data['user_token'];
		}else{
			$token = 'token=' . $this->session->data['token'];
		}
		$this->load->language('e_wallet/e_wallet');
		$data['entry_email_phone'] = $this->language->get('entry_email_phone');
		$data['entry_to'] = $this->language->get('entry_to');
		$data['status_pending'] = $this->language->get('status_pending');
		$data['status_approve'] = $this->language->get('status_approve');
		$data['status_reject'] = $this->language->get('status_reject');
		$data['entry_filter'] = $this->language->get('entry_filter');

		$page = 1;
		$data['status'] = '';
		$limit = $this->config->get('config_limit_admin');
		$url = $data['email'] = '';
		$where = ' WHERE 1 ';
		$data['datefrom'] = '';
		$data['dateto'] = '';
		$per = DB_PREFIX;
		if(isset($this->request->get['page'])) $page = (int)$this->request->get['page'];
		if(isset($this->request->get['limit'])) $limit = (int)$this->request->get['limit'];
		$filter = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		if(isset($this->request->request['datefrom']) && $this->request->request['datefrom']){
			$url ='&datefrom='.$this->request->request['datefrom'];
			$data['datefrom'] = $this->request->request['datefrom'];
			$where .= " AND date_added >= '".date('Y-m-d',strtotime(date('Y-m-d',strtotime($this->request->request['datefrom']))))."' ";
		}
		if(isset($this->request->request['dateto']) && $this->request->request['dateto']){
			$url ='&dateto='.$this->request->request['dateto'];
			$data['dateto'] = $this->request->request['dateto'];
			$where .= " AND date_added <= '".date('Y-m-d',strtotime(date('Y-m-d',strtotime($this->request->request['dateto'])).' +1 days'))."' ";
		}
		if(isset($this->request->request['status']) && trim($this->request->request['status']) != ''){
			$url ='&status='.$this->request->request['status'];
			$data['status'] = $this->request->request['status'];
			$where .= " AND status = '".(int)$data['status']."' ";
		}
		if(isset($this->request->request['email']) && $this->request->request['email']){
			$url ='&email='.$this->request->request['email'];
			$data['email'] = $this->request->request['email'];
			$where .= " AND customer_id IN (SELECT customer_id FROM  `{$per}customer` WHERE `email` like '%{$data['email']}%' or `telephone` like '%{$data['email']}%') ";
		}
		$totaltransaction = $this->db->query("SELECT COUNT(*) AS total FROM {$per}withdraw_request {$where}");
		$totaltransaction = $totaltransaction->row['total'];
		$results = $this->db->query("SELECT * FROM {$per}withdraw_request {$where} ORDER BY date_added DESC LIMIT ".$filter['start'].",".$filter['limit']." ");
		$results = $results->rows;
		$data['requests'] = array();
		$this->load->model('customer/customer');
		foreach ($results as $result){
			$customer_info = $this->model_customer_customer->getCustomer($result['customer_id']);
			$data['requests'][] = array(
				'request_id'  => $result['request_id'],
				'customer_id' => $result['customer_id'],
				'description' => $result['description'],
				'status' => $result['status'],
				'customer' => $customer_info['email'].' - '.$customer_info['telephone'],
				'price' => $this->currency->format($result['amount'],$this->config->get('config_currency')),
				'date' => date('d-m-Y h:i:s A',strtotime($result['date_added'])),
				'c_link'       => $this->url->link('customer/customer/edit', $token . '&customer_id=' . $result['customer_id'], 'SSL')
			);
		}
			$this->load->language('e_wallet/e_wallet');
			$data['breadcrumbs'] = array(array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/dashboard',$token, 'SSL')
				), array(
					'text' => $this->language->get('withdraw_heading_title'),
					'href' => $this->url->link('e_wallet/e_wallet/withdraw_request',$token, 'SSL')
				)
			);
			$data['delete'] = $this->url->link('e_wallet/e_wallet/deleterequest', $token, 'SSL');
			$data['approveurl'] = $this->url->link('e_wallet/e_wallet/wapproverequest',$token, 'SSL');
			$data['rejecturl'] = $this->url->link('e_wallet/e_wallet/wrejectrequest',$token, 'SSL');
			$data['formurl'] = $this->url->link('e_wallet/e_wallet/withdraw_request',$token, 'SSL');
			$data['dis_edit'] = $this->url->link('e_wallet/e_wallet/wdis_edit', $token, 'SSL');

			$data['heading_title']      = $this->language->get('withdraw_heading_title');
			$data['text_list']          = $this->language->get('withdraw_text_list');
			$data['text_no_results']    = $this->language->get('text_no_results');
			$data['text_confirm']       = $this->language->get('text_confirm');
			$data['column_name']        = $this->language->get('column_name');
			$data['column_sort_order']  = $this->language->get('column_sort_order');
			$data['column_action']      = $this->language->get('column_action');
			$data['button_add']         = $this->language->get('button_add');
			$data['button_edit']        = $this->language->get('button_edit');
			$data['button_delete']      = $this->language->get('button_delete');
			$data['column_customer']    = $this->language->get('column_customer');
			$data['column_description'] = $this->language->get('column_description');
			$data['column_price']       = $this->language->get('column_price');
			$data['column_date']        = $this->language->get('column_date');

			if (isset($this->error['warning'])){
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}
			if (isset($this->session->data['success'])){
				$data['success'] = $this->session->data['success'];
				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}
			if (isset($this->request->post['selected'])){
				$data['selected'] = (array)$this->request->post['selected'];
			} else {
				$data['selected'] = array();
			}
			$data['sort_name'] = $this->url->link('e_wallet/e_wallet/add_request', $token, 'SSL');
			$data['sort_sort_order'] = $this->url->link('e_wallet/e_wallet/add_request', $token, 'SSL');

			$pagination = new Pagination();
			$pagination->total = $totaltransaction;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_limit_admin');

			$pagination->url = $this->url->link('e_wallet/e_wallet/add_request', $token . '&page={page}', 'SSL');
			$data['pagination'] = $pagination->render();
			$data['results'] = sprintf($this->language->get('text_pagination'), ($totaltransaction) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($totaltransaction - $this->config->get('config_limit_admin'))) ? $totaltransaction : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $totaltransaction, ceil($totaltransaction / $this->config->get('config_limit_admin')));
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			if ($thisvar >= 3000){
				$this->response->setOutput($this->load->view('e_wallet/withdraw_request', $data));
			}else{
				$this->response->setOutput($this->load->view('e_wallet/withdraw_request.tpl', $data));
			}
	}
	public function wapproverequest(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000){
				$token = 'user_token=' . $this->session->data['user_token'];
		}else{
				$token = 'token=' . $this->session->data['token'];
		}
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()){
			foreach ($this->request->post['selected'] as $request_id){
				$this->wapproverequests($request_id);
			}
			$this->session->data['success'] = $this->language->get('text_success_request');
			$url = '';
			if (isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet/withdraw_request', $token .$url, 'SSL'));
		}
		$this->withdraw_request();
	}
	public function wrejectrequest(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000){
			$token = 'user_token=' . $this->session->data['user_token'];
		}else{
			$token = 'token=' . $this->session->data['token'];
		}
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()){
			foreach ($this->request->post['selected'] as $request_id){
				$this->wrejectrequests($request_id);
			}
			$this->session->data['success'] = $this->language->get('text_success_request');
			$url = '';
			if (isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet/withdraw_request',$token .$url, 'SSL'));
		}
		$this->withdraw_request();
	}
	public function wapproverequests($id){
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
		$this->load->language('e_wallet/e_wallet');
		$entry_req_approve  = $this->language->get('entry_req_approve');
		$entry_withdraw_req = $this->language->get('entry_withdraw_req');
		$entry_desciption   = $this->language->get('entry_desciption');
		$entry_amount       = $this->language->get('entry_amount');
		$entry_date_text    = $this->language->get('entry_date_text');
		$entry_request      = $this->language->get('entry_request');
		$per = DB_PREFIX;
		$sql = "SELECT * FROM `{$per}withdraw_request` WHERE request_id = '".(int)$id."' AND status = '0'";
		$request = $this->db->query($sql)->row;
		if($request){
			$ststus = 'Approved';
			$adddata = array(
				'customer_id' => $request['customer_id'],
				'amount' => $request['amount'],
				'description' => 'Request Approved : '.$request['description'].'.'
			);
			$desc="Request Approved : ".$request['description'];
			$str = "UPDATE `" . DB_PREFIX . "withdraw_request` SET description='".$this->db->escape($desc)."', status = '1' WHERE request_id = '".(int)$id."'";

			$this->db->query($str);
			$c_info = $this->db->query("SELECT * FROM  `{$per}customer` WHERE  customer_id = {$request['customer_id']}")->row;
			$admin_email = $this->config->get($module_key.'e_wallet_setting_mail');
			//-----------------------------For Admin email changes---------------------------------
			list($e_title,$withdraw_money_title,$withdraw_money_subject) = $this->lg(array('title','withdraw_money_text','withdraw_setting_sub'));
			$find = array('{EN}','{WT}','{ST}');
			$replace = array(
				'{EN}' => $e_title,
				'{WT}' => $withdraw_money_title,
				'{ST}' => $ststus,
			);
			$withdraw_money_sub_data = str_replace($find, $replace, $withdraw_money_subject);
			list($withdraw_money_data,$withdraw_title) = $this->lg(array('withdraw_setting_msg','withdraw_money_text'));
			$find = array('{WT}','{ST}','{DC}','{AD}','{DT}');
			$replace = array(
				'{WT}' => $withdraw_title,
				'{ST}' => $ststus,
				'{DC}' => $request['description'],
				'{AD}' => $this->currency->format($request['amount'],$this->config->get('config_currency')),
				'{DT}' => date('d-m-Y h:i:s A'), 
			);
		    $withdraw_money_data_sub = str_replace($find, $replace, $withdraw_money_data);
		    //---------------------------For User Email Changes----------------------
		    list($e_title,$withdraw_title,$withdraw_money_subject_user) = $this->lg(array('title','withdraw_money_text','withdraw_user_sub'));
			$find = array('{EN}','{WT}','{ST}');
			$replace = array(
				'{EN}' => $e_title,
				'{WT}' => $withdraw_title,
				'{ST}' => $ststus,
			);
			$withdraw_money_sub_data_user = str_replace($find, $replace, $withdraw_money_subject_user);
			list($withdraw_money_data_user) = $this->lg('withdraw_user_msg');
			$find = array('{ST}','{DC}','{AD}','{DT}');
			$replace = array(
				'{ST}' => $ststus,
				'{DC}' => $request['description'],
				'{AD}' => $this->currency->format($request['amount'],$this->config->get('config_currency')), 
				'{DT}' => date('d-m-Y h:i:s A'), 
			);
		    $withdraw_money_data_msg_user = str_replace($find, $replace, $withdraw_money_data_user);
		    list($email_header_text,$email_footer_text) = $this->lg(array('email_header_text','email_footer_text'));
			if($c_info){
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setFrom($admin_email);
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$admin_subject = $withdraw_money_sub_data;
				$admin_message = $withdraw_money_data_sub;
				$mail->setSubject(html_entity_decode($admin_subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($admin_message);
				$mail->setTo($admin_email);
				$mail->send();
				$user_subject = $withdraw_money_sub_data_user;
				$user_message = $email_header_text."\n\n".$withdraw_money_data_msg_user."\n\n".$email_footer_text;
				$mail->setTo($c_info['email']);
				$mail->setSubject(html_entity_decode($user_subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($user_message);
				$mail->send();
			}
			return true;
		}
		return false;
	}
	public function wrejectrequests($id){
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
		$per = DB_PREFIX;
		$sql = "SELECT * FROM `{$per}withdraw_request` WHERE request_id = '".(int)$id."' AND status = '0'";
		$request = $this->db->query($sql)->row;
		if($request){
			$adddata = array(
				'customer_id' => $request['customer_id'],
				'amount' => $request['amount'],
				'description' => 'Request Rejected : '.$request['description'].'.'
			);
			$transaction_id =$this->addtransaction($adddata);
			$desc="Request Rejected : ".$request['description'];
			$str = "UPDATE `" . DB_PREFIX . "withdraw_request` SET description='".$this->db->escape($desc)."', status = '2',transaction_id = '{$transaction_id}' WHERE request_id = '".(int)$id."'";
			$this->db->query($str);
			$ststus = 'Rejected';
			$c_info = $this->db->query("SELECT * FROM  `{$per}customer` WHERE  customer_id = {$request['customer_id']}")->row;
			$admin_email = $this->config->get($module_key.'e_wallet_setting_mail');
			//-------------------------for admin email changes-------------------------------
			list($e_title,$withdraw_money_title,$withdraw_money_subject) = $this->lg(array('title','withdraw_money_text','withdraw_setting_sub'));
			$find = array('{EN}','{WT}','{ST}');
			$replace = array(
				'{EN}' => $e_title,
				'{WT}' => $withdraw_money_title,
				'{ST}' => $ststus,
			);
			$withdraw_money_sub_data = str_replace($find, $replace, $withdraw_money_subject);
			list($withdraw_money_data,$withdraw_money_title) = $this->lg(array('withdraw_setting_msg','withdraw_money_text'));
			$find = array('{WT}','{ST}','{DC}','{AD}','{DT}');
			$replace = array(
				'{WT}' => $withdraw_money_title,
				'{ST}' => $ststus,
				'{DC}' => $request['description'],
				'{AD}' =>$this->currency->format($request['amount'],$this->config->get('config_currency')),
				'{DT}' => date('d-m-Y h:i:s A'), 
			);
		    $withdraw_money_data_sub = str_replace($find, $replace, $withdraw_money_data);
		    //----------------------------for user email changes--------------------------
		    list($e_title,$withdraw_money_title,$withdraw_money_subject_user) = $this->lg(array('title','withdraw_money_text','withdraw_user_sub'));
			$find = array('{EN}','{WT}','{ST}');
			$replace = array(
				'{EN}' => $e_title,
				'{WT}' => $withdraw_money_title,
				'{ST}' => $ststus,
			);
			$withdraw_money_sub_data_user = str_replace($find, $replace, $withdraw_money_subject_user);
			list($withdraw_money_data_user) = $this->lg('withdraw_user_msg');
			$find = array('{ST}','{DC}','{AD}','{DT}');
			$replace = array(
				'{ST}' => $ststus,
				'{DC}' => $request['description'],
				'{AD}' => $this->currency->format($request['amount'],$this->config->get('config_currency')),
				'{DT}' => date('d-m-Y h:i:s A'), 
			);
			$withdraw_money_data_msg_user = str_replace($find, $replace, $withdraw_money_data_user);
			list($email_header_text,$email_footer_text) = $this->lg(array('email_header_text','email_footer_text'));
			if($c_info){
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setFrom($admin_email);
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$admin_subject = $withdraw_money_sub_data;
				$admin_message = $withdraw_money_data_sub;
				$mail->setSubject(html_entity_decode($admin_subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($admin_message);
				$mail->setTo($admin_email);
				$mail->send();
				$user_subject = $withdraw_money_sub_data_user;
				$user_message = $email_header_text."\n\n".$withdraw_money_data_msg_user."\n\n".$email_footer_text;
				$mail->setTo($c_info['email']);
				$mail->setSubject(html_entity_decode($user_subject, ENT_QUOTES, 'UTF-8'));
				$mail->setText($user_message);
				$mail->send();
			}
			return true;
		}
		return false;
	}
	public function wdeleterequest(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000){
				$token = 'user_token=' . $this->session->data['user_token'];
		}else{
				$token = 'token=' . $this->session->data['token'];
		}
		$this->load->language('e_wallet/e_wallet');
		$this->document->setTitle($this->language->get('heading_title'));
		if (isset($this->request->post['selected']) && $this->validateDelete()){
			foreach ($this->request->post['selected'] as $request_id){
				$this->wdeleterequests($request_id);
			}
			$this->session->data['success'] = $this->language->get('text_success_request');
			$url = '';
			if (isset($this->request->get['page'])){
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('e_wallet/e_wallet/withdraw_request',$token.$url, 'SSL'));
		}
		$this->withdraw_request();
	}
	public function wdeleterequests($id){
		$str = "DELETE FROM `" . DB_PREFIX . "withdraw_request` WHERE request_id = '".(int)$id."'";
		return $this->db->query($str);
	}
	public function vouchar_list(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000){
			$token = 'user_token=' . $this->session->data['user_token'];
		}else{
			$token = 'token=' . $this->session->data['token'];
		}
		$page = 1;
		$per = DB_PREFIX;
		$limit = $this->config->get('config_limit_admin');
		if(isset($this->request->get['page'])) $page = (int)$this->request->get['page'];
		if(isset($this->request->get['limit'])) $limit = (int)$this->request->get['limit'];
		$where = ' WHERE 1 ';
		$per = DB_PREFIX;

		$filter = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
		$this->load->language('e_wallet/e_wallet');
		$data['entry_voucher_code']      = $this->language->get('entry_voucher_code');
		$data['entry_filter']            = $this->language->get('entry_filter');
		$data['entry_voucher_code_text'] = $this->language->get('entry_voucher_code_text');
		$data['entry_voucher_name']      = $this->language->get('entry_voucher_name');
		$data['entry_voucher_amount']    = $this->language->get('entry_voucher_amount');
		$data['entry_voucher_limit']     = $this->language->get('entry_voucher_limit');
		$data['entry_total_used']        = $this->language->get('entry_total_used');
		$data['entry_date_added']        = $this->language->get('entry_date_added');
		$data['entry_status']            = $this->language->get('entry_status');
		$data['entry_action']            = $this->language->get('entry_action');
		$data['entry_active']            = $this->language->get('entry_active');
		$data['entry_deactive']          = $this->language->get('entry_deactive');
		$data['entry_edit']              = $this->language->get('entry_edit');
		$data['entry_delete']            = $this->language->get('entry_delete');
		$data['entry_no_voucher_add']    = $this->language->get('entry_no_voucher_add');
		$data['entry_new_voucher_add']   = $this->language->get('entry_new_voucher_add');
		$data['entry_close']             = $this->language->get('entry_close');
		$data['entry_voucher_add']       = $this->language->get('entry_voucher_add');
		$data['button_delete']           = $this->language->get('button_delete');
		$data['entry_demo_model']        = $this->language->get('entry_demo_model');
		$data['entry_add_model']         = $this->language->get('entry_add_model');
		$data['entry_voucher_name']      = $this->language->get('entry_voucher_name');
		$data['entry_voucher_code']      = $this->language->get('entry_voucher_code');
		$data['entry_voucher_model_amount'] = $this->language->get('entry_voucher_model_amount');
		$data['entry_voucher_limit']       = $this->language->get('entry_voucher_limit');
		$data['entry_model_status'] = $this->language->get('entry_model_status');
		$data['entry_model_active'] = $this->language->get('entry_model_active');
		$data['entry_model_deactive'] = $this->language->get('entry_model_deactive');
		$data['entry_model_close'] = $this->language->get('entry_model_close');
		$data['entry_add_voucher'] = $this->language->get('entry_add_voucher');
 
		if (isset($this->session->data['success'])){
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])){
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$data['total']          = $this->language->get('total');
		$data['text_list']      = $this->language->get('text_list');

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $token, 'SSL')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('e_wallet/e_wallet', $token, 'SSL')
		);

		$data['formurl'] = $this->url->link('e_wallet/e_wallet/vouchar_list', $token, 'SSL');

		if(isset($this->request->request['search_vouchar']) && $this->request->request['search_vouchar']){
			$data['search_vouchar'] = $this->request->request['search_vouchar'];
			$where .= " AND `vouchar_name` like '%{$data['search_vouchar']}%' or `vouchar_code` like '%{$data['search_vouchar']}%' ";
		}else{
			$data['search_vouchar'] = '';

		}

		if (isset($this->error['warning'])){
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		$data['heading_title'] = 'Vouchar list';

		$str = "SELECT count(vouchar_id) AS total FROM `{$per}e_wallet_vouchar_list` {$where} ORDER BY date_added DESC";
		$totalvouchar = $this->db->query($str)->row['total'];
		$str = "SELECT * FROM `{$per}e_wallet_vouchar_list` {$where} ORDER BY date_added DESC LIMIT ".$filter['start'] .' , '.$filter['limit'];
		$results = $this->db->query($str)->rows;
		$data['vouchars'] = array();
		foreach ($results as $result){
			if (empty(trim($result['used_by']))){
				$total_used = 0;
			}else{
				$used_by_array = explode("','", $result['used_by']);
				$total_used = count($used_by_array);
			}
			$data['vouchars'][] = array(
				'vouchar_id'  => $result['vouchar_id'],
				'vouchar_name'  => $result['vouchar_name'],
				'vouchar_amount' => $result['vouchar_amount'],
				'vouchar_code' => $result['vouchar_code'],
				'user_limit' => $result['user_limit'],
				'total_used' => $total_used,
				'used_by' => $result['used_by'],
				'date_added' => $result['date_added'],
				'status' => $result['status']
			);
		}

	 	if(isset($_GET['print_data'])){
			echo "<pre>";print_r($data['vouchars']);echo "</pre>";die;
		}

		$pagination = new Pagination();
		$pagination->total = $totalvouchar;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('e_wallet/e_wallet/vouchar_list', $token . '&page={page}', 'SSL');


		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($totalvouchar) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($totalvouchar - $this->config->get('config_limit_admin'))) ? $totalvouchar : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $totalvouchar, ceil($totalvouchar / $this->config->get('config_limit_admin')));

		$data['delete'] = $this->url->link('e_wallet/e_wallet/delete_selected_vouchar', $token , 'SSL');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		if ($thisvar >= 3000){
			$this->response->setOutput($this->load->view('e_wallet/vouchar_list', $data));
		}else{
			$this->response->setOutput($this->load->view('e_wallet/vouchar_list.tpl', $data));
		}
	}
	public function add_vouchar_ajax(){
		$this->language->load('e_wallet/e_wallet');
		$error_voucher_code = $this->language->get('error_wrong_msg');
		$error_voucher_name = $this->language->get('error_voucher_name');
		$error_voucher_limit = $this->language->get('error_voucher_limit');
		$error_voucher_required = $this->language->get('error_voucher_required');
		$error_voucher_updated = $this->language->get('error_voucher_updated');
		$error_voucher_exist = $this->language->get('error_voucher_exist');
		$error_voucher_success = $this->language->get('error_voucher_success');

		$per = DB_PREFIX;
		$json = array();
		$json['error'] = array();
		if (!isset($this->request->post['vouchar_name']) || (strlen($this->request->post['vouchar_name']) <= 3 && strlen($this->request->post['vouchar_name']) >= 40)){
			$json['error']['vouchar_name'] = $error_voucher_name;
		}

		if (!isset($this->request->post['vouchar_code']) || (strlen($this->request->post['vouchar_code']) <= 3 && strlen($this->request->post['vouchar_code']) >= 40)){
			$json['error']['vouchar_code'] = $error_voucher_name;
		}

		if (!isset($this->request->post['user_limit']) || (strlen($this->request->post['user_limit']) <= 0)){
			$json['error']['user_limit'] = $error_voucher_limit;
		}

		if (!isset($this->request->post['vouchar_amount']) || (strlen($this->request->post['vouchar_amount']) <= 0)){
			$json['error']['vouchar_amount'] = $error_voucher_required;
		}

		if (empty($json['error'])){
			if (isset($this->request->post['vouchar_id']) && $this->request->post['vouchar_id']){
				$str = "UPDATE `{$per}e_wallet_vouchar_list` SET `vouchar_name` = '". $this->request->post['vouchar_name'] ."', `vouchar_code` = '". $this->request->post['vouchar_code'] ."', `vouchar_amount` = '". $this->request->post['vouchar_amount'] ."', `user_limit` = '". $this->request->post['user_limit'] ."', `status` = '". $this->request->post['user_status'] ."' WHERE vouchar_id= '". $this->request->post['vouchar_id'] ."' ";
		 		$this->db->query($str);

		 		$json['success'] = $error_voucher_updated;
			}else{
				$str = "SELECT count(vouchar_id) as total FROM `{$per}e_wallet_vouchar_list` WHERE vouchar_code= '". $this->request->post['vouchar_code'] ."' ";
				$vouchar_found = $this->db->query($str)->row['total'];

				if ($vouchar_found){
					$json['error']['exist'] = $error_voucher_exist;
				}else{
					$str = "INSERT INTO `" . DB_PREFIX . "e_wallet_vouchar_list` SET
					`vouchar_name` = '". $this->request->post['vouchar_name'] ."',
					`vouchar_code` = '". $this->request->post['vouchar_code'] ."',
					`vouchar_amount` = '". $this->request->post['vouchar_amount'] ."',
					`user_limit` = '". $this->request->post['user_limit'] ."',
					`used_by` = '',
					`status` = '". $this->request->post['user_status'] ."',
					`date_added` = NOW()";

					$this->db->query($str);
					$vouchar_id = $this->db->getLastId();

					$json['success'] = $error_voucher_success;
				}
			}

		}

		$this->response->addHeader('Content-Type: application/json');
   		$this->response->setOutput(json_encode($json));
	}
	public function delete_selected_vouchar(){
		$thisvar = $this->octlversion();
		if (isset($this->request->post['selected']) && $this->validateDelete()){
			foreach ($this->request->post['selected'] as $vouchar_id){
				$this->deletevouchar($vouchar_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
		}
		$url = '';
		if (isset($this->request->get['page'])){
			$url .= '&page=' . $this->request->get['page'];
		}
		if($thisvar >= 3000){
			$token = 'user_token=' . $this->session->data['user_token'];
		}else{
			$token = 'token=' . $this->session->data['token'];
		}
		$this->response->redirect($this->url->link('e_wallet/e_wallet/vouchar_list', $token .$url, 'SSL'));

	}
	public function deletevouchar($id){
		$str = "DELETE FROM `" . DB_PREFIX . "e_wallet_vouchar_list` WHERE vouchar_id = '".(int)$id."'";
		return $this->db->query($str);
	}
	public function deletevoucharajax(){
		$json = array();
		$this->load->language('e_wallet/e_wallet');
		$error_voucher_delete = $this->language->get('error_wrong_msg');
		$error_wrong_msg = $this->language->get('error_wrong_msg');

		if (isset($this->request->post['vouchar_id']) && $this->request->post['vouchar_id']){
			$this->deletevouchar($this->request->post['vouchar_id']);
			$json['success'] = $error_voucher_delete;
		}else{
			$json['error'] = $error_wrong_msg;
		}

		$this->response->addHeader('Content-Type: application/json');
   		$this->response->setOutput(json_encode($json));
	}
	protected function octlversion(){
    	$varray = explode('.', VERSION);
    	return (int)implode('', $varray);
	}
	private function validation(){
		$this->load->language('e_wallet/e_wallet');
		$entry_select_name     = $this->language->get('entry_select_name');
		$entry_amount_text     = $this->language->get('entry_amount_text');
		$entry_desciption_text = $this->language->get('entry_desciption_text');

		if(!$this->request->post['customer_id']){
			$this->error['name']="Select Name";
		}else if(!$this->request->post['amount']){
			$this->error['amount']="Enter Amount";
		}else if(!$this->request->post['description'] ){
			$this->error['description']="Enter Discription";
		}
		return !$this->error;
	}
	protected function validateDelete(){
		if (!$this->user->hasPermission('modify', 'e_wallet/e_wallet')){
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
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
}