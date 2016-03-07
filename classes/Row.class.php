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
	
	public function setProcessor($process){
		$this->processor = $process;
	}
	
	public function appendRead($which, $process){
		array_unshift($this->read[$which], $process);
	}
	
	public function setWrite($which, $process){
		$this->write[$which] = $process;
	}
	
	public function getWrite($which){
		return $this->write[$which];
	}
	
	public function getWaitArray($which){
		return $this->wait[$which];
	}
	
	public function appendWait($which, $process){
		array_unshift($this->wait[$which], $process);
	}
	
	public function takeNextFromWait($which){
		$nextFromWait = $this->wait[$which][count($this->wait[$which])-1];
		array_pop($this->wait[$which]);
		return $nextFromWait;
	}
	
	public function getNextFromWait($which){
		$nextFromWait = $this->wait[$which][count($this->wait[$which])-1];
		return $nextFromWait;
	}
	
	public function dropFromWrite($which){
		$this->write[$which] = NULL;
	}
	
	public function getReadyArray(){
		return $this->ready;
	}
	
	public function getProcessor(){
		return $this->processor;
	}
	
	public function takeFromProcessor(){
		$process = $this->processor;
		$this->processor = NULL;
		return $process;
	}
	
	public function getReadArray($which){
		return $this->read[$which];
	}
	
	public function getProcessorString(){
		if(empty($this->processor)){
			return "";
		} else {
			return (string) $this->processor->getProcessNumber();
		}
	}
	
	public function getReadyString(){
		if(empty($this->ready)){
			return "";
		} else {
			$string = "";
			foreach($this->ready as $process){
				$string .= (string) $process->getProcessNumber();
			}
			return $string;
		}		
	}
	
	public function getWaitString($which){
		if(empty($this->wait[$which])){
			return "";
		} else {
			$string = "";
			foreach($this->wait[$which] as $process){
				$string .= (string) $process->getProcessNumber();
			}
			return $string;
		}
	}
	
	public function getReadString($which){
		if(empty($this->read[$which])){
			return "";
		} else {
			$string = "";
			foreach($this->read[$which] as $process){
				$string .= (string) $process->getProcessNumber();
			}
			return $string;
		}
	}
	
	public function getWriteString($which){
		if(empty($this->write[$which])){
			return "";
		} else {
			return (string) $this->write[$which]->getProcessNumber();
		}
	}
	
	public function dropFromProcessor(){
		$this->processor = NULL;
	}
	
	public function takeNextFromReady(){
// 		if($CPUSchedulingMethod == FCFS){
		$nextFromReady = $this->ready[count($this->ready)-1];
		array_pop($this->ready);
// 		} else if($CPUSchedulingMethod == SJN){
// 			$shortestTimeProcessIndex = 0;
// 			for($i = 0; $i<count($this->ready); $i++){
// 				if($i != $shortestTimeProcessIndex){
// 					if($this->ready[$i]->getCurrentTask()->getDuration() < $this->ready[$shortestTimeProcessIndex]->getCurrentTask()->getDuration()){
// 						$shortestTimeProcessIndex = $i;
// 					} else if($this->ready[$i]->getCurrentTask()->getDuration() == $this->ready[$shortestTimeProcessIndex]->getCurrentTask()->getDuration()){
// 						if($this->ready[$i]->getProcessNumber() < $this->ready[$shortestTimeProcessIndex]->getProcessNumber()){
// 							$shortestTimeProcessIndex = $i;						
// 						}
// 					}
// 				}
// 			}
// 			$nextFromReady = $this->ready[$shortestTimeProcessIndex];
// 			$new_ready = array();
// 			for($i = 0; $i<count($this->ready); $i++){
// 				if($i != $shortestTimeProcessIndex){
// 					array_push($new_ready, $this->ready[$i]);
// 				}
// 			}
// 			$this->ready = $new_ready;
// 		}
		return $nextFromReady;
	}
	
	public function dropNextFromRead($which){
		array_pop($this->read[$which]);
	}
	
	public function getRowSnapshotObject(){
		$snap = new StdClass();
		$snap->ready = $this->getReadyString();
		$snap->processor = $this->getProcessorString();
		$snap->wait1 = $this->getWaitString(1);
		$snap->read1 = $this->getReadString(1);
		$snap->write1 = $this->getWriteString(1);
		$snap->wait2 = $this->getWaitString(2);
		$snap->read2 = $this->getReadString(2);
		$snap->write2 = $this->getWriteString(2);
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