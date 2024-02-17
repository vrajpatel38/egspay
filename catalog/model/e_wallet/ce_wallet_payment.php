<?php
class ModelExtensionpaymentEwalletPayment extends Model{
	public function getMethod($address, $total){
		$this->load->language('payment/e_wallet_payment');
		$thisvar = $this->octlversion();
		$payment_key = $module_key = $total_key = '';
		if($thisvar >= 3000){
			$payment_key = 'payment_';
			$module_key  = 'module_';
			$total_key   = 'total_';
		}
		$status         = true;
		$wallet_balance = 0;
		$method_data    = array();
		$data           = array();
		if(isset($this->session->data['vouchers_key'], $this->session->data['vouchers'])) $status = !isset($this->session->data['vouchers'][$this->session->data['vouchers_key']]);
		if($status && $this->config->get($module_key.'e_wallet_status')){

			if((int)$this->config->get($total_key.'e_wallet_total') > 0 && $this->config->get($total_key.'e_wallet_total') > $total) $status = false;

			if($status && $this->config->get('e_wallet_geo_zone_id')){
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('e_wallet_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
				if(!$query->num_rows) $status = false;
			}

			if($status){
				$this->load->model('account/e_wallet');
				$wallet_balance = (float)$this->model_account_e_wallet->getBalance();
				if($wallet_balance <= 0) $status = false;
			}

			if($status){
				list($data['title']) = $this->lg("title");
			  	list($new_e_wallet_title, $new_half_payment_line, $new_full_payment_line) = $this->lg(array(
					"payment_method_title" => $data['title'],
					"half_payment_line"    => "<p>Payment to be made{OT} - wallet:{WT} Select an Option to pay balance{RT} test<br></p>",
					"full_payment_line"    => "<p>Awesome! You have Sufficient balance in your Wallet test</p>",
			  	));
				$new_e_wallet_title    = sprintf($this->language->get('text_use')."\n".$new_e_wallet_title);
				$new_full_payment_line = html_entity_decode($new_full_payment_line,ENT_QUOTES, 'UTF-8');
				$new_half_payment_line = html_entity_decode($new_half_payment_line,ENT_QUOTES, 'UTF-8');

				$remain_payment = 0;
				if($total > $wallet_balance){
					$remain_payment = $total - $wallet_balance;
					$config_currency = isset($this->session->data['currency']) ? $this->session->data['currency'] : $this->config->get('config_currency');
					
					$find = array('{OT}', '{WT}', '{RT}');
					$replace = array(
						'OT' => $this->currency->format($total,$config_currency),
						'WT' => $this->currency->format($wallet_balance,$config_currency),
						'RT' => $this->currency->format($remain_payment,$config_currency),
					);
					$wallettext = str_replace($find, $replace, html_entity_decode($new_half_payment_line, ENT_QUOTES, 'UTF-8'));
					$data['wallettext'] = $wallettext;
					}else{
					$data['wallettext'] = $new_full_payment_line;
					}

	  			$data['new_title'] = $new_e_wallet_title;
	  			$data['remain_payment'] = $remain_payment;
	  			$data['use_e_wallet'] = isset($this->session->data['use_e_wallet']) ? true : false;
	  			$data['other_theme'] = (int)defined("JOURNAL_VERSION");
	  			if($this->octlversion() >= 2200){
		  				$method_data = array(
						'code'       => 'e_wallet_payment',
						'title'      => $this->load->view($this->config->get('config_template') . 'e_wallet/payment_title', $data),
						'terms'      => '',
						'sort_order' => -1
					);
	  			}else{
		  				$method_data = array(
						'code'       => 'e_wallet_payment',
						'title'      => $this->load->view('default/template/e_wallet/payment_title.tpl', $data),
						'terms'      => '',
						'sort_order' => -1
					);
	  			}
			}
		}
		return $method_data;
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
	protected function octlversion(){
    	$varray = explode('.', VERSION);
    	return (int)implode('', $varray);
	}
	public function get_order_history($order_info,$order_id,$order_status_id,$comment = '',$notify = false){
		$thisvar = $this->octlversion();
		$payment_key = $module_key = $total_key = '';
		if($thisvar >= 3000){
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}
		list($add_data,$add_money_text,$wallet_text) = $this->lg(array('add_money_string_text','add_money_text','title'));
		list($approve_data,$add_money_text) = $this->lg(array('approve_request_text','add_money_text'));
		if($order_info && $this->config->get($module_key.'e_wallet_status')){
			$config_currency = isset($this->session->data['currency']) ? $this->session->data['currency'] : $this->config->get('config_currency');
			$alls = array_merge($this->config->get($module_key.'e_wallet_processing_status'),$this->config->get($module_key.'e_wallet_complete_status'));
			if(!in_array($order_info['order_status_id'],$alls) && $order_status_id){
				$this->load->model('account/order');
				$vouchers = $this->model_account_order->getOrderVouchers($order_id);
				if(count($vouchers) == 1){
				 	$vouchers = $vouchers[0];
				 	if($vouchers['to_name'] == 'e_wallet_vouchers'){
						$this->load->model('account/e_wallet');
						$amount = $order_info['total'];
						$find = array('{AT}', '{WT}', '{BT}');
						$replace = array(
							'{AT}' => $add_money_text,
							'{WT}' => $wallet_text,
							'{BT}' => $this->currency->format($amount,$config_currency),
						);
						$add_money_text = str_replace($find, $replace,$add_data);
						if($order_info['currency_code'] != $this->config->get('config_currency'))
							$amount = $this->currency->convert($amount,$order_info['currency_code'],$config_currency);
						if(in_array($order_status_id,$this->config->get($module_key.'e_wallet_complete_status'))){
							if((float)$amount > 0){
								$data = array(
									'desc' => $add_money_text,
									'amount' => $amount,
									'customer_id' => $order_info['customer_id'],
								);
								$this->model_account_e_wallet->addtransaction($data);
							} 
						}else{
							$find = array('{AT}', '{BT}', '{PM}');
							$replace = array(
								'{AT}' => $add_money_text,
								'{BT}' => $this->currency->format($amount,$config_currency),
								'{PM}' => $order_info['payment_method'],
							);
							$approve_money_text = str_replace($find,$replace,$approve_data);
							$data = array(
								'desc' => $approve_money_text,
								'amount' => $amount,
								'customer_id' => $order_info['customer_id'],
							);
							$this->model_account_e_wallet->addcod_request($data);
						}	
						if($this->config->get($module_key.'e_wallet_delete_voucher_order')){
							$this->load->model('checkout/order');
							$this->model_checkout_order->deleteOrder($order_id);
							return false;
						}
					}
				}
			}
			list($paid_orderid_msg) = $this->lg('order_id_text');
			if($this->config->get($payment_key.'e_wallet_payment_status')){
				if(!$order_info['order_status_id'] && in_array($order_status_id,$alls)){
					if($order_info['payment_code'] != "e_wallet_payment"){
						$per = DB_PREFIX;
						$check_total = $this->db->query("SELECT order_id,value FROM  `{$per}order_total` WHERE `order_id` = '{$order_id}' AND code = 'e_wallet_total'");
					   	if($check_total->num_rows == 1){
					   	  $find = array('{OI}');
						   $replace = array(
								'{OI}' => $order_id,
						   );
						   $o_msg = str_replace($find, $replace,$paid_orderid_msg);
						   $this->load->model('account/e_wallet');
							$wallet_balance = $this->model_account_e_wallet->getBalance();
							if((int)$wallet_balance > 0 && (int)$wallet_balance == abs($check_total->row['value'])){
							$data = array(
								'desc' => $o_msg,
								'amount' => -$wallet_balance,
								'customer_id' => $order_info['customer_id'],
							);
							$this->model_account_e_wallet->addtransaction($data);			 
							}else{
								$order_status_id = $this->config->get("config_fraud_status_id");
								if((int)$wallet_balance > 0){
									$data = array(
										'desc' => $o_msg,
										'amount' => -$wallet_balance,
										'customer_id' => $order_info['customer_id'],
									);			 
									$this->model_account_e_wallet->addtransaction($data);
								}
							}
						}
					}
				}
			}
			$refunds = $this->config->get($module_key.'e_wallet_refund_order_id');
			$refunds = is_array($refunds) ? $refunds : array();
			list($refund_data) = $this->lg('refund_string_text');
			$find = array('{OI}');
			$replace = array(
				'{OI}' => $order_id,
			);
			$refund_msg = str_replace($find, $replace,$refund_data);
			$per = DB_PREFIX;
			$e_total = $this->db->query("SELECT order_id,value FROM  `{$per}order_total` WHERE  `order_id` = '{$order_id}' AND code = 'e_wallet_total'");
			if(in_array($order_status_id, $refunds) && ($order_info['payment_code'] == "e_wallet_payment" || $e_total->num_rows)){
				$order_history = $this->db->query("SELECT * FROM `{$per}order_history` WHERE `order_status_id` IN(".implode(',', $refunds).") AND order_id = '{$order_id}'");
				if(!$order_history->num_rows){
					$this->load->model('account/e_wallet');
					$amount = $e_total->num_rows ? abs($e_total->row['value']) : $order_info['total'];
					if($order_info['currency_code'] != $this->config->get('config_currency')){
						$amount = $this->currency->convert($amount,$order_info['currency_code'],$config_currency);
					}
					$d = array(
						'customer_id' => $order_info['customer_id'],
						'amount' => $amount,
						'desc' => $refund_msg,
					);
					$this->model_account_e_wallet->addtransaction($d);
				 }
			}
		}
		return true;
	}
}

class Modelpaymentewalletpayment extends ModelExtensionpaymentEwalletPayment{
	function __construct($registry){
		parent::__construct($registry);
	}
}