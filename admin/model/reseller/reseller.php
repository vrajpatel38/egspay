<?php
class ModelResellerReseller extends Model {
	public function addReseller($data) {

		$this->db->query(" INSERT INTO `" . DB_PREFIX . "customer` SET
				customer_group_id = '" . (int) $data['customer_group_id'] . "',
				store_id = '" . (int) $this->config->get('config_store_id') . "',
				language_id = '" . (int) $this->config->get('config_language_id') . "',
				firstname = '" . $this->db->escape($data['firstname']) . "',
				lastname = '" . $this->db->escape($data['lastname']) . "',
				email = '" . $this->db->escape($data['email']) . "',
				default_country = '" . $this->db->escape($data['default_country']) . "',
				telephone = '" . $this->db->escape($data['telephone']) . "',
				custom_field = '',
				salt = '" . $this->db->escape($salt = token(9)) . "',
				password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "',
				newsletter = '0',
				ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',
				status = '0',
				date_added = NOW()");

		$customer_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_to_reseller
			SET customer_id='" . (int) $customer_id . "',
			no_nik = '" . $this->db->escape($data['no_nik']) . "',
			social_media_facebook = '" . $this->db->escape($data['social_media_facebook']) . "',
			social_media_twitter = '" . $this->db->escape($data['social_media_twitter']) . "',
			social_media_instagram = '" . $this->db->escape($data['social_media_instagram']) . "',
			zone_id = '" . (int) $data['zone_id'] . "',
			country_id = '" . (int) $data['country_id'] . "',
			address = '" . $this->db->escape($data['address']) . "'");


		$telephone = $data['default_country'] . " " . (int)$data['telephone'];

		// update customer data with proper country code + actualphone format
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET telephone='" . $telephone . "' WHERE customer_id='" . (int)$customer_id . "'");

		if ($data['lampiran']) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_to_reseller SET lampiran='" . $data['lampiran'] . "' WHERE customer_id='" . (int) $customer_id . "'");
		}

		$this->sendSms($this->db->escape($data['telephone']));

		return $customer_to_reseller_id;
	}


	public function getCustomersNotReseller($customer_id) {
		$sql = "SELECT c.customer_id, CONCAT(c.firstname, ' ' , c.lastname) as name, c.email FROM " . DB_PREFIX . "customer c";
		$query = $this->db->query($sql);
		$customers = $query->rows;

		$result = [];
		foreach ($customers as $customer) {
			if (!$this->getReseller($customer['customer_id']) || $customer['customer_id'] == $customer_id) {
				$result[] = $customer;
			}
		}

		return $result;
	}

	public function getZoneResellers() {
		$query = $this->db->query("SELECT DISTINCT z.zone_id, z.name FROM " . DB_PREFIX . "zone z INNER JOIN " . DB_PREFIX . "customer_to_reseller ctr ON z.zone_id = ctr.zone_id INNER JOIN " . DB_PREFIX . "customer c ON ctr.customer_id = c.customer_id");

		return $query->rows;
	}

