<?php

namespace Babylon;

use Babylon\Rules\Rule;
use Tester\Assert;


class Transpiler
{

	/** @var array */
	private $listeners = [];

	/** @var Writer */
	private $writer;

	/** @var Rule[] */
	private $rules;



	/**
	 * @param  Writer
	 * @param  array
	 */
	public function __construct(Writer $writer, array $rules = [])
	{
		$this->rules = $rules;
		foreach ($this->rules as $rule) {
			foreach ($rule->register() as $token) {
				$this->listeners[$token][] = $rule;
			}
		}
		$this->writer = $writer;
	}



	/**
	 * @param  File
	 * @return bool if file was changed
	 */
	public function transpile(File $file)
	{
		$oldSource = $source = file_get_contents($file->getFilename());

		foreach ($file->getTokens() as $key => $token) {
			if (isset($this->listeners[$token['code']])) {
				/** @var Rule $rule */
				foreach ($this->listeners[$token['code']] as $rule) {
					$source = $rule->execute($file, $key);
				}
			}
		}

		if ($oldSource !== $source) {
			$this->writer->writeFile($file->getFilename(), $source);
			return TRUE;
		} else {
			$this->writer->writeFile($file->getFilename(), $oldSource);
			return FALSE;
		}
	}

}
