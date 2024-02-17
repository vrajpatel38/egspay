<?php
class ControllerResellerReseller extends Controller {
	private $error = array();

	public function index() {
		
		
		$this->load->language('reseller/reseller');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('reseller/reseller');

		$this->getList();
	}

	public function add() {
		$this->load->language('sale/order');
		$this->load->language('reseller/reseller');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('reseller/reseller');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_reseller_reseller->addResellerFromCustomer($this->request->post['customer_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function enable() {
		$this->load->model('reseller/reseller');
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['customer_id'])) {
			$this->model_reseller_reseller->enableReseller($this->request->get['customer_id']);
			$this->response->redirect($this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . $url, true));
		} else {
			$this->response->redirect($this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . $url, true));
		}
	}
	public function disable() {
		$this->load->model('reseller/reseller');
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		if (isset($this->request->get['customer_id'])) {

			$this->model_reseller_reseller->disableReseller($this->request->get['customer_id']);

			$this->response->redirect($this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . $url, true));
		} else {
			$this->response->redirect($this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . $url, true));
		}
	}
	public function edit() {
		$this->load->language('sale/order');
		$this->load->language('reseller/reseller');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('reseller/reseller');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

			$this->model_reseller_reseller->editReseller($this->request->get['customer_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group'])) {
				$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('reseller/reseller');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('reseller/reseller');

		// single deletion
		if (isset($this->request->get['customer_id'])) {
			$this->model_reseller_reseller->deleteReseller($this->request->get['customer_id']);
		}

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_id) {
				$this->model_reseller_reseller->deleteReseller($customer_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_ip'])) {
				$url .= '&filter_ip=' . $this->request->get['filter_ip'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = '';
		}

		if (isset($this->request->get['filter_whatsapp'])) {
			$filter_whatsapp = $this->request->get['filter_whatsapp'];
		} else {
			$filter_whatsapp = '';
		}

		if (isset($this->request->get['filter_zone_id'])) {
			$filter_zone_id = $this->request->get['filter_zone_id'];
		} else {
			$filter_zone_id = '';
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

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
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_whatsapp'])) {
			$url .= '&filter_whatsapp=' . urlencode(html_entity_decode($this->request->get['filter_whatsapp'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_zone_id'])) {
			$url .= '&filter_zone_id=' . urlencode(html_entity_decode($this->request->get['filter_zone_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . $url, true),
		);

		$data['add'] = $this->url->link('reseller/reseller/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('reseller/reseller/delete', 'token=' . $this->session->data['token'] . $url, true);

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$data['customers'] = array();

		$filter_data = array(
			'filter_name' => $filter_name,
			'filter_email' => $filter_email,
			'filter_customer_group_id' => $filter_customer_group_id,
			'filter_whatsapp' => $filter_whatsapp,
			'filter_zone_id' => $filter_zone_id,
			'filter_status' => $filter_status,
			'filter_date_added' => $filter_date_added,
			'filter_ip' => $filter_ip,
			'sort' => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin'),
		);

		$customer_total = $this->model_reseller_reseller->getTotalReseller($filter_data);

		$results = $this->model_reseller_reseller->getResellers($filter_data);

		foreach ($results as $result) {
			$login_info = $this->model_reseller_reseller->getTotalLoginAttempts($result['email']);

			if ($login_info && $login_info['total'] >= $this->config->get('config_login_attempts')) {
				$unlock = $this->url->link('reseller/reseller/unlock', 'token=' . $this->session->data['token'] . '&email=' . $result['email'] . $url, true);
			} else {
				$unlock = '';
			}

			$store_data = array();

			$store_data[] = array(
				'name' => $this->config->get('config_name'),
				'href' => $this->url->link('reseller/reseller/login', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . '&store_id=0', true),
			);

			foreach ($stores as $store) {
				$store_data[] = array(
					'name' => $store['name'],
					'href' => $this->url->link('reseller/reseller/login', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . '&store_id=' . $result['store_id'], true),
				);
			}

			$waMsg = "Halo " . ucfirst($result['name']) . " kami dari " . html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8') . " " . HTTPS_CATALOG;


			$data['resellers'][] = array(
				'customer_id' => $result['customer_id'],
				'customer_id' 			  => $result['customer_id'],
				'name' => $result['name'],
				'email' => $result['email'],
				'customer_group' => $result['customer_group'],
				'lampiran' => $this->getUploadLink($result['lampiran']),
				'text_status' => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'status' => $result['status'],
				'date_added' => date('d M Y', strtotime($result['date_added'])),
				'send_wa' => 'https://api.whatsapp.com/send?phone=' . preg_replace('/\s+/', '', $result['telephone']) . '&text=' . urlencode($waMsg),
				'edit' => $this->url->link('reseller/reseller/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
				'enable' => $this->url->link('reseller/reseller/enable', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
				'disable' => $this->url->link('reseller/reseller/disable', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
				'delete' => $this->url->link('reseller/reseller/delete', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
			);
		}

		$data['token'] = $this->session->data['token'];

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
			$data['selected'] = (array) $this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_whatsapp'])) {
			$url .= '&filter_whatsapp=' . urlencode(html_entity_decode($this->request->get['filter_whatsapp'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_zone_id'])) {
			$url .= '&filter_zone_id=' . urlencode(html_entity_decode($this->request->get['filter_zone_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_email'] = $this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, true);
		$data['sort_customer_group'] = $this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . '&sort=c.customer_group_id' . $url, true);
		$data['sort_status'] = $this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . '&sort=c.status' . $url, true);
		$data['sort_date_added'] = $this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . '&sort=c.date_added' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_whatsapp'])) {
			$url .= '&filter_whatsapp=' . urlencode(html_entity_decode($this->request->get['filter_whatsapp'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_zone_id'])) {
			$url .= '&filter_zone_id=' . urlencode(html_entity_decode($this->request->get['filter_zone_id'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_email'] = $filter_email;
		$data['filter_whatsapp'] = $filter_whatsapp;
		$data['filter_zone_id'] = $filter_zone_id;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_status'] = $filter_status;
		$data['filter_ip'] = $filter_ip;
		$data['filter_date_added'] = $filter_date_added;

		$data['customer_groups'] = $this->model_reseller_reseller->getCustomerGroups();

		$data['zones'] = $this->model_reseller_reseller->getZoneResellers();

		//$data['default_customer_group_id'] = $this->config->get('config_customer_group_id');

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reseller/reseller_list', $data));
	}

	protected function getForm() {
		$this->document->addScript('view/javascript/selectpicker/js/bootstrap-select.min.js');
		$this->document->addStyle('view/javascript/selectpicker/css/bootstrap-select.min.css');

		$data['text_form'] = !isset($this->request->get['customer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['customer_id'])) {
			$data['customer_id'] = $this->request->get['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}

		if (isset($this->error['customer_id'])) {
			$data['error_customer_id'] = $this->error['customer_id'];
		} else {
			$data['error_customer_id'] = '';
		}

		if (isset($this->error['ktp'])) {
			$data['error_ktp'] = $this->error['ktp'];
		} else {
			$data['error_ktp'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['address'])) {
			$data['error_address'] = $this->error['address'];
		} else {
			$data['error_address'] = array();
		}

		if (isset($this->error['zone_id'])) {
			$data['error_zone_id'] = $this->error['zone_id'];
		} else {
			$data['error_zone_id'] = '';
		}

		if (isset($this->error['country_id'])) {
			$data['error_country_id'] = $this->error['country_id'];
		} else {
			$data['error_country_id'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . $url, true),
		);

		if (!isset($this->request->get['customer_id'])) {
			$data['action'] = $this->url->link('reseller/reseller/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('reseller/reseller/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('reseller/reseller', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['customer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$reseller_info = $this->model_reseller_reseller->getReseller($this->request->get['customer_id']);

			
		}

		$data['customer_groups'] = $this->model_reseller_reseller->getCustomerGroups();

		$data['customers'] = $this->model_reseller_reseller->getCustomersNotReseller($this->request->get['customer_id'] ?? 0);

		if (isset($this->request->post['customer_group_id'])) {
			$data['customer_group_id'] = $this->request->post['customer_group_id'];
		} elseif (!empty($reseller_info)) {
			$data['customer_group_id'] = $reseller_info['customer_group_id'];
		} else {
			$data['customer_group_id'] = '';
		}

		if (isset($this->request->post['customer_id'])) {
			$data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($reseller_info)) {
			$data['customer_id'] = $reseller_info['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

		if (isset($this->request->post['facebook'])) {
			$data['facebook'] = $this->request->post['facebook'];
		} elseif (!empty($reseller_info)) {
			$data['facebook'] = $reseller_info['social_media_facebook'];
		} else {
			$data['facebook'] = '';
		}

		if (isset($this->request->post['twitter'])) {
			$data['twitter'] = $this->request->post['twitter'];
		} elseif (!empty($reseller_info)) {
			$data['twitter'] = $reseller_info['social_media_twitter'];
		} else {
			$data['twitter'] = '';
		}

		if (isset($this->request->post['instagram'])) {
			$data['instagram'] = $this->request->post['instagram'];
		} elseif (!empty($reseller_info)) {
			$data['instagram'] = $reseller_info['social_media_instagram'];
		} else {
			$data['instagram'] = '';
		}

		if (isset($this->request->post['customer_id'])) {
			$data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($reseller_info)) {
			$data['customer_id'] = $reseller_info['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

		//echo $data['customer_id'];die;

		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($reseller_info)) {
			$data['firstname'] = $reseller_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($reseller_info)) {
			$data['lastname'] = $reseller_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->request->post['address'])) {
			$data['address'] = $this->request->post['address'];
		} elseif (!empty($reseller_info)) {
			$data['address'] = $reseller_info['address'];
		} else {
			$data['address'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($reseller_info)) {
			$data['email'] = $reseller_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($reseller_info)) {
			$data['telephone'] = $reseller_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['ktp'])) {
			$data['ktp'] = $this->request->post['ktp'];
		} elseif (!empty($reseller_info)) {
			$data['ktp'] = $reseller_info['no_nik'];
		} else {
			$data['ktp'] = '';
		}

		if (isset($this->request->post['lampiran'])) {
			$data['lampiran'] = $this->request->post['lampiran'];
		} elseif (!empty($reseller_info)) {
			$data['lampiran'] = $reseller_info['lampiran'];
		} else {
			$data['lampiran'] = '';
		}

		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = $this->request->post['country_id'];
		} elseif (!empty($reseller_info)) {
			$data['country_id'] = $reseller_info['country_id'];
		} else {
			$data['country_id'] = '';
		}

		if (isset($this->request->post['zone_id'])) {
			$data['zone_id'] = $this->request->post['zone_id'];
		} elseif (!empty($reseller_info)) {
			$data['zone_id'] = $reseller_info['zone_id'];
		} else {
			$data['zone_id'] = '';
		}

		$data['id_card_entry'] = $this->config->get('hpwd_reseller_management_id_card_entry');



		$this->load->model('localisation/country');

		$data['countries'] = [];
		$countries_code = [];
		// if exist HPSB
		if($this->config->get('module_bundle_city_id')) {
			$this->load->model('localisation/country');
			$data['countries'] = $this->model_localisation_country->getCountries();
		} else {
			foreach ($this->config->get('hpwd_reseller_management_country') as $country) {

				$data_country = explode("--", $country);
				$countries_code[] = $data_country[1];
			}
			$countries = $this->model_localisation_country->getCountries();
			foreach ($countries as &$country) {
				if (in_array($country['iso_code_2'],$countries_code)) {
					$data['countries'][] = $country;
				}
			}
		}

		

	//	$countries = $this->model_localisation_country->getCountries();

	//	$data['countries'] = array();

		$availabel_countries = $this->config->get('hpwd_reseller_management_country');
		$data['availabel_countries'] = [];

		foreach ($availabel_countries as $availabel_country) {
			$availabel_country = explode("--", $availabel_country);
			$data['availabel_countries'][] = $availabel_country[1];
		}

		// foreach ($countries as $country) {
		// 	if (in_array($country['iso_code_2'], $data['availabel_countries'])) {
		// 		$data['countries'][] = $country;
		// 	}
		// }

		$data['availabel_countries'] = json_encode($data['availabel_countries']);

		if (isset($this->request->post['default_country'])) {
			$data['default_country'] = $this->request->post['default_country'];
		} elseif (!empty($reseller_info)) {
			$data['default_country'] = $reseller_info['default_country'];
		} else {
			$data['default_country'] = '';
		}

		$data['upload_link'] = $data['lampiran'] ? $this->getUploadLink($data['lampiran']) : array();

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($reseller_info)) {
			$data['status'] = $reseller_info['status'];
		} else {
			$data['status'] = true;
		}

		// custom attachment from translation
		$data['social_media_profile'] = $this->config->get('hpwd_reseller_management_social_media_profile');

		$data['help_lampiran'] = $this->config->get('hpwd_reseller_management_text_attachment_' . $this->config->get('config_language_id'));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('reseller/reseller_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'reseller/reseller')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		// if (isset($this->request->post['firstname']) && (utf8_strlen($this->request->post['firstname']) < 1)) {
		// 	$this->error['firstname'] = $this->language->get('error_firstname');
		// }

		// if (isset($this->request->post['lastname']) && (utf8_strlen($this->request->post['lastname']) < 1)) {
		// 	$this->error['lastname'] = $this->language->get('error_lastname');
		// }

		// if ((utf8_strlen($this->request->post['email']) < 1)) {
		// 	$this->error['email'] = $this->language->get('error_email');
		// }

		if ($this->config->get('hpwd_reseller_management_id_card_entry') == 2) {
			if ((utf8_strlen($this->request->post['ktp']) < 10)) {
				$this->error['ktp'] = $this->language->get('error_input');
			}
		}

		if ((isset($this->request->post['country_id']) && ($this->request->post['country_id'] < 1))) {
			$this->error['country_id'] = $this->language->get('error_input');
		}

		if ((isset($this->request->post['zone_id']) && ($this->request->post['zone_id'] === ""))) {
			$this->error['zone_id'] = $this->language->get('error_input');
		}

		if (((isset($this->request->post['customer_id']) && ($this->request->post['customer_id'] < 1))) || !isset($this->request->post['customer_id'])) {
			$this->error['customer_id'] = $this->language->get('error_input');
		}

		// 		if ((utf8_strlen($this->request->post['facebook']) < 1)) {
		// 			$this->error['facebook'] = $this->language->get('error_input');
		// 		}
		// 		if ((utf8_strlen($this->request->post['twitter']) < 1)) {
		// 			$this->error['twitter'] = $this->language->get('error_input');
		// 		}
		// 		if ((utf8_strlen($this->request->post['instagram']) < 1)) {
		// 			$this->error['instagram'] = $this->language->get('error_input');
		// 		}
		if ((utf8_strlen($this->request->post['address']) < 1)) {
			$this->error['address'] = $this->language->get('error_address');
		}

		//$reseller_info = $this->model_reseller_reseller->getCustomerReseller($this->request->post['customer_id']);

		// $reseller_info = $this->model_reseller_reseller->getCustomerByEmail($this->request->post['email']);

		// if (!isset($this->request->post['customer_id'])) {
		// 	if ($reseller_info) {
		// 		$this->error['warning'] = $this->language->get('error_exists_email');
		// 	}
		// } else {
		// 	if ($reseller_info && ($this->request->post['customer_id'] != $reseller_info['customer_id'])) {
		// 		$this->error['warning'] = $this->language->get('error_exists_email');
		// 	}
		//}

		// if ((utf8_strlen($this->request->post['telephone']) < 3)) {
		// 	$this->error['telephone'] = $this->language->get('error_input');
		// }

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'reseller/reseller')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email']) || isset($this->request->get['filter_whatsapp'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_email'])) {
				$filter_email = $this->request->get['filter_email'];
			} else {
				$filter_email = '';
			}

			if (isset($this->request->get['filter_whatsapp'])) {
				$filter_whatsapp = $this->request->get['filter_whatsapp'];
			} else {
				$filter_whatsapp = '';
			}

			$this->load->model('reseller/reseller');

			$filter_data = array(
				'filter_name' => $filter_name,
				'filter_email' => $filter_email,
				'filter_whatsapp' => $filter_whatsapp,
				'start' => 0,
				'limit' => 5,
			);

			$results = $this->model_reseller_reseller->getResellers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'reseller' => $result['customer_id'],
					'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'email' => $result['email'],
					'telephone' => $result['default_country'] . ' ' .  $result['telephone'],
					// 'address'           => $result['address']
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function upload() {
		$this->load->language('reseller/reseller');

		$json = array();

		if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
			// Sanitize the filename
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

			// Validate the filename length
			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// Allowed file extension types
			$allowed = array();

			$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));

			$filetypes = explode("\n", $extension_allowed);

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Allowed file mime types
			$allowed = array();

			$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));

			$filetypes = explode("\n", $mime_allowed);

			foreach ($filetypes as $filetype) {
				$allowed[] = trim($filetype);
			}

			if (!in_array($this->request->files['file']['type'], $allowed)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Check to see if any PHP files are trying to be uploaded
			$content = file_get_contents($this->request->files['file']['tmp_name']);

			if (preg_match('/\<\?php/i', $content)) {
				$json['error'] = $this->language->get('error_filetype');
			}

			// Return any upload error
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json) {
			$file = $filename . '.' . md5(mt_rand());

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);

			// Hide the uploaded file name so people can not link to it directly.
			$this->load->model('tool/upload');

			$json['code'] = $this->model_tool_upload->addUpload($filename, $file);

			$json['success'] = $filename;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function getUploadLink($code) {
		$this->load->model('tool/upload');
		$lampiran = array();
		if ($code) {
			$upload_info = $this->model_tool_upload->getUploadByCode($code);
			if ($upload_info) {
				$lampiran = array(
					'value' => $upload_info['name'],
					'href' => $this->url->link('tool/upload/download', 'token=' . $this->session->data['token'] . '&code=' . $upload_info['code'], 'SSL'),
				);
			}
		}
		return $lampiran;
	}
}
