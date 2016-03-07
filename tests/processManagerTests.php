<?php	
	function distributeAllRunningDifferentSlotsTest(){
		$name = __FUNCTION__;
		$CPUSchedulingMethod = FCFS;
		$processManager = new ProcessManager($CPUSchedulingMethod);
		$process1 = new Process(1, array(new Task("write", 1, 2)));
		$process2 = new Process(2, array(new Task("read", 2, 2)));
		$process3 = new Process(3, array(new Task("use", 0, 2)));
		$row = new Row();
		$processArray = array($process1, $process2, $process3);
		$processManager->distribute($processArray, $row);
		$firstIsInWrite1 = $row->getWrite(1)->getCurrentTask()->getActivity() == "write" &&
							$row->getWrite(1)->getCurrentTask()->getResourceNumber() == 1;
		$read2 = $row->getReadArray(2);
		$secondIsInRead2 = $read2[0]->getCurrentTask()->getActivity() == "read" &&
							$read2[0]->getCurrentTask()->getResourceNumber() == 2;
		$thirdIsInProcessor = $row->getProcessor()->getCurrentTask()->getActivity() == "use" &&
								$row->getProcessor()->getCurrentTask()->getResourceNumber() == 0;
		checkAndDisplay($name, $firstIsInWrite1 && $secondIsInRead2 && $thirdIsInProcessor);
	}
	
	function distribute1Of3NotRunningDifferentSlotsTest(){
		$name = __FUNCTION__;
		$CPUSchedulingMethod = FCFS;
		$processManager = new ProcessManager($CPUSchedulingMethod);
		$process1 = new Process(1, array(new Task("write", 1, 2)));
		$process2 = new Process(2, array(new Task("read", 2, 2)));
		$process2->stopRunning();
		$process3 = new Process(3, array(new Task("use", 0, 2)));
		$row = new Row();
		$processArray = array($process1, $process2, $process3);
		$processManager->distribute($processArray, $row);
		$firstIsInWrite1 = $row->getWrite(1)->getCurrentTask()->getActivity() == "write" &&
							$row->getWrite(1)->getCurrentTask()->getResourceNumber() == 1;
		$secondIsNull = $row->getReadArray(2) == NULL;
		$thirdIsInProcessor = $row->getProcessor()->getCurrentTask()->getActivity() == "use" &&
								$row->getProcessor()->getCurrentTask()->getResourceNumber() == 0;
		checkAndDisplay($name, $firstIsInWrite1 && $secondIsNull && $thirdIsInProcessor);		
	}
	
	function distributeWrite1WhenWrite1OccupiedTest(){
		$name = __FUNCTION__;
		$row = new Row();
		$CPUSchedulingMethod = FCFS;
		$processManager = new ProcessManager($CPUSchedulingMethod);
		$process1 = new Process(1, array(new Task("write", 1, 2)));
		$process2 = new Process(2, array(new Task("write", 1, 2)));
		$processArray = array($process1, $process2);
		$processManager->distribute($processArray, $row);
		$expected = $row->getWaitArray(1) == array($process2) && $row->getWrite(1) == $process1;
		checkAndDisplay($name, $expected);		
	}
	
	function distributeWrite1WhenRead1OccupiedTest(){
		$name = __FUNCTION__;
		$row = new Row();
		$CPUSchedulingMethod = FCFS;
		$processManager = new ProcessManager($CPUSchedulingMethod);
		$process1 = new Process(1, array(new Task("read", 1, 2)));
		$process2 = new Process(2, array(new Task("write", 1, 2)));
		$processArray = array($process1, $process2);
		$processManager->distribute($processArray, $row);
		$expected = $row->getWaitArray(1) == array($process2) && $row->getReadArray(1) == array($process1);
		checkAndDisplay($name, $expected);		
	}
	
	function distributeWrite1Test(){
		$name = __FUNCTION__;
		$row = new Row();
		$CPUSchedulingMethod = FCFS;
		$processManager = new ProcessManager($CPUSchedulingMethod);
		$process1 = new Process(1, array(new Task("write", 1, 2)));
		$writeArray = array($process1);
		$processManager->distribute($writeArray, $row);
		$expected = $row->getWrite(1) == $process1;
		checkAndDisplay($name, $expected);		
	}
	
	function distributeRead1WhenWrite1OccupiedTest(){
		$name = __FUNCTION__;
		$row = new Row();
		$CPUSchedulingMethod = FCFS;
		$processManager = new ProcessManager($CPUSchedulingMethod);
		$process1 = new Process(1, array(new Task("write", 1, 2)));
		$process2 = new Process(2, array(new Task("read", 1, 2)));
		$readArray = array($process1, $process2);
		$processManager->distribute($readArray, $row);
		$expected = $row->getWaitArray(1) == array($process2) && $row->getWrite(1) == $process1;
		checkAndDisplay($name, $expected);		
	}
	
	function distributeRead1WhenOneInRead1Test(){
		$name = __FUNCTION__;
		$row = new Row();
		$CPUSchedulingMethod = FCFS;
		$processManager = new ProcessManager($CPUSchedulingMethod);
		$process1 = new Process(1, array(new Task("read", 1, 2)));
		$process2 = new Process(2, array(new Task("read", 1, 2)));
		$readArray = array($process1, $process2);
		$processManager->distribute($readArray, $row);
		$expected = $row->getReadArray(1) == array($process2, $process1);
		checkAndDisplay($name, $expected);		
	}
	
	function distributeRead1Test(){
		$name = __FUNCTION__;
		$row = new Row();
		$CPUSchedulingMethod = FCFS;
		$processManager = new ProcessManager($CPUSchedulingMethod);
		$process1 = new Process(1, array(new Task("read", 1, 2)));
		$readArray = array($process1);
		$processManager->distribute($readArray, $row);
		$expected = $row->getReadArray(1) == $readArray;
		checkAndDisplay($name, $expected);
	}
	
	function distributeReadyAndWaitTest(){
		$name = __FUNCTION__;
		$row = new Row();
		$CPUSchedulingMethod = FCFS;
		$processManager = new ProcessManager($CPUSchedulingMethod);
		$process1 = new Process(1, array(new Task("read", 1, 2)));
		$process2 = new Process(2, array(new Task("write", 2, 2)));
		$process3 = new Process(3, array(new Task("use", 0, 2)));
		$row->appendReady($process3, $CPUSchedulingMethod);
		$row->appendWait(1, $process1);
		$row->appendWait(2, $process2);
		$processManager->distributeReadyAndWait($row, $CPUSchedulingMethod);
		$expected = $row->getReadArray(1) == array($process1) &&
					$row->getWrite(2) == $process2 &&
					$row->getProcessor() == $process3;
		checkAndDisplay($name, $expected);
	}
	
	function distributeReadyAndWaitTestWhenOccupied(){}
	
	function drop2Of3ProcessesTest(){
		$name = __FUNCTION__;
		$CPUSchedulingMethod = FCFS;
		$processManager = new ProcessManager($CPUSchedulingMethod);
		$row = new Row();
		$process1 = new Process(1, array(new Task("use", 0, 3)));
		$process2 = new Process(2, array(new Task("read", 1, 2)));
		$process3 = new Process(3, array(new Task("write", 2, 4)));
		$processArray = array($process1, $process2, $process3);
		$processManager->distribute($processArray, $row);
		$process1->getCurrentTask()->setTimeElapsed(3);
		$process3->getCurrentTask()->setTimeElapsed(4);
		$processManager->dropEndedProcesses($row);
		$processorIsEmpty = $row->getProcessorString() == "";
		$read1 = $row->getReadArray(1);
		$read1IsStillRunning = $read1[0]->getCurrentTask()->getActivity() == "read";
		$write2IsEmpty = $row->getWriteString(2) == "";
		checkAndDisplay($name, $processorIsEmpty && $read1IsStillRunning && $write2IsEmpty);
	}
	
	distributeAllRunningDifferentSlotsTest();
	distribute1Of3NotRunningDifferentSlotsTest();
	distributeRead1Test();
	distributeRead1WhenOneInRead1Test();
	distributeRead1WhenWrite1OccupiedTest();
	distributeWrite1Test();
	distributeWrite1WhenRead1OccupiedTest();
	distributeWrite1WhenWrite1OccupiedTest();
	distributeReadyAndWaitTest();
	drop2Of3ProcessesTest();
?>