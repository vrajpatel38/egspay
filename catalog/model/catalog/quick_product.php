<?php
class ModelCatalogquickproduct extends Model {
    public function getProductColumn($data = array()){
        echo '<pre>';print_r(111111);echo '</pre>';die();
        // $array = array();
        
        // array_push($data,"p.product_id");

        // // $array = $this->db->query("SELECT " . implode(',',$data) ." FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category pc ON (p.product_id = pc.product_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (pc.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "filter_description pfd ON (pf.filter_id = pfd.filter_id) LEFT JOIN " . DB_PREFIX . "manufacturer pm ON (p.manufacturer_id = pm.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_related pr ON (p.product_id = pr.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        // $array = $this->db->query("SELECT " . implode(',',$data) ." FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category pc ON (p.product_id = pc.product_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (pc.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "filter_description pfd ON (pf.filter_id = pfd.filter_id) LEFT JOIN " . DB_PREFIX . "manufacturer pm ON (p.manufacturer_id = pm.manufacturer_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        // if(in_array('p.image',$data)){
        //     $result = $this->db->query("SELECT p.image FROM " . DB_PREFIX . " product p");
        // }

        return $array;
    }
}