<?php
class CheckAjaxRequest {
    private $_controllers = [];

    private $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    public function check_request_type_ajax() {
        $controller = $this->CI->router->fetch_class();
       $method = $this->CI->router->fetch_method();
		
		
		/*Examples
		 * $_controllers = [
		 *      'my_controller_name' => TRUE //all methods must be ajax
		 *      'my_controller_name  => [
		 *          'method_name' => TRUE //only the selected methods must be ajax
		 *      ]
		 * ]
		 */
		
		/* $this->_controllers = [
			 
			 'dashboard'  => [
				'changePassword' => TRUE, 
				'edit_field' => TRUE, 
				'save_new_expertise' => TRUE 
			 ]
			];
		
		
        if ( array_key_exists( $controller, $this->_controllers ) && $this->CI->input->is_ajax_request() === FALSE  ) {
           if ( ( $this->_controllers[ $controller ] === TRUE || ( is_array( $this->_controllers[ $controller ] ) && array_key_exists( $method, $this->_controllers[ $controller ] ) && $this->_controllers[ $controller ][ $method ] === TRUE ) ) ) {
				
				 exit($this->CI->load->view('404defaultpage/404_default','', true));
			
		
            }
        } */
    }
	
	
}

	
?>	