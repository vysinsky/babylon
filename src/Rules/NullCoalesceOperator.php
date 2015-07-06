<?php

namespace Babylon\Rules;

use Babylon\File;


class NullCoalesceOperator implements Rule
{

	/**
	 * {@inheritdoc}
	 */
	public function register()
	{
		return [
			T_INLINE_THEN,
		];
	}



	/**
	 * {@inheritdoc}
	 */
	public function execute(File $file, $pointer)
	{
		$tokens = $file->getTokens();
		if ($tokens[$pointer + 1]['code'] !== T_INLINE_THEN) {
			return $file->fixer->getContents();
		}

		$equal = $file->findPrevious(T_EQUAL, $pointer);

		$file->fixer->beginChangeset();
		$leftSide = '';
		for ($i = $equal + 1; $i < $pointer; $i++) {
			$file->fixer->replaceToken($i, '');
			$leftSide .= $tokens[$i]['content'];
		}
		$leftSide = ' isset(' . trim($leftSide) . ') ? ' . trim($leftSide) . ' : ';

		$file->fixer->replaceToken($pointer, '');
		$file->fixer->replaceToken($pointer + 1, '');
		if ($tokens[$pointer + 2]['code'] === T_WHITESPACE) {
			$file->fixer->replaceToken($pointer + 2, '');
		}

		$file->fixer->addContentBefore($pointer, $leftSide);

		$file->fixer->endChangeset();

		return $file->fixer->getContents();
	}

}
