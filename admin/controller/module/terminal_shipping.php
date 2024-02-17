<?php
class ControllerModuleTerminalShipping extends Controller {
	private $error = array();

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->library('terminal_africa');
	}

	public function index() {
		$this->load->language('module/terminal_shipping');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('terminal_shipping', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_public_key'] = $this->language->get('entry_public_key');
		$data['entry_secret_key'] = $this->language->get('entry_secret_key');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/terminal_shipping', 'token=' . $this->session->data['token'], true)
		);

		$data['token'] = 'token=' . $this->session->data['token'];

		$data['action'] = $this->url->link('module/terminal_shipping', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], true);

		if (isset($this->request->post['account_status'])) {
			$data['account_status'] = $this->request->post['account_status'];
		} else {
			$data['account_status'] = $this->config->get('account_status');
		}

		$terminal_africa_auth_settings = $this->config->get('terminal_africa_auth_settings');
		$auth_settings = json_decode($terminal_africa_auth_settings);
		$terminal_merchant_user_id = isset($auth_settings->user_id) ? $auth_settings->user_id : '';

		$wallet_balance = $this->getWalletBalance($terminal_merchant_user_id);

		$data['wallet_data'] = [];
		$other_data = false;
		//check if code is 200
		if ($wallet_balance['code'] == 200) {
		    $data['wallet_data'][] = [
		        'balance' => $wallet_balance['data']->amount,
		        'currency' => $wallet_balance['data']->currency
		    ];
		    $data['other_data'] = $wallet_balance['data'];
		} else {
		    $data['wallet_data'][] = [
		        'balance' => 0,
		        'currency' => 'NGN'
		    ];
		}

		$terminal_africa_status = $this->config->get('terminal_africa_status');
		if($terminal_africa_status){
			$data_address = $this->config->get('terminal_africaaddressdata_merchant_address');
			$data_merchant_address = json_decode($data_address);
			$data['merchant_address'] = json_decode(json_encode($data_merchant_address), true);
			// echo "<pre>";print_r($data['merchant_address']);echo "</pre>";die;

			$get_shippingdata = $this->config->get('terminal_africa_auth_settings');
			$shipping_dataarray = json_decode($get_shippingdata);
			$data['pk'] = $shipping_dataarray->public_key ? $shipping_dataarray->public_key : '';
			$data['sk'] = $shipping_dataarray->secret_key ? $shipping_dataarray->secret_key : '';

			$carriers = $this->getTerminalCarriers('domestic');
			$internationalCarriers = $this->getTerminalCarriers('international');
			$regionalCarriers = $this->getTerminalCarriers('regional');
			$userCarriersD = $this->getUserCarriers('domestic');
			$userCarriersI = $this->getUserCarriers('international');
			$userCarriersR = $this->getUserCarriers('regional');
			
			$data['carriersData'] = [
			    'domestic' => [
			        'title' => 'Domestic Carriers',
			        'carriers' => $carriers['data']->carriers,
			        'userCarriers' => $userCarriersD
			    ],
			    'international' => [
			        'title' => 'International Carriers',
			        'carriers' => $internationalCarriers['data']->carriers,
			        'userCarriers' => $userCarriersI
			    ],
			    'regional' => [
			        'title' => 'Regional Carriers',
			        'carriers' => $regionalCarriers['data']->carriers,
			        'userCarriers' => $userCarriersR
			    ]
			];

		}

		$data['packaging_id'] = $this->config->get('terminal_packaging_default_packaging_id') ? 'yes' : 'no';
		$data['terminal_percentage_custom_price_mark_up'] = $this->config->get('terminal_percentage_custom_price_mark_up') ? $this->config->get('terminal_percentage_custom_price_mark_up') : '';

		$data['countries'] = $this->get_countries();
		$data['states'] = $this->get_states_show();

		$this->document->addStyle('view/javascript/jquery/terminalafrica/terminalafrica_shipping.css');
		$this->document->addStyle('view/javascript/jquery/izitoast/iziToast.min.css');
		$this->document->addScript('view/javascript/jquery/izitoast/iziToast.min.js');
		$this->document->addStyle('view/javascript/jquery/sweetalert/sweetalert2.min.css');
		$this->document->addScript('view/javascript/jquery/sweetalert/sweetalert2.min.js');
		
		$data['bank_icon_orange'] = HTTPS_SERVER . 'view/image/terminal_shipping_image/Bank-Icon-Orange.png';
		$data['bank_check'] = HTTPS_SERVER . 'view/image/terminal_shipping_image/check.png';
		$data['walleticon'] = HTTPS_SERVER . 'view/image/terminal_shipping_image/WalletIcon.png';
		// $data['bank_icon_orange'] = HTTPS_SERVER . 'view/image/terminal_shipping_image/Bank-Icon-Orange.png';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/terminal_shipping', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/terminal_shipping')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function save_terminal_custom_price_mark_up()
    {
        //data
        $percentage =isset($this->request->post['percentage']) ? $this->request->post['percentage'] : 0.1;
        $this->load->model('setting/setting');
        $data = array(
	        "terminal_percentage_custom_price_mark_up" => $percentage
	    );
        $this->model_setting_setting->editSetting('terminal_percentage', $data);
        //save custom price mark up
        // update_option('terminal_custom_price_mark_up', $percentage);
        //check if percentage is empty
        if (empty($percentage)) {
            //return error
            $response = [
                'code' => 200,
                'message' => 'Default price mark up saved successfully',
            ];
            header('Content-Type: application/json');
	    	echo json_encode($response);
	    exit;
        }
        //return
        $response = [
            'code' => 200,
            'message' => 'Custom price mark up saved successfully',
        ];
        header('Content-Type: application/json');
	    echo json_encode($response);
	    exit;
    }

	public function terminal_africa_auth() {
		// echo "<pre>";print_r($this->request->post);echo "</pre>";die;
	    // $nonce = isset($this->request->post['nonce']) ? sanitize_text_field($this->request->post['nonce']) : '';

	    // if (empty($nonce) || !isset($this->request->post['terminal_africa_nonce']) || $nonce !== $this->request->post['terminal_africa_nonce']) {
	    //     $response = [
	    //         'code' => 400,
	    //         'message' => 'Wrong nonce, please refresh the page and try again'
	    //     ];
	    //     echo json_encode($response);
	    //     exit;
	    // }
	    
	    $public_key = isset($this->request->post['public_key']) ? ($this->request->post['public_key']) : '';
	    $secret_key = isset($this->request->post['secret_key']) ? ($this->request->post['secret_key']) : '';
	    
	    if (empty($public_key) || empty($secret_key)) {
	        $response = [
	            'code' => 400,
	            'message' => 'Please enter your public key and secret key'
	        ];
	        echo json_encode($response);
	        exit;
	    }
	    
	    // validate keys
	    $validate_keys = $this->terminal_africa->checkKeys($public_key, $secret_key);
	    $endpoint = $validate_keys["endpoint"];
	    $authorization_header = 'Bearer ' . $secret_key;
	   
	    // echo "<pre>";print_r($settings);echo "</pre>";die;
	    // Simulating Requests::get() using cURL
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $endpoint . "users/secrete");
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: $authorization_header"));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($ch);
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    
	    // check if response is 200
	    $body = json_decode($response);
	    if ($http_code == 200) {
	        // save keys
	        $settings = array(
	            'public_key' => $public_key,
	            'secret_key' => $secret_key,
	            'user_id' => $body->data->user->user_id,
	            'others' => $body->data
	        );
	        // Update options (assuming you have the appropriate functions for this)
	        // update_option('terminal_africa_settings', $settings);
	        // update_option('terminal_africa_merchant_id', $body->data->user->user_id);
	        
	        // check if metadata exist
	        // if (isset($body->data->user->metadata)) {
	        //     $isoCode = $body->data->user->country;
	        //     update_option('terminal_default_currency_code', ['currency_code' => $body->data->user->metadata->default_currency, 'isoCode' => $isoCode]);
	        // }
	        // echo "<pre>";print_r($settings);echo "</pre>";die;	
	        // get shipping settings

	        $jsonData = json_encode($settings);
	        $data = array(
	        	"terminal_africa_auth_settings" => $jsonData
	        );
	        $this->load->model('setting/setting');
	        $this->model_setting_setting->editSetting('terminal_africa_auth', $data);

	        // $settings = get_option('woocommerce_terminal_delivery_settings');
	        // $settings['enabled'] = 'yes';
	        
	        // Update shipping settings (assuming you have the appropriate functions for this)
	        // update_option('woocommerce_terminal_delivery_settings', $settings);
	        
	        // log plugin data
	        // TerminalLogHandler::terminalLoggerHandler('plugin/activate');
	        
	        // return
	        $response = [
	            'code' => 200,
	            'message' => 'Authentication successful'
	        ];
	        echo json_encode($response);
	    } else {
	        $response = [
	            'code' => 400,
	            'message' => $body->message
	        ];
	        echo json_encode($response);
	    }
	}

	public function getWalletBalance($user_id, $force = false)
	{
	    // If session is not started, start it
	    if (session_status() == PHP_SESSION_NONE) {
	        session_start();
	    }

	    // Check if data is in session and force flag is false
	    if (isset($this->session->data['wallet_balance']) && !$force) {
	        return [
	            'code' => 200,
	            'message' => 'success',
	            'data' => $this->session->data['wallet_balance'],
	            'from' => 'session',
	        ];
	    }

	    // Check if self::$skkey is set
	    // if (!self::$skkey) {
	    //     return [
	    //         'code' => 404,
	    //         'message' => "Invalid API Key",
	    //         'data' => [],
	    //     ];
	    // }

	    // Sanitize user_id
	    // $user_id = sanitize_text_field($user_id);

	    // Build the query string
	    $query = http_build_query([
	        'user_id' => $user_id,
	    ]);

	    // Create cURL handle
	    $ch = curl_init();

	    // Set cURL options
	    // $url = self::$enpoint . 'users/wallet?' . $query;
	    $url = 'https://api.terminal.africa/v1/users/wallet?' . $query;
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, [
	        'Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H',
	    ]);

	    // Execute cURL request
	    $response = curl_exec($ch);
	    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	    // Close cURL handle
	    curl_close($ch);

	    // Check if response is successful
	    if ($httpCode == 200) {
	        // Decode response JSON
	        $body = json_decode($response);
	        // Return wallet balance
	        $data = $body->data;
	        // Save data to session
	        $this->session->data['wallet_balance'] = $data;
	        // Return data
	        return [
	            'code' => 200,
	            'message' => 'success',
	            'data' => $data,
	        ];
	    } else {
	        // Decode error response JSON
	        $body = json_decode($response);
	        // Return error message
	        return [
	            'code' => $httpCode,
	            'message' => $body->message,
	            'data' => [],
	        ];
	    }
	}

	//update_user_carrier_terminal
    public function update_user_carrier_terminal() {
        //check if session is started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // $nonce = sanitize_text_field($_POST['nonce']);
        // if (!wp_verify_nonce($nonce, 'terminal_africa_nonce')) {
        //     wp_send_json([
        //         'code' => 400,
        //         'message' => 'Wrong nonce, please refresh the page and try again'
        //     ]);
        // }
        //data
        $carriers = $this->request->post['carrierObj'] ? $this->request->post['carrierObj'] : '';
        $status =   $this->request->post['status'] ? $this->request->post['status'] : '' ;
        //sanitize object data
        foreach ($carriers as $key => $value) {
            $carriers[$key] = $value;
        }
        //check if carriers is empty
        if (empty($carriers)) {
            //return error
            wp_send_json([
                'code' => 400,
                'message' => 'Please select at least one carrier',
            ]);
        }
        $arrayData = [
            'carriers' => [
                [
                    'carrier_id' => $carriers['id'],
                    'domestic' => (bool)$carriers['domestic'],
                    'international' => (bool)$carriers['international'],
                    'regional' => (bool)$carriers['regional'],
                ]
            ]
        ];
        //check if status is enabled
        if ($status == 'enabled') {
            //enable carrier
            $enable_carrier = $this->enableSingleCarriers($arrayData);
            //check if carrier is enabled
            if ($enable_carrier['code'] == 200) {
                //clear cache
                unset($this->session->data['terminal_carriers_data']);
                //terminal_africa_carriers
                unset($this->session->data['terminal_africa_carriers']);
                //return
                $response = [
                    'code' => 200,
                    'message' => 'Carrier enabled successfully',
                ];
                // echo json_encode($response);
            } else {
                //return error
                $response = [
                    'code' => 400,
                    'message' => $enable_carrier['message'],
                ];
            	// echo json_encode($response);
            }
            header('Content-Type: application/json');
			echo json_encode($response);
			exit;
        } else {
            //disable carrier
            $disable_carrier = $this->disableSingleCarriers($arrayData);
            //check if carrier is disabled
            if ($disable_carrier['code'] == 200) {
                //clear cache
                unset($this->session->data['terminal_carriers_data']);
                //terminal_africa_carriers
                unset($this->session->data['terminal_africa_carriers']);
                //return
                $response = [
                    'code' => 200,
                    'message' => 'Carrier disabled successfully',
                ];
                // echo json_encode($response);
            } else {
                //return error
                $response = [
                    'code' => 400,
                    'message' => $disable_carrier['message'],
                ];
                // echo json_encode($response);
            }
            header('Content-Type: application/json');
			echo json_encode($response);
			exit;
        }
        //return
        $response = [
            'code' => 500,
            'message' => 'Something went wrong, please try again',
        ];
        header('Content-Type: application/json');
		echo json_encode($response);
		exit;
    }

    public static function enableSingleCarriers($carriers) {
	    // Check $skkey
	    // if (!self::$skkey) {
	    //     return [
	    //         'code' => 404,
	    //         'message' => "Invalid API Key",
	    //         'data' => [],
	    //     ];
	    // }
	    
	    // Enable single carriers
	    $ch = curl_init();
	    // curl_setopt($ch, CURLOPT_URL, self::$enpoint . 'carriers/multiple/enable');
	    curl_setopt($ch, CURLOPT_URL, 'https://api.terminal.africa/v1/carriers/multiple/enable');
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        // "Authorization: Bearer " . self::$skkey,
	        "Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H",
	        "Content-Type: application/json"
	    ));
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($carriers));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	    $response = curl_exec($ch);
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    
	    $body = json_decode($response);
	    
	    // Check if response is OK
	    if ($http_code == 200) {
	        // Return data
	        return [
	            'code' => 200,
	            'message' => 'success',
	            'data' => $body->data,
	        ];
	    } else {
	        return [
	            'code' => $http_code,
	            'message' => $body->message,
	            'data' => [],
	        ];
	    }
	}

	public static function disableSingleCarriers($carriers) {
	    // Check $skkey
	    // if (!self::$skkey) {
	    //     return [
	    //         'code' => 404,
	    //         'message' => "Invalid API Key",
	    //         'data' => [],
	    //     ];
	    // }

	    // Disable single carriers
	    $ch = curl_init();
	    // curl_setopt($ch, CURLOPT_URL, self::$enpoint . 'carriers/multiple/disable');
	    curl_setopt($ch, CURLOPT_URL, 'https://api.terminal.africa/v1/carriers/multiple/disable');
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	        // "Authorization: Bearer " . self::$skkey,
	        "Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H",
	        "Content-Type: application/json"
	    ));
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($carriers));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	    $response = curl_exec($ch);
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);

	    $body = json_decode($response);

	    // Check if response is OK
	    if ($http_code == 200) {
	        // Return data
	        return [
	            'code' => 200,
	            'message' => 'success',
	            'data' => $body->data,
	        ];
	    } else {
	        return [
	            'code' => $http_code,
	            'message' => $body->message,
	            'data' => [],
	        ];
	    }
	}

	public function getTerminalCarriers($type, $force = false) {
	    // Start the session if not already started
	    if (session_status() == PHP_SESSION_NONE) {
	        session_start();
	    }
	    
	    // Check if data is in session
	    // if (isset($this->session->data['terminal_carriers_data'][$type]) && !$force) {
	    //     return [
	    //         'code' => 200,
	    //         'message' => 'success',
	    //         'data' => sanitize_array($this->session->data['terminal_carriers_data'][$type]),
	    //         'from' => 'session',
	    //     ];
	    // }
	    
	    // if (!self::$skkey) {
	    //     return [
	    //         'code' => 404,
	    //         'message' => "Invalid API Key",
	    //         'data' => [],
	    //     ];
	    // }
	    
	    $query = [
	        'type' => $type
	    ];
	    // Query builder
	    $query = http_build_query($query);
	    
	    // Get carriers
	    $ch = curl_init();
	    // curl_setopt($ch, CURLOPT_URL, self::$enpoint . 'carriers?' . $query);
	    curl_setopt($ch, CURLOPT_URL, 'https://api.terminal.africa/v1/carriers?' . $query);
	    // curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . self::$skkey));
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H"));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($ch);
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    
	    $body = json_decode($response);
	    
	    // Check if response is OK
	    if ($http_code == 200) {
	        // Return carriers data
	        $data = $body->data;
	        // Save to session
	        $this->session->data['terminal_carriers_data'][$type] = $data;
	        // Return data
	        return [
	            'code' => 200,
	            'message' => 'success',
	            'data' => $data,
	        ];
	    } else {
	        return [
	            'code' => $http_code,
	            'message' => $body->message,
	            'data' => [],
	        ];
	    }
	}

	public function getUserCarriers($type = "domestic", $force = false) {
	    // Start the session if not already started
	    if (session_status() == PHP_SESSION_NONE) {
	        session_start();
	    }
	    
	    // Check if data is in session
	    // if (isset($this->session->data['terminal_africa_carriers'][$type]) && !$force) {
	    //     return [
	    //         'code' => 200,
	    //         'message' => 'success',
	    //         'data' => sanitize_array($this->session->data['terminal_africa_carriers'][$type]),
	    //     ];
	    // }
	    
	    // if (!self::$skkey) {
	    //     return [
	    //         'code' => 404,
	    //         'message' => "Invalid API Key",
	    //         'data' => [],
	    //     ];
	    // }
	    
	    // Get user carriers
	    $ch = curl_init();
	    // curl_setopt($ch, CURLOPT_URL, self::$enpoint . 'users/carriers?type=' . $type);
	    curl_setopt($ch, CURLOPT_URL, 'https://api.terminal.africa/v1/users/carriers?type=' . $type);
	    // curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . self::$skkey));
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H"));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($ch);
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    
	    $body = json_decode($response);
	    
	    // Check if response is OK
	    if ($http_code == 200) {
	        // Return carriers data
	        $data = $body->data->carriers;
	        // Save to session
	        $this->session->data['terminal_africa_carriers'][$type] = $data;
	        // Return data
	        return [
	            'code' => 200,
	            'message' => 'success',
	            'data' => $data,
	        ];
	    } else {
	        return [
	            'code' => $http_code,
	            'message' => $body->message,
	            'data' => [],
	        ];
	    }
	}

	// public function getActiveCarrier($carrier_id, $carriers_array_obj, $type)
    // {
    // 	echo "<pre>";print_r('this sss');echo "</pre>";die;
    //     //check if $carriers_array_obj code is not 200
    //     if ($carriers_array_obj['code'] != 200) {
    //         return false;
    //     }
    //     //get carriers_array_obj
    //     $carriers_array_obj = $carriers_array_obj['data'];
    //     //check if carrier_id is in carriers_array_obj
    //     $carrier = array_filter($carriers_array_obj, function ($carrier) use ($carrier_id) {
    //         return $carrier->carrier_id == $carrier_id;
    //     });
    //     //if carrier_id is in carriers_array_obj
    //     if ($carrier) {
    //         //get carrier
    //         $carrier = array_values($carrier)[0];
    //         //check if carrier has type enabled
    //         if ($carrier->{$type}) {
    //             //return carrier
    //             return true;
    //         }
    //     }
    //     //return false
    //     return false;
    // }


    public function getActiveCarrier(){
	    if ($this->request->server['REQUEST_METHOD'] === 'POST') {
	        $carrierId = $this->request->post['carrier_id'];
	        $carriersArrayObj = $this->request->post['usercarriers'];
	        $type = $this->request->post['type'];
	        // Check if $carriersArrayObj code is not 200
	        if ($carriersArrayObj['code'] != 200) {
	            $response = [
	                'code' => 404,
	                'message' => "Invalid carriers array object",
	                'data' => false,
	            ];
	        } else {
	            // Get the carriers array from $carriersArrayObj
	            $carriersArray = $carriersArrayObj['data'];

	            $filteredCarriers = array_filter($carriersArray, function ($carrier) use ($carrierId) {
	                return isset($carrier['carrier_id']) && $carrier['carrier_id'] == $carrierId;
	            });

	            if (!empty($filteredCarriers)) {
	                $carrier = reset($filteredCarriers);

	                // Check if carrier has the specified type enabled
	                if (isset($carrier[$type]) && $carrier[$type]) {
	                    $response = [
	                        'code' => 200,
	                        'message' => "Carrier is active",
	                        'data' => true,
	                    ];
	                } else {
	                    $response = [
	                        'code' => 200,
	                        'message' => "Carrier is not active",
	                        'data' => false,
	                    ];
	                }
	            } else {
	                $response = [
	                    'code' => 404,
	                    'message' => "Carrier not found",
	                    'data' => false,
	                ];
	            }
	        }

	        // Return the response as JSON
	        header('Content-Type: application/json');
	        echo json_encode($response);
	        exit;
	    }
	}


	public function terminal_merchant_save_address() {
	    // $nounce = $_POST['nonce'];
	    // if (!wp_verify_nonce($nounce, 'terminal_africa_nonce')) {
	    //     return [
	    //         'code' => 400,
	    //         'message' => 'Wrong nonce, please refresh the page and try again'
	    //     ];
	    // }
	    
	    $first_name = $this->request->post['first_name'];
	    $last_name = $this->request->post['last_name'];
	    $email = $this->request->post['email'];
	    $phone = $this->request->post['phone'];
	    $line_1 = $this->request->post['line_1'];
	    $line_2 = $this->request->post['line_2'];
	    $city = $this->request->post['lga'];
	    $state = $this->request->post['state'];
	    $country = $this->request->post['country'];
	    $zip_code = $this->request->post['zip_code'];

	    //check if any field is empty
	    // if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($line_1) || empty($city) || empty($state) || empty($country)) {
	    //     return [
	    //         'code' => 400,
	    //         'message' => 'Please fill all required fields'
	    //     ];
	    // }


	    
	    //check if merchant_address_id is set
	    $merchant_address_id = $this->config->get('terminal_africaaddressdata_merchant_address_id');
	    if (empty($merchant_address_id)) {
	        //create address
	        $create_address = $this->createTerminalAddress($first_name, $last_name, $email, $phone, $line_1, $line_2, $city, $state, $country, $zip_code);
	        //check if address is created
	        if ($create_address['code'] == 200) {
	            //save address id
	            // update_option('terminal_africa_merchant_address_id', $create_address['data']->address_id);
	            //save address
	            // update_option('terminal_africa_merchant_address', $create_address['data']);

	        	$terminal_africa_merchant_address_data = json_encode($create_address['data']);
	        	$data_address = array(
		        	"terminal_africaaddressdata_merchant_address_id" => $create_address['data']->address_id,
		        	"terminal_africaaddressdata_merchant_address" => $terminal_africa_merchant_address_data 
		        );
		        
		        $this->load->model('setting/setting');
		        $this->model_setting_setting->editSetting('terminal_africaaddressdata', $data_address);

	            $response = [
	                'code' => 200,
	                'message' => 'Address saved successfully'
	            ];
	        } else {
	            $response = [
	                'code' => 400,
	                'message' => $create_address['message']
	            ];
	        }
	        header('Content-Type: application/json');
	        echo json_encode($response);
	        exit;
	    } else {
	        //update address
	        $update_address = $this->updateTerminalAddress($merchant_address_id, $first_name, $last_name, $email, $phone, $line_1, $line_2, $city, $state, $country, $zip_code);
	        //check if address is updated
	        if ($update_address['code'] == 200) {
	            //save address

	            $terminal_africa_merchant_address_data = json_encode($update_address['data']);
	        	$data_address = array(
		        	"terminal_africaaddressdata_merchant_address_id" => $merchant_address_id,
		        	"terminal_africaaddressdata_merchant_address" => $terminal_africa_merchant_address_data 
		        );
		        
		        $this->load->model('setting/setting');
		        $this->model_setting_setting->editSetting('terminal_africaaddressdata', $data_address);

	            // update_option('terminal_africa_merchant_address', $update_address['data']);
	            //return
	            $response = [
	                'code' => 200,
	                'message' => 'Address updated successfully'
	            ];
	        } else {
	            $response = [
	                'code' => 400,
	                'message' => $update_address['message']
	            ];
	        }
	        header('Content-Type: application/json');
	        echo json_encode($response);
	        exit;
	    }
	}


	public function createTerminalAddress($first_name, $last_name, $email, $phone, $line_1, $line_2, $state, $city, $country, $zip_code) {
	    // if (!self::$skkey) {
	    //     return [
	    //         'code' => 404,
	    //         'message' => "Invalid API Key",
	    //         'data' => [],
	    //     ];
	    // }

	    // $url = self::$enpoint . 'addresses';
	    $url = 'https://api.terminal.africa/v1/addresses';
	    $headers = [
	        // 'Authorization: Bearer ' . self::$skkey,
	        'Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H',
	        'Content-Type: application/json',
	    ];
	    $data = [
	        'first_name' => $first_name,
	        'last_name' => $last_name,
	        'email' => $email,
	        'phone' => $phone,
	        'line1' => $line_1,
	        'line2' => $line_2,
	        'city' => $city,
	        'state' => $state,
	        'country' => $country,
	        'zip' => $zip_code,
	    ];

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

	    $response = curl_exec($ch);
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	    curl_close($ch);

	    $body = json_decode($response);
	    //check if response is ok
	    if ($http_code == 200) {
	        //return data
	        return [
	            'code' => 200,
	            'message' => 'success',
	            'data' => $body->data,
	        ];
	    } else {
	        return [
	            'code' => $http_code,
	            'message' => $body->message,
	            'data' => [],
	        ];
	    }
	}

	public function updateTerminalAddress($merchant_address_id, $first_name, $last_name, $email, $phone, $line_1, $line_2, $city, $state, $country, $zip_code)
	{
	    // if (!self::$skkey) {
	    //     return [
	    //         'code' => 404,
	    //         'message' => "Invalid API Key",
	    //         'data' => [],
	    //     ];
	    // }
	    //check if merchant_address_id is empty
	    if (empty($merchant_address_id)) {
	        return [
	            'code' => 404,
	            'message' => "Invalid merchant_address_id",
	            'data' => [],
	        ];
	    }

	    
	    $url = 'https://api.terminal.africa/v1/addresses/' . $merchant_address_id;
	    $headers = [
	        'Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H',
	        'Content-Type: application/json',
	    ];
	    $data = [
	        'first_name' => $first_name,
	        'last_name' => $last_name,
	        'email' => $email,
	        'phone' => $phone,
	        'line1' => $line_1,
	        'line2' => $line_2,
	        'city' => $city,
	        'state' => $state,
	        'country' => $country,
	        'zip' => $zip_code,
	    ];

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

	    $response = curl_exec($ch);
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	    curl_close($ch);

	    $body = json_decode($response);
	    //check if response is ok
	    if ($http_code == 200) {
	        //return data
	        return [
	            'code' => 200,
	            'message' => 'success',
	            'data' => $body->data,
	        ];
	    } else {
	        return [
	            'code' => $http_code,
	            'message' => $body->message,
	            'data' => [],
	        ];
	    }
	}

	public function get_countries()
	{
	    // Check if self::$skkey is set
	    // if (!self::$skkey) {
	    //     return [];
	    // }

	    // Check if terminal_africa_countries is set in the options
	    // if ($data = get_option('terminal_africa_countries')) {
	    //     // Return the countries
	    //     return $data;
	    // }

	    // Create a new cURL resource
	    $curl = curl_init();

	    // Set the URL and headers
	    // curl_setopt($curl, CURLOPT_URL, self::$enpoint . 'countries');
	    curl_setopt($curl, CURLOPT_URL, 'https://api.terminal.africa/v1/countries');
	    curl_setopt($curl, CURLOPT_HTTPHEADER, [
	        // 'Authorization: Bearer ' . self::$skkey,
	        'Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H',
	    ]);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	    // Execute the request
	    $response = curl_exec($curl);

	    // Check for errors
	    if (curl_errno($curl)) {
	        curl_close($curl);
	        return [];
	    }

	    // Get the HTTP status code
	    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	    // Close the cURL resource
	    curl_close($curl);

	    // Check if the response is successful
	    if ($status_code == 200) {
	        // Decode the response body
	        $body = json_decode($response);
	        $data = $body->data;

	        // Save the raw data to the options
	        // update_option('terminal_africa_countries', $data);

	        // Return the data
	        return $data;
	    }

	    return [];
	}

	public function get_states($countryCode = "NG")
	{
		$countryCode = $this->request->post['countryCode'] ? $this->request->post['countryCode'] : $countryCode ;
	    // Check if self::$skkey is set
	    // if (!self::$skkey) {
	    //     return [
	    //         'code' => 404,
	    //         'message' => "Invalid API Key",
	    //         'data' => [],
	    //     ];
	    // }

	    // Check if terminal_africa_states.$countryCode is set in the options
	    // if ($data = get_option('terminal_africa_states' . $countryCode)) {
	    //     return [
	    //         'code' => 200,
	    //         'message' => 'success',
	    //         'data' => $data,
	    //     ];
	    // }

	    // Create a new cURL resource
	    $curl = curl_init();

	    // Set the URL and headers
	    // curl_setopt($curl, CURLOPT_URL, self::$enpoint . 'states?country_code=' . $countryCode);
	    curl_setopt($curl, CURLOPT_URL, 'https://api.terminal.africa/v1/states?country_code=' . $countryCode);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, [
	        // 'Authorization: Bearer ' . self::$skkey,
	        'Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H',
	        'Content-Type: application/json',
	    ]);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	    // Execute the request
	    $response = curl_exec($curl);
	    // Check for errors
	    if (curl_errno($curl)) {
	        curl_close($curl);
	        return [
	            'code' => 500,
	            'message' => 'cURL error: ' . curl_error($curl),
	            'data' => [],
	        ];
	    }

	    // Get the HTTP status code
	    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	    // Close the cURL resource
	    curl_close($curl);

	    // Check if the response is successful
	    if ($status_code == 200) {
	        // Decode the response body
	        $body = json_decode($response);
	        $data = $body->data;

	        // Save the raw data to the options
	        // update_option('terminal_africa_states' . $countryCode, $data);

	        // Return the data

	        $response = [
	            'code' => 200,
	            'message' => 'success',
	            'data' => $data,
	        ];
	        header('Content-Type: application/json');
	        echo json_encode($response);
	        exit;
	    }

	    // Decode the error response body
	    // $body = json_decode($response);
	    // $response = [
	    //     'code' => $status_code,
	    //     'message' => $body->message,
	    //     'data' => [],
	    // ];

	    // header('Content-Type: application/json');
        // echo json_encode($response);
        // exit;
	}


	public function get_states_show($countryCode = "NG")
	{
	    // Check if self::$skkey is set
	    // if (!self::$skkey) {
	    //     return [
	    //         'code' => 404,
	    //         'message' => "Invalid API Key",
	    //         'data' => [],
	    //     ];
	    // }

	    // Check if terminal_africa_states.$countryCode is set in the options
	    // if ($data = get_option('terminal_africa_states' . $countryCode)) {
	    //     return [
	    //         'code' => 200,
	    //         'message' => 'success',
	    //         'data' => $data,
	    //     ];
	    // }

	    // Create a new cURL resource
	    $curl = curl_init();

	    // Set the URL and headers
	    // curl_setopt($curl, CURLOPT_URL, self::$enpoint . 'states?country_code=' . $countryCode);
	    curl_setopt($curl, CURLOPT_URL, 'https://api.terminal.africa/v1/states?country_code=' . $countryCode);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, [
	        // 'Authorization: Bearer ' . self::$skkey,
	        'Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H',
	        'Content-Type: application/json',
	    ]);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	    // Execute the request
	    $response = curl_exec($curl);

	    // Check for errors
	    if (curl_errno($curl)) {
	        curl_close($curl);
	        return [
	            'code' => 500,
	            'message' => 'cURL error: ' . curl_error($curl),
	            'data' => [],
	        ];
	    }

	    // Get the HTTP status code
	    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	    // Close the cURL resource
	    curl_close($curl);

	    // Check if the response is successful
		if ($status_code == 200) {
	        // Decode the response body
	        $body = json_decode($response);
	        $data = $body->data;

	        // Save the raw data to the options
	        

	        // Return the data
	        return [
	            'code' => 200,
	            'message' => 'success',
	            'data' => $data,
	        ];
	    }

	    // Decode the error response body
	    $body = json_decode($response);
	    return [
	        'code' => $status_code,
	        'message' => $body->message,
	        'data' => [],
	    ];
	}

	public function get_terminal_cities()
	{
		$stateCode = $this->request->post['stateCode'];
        $countryCode = $this->request->post['countryCode'];
	    // If session is not started, start it
	    // if (session_status() == PHP_SESSION_NONE) {
	    //     session_start();
	    // }

	    // Check if terminal_africa_cities.$countryCode.$state_code is set in session
	    if (isset($this->session->data['terminal_africa_cities'][$countryCode][$stateCode])) {
	        // Return cached data from session
	        return [
	            'code' => 200,
	            'message' => 'success',
	            'data' => $this->session->data['terminal_africa_cities'][$countryCode][$stateCode],
	        ];
	    }

	    // Check if self::$skkey is set
	    // if (!self::$skkey) {
	    //     return [
	    //         'code' => 404,
	    //         'message' => "Invalid API Key",
	    //         'data' => [],
	    //     ];
	    // }

	    // Sanitize countryCode and state_code
	    // $countryCode = sanitize_text_field($countryCode);
	    // $state_code = sanitize_text_field($state_code);
	    // Build the query string
	    $query = http_build_query([
	        'country_code' => $countryCode,
	        'state_code' => $stateCode,
	    ]);

	    // Create cURL handle
	    $ch = curl_init();

	    // Set cURL options
	    // $url = self::$enpoint . 'cities?' . $query;
	    $url = 'https://api.terminal.africa/v1/cities?' . $query;
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, [
	        'Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H',
	    ]);

	    // Execute cURL request
	    $response = curl_exec($ch);
	    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    // Close cURL handle
	    curl_close($ch);
	    // Check if response is successful
	    if ($httpCode == 200) {
	        // Decode response JSON
	        $body = json_decode($response);
	        // Return cities
	        $data = $body->data;
	        // Save data to session
	        $this->session->data['terminal_africa_cities'][$countryCode][$stateCode] = $data;
	        // Return data
	        $response = [
	            'code' => 200,
	            'message' => 'success',
	            'data' => $data,
	        ];
	        header('Content-Type: application/json');
	        echo json_encode($response);
	        exit;
	    }
	    // else {
	    //     // Decode error response JSON
	    //     $body = json_decode($response);
	    //     // Return error message
	    //     return [
	    //         'code' => $httpCode,
	    //         'message' => $body->message,
	    //         'data' => [],
	    //     ];
	    // }
	}

	public function get_terminal_packaging()
    {
        //get packaging
        $get_packaging = $this->getTerminalPackagingData();
        //check if packaging is gotten
        if ($get_packaging['code'] == 200) {
            //return
            $response = [
                'code' => 200,
                'message' => 'Packaging gotten successfully',
                'data' => $get_packaging['data']
            ];
        } else {
            //return error
            $response = [
                'code' => 400,
                'message' => $get_packaging['message'],
                'endpoint' => 'get_packaging'
            ];
        }
        header('Content-Type: application/json');
    	echo json_encode($response);
    	exit;
    }



    public function getTerminalPackagingData()
	{
	    $packaging_id = $this->config->get('terminal_packagingid_terminal_default_packaging_id');
	    // check if packaging id is set
	    if ($packaging_id) {
	        return [
	            'code' => 200,
	            'message' => 'success',
	            'data' => [
	                'packaging_id' => $packaging_id
	            ]
	        ];
	    }
		// echo "<pre>";print_r('this');echo "</pre>";die;

	    // if (!self::$skkey) {
	    //     return [
	    //         'code' => 404,
	    //         'message' => "Invalid API Key",
	    //         'data' => [],
	    //     ];
	    // }

	    // Make the API request using cURL
	    $ch = curl_init();
	    // curl_setopt($ch, CURLOPT_URL, self::$enpoint . 'packaging');
	    curl_setopt($ch, CURLOPT_URL, 'https://api.terminal.africa/v1/packaging');
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H'));
	    $response = curl_exec($ch);
	    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    $body = json_decode($response);
        
        if ($body->status == true) {
            
            $data = $body->data;
            $packaging = $data->packaging;
            
            if (count($packaging) > 0) {
                
                $element = $packaging[0];             
                $packaging_id = $element->packaging_id;
                
                $terminal_default_packagingid = array(
		        	"terminal_packaging_default_packaging_id" => $packaging_id
		        );

		        $this->load->model('setting/setting');
		        $this->model_setting_setting->editSetting('terminal_packaging', $terminal_default_packagingid);

                // update_option('terminal_default_packaging_id', $element->packaging_id);
            } else {
                //create new packaging
                $create = $this->createDefaultPackaging();               
                if ($create['code'] == 200) {
                    //save the id to option
                $packaging_id = $create['data']->packaging_id;
                $new_terminal_default_packaging_id = array(
	        		"terminal_packaging_default_packaging_id" => $packaging_id
		        );		        
		        $this->load->model('setting/setting');
		        $this->model_setting_setting->editSetting('terminal_packaging', $new_terminal_default_packaging_id);

                  // update_option('terminal_default_packaging_id', $create['data']->packaging_id);
                } else {
                    return [
                        'code' => 404,
                        'message' => "Unable to create default packaging",
                        'data' => [],
                    ];
                }
            }
            //return data
            return [
                'code' => 200,
                'message' => 'success',
                'data' => $data,
            ];
        } else {
            return [
                'code' => $response->status,
                'message' => $body->message,
                'data' => [],
            ];
        }
	}


	public function createDefaultPackaging()
	{
	    // check if terminal_africa_merchant_id is set
	    $terminal_africa_auth_settings = $this->config->get('terminal_africa_auth_settings');
		$auth_settings = json_decode($terminal_africa_auth_settings);

	    $terminal_africa_merchant_id =isset($auth_settings->user_id) ? $auth_settings->user_id : '';
	    if (!$terminal_africa_merchant_id) {
	        return [
	            'code' => 404,
	            'message' => "Invalid Merchant ID",
	            'data' => [],
	        ];
	    }
	    
	    // check $skkey
	    // if (!self::$skkey) {
	    //     return [
	    //         'code' => 404,
	    //         'message' => "Invalid API Key",
	    //         'data' => [],
	    //     ];
	    // }

	    $data = [
	        "height" => 1,
	        "length" => 47,
	        "name" => "DHL Express Large Flyer",
	        "size_unit" => "cm",
	        "type" => "soft-packaging",
	        "user" => $terminal_africa_merchant_id,
	        "weight" => 0.1,
	        "weight_unit" => "kg",
	        "width" => 38
	    ];
	    
	    $headers = [
	        // 'Authorization' => 'Bearer ' . self::$skkey,
	        'Authorization' => 'Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H',
	        'Content-Type' => 'application/json'
	    ];

	    // Make the API request using cURL
	    $ch = curl_init();
	    // curl_setopt($ch, CURLOPT_URL, self::$enpoint . 'packaging');
	    curl_setopt($ch, CURLOPT_URL, 'https://api.terminal.africa/v1/packaging');
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	    $response = curl_exec($ch);
	    curl_close($ch);

	    $body = json_decode($response);

	    // check if response is ok
	    if ($body->status == true) {
	        // return data
	        return [
	            'code' => 200,
	            'message' => 'success',
	            'data' => $body->data,
	        ];
	    } else {
	        return [
	            'code' => 404, // Assuming 404 for other failure scenarios
	            'message' => $body->message ?? "API Request Failed",
	            'data' => [],
	        ];
	    }
	}


}