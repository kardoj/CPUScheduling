<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CPU Scheduling</title>
  <meta name="description" content="CPU Scheduling solver">
  <meta name="author" content="Kardo Jõeleht">
  <meta name="year" content="2016">
</head>
<body>
	<form action="index.php">
	<fieldset>
		<legend>Sisendid</legend>
		Protsess 1: <input type="text" name="process1" placeholder="P-1:R2-2 ..." value="<?php if(isSet($_REQUEST["process1"])){echo $_REQUEST["process1"];}?>" />
		<br>
		Protsess 2: <input type="text" name="process2" value="<?php if(isSet($_REQUEST["process2"])){echo $_REQUEST["process2"];}?>" />
		<br>
		Protsess 3: <input type="text" name="process3" value="<?php if(isSet($_REQUEST["process3"])){echo $_REQUEST["process3"];}?>" />
	</fieldset>
	<fieldset>
		<legend>Protsessori juhtimismeetod</legend>
		<?php
			$chosenMethod = "fcfs";
			if(isSet($_REQUEST["scheduling"])){
				$chosenMethod = $_REQUEST["scheduling"];
			}
			$methodAbbreviations = array("fcfs", "sjn", "srtn", "rr");
			$methodNames = array("FCFS (FIFO)", "SJN", "SRTN", "RR");
			for($i=0; $i<count($methodAbbreviations); $i++){
				$checked = "";
				if($chosenMethod == $methodAbbreviations[$i]){
					$checked = "checked='checked'";
				}
				echo "<input type='radio' name='scheduling' value={$methodAbbreviations[$i]} {$checked} /> {$methodNames[$i]}<br>";
				if($methodAbbreviations[$i] == "rr"){
					$time = "";
					if(isSet($_REQUEST["RRTime"])){
						$time = $_REQUEST["RRTime"];
					}
					echo " <input type='text' name='RRTime' value='{$time}' placeholder='Ajakvant' />";
				}
			}
		?>
	</fieldset>
	<fieldset>
		<legend>Ridu</legend>
		<input type="text" name="rows"  value="<?php if(isSet($_REQUEST["rows"])){echo $_REQUEST["rows"];} else {echo 10;} ?>" />
		<input type="submit" value="Kalkuleeri" />
	</fieldset>
	</form>
	<?php
		require_once 'schedulingMethodDefinitions.php';
		require_once 'classes/Row.class.php';
		require_once 'classes/ProcessManager.class.php';
		require_once 'classes/Process.class.php';
		require_once 'classes/Task.class.php';
		require_once 'classes/Solution.class.php';
	
		if(isSet($_REQUEST["process1"]) &&
			isSet($_REQUEST["process2"]) &&
			isSet($_REQUEST["process3"])){
			$ticks = $_REQUEST["rows"];
			$row = new Row();
			$process1 = new Process(1, getTaskArrayFromString($_REQUEST["process1"], ":"));
			$process2 = new Process(2, getTaskArrayFromString($_REQUEST["process2"], ":"));
			$process3 = new Process(3, getTaskArrayFromString($_REQUEST["process3"], ":"));
			$processArray = filterOutProcessesWithoutTasks(array($process1, $process2, $process3));
			$CPUSchedulingMethod = FCFS;
			
			$RRTime = 0;
			if($_REQUEST["scheduling"] == "fcfs"){
				$CPUSchedulingMethod = FCFS;
			} else if($_REQUEST["scheduling"] == "sjn"){
				$CPUSchedulingMethod = SJN;
			} else if($_REQUEST["scheduling"] == "srtn"){
				$CPUSchedulingMethod = SRTN;
			} else if($_REQUEST["scheduling"] == "rr"){
				$CPUSchedulingMethod = RR;
				$RRTime = $_REQUEST["RRTime"];
			}
			
			$processManager = new ProcessManager($CPUSchedulingMethod);
			$solution = new Solution();
			
			for($i=0; $i<$ticks; $i++){
				if($CPUSchedulingMethod == SJN){
					setStartingLineUpForProcessor($row, $processArray, $CPUSchedulingMethod, $processManager);
				} else if($CPUSchedulingMethod == SRTN){
					moveFromProcessorToReady($row, $CPUSchedulingMethod);
					setStartingLineUpForProcessor($row, $processArray, $CPUSchedulingMethod, $processManager);
				} else if($CPUSchedulingMethod == RR){
					if($row->getProcessor() != NULL){
						if($row->getProcessor()->getCurrentTask()->getTimeElapsed() % $RRTime == 0){
							$row->appendReady($row->takeFromProcessor(), $CPUSchedulingMethod);
						}
					}
				}
				$processManager->distributeReadyAndWait($row, $CPUSchedulingMethod);
				$processManager->distribute($processArray, $row);
				$processManager->incrementTaskTimeElapsed($row);
				$solution->addRow($row->getRowSnapShotObject());
				$processManager->dropEndedProcesses($row);
				$processManager->setNextTasks($processArray);
				
// 				echo "<pre>";
// 				print_r($process2);
// 				echo "</pre>";
				
			}
			$solution->outputResultHTML();
		}
		
		function moveFromProcessorToReady($row, $CPUSchedulingMethod){
			if($row->getProcessor() != NULL){
				$row->appendReady($row->takeFromProcessor(), $CPUSchedulingMethod);
			}
		}
		
		function setStartingLineUpForProcessor($row, $processArray, $CPUSchedulingMethod, $processManager){
			foreach($processArray as $process){
				if($process->getCurrentTask()->getActivity() == "use" && !$processManager->isDistributed($process, $row)){
					$row->appendReady($process, $CPUSchedulingMethod);
				}
			}			
		}
		
		function getTaskArrayFromString($string, $delimiter){
			$tasks = array();
			if($string != ""){
				$pieces = explode($delimiter, $string);
				foreach($pieces as $one){
					$one = trim($one);
					$activity = "";
					$resourceNumber = 0;
					$time = 0;
					$firstLetter = substr($one, 0, 1);
					if($firstLetter == "P"){
						$activity = "use";
						$resourceNumber = 0;
					} else if($firstLetter == "R"){
						$activity = "read";
						$resourceNumber = intval(substr($one, 1, 1));
					} else if($firstLetter == "W"){
						$activity = "write";
						$resourceNumber = intval(substr($one, 1, 1));
					}
					$time = intval(substr($one, -1));
					array_push($tasks, new Task($activity, $resourceNumber, $time));
				}
			}
			return $tasks;
		}
		
		function filterOutProcessesWithoutTasks($processArray){
			$filtered = array();
			foreach($processArray as $process){
				if($process->getTaskCount() > 0){
					array_push($filtered, $process);
				}
			}
			return $filtered;
		}
	?>
</body>
</html>