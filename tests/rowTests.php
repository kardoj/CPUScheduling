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
		$row->appendReady(new Process(1, array(new Task("use", 0, 2))), $CPUSchedulingMethod);
		$row->appendReady(new Process(2, array(new Task("use", 0, 3))), $CPUSchedulingMethod);
		$first = $row->takeNextFromReady();
		$readyString = $row->getReadyString();
		$second = $row->takeNextFromReady();
		$readyString2 = $row->getReadyString();
		checkAndDisplay(__FUNCTION__, $first->getProcessNumber() == 1 && 
								$second->getProcessNumber() == 2 && 
								$readyString == "2" &&
								$readyString2 == ""
		);	
	}
	
	function dropNextFromRead1Test(){
		$row = new Row();
		$row->appendRead(1, new Process(1, array(new Task("use", 0, 3))));
		$read1String1 = $row->getReadString(1);
		$row->dropNextFromRead(1);
		$read1String2 = $row->getReadString(1);
		checkAndDisplay(__FUNCTION__, $read1String1 == "1" && $read1String2 == "");
	}
	
	function dropNextFromRead2Test(){
		$row = new Row();
		$row->appendRead(2, new Process(1, array(new Task("use", 0, 3))));
		$read1String1 = $row->getReadString(2);
		$row->dropNextFromRead(2);
		$read1String2 = $row->getReadString(2);
		checkAndDisplay(__FUNCTION__, $read1String1 == "1" && $read1String2 == "");
	}
	
	function dropFromWrite1Test(){
		$row = new Row();
		$row->setWrite(1, new Process(1, array(new Task("use", 0, 3))));
		$writeString1 = $row->getWriteString(1);
		$row->dropFromWrite(1);
		$writeString2 = $row->getWriteString(1);
		checkAndDisplay(__FUNCTION__, $writeString1 == "1" && $writeString2 == "");
	}
	
	function dropFromWrite2Test(){
		$row = new Row();
		$row->setWrite(2, new Process(1, array(new Task("use", 0, 3))));
		$writeString1 = $row->getWriteString(2);
		$row->dropFromWrite(2);
		$writeString2 = $row->getWriteString(2);
		checkAndDisplay(__FUNCTION__, $writeString1 == "1" && $writeString2 == "");
	}
	
	function rowProcessorPointerTest(){
		$row = new Row();
		$processorPointer = $row->getProcessor();
		$processorString = $processorPointer;
		$row->setProcessor(new Process(1, array(new Task("use", 0, 3))));
		$processorString2 = $processorPointer;
		checkAndDisplay(__FUNCTION__, $processorString == $processorString2);
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
	rowProcessorPointerTest();
	
	empty_getProcessStringFromProcessArrayTest();
	singleProcess_getProcessStringFromProcessArrayTest();
	getProcessStringFromProcessArrayTest();
	
?>