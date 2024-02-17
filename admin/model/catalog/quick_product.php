<?php
class ModelCatalogquickproduct extends Model {
    public function getProductColumn($data = array()){
        $array = array();

        array_push($data,"p.product_id");

        // $array = $this->db->query("SELECT " . implode(',',$data) ." FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category pc ON (p.product_id = pc.product_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (pc.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "filter_description pfd ON (pf.filter_id = pfd.filter_id) LEFT JOIN " . DB_PREFIX . "manufacturer pm ON (p.manufacturer_id = pm.manufacturer_id) LEFT JOIN " . DB_PREFIX . "product_related pr ON (p.product_id = pr.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        $array = $this->db->query("SELECT " . implode(',',$data) ." FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category pc ON (p.product_id = pc.product_id) LEFT JOIN " . DB_PREFIX . "category_description cd ON (pc.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "filter_description pfd ON (pf.filter_id = pfd.filter_id) LEFT JOIN " . DB_PREFIX . "manufacturer pm ON (p.manufacturer_id = pm.manufacturer_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        $main_array = array();
        if(in_array('p.image',$data)){
            $image_results = $this->db->query("SELECT image FROM " . DB_PREFIX . "product p");          
            foreach($image_results->rows as $result){
                $main_array['image'][] = $result['image'];
            }  
        }
        if(in_array('pd.name',$data)){
            $product_name_results = $this->db->query("SELECT pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)");
            foreach($product_name_results->rows as $result){
                $main_array['product_name'][] = $result['name'];
            }
        }
        if(in_array('pd.description',$data)){
            $product_name_results = $this->db->query("SELECT pd.description FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)");
            foreach($product_name_results->rows as $result){
                $main_array['product_description'][] = $result['description'];
            }
        }
        if(in_array('pd.meta_title',$data)){
            $meta_title_results = $this->db->query("SELECT pd.meta_title FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)");
            foreach($meta_title_results->rows as $result){
                $main_array['meta_title'][] = trim($result['meta_title']);
            }
        }
        
        echo '<pre>';print_r($main_array);echo '</pre>';die();
        
        
        
        return $array;
    }
}