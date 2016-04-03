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

	public function distributeToReadyAndWait($processArray, $row){
		foreach($processArray as $process){
			$isNotDistributed = !$this->isDistributed($process, $row);
			if($process->isRunning() && $isNotDistributed){
				$processForProcessor = $process->getCurrentTask()->getActivity() == "use";
				if($processForProcessor){
					$row->appendReady($process, $this->CPUSchedulingMethod);
				} else {
					$resourceNumber = $process->getCurrentTask()->getResourceNumber();
					$row->appendWait($resourceNumber, $process);
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

	public function distributeReadyAndWait($row){
		$this->setProcessorFromReady($row);
		$this->setFromWait($row, 1);
		$this->setFromWait($row, 2);
	}

	private function setFromWait($row, $resourceNumber){
		$waitArray = $row->getWaitArray($resourceNumber);
		$length = count($waitArray);
		for($i = $length-1; $i>=0; $i--){
			$process = $waitArray[$i];
			$activity = $process->getCurrentTask()->getActivity();
			if($activity == "read"){
				$this->setRead($row, $process, $resourceNumber);
			} else if($activity == "write"){
				$this->setWrite($row, $process, $resourceNumber);
			}
		}
	}

	private function setRead($row, $process, $resourceNumber){
		$resourceNumberIsCorrect = $process->getCurrentTask()->getResourceNumber() == $resourceNumber;
		$writeIsEmpty = $row->writeIsEmpty($resourceNumber);
		if($resourceNumberIsCorrect && $writeIsEmpty){
				$row->appendRead($resourceNumber, $row->takeNextFromWait($resourceNumber));
		}
	}

	private function setWrite($row, $process, $resourceNumber){
		$resourceNumberIsCorrect = $process->getCurrentTask()->getResourceNumber() == $resourceNumber;
		$writeIsEmpty = $row->writeIsEmpty($resourceNumber);
		$readIsEmpty = $row->readIsEmpty($resourceNumber);
		if($resourceNumberIsCorrect && $writeIsEmpty && $readIsEmpty){
			$row->setWrite($resourceNumber, $row->takeNextFromWait($resourceNumber));
		}
	}

	private function setProcessorFromReady($row){
		$readyArray = $row->getReadyArray();
		$readyNotEmpty = !empty($readyArray);
		$processor = $row->getProcessor();
		$processorIsEmpty = $processor == NULL;
		if($readyNotEmpty && $processorIsEmpty){
			$row->setProcessor($row->takeNextFromReady());
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
		if($row->getProcessor() != NULL){
			$task = $row->getProcessor()->getCurrentTask();
			if($task->getTimeElapsed() == $task->getDuration()){
				$row->dropFromProcessor();
			}
		}
		foreach($row->getReadArray(1) as $one){
			$task = $one->getCurrentTask();
			if($task->getTimeElapsed() == $task->getDuration()){
				$row->dropFromRead(1, $one);
			}
		}
		foreach($row->getReadArray(2) as $one){
			$task = $one->getCurrentTask();
			if($task->getTimeElapsed() == $task->getDuration()){
				$row->dropFromRead(2, $one);
			}
		}
		if(!$row->writeIsEmpty(1)){
			$task = $row->getWrite(1)->getCurrentTask();
			if($task->getTimeElapsed() == $task->getDuration()){
				$row->dropFromWrite(1);
			}
		}
		if(!$row->writeIsEmpty(2)){
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
		if($row->processorIsEmpty()){
			$row->setProcessor($process);
		} else {
			$row->appendReady($process, $this->CPUSchedulingMethod);
		}
	}

	private function setByResource($resourceNumber, $process, $row){
		$task = $process->getCurrentTask();
		$activity = $task->getActivity();
		$writeString = $row->getProcessStringFromProcessArray(
							array($row->getWrite($resourceNumber))
					   );
		if($activity == "read"){
			if($writeString == ""){
				$row->appendRead($resourceNumber, $process);
			} else {
				$row->appendWait($resourceNumber, $process);
			}
		} else if($activity == "write" && $writeString == ""){
			$readString = $row->getProcessStringFromProcessArray(
								$row->getReadArray($resourceNumber)
						  );
			if($readString == ""){
				$row->setWrite($resourceNumber, $process);
			} else {
				$row->appendWait($resourceNumber, $process);
			}
		} else if($activity == "write" && $writeString != ""){
			$row->appendWait($resourceNumber, $process);
		}
	}
}
?>
