<?php
class ModelQuoteQuoteModule extends Model {
	public function getquotedata($data = array()){
		$sql = "SELECT * FROM " . DB_PREFIX . "p_quote_option_save_value pq";
		if (!empty($data['filter_name'])) {
			$sql .= " AND pq.name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY pq.p_q_o_value_id";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
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

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);
		return $query->rows;
	}
	public function getTotalquotedata() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "p_quote_option_save_value");

		return $query->row['total'];
	}
	public function deleteQuotedata($p_q_o_value_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "p_quote_option_save_value WHERE p_q_o_value_id = '" . (int)$p_q_o_value_id . "'");

		$this->cache->delete('p_quote_option_save_value');
	}
	public function getquoteproductdata($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "p_quote_option WHERE product_id = '" . (int)$product_id . "'");
		return $query->rows;
	}
	public function getQuote($quote_value_id){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "p_quote_option_save_value p_value LEFT JOIN " . DB_PREFIX . "p_quote_option_save p_save ON (p_value.p_q_o_value_id = p_save.p_q_o_value_id) WHERE p_value.p_q_o_value_id = '" . (int)$quote_value_id . "'");
		return $query->rows;
	}
}