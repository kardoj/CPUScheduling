<?php
class Process{
    
	private $number;
	private $running;
	private $orderedTaskArray;
 	private $currentTaskIndex;
	
	public function Process($number, $orderedTaskArray){
		$this->number = $number;
		$this->setRunning(count($orderedTaskArray));
		$this->orderedTaskArray = $orderedTaskArray;
		$this->currentTaskIndex = 0;
	}
	
	private function setRunning($taskCount){
		if($taskCount > 0){
			$this->running = true;
		} else {
			$this->running = false;
		}		
	}
	
	public function setNextTask(){
		if($this->currentTaskIndex < count($this->orderedTaskArray)-1){
			$this->currentTaskIndex++;
		} else {
			$this->stopRunning();
		}
	}
	
	public function stopRunning(){
		$this->running = false;
	}
	
	public function isRunning(){
		return $this->running;
	}
	
	public function getProcessNumber(){
		return $this->number;
	}
    
	public function getCurrentTask(){
		return $this->orderedTaskArray[$this->currentTaskIndex];
	}
	
	public function getTaskCount(){
		return count($this->orderedTaskArray);
	}
}
?>