	public function addResellerFromCustomer($customer_id, $data) {
		$this->load->model('customer/customer_group');

		$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($data['customer_group_id']);

		if ($customer_group_info['approval']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int) $customer_id . "', type = 'customer', date_added = NOW()");
		}

		if (!$this->config->get('hpwd_reseller_management_social_media_profile')) {
			$data['facebook'] 	= '';
			$data['twitter'] 	= '';
			$data['instagram'] 	= '';
		}

		if (!$this->config->get('hpwd_reseller_management_id_card_entry')) {
			$data['ktp'] = '';
		}

		//$telephone = $data['default_country']." ".(int)$data['telephone'];

		// update customer data with proper country code + actualphone format
		// change existing customer group that register as reseller
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET customer_group_id='" . (int)$data['customer_group_id'] . "' WHERE customer_id='" . (int)$customer_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_to_reseller
			SET customer_id='" . (int) $data['customer_id'] . "',
			no_nik = '" . $this->db->escape($data['ktp']) . "',
			social_media_facebook = '" . $this->db->escape($data['facebook']) . "',
			social_media_twitter = '" . $this->db->escape($data['twitter']) . "',
			social_media_instagram = '" . $this->db->escape($data['instagram']) . "',
			zone_id = '" . (int) $data['zone_id'] . "',
			country_id = '" . (int) $data['country_id'] . "',
			address = '" . $this->db->escape($data['address']) . "'");

		if ($data['lampiran']) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_to_reseller SET lampiran='" . $data['lampiran'] . "' WHERE customer_id='" . (int) $customer_id . "'");
		}

		return $customer_id;
	}


	protected function getCustomerId($customer_id) {
		$query = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "customer_to_reseller` WHERE customer_id ='" . (int) $customer_id . "'");

		return $query->num_rows ? $query->row['customer_id'] : 0;
	}

	public function editReseller($customer_id, $data) {

		if (!$this->config->get('hpwd_reseller_management_social_media_profile')) {
			$data['facebook'] 	= '';
			$data['twitter'] 	= '';
			$data['instagram'] 	= '';
		}

		if (!$this->config->get('hpwd_reseller_management_id_card_entry')) {
			$data['ktp'] = '';
		}

		// $telephone = $data['default_country'] . " " . (int)$data['telephone'];

		$this->db->query("UPDATE " . DB_PREFIX . "customer
			SET customer_group_id = '" . (int) $data['customer_group_id'] . "',
			default_country = '" . $this->db->escape($data['default_country']) . "',
			telephone = '" . $this->db->escape($data['telephone']) . "',
			ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',
			status = '" . (int) $data['status'] . "'
			WHERE customer_id='" . (int) $customer_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_to_reseller WHERE customer_id='" . (int) $customer_id . "'");


		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_to_reseller
			SET customer_id='" . (int) $data['customer_id'] . "',
			no_nik = '" . $this->db->escape($data['ktp']) . "',
			social_media_facebook = '" . $this->db->escape($data['facebook']) . "',
			social_media_twitter = '" . $this->db->escape($data['twitter']) . "',
			social_media_instagram = '" . $this->db->escape($data['instagram']) . "',
			zone_id = '" . (int) $data['zone_id'] . "',
			country_id = '" . (int) $data['country_id'] . "',
			address = '" . $this->db->escape($data['address']) . "'");

		if ($data['lampiran']) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_to_reseller SET lampiran='" . $data['lampiran'] . "' WHERE customer_id='" . (int) $customer_id . "'");
		}
	}

	public function enableReseller($id) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer
			SET
			status = '1'
			WHERE customer_id='" . (int) $id . "'");
	}
	public function disableReseller($id) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer
			SET
			status = '0'
			WHERE customer_id='" . (int) $id . "'");
	}

	public function editToken($customer_id, $token) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = '" . $this->db->escape($token) . "' WHERE customer_id = '" . (int) $customer_id . "'");
	}

	public function deleteReseller($customer_id) {
		// delete customer first
		// $this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int) $this->getCustomerId($customer_id) . "'");

		$reseller_info = $this->getReseller($customer_id);

		if ($reseller_info) {
			$this->load->model('tool/upload');
			// Remove file before deleting DB record.
			$upload_info = $this->model_tool_upload->getUploadByCode($reseller_info['lampiran']);

			if ($upload_info && is_file(DIR_UPLOAD . $upload_info['filename'])) {
				unlink(DIR_UPLOAD . $upload_info['filename']);
			}

			$this->model_tool_upload->deleteUpload($upload_info['upload_id']);

			$this->db->query("DELETE FROM " . DB_PREFIX . "customer_to_reseller WHERE customer_id = '" . (int) $customer_id . "'");
		}
	}

	public function getReseller($customer_id) {
		$query = $this->db->query("SELECT *,
			CONCAT(c.firstname, ' ', c.lastname) AS name,
			cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) LEFT JOIN " . DB_PREFIX . "customer_to_reseller r ON (c.customer_id=r.customer_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND r.customer_id = '" . (int) $customer_id . "'");

		return $query->row;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getCustomerReseller($customer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_to_reseller WHERE customer_id = '" . (int) $customer_id . "'");

		return $query->row;
	}

	public function getResellers($data = array()) {
		$sql = "SELECT *,c.status as status, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) LEFT JOIN " . DB_PREFIX . "customer_to_reseller r ON (c.customer_id=r.customer_id) LEFT JOIN " . DB_PREFIX . "zone z ON z.zone_id = r.zone_id";

		$sql .= " WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c.customer_id=r.customer_id";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "concat(c.firstname, ' ',c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_whatsapp'])) {
			$implode[] = "CONCAT(c.default_country, ' ', c.telephone) LIKE '%" . $this->db->escape($data['filter_whatsapp']) . "%'";
		}

		if (!empty($data['filter_zone_id'])) {
			$implode[] = "r.zone_id = " . (int) $data['filter_zone_id'];
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_customer_group'])) {
			$implode[] = "c.customer_group_id =" . $this->db->escape($data['filter_customer_group']) . " ";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "c.status = '" . (int) $data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			"concat(c.firstname, ' ',c.lastname) as name",
			'c.email',
			'c.customer_group_id',
			'c.status',
			'c.date_added',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY concat(c.firstname, ' ',c.lastname)";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getAddress($address_id) {
		$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int) $address_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int) $address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int) $address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}

			return array(
				'address_id' => $address_query->row['address_id'],
				'customer_id' => $address_query->row['customer_id'],
				'firstname' => $address_query->row['firstname'],
				'lastname' => $address_query->row['lastname'],
				'company' => $address_query->row['company'],
				'address_1' => $address_query->row['address_1'],
				'address_2' => $address_query->row['address_2'],
				'postcode' => $address_query->row['postcode'],
				'city' => $address_query->row['city'],
				'zone_id' => $address_query->row['zone_id'],
				'zone' => $zone,
				'zone_code' => $zone_code,
				'country_id' => $address_query->row['country_id'],
				'country' => $country,
				'iso_code_2' => $iso_code_2,
				'iso_code_3' => $iso_code_3,
				'address_format' => $address_format,
				'custom_field' => json_decode($address_query->row['custom_field'], true),
			);
		}
	}

	public function getAddresses($customer_id) {
		$address_data = array();

		$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");

		foreach ($query->rows as $result) {
			$address_info = $this->getAddress($result['address_id']);

			if ($address_info) {
				$address_data[$result['address_id']] = $address_info;
			}
		}

		return $address_data;
	}

	public function getTotalReseller($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer c INNER JOIN " . DB_PREFIX . "customer_to_reseller r ON (c.customer_id=r.customer_id) LEFT JOIN " . DB_PREFIX . "zone z ON z.zone_id = r.zone_id";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_whatsapp'])) {
			$implode[] = "CONCAT(c.default_country, ' ', c.telephone) LIKE '%" . $this->db->escape($data['filter_whatsapp']) . "%'";
		}

		if (!empty($data['filter_zone_id'])) {
			$implode[] = "r.zone_id = " . (int) $data['filter_zone_id'];
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "newsletter = '" . (int) $data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "customer_group_id = '" . (int) $data['filter_customer_group_id'] . "'";
		}

		if (!empty($data['filter_ip'])) {
			$implode[] = "customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "status = '" . (int) $data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalAddressesByCustomerId($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int) $customer_id . "'");

		return $query->row['total'];
	}

	public function getTotalAddressesByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE country_id = '" . (int) $country_id . "'");

		return $query->row['total'];
	}

	public function getTotalAddressesByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE zone_id = '" . (int) $zone_id . "'");

		return $query->row['total'];
	}

	public function getTotalCustomersByCustomerGroupId($customer_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE customer_group_id = '" . (int) $customer_group_id . "'");

		return $query->row['total'];
	}

	public function addHistory($customer_id, $comment) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_history SET customer_id = '" . (int) $customer_id . "', comment = '" . $this->db->escape(strip_tags($comment)) . "', date_added = NOW()");
	}

	public function getHistories($customer_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT comment, date_added FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . (int) $customer_id . "' ORDER BY date_added DESC LIMIT " . (int) $start . "," . (int) $limit);

		return $query->rows;
	}

	public function getTotalHistories($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_history WHERE customer_id = '" . (int) $customer_id . "'");

		return $query->row['total'];
	}

	public function addTransaction($customer_id, $description = '', $amount = '', $order_id = 0) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float) $amount . "', date_added = NOW()");
	}

	public function deleteTransactionByOrderId($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int) $order_id . "'");
	}

	public function getTransactions($customer_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int) $customer_id . "' ORDER BY date_added DESC LIMIT " . (int) $start . "," . (int) $limit);

		return $query->rows;
	}

	public function getTotalTransactions($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int) $customer_id . "'");

		return $query->row['total'];
	}

	public function getTransactionTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int) $customer_id . "'");

		return $query->row['total'];
	}

	public function getTotalTransactionsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int) $order_id . "'");

		return $query->row['total'];
	}

	public function addReward($customer_id, $description = '', $points = '', $order_id = 0) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int) $customer_id . "', order_id = '" . (int) $order_id . "', points = '" . (int) $points . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");
	}

	public function deleteReward($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int) $order_id . "' AND points > 0");
	}

	public function getRewards($customer_id, $start = 0, $limit = 10) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int) $customer_id . "' ORDER BY date_added DESC LIMIT " . (int) $start . "," . (int) $limit);

		return $query->rows;
	}

	public function getTotalRewards($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int) $customer_id . "'");

		return $query->row['total'];
	}

	public function getRewardTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int) $customer_id . "'");

		return $query->row['total'];
	}

	public function getTotalCustomerRewardsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int) $order_id . "' AND points > 0");

		return $query->row['total'];
	}

	public function getIps($customer_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}
		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int) $customer_id . "' ORDER BY date_added DESC LIMIT " . (int) $start . "," . (int) $limit);

		return $query->rows;
	}

	public function getTotalIps($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int) $customer_id . "'");

		return $query->row['total'];
	}

	public function getTotalCustomersByIp($ip) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->row['total'];
	}

	public function getTotalLoginAttempts($email) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE `email` = '" . $this->db->escape($email) . "'");

		return $query->row;
	}

	public function deleteLoginAttempts($email) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE `email` = '" . $this->db->escape($email) . "'");
	}

	public function getCustomerGroups() {
		$sql = "SELECT customer_group_id, name FROM `" . DB_PREFIX . "customer_group_description` WHERE customer_group_id !='" . (int) $this->config->get('config_customer_group_id') . "' AND language_id='" . (int) $this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}
