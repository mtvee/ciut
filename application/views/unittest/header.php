<?php if(!is_cli()): ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Unit test results</title>
	
	<!-- style -->
	<style type="text/css">
	* { font-family: Arial, sans-serif; font-size: 9pt }
	h1 { font-size: 12pt }
	.uttable { border-collapse: collapse; font-size: 8pt; font-family: Arial, Helvetica, sans-serif; width: 90%;}
	.uttable caption { color: #fff; background: #333; text-align: left;  padding: 2px;}
	.uttable th { color: #fff; background: #666; }
	.uttable tr { border-bottom: 1px solid #999; }
	.utpass { color: #333; background: #9f9; }
	.utfail { color: #333; background: #f99; }
	.odd { background-color: #dddddd;}
	.center { text-align: center;}
	</style>	
	
	<!-- meta -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="generator" content="" />
	<meta name="description" content="" />	
</head>

<body>

<h1>Unit Tests</h1>
<h4><?= date( "D, M j Y G:i:s T" )?></h4>

<?php else: ?>
Unit Tests: <?= date( "D, M j Y G:i:s T" )?>

=========================================

<?php endif; ?>
