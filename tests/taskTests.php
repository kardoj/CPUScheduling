<?php
	function timeIncrementTest(){
		$name = __FUNCTION__;
		$task = new Task("use", 0, 3);
		$task->incrementTimeElapsed();
		$timeElapsedIsOne = $task->getTimeElapsed() == 1;
		checkAndDisplay($name, $timeElapsedIsOne);
	}
	
	function tryToIncrementTimeWhenOverTest(){
		$name = __FUNCTION__;
		$task = new Task("use", 0, 0);
		$task->incrementTimeElapsed();
		$timeNotIncremented = $task->getTimeElapsed() == 0;
		checkAndDisplay($name, $timeNotIncremented);
	}
	
	function getResourceNumberTest(){
		$name = __FUNCTION__;
		$task = new Task("read", 1, 3);
		$resourceNumberIsOne = $task->getResourceNumber() == 1;
		checkAndDisplay($name, $resourceNumberIsOne);
	}
	
	function getActivityTest(){
		$name = __FUNCTION__;
		$task = new Task("write", 2, 3);
		$activityIsWrite = $task->getActivity() == "write";
		checkAndDisplay($name, $activityIsWrite);
	}
	
	timeIncrementTest();
	tryToIncrementTimeWhenOverTest();
	getResourceNumberTest();
	getActivityTest();
?>