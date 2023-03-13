<?php

class BaseController extends CI_Controller{
    function index()
    {
        $this->load->view('login');
    }
    
    function edit($id = '')
    {
        $this->load->view('user');
    }
}