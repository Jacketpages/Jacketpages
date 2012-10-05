<?php
/**
 * @author Stephen Roca
 * @since 10/01/2012
 */

class CsvComponent extends Component 
{
	public $buffer;
	
	public function open()
	{
		$this->buffer = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
	}
	
	public function append()
	{
		fputcsv($this->buffer, $row, $this->delimiter, $this->enclosure);
	}
	
	public function render()
	{
		header("Content-type:application/vnd.ms-excel");
		header("Content-disposition:attachment;filename=".$this->filename);
		$file = rewind($this -> buffer);
		return $this -> output(stream_get_contents($file));
	}
}
