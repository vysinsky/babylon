<?php

namespace Babylon;

use PHP_CodeSniffer;
use PHP_CodeSniffer_File;


class File extends PHP_CodeSniffer_File
{

	/**
	 * @param  string
	 */
	public function __construct($file)
	{
		parent::__construct($file, [], [], new PHP_CodeSniffer());
	}



	/**
	 * {@inheritdoc}
	 */
	public function getTokens()
	{
		$tokens = parent::getTokens();
		if (empty($tokens)) {
			$this->start();
		}

		return parent::getTokens();
	}



	/**
	 * @param  array|int
	 * @param  int
	 * @param  int
	 * @return array
	 */
	public function findAllBetween($types, $start, $end)
	{
		$tokens = [];
		do {
			$token = $this->findNext($types, $start, $end);
			if ($token) {
				$tokens[] = $token;
				$start = $token + 1;
			}
		} while ($token);

		return $tokens;
	}

}
