<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
	
	public function index()
	{		
		$this->load->library('Options');
		$this->load->helper('url');

		if ( ! empty($_POST) ) 
		{
			die('hoooy');
			// $this->options->save_settings();

			// redirect('settings');
		}
		
		$this->load->view("settings/index");
	}
	
}