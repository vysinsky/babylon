<?php

require __DIR__ . '/../bootstrap.php';


$outputFilePath = __DIR__ . '/output/foo/bar/Output.php';
$outputString = "<?php\n\n//Test output";

$writer = new Babylon\Writer(__DIR__ . '/output/');
$writer->writeFile($outputFilePath, $outputString);
Tester\Assert::true(file_exists($outputFilePath));
Tester\Assert::same($outputString, file_get_contents($outputFilePath));
Tester\Helpers::purge(__DIR__ . '/output');
rmdir(__DIR__ . '/output');

$writer = new Babylon\Writer(__DIR__ . '/output');
$writer->writeFile($outputFilePath, $outputString);
Tester\Assert::true(file_exists($outputFilePath));
Tester\Assert::same($outputString, file_get_contents($outputFilePath));
Tester\Helpers::purge(__DIR__ . '/output');
rmdir(__DIR__ . '/output');
