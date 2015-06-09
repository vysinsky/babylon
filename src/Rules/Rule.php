<?php

namespace Babylon\Rules;

use Babylon\File;


interface Rule
{

	/**
	 * @return array of tokens for which rule should listen
	 */
	function register();



	/**
	 * @param  File
	 * @param  int
	 * @return string changed source code
	 */
	function execute(File $file, $pointer);

}
