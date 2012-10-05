<?php
/**
 * @author Stephen Roca
 * @since 10/01/2012
 */

class CsvHelper extends AppHelper 
{
	public $buffer;
	public $filename = 'Export.csv';
	
	public function start()
	{
		$this->buffer = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
	}
	
	public function append($row = array())
	{
		fputcsv($this->buffer, $row, ',', '"');
	}
	
	function setFilename($filename) {
		$this->filename = $filename;
		if (strtolower(substr($this->filename, -4)) != '.csv') {
			$this->filename .= '.csv';
		}
	}
	
	public function end()
	{
		header("Content-type:application/vnd.ms-excel");
		header("Content-disposition:attachment;filename=".$this->filename);
		$file = rewind($this -> buffer);
		return stream_get_contents($this -> buffer);
	}
}
