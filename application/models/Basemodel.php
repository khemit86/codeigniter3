<?php

if ( ! defined ('BASEPATH'))
{
    exit ('No direct script access allowed');
}

class Basemodel extends CI_Model
{
	public function __construct ()
    {
       return parent::__construct ();
    }
    public function insert ($table, $fileds)
    {
        return $fileds;
    }
}
