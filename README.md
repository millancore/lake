<p align="center"><img src="https://raw.githubusercontent.com/millancore/lake/master/logo.jpg"></p>

<p align="center">
<a href="https://travis-ci.org/millancore/lake?branch=master"><img src="https://travis-ci.org/millancore/lake.svg?branch=master" alt="Build Status"></a>
<a href="https://codeclimate.com/github/millancore/lake/maintainability"><img src="https://api.codeclimate.com/v1/badges/802c342410008cbd8c08/maintainability" /></a>
<a href="https://codeclimate.com/github/millancore/lake/test_coverage"><img src="https://api.codeclimate.com/v1/badges/802c342410008cbd8c08/test_coverage" /></a>
</p>

Lake is a command line utility that allows you to dynamically create classes and methods and create your reflexes for testing, also Lake can add methods to existing classes.

One of the most powerful features of Lake is that it automatically adds **"USE"** statements, Lake can recognize internal PHP classes, project classes and those that are present as Composer dependencies.


## Install  
**PHP 7.1 or newer**
```bash
composer require --dev millancore/lake
```

## Configure

Lake takes to Composer the configuration of your project, he expects that the folder `tests` to store the reflections of test.

But not all projects have the same structure, if that's the case you can use the configuration file `lake.yml` and [Config options](#config-options).


## Usage

In order for Lake to automatically add the `USE` statements from Composer's dependencies, you must run `lake dump`. If in the process of creation there is ambiguity in the name of the class or interface Lake will give you a choice from those available.

**Only run once after installing some dependencies with Composer.**

```bash
vendor/bin/lake dump
```

### Create a class + method

```bash
vendor/bin/lake make src/DirName/ClassName MethodName 
```

**If the name of the method is not defined, the defined arguments will be those of the constructor.** 

## Options


Option | Name   | Example | Description
------ | ------ | ------- | -----------
**-e** | Extends    | `-e Controller` |  Define the class of the one that extends. 
**-a** | Arguments  | `-a Int:id` | Method argument, you can use this option many times.
...    | ...       | `-a Request`| If the name of the variable is not defined<br /> Lake will create the variable name from the type.
...    | ...       | `-a ?Array:params` | Nullable argument.
**-r** | Return     | `-r Response` | Defines the type of return.
**-d** | DocBlock   | `-d 'description'` | DocBlock Description of the method.

## Examples

```bash
vendor/bin/lake make src/App/Command/CommandLake -e Command configure -a array:params
```


classFile: `src/App/Command/CommandLake.php`
```php
<?php

namespace Lake\App\Command;

use Symfony\Component\Console\Command\Command;

class CommandLake extends Command
{

    /**
     * Undocumented function
     *
     * @param array $params
     * @return void
     */
    public function configure(array $params) : void
    {

    }


}
```
testFile: `test/App/Command/CommandLakeTest.php`

```php
<?php

use PHPUnit\Framework\TestCase;
use Lake\App\Command\CommandLake;

class CommandLakeTest extends TestCase
{

    public function testDummyConfigureMethod()
    {

    }


}
```

## Config options

Option   | Values | Description
---------| ------ | -----------
mode | `loose`, `strict` | By default the mode is loose, but a strict mode can be defined if you want to use TDD in the right way. The strict mode creates only the test files and in the code part create the files with a .lake extension.
src.dir | `src` | This is the folder where the code are, usually is `src`, but it can change.
src.namespace | ... | This is the base name defined in the composer's autoload.
test.dir | `tests` | This is the folder where the test are, usually is `tests`, but it can change.
test.extends | ... | The tests extend by default is `PHPUnit\Framework\TestCase`, but you can define another class.


