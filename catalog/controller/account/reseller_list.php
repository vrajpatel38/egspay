<?php

class ControllerAccountResellerList extends Controller {

    public function index() {

        if(!$this->config->get('hpwd_reseller_management_status')){
			$this->response->redirect($this->url->link('common/home', '', true));
		}
		
		$this->load->language('account/reseller_list');

		$data['heading_title'] = $this->language->get('heading_title');
       
		$this->document->addScript('admin/view/javascript/selectpicker/js/bootstrap-select.min.js');
		$this->document->addStyle('admin/view/javascript/selectpicker/css/bootstrap-select.min.css');
        $this->document->addStyle('catalog/view/javascript/hprm.css');

        $language_id = $this->config->get('config_language_id');

        $data['text_heading'] = $this->config->get('hpwd_reseller_management_heading_resellerlist_' . $language_id);
        $data['text_instruction'] = $this->config->get('hpwd_reseller_management_instruction_resellerlist_' . $language_id);

        $this->document->setTitle($data['heading_title']);

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => false,
		];

		$data['breadcrumbs'][] = [
			'text' => $data['heading_title'],
			'href' => $this->url->link('account/reseller_list', '', 'SSL'),
			'separator' => $this->language->get('text_separator'),
		];

		$this->load->model('account/reseller');

		$this->load->model('account/customer_group');

		$data['zones'] = $this->model_account_reseller->getZoneResellers();

		$reseller_groups = $this->config->get('hpwd_reseller_management_group');

		$data['reseller_groups'] = array();

		foreach ($reseller_groups as $key => $group_id) {
			$data['reseller_groups'][] = $this->model_account_customer_group->getCustomerGroup($group_id);
		}

		$data['reseller_list'] = $this->load(true);

		if (isset($this->request->get['t'])) {
			$data['filter_type'] = $this->request->get['t'];
		} else {
			$data['filter_type'] = '';
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/reseller_list', $data));
	}


	public function load($return = false){
		$this->load->language('account/reseller_list');

		$this->load->model('account/reseller');
		$this->load->model('account/customer_group');

		$limit = 9;

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['filter_zone_id'])) {
			$filter_zone_id = $this->request->get['filter_zone_id'];
		} else {
			$filter_zone_id = '';
		}

		if (isset($this->request->get['t'])) {
			$filter_type = $this->request->get['t'];
		} else {
			$filter_type = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_type_id'])) {
			$filter_type_id = $this->request->get['filter_type_id'];
		} else {
			$filter_type_id = '';
		}


		$filter_data = array(
			'filter_name'              => $filter_name,
			'filter_zone_id'           => $filter_zone_id,
			'filter_type_id'           => $filter_type_id,
			'filter_type'              => $filter_type,
			'start'                    => ($page - 1) * $limit,
			'limit'                    => $limit
		);

		$order_total = $this->model_account_reseller->getTotalResellers($filter_data);

		$results = $this->model_account_reseller->getResellers($filter_data);

		
		$data['resellers'] = array();

		foreach ($results as $result) {

			$customer_group = $this->model_account_customer_group->getCustomerGroup($result['customer_group_id']);
			
			$whatsapp = '';
			
			if(!empty($result['default_country']) && !empty($result['telephone'])){
				$whatsapp = 'https://api.whatsapp.com/send/?phone=' . preg_replace('/\s+/', '', $result['telephone']);
			}

			$instagram = '';
			
			if(!empty($result['social_media_instagram'])){
				$instagram = 'https://instagram.com/' . $result['social_media_instagram'];
			}
		
			$data['resellers'][] = array(
				'reseller_id'   => $result['customer_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'whatsapp'     => $whatsapp,
				'instagram' => $instagram,
				'instagram_name' => '@'.$result['social_media_instagram'],
				'zone' => $result['zone_name'],
				'country' => $result['country_name'],
				'type' => sprintf($this->language->get('text_reseller_type'), $customer_group['name']),
				
			);
		}


		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_zone_id'])) {
			$url .= '&filter_zone_id=' . urlencode(html_entity_decode($this->request->get['filter_zone_id'], ENT_QUOTES, 'UTF-8'));
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('account/reseller_list/load', $url . 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($order_total - $limit)) ? $order_total : ((($page - 1) * $limit) + $limit), $order_total, ceil($order_total / $limit));
	
		if($return){
			return $this->load->view('account/reseller_list_template', $data);
		}
		
	    $this->response->setOutput($this->load->view('account/reseller_list_template', $data));
	}


}
