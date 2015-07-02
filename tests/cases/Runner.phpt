<?php

require __DIR__ . '/../bootstrap.php';

Tester\Environment::$checkAssertions = FALSE;

$application = new Symfony\Component\Console\Application();
$application->add(new Babylon\Runner());

$command = $application->find('run');
$commandTester = new Symfony\Component\Console\Tester\CommandTester($command);
// Accepts directory
$commandTester->execute([
	'command' => $command->getName(),
	'input' => realpath(__DIR__ . '/../data/php7'),
	'output' => __DIR__ . '/output',
]);

Tester\Helpers::purge(__DIR__ . '/output');
rmdir(__DIR__ . '/output');

// Accepts single file
$commandTester->execute([
	'command' => $command->getName(),
	'input' => realpath(__DIR__ . '/../data/php7/ReturnTypes.php'),
	'output' => __DIR__ . '/output',
]);

Tester\Helpers::purge(__DIR__ . '/output');
rmdir(__DIR__ . '/output');
