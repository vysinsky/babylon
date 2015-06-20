<?php

require __DIR__ . '/../../bootstrap.php';

use Prophecy\Argument;


$prophet = new Prophecy\Prophet();

$writer = $prophet->prophesize(Babylon\Writer::class);

$writer->writeFile(Argument::type('string'), Argument::type('string'))->will(function ($args) {
	Tester\Assert::matchFile(__DIR__ . '/../../data/php5/Calculator.php', $args[1]);
});

$transpiler = new Babylon\Transpiler($writer->reveal(), [
	new Babylon\Rules\ScalarTypeHints(),
]);

$transpiler->transpile(new Babylon\File(__DIR__ . '/../../data/php7/Calculator.php'));
$prophet->checkPredictions();
