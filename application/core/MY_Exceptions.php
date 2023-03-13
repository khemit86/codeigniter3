<?php (defined('BASEPATH')) OR exit('No direct script access allowed');



class MY_Exceptions extends CI_Exceptions  {


	public function __construct()
    {
		
        parent::__construct();
		
    }

	public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		
		
		/* $log_threshold_value = config_item('log_threshold'); */
		if(ENVIRONMENT == 'production' && $status_code == 400){
			$template = 'error_general_production';
		}if(ENVIRONMENT == 'production' && $status_code == 404){
			$template = 'error_404_production';
		}if(ENVIRONMENT == 'production' && $status_code == 500){
			$template = 'error_db_production';
		}
		
		$templates_path = config_item('error_views_path');
		if (empty($templates_path))
		{
			$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;
		}

		if (is_cli())
		{
			$message = "\t".(is_array($message) ? implode("\n\t", $message) : $message);
			$template = 'cli'.DIRECTORY_SEPARATOR.$template;
		}
		else
		{
			set_status_header($status_code);
			$message = '<p>'.(is_array($message) ? implode('</p><p>', $message) : $message).'</p>';
			$template = 'html'.DIRECTORY_SEPARATOR.$template;
		}

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();
		include($templates_path.$template.'.php');
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	
	
	public function show_404($page = '', $log_error = TRUE)
	{
			
		if (is_cli())
		{
			$heading = 'Not Found';
			$message = 'The controller/method pair you requested was not found.';
		}
		else
		{
			$heading = '404 Page Not Found';
			$message = 'The page you requested was not found.';
		}

		// By default we log this, but allow a dev to skip it
		if ($log_error)
		{
			log_message('error', $heading.': '.$page);
		}

		echo $this->show_error($heading, $message, 'error_404', 404);
		exit(4); // EXIT_UNKNOWN_FILE
	}
	
	
	public function show_php_error($severity, $message, $filepath, $line)
	{
				
		//$log_threshold_value = config_item('log_threshold');
		$templates_path = config_item('error_views_path');
		if (empty($templates_path))
		{
			$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;
		}

		$severity = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;

		// For safety reasons we don't show the full file path in non-CLI requests
		if ( ! is_cli())
		{
			$filepath = str_replace('\\', '/', $filepath);
			if (FALSE !== strpos($filepath, '/'))
			{
				$x = explode('/', $filepath);
				$filepath = $x[count($x)-2].'/'.end($x);
			}
			if(ENVIRONMENT == 'production'){
				$template = 'html'.DIRECTORY_SEPARATOR.'error_php_production';
			}else{ 
			$template = 'html'.DIRECTORY_SEPARATOR.'error_php';
			}
		}
		else
		{
			$template = 'cli'.DIRECTORY_SEPARATOR.'error_php';
		}

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();
		include($templates_path.$template.'.php');
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}
	
	public function show_exception($exception)
	{
			
		if(ENVIRONMENT == 'production'){
			$template = 'error_exception_production.php';
		}else{
			$template =	'error_exception.php';
		}
		$templates_path = config_item('error_views_path');
		if (empty($templates_path))
		{
			$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;
		}

		$message = $exception->getMessage();
		if (empty($message))
		{
			$message = '(null)';
		}

		if (is_cli())
		{
			$templates_path .= 'cli'.DIRECTORY_SEPARATOR;
		}
		else
		{
			$templates_path .= 'html'.DIRECTORY_SEPARATOR;
		}

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		

		ob_start();
		include($templates_path.$template);
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}
	
	
	
}