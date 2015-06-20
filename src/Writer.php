<?php

namespace Babylon;


class Writer
{

	/**
	 * @var string
	 */
	private $outputDirectory;



	/**
	 * @param  string
	 */
	public function __construct($outputDirectory)
	{
		$this->outputDirectory = $outputDirectory;
	}



	/**
	 * {@inheritdoc}
	 */
	public function writeFile($filePath, $source)
	{
		$commonPath = $this->detectCommonPath($filePath);
		$savePath = $this->outputDirectory . str_replace($commonPath, NULL, $filePath);
		$dirname = dirname($savePath);
		if (!is_dir($dirname)) {
			mkdir($dirname, 0777, TRUE);
		}
		file_put_contents($savePath, $source);
	}



	/**
	 * @param  string
	 * @return string
	 */
	private function detectCommonPath($filePath)
	{
		$commonPath = '';
		$outputDirectory = str_split($this->outputDirectory);
		foreach (str_split($filePath) as $index => $character) {
			if (isset($outputDirectory[$index]) && $outputDirectory[$index] === $character) {
				$characterPassed = TRUE;
			} else {
				$characterPassed = FALSE;
			}
			if ($characterPassed) {
				$commonPath .= $character;
			}
		}
		$commonPath = join('/', array_slice(explode('/', $commonPath), 0, -1));
		return $commonPath;
	}

}
