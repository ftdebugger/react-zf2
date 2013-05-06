# Zend Framework 2 and React PHP

Example of usage [zend framework 2](https://github.com/zendframework/zf2) and [react php](https://github.com/reactphp/react)

## Install

The recommended way to install react is [through composer](http://getcomposer.org).

```JSON
{
    "require": {
        "ftdebugger/react-zf2": "dev-master"
    }
}
```

## Usage

Create file at the root of project and write something like this

```PHP

<?php

chdir(__DIR__);

include_once __DIR__ . "/vendor/autoload.php";

\ReactZF\Mvc\Application::init(require 'config/application.config.php')->run();

```