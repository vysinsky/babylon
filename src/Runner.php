<?php

namespace Babylon;

use Babylon\Rules\ReturnTypes;
use Babylon\Rules\ScalarTypeHints;
use Nette\Utils\Finder;
use Symfony\Component\Console;


class Runner extends Console\Command\Command
{

	/** @var Console\Output\Output */
	private $output;



	/**
	 * {@inheritdoc}
	 */
	protected function configure()
	{
		$this->setName('run')
			->addArgument('input', Console\Input\InputArgument::REQUIRED, 'Path to file or directory which should be translated to PHP 5.6 syntax.')
			->addArgument('output', Console\Input\InputArgument::REQUIRED, 'Path to file or directory where translated sources should be saved.');
	}



	/**
	 * {@inheritdoc}
	 */
	protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
	{
		$this->output = $output;
		$this->printHeader();

		$inputDir = $this->normalizePath($input->getArgument('input'));
		$outputDir = $this->normalizePath($input->getArgument('output'));

		$output->writeln(sprintf('Searching for PHP files in "%s".', $inputDir));
		$files = $this->findFiles($inputDir);

		$output->writeln(sprintf(
			'%s file%s found.',
			count($files),
			count($files) === 1 ? '' : 's'
		));
		$output->writeln('');

		$this->process($files, $inputDir, $outputDir);
	}



	private function printHeader()
	{
		$header = <<<TEXT

Welcome to
 _____ _____ _____ __ __ __    _____ _____
| __  |  _  | __  |  |  |  |  |     |   | |
| __ -|     | __ -|_   _|  |__|  |  | | | |
|_____|__|__|_____| |_| |_____|_____|_|___| 1.0.0 alpha

The transpiler for writing PHP 7 today


TEXT;

		$this->output->writeln($header);
	}



	/**
	 * @param  string
	 * @return string
	 */
	private function normalizePath($inputPath)
	{
		if ($inputPath[0] == '/' || $inputPath[0] == '\\') {
			return $inputPath;
		}

		return getcwd() . DIRECTORY_SEPARATOR . $inputPath;
	}



	/**
	 * @param  string
	 * @return File[]
	 */
	private function findFiles($inputDir)
	{
		/** @var File[] $files */
		$files = [];
		foreach (Finder::findFiles('*.php')->from($inputDir) as $path => $file) {
			$files[] = new File($path);
		}
		return $files;
	}



	/**
	 * @param  File []
	 * @param  string
	 * @param  string
	 */
	private function process($files, $inputDir, $outputDir)
	{
		$transpiler = new Transpiler(new Writer($outputDir), $this->listRules());
		$table = new Console\Helper\Table($this->output);
		$table->setHeaders([
			'File',
			'Result',
		]);
		$table->setStyle('borderless');

		/** @var File $file */
		foreach ($files as $file) {
			$shortName = str_replace($inputDir . DIRECTORY_SEPARATOR, NULL, $file->getFilename());
			if ($transpiler->transpile($file)) {
				$shortName = '<info>' . $shortName . '</info>';
				$info = '<info>Transpiled</info>';
			} else {
				$shortName = '<comment>' . $shortName . '</comment>';
				$info = '<comment>Nothing to do</comment>';
			}
			$table->addRow([$shortName, $info]);
		}
		$table->render();
	}



	/**
	 * @return Rules\Rule[]
	 */
	private function listRules()
	{
		return [
			new ReturnTypes(),
			new ScalarTypeHints(),
		];
	}

}
