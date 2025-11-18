<?php

namespace App\Controllers;

class Home extends BaseController
{	
	public function __construct()
    {
        $this->is_logged = session()->is_logged ? true : false;
    }
	public function index()
	{
		return ($this->is_logged)? view('user/dashboard') : view('login');
	}
}
