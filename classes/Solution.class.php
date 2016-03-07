<?php
class Solution{
	private $rows = array();
	
	public function Solution(){}
	
	public function addRow($rowSnapshotObject){
		array_push($this->rows, $rowSnapshotObject);
	}
	
	public function outputResultHTML(){
		$this->outputTableHeader();
		$rowNumber = 1;
		foreach($this->rows as $row){
			
			echo "
					<tr>
						<td>{$rowNumber}</td>
						<td>{$row->ready}</td>
						<td>{$row->processor}</td>
						<td>{$row->wait1}</td>
						<td>{$row->read1}</td>
						<td>{$row->write1}</td>
						<td>{$row->wait2}</td>
						<td>{$row->read2}</td>
						<td>{$row->write2}</td>
					</tr>
					";
			$rowNumber++;
			
		}
		
		$this->outputTableFooter();
	}
	
	private function outputTableFooter(){
		echo "	</table>♥
			</fieldset>";
	}
	
	private function outputTableHeader(){
		echo "<fieldset>
				<legend>Vastus</legend>
					<table border='1'>
		                <tr>
		                    <th rowspan='2'>Time</th>
		                    <th rowspan='2'>Ready</th>
		                    <th rowspan='2'>Processor</th>
		                    <th colspan='3'>Resource 1</th>
		                    <th colspan='3'>Resource 2</th>
		                </tr>
		                <tr>
		                    <th>Wait</th>
		                    <th>Read</th>
		                    <th>Write</th>
		                    <th>Wait</th>
		                    <th>Read</th>
		                    <th>Write</th>
		                </tr>";
	}
}
?>