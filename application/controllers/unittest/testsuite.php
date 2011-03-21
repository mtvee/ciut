<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class TestSuite extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->library('ciut');
		// can also set the test path in $config['unit_test_path'] = '';
		//$this->ciut->set_test_path( APPPATH . '/tests' );
		$this->ciut->run();
		
		$this->load->view('unittest/header');
		$this->load->view('unittest/results', array( 'results' => $this->ciut ));
		$this->load->view('unittest/footer');
	}
		
}