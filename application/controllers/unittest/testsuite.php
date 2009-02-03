<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once( APPPATH . '/libraries/unittest.php');

class TestSuite extends Controller
{
	function __construct()
	{
		parent::__construct();
		$this->auth->restrict_role('admin');
	}

	function index()
	{
		$ut = new UnitTestSuite( APPPATH . '/tests' );
		$ut->run();
		$this->load->view('unittest/header');
		$this->load->view('unittest/results', array('results' => $ut));
		$this->load->view('unittest/footer');
	}
		
}