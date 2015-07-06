<?php

require __DIR__ . '/../../bootstrap.php';


testRule(new Babylon\Rules\NullCoalesceOperator(), 'ArrayIsset');
testRule(new Babylon\Rules\NullCoalesceOperator(), 'MethodCall');
