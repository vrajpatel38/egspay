<?php
if (defined('VERSION') && (VERSION == '2.2.0.0')){
    class Modeltotalewallettotal extends Model{
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
	    public function getTotal($total){
	        if (isset($this->session->data['use_e_wallet']) && (bool)$this->session->data['use_e_wallet']){
	            $this->load->model('account/e_wallet');
	            $wallet_balance = $this->model_account_e_wallet->getBalance();
	            if((int)$wallet_balance > 0 && (float)$total['total'] > (float)$wallet_balance){
	            	list($t_title) = $this->lg('total_title');
	                $total['totals'][] = array(
	                    'code'       => 'e_wallet_total',
	                    'title'      => $t_title,
	                    'value'      => -$wallet_balance,
	                    'sort_order' => $this->config->get('e_wallet_total_sort_order')
	                );
	                $total['total'] -= $wallet_balance;
	            }
	        }
	    }
   	}
}else{
    class ModeltotalEwalletTotal extends Model{
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
	   public function getTotal(&$total_data, &$total, &$taxes){
	        if (isset($this->session->data['use_e_wallet']) && (bool)$this->session->data['use_e_wallet']){
	            $this->load->model('account/e_wallet');
	            $wallet_balance = $this->model_account_e_wallet->getBalance();
	            if((int)$wallet_balance > 0 && (float)$total > (float)$wallet_balance){
	            	list($t_title) = $this->lg('total_title');
	                $genrateed_html = '';
	                $total = $this->cart->getSubTotal();
	                $genrateed_html = '';
	                $total_data[] = array(
	                    'code'       => 'e_wallet_total',
	                    'title'      => $t_title,
	                    'value'      => -$wallet_balance,
	                    'sort_order' => $this->config->get('e_wallet_total_sort_order')
	                );
	                $total -= $wallet_balance;
	            }
	        }
	   }
    }


}
class ModelExtensiontotalewallettotal extends Model {
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
		public function getTotal($total){
			$thisvar = $this->octlversion();
				if($thisvar >= 3000) {
					$payment_key = 'payment_';
					$module_key = 'module_';
					$total_key = 'total_';
				}else{
					$payment_key = $module_key = $total_key = '';
				}
			if (isset($this->session->data['use_e_wallet']) && $this->session->data['use_e_wallet']){
				$this->load->model('account/e_wallet');
				$wallet_balance = $this->model_account_e_wallet->getBalance();
				if((int)$wallet_balance > 0 && (float)$total['total'] > (float)$wallet_balance){
					list($t_title) = $this->lg('total_title');
					$total['totals'][] = array(
						'code'       => 'e_wallet_total',
						'title'      => $t_title,
						'value'      => -$wallet_balance,
						'sort_order' => $this->config->get($total_key.'e_wallet_total_sort_order')
					);
					$total['total'] -= $wallet_balance;
				}
			}
		}
	}




