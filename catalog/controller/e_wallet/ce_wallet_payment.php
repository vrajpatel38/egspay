<?php
class ControllerExtensionPaymentewalletpayment extends Controller {
	
	public function index() {
		$thisvar = $this->octlversion();
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['continue'] = $this->url->link('checkout/success');
		if($thisvar >= 2200) {
			return $this->load->view('e_wallet/e_wallet_payment', $data);
		}else{
			return $this->load->view('default/template/e_wallet/e_wallet_payment.tpl', $data);
		}
	}
	public function confirm() {
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}
		if ($this->session->data['payment_method']['code'] == 'e_wallet_payment') {
			$this->load->model('account/e_wallet');
			$wallet_balance = $this->model_account_e_wallet->getBalance();
			$this->load->model('checkout/order');
			$o_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			$amount = $o_info['total'];
			if((int)$wallet_balance > 0 && (float)$amount <= (float)$wallet_balance){
				if($o_info['currency_code'] != $this->config->get('config_currency')){
					$amount = $this->currency->convert($amount,$o_info['currency_code'],$this->config->get('config_currency'));
				}
				list($paid_order) = $this->lg('order_id_text');
				$find = array('{OI}');
				$replace = array(
					'{OI}' => $this->session->data['order_id'],
				);
				$paid_order_text = str_replace($find,$replace,$paid_order);
				$data = array(
					'desc' => $paid_order_text,
					'amount' => -$amount,
				);
				$this->model_account_e_wallet->addtransaction($data);
				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get($payment_key.'e_wallet_payment_order_status_id'));
			}else{
				list($sufficient_msg,$wallet_text) = $this->lg('sufficient_msg','title');
				$find = array('{WT}');
				$replace = array(
					'{WT}' => $wallet_text,
				);
				$sufficient_msg = str_replace($find,$replace,$sufficient_msg);
				$json['error'] = $sufficient_msg;
				header("Content-Type: application/json; charset=UTF-8");
				echo json_encode($json);
				die;
			}
		}else{
			list($invalid_msg) = $this->lg('invalid_method');
			$json['error'] = $invalid_msg;
			header("Content-Type: application/json; charset=UTF-8");
			echo json_encode($json);
			die;
		}
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
}
class ControllerPaymentewalletpayment extends ControllerExtensionPaymentewalletpayment{
		function __construct($registry)
		{
			parent::__construct($registry);
		}
}