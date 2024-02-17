<?php
class ModelAccountReseller extends Model {
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

		$telephone = $data['default_country']." ".(int)$data['telephone'];

		// update customer data with proper country code + actualphone format
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET telephone='".$telephone."' WHERE customer_id='".(int)$customer_id."'");

		$customer_to_reseller_id = $this->db->getLastId();

		if ($data['lampiran']) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_to_reseller SET lampiran='" . $data['lampiran'] . "' WHERE customer_to_reseller_id='" . (int) $customer_to_reseller_id . "'");
		}

		$this->sendSms($this->db->escape($data['telephone']));

		return $customer_to_reseller_id;
	}

public function editReseller($customer_id, $data) {

		if(!$this->config->get('hpwd_reseller_management_social_media_profile')) {
			$data['facebook'] 	= '';
			$data['twitter'] 	= '';
			$data['instagram'] 	= '';
		}

		if(!$this->config->get('hpwd_reseller_management_id_card_entry')) {
				$data['ktp'] = '';
		}

		$telephone = $data['default_country']." ".(int)$data['telephone'];

		// change reseller customer group
		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($data['customer_group_id']);

		if ($customer_group_info['approval']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int) $customer_id . "', type = 'customer', date_added = NOW()");
		}

		$this->db->query("UPDATE " . DB_PREFIX . "customer
			SET customer_group_id = '" . (int) $data['customer_group_id'] . "',
			default_country = '" . $this->db->escape($data['default_country']) . "',
			telephone = '" . $this->db->escape($data['telephone']) . "',
			ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'
			WHERE customer_id='" . (int) $customer_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_to_reseller WHERE customer_id='" . (int) $customer_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_to_reseller
			SET customer_id='" . (int) $customer_id . "',
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



	public function addResellerFromCustomer($customer_id,$data) {
		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($data['customer_group_id']);

		if ($customer_group_info['approval']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int) $customer_id . "', type = 'customer', date_added = NOW()");
		}

		if(!$this->config->get('hpwd_reseller_management_social_media_profile')) {
			$data['facebook'] 	= '';
			$data['twitter'] 	= '';
			$data['instagram'] 	= '';
		}

		if(!$this->config->get('hpwd_reseller_management_id_card_entry')) {
				$data['ktp'] = '';
		}

		$telephone = $data['default_country']." ".(int)$data['telephone'];

		// change existing customer group that register as reseller
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET customer_group_id='".(int)$data['customer_group_id']."', telephone = '".$telephone."' WHERE customer_id='".(int)$customer_id."'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_to_reseller
			SET customer_id='" . (int) $customer_id . "',
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


	public function getCountries() {
		$q = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE name = 'Afghanistan'");
		if ($q->num_rows) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country");
		} else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country_hpwd");
		}
		return $query->rows;
	}

	public function getReseller($customer_id) {
		$query = $this->db->query("SELECT *,
			CONCAT(c.firstname, ' ', c.lastname) AS name,
			cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) LEFT JOIN " . DB_PREFIX . "customer_to_reseller r ON (c.customer_id=r.customer_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "' AND r.customer_id = '" . (int) $customer_id . "'");

		return $query->row;
	}

	public function getZoneResellers() {
		$query = $this->db->query("SELECT DISTINCT z.zone_id, z.name FROM " . DB_PREFIX . "zone z INNER JOIN " . DB_PREFIX . "customer_to_reseller ctr ON z.zone_id = ctr.zone_id INNER JOIN " . DB_PREFIX . "customer c ON ctr.customer_id = c.customer_id");

		return $query->rows;
	}


	public function getResellers($data = array()) {
		$sql = "SELECT *, z.name as zone_name, ct.name as country_name FROM " . DB_PREFIX . "customer_to_reseller ctr INNER JOIN " . DB_PREFIX . "customer c ON ctr.customer_id = c.customer_id LEFT JOIN " . DB_PREFIX . "zone z ON z.zone_id = ctr.zone_id LEFT JOIN " . DB_PREFIX . "country ct ON ct.country_id = ctr.country_id WHERE 1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_zone_id'])) {
			$sql .= " AND ctr.zone_id = '" . (int)$data['filter_zone_id']  . "'";
		}

		if (!empty($data['filter_type_id'])) {
			$sql .= " AND c.customer_group_id = '" . (int)$data['filter_type_id']  . "'";
		}

		if (!empty($data['filter_type'])) {
			$sql .= " AND c.customer_group_id IN (SELECT customer_group_id FROM " . DB_PREFIX . "customer_group_description WHERE name LIKE '" . $this->db->escape($data['filter_type']) . "')";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalResellers($data = array()) {
		$sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "customer_to_reseller ctr LEFT JOIN " . DB_PREFIX . "customer c ON ctr.customer_id = c.customer_id WHERE 1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_zone_id'])) {
			$sql .= " AND ctr.zone_id = '" . (int)$data['filter_zone_id']  . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	private function sendSms($phone) {
		$status = false;

		$userkey = $this->config->get('hpwd_reseller_management_sms_userkey');
		$passkey = $this->config->get('hpwd_reseller_management_sms_passkey');
		$message = $this->config->get('hpwd_reseller_management_sms_receipt_template');

		$gateway = $this->config->get('hpwd_reseller_management_sms_gateway');
		$option = $this->config->get('hpwd_reseller_management_sms_send_receipt');

		if ($option != 1) {return false;}

		if ($userkey && $passkey) {

			switch ($gateway) {

				case 'zenziva':
					return $this->zenzivaSendSMS($userkey,$passkey,$phone,$message);
				break;

				case 'wavecell':
					return $this->wavecellSendSMS($userkey,$passkey,$phone,$message);
				break;

				case 'netgsm':
					return $this->netgsmSendSMS($userkey,$passkey,$phone,$message);
				  break;

				default:
				return false;
				break;

			}
		}


		return $status;
	}

	private function netgsmSendSMS($userkey,$passkey,$phone,$message) {
		  $url= "https://api.netgsm.com.tr/sms/send/otp/?usercode=".$userkey."&password=".$passkey."&no=".$phone."&msg=".urlencode($message)."&msgheader=".$userkey;

		  $ch = curl_init($url);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		  $http_response = curl_exec($ch);
		  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		  if($http_code != 200){
			echo "$http_code $http_response\n";
			return false;
		  }

		  $status = $http_response;

	}

	private function wavecellSendSMS($userkey,$passkey,$phone,$message) {
		$curl = curl_init();
					$phone = preg_replace('/^(^\+62\s?|^0)/m', "+62", $phone);

					// $passkey = urlencode("zenzivaB1sm1ll@hi");
					// $userkey = "23x78u";
					$passkey = urlencode($this->config->get('module_hp_social_login_sms_passkey'));
					$userkey = $this->config->get('module_hp_social_login_sms_userkey');


					$postRequest = array(
						"source" => "abcde",
						"destination" => $phone,
						"text" => $message,
						"encoding" => "AUTO",
					);
					$headers = array();
					$headers[] = 'Authorization: Bearer ' . $passkey;
					$headers[] = 'Content-Type: application/json';

					$curl = curl_init();
					curl_setopt_array($curl, array(
						CURLOPT_URL => "https://api.wavecell.com/sms/v1/" . $userkey . "/single",
						CURLOPT_HTTPHEADER => $headers,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "AUTO",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 30,
						CURLOPT_POST => 1,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_POSTFIELDS => json_encode($postRequest),
					));

					$response = curl_exec($curl);
					$response = json_decode($response);
					$err = curl_error($curl);
					curl_close($curl);

					if ($response->status->code == "QUEUED") {
						return true;
					} else {
						return false;
					}

	}


	private function zenzivaSendSMS($userkey,$passkey,$phone,$message) {
				$destination = "+62" . $phone;

				$url = "https://reguler.zenziva.net/apps/smsapi.php";

				$text = urlencode($message);
				$content = 'userkey=' . rawurlencode($userkey) .
					'&passkey=' . rawurlencode($passkey) .
					'&nohp=' . rawurlencode($destination) .
					'&pesan=' . htmlentities($text);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$getresponse = curl_exec($ch);
				curl_close($ch);
				$xmldata = new SimpleXMLElement($getresponse);

				return $xmldata->message[0]->text ? true: false;


	}

	public function getCustomerGroups() {
		$sql = "SELECT customer_group_id, name FROM `" . DB_PREFIX . "customer_group_description` WHERE customer_group_id !='" . (int) $this->config->get('config_customer_group_id') . "' AND language_id='" . (int) $this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCustomerGroupsActive(){
		$sql = "SELECT customer_group_id, name FROM `" . DB_PREFIX . "customer_group_description` WHERE language_id='" . (int) $this->config->get('config_language_id') . "' AND customer_group_id IN (" . implode(", ", $this->config->get('hpwd_reseller_management_group')) . ")";

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getResellerByEmail($email) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` c INNER JOIN `" . DB_PREFIX . "customer_to_reseller` ctr ON ctr.customer_id = c.customer_id  WHERE LOWER(c.email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getOrderAmount($customer_id)
	{
		$sql = "SELECT SUM(total) AS 'total' FROM `".DB_PREFIX."order` WHERE customer_id=".$customer_id;

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function updateLevel($customer_id,$customer_group_id)
	{
		$this->load->language('account/reseller');
		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
		$msg = sprintf($this->language->get('notif_msg'),$customer_group_info['name']);

		$sqls[] = "DELETE FROM ".DB_PREFIX."reseller_notif WHERE customer_id=".$customer_id;
		$sqls[] = "INSERT INTO ".DB_PREFIX."reseller_notif SET msg='".$this->db->escape($msg)."', customer_id=".$customer_id;
		$sqls[] = "UPDATE ".DB_PREFIX."customer SET customer_group_id='".$customer_group_id."' WHERE customer_id=".$customer_id;

		foreach ($sqls as $sql) {
			$this->db->query($sql);
		}
	}

	public function getNotif($customer_id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."reseller_notif WHERE customer_id=".$customer_id;
		$query = $this->db->query($sql);

		return $query->row;
	}

	public function deleteNotif($customer_id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "reseller_notif WHERE customer_id=" . $customer_id;

		$this->db->query($sql);
	}
}
