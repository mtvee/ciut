<?php
/** 
 * A simple unit tester and suite for CodeIgniter.
 *
 * There are two classes in this file, 'Ciut' which you can load from
 * your controller, and 'UnitTest' which you can extend your test from.
 *
 * <code>
 * $this->load->library('ciut');
 * if( $this->ciut->run()) {
 *   $this->load->view('results', array('results' => $this->ciut ));
 * } else {
 *   echo "Error running tests.";   
 * } 
 * </code> 
 *
 * @category       UnitTesting
 * @package        Talon
 * @author         J. Knight <emptyvee at gmail dot com>
 * @license        GPLv3
 * @copyright      Copyright (c) 2006-2011, J. Knight
 */


/** **********************************************************************
 * Base class for a unit test
 *
 * Tests should extend this class. The file the test resides in should
 * be called [SomeName]Test.php and the class called SomeNameTest.
 *
 * Individual test methods inside the class should begin with the word "test"
 * e.g. <code>function testMyFunkyThang()</code>
 *
 * Always use the "assert..." methods to pass or fail tests. A failed assert
 * will stop further running of that test method but any other methods will
 * still be run and the setUp and tearDown methods will always run.
 *
 * Access to the CodeIgniter core is already setup via $this->CI, so you
 * can load stuff through that in your constructor or test methods.
 * e.g. 
 * <code>
 *	$this->CI->load->model('ticket/Ticket_model', '', true);
 *  $this->CI->Ticket_model->get_something_or_other();
 * </code>
 *
 */
class UnitTest
{
  public $assert_count = 0;
  
  public function __construct()
  {
      $this->CI =& get_instance();  
  }

  /**
   * Runs before any tests are run
   */
  function setUp()
  {
  }
  
  /**
   * Runs after all tests are run
   */
  function tearDown()
  {
  }
  
  /**
   * Assert that $val == TRUE
   *
   * @param $val the value to check
   * @param $mag the msg to report if assertion fails
   **/
  function assertTrue( $val, $msg = "assertTrue failed" )
  {
    if( !$val ) {
      throw new Exception( $msg );
    }    
    $this->assert_count++;
  }

  /**
   * Assert that $val != TRUE
   *
   * @param $val the value to check
   * @param $mag the msg to report if assertion fails
   **/
  function assertFalse( $val, $msg = "assertFalse failed" )
  {
    if( $val ) {
      throw new Exception( $msg );
    }    
    $this->assert_count++;
  }
  
  /**
   * Assert that $val == NULL
   *
   * @param $val the value to check
   * @param $mag the msg to report if assertion fails
   **/
  function assertNull( $val, $msg = "assertNull failed" )
  {
    if( $val != NULL ) {
      throw new Exception( $msg );
    }    
    $this->assert_count++;
  }

  /**
   * Assert that $val != NULL
   *
   * @param $val the value to check
   * @param $mag the msg to report if assertion fails
   **/
  function assertNotNull( $val, $msg = "assertNotNull failed" )
  {
    if( $val == NULL ) {
      throw new Exception( $msg );
    }    
    $this->assert_count++;
  }
  
  /**
   * Assert that $val1 == $val2
   *
   * @param val1 the first value
   * @param val2 the second value
   * @param msg the message to report when assertion fails
   */
  function assertEquals( $val1, $val2, $msg = NULL ) {
    if( $val1 != $val2 ) {
      if( $msg == NULL ) {
        $msg = "Expected: '$val1' == '$val2'";
      }
      throw new Exception( $msg );
    }
    $this->assert_count++;
  }

  /**
   * Assert that $val1 == $val2
   *
   * @param val1 the first value
   * @param val2 the second value
   * @param msg the message to report when assertion fails
   */
  function assertEqual( $val1, $val2, $msg = NULL )
  {
    $this->assertEquals( $val1, $val2, $msg );
  }

  /**
   * Assert that $val1 != $val2
   *
   * @param val1 the first value
   * @param val2 the second value
   * @param msg the message to report when assertion fails
   */
  function assertNotEquals( $val1, $val2, $msg = "assertNotEquals failed" ) {
    if( $val1 == $val2 ) {
      throw new Exception( $msg );
    }
    $this->assert_count++;
  }    

  /**
   * Assert that $val1 != $val2
   *
   * @param val1 the first value
   * @param val2 the second value
   * @param msg the message to report when assertion fails
   */
  function assertNotEqual( $val1, $val2, $msg = NULL )
  {
    $this->assertNotEquals( $val1, $val2, $msg );
  }

}

