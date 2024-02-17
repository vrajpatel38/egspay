<?php
class ControllerExtensionModuleeditlanguage extends Controller {
    private $error = array();
    private $default = 'en-gb';
    private $directory;
    private  $catalog_data = array();
    private  $user_token = '';
    private  $route_url = '';
    private $user_token_str = '';
    public $permission = array(
        'editlanguage/editlanguage',
    );
    function __construct($registry){
        parent::__construct($registry);
        $this->load->model('user/user_group');
        $this->load->model('setting/setting');
        if(isset($this->session->data['user_token']) && $this->session->data['user_token']){
            $this->user_token_no = $this->session->data['user_token'];
            $this->user_token =  'user_token='.$this->user_token_no;
            $this->route_url = 'editlanguage/editlanguage'; 
        }else{
            $this->user_token_no = $this->session->data['token'];
            $this->user_token =  'token='.$this->user_token_no;
            $this->route_url = 'module/editlanguage';
            if($this->octlversion() == '2302' || $this->octlversion() < '1600')
            {
                $this->route_url = 'editlanguage/editlanguage'; 
            }   
        }
    }
    public function octlversion(){
        $varray = explode('.', VERSION);
        return (int)implode('', $varray);
    }
    public function install() {
        $group_id = $this->user->getGroupId();
        foreach ($this->permission as $value){
            $this->model_user_user_group->addPermission($group_id, 'access',$value);
            $this->model_user_user_group->addPermission($group_id, 'modify',$value);
        }
    } 
    public function uninstall() {
        $group_id = $this->user->getGroupId();
        foreach ($this->permission as $value){
            $this->model_user_user_group->removePermission($group_id, 'access',$value);
            $this->model_user_user_group->removePermission($group_id, 'modify',$value);
        }
    } 
    public function index() { 
        $this->load->model('setting/setting');
        $this->load->language($this->route_url);
        $this->load->language('editlanguage/editlanguage');

        $data['heading_title'] = $this->language->get('heading_title');
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $this->load->language('extension/module/editlanguage');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_language'] = $this->language->get('text_language');
        $data['text_language_file'] = $this->language->get('text_language_file');
        $data['text_twig'] = $this->language->get('text_twig');
        $data['help_copy_file'] = $this->language->get('help_copy_file');           
        $data['text_loading'] = $this->language->get('text_loading');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_reset'] = $this->language->get('button_reset');
        $data['text_confirm'] = $this->language->get('text_confirm');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_editlanguage', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            if($this->octlversion() > 2000){
                $this->response->redirect(html_entity_decode($this->url->link($this->route_url, $this->user_token . '&type=module', true)));
            }else{
                $this->response->redirect(html_entity_decode($this->url->link("extension/module", $this->user_token, true)));
            }
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', $this->user_token , true),
            'separator' => false,
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_module'),
            'href' => $this->url->link('extension/module', $this->user_token , true),
            'separator' => ' :: ',  
        );

        if($this->octlversion() >= 2302){
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('text_extension'),
                'href' => $this->url->link($this->route_url, $this->user_token , true),
                'separator' => ' :: ',
            );          
        }

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link($this->route_url, $this->user_token , true),
            'separator' => ' :: ',
        );

        $data['token'] = $this->user_token_no;
        $data['token_str'] = $this->user_token;
        
        $data['action'] = html_entity_decode($this->url->link($this->route_url, $this->user_token , true));
        $data['route'] = $this->route_url;
        $data['el_route'] = "editlanguage/editlanguage";
        $data['text_cancel'] = $this->language->get('text_cancel');
        $data['cancel'] = $this->url->link($this->route_url, $this->user_token  , true);     

        if (isset($this->request->post['module_editlanguage_status'])) {
            $data['module_editlanguage_status'] = $this->request->post['module_editlanguage_status'];
        } else {
            $data['module_editlanguage_status'] = $this->config->get('module_editlanguage_status');
        }


        $path_data = array();
        $files = glob(rtrim(DIR_CATALOG . 'language/', '/') . '/*', GLOB_BRACE);
        if ($files) {
            foreach($files as $file) {
                if (!in_array(basename($file), $path_data))  {
                    if (is_dir($file)) {
                        $data['directory'][] = array(
                            'name' => basename($file),  
                        );
                    }   
                }
            }
        }
        if(version_compare(VERSION, '2.0.0.0') < 0){
            $template = 'editlanguage/editlanguage_n.tpl';
            $this->data = $data;
            $this->template = $template;
            $this->children = array(
                'common/header',
                'common/footer'
            );
            $this->response->setOutput($this->render());
        }else{
            $template = 'module/editlanguage.tpl';
            if(version_compare('2.2.0.0', VERSION) <= 0){
                $template = 'editlanguage/editlanguage';
            }
            $data['header'] = $this->load->controller('common/header');
            if($this->octlversion() == 2000){
                $data['menu'] = $this->load->controller('common/menu');
            }else{
                $data['column_left'] = $this->load->controller('common/column_left');
            }
            $data['footer'] = $this->load->controller('common/footer');
            $this->response->setOutput($this->load->view($template, $data));
        }
    }
    public function getlist() {
        $this->load->language('editlanguage/editlanguage');

        $json = array();

        if (isset($this->request->get['module_lang']) && !empty($this->request->get['module_lang'])) {
            $language = $this->request->get['module_lang'];
        }else{
            $language = "";
        }
        if(isset($this->request->get['path'])) {
            $path = $this->request->get['path'];
        } else {
            $path = '';
        }
        
        if (substr(str_replace('\\', '/', realpath(DIR_CATALOG . 'language/'.$language.'/' . $path)), 0, strlen(DIR_CATALOG . 'language/')) == DIR_CATALOG .'language/') {						
			$path_data = array();
			$files = glob(rtrim(DIR_CATALOG . 'language/{' .$language . '}/' . $path, '/') . '/*', GLOB_BRACE);
			if ($files) {
				foreach($files as $file) {
					if (!in_array(basename($file), $path_data))  {
						if (is_dir($file) && basename($file) != 'backup' ) {
							$json['directory'][] = array(
								'name' => basename($file),
								'path' => trim($path . '/' . basename($file), '/')
							);
						}

						if (is_file($file) && substr($file, -4) == '.php') {
							$json['file'][] = array(
								'name' => basename($file),
								'path' => trim($path . '/' . basename($file), '/')
							);
						}
						$path_data[] = basename($file);
					}
				}
			} 

			$json['missing_file'] = [];
			if($language != $this->config->get('config_language') ){

				$default_lang = glob(rtrim(DIR_CATALOG . 'language/'. $this->config->get('config_language').'/'. $path, '/') . '/*', GLOB_BRACE);

				if($default_lang){
					foreach($default_lang as $miss_files){
						if(!in_array(basename($miss_files), $path_data)){
							if (is_dir($miss_files) && basename($miss_files) != 'backup') {
								$json['missing_file'][] = array(
									'name' => basename($miss_files),
									'path' => trim($path . '/' . basename($miss_files), '/')
								);
							}

							if (is_file($miss_files) && substr($miss_files, -4) == '.php') {
								$json['missing_file'][] = array(
									'name' => basename($miss_files),
									'path' => trim($path . '/' . basename($miss_files), '/')
								);
							}
						}
					}
				}
			}
		}
        if (!empty($this->request->get['path'])) {
            $json['back'] = array(
                'name'    => $this->language->get('button_back'),
                'path'    => urlencode(substr($path, 0, strrpos($path, '/'))),
            );
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    protected function validate() {     
        
        if (!$this->user->hasPermission('modify', $this->route_url)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
    public function display_design() {
        $this->load->language($this->route_url);

        $json = array();

        if (isset($this->request->get['module_lang'])) {
            $language = $this->request->get['module_lang'];
        } else {
            $language = '';
        }
        if (isset($this->request->get['path'])) {
            $path = $this->request->get['path'];
        } else {
            $path = '';
        }

        if ($language) {

            $pos = substr($path, 0, strrpos($path, "."));

            $lang_data = $this->catalogload($language.'/'.$pos);
            $json['code'] = $lang_data;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }   
    public function copy_file() {
        $this->load->language($this->route_url);
        $json = array();

        if (isset($this->request->get['module_lang'])) {
            $language = $this->request->get['module_lang'];
        } else {
            $language = '';
        }
        if (isset($this->request->get['path'])) {
            $path = $this->request->get['path'];
        } else {
            $path = '';
        }
        $directory = DIR_CATALOG . 'language/';
        if(is_file($directory.$this->config->get('config_language').'/' . $path) ){
            copy (DIR_CATALOG . 'language/'.$this->config->get('config_language').'/'.$path,DIR_CATALOG . 'language/'.$language.'/'.$path);

            $pos = substr($path, 0, strrpos($path, "."));

            $lang_data = $this->catalogload($pos);
            $json['code'] = $lang_data;

        }elseif(is_dir($directory.$this->config->get('config_language').'/' . $path)){
            $this->copy_directory(DIR_CATALOG .'language/'.$this->config->get('config_language').'/'.$path,DIR_CATALOG . 'language/'.$language.'/'.$path);
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function save() {
        $this->load->language('editlanguage/editlanguage');

        $json = array();

        if (isset($this->request->get['module_lang'])) {
            $language = $this->request->get['module_lang'];
        } else {
            $language = '';
        }

        if (isset($this->request->get['path'])) {
            $path = $this->request->get['path'];
        } else {
            $path = '';
        }   

        if (!$this->user->hasPermission('modify', $this->route_url) || !$this->config->get('module_editlanguage_status')) {
            $json['error'] = $this->language->get('error_warning');
        }   
        if (!$json) {
            $directory = DIR_CATALOG . 'language/' . $language . '/' . 'backup/';
            if(!is_dir($directory)){
                mkdir (DIR_CATALOG . 'language/' . $language. '/' . 'backup/' , "0777");
            }
            $file = DIR_CATALOG . 'language/'.$language.'/' . $path;

            $array_folder = explode("/" , $path);
            $mkyourfolder = "";
            foreach ($array_folder as $folder) {
                if(substr($folder, -4) != '.php'){
                    $mkyourfolder =  $mkyourfolder . $folder . "/";
                    if (!is_dir($directory . $mkyourfolder)) {
                        mkdir($directory . $mkyourfolder, "0777");
                    }
                }else{
                    $mkyourfolder =  $mkyourfolder . $folder;
                    if(!is_file($directory . $mkyourfolder)){
                        copy($file,$directory.$mkyourfolder);
                    }
                }
            }

            $obj       = json_decode(html_entity_decode($_POST['myData']), true);
            $label     = json_decode(html_entity_decode($_POST['myLabel']), true);

            if(count($obj) == count($label)){

                $new_array = array_combine( $label, $obj ); 

                file_put_contents($file, '');
                $myfile = fopen($file, "a");
                fwrite($myfile  , "<?php ");

                foreach($new_array as $key => $value){
                    if(preg_match('/^([a-z0-9\s\_\-]+)$/', $key)){
                        $label_string = preg_replace('/ - /',"']['",$key);
                        $label_string = preg_replace('/\s/','', $label_string);
                        $value = str_replace("'", "\'", $value);
                        $var    = "\n\$_['$label_string'] = '$value';";
                        $myfile = fopen($file, "a");

                        fwrite($myfile  , "$var");
                        $json['success'] = $this->language->get('text_success');
                    }else{
                        $json['error'] = 'You do not entered special character';
                    }
                }
                fclose($myfile);
            }else{
                $json['warning'] = $this->language->get('error_blank_value');
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function reset() {
        $this->load->language('editlanguage/editlanguage');

        $json = array();

        if (isset($this->request->get['module_lang'])) {
            $language = $this->request->get['module_lang'];
        } else {
            $language = '';
        }
        if (isset($this->request->get['path'])) {
            $path = $this->request->get['path'];
        } else {
            $path = '';
        }
        if (is_file(DIR_CATALOG . 'language/'.$language.'/' . $path) && (substr(str_replace('\\', '/', realpath(DIR_CATALOG . 'language/'.$language.'/' . $path)), 0, strlen(DIR_CATALOG . 'language')) == DIR_CATALOG . 'language')) {

            if(is_file(DIR_CATALOG . 'language/'.$language.'/backup/'.$path)){
                copy (DIR_CATALOG . 'language/'.$language.'/backup/'.$path , DIR_CATALOG . 'language/'.$language.'/' . $path);
            }

            $pos = substr($path, 0, strrpos($path, "."));

            $lang_data = $this->catalogload($language.'/'.$pos);
            $json['code'] = $lang_data;
            $json['success']  = $this->language->get('reset_success'); 
        }else{
            $json['error']    = $this->language->get('error_reset'); 
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function copy_directory($src,$dst) {
        $dir = opendir($src);
        mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->copy_directory($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
    public function catalogload($filename , $key = '') {
        if (!$key) {
            $_ = array();

            $file = DIR_CATALOG .'language/'. $this->default . '/' . $filename . '.php';

            if (is_file($file)) {
                require($file);
            }

            $file = DIR_CATALOG .'language/'. $this->directory . '/' . $filename . '.php';

            if (is_file($file)) {
                require($file);
            } 

            $this->catalog_data = array_merge($this->catalog_data, $_);
        } else {
            // Put the language into a sub key
            $this->catalog_data[$key] = new Language($this->directory);
            $this->catalog_data[$key]->catalogload($filename);
        }
        return $this->catalog_data;
    }
    public function create_folder($data = array()){
        $this->load->language('editlanguage/editlanguage');
        $json = array();

        if (isset($this->request->get['module_lang'])) {
            $language = $this->request->get['module_lang'];
        } else {
            $language = '';
        }

        if (isset($this->request->get['path']) && $this->request->get['path'] != 'undefined') {
            $path = $this->request->get['path'];
        } else {
            $path = '';
        } 

        if (!$this->user->hasPermission('modify', $this->route_url) || !$this->config->get('module_editlanguage_status')) {
            $json['error'] = $this->language->get('error_warning');
        }

        if(!$json){
            $directory_name = $this->request->get['name'];
            $directory = DIR_CATALOG . 'language/' . $language . '/' . $path . '/' . $directory_name ;
            if(!is_dir($directory)){
                mkdir (DIR_CATALOG . 'language/' . $language. '/' . $path . '/' . $directory_name , "0777");
            }    
            $json['success'] = true;        
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
    public function create_file(){
        $this->load->language('editlanguage/editlanguage');
        $json = array();

        if (isset($this->request->get['module_lang'])) {
            $language = $this->request->get['module_lang'];
        } else {
            $language = '';
        }

        if (isset($this->request->get['path']) && $this->request->get['path'] != 'undefined') {
            $path = $this->request->get['path'];
        } else {
            $path = '';
        }  

        if (!$this->user->hasPermission('modify', $this->route_url) || !$this->config->get('module_editlanguage_status')) {
            $json['error'] = $this->language->get('error_warning');
        }
        
        if(!$json){
            $file = $this->request->post['name'];
            $file = DIR_CATALOG . 'language/' . $language . '/' . $path . '/' . $file . '.php' ;
            if(!is_file($file)){
                $myfile = fopen($file,'w');
                fclose($myfile);
            }

            $json['success'] = true;        
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
class ControllerModuleeditlanguage extends ControllerExtensionModuleeditlanguage {
    function __construct($registry){
        parent::__construct($registry);
    }   
}
class ControllerEditlanguageEditlanguage extends ControllerExtensionModuleeditlanguage {
    function __construct($registry){
        parent::__construct($registry);
    }   
}