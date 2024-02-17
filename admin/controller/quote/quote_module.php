<?php
class ControllerQuoteQuoteModule extends Controller {
	protected function octlversion(){
    	$varray = explode('.', VERSION);
    	return (int)implode('', $varray);
	}
	public function index(){
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

		$this->load->language('extension/module/quote_module');
		$this->load->model('quote/quote_module');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->getList();
	}
	public function delete(){
		$thisvar = $this->octlversion();
		$this->load->language('extension/module/quote_module');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('quote/quote_module');
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $p_q_o_value_id) {
			  $this->model_quote_quote_module->deleteQuotedata($p_q_o_value_id);
			}
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			if($thisvar >= 3000) $token = 'user_token=' . $this->session->data['user_token'];
			else $token = 'token=' . $this->session->data['token'];
			$this->response->redirect($this->url->link('quote/quote_module', $token . $url, true));
		}
		$this->getList();
	}
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'quote/quote_module')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		return !$this->error;
	}
	public function getList(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) $token = 'user_token=' . $this->session->data['user_token'];
			else $token = 'token=' . $this->session->data['token'];
		$data = array_merge($this->load->language('extension/module/quote_module'),array());
	    if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', $token, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('quote/quote_module', $token . $url, true)
		);
		$data['delete'] = $this->url->link('quote/quote_module/delete', $token . $url, true);

		$data['quote_module'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);
		$quote_total = $this->model_quote_quote_module->getTotalquotedata();
		$this->load->model('tool/image');
		$this->load->model('tool/upload');
		$results = $this->model_quote_quote_module->getquotedata($filter_data);
		foreach ($results as $result) {
			/*if (is_file(DIR_IMAGE . $result['file'])) {
				$image = $this->model_tool_image->resize($result['file'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}*/
			$upload_info = $this->model_tool_upload->getUploadByCode($result['file']);
			$data['quote_data'][] = array(
				'p_q_o_value_id' => $result['p_q_o_value_id'],
				'name'        => $result['name'],
				'city'        => $result['city'],
				'email'       => $result['email'],
				'phone'       => $result['phone'],
				'file'        => $upload_info['name'],
				'href'        => $this->url->link('tool/upload/download', $token . '&code=' . $upload_info['code'], true),
 				'info'        => $this->url->link('quote/quote_module/info', $token . '&p_q_o_value_id=' . $result['p_q_o_value_id'] . $url, true),
				'delete'      => $this->url->link('quote/quote_module/delete', $token . '&p_q_o_value_id=' . $result['p_q_o_value_id'] . $url, true)
			);
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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('quote/quote_module', $token . '&sort=name' . $url, true);
		$data['sort_sort_order'] = $this->url->link('quote/quote_module', $token . '&sort=sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $quote_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('quote/quote_module', $token . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($quote_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($quote_total - $this->config->get('config_limit_admin'))) ? $quote_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $quote_total, ceil($quote_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		if ($thisvar >= 3000){
			$this->response->setOutput($this->load->view('quote/quote_list', $data));
		}else{
			$this->response->setOutput($this->load->view('quote/quote_list.tpl', $data));
		}
	}
	public function info(){
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) $token = 'user_token=' . $this->session->data['user_token'];
			else $token = 'token=' . $this->session->data['token'];
		$data = array_merge($this->load->language('extension/module/quote_module'),array());
		$data['text_order'] = $this->language->get('text_order');
		$this->load->model('quote/quote_module');
		$this->load->model('tool/upload');
		$this->load->model('tool/image');
		if (isset($this->request->get['p_q_o_value_id'])) {
			$quote_value_id = $this->request->get['p_q_o_value_id'];
		} else {
			$quote_value_id = 0;
		}
		$data['quote_info_new'] = array();
	    $quote_info = $this->model_quote_quote_module->getQuote($quote_value_id);
	    foreach ($quote_info as $quote_data_value) {
	    	if (is_file(DIR_IMAGE . $quote_data_value['image'])) {
				$thumb = $this->model_tool_image->resize($quote_data_value['image'], 40, 40);
			} else {
				$thumb = $this->model_tool_image->resize('no_image.png', 40, 40);
			}
	    	$quote_options = array_values(json_decode($quote_data_value['quote_options'], true));
	    	$upload_info = $this->model_tool_upload->getUploadByCode($quote_data_value['file']);
	    	$data['q_data'] = array(
	    		'name' => $quote_data_value['name'],
	    		'city' => $quote_data_value['city'],
	    		'email' => $quote_data_value['email'],
	    		'phone' => $quote_data_value['phone'],
	    		'file'  => $upload_info['name'],
	    		'comment' => $quote_data_value['comment'],
	    		'href_file'  => $this->url->link('tool/upload/download', $token . '&code=' . $upload_info['code'], true),
	    	);
	    	$data['quote_info'][] = array(
	    		'p_q_o_value_id' => $quote_data_value['p_q_o_value_id'],
	    		'product_id' => $quote_data_value['product_id'],
	    		'product_name' => $quote_data_value['product_name'],
	    		'image' => $thumb,
	    		'model' => $quote_data_value['model'],
	    		'price' => $this->currency->format($quote_data_value['price'],$this->config->get('config_currency')),
	    		'quantity' => $quote_data_value['quantity'],
	    		'total' => $this->currency->format(($quote_data_value['price'] * $quote_data_value['quantity']), $this->config->get('config_currency')),
	    		'quote_options'=> $quote_options,
	    		'href'     		   => $this->url->link('catalog/product/edit', $token . '&product_id=' . $quote_data_value['product_id'], true)
	    	);
	    }
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		if ($thisvar >= 3000){
			$this->response->setOutput($this->load->view('quote/quote_info', $data));
		}else{
			$this->response->setOutput($this->load->view('quote/quote_info.tpl', $data));
		}
	}
}