/** **********************************************************************
 * A class for unit testing CodeIgniter.
 *
 * This is the class that collects and runs all the tests.
 *
 * The default location for tests is 'APPPATH/tests' but you can put them 
 * wherever you like and set the path with 
 *   $config['unit_test_path'] = 'SOME_PATH';
 * or
 *   $this->cuit->set_test_path( 'SOME_PATH' );
 *
 */
class Ciut
{
	/**
	 * A string used to store the directory in which to look for tests.
	 */
	var $dir;
	// CI instance
	var $ci;

	// various statistics
	var $num_files = 0;
	var $num_tests = 0;
	var $num_passed = 0;
	var $num_failed = 0;

	/**
	 * An array used to store tests run.
	 */
	var $tests = null;

	/**
	 * Constructor.
	 */
	function __construct( )
	{
		if( function_exists('get_instance')) {
			// see if we have the path in the config
			$this->ci =& get_instance();
			$this->dir = $this->ci->config->item('unit_test_path');
			if( !$this->dir ) {
				// set a default
				$this->dir = APPPATH . '/tests';
			}
		}
	}

	/**
	 * Set the path for unit tests
	 */
	function set_test_path( $dir )
	{		
		$this->dir = $dir;	
	}
	
	/**
	 * Runs all tests and collects the results in $this->tests.
	 * 
	 * @return false on error or true if everything ran ok
	 */
	function run()
	{
		// reset
		$this->tests = null;
		$this->num_files = 0;
		$this->num_tests = 0;
		$this->num_passed = 0;
		$this->num_failed = 0;
	
		$testlist = $this->get_tests();
		if( $testlist === false ) {
			return false;
		}
			
		foreach( $testlist as $testfile ) {
			$res = $this->run_test( $testfile );
			$this->num_files++;
			foreach( $res as $key => $value ) {
				$this->tests[$key] = $value;
			}
		}
		return true;
	}
	
		
	/**
	 * Runs one unit test file.
	 *
	 * This function returns an array of test results from the tests in the
	 * named file. The array looks like:
	 * <pre>
	 * Array
	 * (
	 *     [TestName] => Array
	 *		(
	 *			[0] => Array
	 * 				(
	 *					["test"]   => "methodname"
	 *					["result"] => result (1 or 0)
	 *					["error"]  => "error message if any"
	 *				)
	 *			...
	 *		)
	 * }
	 * </pre>
	 *
	 * @param $testfile
	 *	A string containing the file path to the test file.
	 * @return
	 *	An array of test results.
	 */
	function run_test( $testfile )
	{
		$result = array();
	
		include_once( $testfile );
		$fname = basename( $testfile );
		// print "$fname\n";
		$class = substr( $fname, 0, strlen( $fname ) - 8 );
		$cname = $class . 'Test';
		//print $cname . "\n";
		if( class_exists( $cname )) {
			$test = new $cname();
			
			$result[$cname] = array();
			
			// run the setup method
			if( method_exists( $test, 'setUp' )) {
				$test->setup();
			}
			
			$m = get_class_methods( $test );
			foreach( $m as $mname ) {
				if( substr( $mname, 0, 4 ) == 'test' ) {
					$test->error = '';				
					// run the test
					$ret = TRUE;
					$msg = "";
					try {
					  $test->$mname();
					} catch( Exception $e ) {
					  $ret = FALSE;
					  $msg = $e->getMessage();
					}
					$this->num_tests++;
					if( $ret )
						$this->num_passed++;
					else
						$this->num_failed++;
						
					$result[$cname][] = array( "test" => $mname, 
											               "result" => $ret, 
											               "error" => $msg,
											               "asserts" => $test->assert_count 
											               );
				}
			}
			
			// run the tearDown method
			if( method_exists( $test, 'tearDown' )) {
				$test->tearDown();
			}
		}
		
		return $result;
	}
	
	/**
	 * Returns an array where each element contains the full path to
	 * any valid test file found.
	 *
	 * @return
	 *	An array with the paths to any valid tests found.
	 */
	function get_tests()
	{
	  
		if( !file_exists( $this->dir ))
			return false;
	
		if( !is_readable( $this->dir ))
			return false;
	
		$tests = array();
	
		$dh = opendir( $this->dir );
		while(($file = readdir($dh)) !== false ) {
			if( strlen( $file ) > 8 && substr( $file, -3 ) == 'php' && $file[0] != '.' ) {
				include_once( $this->dir . "/" . $file );
				$class = substr( $file, 0, strlen($file) - 8 );
				$cname = $class . 'Test';
				if( class_exists( $cname )) {
					$tests[] = "$this->dir/$file";
				}
			}
		}
		
		return $tests;
	}	
}

/*
 * Copyright (C) 2009-2011 J. Knight <emptyvee at gmail dot com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://www.gnu.org/licenses.
 */

?>