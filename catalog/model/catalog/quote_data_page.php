<?php
class ModelCatalogQuoteDataPage extends Model {
    // quote_data for product
        public function getProductQuoteOptions($product_id){
          $p_quote_option_data = array();
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "p_quote_option WHERE product_id = '" . (int)$product_id . "'");
            foreach($query->rows as $p_quote_option){
                $p_quote_option_data[] = array(
                    'quote_option_id' => $p_quote_option['quote_option_id'],
                    'product_id' => $p_quote_option['product_id'],
                    'quote_name' => $p_quote_option['quote_name'],
                    'type' => $p_quote_option['type'],
                    'value' => $p_quote_option['value'],
                );
            }
            return $p_quote_option_data;
        }
    // quote_data for product
	
	public function addQuoteData($data,$value,$product_data_info) {
        $ip = $this->request->server['REMOTE_ADDR'];
        $this->db->query("INSERT INTO " . DB_PREFIX . "p_quote_option_save_value SET name = '" . $this->db->escape($data['name']) . "', city = '" . $this->db->escape($data['city']) . "', email = '" . $this->db->escape($data['email']) . "', phone = '" . $this->db->escape($data['phone']) . "',comment = '" . $this->db->escape($data['comment']) . "',ip = '" . $ip . "', file = '" . $this->db->escape($value) . "'");
        $p_q_o_value_id = $this->db->getLastId();
        foreach ($product_data_info as $product_data) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "p_quote_option_save  SET product_id = '" . (int)($product_data['product_id']) . "', product_name = '" . $this->db->escape($product_data['name']) . "', image = '" . $this->db->escape($product_data['image']) . "',model = '" . $this->db->escape($product_data['model']) . "',price = '" . $this->db->escape($product_data['price']) . "',quantity = '" . $this->db->escape($product_data['quantity']) . "', quote_options = '" . $this->db->escape($product_data['option_value_json']) . "', p_q_o_value_id = '" .$p_q_o_value_id . "'");
        }
        return $p_q_o_value_id;
    }

    public function deleteproduct($product_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "p_quote_option_save WHERE product_id = '" . (int)$product_id . "'");
        //$this->db->query("DELETE FROM " . DB_PREFIX . "p_quote_option_save_value WHERE p_q_o_value_id = '" . (int)$product_id . "'");
    }
}
