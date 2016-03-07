<?php
	function startedWithZeroTasksTest(){
		$name = __FUNCTION__;
		$process = new Process(1, array());
		$processIsNotRunning = !$process->isRunning();
		checkAndDisplay($name, $processIsNotRunning);
	}
	
	function startedWithMoreThanZeroTasksTest(){
		$name = __FUNCTION__;
		$process = new Process(1, array(new Task("read", 2, 3)));
		$processIsRunning = $process->isRunning();
		checkAndDisplay($name, $processIsRunning);		
	}
	
	function setNextTaskTest(){
		$name = __FUNCTION__;
		$testTask = new Task("write", 1, 2);
		$process = new Process(1, array(new Task("read", 2, 3), $testTask));
		$process->setNextTask();
		$expression = $process->getCurrentTask() == $testTask;
		checkAndDisplay($name, $expression);
	}
	
	function getProcessNumberTest(){
		$name = __FUNCTION__;
		$process = new Process(1, array(new Task("read", 2, 3)));
		$processNumberIsOne = $process->getProcessNumber() == 1;
		checkAndDisplay($name, $processNumberIsOne);
	}
	
	function getCurrentTaskTest(){
		$name = __FUNCTION__;
		$testTask = new Task("read", 2, 3);
		$process = new Process(1, array($testTask));
		$expression = $process->getCurrentTask() == $testTask;
		checkAndDisplay($name, $expression);
	}
	
	function getTaskCountTest(){
		$name = __FUNCTION__;
		$process = new Process(1, array(new Task("read", 1, 2), new Task("use", 0, 3)));
		$expression = $process->getTaskCount() == 2;
		checkAndDisplay($name, $expression);
	}	
	
	startedWithZeroTasksTest();
	startedWithMoreThanZeroTasksTest();
	getProcessNumberTest();
	getCurrentTaskTest();
	setNextTaskTest();
	getTaskCountTest();
?>