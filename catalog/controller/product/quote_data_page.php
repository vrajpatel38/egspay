<?php
class ControllerProductQuoteDataPage extends Controller {
	public $error = array();
	protected function octlversion(){
    	$varray = explode('.', VERSION);
    	return (int)implode('', $varray);
	}
	public function index() {
		$thisvar = $this->octlversion();
		if($thisvar >= 3000) {
			$payment_key = 'payment_';
			$module_key = 'module_';
			$total_key = 'total_';
		}else{
			$payment_key = $module_key = $total_key = '';
		}
        $this->load->language('product/quote_data_page');
		$this->load->model('tool/image');
		$this->load->model('catalog/product');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data = array_merge($this->load->language('product/quote_data_page'),array());
		$product_data = isset($this->session->data['product_quote_data']) ? $this->session->data['product_quote_data'] : array();
		foreach($product_data as $product_details){
			$data['quote_json_encode'] = json_encode($product_details['quote_option_save'] , JSON_FORCE_OBJECT);
			$result = array_values(json_decode($data['quote_json_encode'], true));
			if ($product_details['image']) {
				$thumb = $this->model_tool_image->resize($product_details['image'], 200,300);
			} else {
				$thumb = '';
			}
			$data['product_data_info'][] = array(
				'product_id' => $product_details['product_id'],
				'name' => $product_details['name'],
				'model' => $product_details['model'],
				'price' => $product_details['price'],
				'quantity' => $product_details['quantity'],
				'image' => $product_details['image'],
				'thumb' => $thumb,
				'option_value_json' => json_encode($product_details['quote_option_save'] , JSON_FORCE_OBJECT),
				'quote_option_value_json' => array_filter($result),
				'href' => $this->url->link('product/product', 'product_id=' . $product_details['product_id']),
			);
		}
		      
		$data['heading_title'] = $this->language->get('heading_title');
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$this->load->model('catalog/quote_data_page');
			$this->load->model('tool/upload');
			$upload_info = $this->model_tool_upload->getUploadByCode($this->request->post['quote_option_doc']);
			if ($upload_info) {
				$value = $upload_info['code'];
			} else { 
				$value = '';
			}
			$this->model_catalog_quote_data_page->addQuoteData($this->request->post,$value,$data['product_data_info']);
			unset($this->session->data['product_quote_data']);
			$this->response->redirect($this->url->link('common/home'));
		}
		
		if (isset($this->error['error_name'])) {
			$data['error_name'] = $this->error['error_name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['error_city'])) {
			$data['error_city'] = $this->error['error_city'];
		} else {
			$data['error_city'] = '';
		}
		
		if (isset($this->error['error_email'])) {
			$data['error_email'] = $this->error['error_email'];
		} else {
			$data['error_email'] = '';
		}
		
		if (isset($this->error['error_phone'])) {
			$data['error_phone'] = $this->error['error_phone'];
		} else {
			$data['error_phone'] = '';
		}

		if (isset($this->error['error_file'])) {
			$data['error_file'] = $this->error['error_file'];
		} else {
			$data['error_file'] = '';
		}

		if (isset($this->error['error_comment'])) {
			$data['error_comment'] = $this->error['error_comment'];
		} else {
			$data['error_comment'] = '';
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['city'])) {
			$data['city'] = $this->request->post['city'];
		} else {
			$data['city'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['phone'])) {
			$data['phone'] = $this->request->post['phone'];
		} else {
			$data['phone'] = '';
		}
		if (isset($this->request->post['comment'])) {
			$data['comment'] = $this->request->post['comment'];
		} else {
			$data['comment'] = '';
		}
		$data['action'] = $this->url->link('product/quote_data_page', true);
        $data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		if($thisvar < 2200){
			$file = '/template/product/quote_data_page.tpl';
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template').$file)) {
				$this->response->setOutput($this->load->view($this->config->get('config_template').$file, $data));
			} else {
				$this->response->setOutput($this->load->view('default'.$file, $data));
			}
		}else{
			$this->response->setOutput($this->load->view('product/quote_data_page', $data));
		}
	}
	public function setQuoteOptionData(){
		$json  = array();
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$product_id = isset($this->request->post['product_id']) ? $this->request->post['product_id'] : 0;
		$product_info = $this->model_catalog_product->getProduct($product_id);
		if($product_info){
			if (isset($this->request->post['in_quantity'])) {
				$quantity = (int)$this->request->post['in_quantity'];
			} else {
				$quantity = 1;
			}
			if (!isset($this->session->data['product_quote_data'])) {
				$this->session->data['product_quote_data'] = array();
			}
			$this->load->model('tool/upload');
			$product_info_data = array(
				'product_id' => $product_info['product_id'],
				'name' => $product_info['name'],
				'model' => $product_info['model'],
				'quantity' => $quantity,
				'price'    		   => $product_info['price'],
				'image' =>  $product_info['image'],
				'quote_option_save' => isset($this->request->post['quote_option_save']) ? $this->request->post['quote_option_save'] : array(),
			);
			$this->session->data['product_quote_data'][$product_id] = $product_info_data;
		}
		$json['quote_product_data'] = $this->session->data['product_quote_data'];
		$json['success'] = true;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function validate() {
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
			$this->error['error_name'] = $this->language->get('text_error_name');
		}
		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['error_email'] = $this->language->get('text_error_email');
		}
		if ((utf8_strlen($this->request->post['phone']) < 3) || (utf8_strlen($this->request->post['phone']) > 32)) {
			$this->error['error_phone'] = $this->language->get('text_error_phone');
		}
		if ((utf8_strlen($this->request->post['city']) < 3) || (utf8_strlen($this->request->post['city']) > 32)) {
			$this->error['error_city'] = $this->language->get('text_error_city');
		}
		if ((utf8_strlen($this->request->post['quote_option_doc']) < 3) || (utf8_strlen($this->request->post['quote_option_doc']) > 3000)) {
			$this->error['error_file'] = $this->language->get('text_error_file');
		}
		if ((utf8_strlen($this->request->post['comment']) < 3) || (utf8_strlen($this->request->post['comment']) > 32)) {
			$this->error['error_comment'] = $this->language->get('text_error_comment');
		}
		return !$this->error;
	}

	public function deleteproductajax(){
		$json = array();
		$this->load->language('product/quote_data_page');
		$error_product_delete = $this->language->get('error_product_delete');
		$error_wrong_msg = $this->language->get('error_wrong_msg');
		$this->load->model('catalog/quote_data_page');
		if(isset($this->request->post['product_id'])){
			unset($this->session->data['product_quote_data'][$this->request->post['product_id']]);
		}
		if (isset($this->request->post['product_id']) && $this->request->post['product_id']){
			$this->model_catalog_quote_data_page->deleteproduct($this->request->post['product_id']);
			$json['success'] = $error_product_delete;
		}else{
			$json['error'] = $error_wrong_msg;
		}

		$this->response->addHeader('Content-Type: application/json');
   		$this->response->setOutput(json_encode($json));
	}
}