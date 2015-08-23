<?php

class Destinations extends CI_Controller{

function index(){
	
	$this->load->helper('url');
	
	$data['	title']='';
	
	$this->load->view('templates/header',$data);
	$this->load->view('templates/footer',$data);

	}

}
?>