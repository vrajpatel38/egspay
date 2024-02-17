<?php
class ModelAccountEwallet extends Model{
	public $per = DB_PREFIX;
	public function gettransaction($data = array()){
		$str = "SELECT * FROM `{$this->per}e_wallet_transaction` 
			WHERE customer_id = ".(int)$this->customer->getId();
		if(isset($data['datefrom'])) $str .= " AND date_added >= '".date('Y-m-d',strtotime($data['datefrom']))."'";
		if(isset($data['dateto'])) $str .= " AND date_added <= '".date('Y-m-d',strtotime($data['dateto'].' +1 days'))."'";
		$str .= " ORDER BY date_added DESC ";
		$start = 0; $limit = 20;
		if(isset($data['start'])) $start = $data['start'];
		if(isset($data['limit'])) $limit = $data['limit'];
		$str .= " LIMIT ".$start." , ".$limit;
		$data = $this->db->query($str);
		return $data->rows;
	}
	public function gettransactiontotal($data = array()){
		$str = "SELECT COUNT(*) AS total FROM `{$this->per}e_wallet_transaction` 
			WHERE customer_id = ".(int)$this->customer->getId();
		if(isset($data['datefrom'])) $str .= " AND date_added >= '".date('Y-m-d',strtotime($data['datefrom']))."'";
		if(isset($data['dateto'])) $str .= " AND date_added <= '".date('Y-m-d',strtotime($data['dateto'].' +1 days'))."'";
		$data = $this->db->query($str);
		return $data->row['total'];
	}
	public function getopenningbalance($data = array()){
		$time = strtotime($data['datefrom'].' -1 days');
		$str = "SELECT balance FROM `{$this->per}e_wallet_transaction` 
			WHERE customer_id = ".(int)$this->customer->getId();
		if(isset($data['datefrom'])) $str .= " AND DATE(date_added) <= '".date('Y-m-d',$time)."'";
		$str .= " ORDER BY date_added DESC ";
		$data = $this->db->query($str);
		if($data->num_rows)
			return $data->row['balance'];
		return 0;
	}
	public function getbank($data = array()){
		if(isset($data['customer_id'])) $customer_id = (int)$data['customer_id'];
		else $customer_id = (int)$this->customer->getId();
		$str = "SELECT * FROM `{$this->per}e_wallet_bank` 
			WHERE customer_id = ".(int)$customer_id;
		$data = $this->db->query($str);
		if($data->num_rows)
			return $data->row;
		return array();
	}
	public function setbank($data = array()){
		if(isset($data['customer_id'])) $customer_id = (int)$data['customer_id'];
		else $customer_id = (int)$this->customer->getId();
		$data['data'] = isset($data['data']) ? $data['data'] : array();

		$bank = $this->getbank($data);
		if(!$bank) $str = "INSERT INTO ";
		else $str = "UPDATE ";
		$str .= "`{$this->per}e_wallet_bank` SET
		`data` = '". $this->db->escape(json_encode($data['data'])) ."',
		`update_date` = now() ";
		if($bank) $str .= " WHERE customer_id = ".(int)$customer_id;
		else $str .= " ,`customer_id` = '{$customer_id}' ";
		 // echo "<pre>"; print_r($str); echo "</pre>";die();
		$this->db->query($str);
	}
	public function setbalance($data = array()){
		if(isset($data['customer_id'])) $customer_id = (int)$data['customer_id'];
		else $customer_id = (int)$this->customer->getId();
		$data['customer_id'] = $customer_id;
		$balance = (float)$this->getBalance($data);
		$str = "UPDATE `{$this->per}e_wallet_transaction` SET
			balance = {$balance}
			WHERE customer_id = ".$customer_id." AND transaction_id = ".(int)$data['transaction_id'];
		$this->db->query($str);
	}
	
	public function getBalance($data = array()){
		if(isset($data['customer_id'])) $customer_id = (int)$data['customer_id'];
		else $customer_id = (int)$this->customer->getId();
		$str = "SELECT SUM(price) as total FROM `{$this->per}e_wallet_transaction` WHERE customer_id = ".$customer_id;
		$data = $this->db->query($str);
		return $data->row['total'];
	}

