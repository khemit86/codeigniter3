<?php

/////////////////////////////////////////////////////
///						  ///
///		Send Mail Thorugh Mail Template   ///
///		Company: ScriptGiant Technology	  ///
///		Author: Samim Almamun		  ///
///		Type: Library	 		  ///
///						  ///
/////////////////////////////////////////////////////
///////////// NOTE //////////////////////////////////
/*
  DB Table Stucture:
  type(varchar),subject(varchar),template(text),status(Y,N)

  from = samim@gmail.com, to = "anyone@gmail.com",
  templete_type = <template_type>
  param = array( "{name}" => "Samim Almamun" )

  Function call:
  send_mail($from,$to,$templete_type,$param)
 */
/////////////////////////////////////////////////////

class Mailtemplete {

    private $CI;

    public function __construct() {
        $CI = &get_instance();
        $config = Array(
            'mailtype' => 'html'
        );
        $CI->load->library('email', $config);
    }

}

?>