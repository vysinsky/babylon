<?php

require __DIR__ . '/../vendor/autoload.php';

use Prophecy\Argument;


Tester\Environment::setup();

/**
 * @param  Babylon\Rules\Rule
 * @param  string
 */
function testRule(Babylon\Rules\Rule $rule, $fileSuffix = '')
{
	$ruleReflection = new ReflectionClass($rule);
	$prophet = new Prophecy\Prophet();
	$writer = $prophet->prophesize(Babylon\Writer::class);
	$writer->writeFile(Argument::type('string'), Argument::type('string'))->will(function ($args) use ($ruleReflection, $fileSuffix) {
		Tester\Assert::matchFile(__DIR__ . '/data/php5/' . $ruleReflection->getShortName() . ($fileSuffix ? '.' . $fileSuffix : '') . '.php', $args[1]);
	});

	$transpiler = new Babylon\Transpiler($writer->reveal(), [
		$rule,
	]);

	$transpiler->transpile(new Babylon\File(__DIR__ . '/data/php7/' . $ruleReflection->getShortName() . ($fileSuffix ? '.' . $fileSuffix : '') . '.php'));
	$prophet->checkPredictions();
}
