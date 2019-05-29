<?php

namespace App\Helpers;

class CompareFiles{
    public $similarCount = 0;
    public $differentCount = 0;

    /**
	 * Function to create an array from file contents (text)
	 *
	 * @param $fileContents string - File contents
	 *
	 * @return array - array of lines of the file contents
	 */
	 private function filetoArray($fileContents) {
		 return file($fileContents);
	 }
}