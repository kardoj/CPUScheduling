<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CPU Scheduling program tests</title>
  <meta name="description" content="CPU Scheduling program tests">
  <meta name="author" content="Kardo J�eleht">
  <meta name="year" content="2016">
  <style type="text/css">
	  html{
	  	font-family: monospace;
	  }
	  .passedTest{
	  	color: green;
	  }
	  .failedTest{
	  	color: red;
	  }
  </style>
</head>
<body>
	<?php
		function getHTMLClassStringByBoolean($string, $boolean){
			if($boolean){
				return "<span class='passedTest'>" . $string . "</span>";
			} else {
				return "<span class='failedTest'>" . $string . "</span>";
			}
		}
	
		function checkAndDisplay($name, $expression){
			$stringLength = 70;
			$resultString = "failed";
			if($expression){$resultString = "passed";}
			echo str_pad($name, $stringLength, ".", STR_PAD_RIGHT) . 
				 getHTMLClassStringByBoolean($resultString, $expression);
			echo "<br>";
		}
		
		require_once '../schedulingMethodDefinitions.php';
		require_once '../classes/Row.class.php';
		require_once '../classes/ProcessManager.class.php';
		require_once '../classes/Process.class.php';
		require_once '../classes/Task.class.php';
		require_once '../classes/Solution.class.php';
		
		echo "<strong>processTests.php</strong><br>";
		require_once 'processTests.php';
		echo "<br><strong>taskTests.php</strong><br>";
		require_once 'taskTests.php';	
		echo "<br><strong>processManagerTests.php</strong><br>";
		require_once 'processManagerTests.php';
		echo "<br><strong>rowTests.php</strong><br>";
		require_once 'rowTests.php';
	?>
</body>
</html>