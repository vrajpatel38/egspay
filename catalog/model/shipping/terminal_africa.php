<?php
class ModelShippingTerminalAfrica extends Model {
	function getQuote($address) {
		$this->load->language('shipping/terminal_africa');
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('flat_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('flat_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
		$cart_item = $this->cart->getProducts();

		$data_items = [];
	        foreach ($cart_item as $item) {
	            $data_items[] = [
	                'name' => $item['name'],
	                'quantity' => (int)$item['quantity'],
	                'value' => $item['price'],
	                '	' => "{$item['quantity']} of {$item['name']} at {$item['price']} each for a total of {$item['total']}",
	                'type' => 'parcel',
	                'currency' => $this->session->data['currency'],
	                'weight' => (float)$item['width'] ?: 0.1,
	            ];
	        }


	        $packaging_id = $this->config->get('terminal_packaging_default_packaging_id');

	        // $verifyDefaultPackaging = $this->verifyDefaultPackaging($packaging_id);
	        $parcel = [
	            'packaging' => $packaging_id,
	            'weight_unit' => 'kg',
	            'items' => $data_items,
	            'description' => 'Order from sample',
        	];

        	// check if terminal_africa_parcel_id is set
	        // $parcel_id = $this->session->data['terminal_africa_parcel_id'];

	        if (!empty($parcel_id)) {
	            // update parcel
	            $response = $this->updateTerminalParcel($parcel_id, $parcel);
	            // check if response is 200
	            // if ($response['code'] == 200) {
	            //     //return
	            //     wp_send_json([
	            //         'code' => 200,
	            //         'type' => 'percel',
	            //         'message' => 'Parcel updated successfully',
	            //     ]);
	            // } else {
	            //     wp_send_json([
	            //         'code' => 401,
	            //         'type' => 'percel',
	            //         'message' => $response['message']
	            //     ]);
	            // }
	        }
	        //post request
	        $response = $this->createTerminalParcel($parcel);
	        //check if response is 200
	        if ($response['code'] == true) {
	            //save parcel wc session
	             $this->session->data['terminal_africa_parcel_id'] = $response['data']->parcel_id;
	        }

			$quote_data = array();

			$quote_data['terminal_africa'] = array(
				'code'         => 'terminal_africa.terminal_africa',
				'title'        => $this->language->get('text_description'),
				'cost'         => $this->config->get('terminal_percentage_custom_price_mark_up'),
				'tax_class_id' => $this->config->get('flat_tax_class_id'),
				'text'         => $this->currency->format($this->tax->calculate($this->config->get('terminal_percentage_custom_price_mark_up'), $this->config->get('flat_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
			);

			$method_data = array(
				'code'       => 'terminal_africa',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('terminal_africa_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}

	public function createTerminalParcel($body)
	{
	    // if (!self::$skkey) {
	    //     return [
	    //         'code' => 404,
	    //         'message' => "Invalid API Key",
	    //         'data' => [],
	    //     ];
	    // }

	    // $inputArray = array(
		//     'packaging' => 'PA-UHOQMEUTL6BQM0QM',
		//     'weight_unit' => 'kg',
		//     'items' => array(
		//         array(
		//             'name' => 'sample',
		//             'quantity' => 1,
		//             'value' => 100,
		//             'description' => '1 of sample at 100 each for a total of 100',
		//             'type' => 'parcel',
		//             'currency' => 'AED',
		//             'weight' => 5,
		//         ),
		//     ),
		//     'description' => 'Order from wordpress_shipping_2',
		// );

		// echo "<pre>";print_r($body);echo "</pre>";

	  
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.terminal.africa/v1/parcels',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => json_encode($body),
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/json',
		    'Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H'
		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		$response_body = json_decode($response);

	    // check if response is ok
	    if ($response_body->status == true) {
	        // return data
	        return [
	            'code' => true,
	            'message' => 'success',
	            'data' => $response_body->data,
	        ];
	    } else {
	        return [
	            'code' => $response_body->status,
	            'message' => $response_body->message ?? "API Request Failed",
	            'data' => [],
	        ];
	    }
	}


	public function updateTerminalParcel($parcel_id, $body)
    {
        // if (!self::$skkey) {
        //     return [
        //         'code' => 404,
        //         'message' => "Invalid API Key",
        //         'data' => [],
        //     ];
        // }

        // $response = Requests::put(
        //     self::$enpoint . 'parcels' . "/" . $parcel_id,
        //     [
        //         'Authorization' => 'Bearer ' . self::$skkey,
        //         'Content-Type' => 'application/json'
        //     ],
        //     json_encode(
        //         $body
        //     ),  
        //     //time out 60 seconds
        //     ['timeout' => 60]
        // );

        $curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.terminal.africa/v1/parcels/'. $parcel_id,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'PUT',
		  CURLOPT_POSTFIELDS => json_encode($body),
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/json',
		    'Authorization: Bearer sk_live_kmXfitFwMtBDHz7dHUZiNdUv2211v48H'
		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);

        // $body = json_decode($response);
        // //check if response is ok
        // if ($body->status === 1) {
        //     //return countries
        //     $data = $body->data;
        //     //return data
        //     return [
        //         'code' => 200,
        //         'message' => 'success',
        //         'data' => $data,
        //     ];
        // } else {
        //     return [
        //         'code' => $response->status,
        //         'message' => $body->message,
        //         'data' => [],
        //     ];
        // }
    }
}