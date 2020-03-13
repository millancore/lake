<p align="center"><img src="https://raw.githubusercontent.com/millancore/lake/master/logo.jpg"></p>

<p align="center">
<a href="https://travis-ci.org/millancore/lake?branch=master"><img src="https://travis-ci.org/millancore/lake.svg?branch=master" alt="Build Status"></a>
<a href="https://codeclimate.com/github/millancore/lake/maintainability"><img src="https://api.codeclimate.com/v1/badges/802c342410008cbd8c08/maintainability" /></a>
<a href="https://codeclimate.com/github/millancore/lake/test_coverage"><img src="https://api.codeclimate.com/v1/badges/802c342410008cbd8c08/test_coverage" /></a>
</p>

Lake is a command line utility that allows you to dynamically create classes and methods and create your reflexes for testing.


## Install 
```bash
composer require --dev millancore/lake
```

## Configure
Lake uses a simple configuration file to complete some features.

Basic config file `lake.yml`
```yml
mode: 'loose'
src:
  dir: 'src'
  namespace: 'Lake'

test:
  dir: 'tests'
```

### Config options

Option   | Values | Description
---------| ------ | -----------
mode | `loose`, `strict` | By default the mode is loose, but a strict mode can be defined if you want to use TDD in the right way. The strict mode creates only the test files and in the code part create the files with a .lake extension.
src.dir | `src` | This is the folder where the code are, usually is `src`, but it can change.
src.namespace | ... | This is the base name defined in the composer's autoload.
test.dir | `tests` | This is the folder where the test are, usually is `tests`, but it can change.
test.extends | ... | The tests extend by default the PHPUnit\Framework\TestCase, here you can define another class.

## Usage

lake works hand in hand with composer if you want it to **automatically add the 'use'** statements you must first run 

```bash
vendor/bin/lake dump
```
If there is more than one match, Lake will let you choose from those available on the project.


### Create a class + method

```bash
vendor/bin/lake make src/DirName/ClassName MethodName 
```

## Options


Option | Name   | Example | Description
------ | ------ | ------- | -----------
**-e** | Extends    | `-e Controller` |  Define the class of the one that extends. 
**-i** | Implements | `-i NameInterface` | It defines the interfaces implements, you can use this option many times. 
**-a** | Arguments  | `-a Int:id` | Arguments, you can use this option many times.
...    | ...       | `-a Request`| If the name of the variable is not defined Lake will create the variable name from the type.
...    | ...       | `-a ?Array:params` | Nullable argument.
**-r** | Return     | `-r Response` | Defines the type of return.
**-d** | DocBlock   | `-d 'description'` | DocBlock Description of the method.
**-v** | Visibility | `-v pub` | It defines the visibility of the method, by default it is `public`.
...    | ...       | `-v pro` | Defines visibility as `protected`.
...    | ...       | `-v pri` | Defines visibility as `private`.



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
    public function configure(array $params)
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


