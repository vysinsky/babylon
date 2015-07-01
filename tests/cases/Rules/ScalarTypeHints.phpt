<?php

require __DIR__ . '/../../bootstrap.php';

testRule(new Babylon\Rules\ScalarTypeHints());
testRule(new Babylon\Rules\ScalarTypeHints(), 'noTypeHint');
