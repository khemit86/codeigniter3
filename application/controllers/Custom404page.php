<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class custom404page extends MX_Controller {
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://manish.devserver1.info/custom404
	 *	- or -  
	 * 		http://manish.devserver1.info/custom404
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct() {
		parent :: __construct();
	} 
	
	public function index()
	{	
		
		set_status_header(404);
		//$this->load->view ('404defaultpage/404_default');
		//$data['current_page'] = '404_default';
		$lay = array();
		########## set the default 404 title meta tag and meta description  start here #########
		$default_404_page_title_meta_tag = $this->config->item('404_page_title_meta_tag');
		$default_404_page_description_meta_tag = $this->config->item('404_page_description_meta_tag');

		
		$data['meta_tag'] = '<title>' . $default_404_page_title_meta_tag . '</title><meta name="description" content="' . $default_404_page_description_meta_tag . '"/>';
		########## set the default 404 title meta tag and meta description  end here #########
		
		
		$this->layout->view ('404defaultpage/404_default', $lay, $data, 'error_404');  
	}
	
	#
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */