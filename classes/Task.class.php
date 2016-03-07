<?php
class Task{	
	private $activity; // "use", "read", "write"
	private $resourceNr; // 0 is processor
	private $duration;
	private $timeElapsed;
	
	public function Task($activity, $resourceNr, $duration){
		$this->activity = $activity;
		$this->duration = $duration;
		$this->resourceNr = $resourceNr;
		$this->timeElapsed = 0;
	}
	
	public function incrementTimeElapsed(){
		if($this->timeElapsed < $this->duration){
			$this->timeElapsed++;
		}
	}
	
	public function getTimeElapsed(){
		return $this->timeElapsed;
	}
	
	public function resetTimeElapsed(){
		$this->timeElapsed = 0;
	}
	
	public function getResourceNumber(){
		return $this->resourceNr;
	}
	
	public function getActivity(){
		return $this->activity;
	}	
	
	public function getDuration(){
		return $this->duration;
	}
	
	// Needed for testing. NEVER used in main code!
	public function setTimeElapsed($time){
		$this->timeElapsed = $time;
	}
}
?>