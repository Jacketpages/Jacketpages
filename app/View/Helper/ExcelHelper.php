<?php

/**
 * This is a helper that uses PHPExcel v1.8.0 to produce Excel documents.
 */
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel' . DS . 'Classes' . DS . 'PHPExcel.php'));
App::import('Vendor', 'PHPExcel', array('file' => 'PHPExcel' . DS . 'PHPExcel' . DS . 'IOFactory.php'));
class ExcelHelper extends AppHelper
{
	/**
	 * The filename of the workbook.
	 */
	private $filename;

	/**
	 * The current workbook
	 */
	private $workbook;

	/**
	 * The currently selected worksheet
	 */
	private $worksheet;

	/**
	 * The current row. (one-based)
	 */
	public $row = 1;

	/**
	 * The current column. (zero-based)
	 */
	public $column = 0;

	public function create()
	{
		$this -> workbook = new PHPExcel();
		$this -> worksheet = $this -> workbook -> getActiveSheet();
	}

	/**
	 * Adds a row of data to the current worksheet.
	 * @param $data mi
	 */
	public function addRow($data)
	{
		if (is_array($data))
		{
			// The following is loop structured so that it can agnostically loop through the array.
			// Doing a normal for loop or foreach loop either means keys that aren't numbers don't 
			// get picked up or the math for the column + key is erroneous.
			$i = 0;
			foreach($data as $datum)
			{
				$this -> worksheet -> setCellValueByColumnAndRow($this -> column + $i, $this -> row, $datum);
				$i++;
			}
		}
		else if (is_scalar($data))
		{
			$this -> worksheet -> setCellValueByColumnAndRow($this -> column, $this -> row, $data);
		}
		$this -> row++;
	}

	public function generate($filename = null)
	{
		header("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($this -> workbook, "Excel2007");
		ob_end_clean();
		$objWriter -> save("php://output");
		// Need to disconnect cyclic references for cells and worksheets so the objects'
		// memory can be reclaimed.
		$this -> worksheet -> disconnectCells();
		unset($this -> worksheet);
		$this -> workbook -> disconnectWorksheets();
		unset($this -> workbook);
	}

	public function setRow($value)
	{
		$this -> row = $value;
	}

	public function setColumn($value)
	{
		$this -> column = $value;
	}

}
