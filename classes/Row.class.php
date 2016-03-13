<?php
class Row{
	private $ready = array();
	private $processor = NULL;
	private $wait = array(NULL, array(), array()); // $wait[1] = resource 1 wait, $wait[2] = resource 2 wait
	private $read = array(NULL, array(), array());
	private $write = array(NULL, NULL, NULL);
	
	public function Row(){}
	
	public function appendReady($process, $CPUSchedulingMethod){
		if($CPUSchedulingMethod == FCFS){
			array_unshift($this->ready, $process);
		} else if($CPUSchedulingMethod == SJN){
			array_push($this->ready, $process);
			usort($this->ready, "cmpSJN");
		} else if($CPUSchedulingMethod == SRTN){
			array_push($this->ready, $process);
			usort($this->ready, "cmpSRTN");
		} else if($CPUSchedulingMethod == RR){
			array_unshift($this->ready, $process);
		}
	}
		
	public function getReadyArray(){
		return $this->ready;
	}
	
	public function takeNextFromReady(){
		$nextFromReady = $this->ready[count($this->ready)-1];
		array_pop($this->ready);
		return $nextFromReady;
	}
	
	public function processorIsEmpty(){
		return $this->isEmpty($this->processor);
	}
	
	public function setProcessor($process){
		$this->processor = $process;
	}
	
	public function getProcessor(){
		return $this->processor;
	}
	
	public function takeFromProcessor(){
		$process = $this->processor;
		$this->processor = NULL;
		return $process;
	}
	
	public function dropFromProcessor(){
		$this->processor = NULL;
	}
	
	public function appendWait($which, $process){
		array_unshift($this->wait[$which], $process);
	}
	
	public function getWaitArray($which){
		return $this->wait[$which];
	}
	
	public function getNextFromWait($which){
		$nextFromWait = $this->wait[$which][count($this->wait[$which])-1];
		return $nextFromWait;
	}
	
	public function takeNextFromWait($which){
		$nextFromWait = $this->wait[$which][count($this->wait[$which])-1];
		array_pop($this->wait[$which]);
		return $nextFromWait;
	}
	
	public function readIsEmpty($which){
		return $this->isEmpty($this->getReadArray($which));
	}
	
	public function appendRead($which, $process){
		array_unshift($this->read[$which], $process);
	}
	
	public function getReadArray($which){
		return $this->read[$which];
	}
	
	public function dropFromRead($which, $process){
		$readArray = $this->getReadArray($which);
		$newReadArray = array();
		foreach($readArray as $one){
			if($one != $process){
				array_push($newReadArray, $one);
			}
		}
		$this->read[$which] = $newReadArray;
	}
	
	public function writeIsEmpty($which){
		return $this->isEmpty($this->write[$which]);
	}
	
	public function setWrite($which, $process){
		$this->write[$which] = $process;
	}
	
	public function getWrite($which){
		return $this->write[$which];
	}
	
	public function dropFromWrite($which){
		$this->write[$which] = NULL;
	}
	
	public function getRowSnapshotObject(){
		$snap = new StdClass();
		$snap->ready = $this->getProcessStringFromProcessArray($this->getReadyArray());
		$snap->processor = $this->getProcessStringFromProcessArray(array($this->getProcessor()));
		$snap->wait1 = $this->getProcessStringFromProcessArray($this->getWaitArray(1));
		$snap->read1 = $this->getProcessStringFromProcessArray($this->getReadArray(1));
		$snap->write1 = $this->getProcessStringFromProcessArray(array($this->getWrite(1)));
		$snap->wait2 = $this->getProcessStringFromProcessArray($this->getWaitArray(2));
		$snap->read2 = $this->getProcessStringFromProcessArray($this->getReadArray(2));
		$snap->write2 = $this->getProcessStringFromProcessArray(array($this->getWrite(2)));
		return $snap;
	}
	
	public function getProcessStringFromProcessArray($processArray){
		$returnString = "";
		foreach($processArray as $process){
			if($process != NULL){
				$returnString .= strval($process->getProcessNumber());
			}
		}
		return $returnString;
	}
	
	private function isEmpty($var){
		if(empty($var)){
			return true;
		}
		return false;
	}
}

function cmpSJN($a, $b){
	$aTask = $a->getCurrentTask();
	$aTime = $aTask->getDuration();
	$bTask = $b->getCurrentTask();
	$bTime = $bTask->getDuration();
	$aProcessNumber = $a->getProcessNumber();
	$bProcessNumber = $b->getProcessNumber();
	if($aTime == $bTime){
		return $aProcessNumber > $bProcessNumber;
	}
	return $aTime < $bTime;
}

function cmpSRTN($a, $b){
	$aTask = $a->getCurrentTask();
	$aRemainingTime = $aTask->getDuration() - $aTask->getTimeElapsed();
	$bTask = $b->getCurrentTask();
	$bRemainingTime = $bTask->getDuration() - $bTask->getTimeElapsed();
	$aProcessNumber = $a->getProcessNumber();
	$bProcessNumber = $b->getProcessNumber();
	if($aRemainingTime == $bRemainingTime){
		return $aProcessNumber < $bProcessNumber;
	}
	return $aRemainingTime < $bRemainingTime;
}
?>