<?php

namespace Babylon\Rules;

use Babylon\File;


class ScalarTypeHints implements Rule
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
		$openParenthesis = $file->findNext(T_OPEN_PARENTHESIS, $pointer);
		$closeParenthesis = $file->findNext(T_CLOSE_PARENTHESIS, $pointer);

		$variables = $file->findAllBetween(T_VARIABLE, $openParenthesis, $closeParenthesis);

		$file->fixer->beginChangeset();
		foreach ($variables as $variable) {
			$typeHint = $file->findPrevious(T_STRING, $variable, $openParenthesis);
			$file->fixer->replaceToken($typeHint, '');
			$file->fixer->replaceToken($typeHint + 1, '');
		}
		$file->fixer->endChangeset();

		return $file->fixer->getContents();
	}

}
