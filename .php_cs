<?php

$finder = \Symfony\CS\Finder\DefaultFinder::create()
    ->exclude("vendor")
    ->in(__DIR__ . '/src');

return \Symfony\CS\Config\Config::create()
    ->finder($finder);