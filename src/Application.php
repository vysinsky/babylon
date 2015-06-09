<?php

namespace Babylon;

use Symfony\Component\Console;


class Application extends Console\Application
{

	/**
	 * {@inheritdoc}
	 */
	protected function getCommandName(Console\Input\InputInterface $input)
	{
		return 'run';
	}



	/**
	 * {@inheritdoc}
	 */
	protected function getDefaultCommands()
	{
		$defaultCommands = parent::getDefaultCommands();
		$defaultCommands[] = new Runner();
		return $defaultCommands;
	}



	/**
	 * {@inheritdoc}
	 */
	public function getDefinition()
	{
		$inputDefinition = parent::getDefinition();
		$inputDefinition->setArguments();
		return $inputDefinition;
	}

}
