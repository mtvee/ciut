<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class UtTest extends UnitTest
{	
	function __construct()
	{
		parent::__construct();
	}
	
	function setUp()
	{
		
	}
	
	function tearDown()
	{
		
	}
	
	function testNothing()
	{
		$this->assertTrue( 1 == 2, "1 == 2" );
		$this->assertTrue( 1 == 1, "1 == 1" );
	}

	function testSomething()
	{
		$this->assertTrue( 1 == 1, "1 == 1" );
		$this->assertFalse( 1 == 2, "1 == 2" );
	}
}