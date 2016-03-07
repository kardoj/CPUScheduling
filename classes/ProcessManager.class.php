<?php
class ProcessManager{
	
	private $CPUSchedulingMethod;
	
	public function ProcessManager($CPUSchedulingMethod){
		$this->CPUSchedulingMethod = $CPUSchedulingMethod;
	}
	
	public function distribute($processArray, $row){
		foreach($processArray as $process){
			$isDistributed = $this->isDistributed($process, $row);
			if($process->isRunning() && !$isDistributed){
				$resourceNumber = $process->getCurrentTask()->getResourceNumber();
				switch($resourceNumber){
					case 0:
						$this->setProcessor($process, $row);
						break;
					case 1:
						$this->setByResource($resourceNumber, $process, $row);
						break;
					case 2:
						$this->setByResource($resourceNumber, $process, $row);
						break;
				}
			}
		}
	}
	
	public function isDistributed($process, $row){
		$distributedProcesses = array();
		$ready = $row->getReadyArray();
		$processor = array($row->getProcessor());
		$wait1 = $row->getWaitArray(1);
		$read1 = $row->getReadArray(1);
		$write1 = array($row->getWrite(1));
		$wait2 = $row->getWaitArray(2);
		$read2 = $row->getReadArray(2);
		$write2 = array($row->getWrite(2));
		$distributedProcesses = array_merge($ready, $processor, $wait1, $wait2, $read1, $read2, $write1, $write2);
		foreach($distributedProcesses as $distributedProcess){
			if($distributedProcess == $process){
				return true;
			}
		}
		return false;
	}
	
	public function distributeReadyAndWait($row, $CPUSchedulingMethod){
		// if there is something in ready and processor is empty, move it to processor
		if($row->getReadyString() != "" && $row->getProcessorString() == ""){
			$row->setProcessor($row->takeNextFromReady());
		}
		
		if($row->getWaitString(1) != ""){
			$process = $row->getNextFromWait(1);
			$activityIsRead = $process->getCurrentTask()->getActivity() == "read";
			$activityIsWrite = $process->getCurrentTask()->getActivity() == "write";
			$resourceNumberIs1 = $process->getCurrentTask()->getResourceNumber() == 1;
			$write1IsEmpty = $row->getWrite(1) == NULL;
			$read1IsEmpty = $row->getReadArray(1) == array();
			
			if($activityIsRead && $resourceNumberIs1 && $write1IsEmpty){
				$row->appendRead(1, $row->takeNextFromWait(1));
			} else if($activityIsWrite && $resourceNumberIs1 && $write1IsEmpty && $read1IsEmpty){
				$row->setWrite(1, $row->takeNextFromWait(1));
			}
		}
		
		if($row->getWaitString(2) != ""){
			$process = $row->getNextFromWait(2);
			$activityIsRead = $process->getCurrentTask()->getActivity() == "read";
			$activityIsWrite = $process->getCurrentTask()->getActivity() == "write";
			$resourceNumberIs2 = $process->getCurrentTask()->getResourceNumber() == 2;
			$write2IsEmpty = $row->getWrite(2) == NULL;
			$read2IsEmpty = $row->getReadArray(2) == array();
				
			if($activityIsRead && $resourceNumberIs2 && $write2IsEmpty){
				$row->appendRead(2, $row->takeNextFromWait(2));
			} else if($activityIsWrite && $resourceNumberIs2 && $write2IsEmpty && $read2IsEmpty){
				$row->setWrite(2, $row->takeNextFromWait(2));
			}
		}		
	}
	
	public function incrementTaskTimeElapsed($row){
		$arrayOfProcesses = $this->getArrayOfProcessesInPR1R2W1W2($row);
		foreach($arrayOfProcesses as $one){
			if($one != NULL){
				$one->getCurrentTask()->incrementTimeElapsed();
			}
		}
	}
	
	private function getArrayOfProcessesInPR1R2W1W2($row){
		$processor = $row->getProcessor();
		$write1 = $row->getWrite(1);
		$write2 = $row->getWrite(2);
		$singleVariableFields = array($processor, $write1, $write2);
		
		$read1Array = $row->getReadArray(1);
		$read2Array = $row->getReadArray(2);	
		
		return  array_merge(
					$singleVariableFields, 
					$read1Array, 
					$read2Array
				);
	}
	
	public function dropEndedProcesses($row){
		/*
		$arrayOfProcesses = $this->getArrayOfProcessesInPR1R2W1W2($row);
		foreach($arrayOfProcesses as $process){
			$task = $process->getCurrentTask();
			if($task->getTimeElapsed() == $task->getDuration()){
				$row->dropProcessNumber($process->getProcessNumber());
			}
		}
		*/
		if($row->getProcessor() != NULL){
			$task = $row->getProcessor()->getCurrentTask();
			if($task->getTimeElapsed() == $task->getDuration()){
				$row->dropFromProcessor();
			}			
		}
		foreach($row->getReadArray(1) as $one){
			$task = $one->getCurrentTask();
			if($task->getTimeElapsed() == $task->getDuration()){
				$row->dropNextFromRead(1);
			}
		}
		foreach($row->getReadArray(2) as $one){
			$task = $one->getCurrentTask();
			if($task->getTimeElapsed() == $task->getDuration()){
				$row->dropNextFromRead(2);
			}
		}
		if($row->getWrite(1) != NULL){			
			$task = $row->getWrite(1)->getCurrentTask();
			if($task->getTimeElapsed() == $task->getDuration()){
				$row->dropFromWrite(1);
			}
		}
		if($row->getWrite(2) != NULL){			
			$task = $row->getWrite(2)->getCurrentTask();
			if($task->getTimeElapsed() == $task->getDuration()){
				$row->dropFromWrite(2);
			}
		}
	}
	
	public function setNextTasks($processArray){
		foreach($processArray as $process){
			$task = $process->getCurrentTask();
			if($task->getTimeElapsed() == $task->getDuration()){
				$process->setNextTask();
			}
		}		
	}
	
	public function fromProcessorToReady($row){
		if($row->getProcessorString() != ""){
			$row->appendReady($row->takeFromProcessor());
		}
	}
	
	private function setProcessor($process, $row){
		if($row->getProcessorString() == ""){
			$row->setProcessor($process);
		} else {
			$row->appendReady($process, $this->CPUSchedulingMethod);
		}
	}
	
	private function setByResource($resourceNumber, $process, $row){
		$task = $process->getCurrentTask();
		$activity = $task->getActivity();
		if($activity == "read"){
			if($row->getWriteString($resourceNumber) == ""){
				$row->appendRead($resourceNumber, $process);
			} else {
				$row->appendWait($resourceNumber, $process);
			}
		} else if($activity == "write" && $row->getWriteString($resourceNumber) == ""){
			if($row->getReadString($resourceNumber) == ""){
				$row->setWrite($resourceNumber, $process);
			} else {
				$row->appendWait($resourceNumber, $process);
			}
		} else if($activity == "write" && $row->getWriteString($resourceNumber) != ""){
			$row->appendWait($resourceNumber, $process);
		} 
	}
}
?>