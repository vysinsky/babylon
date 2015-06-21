<?php

namespace Babylon\Rules;

use Babylon\File;


class ReturnTypes implements Rule
{

	/**
	 * {@inheritdoc}
	 */
	public function register()
	{
		return [
			T_FUNCTION,
		];
	}



	/**
	 * {@inheritdoc}
	 */
	public function execute(File $file, $pointer)
	{
		$openCurlyBrackets = $file->findNext(T_OPEN_CURLY_BRACKET, $pointer);
		$closeParenthesis = $file->findNext(T_CLOSE_PARENTHESIS, $pointer);

		$file->fixer->beginChangeset();
		foreach ($file->findAllBetween([T_STRING, T_COLON], $closeParenthesis, $openCurlyBrackets) as $token) {
			$file->fixer->replaceToken($token, '');
		}
		$file->fixer->endChangeset();

		return $file->fixer->getContents();
	}

}
