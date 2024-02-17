<?php
class ControllerCommonColumnLeft extends Controller {

		protected function octlversion(){
	    	$varray = explode('.', VERSION);
	    	return (int)implode('', $varray);
		}
		
	public function index() {

			$thisvar = $this->octlversion();
		
		if (isset($this->request->get['token']) && isset($this->session->data['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$data['profile'] = $this->load->controller('common/profile');
			$data['menu'] = $this->load->controller('common/menu');
			$data['stats'] = $this->load->controller('common/stats');

			return $this->load->view('common/column_left', $data);
		}
	}
}