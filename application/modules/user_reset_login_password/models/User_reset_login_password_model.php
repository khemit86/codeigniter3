<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class User_reset_login_password_model extends BaseModel
{

    public function __construct ()
    {
        return parent::__construct ();
		$this->load->model ('signin/Signin_model');
    }

	// This function check the email exists or not into the database during the time of enter email in forgot password form.
	public function check_email_exist ($email)
    {
        $this->db->where ("email", $email);
        $en = $this->db->count_all_results ('users');
		
		$count_verify_user_record = $this->db
			->select ('user_id')
			->from ('users')
			->where ('email', $email)
			->get ()->num_rows ();
		/* check already exist email in users_new_registrations_pending_verification table */	
		$count_unverify_user_record = $this->db
			->select ('user_id')
			->from ('users_new_registrations_pending_verification')
			->where ('email', $email)
			->get ()->num_rows ();
		if ($count_verify_user_record > 0 || $count_unverify_user_record > 0)
        {
			if($count_unverify_user_record > 0){
			
				$result = $this->db->get_where('users_new_registrations_pending_verification', ['email' => $email])->row_array();
				if(!empty($result) && strtotime($result['account_expiration_date']) < time()){
					
					$this->Signin_model->remove_unverified_user($result);
					 return false;
				}
			}
            return true;
        }
        else
        {
            return false;
        }
    }

}

?>