	public function addcod_request($data = array()){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}
		if(isset($data['customer_id'])) $customer_id = (int)$data['customer_id'];
		else $customer_id = (int)$this->customer->getId();
		$sql = "INSERT INTO `".DB_PREFIX."cod_request` SET 
			customer_id = ".$customer_id." ,
			amount = ".(float)$data['amount']." ,
			description = '".$this->db->escape($data['desc'])."',
			date_added = NOW()";
		$this->db->query($sql);
		$cod_request_id = $this->db->getLastId();
		list($add_cod_req_sub) = $this->lg('add_money_req_sub');
		list($add_cod_req_msg) = $this->lg('add_money_req_msg');
		$find = array('{DC}','{AT}','{DT}');
		$replace = array(
			'{DC}' => $data['desc'],
			'{AT}' => $this->currency->format($data['amount'],$this->config->get('config_currency')),
			'{DT}' => date('d-m-Y h:i:s A'), 
		);
		$add_cod_req_message = str_replace($find, $replace, $add_cod_req_msg);
		$admin_email = $this->config->get($module_key.'e_wallet_setting_mail');
		list($email_header_text,$email_footer_text) = $this->lg(array('email_header_text','email_footer_text'));
		if($this->customer->getId()){
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
			$subject = $add_cod_req_sub;
			$message = $email_header_text."\n\n".$add_cod_req_message."\n\n".$email_footer_text;
			$mail->setTo($this->customer->getEmail());
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText($message);
			$mail->send();
		}
		return $cod_request_id;
	}
	
	public function addtransaction($data = array()){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}
		if(isset($data['customer_id']) && (int)$data['customer_id']){
			$customer_id = (int)$data['customer_id'];
		}else if((int)$this->customer->getId()){
			$customer_id = (int)$this->customer->getId();
		}  
		$str = "INSERT INTO `{$this->per}e_wallet_transaction` SET          
			`customer_id` = '".(int)$customer_id."',
			`price` = '".(float)$data['amount']."',
			`description` = '".$this->db->escape($data['desc'])."',
			`date_added` = NOW()";
		$this->db->query($str);
		$transaction_id = $this->db->getLastId();
		$this->setbalance(array('customer_id' => $customer_id,'transaction_id' => $transaction_id));
		list($transaction_email_text,$wallet_text) = $this->lg(array('add_transaction_email_sub','title'));
		$find = array('{WT}');
		$replace = array(
		  '{WT}' => $wallet_text, 
		);
		$transaction_sub_text = str_replace($find,$replace,$transaction_email_text);
		list($transcation_mail_msg) = $this->lg('transaction_mail_msg');
		$find = array('{DC}','{AT}','{DT}');
		$replace = array(
			'{DC}' => $data['desc'],
			'{AT}' => $this->currency->format($data['amount'],$this->config->get('config_currency')),
			'{DT}' => date('d-m-Y h:i:s A'), 
		);
		$transaction_data = str_replace($find, $replace, $transcation_mail_msg);
		$admin_email = $this->config->get($module_key.'e_wallet_setting_mail');
		list($email_header_text,$email_footer_text) = $this->lg(array('email_header_text','email_footer_text'));
		if($this->customer->getId()){
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
			$subject = $transaction_sub_text;
			$message = $email_header_text."\n\n".$transaction_data."\n\n".$email_footer_text;
			$mail->setTo($this->customer->getEmail());
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText($message);
			$mail->send();
		}
		return $transaction_id;
	}
	protected function octlversion(){
    	$varray = explode('.', VERSION);
    	return (int)implode('', $varray);
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
	public function sendmoney($data = array()){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}
		$language_data = $this->config->get($module_key.'e_wallet_language');
		list($send_data,$send_money_text,$wallet_text) = $this->lg(array('send_money_string_text','send_money_text','title'));
		list($receive_money_text) = $this->lg('receive_money_text');
		$find = array('{FT}','{LT}','{ET}');
		$replace = array(
			'{FT}' => $this->customer->getFirstName(),
			'{LT}' => $this->customer->getLastName(),
			'{ET}' => $this->customer->getEmail(),
			);
		$receive_data = str_replace($find,$replace,$receive_money_text);
		if(isset($data['customer_id']) && (int)$data['customer_id'] != 0){
			$str = "INSERT INTO `{$this->per}e_wallet_transaction` SET          
				`customer_id` = '".(int)$data['customer_id']."',
				`price` = '".(float)$data['amount']."',
				`description` = '".$receive_data."',
				`date_added` = NOW()";
			$this->db->query($str);
			$transaction_id = $this->db->getLastId();
			$this->setbalance(array('customer_id' => $data['customer_id'],'transaction_id' => $transaction_id));
			$c_email = $this->db->query("SELECT email FROM `{$this->per}customer` WHERE customer_id = '".(int)$data['customer_id']."'")->row;
			$find = array('{ST}', '{NT}', '{ET}');
			$replace = array(
				'{ST}' => $send_money_text,
				'{NT}' => $data['name'],
				'{ET}' => $c_email['email'],
			);
			$send_money_text = str_replace($find, $replace,$send_data);
			$str = "INSERT INTO `{$this->per}e_wallet_transaction` SET          
				`customer_id` = '".(int)$this->customer->getId()."',
				`price` = '".(float)-$data['amount']."',
				`description` = '".$send_money_text."',
				`date_added` = NOW()";
			$this->db->query($str);
			list($receive_sub_msg,$wallet_title) = $this->lg(array('receive_money_email_sub','title'));
			$find = array('{ET}');
			$replace = array(
				'{ET}' => $wallet_title,
			);
			$receive_email_data = str_replace($find,$replace,$receive_sub_msg);
			list($receive_data_msg) = $this->lg(array('receive_email_msg'));
			$find = array('{FT}','{AT}','{ET}','{DT}');
			$replace = array(
				'{FT}' => $this->customer->getFirstName(),
				'{AT}' => $this->currency->format($data['amount'],$this->config->get('config_currency')), 
				'{ET}' => $this->customer->getEmail(),
				'{DT}' => date('d-m-Y h:i:s A'),
			); 
			$receive_msg = str_replace($find,$replace,$receive_data_msg);
			list($send_data_sub,$wallet_text,$send_money_title) = $this->lg(array('send_data_sub','title','send_money_text'));
			$find = array('{ST}','{ET}');
			$replace = array(
				'{ST}' => $send_money_title,
				'{ET}' => $wallet_text,
			);
			$send_data_text = str_replace($find, $replace,$send_data_sub);
			list($send_money_msg,$send_data) = $this->lg(array('send_money_msg','send_money_text'));
			$find = array('{ST}','{NT}','{AT}','{ET}','{DT}');
			$replace = array(
				'{ST}' => $send_data,
				'{NT}' => $data['name'],
				'{AT}' => $this->currency->format($data['amount'],$this->config->get('config_currency')),
				'{ET}' => $data['email'],
				'{DT}' => date('d-m-Y h:i:s A'),
			);
			$send_email_msg = str_replace($find,$replace,$send_money_msg);
			$transaction_id = $this->db->getLastId();
			$this->setbalance(array('customer_id' => $this->customer->getId(),'transaction_id' => $transaction_id));
			$admin_email = $this->config->get($module_key.'e_wallet_setting_mail');
			list($email_header_text,$email_footer_text) = $this->lg(array('email_header_text','email_footer_text'));
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
			$r_subject = $receive_email_data;
			$r_message = $email_header_text."\n\n".$receive_msg."\n\n".$email_footer_text;
			$mail->setTo($data['email']);
			$mail->setSubject(html_entity_decode($r_subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText($r_message);
			$mail->send();
			$s_subject = $send_data_text;
			$s_message = $email_header_text."\n\n".$send_email_msg."\n\n".$email_footer_text;
			$mail->setTo($this->customer->getEmail());
			$mail->setSubject(html_entity_decode($s_subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText($s_message);
			$mail->send();
		}
	}
	public function withdrawmoney($data = array()){
			$thisvar = $this->octlversion();
			if($thisvar >= 3000) {
				$payment_key = 'payment_';
				$module_key = 'module_';
				$total_key = 'total_';
			}else{
				$payment_key = $module_key = $total_key = '';
			}
			list($withdraw_data,$withdraw_money_text,$wallet_title) = $this->lg(array('withdraw_string_text','withdraw_money_text','title'));
			$find = array('{WT}', '{AT}','{ET}');
			$replace = array(
				'{WT}' => $withdraw_money_text,
				'{AT}' => $this->currency->format($data['amount'],$this->config->get('config_currency')),
				'{ET}' => $wallet_title,
			);
			$withdraw_money_text = str_replace($find,$replace,$withdraw_data);
			$str = "INSERT INTO `{$this->per}e_wallet_transaction` SET          
				`customer_id` = '".(int)$this->customer->getId()."',
				`price` = '".(float)-$data['amount']."',
				`description` = '".$withdraw_money_text."',
				`date_added` = NOW()";
			$this->db->query($str);
			$transaction_id = $this->db->getLastId();
			$this->setbalance(array('customer_id' => $this->customer->getId(),'transaction_id' => $transaction_id));
			$customer_id = (int)$this->customer->getId();
			$sql = "INSERT INTO `".DB_PREFIX."withdraw_request` SET 
				customer_id = ".$customer_id." ,
				amount = ".(float)$data['amount']." ,
				description = '".$withdraw_money_text."',
				date_added = NOW()";
			$this->db->query($sql);
	}
}