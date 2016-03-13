<?php

	function singleProcess_appendReadyFCFSTest(){
		$row = new Row();
		$CPUSchedulingMethod = FCFS;
		$process = new Process(1, array(new Task("use", 0, 2)));
		$row->appendReady($process, $CPUSchedulingMethod);
		$readyArray = $row->getReadyArray();
		$expression = $readyArray[0] == $process;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function appendReadyFCFSTest(){
		$row = new Row();
		$CPUSchedulingMethod = FCFS;
		$process1 = new Process(1, array(new Task("use", 0, 2)));
		$row->appendReady($process1, $CPUSchedulingMethod);
		$process2 = new Process(2, array(new Task("use", 0, 3)));
		$row->appendReady($process2, $CPUSchedulingMethod);
		$readyArray = $row->getReadyArray();
		$expression = $readyArray[0] == $process2;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function singleProcess_takeNextFromReadyTest(){
		$row = new Row();
		$CPUSchedulingMethod = FCFS;
		$process1 = new Process(1, array(new Task("use", 0, 2)));
		$row->appendReady($process1, $CPUSchedulingMethod);
		$ready = $row->takeNextFromReady();
		$expression = $ready == $process1;
		checkAndDisplay(__FUNCTION__, $expression);		
	}
	
	function setGetProcessorTest(){
		$row = new Row();
		$process = new Process(1, array(new Task("use", 0, 3)));
		$row->setProcessor($process);
		$processor = $row->getProcessor($process);
		$expression = $processor == $process;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function setGetWait1Test(){
		$row = new Row();
		$process1 = new Process(1, array(new Task("write", 1, 2)));
		$process2 = new Process(2, array(new Task("write", 1, 3)));
		$row->appendWait(1, $process1);
		$row->appendWait(1, $process2);
		$waitArray = $row->getWaitArray(1);
		$expression = $waitArray[0] == $process2;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function singleProcess_setGetWait1Test(){
		$row = new Row();
		$process = new Process(1, array(new Task("read", 1, 2)));
		$row->appendWait(1, $process);
		$waitArray = $row->getWaitArray(1);
		$expression = $waitArray[0] == $process;
		checkAndDisplay(__FUNCTION__, $expression);		
	}
	
	function takeNextFromWait1Test(){
		$row = new Row();
		$process1 = new Process(1, array(new Task("read", 1, 4)));
		$process2 = new Process(2, array(new Task("write", 1, 2)));
		$row->appendWait(1, $process1);
		$row->appendWait(1, $process2);
		$wait = $row->takeNextFromWait(1);
		$expression = $wait == $process1;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function singleProcess_takeNextFromWait1Test(){
		$row = new Row();
		$process = new Process(1, array(new Task("read", 1, 4)));
		$row->appendWait(1, $process);
		$wait = $row->takeNextFromWait(1);
		$expression = $wait == $process;
		checkAndDisplay(__FUNCTION__, $expression);		
	}
	
	function setGetWait2Test(){
		$row = new Row();
		$process1 = new Process(1, array(new Task("read", 2, 2)));
		$process2 = new Process(2, array(new Task("write", 2, 3)));
		$row->appendWait(2, $process1);
		$row->appendWait(2, $process2);
		$waitArray = $row->getWaitArray(2);
		$expression = $waitArray[0] == $process2;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function singleProcess_setGetWait2Test(){
		$row = new Row();
		$process = new Process(1, array(new Task("read", 2, 2)));
		$row->appendWait(2, $process);
		$waitArray = $row->getWaitArray(2);
		$expression = $waitArray[0] == $process;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function takeNextFromWait2Test(){
		$row = new Row();
		$process1 = new Process(1, array(new Task("read", 2, 4)));
		$process2 = new Process(2, array(new Task("write", 2, 2)));
		$row->appendWait(2, $process1);
		$row->appendWait(2, $process2);
		$wait = $row->takeNextFromWait(2);
		$expression = $wait == $process1;
		checkAndDisplay(__FUNCTION__, $expression);		
	}
	
	function singleProcess_takeNextFromWait2Test(){
		$row = new Row();
		$process = new Process(1, array(new Task("read", 2, 4)));
		$row->appendWait(2, $process);
		$wait = $row->takeNextFromWait(2);
		$expression = $wait == $process;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function setGetRead1Test(){
		$row = new Row();
		$process1 = new Process(1, array(new Task("read", 1, 2)));
		$process2 = new Process(2, array(new Task("read", 1, 3)));
		$row->appendRead(1, $process1);
		$row->appendRead(1, $process2);
		$processArray = array($process2, $process1);
		$read = $row->getReadArray(1);
		$expression = $read == $processArray;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function singleProcess_setGetRead1Test(){
		$row = new Row();
		$process = new Process(1, array(new Task("read", 1, 2)));
		$row->appendRead(1, $process);
		$processArray = array($process);
		$read = $row->getReadArray(1);
		$expression = $read == $processArray;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function setGetRead2Test(){
		$row = new Row();
		$process1 = new Process(1, array(new Task("read", 2, 2)));
		$process2 = new Process(2, array(new Task("read", 2, 3)));
		$row->appendRead(2, $process1);
		$row->appendRead(2, $process2);
		$processArray = array($process2, $process1);
		$read = $row->getReadArray(2);
		$expression = $read == $processArray;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function singleProcess_setGetRead2Test(){
		$row = new Row();
		$process = new Process(1, array(new Task("read", 2, 2)));
		$row->appendRead(2, $process);
		$processArray = array($process);
		$read = $row->getReadArray(2);
		$expression = $read == $processArray;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function setGetWrite1Test(){
		$row = new Row();
		$process = new Process(1, array(new Task("write", 1, 2)));
		$row->setWrite(1, $process);
		$write = $row->getWrite(1);
		$expression = $write == $process;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function setGetWrite2Test(){
		$row = new Row();
		$process = new Process(1, array(new Task("write", 1, 2)));
		$row->setWrite(2, $process);
		$write = $row->getWrite(2);
		$expression = $write == $process;
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function dropFromProcessorTest(){
		$row = new Row();
		$process = new Process(1, array(new Task("use", 0, 3)));
		$row->setProcessor($process);
		$row->dropFromProcessor();
		$expression = $row->getProcessor() == NULL;
		checkAndDisplay(__FUNCTION__, $expression);		
	}
	
	function takeNextFromReadyTest(){
		$row = new Row();
		$CPUSchedulingMethod = FCFS;
		$process1 = new Process(1, array(new Task("use", 0, 2)));
		$process2 = new Process(2, array(new Task("use", 0, 3)));
		$row->appendReady($process1, $CPUSchedulingMethod);
		$row->appendReady($process2, $CPUSchedulingMethod);
		$takenFirst = $row->takeNextFromReady();
		$readyArrayCorrectAfterFirstTaken = $row->getReadyArray() == array($process2);
		$takenSecond = $row->takeNextFromReady();
		$readyArrayAfterSecondTaken = $row->getReadyArray();
		$readyArrayCorrectAfterSecondTaken = empty($readyArrayAfterSecondTaken);
		$takenFirstProcessNumberIs1 = $takenFirst->getProcessNumber() == 1;
		$takenSecondProcessNumberIs2 = $takenSecond->getProcessNumber() == 2;
		$expression = $takenFirstProcessNumberIs1 && 
					  $takenSecondProcessNumberIs2 && 
					  $readyArrayCorrectAfterFirstTaken && 
					  $readyArrayCorrectAfterSecondTaken;
		checkAndDisplay(__FUNCTION__, $expression);	
	}
	
	function dropNextFromRead1Test(){
		$row = new Row();
		$process1 = new Process(1, array(new Task("read", 1, 3)));
		$row->appendRead(1, $process1);
		$read1ArrayCorrectAfterAppend = $row->getReadArray(1) == array($process1);
		$row->dropFromRead(1, $process1);
		$read1ArrayAfterDrop = $row->getReadArray(1);
		$read1ArrayCorrectAfterDrop = empty($read1ArrayAfterDrop);
		checkAndDisplay(__FUNCTION__, $read1ArrayCorrectAfterAppend && $read1ArrayCorrectAfterDrop);
	}
	
	function dropNextFromRead2Test(){
		$row = new Row();
		$process1 = new Process(1, array(new Task("read", 1, 3)));
		$row->appendRead(2, $process1);
		$read2ArrayCorrectAfterAppend = $row->getReadArray(2) == array($process1);
		$row->dropFromRead(2, $process1);
		$read2ArrayAfterDrop = $row->getReadArray(2);
		$read2ArrayCorrectAfterDrop = empty($read2ArrayAfterDrop);
		checkAndDisplay(__FUNCTION__, $read2ArrayCorrectAfterAppend && $read2ArrayCorrectAfterDrop);
	}
	
	function dropFromWrite1Test(){
		$row = new Row();
		$process1 = new Process(1, array(new Task("use", 0, 3)));
		$row->setWrite(1, $process1);
		$write1CorrectAfterAppend = $row->getWrite(1) == $process1;
		$row->dropFromWrite(1);
		$write1CorrectAfterDrop = $row->getWrite(1) == NULL;
		checkAndDisplay(__FUNCTION__, $write1CorrectAfterAppend && $write1CorrectAfterDrop);
	}
	
	function dropFromWrite2Test(){
		$row = new Row();
		$process1 = new Process(1, array(new Task("use", 0, 3)));
		$row->setWrite(2, $process1);
		$write2CorrectAfterAppend = $row->getWrite(2) == $process1;
		$row->dropFromWrite(2);
		$write2CorrectAfterDrop = $row->getWrite(2) == NULL;
		checkAndDisplay(__FUNCTION__, $write2CorrectAfterAppend && $write2CorrectAfterDrop);
	}
	
	function empty_getProcessStringFromProcessArrayTest(){
		$row = new Row();
		$processor = $row->getProcessor();
		$processArray = array($processor);
		$expression = $row->getProcessStringFromProcessArray($processArray) == "";
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function singleProcess_getProcessStringFromProcessArrayTest(){
		$row = new Row();
		$process = new Process(1, array(new Task("use", 0, 2)));
		$row->setProcessor($process);
		$processor = $row->getProcessor();
		$processArray = array($processor);
		$expression = $row->getProcessStringFromProcessArray($processArray) == "1";
		checkAndDisplay(__FUNCTION__, $expression);
	}
	
	function getProcessStringFromProcessArrayTest(){
		$row = new Row();
		$process1 = new Process(1, array(new Task("read", 1, 2)));
		$process2 = new Process(2, array(new Task("read", 1, 3)));
		$process3 = new Process(3, array(new Task("read", 1, 1)));
		$row->appendRead(1, $process1);
		$row->appendRead(1, $process2);
		$row->appendRead(1, $process3);
		$readArray = $row->getReadArray(1);
		$expression = $row->getProcessStringFromProcessArray($readArray) == "321";
		checkAndDisplay(__FUNCTION__, $expression);	
	}
	
	
	appendReadyFCFSTest();
	singleProcess_appendReadyFCFSTest();
	singleProcess_TakeNextFromReadyTest();
	
	setGetProcessorTest();
	
	setGetWait1Test();
	singleProcess_setGetWait1Test();
	singleProcess_takeNextFromWait1Test();
	
	setGetWait2Test();
	singleProcess_setGetWait2Test();
	singleProcess_takeNextFromWait2Test();
	
	setGetRead1Test();
	singleProcess_setGetRead1Test();
	
	setGetRead2Test();
	singleProcess_setGetRead2Test();
	
	setGetWrite1Test();
	
	setGetWrite2Test();
	
	dropFromProcessorTest();
	takeNextFromReadyTest();
	takeNextFromWait1Test();
	takeNextFromWait2Test();
	dropNextFromRead1Test();
	dropNextFromRead2Test();
	dropFromWrite1Test();
	dropFromWrite2Test();
	
	empty_getProcessStringFromProcessArrayTest();
	singleProcess_getProcessStringFromProcessArrayTest();
	getProcessStringFromProcessArrayTest();
	
?>