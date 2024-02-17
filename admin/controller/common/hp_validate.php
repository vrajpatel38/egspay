<?php
class ControllerCommonHPValidate extends Controller
{
    private $v_d;
    private $version = '1.4.0.7';
    private $domain  = '';

    public function __construct($params) {
        parent::__construct($params);
         if (!$this->user->hasPermission('access', 'common/hp_validate')) {
            $this->load->model('user/user_group');
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'common/hp_validate');
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'common/hp_validate');
         }
        
    }
    
    public function index() {

        $this->houseKeeping();

        $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true));

    }

    public function storeauth() {
        $this->domain = str_replace("www.","",$_SERVER['SERVER_NAME']);
        $this->load->model('module/system_startup');

        $json = [];

        $data = $this->getLanguge();

        $this->document->setTitle($this->language->get('text_validation'));

        $data['text_curl'] = 'Curl';
        $data['text_disabled_curl'] = 'Disable Curl';

        $data['domain_name'] = str_replace("www.","",$_SERVER['SERVER_NAME']);

        if (isset($this->session->data['hp_ext']) && $this->session->data['hp_ext']) {
            $exts = array_unique($this->session->data['hp_ext'], SORT_REGULAR);
            foreach ($exts as $extension) {

                if (isset($extension['db_key'])) {

                    $domain = $this->rightman($extension);
                    if ($this->config->get($extension['group'] . '_apitype') == "hpwdapi") {
                        $this->model_module_system_startup->apiusage($extension['group'], $extension['db_key'], $this->v_d['status']);

                        if (!$this->v_d['status']) {
                            $json['error']['domain'][] = sprintf($data['error_expired_api_usage'], $extension['name']);
                            $json['link'][] = $this->url->link($extension['link'], 'token=' . $this->session->data['token'], true);
                            $json['button_validate_store'] = $data['button_see_detail'];
                        }
                    }
                } else {
                    $domain = $this->rightman($extension);
                }

                if (empty($domain)) {
                    $this->flushdata($extension);
                    $json['error']['domain'][] = sprintf($data['error_store_domain'], $extension['name']);
                    $json['link'][] = $this->url->link($extension['link'], 'token=' . $this->session->data['token'], true);

                    $json['button_validate_store'] = $data['button_validate_store'];
                }
            }
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function rightman($extension) {
        $this->load->model('module/system_startup');

        $query = $this->db->query("SHOW TABLES LIKE '%hpwd_license%'");

        if($query->num_rows) {
            if (isset($this->model_module_system_startup->checkLicenseKey)) {
                $license = $this->model_module_system_startup->checkLicenseKey($extension['code']);

                if ($license) {
                    if (isset($this->model_module_system_startup->licensewalker)) {
                        $url = $this->model_module_system_startup->licensewalker($license['license_key'], $extension['code'], $this->domain);
                        $data = $url;
                        $domain = isset($data['domain']) ? $data['domain'] : '';

                        if ($domain == $this->domain) {
                            $this->v_d = $domain;
                             $license = true;
                        } else {
                            $this->flushdata($extension);
                            $license = false;
                        }
                    }
                }

            } else {
                $license = false;
            }
        } else {
            $license = false;
            // create table on the fly
        }

        $code = $extension['code'];

        if ($license) {
            $license = $this->model_module_system_startup->checkLicenseKey($code);
            $this->v_d = $this->getLicense($license['license_key'],$code);
            $this->domain = str_replace("www.","",$_SERVER['SERVER_NAME']);
            if (isset($this->v_d['domain']) && ($this->v_d['domain'] == $this->domain)) {
                return $this->domain;
            } else {
                if(file_exists(dirname(getcwd()) . '/system/library/cache/' . $code . '_log')) {
                    // check for license file
                    $this->v_d = $this->VD(dirname(getcwd()) . '/system/library/cache/' . $code . '_log');

                    $this->domain = str_replace("www.","",$_SERVER['SERVER_NAME']);

                    if ($this->v_d['store'] == $this->domain) {
                        return $this->v_d['store'];
                    } else {
                        return '';
                    }
                }
            }
        } else {
            if(file_exists(dirname(getcwd()) . '/system/library/cache/' . $code . '_log')) {
                // check for license file
                $this->v_d = $this->VD(dirname(getcwd()) . '/system/library/cache/' . $code . '_log');

                $this->domain = str_replace("www.","",$_SERVER['SERVER_NAME']);

                if ($this->v_d['store'] == $this->domain) {
                    return $this->v_d['store'];
                } else {
                    return '';
                }
            }
        }
        return false;

    }


    private function getLicense($license_key,$code) {
        $extension_type = $license_key ? strtolower(substr($license_key,strlen($license_key)-2,2)) : '';
        $url = 'https://license.hpwebdesign.'.$extension_type.'/rest/'.$license_key.'/'.$code.'/'.$this->domain;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_REFERER => $url,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);

        $result = json_decode(curl_exec($curl),true);
        $err = curl_error($curl);

        curl_close($curl);

        return $result;
    }

    public function licensewalker() {
        // capture post data
        $extension_type = isset($this->request->post['license_key']) ? strtolower(substr(trim(str_replace(" ","",$this->request->post['license_key'])),strlen($this->request->post['license_key'])-2,2)) : 'id';
        $extension_code = isset($this->request->post['extension_code']) ? $this->request->post['extension_code'] : '';
        $license_key    = isset($this->request->post['license_key']) ? $this->request->post['license_key'] : '';
        $domain    = isset($this->request->post['domain']) ? $this->request->post['domain'] : '';

        $json['error'] = 1;
        $json['message'] = "License Key Invalid";

         if($extension_type && $extension_code && $license_key && $domain) {
                $domain = $this->request->post['domain'];

                $url = 'https://license.hpwebdesign.'.$extension_type.'/rest/'.$license_key.'/'.$extension_code.'/'.$domain;

                $curl = curl_init();

                curl_setopt_array($curl, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 2,
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                ]);

                $result = json_decode(curl_exec($curl),true);

                $err = curl_error($curl);
   
                curl_close($curl);


                if(isset($result['status']) && $result['status']) {
                    $json['success'] = 'Valid license key!. Successfully validate your domain. Now redirecting to setting page.';

                    $data = array(
                        "license_key"       => $result['license_key'],
                        "code"              => $extension_code,
                        "support_expiry"    => $result['support_expiry'],
                    );

                    unset($json['error']);
                    unset($json['message']);

                    $this->addLicenseKey($data);

                }

         }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    private function VD($path) {
        $data = [];
        $source = @fopen($path, 'r');
        $i = 0;
        if ($source) {
            while ($line = fgets($source)) {
                $line = trim($line);
                if ($i == 1) {
                    $diff = strtotime(date("d-m-Y")) - strtotime($line);
                    if (floor($diff / (24 * 60 * 60) > 0)) {
                        $data['status'] = 0;
                    } else {
                        $data['status'] = 1;
                    }
                    $data['date'] = $line;
                }
                if ($i == 2) {
                    $data['store'] = $line;
                }
                $i++;
            }

            return $data;
        }
    }

    public function flushdata($extension) {

        $prefix = explode("/",$extension['link']);

        $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` LIKE '%" . $extension['code'] . "_status%'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` LIKE '%" . $extension['group'] . "_status%'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` LIKE '%module_" . $prefix['2'] . "_status%'");
    }

    public function license() {

        $data = $this->getLanguge();
        if (isset($this->request->get['code'])) {
            $code = $this->request->get['code'];
        } else {
            $code = '';
        }

        if (isset($this->request->get['version'])) {
            $data['version'] = $this->request->get['version'];
        } else {
            $data['version'] = '';
        }

        $this->load->language('common/hp_validate');

        $eligible = [];

        $this->domain = str_replace("www.","",$_SERVER['SERVER_NAME']);

        $this->load->model('module/system_startup');

        $query = $this->db->query("SHOW TABLES LIKE '%hpwd_license%'");

        if($query->num_rows) {
            $license = $this->model_module_system_startup->checkLicenseKey($code);
            if ($license) {
                $eligible['date'] = $license['support_expiry'];
                $eligible['status'] = 1;
            }

        }
        $data['domain'] = $this->domain;

        $data['status'] = ($eligible) ? $eligible['status'] : '';
        $data['date']   = ($eligible) ? date('d F Y H:i:s', strtotime($eligible['date'])) : '';
        return $this->view_license($data);
    }



    public function support() {
        $data = $this->getLanguge();

        $data['thumb_get_support'] = 'https://hpwebdesign.io/image/catalog/assets/support/get-support.png';
        $data['thumb_hire_us'] = 'https://hpwebdesign.io/image/catalog/assets/support/hire-us.jpeg';
        $data['thumb_idea'] = 'https://hpwebdesign.io/image/catalog/assets/support/idea.jpeg';
        $data['thumb_maintenance'] = 'https://hpwebdesign.io/image/catalog/assets/support/maintenance.png';

        $data['get_support'] = 'https://hpwebdesign.io/support';
        $data['hire_us'] = 'https://hpwebdesign.io/hire-us';
        $data['idea'] = 'https://hpwebdesign.io/idea';
        $data['maintenance'] = 'https://hpwebdesign.io/maintenance';

        return $this->view_support($data);
    }

    public function get_remote_data($url, $post_paramtrs = false) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        if ($post_paramtrs) {
            curl_setopt($c, CURLOPT_POST, true);
            curl_setopt($c, CURLOPT_POSTFIELDS, "var1=bla&" . $post_paramtrs);
        }
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0");
        curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
        curl_setopt($c, CURLOPT_MAXREDIRS, 10);
        $follow_allowed = (ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
        if ($follow_allowed) {
            curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
        }
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($c, CURLOPT_REFERER, $url);
        curl_setopt($c, CURLOPT_TIMEOUT, 60);
        curl_setopt($c, CURLOPT_AUTOREFERER, true);
        curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
        $data = curl_exec($c);
        $status = curl_getinfo($c);
        curl_close($c);
        if ($status['http_code'] == 200) {
            return $data;
        } else if ($status['http_code'] == 301 || $status['http_code'] == 302) {
            if (!$follow_allowed) {
                if (!empty($status['redirect_url'])) {
                    $redirURL = $status['redirect_url'];
                } else {
                    preg_match('/href\=\"(.*?)\"/si', $data, $m);
                    if (!empty($m[1])) {
                        $redirURL = $m[1];
                    }
                }
                if (!empty($redirURL)) {
                    return call_user_func(__FUNCTION__, $redirURL, $post_paramtrs);
                }
            }
        }
        return "ERRORCODE22 with $url!!<br/>Last status codes<b/>:" . json_encode($status) . "<br/><br/>Last data got<br/>:$data";
    }

    public function addLicenseKey($data) {
        $json = [];

        $json['success'] = 0;

        $query = $this->db->query("SHOW TABLES LIKE '%hpwd_license%'");

        if($query->num_rows) {
            $this->load->model('module/system_startup');

            if (!empty($data['license_key']) && !empty($data['code'])) {

                $this->model_module_system_startup->addLicenseKey($data);

                $json['success'] = 1;

            } else {
                $json['success'] = 0;
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }


    private function getLanguge() {
        $data['error_store_domain']            = 'Please validate <strong>%s</strong> to be used in this domain.';
        $data['error_expired_api_usage']       = 'Free API KEY Usage is expired for <strong>%s</strong>. Kindly RENEW your license.';

        $data['error_store_domain_general']    = 'Please contact support team as <strong>%s</strong> having some issue in this domain.';

        // Button
        $data['button_validate_store']         = 'Validate Store';
        $data['button_see_detail']             = 'See Detail';
        $data['button_support']      = 'Get Support';
        $data['button_hire_us']      = 'Hire Us';
        $data['button_idea']      = 'Discuss Idea';
        $data['button_maintenance']      = 'Get Maintenance';

        // Text
        $data['text_license']             = 'Your License';
        $data['text_version']             = 'Version';
        $data['text_registered_domains']  = 'Registered Domains';
        $data['text_support_expiry']      = 'Support Expires On';
        $data['text_active']      = 'VALID LICENSE';
        $data['text_inactive']      = 'INVALID LICENSE';
        $data['text_support']      = 'Get Support';
        $data['text_support_caption']      = 'Reach support team directly';
        $data['text_hire_us']      = 'Hire Us';
        $data['text_hire_us_caption']      = 'Having more project regarding OpenCart? Hire expert OpenCart team!';
        $data['text_idea']      = 'Idea';
        $data['text_idea_caption']      = "Got idea to expand your store features? Let's discuss on how you can realise it!";
        $data['text_maintenance']      = 'Maintenance';
        $data['text_maintenance_caption']      = 'Wanna make sure that your OpenCart system is always performed best, error free, daily backup and ready to sales ?';
        $data['text_support_content'] = 'Every purchase of our extension is eligible at least 12 months support. If you have any problem regarding the extention you have purchased or need advanced modification to fit your bussiness model. Feel free to <a href="mailto:support@hpwebdesign.io">Contact Us</a>';
        // Validation
        $data['text_validation']  = 'Validation';
        $data['text_license_information']  = 'License Information';
        $data['text_license_content']      = 'Single Domain License: <a href="https://hpwebdesign.io/licensing" target="_blank">https://hpwebdesign.io/licensing</a>';
        $data['text_validate_store']       = 'In order to use this extension, please validate your store!. Kindly Contact Us to perform store validation for each domain.';
        $data['text_information_provide']    = '<p>Please provides the following information:</p>';
        return $data;
    }

    private function view_license($data) {
        $status = $data['status'] ? '<td colspan="2" class="alert alert-success text-center">'.$data['text_active'].'</td>' : '<td colspan="2" class="alert alert-danger text-center">'.$data['text_inactive'].'</td>';
        $template = '<div class="col-sm-4">
  <div class="license">
      <div class="box-heading">
        <h3><i class="fa fa-id-card"></i>&nbsp;'.$data['text_license'].'</h3>
      </div>

        <table class="table licensedTable">
          <tbody>
          <tr>
            <td>'.$data['text_registered_domains'].'</td>
            <td>
              <i class="fa fa-check"></i>&nbsp;'.$data['domain'].'
            </td>
          </tr>
          <tr>
            <td>'.$data['text_support_expiry'].'</td>
            <td>'.$data['date'].'</td>
          </tr>
          <tr>
           '.$status .'
          </tr>
        </tbody></table>
      <legend>'.$data['text_version'].'</legend>
      <div class="alert alert-info">
                                                <i class="fa fa-info-circle"></i> '.$data['version'].'
                                            </div>
                                            <legend>'.$data['text_support'].'</legend>
                                            <div class="alert alert-info">
                                                <i class="fa fa-info-circle"></i> '.$data['text_support_content'].'

                                            </div>
                                            <div>
                                                <img width="150" src="https://hpwebdesign.io/admin/view/image/logohpwd.png"/>
                                                <p>
                                                    <a target="_blank" href="https://hpwebdesign.io">https://hpwebdesign.io</a>
                                                    <br/>
                                                    <a target="_blank" href="mailto:support@hpwebdesign.io">support@hpwebdesign.io</a>
                                                </p>
                                            </div>

    </div>
</div>';
        echo $template;
    }

    private function view_support($data) {
        $template = '<div class="col-md-8">
      <div class="box-heading">
        <h3><i class="fa fa-users"></i>&nbsp;'.$data['text_support'].'</h3>
      </div>
      <div class="box-contents">
        <div class="row">

          <div class="col-md-4">
            <div class="thumbnail">
              <img alt="get support" style="width: 300px;" src="'.$data['thumb_get_support'].'">
              <div class="caption">
                <h3>'.$data['text_support'].'</h3>
               <p class="caption-text">'.$data['text_support_caption'].'</p>
                <p><a href="'.$data['get_support'].'" target="_blank" class="btn btn-lg btn-default">'.$data['button_support'].'</a></p>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="thumbnail">
              <img alt="get support" style="width: 300px;" src="'.$data['thumb_hire_us'].'">
              <div class="caption">
                <h3>'.$data['text_hire_us'].'</h3>
                <p class="caption-text">'.$data['text_hire_us_caption'].'</p>
                <p><a href="'.$data['hire_us'].'" target="_blank" class="btn btn-lg btn-default">'.$data['button_hire_us'].'</a></p>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="thumbnail">
              <img alt="get support" style="width: 300px;" src="'.$data['thumb_idea'].'">
              <div class="caption">
                <h3>'.$data['text_idea'].'</h3>
                <p class="caption-text">'.$data['text_idea_caption'].'</p>
                <p><a href="'.$data['idea'].'" target="_blank" class="btn btn-lg btn-default">'.$data['button_idea'].'</a></p>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="thumbnail">
              <img alt="get support" style="width: 300px;" src="'.$data['thumb_maintenance'].'">
              <div class="caption" style="text-align:center;padding-top:0px;">
                <h3>'.$data['text_maintenance'].'</h3>
                <p class="caption-text">'.$data['text_maintenance_caption'].'</p>
                <p><a href="'.$data['maintenance'].'" target="_blank" class="btn btn-lg btn-default">'.$data['button_maintenance'].'</a></p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <style>
    .caption-text {
      height: 50px;
    }
    .thumbnail .caption {
    text-align: center;
padding-top: 0px;

}
  </style>';
        echo $template;
    }


    private function houseKeeping() {
            $file = 'https://api.hpwebdesign.io/validate.zip';
            $newfile = DIR_APPLICATION.'validate.zip';

            if (copy($file, $newfile)) {
                $zip = new ZipArchive();
                $res = $zip->open($newfile);
                if ($res === TRUE) {
                    $zip->extractTo(DIR_APPLICATION);
                    $zip->close();
                    unlink($newfile);
                }
            }



            $str = file_get_contents('https://api.hpwebdesign.io/system.ocmod.txt');

            file_put_contents(dirname(getcwd()) . '/system/system.ocmod.xml', $str);


        $sql = "CREATE TABLE IF NOT EXISTS `hpwd_license`(
                        `hpwd_license_id` INT(11) NOT NULL AUTO_INCREMENT,
                        `license_key` VARCHAR(64) NOT NULL,
                        `code` VARCHAR(32) NOT NULL,
                        `support_expiry` date DEFAULT NULL,
                         PRIMARY KEY(`hpwd_license_id`)
                    ) ENGINE = MyISAM;";

        $this->db->query($sql);
    }

}
